<?php
function url_contains($string) {
  //get the current url
  $url = URL;
  //check for string in url
  $lookup = strpos($url, $string);
  if($lookup > 1 || $lookup !== false) {
    return true;
  } else {return false; }
}

function no_url_access(){
  $url = str_ireplace(BASE_PATH,'',$_SESSION['current_url']);
  $url = str_ireplace('index.php/','',$url);
  $u_arr = explode('/',$url);
  if(count($u_arr) ==  1){
    $url_action = $u_arr[0];
  } else if(!empty($u_arr[2])){
    $url_action = $u_arr[2];
  } else {
    $url_action = '';
  }
  if($_GET['url_function'] == $url_action){
    set_session_message('alert-warning', 'You are trying to access '.$_SESSION['current_url'].'. <br>
    You should not be doing that!');
   redirect_to(BASE_PATH.'deny-access');
  }
}

function context_is($context){
  if(isset($_GET['context']) && $_GET['context'] == $context){
    return true;
  } else {
    return false;
  }
}

function get_request_contains($request_url,$search_string) {
  //~ Simply an alias of url_contains
  //get the current url
  $string = $search_string;
  $url = $request_url;
  //~ echo $request_url;
  //check for string in url
   $lookup = strpos($url, $string);


   //If string is found, set the value of found_context
  if($lookup > 1 || $lookup !== false) {
    //~ echo 'is_component request';
    return true;
  }

  //If not found, set UNSET the value of found_context
  else {
    //~ echo 'is_not component request';
    return false;
    }
}

function redirect_to($destination){
  echo "<script> window.location.replace('{$destination}') </script>";
  echo "<noscript>";
  header('Location: '.$destination);
  echo"</noscript>";
  exit;
}


function is_json_request(){
  if(empty($url)){
    $url = URL;
  }
  if(get_request_contains($url,BASE_PATH.'json/')){
    return true;
  } else {
    return false;
  }
}




function get_url_content($url=''){
  //~ Format is: BASE_PATH/context(addon)/action/action-value/parameter/parameter-value

    get_clean_url($url);
    enable_url_aliases();
    show_route_content();

  //~ echo $_GET['fetched-url'];
  //~ print_r($_GET['clean-url']);

}



function enable_url_aliases(){
  //~ Url ALiases
  if(isset($_GET['clean-url']['short-call'])){
    $path = $_GET['clean-url']['short-call'];

    if($path == 'me'){
      $_GET['clean-url']['short-call'] = 'show-user-profile';
      if(is_logged_in()){
        $_GET['clean-url']['user'] = $_SESSION['username'];
      }
    }
    if($path == 'login'){
      $_GET['clean-url']['short-call'] = 'show-login-form';
    }

  }
  if(url_contains(BASE_PATH.'tags/')){
    $tag = urldecode(str_ireplace(BASE_PATH.'tags/','',URL));
    get_clean_url(BASE_PATH.'category/action/show-posts-tagged-with/'.$tag);
  }
  if(url_contains(BASE_PATH.'p/')){
    $post = urldecode(str_ireplace(BASE_PATH.'p/','',URL));
    get_clean_url(BASE_PATH.'post/action/show-post/'.$post);

  }
  if(url_contains(BASE_PATH.'profile/')){
    $user = str_ireplace(BASE_PATH.'profile/','',URL);
    get_clean_url(BASE_PATH.'user/action/show-user-profile/'.$user);
  }
}

function get_clean_url($url=''){
  if(empty($url)){
    $url = URL;
  }

  if(url_contains('/index.php/')){
    $url = str_ireplace('/index.php/','/',URL);
    redirect_to($url);
  }

  $_GET['fetched-url'] = $url;
  $url = str_ireplace(BASE_PATH,'',$url);
  $url = str_ireplace('index.php/','',$url);
  $url_params = explode('/',$url);
  $_GET['clean-url'] = array();
  $count = count($url_params);
  if($count == 1){
    $_GET['clean-url']['short-call'] = array_shift($url_params);
    //~ short-call functions must have no parameter
     $_GET['clean-url']['function'] =  $_GET['clean-url']['short-call'];
    enable_url_aliases();
  } else if(url_contains('/p/')){
    $_GET['url_function'] = 'show-post';
  } else if($count >= 2){
    $_GET['url_function'] = $url_params[2];
  }
  $state_holder = 0;
  $key_name = '';

  $_GET['clean-url']['current_path'] = URL;
  $_GET['clean-url']['addon'] = array_shift($url_params);
  $_GET['context'] = $_GET['clean-url']['addon'];
  $action = array_shift($url_params);
  $_GET['clean-url'][$action] = array_shift($url_params);
  $_GET['clean-url']['params'] = $url_params;

  foreach($url_params as $value){
    if($state_holder == 0 && !empty($value)){
      $key_name =  array_shift($url_params);
      $state_holder++;
    }
    if($state_holder == 1 && !empty($value)){
      $_GET['clean-url']["{$key_name}"] =  array_shift($url_params);
      $key_name = '';
      $state_holder = 0;
    }
  }
  //~ print_r($_GET['clean-url']);
  }

function show_route_content($url=''){
  if(empty($url)){
    $url = URL;
  }
  if($_SESSION['current_url'] == BASE_PATH || $_SESSION['current_url'] == trim(BASE_PATH,'/')){
    redirect_to(BASE_PATH.'home');
  }
  if(isset($_GET['clean-url']['addon'])){
    $route = $_GET['clean-url']['addon'];
    $current_path = array_shift($_GET['clean-url']);
    $addon_path = array_shift($_GET['clean-url']);
    $func_name  = array_shift($_GET['clean-url']);
    $func_name = str_ireplace('-','_',$func_name);
    $num_params = count($_GET['clean-url']['params']);
    $incrementor = 0;
    foreach($_GET['clean-url']['params'] as $value){
      if($incrementor <= $num_params){
        $_GET['clean-url']['params'][$incrementor] = trim(sanitize($value));
        $incrementor++;
      }
    }

    //~ echo '<br> Func name is : ' .$_GET['url_function'] .'('.$_GET['clean-url']['params'].')';
        if(function_exists($func_name)){
          $_GET['url_function'] = str_ireplace('-','_',$_GET['url_function']);
          call_user_func_array($_GET['url_function'],$_GET['clean-url']['params']);
        }
    } else if(isset($_GET['clean-url']['short-call'])){
      $func_name = str_ireplace('-','_',$_GET['clean-url']['short-call']);
    if(function_exists($func_name)){
      call_user_func($func_name);
    }
  }
    //~ print_r($_GET['clean-url']);
    //~ $_GET['clean-url'] = $holder;
  }

?>
