<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 					'ab');
define('FOPEN_READ_WRITE_CREATE', 				'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 			'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./system/application/config/constants.php */


// environments
define('ENV_PRODUCTION',	'production');
define('ENV_TESTING',		'testing');
define('ENV_DEVELOPMENT',	'development');

define('USER_ROOT', 10);
define('USER_ADMIN', 9);
define('USER_KNOWN', 2);
define('USER_ANON', 0);

define('ALL_PERMISSIONS_STRING', implode(",", array(USER_ROOT, USER_ADMIN, USER_KNOWN, USER_ANON)));
define('ERROR_UNKNOWN', 'An unknown error occurred');

/*
|--------------------------------------------------------------------------
| Passwords and API keys
|--------------------------------------------------------------------------
*/

define('CI_ENCRYPTION_KEY', getenv('CI_ENCRYPTION_KEY'));
define('DATABASE', getenv('DATABASE'));
define('DATABASE_USERNAME', getenv('DATABASE_USERNAME'));
define('DATABASE_PASSWORD', getenv('DATABASE_PASSWORD'));

if(empty(CI_ENCRYPTION_KEY)){
  exit('Missing encryption key - set in environment variables');
}

if(empty(DATABASE)){
  exit('Missing database name - set in environment variables');
}

if(empty(DATABASE_USERNAME)){
  exit('Missing database username - set in environment variables');
}

if(empty(DATABASE_PASSWORD)){
  exit('Missing database password - set in environment variables');
}
