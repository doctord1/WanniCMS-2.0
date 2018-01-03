<?php

function start_page(){
	echo '
	<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>'.APPLICATION_NAME.' - '.$_SESSION['page_name'].'</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <!-- Custom fonts for this template -->
    <link href="theme/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">';
    echo "<link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>";

    load_stylesheets();
    
    echo '

  </head>	';
	echo '
	
	<!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="HandheldFriendly" content="true" />

  <!-- FONT
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  

  <!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<link rel="shortcut icon" href="'.BASE_PATH.'uploads/files/default_images/favicon.ico?v=4f32ecc8f43d">
  <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">';

  echo '<link href="'.BASE_PATH .'libraries/font-awesome/css/font-awesome.min.css" rel="stylesheet" media="screen">';
	
  echo '<!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="'.BASE_PATH .'uploads/files/default_images/favicon.png">
  <!-- SCRIPTS
  ____________________________________________________ -->';
  
echo "</head>";

	remove_file();
}


function show_brand_and_menus(){
   $menu = get_nav_bar();
  if(empty($menu)){
    $menu = '<div class="row">
  <header class ="navigation col-md-12 col-xs-12">
    <nav class="navbar navbar-default" role="navigation">
    <div class="navbar-header col-md-12 col-xs-12">
    <div class="row">
      <div class="col-md-3 col-xs-3">
        <a class="navbar-brand col-md-3 col-xs-3" href="'.BASE_PATH.'">
          '.APPLICATION_NAME.'
        </a>
      </div> 
      
    </div>
  </div>

  </header>
  </div>';
  }
  echo $menu;
}

function render_page_top(){
  echo '<body id="page-top" class="content-pushed">';
  show_brand_and_menus();
  echo '<div class="container p-0">';
  //~ echo '<div class="col-md-6 col-xs-12 inline-block mx-auto mb-2 p-2 well text-center"> Ad slot 1';
  echo '</div>';
  
  //~ echo '<div class="col-md-6 col-xs-hiddden inline-block mx-auto mb-2 p-2 well text-center"> Ad slot 2';
  echo '</div>';
}
  
function render_page_bottom(){
  echo '</div>';
  if(!isset($_GET['go-embed-mode'])){
    echo '<section class="footer-region">'; do_footer(); echo '</section>';
  }
  echo ' </body></html>';
}


function show_default_home_page(){
  echo '<h1>Welcome to WanniCMS homepage</h1>';
}

function show_default_about_page(){
echo '<h1>About WanniCMS</h1>';
}

function show_left_sidebar(){

  $is_mobile = check_user_agent('mobile');
  if($is_mobile || !is_logged_in()){
  echo '<aside id="sidebar" class="hidden">';
  } else if($is_mobile && is_logged_in() ){
  echo '<aside id="sidebar" class="hidden">';
  } else if(!$is_mobile && is_logged_in()
  //&& ($_SESSION['current_url'] == BASE_PATH .'?page_name=home' )
  ){
    if(is_home_page()){
      echo '<aside id="sidebar" class="content-pushed">';
      } else {
      echo '<aside id="sidebar" class="hidden">';
    }
  }else if(!$is_mobile && !is_logged_in()){
    if(is_home_page()){
      echo '<aside id="sidebar" class="content-pushed">';
      } else {
      echo '<aside id="sidebar" class="hidden">';
    }
    }
  echo '<div class=" main-sidebar main-sidebar-left">';
    echo '<div id="close-sidebar"><p align="center"><br> - Close x </p></div>';
  if(!is_user_page()){
  $pic = show_user_pic($user=$_SESSION['username'],$pic_class='circle-pic',$length='100px');
  echo '<div class="padding-20 center-block">'.$pic['picture'].'</div>';
  //~ if(addon_is_active('rewards')){
    //~ display_user_rewards_status();
    //~ }
  }

  do_left_sidebar();

  //~ if(is_logged_in()){
  //~ link_to(BASE_PATH.'user','&nbsp;<i class="glyphicon glyphicon-search"></i>&nbsp; Find someone',$class='btn btn-sm btn-default margin-10',$type='button');
  //~ }

  //show_sidebar_settings_menu();

  //~ echo '</div>';
  if(!is_logged_in()){
    echo '<div class="extra-content-1">';

    echo '</div>';
    }
    echo '<div class="extra-content-2">

    </div>';

echo '</aside>';
}

function show_main_content(){
echo "";
if(isset($_SESSION['user_before_morph'])){
    echo "<br>You are now masquerading as {$_SESSION['username']} .
    <em><a href='".BASE_PATH."user/?user={$_SESSION['user_before_morph']}&morph_string={$_SESSION['control']}&morph_target={$_SESSION['user_before_morph']}'>switch back to {$_SESSION['user_before_morph']}</a></em>";
  }

do_main_content();
}

function get_default_template(){
  $template = '';
  $template .= start_page(); 
  $template .= show_left_sidebar();
  $template .= render_page_top();
  $template .= '<div class="col-md-12 col-xs-12">';
  $template .= get_url_content();
  $template .= '</div>';
  $template .= render_page_bottom();
  echo $template;
}

function get_custom_template(){
  //~ $template = '';
  //~ $template .= start_page(); // LOADS all stylesheets in styles folder and beginning html
  //~ $template .= render_page_top();// renders content above the main content
  //~ $template .= '<div class="col-md-12 col-xs-12">';
  //~ $template .= get_url_content(); //VERY IMPORTANT!!
  //~ $template .= '</div>';
  //~ $template .= render_page_bottom(); // RENDERS footer and loads all scripts in scripts folder like bootstap etc
  //~ return $template;
}
  
function render_template(){
  $template = get_custom_template();
  if(empty($template)){
    $template = get_default_template();
  }
  echo $template;
}

?>
