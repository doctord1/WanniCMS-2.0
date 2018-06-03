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

function register(){
  if(isset($_GET['ref_id'])){
    $ref_id = trim(sanitize($_GET['ref_id']));
  }
  process_user_registration();
  load_view('user','create-user-form');
}

function get_user_id_by_username($user){
  $u = get_user_by_username($user);
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

function load_user($user){
  $_SESSION['current_user'] = $user;
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['username'] = $user['user_name'];
  $_SESSION['phone'] = $user['phone'];
  $_SESSION['account_type'] = $user['account_type'];
  //~ $_SESSION['site_funds_amount'] = $user['site_funds_amount'];
  $_SESSION['login_count'] = $user['login_count'];
  $_SESSION['LAST_ACTIVITY'] = time();
  $_SESSION['CREATED'] = time();

  $control = mt_rand(10000,100000);
  $_SESSION['control'] = $control;

  $_SESSION['role'] = $user['role'];
  $_SESSION['picture'] = '<a href="'.BASE_PATH .'user/action/show-user-profile/user/'.$user['user_name'] .'">'.
  '<img src="'.$user['picture'].'"></a>';
  $_SESSION['picture_thumbnail'] = '<a href="'.BASE_PATH .'user/action/show-user-profile/user/'.$user['user_name'] .'">'.
  $_SESSION['secret_question'] = $user['secret_question'];
  $_SESSION['secret_answer'] = $user['secret_answer'];
  unset($_SESSION['not_logged_in']);
  #print_r($_SESSION);

}

function process_user_login(){
  if(isset($_POST['login'])) {
    if(isset($_POST['password'])) {
      $password = trim(sanitize($_POST['password']));
      //~ print_r($_POST);
      $hashed_password = sha1($password);
    }
    if(isset($_POST['email'])) {
      $email = trim(sanitize($_POST['email']));
    }
      $begins_with = substr($_POST['phone'],0,1);
      //echo "begins with" .$begins_with;
    if(isset($_POST['phone']) && ($begins_with == '0' || $begins_with == '+')){
      $phone = sanitize($_POST['phone']);
    } else if($begins_with !== '0' && $begins_with !== '+'){
      $phone = '0'. sanitize($_POST['phone']);
    }

    //login
    $q = query_db("SELECT * FROM core_user WHERE phone='{$phone}' AND password='{$hashed_password}'",
    "Could not get user details in process login! ");

      $login_result = $q['result'][0];
      load_user($login_result);


      if(isset($_SESSION['username'])){
        $time = time();
        $user = $_SESSION['username'];


        //Upate login time
        $query = query_db("UPDATE core_user SET `last_login`='{$time}' WHERE user_name='{$user}'","Failed to update login time");

        if($query){
          $_SESSION['last_login'] = $time;
        }

        if(function_exists('get_user_personal_data')){
          $personal_data = get_user_personal_data($login_result['id']);
          if(empty($personal_data)){
            redirect_to(BASE_PATH.'user/action/add-user-personal-data/'.$login_result['user_name']);
          }
        }


        $_SESSION['status-message'] = "<div class='alert alert-success p-3 text-center'> Login successful - Welcome {$user}! </div>";
        if(!empty($_POST['destination-url'])){
          redirect_to($_POST['destination-url']);
        } else {
          //~ $_SESSION['status-message'] = '<div class="alert alert-success">Welcome </div>';
          redirect_to(BASE_PATH.'login-intent');
        }
      }
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
    if((
    isset($_GET['clean-url']['short-call']) && $_GET['clean-url']['short-call'] == 'show-user-profile')
    || (isset($_GET['clean-url']['short-call']) && $_GET['clean-url']['short-call'] == 'me')
    || (isset($_GET['clean-url']['action']) && $_GET['clean-url']['action'] == 'show-user-profile')
    || (isset($_GET['clean-url']['addon_path']) && $_GET['clean-url']['addon_path'] == 'user')){
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
  if(is_logged_in() && $_SESSION['current_user']['user_name'] == $_SESSION['username']){
    return true;
    } else {
      return false;
      }
  }


function get_num_users(){
  $q = query_db("SELECT COUNT(id) as count FROM core_user",
  "Could not get num users! ");
  return $q['result'][0]['count'];
}


function get_new_users(){
  $q = query_db("SELECT id, user_name, picture FROM core_user ORDER BY id desc limit 10",
  "Could not get new users! ");
  return $q['result'];
}


function online_users($type=''){
  if(is_logged_in()){
    $diff = 54000;
    $now = time();
    $time_limit = $now - $diff; ;

    $q = query_db("SELECT user_name,`last_login` FROM core_user WHERE last_login>='{$time_limit}' Limit 20",
    "Could not get online users! ");

    $_SESSION['temp'] = $q;

    if($type == 'pics'){
      load_view('user','online-users-pictures');
    } else {
      load_view('user','online-users-text-only');
    }
  }
}


function switch_user($username=''){

  if(!empty($username) && is_admin()){
    $morph_target = trim(sanitize($username));
    $person = get_user_by_username($morph_target);

    if(!empty($person)){
      $_SESSION['current_user'] = $person;
      $_SESSION['user_id_before_morph'] = $_SESSION['user_id'];
      $_SESSION['user_before_morph'] = $_SESSION['username'];
      $_SESSION['user_id'] = $person['id'];
      $_SESSION['username'] = sanitize($username);
      $_SESSION['role_before_morph'] = $_SESSION['role'];
      $_SESSION['role'] = $person['role'];
    }

    $_SESSION['status-message'] = '<div class="alert alert-info">Your are now viewing '.APPLICATION_NAME .' as <big>'.$morph_target .'</big>!</div>';
    redirect_to(BASE_PATH.'user/action/show-user-profile/'.$morph_target);
  }

  if(is_admin() && !is_my_profile_page()){
    $user = user_being_viewed();
    echo '<div class="d-block p-3 text-centered">
    <a href="'.BASE_PATH.'user/action/switch-user/'.$user.'">View as '.$user.'</a>
    </div>';
  }

  if(isset($_SESSION['user_before_morph'])){
    echo "<br>You are now masquerading as {$_SESSION['username']} .
    <em>
    <a href='".BASE_PATH."user/action/switch-back-to-me'>
    switch back to ".$_SESSION['user_before_morph']."
    </a>
    </em>";
  }
}


function switch_back_to_me(){
  if(isset($_SESSION['user_before_morph']) && $_SESSION['role_before_morph'] == 'admin'){
    $me = $_SESSION['user_before_morph'];
    $_SESSION['user_id'] = $_SESSION['user_id_before_morph'];
    unset($_SESSION['user_id_before_morph']);
    unset($_SESSION['user_before_morph']);
    unset($_SESSION['role_before_morph']);
    $user = get_user_by_username($me);
    load_user($user);
    redirect_to(BASE_PATH.'user/action/show-user-profile/'.$me);
  }
}


function show_user_interests(){
  $q = query_db("SELECT DISTINCT interests FROM user_personal_data",
  "Could not get interests in Show user interests! ");
  $_SESSION['user-interests'] = $q['result'];
  load_view("user","show-user-interests");
}

function show_people_interested_in($interest){
  $interest = urldecode(($interest));
  $q = query_db("SELECT user_id FROM user_personal_data WHERE interests='{$interest}'",
  "Could not get people interested in {$interest}! ");
  $_SESSION['temp']['people-interested-in'] = $q;
  $_SESSION['temp']['interest'] = $interest;
  if($q){
    load_view('user','people-interested-in');
  }
}

function get_user_by_id($uid=''){//done
  $q = query_db("SELECT * FROM core_user WHERE `id`='{$uid}' LIMIT 1","Could not get user by id! ");
  if($q['num_results'] > 0){
    return $q['result'][0];
  }
}

function get_username_by_id($uid=''){//done
  $uid = sanitize($uid);
  $q = query_db("SELECT user_name FROM core_user WHERE `id`='{$uid}'","Could not get user by id! ");
  if($q['num_results']> 0){
    return $q['result'][0]['user_name'];
  }
}

function get_user_by_username($username){//done
  $value = query_db("SELECT * FROM core_user WHERE user_name='{$username}' LIMIT 1",
  "Failed to get user by username!");
  if(!empty($value['result'][0])){
    return $value['result'][0];
  } else {
    return '';
  }
}

function set_current_user(){
  //~ $user = $_SESSION['username'];
  if(isset($_GET['clean-url']['user']) && !empty($_GET['clean-url']['user'])){
    $q = get_user_by_username($_GET['clean-url']['user']);
    $_SESSION['current_user'] = $q;
  } else if(!empty($_SESSION['username'])){
    $q = get_user_by_username($_SESSION['username']);
    $_SESSION['current_user'] = $q;
  }
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
        //~ echo '$move = '.$move;
        //~ echo ' $uploadfile = '.$uploadfile;
        if($move ==1){
          //~ echo 'moved!';
          $small_path = resize_userpic_small($pic=$rpath);
          $medium_path = resize_userpic_medium($pic=$rpath);
          echo "<div class='alert alert-success'>File is valid, and was successfully uploaded.\n</div>";
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

    if(is_my_profile_page() || is_admin() || user_has_role('manager')){

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

  if($user !== ''){
    $user = trim(sanitize($user));
  }else{ $user = $_SESSION['username']; }

  if($length != ''){
    $dimensions = "width='{$length}' height='{$length}'";
    } else {$dimensions = '';}

$query = query_db("SELECT `picture`, `picture_thumbnail` FROM core_user WHERE user_name='{$user}'",
"Unable to Select user pic! ");

if(!empty($query['result'][0])){
  $result = $query['result'][0]['picture'];
} else {
  $result['picture'] = '';
  $result['picture_thumbnail'] = '';

}
$time = time();
$pic_small = default_pic_fallback($pic=$result['picture_thumbnail'], $size='small');
$pic_medium =  default_pic_fallback($pic=$result['picture'], $size='medium');

if(is_user_page() && ($_SESSION['user_being_viewed'] == $user || is_admin())){
  $picture = '<span class="clear"><a href="'.BASE_PATH .'files/user/'.$user .'.jpg"  rel="prettyPhoto[\"'.$user.'_gal\"]">'.
  '<img src="'.$pic_medium.'?str='.$time.'" alt="user picture" id="" class="center-block img-responsive  '.$pic_class.'" '.$dimensions.'></a>
  </span><br><br>';
} else {
  $picture = '<span class="clear"><a href="'.BASE_PATH .'user/action/show-user-profile/'.$user .'">'.
  '<img src="'.$pic_medium.'?str='.$time.'" alt="user picture" id="" class="center-block img-responsive '.$pic_class.'" '.$dimensions.'></a></span>';
}
  $thumbnail = '<span class="small-pic inline-block"><a href="'.BASE_PATH .'user/action/show-user-profile/'.$user .'">'.
  '<img src="'.$pic_small.'?str='.$time.'" alt="user picture" id="profile-thumbnail" class="'.$pic_class.' img-rounded"'.$dimensions.'></a></span>';


if(!empty($user) && $user === $_SESSION['username']){
  $_SESSION['picture'] = '<a class="tooltip" href="'.BASE_PATH .'user/?user='.$user .'">'.
  '<img src="'.$pic_medium.'"></a>';
  $_SESSION['picture_thumbnail'] = '<a href="'.BASE_PATH .'user/action/show-user-profile/'.$user .'" title="'.$user .'">'.
  '<img src="'.$pic_small.'"></a>';
}

$output = array('picture'=>$picture, 'thumbnail'=>$thumbnail, 'title'=>$user);
return $output;
}

function show_user_pic($user='',$pic_class='',$length=''){
  if(is_numeric($user)){
    $target = 'id';
  } else if(is_string($user)){
    $target = 'user_name';
  }
    if($user == ''){
      $user = $_SESSION['current_user']['user_name'];
    } else {
      $user = trim(sanitize($user));
    }


  if($length != ''){
    $dimensions = 'width="'.$length.'" height="'.$length.'"';
    } else {$dimensions = '';}

  if(!empty($target)){

    $query = query_db("SELECT `picture`, `picture_thumbnail` FROM core_user WHERE {$target}='{$user}'",
    "Unable to Select user pic! ");

    if(!empty($query['result'][0])){
      $result = $query['result'][0];
    } else {
      $result['picture'] = '';
      $result['picture_thumbnail'] = '';
    }

    $result['picture'] = str_ireplace('medium-size/','',$result['picture']);
    $pic_large =  default_pic_fallback($pic=$result['picture'], $size='large');

    echo '<a href="'.BASE_PATH .'user/action/show-user-profile/'.$user .'">'.
    '<img src="'.get_resized_image($pic_large,$length).'" alt="user picture" id="" class="'.$pic_class.'"'.$dimensions.'></a>';
    }
}


function show_user_edit_link($user){
  #get_clean_url();
  if(is_logged_in() && (is_my_profile_page() || is_admin())){
    echo '<a href="' .BASE_PATH .'user/action/edit-user/user/'.$user.'"> Edit </a>';
  }
}

function show_user_delete_link($user){
  if(is_logged_in() && (is_my_profile_page() || is_admin())){
    echo '<a href="' .BASE_PATH .'user/action/delete-user/'.$user.'?control='.$_SESSION['control'].'" onclick="return confirm(\'Are you sure you want to delete this user?\');"> Delete </a>';
  }
}

function show_user_profile($username=''){
 // unset($_SESSION['current_user']['personal-data']);
  unset($_SESSION['current_user']);
  if(url_contains(BASE_PATH.'me' )){
    $username = $_SESSION['username'];
  }
  $user_result = get_user_by_username($username);
  $_SESSION['current_user'] = $user_result;
  set_current_user();
  if(!empty($username)){
    $_GET['clean-url']['user'] = $username;
  } else {
    $_GET['clean-url']['user'] = $_SESSION['username'];
  }
  //~ set_current_user();
  if(isset($_SESSION['login-intent'])){
    login_intent();
  } else {
    if(is_user_page()){
      //~ if(is_logged_in()){
        load_view('user','user-profile');
      //~ } else {
        //~ deny_access();
        log_in_to_continue(' Interact with this person');
      //~ }
    }
  }
}

function show_last_seen(){

  }

function link_to_user_by_id($user_id=''){
  $user_details = get_user_by_id($user_id);
  $username = $user_details['user_name'];
  echo '<a href="'.BASE_PATH.'user/action/show-user-profile/'.$username.'">'.$username.'</a>';
}


function link_to_user_by_username($username=''){
  $username = strtolower($username);
  $user = get_user_by_username($username);
  echo '<a href="'.BASE_PATH.'user/action/show-user-profile/'.$username.'">'.$username.'</a>';
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
      if($user == $_SESSION['username']){
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
              <br>Secret Question :<br> <span class='text-success'><h4>".$row['secret_question']."</h4></span>
              <br> Answer:<br><input type='text' class='form-control' name='secret_answer' value='' placeholder='answer'>

              <label for='interest'>Interests</label>
              <select id='interest' name='interest' class='form-control my-2'>
                <option>Looking for a room</option>
                <option>Looking for a Date</option>
                <option>Looking for a Place to go</option>
                <option>Looking for a relationship</option>
                <option>Looking for a room mate</option>
                <option>Looking for a Job</option>
                <option>Looking for a Business partner</option>
              </select>
              ";
              if($_SESSION['role']==='admin'){

                $form = $form ."<br>Role or level :<br><input type='text' name='role' class='form-control' placeholder='role or permissions level' value='".$row['role']."'>";
              }

            $form = $form ."<br><input type='submit' class='btn btn-primary' name='submit' value='save'> ";

              $form .= "</form>";
              echo $form;
            //~ set_bank_details($user);

        echo '</div>';
      } else {
        set_session_message('alert-danger','You are trying to compromise another account! <br> This will get you BANNED and BLACKLISTED! <br>
        <b class="text-warning">Dont be Evil!</b>');
        redirect_to(BASE_PATH.'deny-access');
      }
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
    <form action='".$_SESSION['current_url']."' method='POST' class='p-3'>
      <input type='number' name='phone' value='' placeholder='What is your phone number?' class='form-control'>
      <br><input type='submit' name='submit' class='btn btn-primary' value='Submit'>
    </form>
    ";
  }

  if(isset($fetched_question) && $fetched_question){
    echo "<form action='".$_SESSION['current_url']."' method='POST'>
    What is the answer to your secret Question?<br>
    <input type=text' name='secret_answer' value='' class='form-control'>
    <input type='hidden' name='secret_question' value='".$question_result['secret_question']."'>
    <input type='hidden' name='phone' value='".$phone."'>
    <input type='hidden' name='user_name' value='".$question_result['user_name']."'>
    <input type='submit' name='submit_secret_answer' class='btn btn-primary' value='Submit'>
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
      $wrong_or_right = "<span class='text-success'> Correct! </span>";

       echo "
      <big>
      <strong>{$user_name} </strong>
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
        sms_notify('system',$username,"Your new password is {$password} .Ensure that you change it to something you can remember or WRITE IT DOWN NOW!",'!mportant');
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
  if(empty($pic) && ($size === 'large' || empty($size))){
    $picture = BASE_PATH.'files/default_images/default-pic.png';
    return $picture;
  } else {
    $picture = $pic;
    return $picture;
  }

}


function show_new_users(){
  load_view('user','show-new-users');
}

function user_being_viewed(){
  if(url_contains('user/action/show-user-profile/')){
    $user = str_ireplace(BASE_PATH.'user/action/show-user-profile/','',$_SESSION['current_url']);
  } else if(url_contains(BASE_PATH.'me')){
    $user = $_SESSION['username'];
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
      $query = query_db("SELECT * FROM core_user {$condition} order by id DESC",
      "Search failed! ");
    } else {
      $query = query_db("SELECT * FROM core_user WHERE picture_thumbnail !='' ORDER BY id DESC LIMIT 30 ","Cannot fetch users !");
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
          .'<a href="'.BASE_PATH.'user/action/show-user-profile/user/'.$result['user_name'].'" title="'.$result['user_name'].'" alt="'.$result['user_name'].'">'
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

$resizeObj -> saveImage($dest_folder, 60);

return $output;

}

function resize_userpic_medium($pic='',$option='auto'){
  $r = dirname(dirname(__FILE__));;
  $width=300;
  $height=300;
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

$resizeObj -> saveImage($dest_folder, 60);

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
    $ip_address = get_user_ip_address();
    $ip_location = get_user_location_by_ip($ip_address);
    $location = $ip_location['country_name'].','.$ip_location['region_name'].','.$ip_location['city'];
    $username = str_ireplace(' ','_',strtolower(trim(sanitize($_POST['user_name']))));
    $password = trim(sanitize($_POST['password']));
    $email = trim(sanitize($_POST['email']));
    $account_type = sanitize($_POST['account-type']);

    $allowed_file_type = ['.jpg','.png','.jpeg'];
    $photo = upload_file2('user',$allowed_file_type);

    $begins_with = substr($_POST['phone'],0,1);
    if($begins_with == '0' || $begins_with == '+'){
      $phone = sanitize($_POST['phone']);
    } else if($begins_with !== '0' && $begins_with !== '+'){
      $phone = '0'. sanitize($_POST['phone']);
    }
      $hashed_password = sha1($password);
      $date_of_birth = sanitize($_POST['date_of_birth']);
      $dob = explode('-',$date_of_birth);
      //~ print_r($dob);die();

      $year_of_birth = $dob[0];
      $month_of_birth = $dob[1];
      $day_of_birth = $dob[2];

      $secret_question = trim(sanitize($_POST['secret_question']));
      $secret_answer = trim(sanitize($_POST['secret_answer']));
      $gender = trim(sanitize($_POST['gender']));

    if(isset($_POST['destination'])){
      $_SESSION['destination'] = $post_destination;
    }
    //echo "Secret Answer = ". $secret_answer;
    $created = date('c');

    // First check for uniqueness of phone and email field
    $value = query_db("SELECT id, phone, email FROM core_user WHERE phone='{$phone}' OR email='{$email}'",
    "Could not check uniqueness of phone / email");

    if($value['num_results'] > 0){
      if(!empty($value['result'][0]['phone'])){
        $_SESSION['status-message'] = '<div class="alert alert-danger">Phone number already in use!</div>';
        redirect_to(BASE_PATH.'register');
      } else if(!empty($value['result'][0]['email'])){
        $_SESSION['status-message'] = '<div class="alert alert-danger">Email already in use!</div>';die('Hatsup');
        redirect_to(BASE_PATH.'register');
      }
    } else {
      //register / add user
      $save_to_db = query_db("INSERT INTO `core_user`(`id`, `user_name`,
      `password`, `email`, `created_time`, `last_login`, `login_count`,
      `logged_in`, `phone`, `ip_address`, `last_login_ip`, `role`, `picture`,
      `picture_thumbnail`, `secret_question`, `secret_answer`, `status`,
      `full_name`, `gender`,`year_of_birth`,`month_of_birth`,`day_of_birth`,
      `location_by_ip`,`account_type`)
       VALUES ('0', '{$username}', '{$hashed_password}', '{$email}', '{$created}',
       '{$created}', '0', 'no', '{$phone}', '{$ip_address}','','authenticated',
       '{$photo}','','{$secret_question}','{$secret_answer}','not verified','',
       '{$gender}','{$year_of_birth}','{$month_of_birth}','{$day_of_birth}',
       '{$location}','{$account_type}')","Registration Failed!");
      //~ upload_user_pic();

      $message = urlencode("{$username} you have just created an account on ".BASE_PATH." your password is '{$password}'");
      email($email,'New account on '.BASE_PATH,$message);
      sms_notify($phone,$message);
       if($save_to_db){
        $_SESSION['status-message'] =  '<div class="alert alert-success">Registration Successful! Login now</div>';
        redirect_to(BASE_PATH.'login');
      }
    }
  }
}

function process_user_edit(){

  if(isset($_POST['submit']) && $_POST['submit'] ==='save') {

  $uid = sanitize($_POST['uid']);

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
    $role = str_ireplace('',',',trim(sanitize($_POST['role'])));
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

      $interest = trim(sanitize($_POST['interest']));
      if(!empty($interest)){

        $update_user = query_db("Update user_personal_data SET interests='{$interest}' WHERE id='{$uid}'",
        "Could not update user interest! ");
      }

      if($update_user){
        $_SESSION['status-message'] = '<div class="alert alert-success">Changes saved successfully!</div>';
        redirect_to($_SESSION['current_url']);
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
