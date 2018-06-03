<?php
function home(){
  $_GET['addon'] = 'home';
  if(!is_logged_in()){
    load_view('default','home');
  } else {
    load_view('default','user-home');
  }
}

?>
