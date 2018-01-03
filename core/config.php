<?php
require_once('connect.php');
error_reporting('E_Error | E_warning');
$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `installed` (
  `id` int(1) NOT NULL,
  `value` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1");

// CONFIGURATION OPTIONS AND CONSTANTS

# SETS APPLICATION NAME
$result = mysqli_query($GLOBALS["___mysqli_ston"], 'SELECT * from config LIMIT 1') ;

$config = mysqli_fetch_array($result);

$app_name = $config['application_name'];

define('APPLICATION_NAME', 'com.codetosolve');
// APPLICATION_NAME is now available as a constant in any page


define('URL', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
// URL is now available as a constant in any page


define('DEFAULT_FUNC', $config['default_functions']);
// DEFAULT FUNCTIONS is now available as a constant in any page



# SETS WELCOME MESSAGE
$welcome_message = $config['welcome_message'];

define('WELCOME_MESSAGE', $welcome_message);
// WELCOME MESSAGE is now available as a constant


# SETS BASE PATH
if (isset($config['base_path'])){
$base_path = $config['base_path'];
}
else {$base_path = "/var/www/html/";}

// SETS SITE VERSION
$site_version = '0.0.1';
define('SITE_VERSION', $site_version);

define('BASE_PATH', $base_path);
// BASE_PATH is now available as a constant in any page


# SETS ADMIN FOLDER PATH
$admin_folder_name = $config['admin_folder_name'];

define('ADMIN_PATH', BASE_PATH .$admin_folder_name);
// ADMIN_PATH is now available as a constant in any page

# SETS PATH TO DEFAULT FUNCTION FILE FOR EASY INCLUDING

define('DEFAULT_FUNCTIONS', $config['default_functions']);
// DEFAULT_FUNUCTIONS is now available to any addon or module

# SETS PATH TO STYLESHEET FILE FOR EASY THEMEING

define('STYLESHEET', $config['stylesheet']);
// STYLESHEET is now available to any addon or function


if (isset($config['addons_path'])){
$addons_path = $config['addons_path'];
}
else {$addons_path = BASE_PATH ."addons/";}

define('ADDONS_PATH', $addons_path);
// ADDONS_PATH is now available to any addon or function


?>
