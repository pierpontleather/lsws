<?php

require_once('blowfish.php');

class CAuthorizer
{

    private $_id;
    private $_id_field;
    private $_pass;
    private $_pass_field;
    private static $_instance = null;

    // prevent an object from being constructed
    private function __construct()
    {
        $label = strtoupper(substr(md5(SERVER_ROOT), 0, 16));
        $this->_id_field = 'LSID' . $label;
        $this->_pass_field = 'LSPA' . $label;

        session_name('LSUI' . $label); // to prevent conflicts with other app sessions
        session_start();

        if (!array_key_exists('changed', $_SESSION)) {
            $_SESSION['changed'] = false;
        }

        if (!array_key_exists('valid', $_SESSION)) {
            $_SESSION['valid'] = false;
        }

        if (!array_key_exists('token', $_SESSION)) {
            $_SESSION['token'] = microtime();
        }

        if ($_SESSION['valid']) {

            if (array_key_exists('lastaccess', $_SESSION)) {

                if (isset($_SESSION['timeout']) && $_SESSION['timeout'] > 0 && time() - $_SESSION['lastaccess'] > $_SESSION['timeout']) {
                    $this->clear();
                    if (strpos($_SERVER['SCRIPT_NAME'], '/view/') !== false) {
                        echo json_encode(array('login_timeout' => 1));
                    } else {
                        header("location:/login.php?timedout=1");
                    }
                    die();
                }

                $this->_id = UIBase::GrabGoodInput('cookie', $this->_id_field);
                $this->_pass = UIBase::GrabInput('cookie', $this->_pass_field);
            }
            if (!defined('NO_UPDATE_ACCESS'))
                $this->updateAccessTime();
        }
    }

    public static function singleton()
    {

        if (!isset(self::$_instance)) {
            $c = __CLASS__;
            self::$_instance = new $c;
        }

        return self::$_instance;
    }

    public static function Authorize()
    {
        $auth = CAuthorizer::singleton();
        if (!$auth->IsValid()) {
            $auth->clear();
            if (strpos($_SERVER['SCRIPT_NAME'], '/view/') !== false) {
                echo json_encode(array('login_timeout' => 1));
            } else {
                header("location:/login.php");
            }
            die();
        }
    }

