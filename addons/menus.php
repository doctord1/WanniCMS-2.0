<?php
function get_nav_bar(){
  $menu = 
  '
  <a href="'.BASE_PATH.'index.php/andrews_site/action/show-default-home-page">Home</a> | '.
  '<a href="'.BASE_PATH.'index.php/andrews_site/action/show-default-about-page">About</a>';

  return $menu;
  }
  
  
?>
