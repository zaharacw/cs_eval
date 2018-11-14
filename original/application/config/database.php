<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'new_local';
$active_record = TRUE;

// development database
$db['remote_dev']['hostname'] = 'localhost';
$db['remote_dev']['username'] = 'evalsdevroot';
$db['remote_dev']['password'] = '4c*VB9-j$H';
$db['remote_dev']['database'] = 'evals';
$db['remote_dev']['dbdriver'] = 'mysqli';
$db['remote_dev']['dbprefix'] = '';
$db['remote_dev']['pconnect'] = TRUE;
$db['remote_dev']['db_debug'] = TRUE;
$db['remote_dev']['cache_on'] = FALSE;
$db['remote_dev']['cachedir'] = '';
$db['remote_dev']['char_set'] = 'utf8';
$db['remote_dev']['dbcollat'] = 'utf8_general_ci';
$db['remote_dev']['swap_pre'] = '';
$db['remote_dev']['autoinit'] = TRUE;
$db['remote_dev']['stricton'] = FALSE;

// old local database
$db['local_dev']['hostname'] = 'localhost';
$db['local_dev']['username'] = 'root';
$db['local_dev']['password'] = '';
$db['local_dev']['database'] = 'evals';
$db['local_dev']['dbdriver'] = 'mysql';
$db['local_dev']['dbprefix'] = '';
$db['local_dev']['pconnect'] = TRUE;
$db['local_dev']['db_debug'] = TRUE;
$db['local_dev']['cache_on'] = FALSE;
$db['local_dev']['cachedir'] = '';
$db['local_dev']['char_set'] = 'utf8';
$db['local_dev']['dbcollat'] = 'utf8_general_ci';
$db['local_dev']['swap_pre'] = '';
$db['local_dev']['autoinit'] = TRUE;
$db['local_dev']['stricton'] = FALSE;

// new local database
$db['new_local']['hostname'] = 'localhost';
$db['new_local']['username'] = 'root';
$db['new_local']['password'] = '';
$db['new_local']['database'] = 'better_evals';
$db['new_local']['dbdriver'] = 'mysql';
$db['new_local']['dbprefix'] = '';
$db['new_local']['pconnect'] = TRUE;
$db['new_local']['db_debug'] = TRUE;
$db['new_local']['cache_on'] = FALSE;
$db['new_local']['cachedir'] = '';
$db['new_local']['char_set'] = 'utf8';
$db['new_local']['dbcollat'] = 'utf8_general_ci';
$db['new_local']['swap_pre'] = '';
$db['new_local']['autoinit'] = TRUE;
$db['new_local']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */
