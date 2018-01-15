<?php

function show_bootstrap_brand_and_menus(){
  $menu_links = get_user_defined_bootstrap_menu_links();
  $menu = '
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="'.BASE_PATH.'">'.APPLICATION_NAME.'</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
        <ul class="nav nav-pills mr-auto">
          ';
          if(!empty($menu_links)){
            foreach($menu_links as $key => $value){
              if($_SESSION['current_url'] == $value){
                $active = 'active bg-dark text-white';
              } else {
                $active = '';
              }
              $menu .= '<li class="nav-item border border-info rounded m-2"><a class="nav-link  p-2 '.$active.'" href="'.$value.'"> '.$key.'</a> </li>';
            }
          }
          $menu .= '
          
        </ul>
          <span class="navbar-text">
            Share a room, make a friend and some money!
          </span>
        </div>
      </nav>';
  echo $menu;
}

function get_user_defined_bootstrap_menu_links(){
  $menu = array();
  $menu['Home'] = BASE_PATH.'index.php/andrews_site/action/show-roomshare-home'; 
  $menu['Add Room'] = BASE_PATH.'index.php/room/action/add-room'; 
  $menu['Find Rooms by Location'] = BASE_PATH.'index.php/room/action/find-room'; 
    
    //~ echo '
    //~ <li class="nav-item dropdown">
      //~ <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Rooms</a>
       //~ <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          
          //~ <a class="dropdown-item" href="'.BASE_PATH.'index.php/room/action/find-room">Find rooms by location</a>
          //~ <div class="dropdown-divider"></div>
          //~ <a class="dropdown-item" href="'.BASE_PATH.'index.php/room/action/search/room/description">Rooms</a>
        //~ </div>
    //~ </li>';
  return $menu;
}
  
  
?>
