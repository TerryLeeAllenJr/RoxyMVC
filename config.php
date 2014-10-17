<?php
/**
 * Created by PhpStorm.
 * User: LeeAllen
 * Date: 12/4/13
 * Time: 5:01 PM
 */

// Environment

define('_ENV','dev');
if(_ENV == 'dev'){
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}


// Always provide a TRAILING SLASH (/) AFTER A PATH
define('URL', 'http://nbcnewschannel.dev/');
define('ADMIN_URL','http://nbcnewschannel.dev/');
define('LIBS', $_SERVER['DOCUMENT_ROOT'].'/core/');
define('PATH',$_SERVER['DOCUMENT_ROOT']."/public/");
define('VERSION','1.0.1');

// DB SETTINGS.
define('DB_TYPE', 'mysql');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'nbcnewschannel');
define('DB_USER', 'root');
define('DB_PASS', 'Begaeen123!');

// NSEncrypt Keys, DO NOT CHANGE ONCE IN PRODUCTION!!! THIS WILL BREAK THE APP.
define("AES_KEY", "abcdefghijuklmno0123456789012345");
define("AES_IV", "1234567890abcdef");

// Timezone Settings
date_default_timezone_set('America/New_York');


// SET YOUR ERROR REPORTING HERE.
//error_reporting(0);