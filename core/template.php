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

    <title>'.APPLICATION_NAME.' - ';
     if(isset($_SESSION['page_name'])){
       echo $_SESSION['page_name'];
     }
     echo '</title>
     
    <!-- Custom fonts for this template -->';
    echo "<!-- FONTS -->
    ";
   
    
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
	';

  echo '<link href="'.BASE_PATH .'libraries/font-awesome/css/font-awesome.min.css" rel="stylesheet" media="screen">';
  echo '<!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="'.BASE_PATH .'uploads/files/default_images/favicon.png">
  <!-- SCRIPTS
  ____________________________________________________ -->';
  
echo "</head>";
  echo "<link href='https://fonts.googleapis.com/css?family=Roboto:100' rel='stylesheet' type='text/css'>";
  load_bootstrap_css();
  load_stylesheets();
  load_jquery();
	remove_file();
}

function load_script_file($path){
  echo '<script src="'.$path.'"></script>';
  }

function load_bootstrap(){

  echo '
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
  ';
}
	
function load_jquery(){
   //~ echo '<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    //~ <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>';
	echo '<script src="'.BASE_PATH .'libraries/jquery/jquery-1.11.2.min.js"></script>';
}

function load_vuejs(){
  echo '<script type="text/javascript" src="'.BASE_PATH.'libraries/vue.js"></script>';
}
	
  
function load_bootstrap_css(){
  //~ echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">';  
  echo '<link rel="stylesheet" href="'.BASE_PATH.'libraries/bootstrap/css/bootstrap.min.css">';  
} 

function render_page_top(){
  load_vuejs();
  echo '<body class="bg-light">';
  show_bootstrap_brand_and_menus();
  show_session_message();
}
 
function render_page_bottom(){
  if(!isset($_GET['go-embed-mode'])){
    echo '<section class="clear-fix d-flex justify-content-center relative-bottom flex-wrap bg-dark p-4 text-white">'; 	unset($_SESSION['status_message']);
  
  echo '<p align="center">&copy; '.date('Y').' '.APPLICATION_NAME.' - All rights reserved. </p>';
	 
	//~ add_nicedit_editor();
		
	if(url_contains('messaging/?mid')){
		echo '<script type="text/javascript">
		var myVar;    
		var url = BasePath + "addons/messaging/new-pings.php?_=" + Math.random();
		function showNewMessages(){
			$("#new-messages").load(url +" #new-pings").fadeIn("slow");
			myVar = setTimeout(showNewMessages, 10000);
		}
		function stopFunction(){
			clearTimeout(myVar); // stop the timer
		}
		$(document).ready(function(){
			showNewMessages();

		});
		</script>';
		}
    
   //~ echo '<!-- Go to www.addthis.com/dashboard to customize your tools --> <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-583a9f4c273aaaed"></script>  '.
    echo "<div class='text-center col-md-12 col-xs-12'>Powered by - Wanni CMS</div>" .
	   "</div>";
     
	load_scripts();
  
    //5. Close connection
	# do this at the end of the page
	if(isset($connection)) {
	((is_null($___mysqli_res = mysqli_close($connection))) ? false : $___mysqli_res);
		}	   echo '</section>';
  }
  load_bootstrap(); 
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
     if(isset($_SESSION['username'])){
          $pic = show_user_pic($user=$_SESSION['username'],$pic_class='circle-pic',$length='100px');
           echo '<div class="padding-20 center-block">'.$pic['picture'].'</div>';
    }

 
  }
    
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

function show_default_template(){
  $template = '';
  start_page(); 
  show_left_sidebar();
  render_page_top();
  echo '<div class="col-md-12 col-xs-12">';
  get_url_content();
  echo '</div>';
  render_page_bottom();
}

  
function render_template(){
  if(function_exists('show_custom_template')){
    show_custom_template();
  } else {
    show_default_template();
  }
}

?>
