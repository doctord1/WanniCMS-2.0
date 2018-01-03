<?php
function get_nav_bar(){
  $menu = 
  '<div class="row nav-bar">
                     <ul class="nav nav-pills" style="background:rgb(10,10,255);">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Product</a></li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Services
                            <span class="caret"></span></a>
                             <ul class="dropdown-menu">
                                <li><a href="#">Training</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Team</a></li>
                        <li><a href="#">About Us</a></li>
                        
                        
                    </ul>
               </div>
  <a href="'.BASE_PATH.'index.php/andrews_site/action/show-default-home-page">Home</a> | '.
  '<a href="'.BASE_PATH.'index.php/andrews_site/action/show-default-about-page">About</a>';

  return $menu;
  }
  
  
?>
