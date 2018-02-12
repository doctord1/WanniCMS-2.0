<?php

function setup_user_db(){
  $q = query_db("DROP TABLE IF EXISTS `core_user`","
  Could not drop user table");
  
  $q = query_db("CREATE TABLE `core_user` (
  `id` int(11) NOT NULL,
  `user_name` varchar(12) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `created_time` varchar(50) NOT NULL,
  `last_login` varchar(50) NOT NULL,
  `login_count` int(11) NOT NULL DEFAULT '2',
  `logged_in` varchar(3) NOT NULL,
  `phone` varchar(14) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `last_login_ip` varchar(15) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'authenticated',
  `picture` varchar(255) NOT NULL,
  `picture_thumbnail` varchar(255) NOT NULL,
  `secret_question` varchar(150) NOT NULL,
  `secret_answer` varchar(150) NOT NULL,
  `status` varchar(15) NOT NULL,
  `full_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
  
  $q = query_db("INSERT INTO `core_user` (`id`, `user_name`, `password`, `email`, `created_time`, `last_login`, `login_count`, `logged_in`, `phone`, `ip_address`, `last_login_ip`, `role`, `picture`, `picture_thumbnail`, `secret_question`, `secret_answer`, `status`, `full_name`) VALUES
  (1, 'system', 'ee8a3cd981e14d074275617ac37776b2f3def6de', '', '2015-09-23 07:12:31', '1517342968', 7, 'no', '07016566148', '', '', 'admin', 'http://localhost/roomshare/files/user/medium-size/system.jpg', 'http://localhost/roomshare/files/user/small-size/system.jpg', '', '', 'subscribed', '')");
}

# Register FORM
function register(){
  if(isset($_GET['ref_id'])){
    $ref_id = trim(sanitize($_GET['ref_id']));
  }
  process_user_registration();
  load_view('user','create-user-form');
}

function get_user_id_by_username($user){
  $u = get_user_details($user);
  $user_id = $u['id'];
  return $user_id;
}


function delete_user($user=''){
    if((is_my_profile_page() || is_admin())
    && $_SESSION['control'] == $_GET['clean-url']['control']){

      $user = trim(sanitize($_GET['clean-url']['user']));

      $query = query_db("DELETE FROM core_user WHERE `id`={$id}",
      'Failed to delete the User account.! ');

      if($query){
       
        if(is_my_profile_page()){
          session_destroy();
        }
        redirect_to(BASE_PATH);
        }
      }
}

function show_login_form(){
  process_user_login();
  load_view('user','login-form');
}

function process_user_login(){
  if(isset($_POST['login'])) {
    echo 'Ready to login'; 
      $begins_with = substr($_POST['phone'],0,1);
      //echo "begins with" .$begins_with;
    if($begins_with == '0' || $begins_with == '+'){
      $phone = sanitize($_POST['phone']);
    } else if($begins_with !== '0' && $begins_with !== '+'){
      $phone = '0'. sanitize($_POST['phone']);	
    }
    //~ echo $phone;
    $password = trim(sanitize($_POST['password']));
    //~ print_r($_POST);
    $hashed_password = sha1($password);
  
    //login
    $q = query_db("SELECT * FROM core_user WHERE phone='{$phone}' AND password='{$hashed_password}'",
    "Could not get user details in process login! ");
      
      $login_result = $q['result'][0];
      
      $_SESSION['username'] = $login_result['user_name'] ;
      $_SESSION['user_id'] = $login_result['id'];
      $_SESSION['username'] = $login_result['user_name'];
      $_SESSION['phone'] = $login_result['phone'];
      $_SESSION['site_funds_amount'] = $login_result['site_funds_amount'];
      $_SESSION['login_count'] = $login_result['login_count'];
      $_SESSION['LAST_ACTIVITY'] = time();
      $_SESSION['CREATED'] = time();
      
      $control = mt_rand(10000,100000);
      $_SESSION['control'] = $control;
      
      $_SESSION['role'] = $login_result['role'];
      $_SESSION['state'] = $login_result['state'];
      $_SESSION['picture'] = '<a href="'.BASE_PATH .'user/action/show-user-profile/user/'.$login_result['user_name'] .'">'.
      '<img src="'.$login_result['picture'].'"></a>';
      $_SESSION['picture_thumbnail'] = '<a href="'.BASE_PATH .'user/action/show-user-profile/user/'.$login_result['user_name'] .'">'.
      $_SESSION['secret_question'] = $login_result['secret_question'];
      $_SESSION['secret_answer'] = $login_result['secret_answer'];
      unset($_SESSION['not_logged_in']);
      #print_r($_SESSION);
      
      if(isset($_SESSION['username'])){
        $time = time();
        $user = $_SESSION['username'];
        
        //Upate login time
        $query = query_db("UPDATE core_user SET `last_login`='{$time}' WHERE user_name='{$user}'","Failed to update login time");
        
        if($query){
          $_SESSION['last_login'] = $time;
        }
        $_SESSION['status-message'] = "<div class='alert alert-success p-3 text-center'> Login successful! </div>"; 
        if(!empty($post_destination)){
          $_SESSION['destination'] = $post_destination;
        } else {
          redirect_to(BASE_PATH.'index.php/me');
        }
      }
    if(isset($_POST['password'])) {
      $password = trim(sanitize($_POST['password']));
    }
    if(isset($_POST['email'])) {
      $email = trim(sanitize($_POST['email']));
    }
    if(url_contains('redirect_to')){
      $destination = sanitize($_GET['redirect_to']);
      $_SESSION['destination'] = $destination;
    }
    
      $begins_with = substr($_POST['phone'],0,1);
      //echo "begins with" .$begins_with;
    if($begins_with == '0' || $begins_with == '+'){
      $phone = sanitize($_POST['phone']);
    } else if($begins_with !== '0' && $begins_with !== '+'){
      $phone = '0'. sanitize($_POST['phone']);
    }	
      $hashed_password = sha1($password);
  }
}


function logout_notify(){

    if(isset($_GET['logout']) && $_GET['logout'] ==='true'){ # If logged out, Notify of logout success

    echo "<div class='alert alert-danger'>You are now logged out!</div>";

    }
}


function is_user_page(){
  get_clean_url();
  if(isset($_GET['clean-url'])){
    if((isset($_GET['clean-url']['short-call']) && $_GET['clean-url']['short-call'] == 'show-user-profile') 
    || $_GET['clean-url']['action'] == 'show-user-profile' || $_GET['clean-url']['addon_path'] == 'user'){
        return true;
    }else{
      return false;
    }
  }
}

function is_logged_in(){
	if(isset($_SESSION['username'])){
    return true;
  } else {
    return false;
  }
}	

function is_my_profile_page(){
  if(isset($_GET['clean-url']['user']) && $_GET['clean-url']['user'] == $_SESSION['username']){
    return true;
    } else {
      return false;
      }
  }


function online_users($type=''){
  if(isset($_SESSION['username'])){
  $diff = 54000;
  $now = time();
  $time_limit = $now - $diff; ;

  $query = mysqli_query($GLOBALS["___mysqli_ston"],"SELECT user_name,`last_login` FROM core_user WHERE last_login>='{$time_limit}' Limit 20")
  or die("Failed to get last seen users" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));


  $last_seen = array();

  while ($result = mysqli_fetch_array($query)){

    $name = $result['user_name'];

    $login_timeago = (( time() - $result['last_login']) / 60 );

    if($login_timeago < 900){
      $last_seen[]= $name;
      if($type === 'pics'){
      $person = show_user_pic($name) ;
      $pics = "<span class=''>" . $person['thumbnail']." &nbsp</span>";
      $text ='';
      echo '<span title="'. $name .'">' . $pics .'</abbr>';
      }else {
      $text = "<span><a href='". BASE_PATH ."user/?user={$name}'>{$name},</a></span> &nbsp;";
      echo $text;
        }
    }
  } echo '<br>';
  }
  $_SESSION['recently_logged_in'] = $last_seen;
  //print_r($last_seen);
}


function masquerade_as(){

  $control = $_SESSION['control'] ;

  if(!empty($_POST['username_to_morph_as']) && $_POST['morph_string'] == $_SESSION['control']){
    $morph_target = trim(sanitize($_POST['username_to_morph_as']));
    $person = get_user_details($morph_target);

    if(!empty($person)){

    $_SESSION['user_before_morph'] = $_SESSION['username'];
    $_SESSION['username'] = trim(sanitize($_POST['username_to_morph_as']));
    $_SESSION['role'] = $person['role'];

    }

  session_message('alert', 'Your are now viewing '.APPLICATION_NAME .' as <big>'.$morph_target .'</big>!');
  redirect_to(BASE_PATH.'user/?user='.$morph_target);

  }

  if(isset($_GET['morph_target'])){
    $morph_target = trim(sanitize($_GET['morph_target']));
    $person = get_user_details($morph_target);

    if($_GET['morph_string'] == $_SESSION['control']){

    unset($_SESSION['user_before_morph']);
    $_SESSION['username'] = trim(sanitize($morph_target));
    $_SESSION['role'] = $person['role'];

    session_message('alert', 'Your are now viewing '.APPLICATION_NAME .' as <big>'.$morph_target .'</big>!');
    redirect_to(BASE_PATH.'?page_name=home');

    }
  }

  if(is_admin()){
    $action = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    if(isset($_GET['user']) && $_GET['user'] !== $_SESSION['username']){

      $user= trim(sanitize($_GET['user']));
      }
    echo "<h2>Masquerade</h2><form method='post' action='{$action}'>
    View site as (person):<input type='text' name='username_to morph_as' value='{$user}' placeholder='user to masquerade as'>
    <input type='hidden' name='morph_string' value='{$control}'>
    <input type='submit' name='submit' value='Go'>
    </form>";
  }
  
  if(isset($_SESSION['user_before_morph'])){
    echo "<br>You are now masquerading as {$_SESSION['username']} .
    <em><a href='".BASE_PATH."user/?user={$_SESSION['user_before_morph']}&morph_string={$_SESSION['control']}&morph_target={$_SESSION['user_before_morph']}'>switch back to {$_SESSION['user_before_morph']}</a></em>";
  }

}

function get_user_by_id($uid=''){//done
  $query = query_db("SELECT * FROM core_user WHERE `id`='{$uid}' LIMIT 1","Could not get user by id! ");
  return $query['result'][0];
}

function get_user_by_username($username){//done
  $value = query_db("SELECT * FROM core_user WHERE user_name='{$username}' LIMIT 1",
  "Failed to get user by username!");
  return $value['result'][0];
}


function upload_user_pic(){
  if(isset($_GET['clean-url']['user'])){
    $user = $_GET['clean-url']['user'];
  } else {
    $user = $_SESSION['current_user']['user_name'];
  }
if(isset($_POST['upload-user-picture'])){
  $r = dirname(dirname(__FILE__));
  $r2 = str_ireplace('/regions/','',$r);
  $r = $r2;
  $submit =  $_POST['upload-user-picture'];
  $uploaddir = $r.'/files/user/';
  $uploadfile = $uploaddir . $_SESSION['username'].'.jpg';
  $path = BASE_PATH .'files/user/'. $_SESSION['username'].'.jpg';
  $rpath = $r.'/files/user/'. $_SESSION['username'].'.jpg';
  

# ONSUBMIT
if (isset($submit)){
  $type = $_FILES['image_field']['type'];
  $name = $_SESSION['username'];

    if(isset($_SESSION['username'])){ 
      $parent = $_SESSION['username'];
      $move = move_uploaded_file($_FILES['image_field']['tmp_name'], $uploadfile);
      echo '$move = '.$move;
      echo ' $uploadfile = '.$uploadfile;
      if($move ==1){
        //~ echo 'moved!';
        $small_path = resize_userpic_small($pic=$rpath);
        $medium_path = resize_userpic_medium($pic=$rpath);
        echo "<div class='success'>File is valid, and was successfully uploaded.\n</div>";
      $query = query_db("UPDATE core_user SET `picture`='{$medium_path}', `picture_thumbnail`='{$small_path}'
      WHERE user_name='{$user}'","Could not save Picture! ");

      //if($query) { echo "Succesfully saved to DB!";} testing
      } else {
        echo "<div class='error'>Error : No file uploaded!\n</div>";
      }

  }

}
//echo 'Here is some more debugging info:' .$_FILES['image_field']['error']; //testing


}


  # UPLOAD FORM
  if(is_logged_in()){

    if(is_my_profile_page() || is_admin() || has_role('manager')){

    echo '<h2> Add / Change Picture </h2>
    <form action="'.$_SESSION['current_url'].'" method="post" enctype="multipart/form-data" class="form form-vertcal">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <p align="center"><input type="hidden" name="MAX_FILE_SIZE" value="5000000" /></p>
    <!-- Name of input element determines name in $_FILES array -->
    <input type="file" size="500" name="image_field"  value="">
    <input type="submit" name="upload-user-picture" value="upload" class="btn btn-secondary">
    </form>';
    }

    show_user_pic($user, '', '60px');
    echo '<br>Current pic';
  }

}


function get_user_pic($user='',$pic_class='',$length=''){

  // if reward addon is active, Get reward badge
  if(addon_is_active('rewards')){
    $badge = get_reward_badge($user);
    }

  if($user !== ''){
    $user = trim(sanitize($user));
  }else{ $user = $_SESSION['username']; }

  if($length != ''){
    $dimensions = "width='{$length}' height='{$length}'";
    } else {$dimensions = '';}

$query = query_db("SELECT `picture`, `picture_thumbnail` FROM core_user WHERE user_name='{$user}'",
"Unable to Select user pic! ");

$result = $query['result'][0];
$time = time();
$pic_small = default_pic_fallback($pic=$result['picture_thumbnail'], $size='small');
$pic_medium =  default_pic_fallback($pic=$result['picture'], $size='medium');

if(is_user_page() && ($_SESSION['user_being_viewed'] == $user || is_admin())){
$picture = '<span class="clear"><a href="'.BASE_PATH .'uploads/files/user/'.$user .'.jpg"  rel="prettyPhoto[\"'.$user.'_gal\"]">'.
'<img src="'.$pic_medium.'?str='.$time.'" alt="user picture" id="" class="center-block img-responsive  '.$pic_class.'" '.$dimensions.'></a>
</span><br><br>';

} else {
$picture = '<span class="clear"><a href="'.BASE_PATH .'user/?user='.$user .'">'.
'<img src="'.$pic_medium.'?str='.$time.'" alt="user picture" id="" class="center-block img-responsive '.$pic_class.'" '.$dimensions.'></a></span><span class="badge padding-5">'.$badge.'</span>';
  }
$thumbnail = '<span class="small-pic inline-block"><a href="'.BASE_PATH .'user/?user='.$user .'">'.
'<img src="'.$pic_small.'?str='.$time.'" alt="user picture" id="profile-thumbnail" class="'.$pic_class.' img-rounded"'.$dimensions.'></a><span class="badge padding-5 margin-3 ">'.$badge.'</span></span>';

$title = "<h1>" .ucfirst($_GET['user']) ."'s Profile </h1><hr> " ;

if($user === $_SESSION['username']){

  $_SESSION['picture'] = '<a class="tooltip" href="'.BASE_PATH .'user/?user='.$result['user_name'] .'">'.
  '<img src="'.$pic_medium.'"></a>';
  $_SESSION['picture_thumbnail'] = '<a href="'.BASE_PATH .'user/?user='.$result['user_name'] .'" title="'.$result['user_name'] .'">'.
  '<img src="'.$pic_small.'"></a>';
  }

$output = array('picture'=>$picture, 'thumbnail'=>$thumbnail, 'title'=>$title);
return $output;
}

function show_user_pic($user='',$pic_class='',$length=''){
  // if reward addon is active, Get reward badge
  if(addon_is_active('rewards')){
    $badge = get_reward_badge($user);
    }
  if($user !== ''){
    $user = trim(sanitize($user));
  }else{ $user = $_SESSION['current_user']['user_name']; }

  if($length != ''){
    $dimensions = 'width="'.$length.'" height="'.$length.'"';
    } else {$dimensions = '';}
    
$query = query_db("SELECT `picture`, `picture_thumbnail` FROM core_user WHERE user_name='{$user}'",
"Unable to Select user pic! ");

$result = $query['result'][0];
//~ $time = time();

$result['picture'] = str_ireplace('medium-size/','',$result['picture']);
$pic_small = default_pic_fallback($pic=$result['picture_thumbnail'], $size='small');
$pic_medium =  default_pic_fallback($pic=$result['picture'], $size='medium');
$pic_large=  default_pic_fallback($pic=$result['picture'], $size='large');

echo '<a href="'.BASE_PATH .'index.php/user/action/show-user-profile/user/'.$user .'">'.
'<img src="'.$pic_large.'" alt="user picture" id="" class="img-fluid'.$pic_class.'"'.$dimensions.'></a>';
//~ echo '<span class="badge padding-5">'.$badge.'</span>';
}

function show_user_thumbnail($user='',$pic_class='',$length=''){
  // if reward addon is active, Get reward badge
  if(addon_is_active('rewards')){
    $badge = get_reward_badge($user);
    }
  if($user !== ''){
    $user = trim(sanitize($user));
  }else{ $user = $_SESSION['username']; }

  if($length != ''){
    $dimensions = 'width="'.$length.'" height="'.$length.'"';
    } else {$dimensions = '';}

$query = query_db("SELECT `picture`, `picture_thumbnail` FROM core_user WHERE user_name='{$user}'",
"Unable to Select user pic! ");

$result = $query['result'][0];
//~ $time = time();

$result['picture'] = str_ireplace('medium-size/','',$result['picture']);
$pic_small = default_pic_fallback($pic=$result['picture_thumbnail'], $size='small');
$pic_medium =  default_pic_fallback($pic=$result['picture'], $size='medium');
$pic_large=  default_pic_fallback($pic=$result['picture'], $size='large');

echo '<a href="'.BASE_PATH .'index.php/user/action/show-user-profile/user/'.$user .'">'.
'<img src="'.$pic_large.'" alt="user picture" id="" class="img-responsive '.$pic_class.'"'.$dimensions.'></a>';
//~ echo '<span class="badge padding-5">'.$badge.'</span>';
}


function show_user_edit_link(){
  get_clean_url();
  if(is_logged_in() && (is_my_profile_page() || is_admin())){
    if(isset($_GET['clean-url']['user'])){
      $user = $_GET['clean-url']['user'];
    } else {
      $user = $_SESSION['username'];
    }
      echo '<a href="' .BASE_PATH .'index.php/user/action/edit-user/user/'.$user.'"> Edit </a>';
  } 
}

function show_user_delete_link(){
  if(is_logged_in() && (is_my_profile_page() || is_admin())){
    if(isset($_GET['clean-url']['user'])){
    $url_user = trim(sanitize($_GET['clean-url']['user']));
    $user_arr = get_user_by_username($url_user);
    $user = $user_arr['user_name'];
    } else {
      $user = $_SESSION['username'];
    }
    
    echo '<a href="' .BASE_PATH .'index.php/user/action/delete-user/'.$user.'?control='.$_SESSION['control'].'" onclick="return confirm(\'Are you sure you want to delete this user?\');"> Delete </a>';
  }
}

function show_user_profile($user=''){
  if(is_user_page()){
    if(isset($_GET['clean-url']['user'])){
      $url_user = trim($_GET['clean-url']['user']);
      $_SESSION['current_user'] = get_user_by_username($url_user);
    } 
    if(is_logged_in()){
      load_view('user','user-profile');  
    } else {
      deny_access();
    }
  }
}

function show_last_seen(){
  
  }

function link_to_user_by_id($user_id=''){
  $user_details = get_user_by_id($user_id);
  $username = $user_details['user_name'];
  echo '<a href="'.BASE_PATH.'index.php/user/action/show-user-profile/user/'.$username.'">'.$username.'</a>';
}


function link_to_user_by_username($username=''){
  $username = strtolower($username);
  $user = get_user_by_username($username);
  echo '<a href="'.BASE_PATH.'index.php/user/action/show-user-profile/user/'.$username.'">'.$username.'</a>';
}


function list_users(){
if (isset($_SESSION['username'])){
  if(empty($_GET['user']) && !empty ($_SESSION['username'])){
  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM  core_user ORDER BY  `id` DESC
  LIMIT 0 , 30") or die('Could not get data:' . ((is_object( )) ? mysqli_error( ) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  #echo "Data fetched succesfully"; //testing
  $user_list = "<h2> Users List</h2>";


  while($result = mysqli_fetch_array($query)){
  $picture = default_pic_fallback($pic=$result['picture_thumbnail'], $size='small');
    $user_list = $user_list
  . "<table class=''>"
  . '<tr><td colspan="3" rowspan="6" width="auto" height="100%"'
  . ' align="left" valign="bottom">'
  .'<a id="pagelist" href="' .BASE_PATH .'user/?user=' .$result['user_name']
  .'"><img src="'.$picture
  .'" width="50" height="50 alt="user pic"> &nbsp<strong><big> '
  . $result['user_name']
  . '</big></strong></a>';
  if(is_admin()){   $user_list = $user_list. ''
  .'&nbsp &nbsp<a href="'
  . BASE_PATH ."user/?user="
  . $result['user_name']
  . '&edit=true'
  . '" '
  . '>edit </a>'
  . "</td>"
  . '<td colspan="3" rowspan="6" width="auto" height="100%"'
  . ' align="left" valign="bottom">'
  . '&nbsp| &nbsp<a href="'
  . BASE_PATH ."user/process.php?"
  . 'action='
  . 'delete_page&'
  . 'user_name='
  . $result['user_name']
  . '" '
  . '>delete </a>';
  }
  $user_list = $user_list. ''
  . "</td><hr></tr></table>";
  } echo $user_list;
  }
}
}

function edit_user($user=''){
  
  if(is_logged_in()){
    process_user_edit();
    
    if(isset($_GET['clean-url']['user'])){
      $user = sanitize($_GET['clean-url']['user']);
      
      echo '<div class="col-md-5 col-xs-12 p-2 m-3">';
        echo "<h3 class='p-2 m-2'>Edit "; link_to_user_by_username($user);
        echo "'s account </h3><hr>";

        upload_user_pic(); # Allows users, admins, or managers to upload pics to user profiles.

        # FETCH USER DETAILS
        $fetch_user= query_db("SELECT * FROM core_user WHERE user_name='{$user}'",
        "No such user! ");

        $row = $fetch_user['result'][0];

            $form = "<form method='post' action='{$_SESSION['current_url']}'>
                <input type='hidden' name='uid' value='{$row['id']}'>";
            $form .= "<br>Password :<br><input type='password' class='form-control' name='password' placeholder='' >
            <br>Confirm password :<br><input type='password' class='form-control' name='confirm_password' placeholder=''>
            <br>Phone :<br><input type='number' class='form-control' name='phone' value='{$row['phone']}' placeholder='Phone number'>
            <br>Email :<br><input type='email' name='email' class='form-control' value='{$row['email']}' placeholder='Email'>
            <br>Secret Question :<br> <span class='green-text'>\"".$_SESSION['secret_question']."\"</span>
            <br> Answer:<br><input type='text' class='form-control' name='secret_answer' value='' placeholder='answer'>
            ";
            if($_SESSION['role']==='admin'){

              $form = $form ."<br>Role or level :<br><input type='text' name='role' class='form-control' placeholder='role or permissions level' value='".$row['role']."'>";
            }

          $form = $form ."<br><input type='submit' class='btn btn-primary' name='submit' value='save'> ";
            
            $form .= "</form>";
            echo $form;

      //~ set_bank_details($user);
      
      echo '</div>';
      }

    }
}


function get_bank_details($user){
  $result = get_user_details($user);
  $output = array();
  $output['id'] = $result['id'];
  $output['account_number'] = $result['bank_account_no'] ;
  $output['bank_name'] = $result['bank_name'];
  $output['full_name'] = $result['full_name'];
  return $output;
  }

function set_bank_details($user){

  $result = get_bank_details($user); //bank details only
  if(empty($result['account_number']) && $_GET['action'] == 'set_bank_details'){ // but if empty
    $result = get_user_details($user); // get all details this time

  $form = "<h1>Enter your bank details</h1><form method='post' action='process.php'>
        <input type='hidden' name='uid' value='{$result['id']}'>
        Full name :<input type='text' name='full_name' value=''><br>
        <em>Cannot be changed and must be the same name on your account!</em><br>
        Account no :<input type='number' name='account_no' value=''><br>
        Bank : <select name='bank'>";
        $banks_list = "Access Bank Plc,
            Citibank Nigeria Limited,
            Diamond Bank Plc,
            Ecobank Nigeria Plc,
            Enterprise Bank,
            Fidelity Bank Plc,
            First Bank of Nigeria Plc,
            First City Monument Bank Plc,
            Guaranty Trust Bank Plc,
            Heritage Banking Company Ltd.,
            Key Stone Bank,
            MainStreet Bank,
            Skye Bank Plc,
            Stanbic IBTC Bank Ltd.,
            Standard Chartered Bank Nigeria Ltd.,
            Sterling Bank Plc,
            Union Bank of Nigeria Plc,
            United Bank For Africa Plc,
            Unity Bank Plc,
            Wema Bank Plc,
            Zenith Bank Plc";

    $banks = explode(',',$banks_list);
    foreach($banks as $bank){
      $form .= "<option value='{$bank}'>{$bank}</option>";
      }
      $form .= "</select>";
    $form = $form ."<br><input type='submit' class='submit' name='submit-account' value='save'>
        </form>";
    echo $form;
  } else {
      if(!empty($result['account_number']) && !empty($result['bank_name'])){
        link_to(BASE_PATH."user/?user={$user}", 'Return to profile');
        echo "<br><hr><h1>Bank Account Details</h1>";
        echo "You have already saved your account details and you cannot change it (except you contact support).";
        echo "<br>Your Bank details are: <hr><br>".
        "<strong>Account number: </strong>{$result['account_number']} <br>
        <strong>Full name: </strong>{$result['full_name']} <br>
        <strong>Bank: </strong>{$result['bank_name']} <br><br>";

        //link_to(BASE_PATH."user/?user={$user}", 'Return to profile');
      }
    }
}

function forgot_password(){

  //~ print_r($_POST);
  echo '<h2 class="p-3 bg-primary row">You forgot your password?</h2>';

  if(isset($_POST['phone'])){
    $phone = sanitize($_POST['phone']);
    
    $question_query = query_db("SELECT `secret_question`, `user_name` FROM core_user WHERE `phone`='{$phone}'",
    "Could not verify secret question and answer! ");

    if($question_query){
      $question_result = $question_query['result'][0];
      $_SESSION['forgot']['username'] = $question_result['user_name'];

      echo "<br><big>
      <strong>{$_SESSION['forgot']['username']} </strong>
      Your secret question is : ". $question_result['secret_question'].
      " ";
      $fetched_question = true;
    }

  } else if(!isset($_POST['secret_question'])) {
    echo "
    <form action='".BASE_PATH."index.php/forgot-password' method='POST' class='p-3'>
      <input type='number' name='phone' value='' placeholder='What is your phone number?' class='form-control'>
      <br><input type='submit' name='submit' class='btn btn-primary' value='Submit'>
    </form>
    ";
  }

  if(isset($fetched_question) && $fetched_question){
    echo "<form action='".$_SESSION['current_url']."' method='POST'>
    What is the answer to your secret Question?<br>
    <input type=text' name='secret_answer' value=''>
    <input type='hidden' name='secret_question' value='".$question_result['secret_question']."'>
    <input type='hidden' name='phone' value='".$phone."'>
    <input type='hidden' name='user_name' value='".$question_result['user_name']."'>
    <input type='submit' name='submit_secret_answer' class='button-submit' value='Submit'>
    </form>";
  }

  if(isset($_POST['submit_secret_answer'])){
    $secret_question = trim(sanitize($_POST['secret_question']));
    $secret_answer = trim(sanitize($_POST['secret_answer']));
    $phone = trim(sanitize($_POST['phone']));
    $user_name = trim(sanitize($_POST['user_name']));
    $query = query_db("SELECT `secret_answer` FROM core_user WHERE user_name='{$user_name}' and `secret_question`='{$secret_question}'","Could not confirm identity in forgot password! ");

    $answer_result = $query['result'][0];
    //~ print_r($query);
    //compare values
    if($answer_result['secret_answer'] === $secret_answer){
      $wrong_or_right = "<span class='green-text'> Correct! </span>";
      
       echo "
      <big>
      <strong>{$username} </strong>
        Your secret question is : \"". $question_result['secret_question'].
        "\" <strong>".$_POST['secret_answer']."</strong> 
        and the secret answer you supplied is {$wrong_or_right}
      </big>";
      
      //RESET PASSWORD
      $password = random_password();
      $new_password = sha1($password);

      $query = query_db("UPDATE core_user SET `password`='{$new_password}' WHERE `phone`='{$phone}' AND `secret_answer`='{$secret_answer}'","Failed to generate new password!");

      if($query){
        echo "Your new password is : <big><strong>{$password}</strong></big> <br>";
        echo "Ensure that you change it to something you can remember or WRITE IT DOWN NOW!";
        sms_notify('system',$username,"Your new pas``sword is {$password} .Ensure that you change it to something you can remember or WRITE IT DOWN NOW!",'!mportant');
      }
    } else {$wrong_or_right = "<span class='text-danger'> incorrect! </span>";}


   

    if($answer_result['secret_answer'] == $secret_answer){
      
    } else { 
      go_back(); 
    }
  }
}

function random_password( $length = 8 ) {
  // random password by http://hughlashbrooke.com/2012/04/23/simple-way-to-generate-a-random-password-in-php/
  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
  $password = substr ( str_shuffle ( str_repeat ( $chars ,$length ) ), 0, $length );
  return $password;
}


function default_pic_fallback($pic,$size=''){

  if(empty($pic) && $size ===''){
    $picture = BASE_PATH.'uploads/files/default_images/default-pic-small.png';
    return $picture;
  } else if(empty($pic) && $size === 'small'){
    $picture = BASE_PATH.'uploads/files/default_images/default-pic-small.png';
    return $picture;
  } else if(empty($pic) && $size === 'medium'){
    $picture = BASE_PATH.'uploads/files/default_images/default-pic.png';
    return $picture;
  } else if(empty($pic) && $size === 'large'){
    $picture = BASE_PATH.'uploads/files/default_images/default-pic.png';
    return $picture;
  } else {
    $picture = $pic;
    return $picture;
    }

}


function show_new_users(){
   $query_string = $_SERVER['QUERY_STRING'];

   if($query_string !== 'forgot_password'){
  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT user_name, `picture_thumbnail` FROM core_user ORDER BY `id` DESC LIMIT 6")
   or die("Error selecting new users" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
    echo '<div class="new-users-carousel center-block">';
  while($result = mysqli_fetch_array($query)){
      echo '<div class="inline-block"><a href="'.BASE_PATH.'user/?user='.$result['user_name'].'">';

      $picture = default_pic_fallback($pic=$result['picture_thumbnail'], $size='small');
      echo '<img src="'.$picture.'" title="'.$result['user_name'].'"></a></div>';
    }
    echo '
            </div><br>';

    }

}

function user_being_viewed(){
  if(!empty($_GET['user'])){
    $user = $_GET['user'];
    }
  return $user;
}


function user_search(){
  
  //~ if($_GET['clean-url']['action'] == 'show-users'){
    //~ echo 'inside user search';
    $show_more_pager = pagerize($start='',$show_more=20);
      $limit = $_SESSION['pager_limit'];
    if(isset($_POST['filter'])){

      $filter = trim(sanitize($_POST['filter']));
      if($_POST['filter'] == 'has photo'){
        $condition = "WHERE picture_thumbnail !='' ";
      } elseif($_POST['filter'] == 'no photo'){
        $condition = "WHERE picture_thumbnail =''";
      } elseif($_POST['filter'] == 'all'){
        $condition = "";
      }
      $query = query_db("SELECT * FROM user {$condition} order by id DESC",
      "Search failed! ");
    } else {
      $query = query_db("SELECT * FROM user WHERE picture_thumbnail !='' ORDER BY id DESC LIMIT 30 ","Cannot fetch users !");
    }
    if(is_json_request()){
      header('Content-Type: application/json');
      echo json_encode($query);
    } else {
      echo '<div class="col-md-12 col-xs-12 caption p-3"><h2>People</h2>';
      show_users_in_grid($query);
      echo $show_more_pager;
      echo '</div>';
    }
}

function show_users_in_grid($query){
  $num = $query['num_results'];
  echo '<div>' ;
        echo '<form method="post" action="'.$_SESSION['current_url'].'">
      <strong>Filter results: </strong>
      <select name="filter">
      <option>has photo</option>
      <option>no photo</option>
      <option>all</option>
      </select>
      <input type="submit" name="submit" value="Search" placeholder="user_name" class="button-primary">
      </form>';

      foreach($query['result'] as $result){
      if($num){
      $picture = default_pic_fallback($pic=$result['picture_thumbnail'], $size='small');
      echo "<div class='thumbnail inline-block margin-10'><div class=''>"
          //.'<a href="'.BASE_PATH.'user/?user='.$result['user_name'].'">&nbsp;&nbsp;'.$result['user_name'].'</a>'
          .'<a href="'.BASE_PATH.'index.php/user/action/show-user-profile/user/'.$result['user_name'].'" title="'.$result['user_name'].'" alt="'.$result['user_name'].'">'
          ."".'<img class="img-rounded" src="'.$picture.'">'.''
        ."</div>".
        substr($result['user_name'],0,8) ;
        if(addon_is_active('rewards')){
      $badge = get_reward_badge($result['user_name']);
      }
        echo '<br><span class="badge padding-10">'.$badge ."</span></div></a>";
      }

      }echo '</div>';
  }

function resize_userpic_small($pic='',$option='exact'){
  $r = dirname(dirname(__FILE__));
  $width=50;
  $height=50;
  $dest_folder= $r.'/files/user/small-size/'. $_SESSION['username'].'.jpg';
  //echo 'Destination folder is '.$dest_folder;
  $output = BASE_PATH.'files/user/small-size/'. $_SESSION['username'].'.jpg';
  /**$folder is the folder name, eg thumbnail, medium etc
   * $option is one of : exact, portrait, landscape, auto, crop
   * */


  // USING THE RESIZE CLASS

// *** 1) Initialise / load image
$resizeObj = new resize($pic);

// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
$resizeObj -> resizeImage($width, $height, $option);

// *** 3) Save image ('image-name', 'quality [int]')

$resizeObj -> saveImage($dest_folder, 100);

return $output;

}

function resize_userpic_medium($pic='',$option='auto'){
  $r = dirname(dirname(__FILE__));;
  $width=240;
  $height=240;
  $dest_folder= $r.'/files/user/medium-size/'. $_SESSION['username'].'.jpg';
  $output = BASE_PATH.'files/user/medium-size/'. $_SESSION['username'].'.jpg';
  /**$folder is the folder name, eg thumbnail, medium etc
   * $option is one of : exact, portrait, landscape, auto, crop
   * */


  // USING THE RESIZE CLASS

// *** 1) Initialise / load image
$resizeObj = new resize($pic);

// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
$resizeObj -> resizeImage($width, $height, $option);

// *** 3) Save image ('image-name', 'quality [int]')

$resizeObj -> saveImage($dest_folder, 100);

return $output;
}

function resize_userpic_large($pic='',$option='auto'){

  $r = dirname(dirname(__FILE__));;
  $width=600;
  $height=600;
  $dest_folder= $r.'files/user/large-size/'. $_SESSION['username'].'.jpg';
  $output = BASE_PATH.'files/user/large-size/'. $_SESSION['username'].'.jpg';
  /**$folder is the folder name, eg thumbnail, medium etc
   * $option is one of : exact, portrait, landscape, auto, crop
   * */

  // USING THE RESIZE CLASS

// *** 1) Initialise / load image
$resizeObj = new resize($pic);

// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
$resizeObj -> resizeImage($width, $height, $option);

// *** 3) Save image ('image-name', 'quality [int]')

$resizeObj -> saveImage($dest_folder, 80);

return $output;

}

function logout(){
  $user = $_SESSION['username'];
  $query = query_db("UPDATE core_user set `logged_in`='no' where user_name='{$user}'",
  'FAiled to logout! ');
  if(isset($_COOKIE[session_name()])) { # Expire session cookie
    setcookie(session_name(), '', time()-50000, '/');
	}
	
  session_destroy(); # Destroys session 
  logout_notify();
  redirect_to(BASE_PATH);
}

function process_user_registration(){
  if(isset($_POST['submit']) && $_POST['submit'] ==='register') {
    $username = str_ireplace(' ','_',strtolower(trim(sanitize($_POST['user_name']))));
    $password = trim(sanitize($_POST['password']));
    $email = trim(sanitize($_POST['email']));

    $begins_with = substr($_POST['phone'],0,1);
    if($begins_with == '0' || $begins_with == '+'){
      $phone = sanitize($_POST['phone']);
    }else if($begins_with !== '0' && $begins_with !== '+'){
      $phone = '0'. sanitize($_POST['phone']);
    }	
      $hashed_password = sha1($password);
      $bonus_funds = 0;
      $state = strtolower(trim(sanitize($_POST['state'])));
      $secret_question = trim(sanitize($_POST['secret_question']));
      $secret_answer = trim(sanitize($_POST['secret_answer']));
      $post_destination = trim(sanitize($_POST['destination']));
      $account_type = trim(sanitize($_POST['account_type']));
      $gender = trim(sanitize($_POST['gender']));
    if(isset($_POST['destination'])){
      $_SESSION['destination'] = $post_destination;
    } 
    //echo "Secret Answer = ". $secret_answer;
    $created = date('c');

    // First check for uniqueness of phone and email field
    $value = query_db("SELECT id FROM user WHERE phone='{$phone}' OR email='{$email}'",
    "Could not check uniqueness of phone / email");

    if($value['num_results'] > 0){
      if(!empty($value['result'][0]['phone'])){
        echo '<div class="alert alert-danger">Phone number already in use!</div>';
        go_back();
      } else if(!empty($value['result'][0]['email'])){
        echo '<div class="alert alert-danger">Email already in use!</div>'; 
        go_back();
      }
    } else {

      //register / add user
      $save_to_db = query_db("insert into core_user(`id`, user_name, `password`, `email`, `created_time`, `last_login`,`login_count`,`logged_in`, `phone`, `site_funds_amount`, `role`, `account_type`, `state`, `picture`, `picture_thumbnail`, `secret_question`, `secret_answer`, `status`, `bank_account_no`, `bank_name`, `full_name`,`full_name`)
       VALUES ('0', '{$username}', '{$hashed_password}', '{$email}', '{$created}', '{$created}', '0', 'no', '{$phone}', '{$bonus_funds}','authenticated','{$account_type}','{$state}','','','{$secret_question}','{$secret_answer}','not verified','0','','','{$gender}')","Registration Failed!");
      upload_user_pic();
    }

    if($save_to_db){
      if($save_to_db && addon_is_active('referrals')){
        save_referral();
        $subject = 'New referral on '.BASE_PATH;
        $message = "{$username}, who you referred has just created an account on {BASE_PATH}";
        email($referrer,$subject,$message); 
      }	
        
        
      status_message('success', 'Registration Successful!');
      echo "<div class='container'><a href='".BASE_PATH."show-login-form'><button> Login Now </button></a></div>";
    }
  }
}

function process_user_edit(){

  if(isset($_POST['submit']) && $_POST['submit'] ==='save') {
    
  $uid = $_POST['uid'];
  $password = trim(sanitize($_POST['password']));
  $confirm_password = trim(sanitize($_POST['confirm_password']));

  $begins_with = substr($_POST['phone'],0,1);
  //echo "begins with" .$begins_with;
  if($begins_with == '0' || $begins_with == '+'){
  $phone = sanitize($_POST['phone']);
  }else if($begins_with !== '0' && $begins_with !== '+'){
  $phone = '0'. sanitize($_POST['phone']);	
  }

  if($password !== $confirm_password){
    status_message("error", "Passwords do not match!");
    
    exit;
    } else {

  $hashed_password = sha1($password);


    if($_POST['role']){
    $role = trim(sanitize($_POST['role']));
    } else { $role = 'authenticated'; }

        if(!empty($_POST['email'])){
          $email = trim(sanitize($_POST['email']));
          $update_user=mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE core_user SET `email`='{$email}' WHERE `id`='{$uid}'");
          }
          
          
    if($_POST['password']===''){
      
      if(!empty($_POST['secret_answer'])){
        die ("<span class='red-text'>You must enter your password to update your secret answer!</span>" . go_back());
        
        }
      
      $update_user= query_db("UPDATE core_user SET `phone`='{$phone}', `role`='{$role}' WHERE `id`='{$uid}'","Update user query failed! ");
      
      if($update_user){
        status_message("success","Changes saved successfully!");
      }
    } else {
        
      if(empty($_POST['secret_answer'])){
      $update_user= query_db("UPDATE core_user SET `password`='{$hashed_password}', `phone`='{$phone}', `role`='{$role}' WHERE `id`='{$uid}'",
      "Could not update user in process user edit! ");
      }
      if(!empty($_POST['secret_answer'])){
      $secret_answer = trim(sanitize($_POST['secret_answer']));
      $update_user = query_db("UPDATE core_user SET `password`='{$hashed_password}', `phone`='{$phone}', `role`='{$role}', `secret_answer`='{$secret_answer}' WHERE `id`='{$uid}'","Could not update user in process user! ");	
    
      }
      
      if($update_user){
        status_message("success","Changes saved successfully!");
      }
    }
    }
  }

  if(isset($_POST['submit-account']) && $_POST['submit-account'] ==='save') {
    $username = $_SESSION['username'];
    $bank_account = sanitize($_POST['account_no']);
    $bank_name = trim(sanitize($_POST['bank']));
    $full_name = trim(sanitize($_POST['full_name']));
    $query = query_db("UPDATE core_user SET `bank_account_no`='{$bank_account}', `bank_name`='{$bank_name}', `full_name`='{$full_name}' WHERE user_name='{$username}'",
    "Failed to save account details ");
    
    if($query){
      status_message('success', 'Account details saved successfully!');
      link_to(BASE_PATH."user/?user={$username}", 'Return to profile');
    } else{ echo "<h2>Nothing happened! why?</h2>"; }
  }

}
 // end of user functions file
 // in root/core/user.php
?>
