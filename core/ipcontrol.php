<?php

function access_check(){
  $ip = $_SERVER['REMOTE_ADDR'];
  $_SESSION['recognize'] = 'http://'.BASE_PATH.'/?scan_vader=open_sesame&na=gringo';
  //echo $_SESSION['recognize'];
  //print_r($_SESSION);
  if($_SESSION['grant_access']=='false' && $_SESSION['recognize'] == $_SESSION['this_url']){
  $query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO whitelist(`id`,`country`,`ip`) VALUES('0','','{$ip}')") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  echo "<script> window.location.replace('http://".BASE_PATH."') </script>";
  }
  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id FROM whitelist WHERE ip='{$ip}'") or die('Who are you? '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  $records = mysqli_num_rows($query);
  if(!empty($records)){
    $_SESSION['grant_access'] = 'true';
    } else { $_SESSION['grant_access']='false';}
  if($_SESSION['grant_access'] != 'true'){
   //echo $user_data['ip'];
   echo "<h1 align='center'>Notice: This space is for sale !</h1>"; die();
   }
  }


function ipcontrol(){
if($_SESSION['grant_access'] != 'true'){
$ip = $_SERVER['REMOTE_ADDR'];
$dest_ip = 'https://freegeoip.net/json/'.$ip;
$ip_info = curl_get($url= $dest_ip);
$user_data = json_decode($ip_info,TRUE);
$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM whitelist");

while($result = mysqli_fetch_array($query)){
  if(strtolower($user_data['country_name']) == strtolower($result['allowed_country'])
  || $user_data['ip'] == '127.0.0.1'
  ){
    $_SESSION['grant_access'] = 'true';
    } else {
      $_SESSION['grant_access'] = 'false';
      }
}

  }
}
//~ access_check();

//~ ipcontrol();

function whitelist(){

  $recognize = $_SESSION['recognize'];
  if(($_GET['scan_vader'] == 'open_sesame' && $_SESSION['grant_access'] = 'true' || $_SESSION['this_url'] == $recognize)
  ){
    echo "Allowed countries = ". $_SESSION['whitelist'];
  //echo "SESSION['grant_access'] = ".$_SESSION['grant_access'];
  if(isset($_POST['allow']) && isset($_POST['country']) && !isset($_POST['take_offline'])){
    $country = mysql_prep($_POST['country']);
    $query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO whitelist(`id`,`country`,`ip`) VALUES('0','{$country}','')") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
    $query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE offline SET offline='no' WHERE id='1'");
    if($query){
    $query2 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM whitelist");
    //$row_count = mysql_num_rows($query2);
    while($result = mysqli_fetch_array($query2)){
      $_SESSION['whitelist'] = $result['country'] .'<br>';
      }

    }

    } elseif(isset($_POST['take_offline'])){

      $query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE offline SET offline='yes' WHERE id='1'");
      $query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM whitelist WHERE ip !=''") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
      $_SESSION['grant_access']='false';
      session_destroy();
      echo "<script> window.location.replace('http://".BASE_PATH."') </script>";
      }


  $dest_country = 'http://data.okfn.org/data/core/country-list/r/data.json';
  $data = curl_get($dest_country);
  $countries = json_decode($data, true);
  //print_r($countries[0]);

  echo '<form method="post" action="'.$_SESSION['this_url'].'" style="margin: 30px;">
  <h2>Select country</h2>
  <select name="country">
  ';
  $num = 0;
  foreach($countries as $country){
    //print_r($country) ; echo '<br>';
    echo '<option>' .$country['Name'] .'</option>';
    $num++;
    }

  echo '
  </select><br><br>
  <input type="submit" name="allow" value="Allow" class="btn btn-primary">
  <input type="submit" name="take_offline" value="Take offline" class="btn btn-danger">
  </form>';
  }
}


function get_user_ip_address(){
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function get_user_location_by_ip(){
  $ip = get_user_ip_address();
  //~ echo $ip;
  $location = curl_get("http://freegeoip.net/json/{$ip}");
  $location = json_decode($location,true);
  return $location;
  }



?>
