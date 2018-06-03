<?php

function start_page(){
  echo '
  <!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="';
    if(isset($_SESSION['meta']['description'])){
      echo $_SESSION['meta']['description'];
    }
    echo '">
    <meta name="author" content="'.APPLICATION_NAME.'">
    <meta name="keywords" content="'.$_SESSION['meta']['keywords'].'">

    <title>'.APPLICATION_NAME.' - '.$_SESSION['page-title']
    ;
     if(isset($_SESSION['page_name'])){
       echo $_SESSION['page_name'];
     }
     echo '</title>

    <!-- Custom fonts for this template -->';
    echo "<!-- FONTS -->";

  echo '

  <!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="HandheldFriendly" content="true" />

  <!-- FONT
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/all.js" integrity="sha384-xymdQtn1n3lH2wcu0qhcdaOpQwyoarkgLVxC/wZ5q7h9gHtxICrpcaSUfygqZGOe" crossorigin="anonymous"></script>
  <!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->


  ';
 echo '<!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="'.BASE_PATH .'files/default_images/staywithme32x32.png">
  <!-- SCRIPTS
  ____________________________________________________ -->';

echo "</head>";
  echo "<link defer href='https://fonts.googleapis.com/css?family=Roboto:300' rel='stylesheet' type='text/css'>";
  load_bootstrap_css();
  load_stylesheets();

}





function render_page_top(){
  echo '<body class="bg-white" style="overflow-x: hidden">';
  show_brand();
  show_bootstrap_menus();
  show_session_message();

}

function render_page_bottom(){
  //~ load_jquery();
  if(!isset($_GET['go-embed-mode'])){

    online_users();

    echo '<div class="row relative-bottom text-centered bg-dark p-4 text-secondary">';
    unset($_SESSION['status_message']);
    load_view('default','footer-links');

  echo '<p align="center" class="d-block p-3 col-md-12 col-xs-12">&copy; '.date('Y').' '.APPLICATION_NAME.' - All rights reserved. </p>';

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

    if(url_contains(BASE_PATH.'p/')){

      echo '<!-- Go to www.addthis.com/dashboard to customize your tools --> <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-583a9f4c273aaaed"></script>  ';
    }
    echo "<div class='text-center col-md-12 col-xs-12'>Powered by - Wanni CMS 2.0</div>" .
    "</div>";

  load_scripts();


    //5. Close connection
  # do this at the end of the page
  if(isset($connection)) {
  ((is_null($___mysqli_res = mysqli_close($connection))) ? false : $___mysqli_res);
    }    echo '</div>';
  }
  load_bootstrap();

  add_tinymce();

  //~ Facebook javascript sdk
  if(is_home_page()){
  //~ echo '<div id="fb-root"></div>
  //~ <script>(function(d, s, id) {
    //~ var js, fjs = d.getElementsByTagName(s)[0];
    //~ if (d.getElementById(id)) return;
    //~ js = d.createElement(s); js.id = id;
    //~ js.src = \'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12&appId=1673202172745708&autoLogAppEvents=1\';
    //~ fjs.parentNode.insertBefore(js, fjs);
  //~ }(document, \'script\', \'facebook-jssdk\'));</script>';
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
  } else if(!$is_mobile && is_logged_in()){
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
  //~ if(!is_user_page()){
     //~ if(isset($_SESSION['username'])){
          //~ $pic = show_user_pic($user=$_SESSION['username'],$pic_class='circle-pic',$length='100');
           //~ echo '<div class="p-3 center-block">'.$pic['picture'].'</div>';
    //~ }


  //~ }

  //~ $menu_links = get_menu_links();
  //~ if(!empty($menu_links)){
    //~ foreach($menu_links as $key => $value){
      //~ if($_SESSION['current_url'] == $value){
        //~ $active = 'text-danger';
      //~ } else {
        //~ $active = '';
      //~ }
      //~ echo '<a class="p-2 m-2 hover-link d-block'.$active.'" href="'.$value.'"> '.$key.'</a>';
    //~ }
  //~ }

  show_sidebar_menu_links();

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
  if(!is_json_request()){
    start_page();
    show_left_sidebar();
    render_page_top();
    echo '<div class="container">';
  }
  get_url_content();
  if(!is_json_request()){
    echo '</div>';
    render_page_bottom();
  }
}


function render_template(){
  if(function_exists('show_custom_template')){
    show_custom_template();
  } else {
    show_default_template();
  }

}



?>
