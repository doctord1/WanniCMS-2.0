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


function show_session_message(){
  if(isset($_SESSION['nag'])){
    foreach($_SESSION['nag'] as $nag){
      echo '<div class="danger p-2 m-2">'.$nag.'</div>';
    }
  }
  if(!empty($_SESSION['status-message'])){
    $message = $_SESSION['status-message'];
    echo $message;
  }
  $_SESSION['status-message']='';
}


$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
$_SESSION['base_path'] = BASE_PATH;
$_SESSION['temp_container']='';



if(PROTOCOL == 'https://' && url_contains('http://')){
  redirect_to(PROTOCOL.$_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI']);
}

?>
