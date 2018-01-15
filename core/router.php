<?php

function url_contains($string) {
  //get the current url

  $url = URL;
  //check for string in url
   $lookup = strpos($url, $string);


   //If string is found, set the value of found_context
  if($lookup > 1 || $lookup !== false) {
    return true;
  }

  //If not found, set UNSET the value of found_context
  else {return false; }
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
  if(get_request_contains($url,'/json.php/')){
    return true;
  } else {
    return false;
  }
}


function get_json_data($url=''){
  #Queris the db and returns json data
  //~ if(is_json_request($url)){
    echo 'meaw!';
    if(empty($url)){
      $url = URL;
    }
    $url = str_ireplace(BASE_PATH,'',$url);
    $url = str_ireplace('json.php/','',$url);
    $url = str_ireplace('index.php/','',$url);
    $url = str_ireplace('get-data/','',$url);
    //~ echo 'New url ='.$url;
    $url_params = explode('/',$url);
    $parameter_count = 0;
    foreach($url_params as $key => $value){
      if(!empty($value)){
        $parameter_count++;
      }
    }
    
    //~ print_r($url_params);
    
    //~ echo 'this is a db call so fetch from db';
      //~ BASE_PATH/json.php/table/column/specifier/parameter/pager-start/pager-stop
      //~ eg 3
      //~ BASE_PATH/json.php/contest/id/equals/21 
      //~ BASE_PATH/json.php/user/condition/site_funds_amount+>'50' 
    
    $_GET['get-data']['table'] = $url_params[0];
    $_GET['get-data']['column'] = $url_params[1];
    $_GET['get-data']['specifier'] = $url_params[2];
    $_GET['get-data']['parameter'] = $url_params[3];
    $_GET['get-data']['pager-start'] = $url_params[4];
    $_GET['get-data']['pager-end'] = $url_params[5];
    print_r($_GET['get-data']);
    $table = $_GET['get-data']['table'];
    $column = $_GET['get-data']['column'];
    $parameter = $_GET['get-data']['parameter'];
    $pager_start = $_GET['get-data']['pager-start'];
    $pager_end = $_GET['get-data']['pager-end'];
    if($_GET['get-data']['specifier'] == 'equals'){
      $condition = "{$column} = '{$parameter}'";
    } else if($_GET['get-data']['specifier'] == 'contains'){
      $condition = "{$column} LIKE '%{$parameter}%'";
    }
    
    if(!empty($pager_start)){
      if(!empty($pager_stop)){
        $limit = "LIMIT {$pager_start}, {$pager_stop}";
      } else {
        $limit = "LIMIT {$pager_start}, 25";
      }
      
    } else {
      $limit = '';
    }
    
    if(!empty($_GET['get-data']['table'])){
      if($_GET['get-data']['column'] == 'all'){
        $q = query_db("SELECT * FROM {$table} {$limit}",
        "Could not get all datafrom {$table}! ");
      } else if($_GET['get-data']['column'] == 'condition'){
        $condition = str_ireplace('+',' ',$_GET['get-data']['specifier']);
        $condition = str_ireplace(' greater than ','>',$condition);
        $condition = str_ireplace(' less than ','<',$condition);
        $condition = sanitize($condition);
        $sql = "SELECT {$condition} {$limit}";
        $sql = trim($sql);
        $q = query_db("$sql",
        "Could not get condition  data from {$table}! ");
      } else {
        $sql = "SELECT * FROM {$table} WHERE {$condition} {$limit}";
        $q = query_db("{$sql}",
        "Could not get {$column} value from {$table}! ");
      }
      if($q){
        foreach($q as $key => $value){
          if(is_numeric($key)){ 
            unset($q[$key]);
          }
        }
        foreach($q['result'] as $key => $value){ 
            if(is_numeric($key)){
              //~ echo $key;
              unset($q['result'][0][$key]);
            }
            if(
            $key == 'password' || 
            $key == 'secret_question' || 
            $key == 'secret_answer' || 
            $key == 'bank_account_no' || 
            $key == 'bank_name' || 
            $key == 'ip_address'
            ){
             unset($q['result'][0][$key]);
            }
          }
        $obj = json_encode($q);
        //~ header('Content-Type: application/json');
        return $obj;
        //~ exit;
      }  
    }
  //~ } else {
    //~ echo 'Not a valid json request!';
  //~ }
}

function get_url_content($url=''){
  //~ Format is: BASE_PATH/addon/action/action-value/parameter/parameter-value

    get_clean_url($url);
    show_route_content();
  
  //~ echo $_GET['fetched-url'];
  //~ print_r($_GET['clean-url']);
}

function get_clean_url($url){
  if(empty($url)){
    $url = URL;
  } 
  $_GET['fetched-url'] = $url;
  $url = str_ireplace(BASE_PATH,'',$url);
  $url = str_ireplace('addons/','',$url);
  $url = str_ireplace('index.php/','',$url);
  $url_params = explode('/',$url);
  
  $state_holder = 0;
  $key_name = '';
  $_GET['clean-url'] = array();
  
  $_GET['clean-url']['current_path'] = URL;
  $_GET['clean-url']['addon'] = array_shift($url_params);
  $action = array_shift($url_params);
  $_GET['clean-url'][$action] = array_shift($url_params);
  $_GET['clean-url']['params'] = $url_params;
  
  //~ print_r($url_params);
  //~ foreach($url_params as $value){
    //~ if($state_holder == 0 && !empty($value)){
      //~ $key_name =  array_shift($url_params);
      //~ $state_holder++;
    //~ }
    //~ if($state_holder == 1 && !empty($value)){
      //~ $_GET['clean-url']["{$key_name}"] =  array_shift($url_params);
      //~ $key_name = '';
      //~ $state_holder = 0;
    //~ }
  //~ }
  //~ print_r($_GET['clean-url']);
  }

function show_route_content($url=''){
  if(empty($url)){
    $url = URL;
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
    
    //~ echo '<br> Func name is : ' . $func_name.'('.$_GET['clean-url']['params'].')';
        if(function_exists($func_name)){
          call_user_func_array($func_name,$_GET['clean-url']['params']);
        }
    }
    //~ $_GET['clean-url'] = $holder;
  }

?>