    public function IsValid()
    {
        return !( ($_SESSION['valid'] !== true) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false));
    }

    public static function GetToken()
    {
        return $_SESSION['token'];
    }

    public static function SetTimeout($timeout)
    {
        $_SESSION['timeout'] = (int) $timeout;
    }

    public static function HasSetTimeout()
    {
        return (isset($_SESSION['timeout']) && $_SESSION['timeout'] >= 60);
    }

    public function GetCmdHeader()
    {
        if (isset($_SESSION['secret']) && is_array($_SESSION['secret'])) {
            $uid = PMA_blowfish_decrypt($this->_id, $_SESSION['secret'][0]);
            $password = PMA_blowfish_decrypt($this->_pass, $_SESSION['secret'][1]);
            return "auth:$uid:$password\n";
        } else
            return '';
    }

    public function Reauthenticate()
    {
        $uid = PMA_blowfish_decrypt($this->_id, $_SESSION['secret'][0]);
        $password = PMA_blowfish_decrypt($this->_pass, $_SESSION['secret'][1]);
        $auth = $this->authUser($uid, $password);

        if (!$auth) {
            $this->clear();
            if (strpos($_SERVER['SCRIPT_NAME'], '/view/') !== false) {
                echo json_encode(array('login_timeout' => 1));
            } else {
                header("location:/login.php?timedout=1");
            }
            die();
        }

    }

    public function GenKeyPair()
    {
        $keyfile = Service::ServiceData(SInfo::DATA_ADMIN_KEYFILE);
        $mykeys = null;
        $keyLength = 512;

        if (file_exists($keyfile)) {
            $str = file_get_contents($keyfile);
            if ($str != '')
                $mykeys = unserialize($str);
        }
        if ($mykeys == null) {
            $jCryption = new jCryption();
            $keys = $jCryption->generateKeypair($keyLength);
            $e_hex = $jCryption->dec2string($keys['e'], 16);
            $n_hex = $jCryption->dec2string($keys['n'], 16);
            $mykeys = array('e_hex' => $e_hex, 'n_hex' => $n_hex, 'd_int' => $keys['d'], 'n_int' => $keys['n']);
            $serialized_str = serialize($mykeys);
            file_put_contents($keyfile, $serialized_str);
            chmod($keyfile, 0600);
        }
        $_SESSION['d_int'] = $mykeys['d_int'];
        $_SESSION['n_int'] = $mykeys['n_int'];

        return '{"e":"' . $mykeys['e_hex'] . '","n":"' . $mykeys['n_hex'] . '","maxdigits":"' . intval($keyLength * 2 / 16 + 3) . '"}';
    }

    public function ShowLogin($is_https, &$msg)
    {
        $timedout = UIBase::GrabInput('get', 'timedout', 'int');
        $logoff = UIBase::GrabInput('get', 'logoff', 'int');
        $msg = '';

        if ($timedout == 1 || $logoff == 1) {
            $this->clear();

            if ($timedout == 1) {
                $msg = DMsg::Err('err_sessiontimeout');
            } else {
                $msg = DMsg::Err('err_loggedoff');
            }
        } else if ($this->IsValid()) {
            return false;
        }

        $userid = null;
        $pass = null;

        if (isset($_POST['jCryption'])) {
            $jCryption = new jCryption();
            $var = $jCryption->decrypt($_POST['jCryption'], $_SESSION['d_int'], $_SESSION['n_int']);
            unset($_SESSION['d_int']);
            unset($_SESSION['n_int']);
            parse_str($var, $result);
            $userid = $result['userid'];
            $pass = $result['pass'];
        } else if ($is_https && isset($_POST['userid'])) {
            $userid = UIBase::GrabInput('POST', 'userid');
            $pass = UIBase::GrabInput('POST', 'pass');
        }

        if ($userid != null && ($this->authenticate($userid, $pass) === true)) {
                return false;
            }
        $msg = DMsg::Err('err_login');
        return true;
    }

    private function updateAccessTime($secret = null)
    {
        $_SESSION['lastaccess'] = time();
        if (isset($secret)) {
            $_SESSION['valid'] = true;
            $_SESSION['secret'] = $secret;
        }
    }

    private function clear()
    {
        session_destroy();
        session_unset();
        $outdated = time() - 3600 * 24 * 30;
        setcookie($this->_id_field, '', $outdated, "/");
        setcookie($this->_pass_field, '', $outdated, "/");
        setcookie(session_name(), '', $outdated, "/");
    }

    private function authUser($authUser, $authPass)
    {
        $auth = false;
        $authUser1 = escapeshellcmd($authUser);

        if (($authUser === $authUser1)
                && !preg_match('/[:\/]/', $authUser)
                && strlen($authUser) && strlen($authPass)) {
            $filename = SERVER_ROOT . 'admin/conf/htpasswd';
            $fd = fopen($filename, 'r');
            if (!$fd) {
                return false;
            }

            $all = trim(fread($fd, filesize($filename)));
            fclose($fd);

            $lines = explode("\n", $all);
            foreach ($lines as $line) {
                list($user, $pass) = explode(':', $line);
                if ($user === $authUser) {
                    if ($pass[0] != '$')
                        $salt = substr($pass, 0, 2);
                    else
                        $salt = substr($pass, 0, 12);
                    $encypt = crypt($authPass, $salt);
                    if ($pass == $encypt) {
                        $auth = true;
                        break;
                    }
                }
            }
        }
        return $auth;
    }

    private function authenticate($authUser, $authPass)
    {
        $auth = $this->authUser($authUser, $authPass);

        if ($auth) {
            $temp = gettimeofday();
            $start = (int) $temp['usec'];
            $secretKey0 = mt_rand() . $start . mt_rand();
            $secretKey1 = mt_rand() . mt_rand() . $start;

            $domain = $_SERVER['HTTP_HOST'];
            if ($pos = strpos($domain, ':')) {
                $domain = substr($domain, 0, $pos);
            }
            $secure = !empty($_SERVER['HTTPS']);
            $httponly = true;

            setcookie($this->_id_field, PMA_blowfish_encrypt($authUser, $secretKey0), 0, "/", $domain, $secure, $httponly);
            setcookie($this->_pass_field, PMA_blowfish_encrypt($authPass, $secretKey1), 0, "/", $domain, $secure, $httponly);

            $this->updateAccessTime(array($secretKey0, $secretKey1));
        } else {
            $this->emailFailedLogin(escapeshellcmd($authUser));
        }

        return $auth;
    }

    private function emailFailedLogin($authUser)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = UIBase::GrabGoodInput('server', 'SCRIPT_URI');

        error_log("[WebAdmin Console] Failed Login Attempt - username:$authUser ip:$ip url:$url\n");

        $emails = Service::ServiceData(SInfo::DATA_ADMIN_EMAIL);
        if ($emails != null) {
            $date = date("F j, Y, g:i a");

            $repl = array('%%date%%'     => $date,
                '%%authUser%%' => $authUser,
                '%%ip%%'       => $ip,
                '%%url%%'      => $url);

            $subject = DMsg::UIStr('mail_failedlogin');
            $contents = DMsg::UIStr('mail_failedlogin_c', $repl);
            mail($emails, $subject, $contents);
        }
    }

}
