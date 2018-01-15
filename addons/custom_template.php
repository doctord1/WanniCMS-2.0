<?php


function show_custom_template(){
  start_page(); // LOADS all stylesheets in styles folder and beginning html
  render_page_top();// renders content above the main content
  echo '<div class="col-md-12 col-xs-12">';
  get_url_content(); //VERY IMPORTANT!!
  if(is_home_page()){
    show_roomshare_home();
  }
  //~ show_roomshare_about();
  
  echo '</div>';
  render_page_bottom(); 
  // RENDERS footer and loads all scripts in scripts folder like bootstap etc
  //~ return $template;
}

function show_roomshare_home(){
  echo '<h1>Share your rooms and get paid</h1>';
}

function show_roomshare_about(){
  echo '<h1>Sometimes we do not need to stay a year and sometimes hotels are just not convenient!</h1>';
}



?>
