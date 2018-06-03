<?php
session_start();
$_SESSION['SITE_VERSION'] = SITE_VERSION;

if(!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 1800) {
  // session started more than 30 minutes ago
  session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
  $_SESSION['CREATED'] = time();  // update creation time
}


if(isset($_SERVER['HTTP_REFERER'])){
  $_SESSION['prev_url'] =$_SERVER['HTTP_REFERER'];
}

$_SESSION['current_url'] = PROTOCOL.$_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI'];

if(empty($_SESSION['username'])){
  $_SESSION['role'] = 'anonymous';
}

function set_session_message($class='', $string=''){
  $_SESSION['status-message']  .= '<div class="alert '.$class.'">'.$string .'</div>';
}

function show_session_message(){
  if(isset($_SESSION['nag'])){
    foreach($_SESSION['nag'] as $nag){
      echo '<div class="danger p-2 m-2">'.$nag.'</div>';
    }
  }
  if(!empty($_SESSION['status-message'])){
    echo $_SESSION['status-message'];
     unset($_SESSION['status-message']);
  }
}


$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
$_SESSION['base_path'] = BASE_PATH;
$_SESSION['temp_container']='';
$_SESSION['page-title']='Find a place to stay/go/hangout in 3 mins';

$_SESSION['meta']['description'] = 'StayWithMe offers a Free Hospitality and Peer Matching service
Here you can
Share your room with others get paid for it
Find cheaper accomodation!
Locate available rooms in an area within 30 seconds!
Find the type of person you will like to stay with.
';
$_SESSION['meta']['keywords'] = 'Share a room, short stay, rent a room, need accomodation, housing, roommate, room mate, house mate, housemate, flatmate, flat mate, travelling out of state, short term accomodation';

if(PROTOCOL == 'https://' && url_contains('http://')){
  redirect_to(PROTOCOL.$_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI']);
}

function set_session_variable($colon_seperated_value=''){
    if(!empty($colon_seperated_value)){
      $var = explode(':',$colon_seperated_value);
      //~ print_r($var);
      $key = array_shift($var);
      $value = array_shift($var);
      if($key != 'username' || $key != 'role' || $key != 'site_funds_amount'){
        $_SESSION[$key] = $value;
        //~ echo $_SESSION[$key]; die();
      }
    }
    redirect_to($_SESSION['prev_url']);
}
unset($_SESSION['destination']);

?>
