<?php
$user = "root";
$server = "localhost";
$pass = "";
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

?>
