<?php
require_once ('core.php');
$user = "wanni";
$server = "localhost";
$pass = "wanni";
$db = "wannivue";


//1. Create a database connection
$connection = ($GLOBALS["___mysqli_ston"] = mysqli_connect($server,  $user,  $pass));
if(!$connection) {
  die("Database cONNECTION failed:". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

}

//2. Select a database to use
$db_select = ((bool)mysqli_query( $connection, "USE " . $db));
if (!$db_select) {
    die("Database SELECTION failed!: ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
}
else {
//uncomment the next line to test for if database selection succeeded
//echo "Selection okay";
}


$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `installed` (
  `id` int(1) NOT NULL,
  `value` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1");

// CONFIGURATION OPTIONS AND CONSTANTS

# SETS APPLICATION NAME

$app_name = 'My Application Name';

define('APPLICATION_NAME', $app_name);
// APPLICATION_NAME is now available as a constant in any page


define('URL', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
// URL is now available as a constant in any page


# SETS WELCOME MESSAGE
$welcome_message = 'My welcome Message';

define('WELCOME_MESSAGE', $welcome_message);
// WELCOME MESSAGE is now available as a constant


# SETS BASE PATH
$base_path = 'http://localhost/aplussite.com/';

// SETS SITE VERSION
$site_version = '0.0.1';
define('SITE_VERSION', $site_version);

define('BASE_PATH', $base_path);
// BASE_PATH is now available as a constant in any page

?>
