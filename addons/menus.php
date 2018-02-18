<?php

function show_bootstrap_brand_and_menus(){
  $menu_links = get_user_defined_bootstrap_menu_links();
  $menu = '
      <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <a class="navbar-brand" href="'.BASE_PATH.'index.php/home">'.APPLICATION_NAME.'</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
        <ul class="nav nav-pills mr-auto">
          ';
          if(!empty($menu_links)){
            foreach($menu_links as $key => $value){
              if($_SESSION['current_url'] == $value){
                $active = 'active bg-light text-danger';
              } else {
                $active = '';
              }
              $menu .= '<li class="nav-item border border-info rounded m-2"><a class="nav-link  p-2 '.$active.'" href="'.$value.'"> '.$key.'</a> </li>';
            }
          }
          $menu .= '
          <li class="nav-item dropdown border border-info rounded m-2">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">My Account</a>
             <div class="dropdown-menu" aria-labelledby="navbarDropdown">';
            
            if(!is_logged_in()){
              $menu .= '<a class="dropdown-item" href="'.BASE_PATH.'index.php/show-login-form">Login</a>';
            }
            $menu .= '<div class="dropdown-divider"></div>';
            if(is_logged_in()){
              $menu .= '<a class="dropdown-item" href="'.BASE_PATH.'index.php/me">My Profile</a>';
              $menu .= '<a class="dropdown-item" href="'.BASE_PATH.'index.php/logout">Logout</a>';
            }
            $menu .= '</div>
          </li>
          
        </ul>
          <span class="navbar-text">
            '.WELCOME_MESSAGE.'
          </span>
        </div>
      </nav>';
  echo $menu;
}

function get_user_defined_bootstrap_menu_links(){
  $menu = array();
  $menu['Home'] = BASE_PATH.'index.php/home'; 
  if(is_logged_in()){
    $menu['Add Room'] = BASE_PATH.'index.php/room/action/add-room'; 
  }
  //~ $menu['Find Rooms by Location'] = BASE_PATH.'index.php/room/action/find-room'; 
  return $menu;
}
?>
