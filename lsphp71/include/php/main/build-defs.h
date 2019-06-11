/*                                                                -*- C -*-
   +----------------------------------------------------------------------+
   | PHP Version 7                                                        |
   +----------------------------------------------------------------------+
   | Copyright (c) 1997-2018 The PHP Group                                |
   +----------------------------------------------------------------------+
   | This source file is subject to version 3.01 of the PHP license,      |
   | that is bundled with this package in the file LICENSE, and is        |
   | available through the world-wide-web at the following url:           |
   | http://www.php.net/license/3_01.txt                                  |
   | If you did not receive a copy of the PHP license and are unable to   |
   | obtain it through the world-wide-web, please send a note to          |
   | license@php.net so we can mail you a copy immediately.               |
   +----------------------------------------------------------------------+
   | Author: Stig Sæther Bakken <ssb@php.net>                             |
   +----------------------------------------------------------------------+
*/

/* $Id$ */

#define CONFIGURE_COMMAND " './configure'  '--prefix=/usr/local/lsws/lsphp71' '--disable-cgi' '--enable-cli' '--enable-phpdbg=no' '--with-litespeed' '--with-config-file-path=/usr/local/lsws/lsphp71/etc/php/7.1/litespeed/' '--with-config-file-scan-dir=/usr/local/lsws/lsphp71/etc/php/7.1/mods-available/' '--build=x86_64-linux-gnu' '--host=x86_64-linux-gnu' '--libdir=${prefix}/lib/php' '--libexecdir=${prefix}/lib/php' '--datadir=${prefix}/share/php/7.1' '--program-suffix=7.1' '--disable-all' '--disable-debug' '--disable-rpath' '--disable-static' '--with-pic' '--with-layout=GNU' '--without-pear' '--enable-bcmath' '--enable-calendar' '--enable-ctype' '--enable-dom' '--with-enchant' '--enable-exif' '--with-gettext' '--with-gmp' '--enable-fileinfo' '--enable-filter' '--enable-ftp' '--enable-hash' '--with-iconv' '--enable-mbregex' '--enable-mbregex-backtrack' '--enable-mbstring' '--enable-phar' '--enable-posix' '--with-mcrypt' '--enable-mysqlnd-compression-support' '--with-zlib-dir' '--with-openssl=yes' '--with-libedit' '--enable-libxml' '--enable-session' '--enable-simplexml' '--enable-soap' '--enable-sockets' '--enable-tokenizer' '--enable-xml' '--enable-xmlreader' '--enable-xmlwriter' '--with-xsl' '--with-mhash=yes' '--enable-sysvmsg' '--enable-sysvsem' '--enable-sysvshm' '--enable-zip' '--with-system-tzdata' '--with-gd' '--enable-gd-native-ttf' '--with-jpeg-dir' '--with-xpm-dir' '--with-png-dir' '--with-freetype-dir' '--with-vpx-dir' '--with-mysql-sock=/var/run/mysqld/mysqld.sock' '--disable-dtrace' '--enable-pdo' '--enable-mysqlnd' '--enable-pcntl' '--with-recode=shared,/usr' '--with-sqlite3=shared,/usr' '--with-pdo-sqlite=shared,/usr' '--with-pdo-dblib=shared,/usr' '--with-ldap=shared,/usr' '--with-ldap-sasl=/usr' '--enable-intl=shared' '--with-snmp=shared,/usr' '--enable-json=shared' '--with-pgsql=shared,/usr' '--with-pdo-pgsql=shared,/usr' '--enable-opcache' '--enable-opcache-file' '--enable-huge-code-pages' '--with-imap=shared,/usr' '--with-kerberos' '--with-imap-ssl=yes' '--with-mysqli=shared,mysqlnd' '--with-pdo-mysql=shared,mysqlnd' '--with-tidy=shared,/usr' '--with-pspell=shared,/usr' '--with-curl=shared,/usr' 'build_alias=x86_64-linux-gnu' 'host_alias=x86_64-linux-gnu' 'CFLAGS=-g -O2 -fdebug-prefix-map=/tmp/buildd/php7.1-7.1.26=. -fstack-protector-strong -Wformat -Werror=format-security -O2 -Wall -fsigned-char -fno-strict-aliasing -g'"
#define PHP_ADA_INCLUDE		""
#define PHP_ADA_LFLAGS		""
#define PHP_ADA_LIBS		""
#define PHP_APACHE_INCLUDE	""
#define PHP_APACHE_TARGET	""
#define PHP_FHTTPD_INCLUDE      ""
#define PHP_FHTTPD_LIB          ""
#define PHP_FHTTPD_TARGET       ""
#define PHP_CFLAGS		"$(CFLAGS_CLEAN) -prefer-non-pic -static"
#define PHP_DBASE_LIB		""
#define PHP_BUILD_DEBUG		""
#define PHP_GDBM_INCLUDE	""
#define PHP_IBASE_INCLUDE	""
#define PHP_IBASE_LFLAGS	""
#define PHP_IBASE_LIBS		""
#define PHP_IFX_INCLUDE		""
#define PHP_IFX_LFLAGS		""
#define PHP_IFX_LIBS		""
#define PHP_INSTALL_IT		""
#define PHP_IODBC_INCLUDE	""
#define PHP_IODBC_LFLAGS	""
#define PHP_IODBC_LIBS		""
#define PHP_MSQL_INCLUDE	""
#define PHP_MSQL_LFLAGS		""
#define PHP_MSQL_LIBS		""
#define PHP_MYSQL_INCLUDE	"@MYSQL_INCLUDE@"
#define PHP_MYSQL_LIBS		"@MYSQL_LIBS@"
#define PHP_MYSQL_TYPE		"@MYSQL_MODULE_TYPE@"
#define PHP_ODBC_INCLUDE	""
#define PHP_ODBC_LFLAGS		""
#define PHP_ODBC_LIBS		""
#define PHP_ODBC_TYPE		""
#define PHP_OCI8_SHARED_LIBADD 	""
#define PHP_OCI8_DIR			""
#define PHP_OCI8_ORACLE_VERSION		""
#define PHP_ORACLE_SHARED_LIBADD 	"@ORACLE_SHARED_LIBADD@"
#define PHP_ORACLE_DIR				"@ORACLE_DIR@"
#define PHP_ORACLE_VERSION			"@ORACLE_VERSION@"
#define PHP_PGSQL_INCLUDE	""
#define PHP_PGSQL_LFLAGS	""
#define PHP_PGSQL_LIBS		""
#define PHP_PROG_SENDMAIL	"/usr/sbin/sendmail"
#define PHP_SOLID_INCLUDE	""
#define PHP_SOLID_LIBS		""
#define PHP_EMPRESS_INCLUDE	""
#define PHP_EMPRESS_LIBS	""
#define PHP_SYBASE_INCLUDE	""
#define PHP_SYBASE_LFLAGS	""
#define PHP_SYBASE_LIBS		""
#define PHP_DBM_TYPE		""
#define PHP_DBM_LIB		""
#define PHP_LDAP_LFLAGS		""
#define PHP_LDAP_INCLUDE	""
#define PHP_LDAP_LIBS		""
#define PHP_BIRDSTEP_INCLUDE     ""
#define PHP_BIRDSTEP_LIBS        ""
#define PEAR_INSTALLDIR         ""
#define PHP_INCLUDE_PATH	".:"
#define PHP_EXTENSION_DIR       "/usr/local/lsws/lsphp71/lib/php/20160303"
#define PHP_PREFIX              "/usr/local/lsws/lsphp71"
#define PHP_BINDIR              "/usr/local/lsws/lsphp71/bin"
#define PHP_SBINDIR             "/usr/local/lsws/lsphp71/sbin"
#define PHP_MANDIR              "/usr/local/lsws/lsphp71/share/man"
#define PHP_LIBDIR              "/usr/local/lsws/lsphp71/lib/php"
#define PHP_DATADIR             "/usr/local/lsws/lsphp71/share/php/7.1"
#define PHP_SYSCONFDIR          "/usr/local/lsws/lsphp71/etc"
#define PHP_LOCALSTATEDIR       "/usr/local/lsws/lsphp71/var"
#define PHP_CONFIG_FILE_PATH    "/usr/local/lsws/lsphp71/etc/php/7.1/litespeed/"
#define PHP_CONFIG_FILE_SCAN_DIR    "/usr/local/lsws/lsphp71/etc/php/7.1/mods-available/"
#define PHP_SHLIB_SUFFIX        "so"
