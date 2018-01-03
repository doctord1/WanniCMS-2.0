<?php

# Register FORM
function register(){
  if(isset($_GET['ref_id'])){
  $ref_id = trim(mysql_prep($_GET['ref_id']));
  }
   //~ echo '<section class="pull-left"><ul><li id="add_page_form_link" class="float-right-lists"><a href="'.BASE_PATH .'user/?action=login">Login </a>' .
   //~ '</li><li id="add_page_form_link" class="float-right-lists"><a href="' .BASE_PATH .'user/?action=register">Register </a></li></ul></section>' ;

      echo "<div class='login-form' align='center'>" .
"<h1>Signup</h1>" .
'<form action="process.php" method="post" style="max-width: 500px">' .
'
<br>
State:<br><select name="state" class="form-control" placeholder="State">
<option>Abia</option>
<option >Abuja</option>
<option >Adamawa</option>
<option >Anambra</option>
<option >Akwa Ibom</option>
<option >Bauchi</option>
<option >Bayelsa</option>
<option >Benue</option>
<option >Borno</option>
<option >Cross River</option>
<option >Delta</option>
<option >Edo</option>
<option >Ebonyi</option>
<option >Enugu</option>
<option >Ekiti</option>
<option >Gombe</option>
<option >Imo</option>
<option >Jigawa</option>
<option >Kaduna</option>
<option >Kano</option>
<option >Katsina</option>
<option >Kebbi</option>
<option >Kwara</option>
<option >Kogi</option>
<option >Lagos</option>
<option >Nasarawa</option>
<option >Niger</option>
<option >Ogun</option>
<option >Ondo</option>
<option >Osun</option>
<option >Oyo</option>
<option >Plateau</option>
<option >Rivers</option>
<option >Sokoto</option>
<option >Taraba</option>
<option >Yobe</option>
<option >Zamfara</option>
</select><br>
Email: <br><input class="form-control" type="email" name="email" id="email" placeholder="Email" required><br>
Username:<br> <input class="form-control" type="text" name="user_name" id="user_name" placeholder="username/studio name " required><br>' .
'Password:<br> <input class="form-control" type="password" name="password" id="password" placeholder="password is case sensitive" required><br>' .
'Confirm Password:<br> <input class="form-control" type="password" name="password" id="password" placeholder="password" required><br>' .
'Phone No.:<br> <input class="form-control" type="tel" name="phone" placeholder="phone number" required><br>' .
'Write a Secret Question.:<br>'.
'<span class="tiny-text">This is the question you will be asked, if you forget your password and cannot log in ... </span><br>'.
'<input class="form-control" type="text" name="secret_question" placeholder="secret question" required><br>' .
'Answer your Secret Question.:<br>'.
'<span class="tiny-text" align="center">This is the answer you MUST provide to reset your password!</span><br>'.
'<input class="form-control" type="text" name="secret_answer" placeholder="answer" required><br>' .
'<input class="form-control" type="hidden" name="funds" value="0">' .
'<input class="form-control" type="hidden" name="logged_in" value="no">' .
'<select class="form-control" name="gender">
<option>Male</option>
<option>Female</option>
</select><br>
Referred by : <br>
<select name="ref_id" class="form-control">
<option></option>';
$value = query_db("SELECT * FROM payplay_studios","Could not select studios! ");
foreach($value['result'] as $studio){
  $ref_id = get_user_by_id($studio['user_id']);
  echo $ref_id;
  echo '<option>'.$ref_id.'</option>';
}
echo '</select><br>
<input type="submit" name="submit" value="register" class="submit btn btn-primary"><br>' .
'</form>' .
'</div>' ;
}



function get_user_id_from_username($user){
  $u = get_user_details($user);
  $user_id = $u['id'];
  return $user_id;
}

function delete_user($user=''){

  //~ if(!empty($_GET['delete']) && !empty($_GET['id']) && !empty($_GET['control'])){
    if((is_my_profile_page() || is_admin())
    && $_SESSION['control'] == $_GET['clean-url']['control']){

      $user = trim(sanitize($_GET['clean-url']['user']));

      $query = query_db("DELETE FROM `user` WHERE `id`={$id}",
      'Failed to delete the User account.! ');

      if($query){
        $artiste_id = get_artiste_details_by_user_id($id);
        $scout_id = get_scout_details_by_user_id($id);
        query_db("DELETE FROM music WHERE uploader_id='{$id}'",
        "Could not delete artiste music! ");
        query_db("DELETE FROM payplay_artiste WHERE user_id='{$id}'",
        "Could not delete artiste! ");
        query_db("DELETE FROM payplay_scouts WHERE user_id='{$id}'",
        "Could not delete scout! ");
        query_db("DELETE FROM payplay_studios WHERE user_id='{$id}'",
        "Could not delete studio account! ");
        query_db("DELETE FROM payplay_scout_watchlist WHERE scout_id='{$scout_id}'",
        "Could not delete scout watchlist! ");
        query_db("DELETE FROM payplay_scout_watchlist WHERE artiste_id='{$artiste_id}'",
        "Could not delete scout watchlist! ");
        query_db("DELETE FROM payplay_fav_artiste WHERE artiste_id='{$artiste_id}'",
        "Could not delete fav artiste! ");
        query_db("DELETE FROM referrals WHERE referrer='{$user}'",
        "Could not delete fav artiste! ");
        query_db("DELETE FROM referrals WHERE referree='{$user}'",
        "Could not delete fav artiste! ");
        if(is_my_profile_page()){
        session_destroy();
        }
        redirect_to(BASE_PATH);
        }
      }

  //~ }
}


#  Show the log in form
function show_login_form($action=''){ // LARGELY INCOMPLETE

   if(!isset($_SESSION['username']) && $query_string !== 'forgot_password'){

  echo '<div class="row">';


    echo "<div class='login-form col-md-6 col-xs-12 p-5 m-2 mt-5 well'>" .
"<h1 align='center'> Login </h1>" .
'<form action="'.BASE_PATH .'index.php/user/action/process-user" method="post" >' .
'Phone: <br><input type="number" name="phone" id="user_name" class="form-control" placeholder="Phone number" width="40px"><br>' .
'Password: <br><input type="password" name="password" id="password" class="form-control" placeholder="password"><br>' ;
   if(url_contains('redirect_to')){
     $destination = mysql_prep($_GET['redirect_to']);
     //echo '<input type="hidden" name="destination" value="'.$destination.'">';
     $_SESSION['destination'] = $destination;
   }

  echo '<br><input type="submit" name="submit" value="login" class="btn btn-primary btn-large">
  <small><a href="'.BASE_PATH.'user/?forgot_password">Forgot password?</a></small><br>' .
  '</form>' .
  '</div>' ;

  echo '<div class="col-md-5 col-xs-12 well">';
  echo '<br><img class="col-md-12 col-xs-12 img-responsive" src="'.BASE_PATH.'uploads/files/default_images/payplay-image3.jpg">';
  echo '<img class="col-md-12 col-xs-12 img-responsive" src="'.BASE_PATH.'uploads/files/default_images/payplay-image1.jpg">';
  echo '</div>';
  echo '</div>';
  }
}

function login_successful(){

  if (isset($_SESSION['username']) && $_GET['login'] =='true'){
    $id = $_SESSION['id'];
    $login_count = $_SESSION['login_count'];
    $login_time = time();

      if(isset($login_count)){
      #echo "<br><br><br>"  .$login_count ."<br><hr>";  // Testing purposes

      if(!isset($_SESSION['do_not_update_login_count'])){
        $login_count_plus = $login_count + 1;

        $update_login_count = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `last_login`='{$login_time}', `login_count`={$login_count_plus}, `logged_in`='yes' WHERE `id`={$_SESSION['user_id']}")
        or die("Could not update Login count!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

        if($update_login_count){
          $_SESSION['do_not_update_login_count'] = 'true';
          echo "<div class='success'> You are now Logged in! </div>";
        }

        if($_SESSION['login_count'] < '1'){
      status_message('success', 'Congrats!! You have just beeen awarded 20 site funds!! ');
      $amt = $_SESSION['site_funds_amount'];
      $bonus = $amt + 20;

      $give_bonus =  mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `site_funds_amount`={$bonus} WHERE `id`={$_SESSION['user_id']}")
      or die("Could not update Site funds!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));

        if($give_bonus){
          #echo "Bonus given"; //testing
          $update_amount = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT site_funds_amount FROM user WHERE id={$_SESSION['user_id']}")
          or die ("Could not fetch updated Site funds!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
        }
        if($update_amount){
          #echo "amount updated";//testing
          $fetched = mysqli_fetch_array($update_amount);
          $_SESSION['site_funds_amount'] = $fetched['site_funds_amount'];

          }
        }
      }
    }
  }
  if(!empty($_SESSION['destination'])){
    $destination = $_SESSION['destination'];
    unset($_SESSION['destination']);
    $_SESSION['status_message']= '<div class="success">You are now Logged in as <b>'.$_SESSION['username'].'</b></div>';
    redirect_to($destination);
    }
}



function logout_notify(){

    if(isset($_GET['logout']) && $_GET['logout'] ==='true'){ # If logged out, Notify of logout success

    echo "<div class='alert alert-danger'>You are now logged out!</div>";

    }
}

function greet_user(){
  if (isset($_SESSION['username'])){
    $output = "Hello " .ucfirst($_SESSION['username']) ."!";
  return $output;
}
  }


function is_user_page(){
  if(isset($_GET['clean-url'])){
    if($_GET['clean-url']['action'] == 'show-user-profile' || $_GET['clean-url']['addon_path'] == 'user'){
        return true;
    }else{
        return false;
    }
  }
}

function is_logged_in(){
	if(isset($_SESSION['username']) && $_SESSION['free_view_count'] < 2){
		return true;
		} else {
			return false;
			}
}	

function is_my_profile_page(){
  if($_GET['clean-url']['user'] == $_SESSION['username']){
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

  $query = mysqli_query($GLOBALS["___mysqli_ston"],"SELECT `user_name`,`last_login` FROM `user` WHERE last_login>='{$time_limit}' Limit 20")
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
    $morph_target = trim(mysql_prep($_POST['username_to_morph_as']));
    $person = get_user_details($morph_target);

    if(!empty($person)){

    $_SESSION['user_before_morph'] = $_SESSION['username'];
    $_SESSION['username'] = trim(mysql_prep($_POST['username_to_morph_as']));
    $_SESSION['role'] = $person['role'];

    }

  session_message('alert', 'Your are now viewing '.APPLICATION_NAME .' as <big>'.$morph_target .'</big>!');
  redirect_to(BASE_PATH.'user/?user='.$morph_target);

  }

  if(isset($_GET['morph_target'])){
    $morph_target = trim(mysql_prep($_GET['morph_target']));
    $person = get_user_details($morph_target);

    if($_GET['morph_string'] == $_SESSION['control']){

    unset($_SESSION['user_before_morph']);
    $_SESSION['username'] = trim(mysql_prep($morph_target));
    $_SESSION['role'] = $person['role'];

    session_message('alert', 'Your are now viewing '.APPLICATION_NAME .' as <big>'.$morph_target .'</big>!');
    redirect_to(BASE_PATH.'?page_name=home');

    }
  }

  if(is_admin()){
    $action = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    if(isset($_GET['user']) && $_GET['user'] !== $_SESSION['username']){

      $user= trim(mysql_prep($_GET['user']));
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

function get_user_by_id($uid=''){
  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `user` WHERE `id`='{$uid}' LIMIT 1")
  or die('Failed to "get user by id "'.mysqli_error($GLOBALS["___mysqli_ston"]));
  $result = mysqli_fetch_array($query);
  $username = $result['user_name'];
  //$linked_username = '<a href="'.BASE_PATH.'user/?user='.$username.'">'.$username.'</a>';
  return $username;
}

function get_user_details($user){
  $user = trim(mysql_prep($user));
  $value = query_db("SELECT * FROM `user` WHERE `user_name`='{$user}' LIMIT 1",
  'Failed to "get user details "');

  unset($value['result'][0]['2']);
  unset($value['result'][0]['password']);
  return $value['result'][0];
  }


function get_user_details_by_id($uid=''){
  $value = query_db("SELECT * FROM `user` WHERE `id`='{$uid}' LIMIT 1",
  'Failed to "get user by id ');
  return $value['result'][0];
  }

function upload_user_pic(){
if (isset($_SESSION['username'])){
global $r;
$r2 = str_ireplace('/regions/','',$r);
    $r = $r2;
$submit =  $_POST['submit'];
$uploaddir = $r.'/uploads/files/user/';
$uploadfile = $uploaddir . $_SESSION['username'].'.jpg';
$path = BASE_PATH .'uploads/files/user/'. $_SESSION['username'].'.jpg';
$rpath = $r.'/uploads/files/user/'. $_SESSION['username'].'.jpg';
$user = $_GET['user'];

# ONSUBMIT
if (isset($submit)){
   $type = $_FILES['image_field']['type'];
   $name = $_SESSION['username'];

   $path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

   if (isset($_SESSION['username']))
   { $parent = $_SESSION['username'];
   $move = move_uploaded_file($_FILES['image_field']['tmp_name'], $uploadfile);
  //echo '$move = '.$move;
  //echo ' $uploadfile = '.$uploadfile;
    if($move ==1)
      {
      $small_path = resize_userpic_small($pic=$rpath);
      $small_path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $small_path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

      $medium_path = resize_userpic_medium($pic=$rpath);
      $medium_path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $medium_path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));


      echo "<div class='success'>File is valid, and was successfully uploaded.\n</div>";
    $query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `picture`='{$medium_path}', `picture_thumbnail`='{$small_path}'
    WHERE `user_name`='{$user}'")
    or die("Could not save Picture!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

    //if($query) { echo "Succesfully saved to DB!";} testing
  } else {
    echo "<div class='error'>Error : No file uploaded!\n</div>";
  }

  }

}
//echo 'Here is some more debugging info:' .$_FILES['image_field']['error']; //testing





  # UPLOAD FORM
  if($_SESSION['username']){

    if($user === $_SESSION['username'] || $_SESSION['role']==='admin' || $_SESSION['role']==='manager'){

    echo '<h2> Add / Change Picture </h2>
    <form action="'.htmlentities($SERVER["PHP_SELF"]) .'" method="post" enctype="multipart/form-data" class="form form-vertcal">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <p align="center"><input type="hidden" name="MAX_FILE_SIZE" value="5000000" /></p>
    <!-- Name of input element determines name in $_FILES array -->
    <input type="file" size="500" name="image_field"  value="">
    <input type="submit" name="submit" value="upload" class="submit">
    </form>';
    }

    $p = show_user_pic($user, 'thumbnail', '200px');
    echo $p['thumbnail'] .'<br>Current pic';
  }
}
}


function get_user_pic($user='',$pic_class='',$length=''){

  // if reward addon is active, Get reward badge
  if(addon_is_active('rewards')){
    $badge = get_reward_badge($user);
    }

  if($user !== ''){
    $user = trim(mysql_prep($user));
  }else{ $user = $_SESSION['username']; }

  if($length != ''){
    $dimensions = "width='{$length}' height='{$length}'";
    } else {$dimensions = '';}

$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `picture`, `picture_thumbnail` FROM `user` WHERE `user_name`='{$user}'")
or die("Unable to Select user pic!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

$result = mysqli_fetch_array($query);
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
    $user = trim(mysql_prep($user));
  }else{ $user = $_SESSION['username']; }

  if($length != ''){
    $dimensions = 'width="'.$length.'" height="'.$length.'"';
    } else {$dimensions = '';}

$query = query_db("SELECT `picture`, `picture_thumbnail` FROM `user` WHERE `user_name`='{$user}'",
"Unable to Select user pic! ");

$result = $query['result'][0];
//~ $time = time();

$result['picture'] = str_ireplace('medium-size/','',$result['picture']);
$pic_small = default_pic_fallback($pic=$result['picture_thumbnail'], $size='small');
$pic_medium =  default_pic_fallback($pic=$result['picture'], $size='medium');
$pic_large=  default_pic_fallback($pic=$result['picture'], $size='large');

echo '<a href="'.BASE_PATH .'index.php/user/action/show-user-profile/user/'.$user .'">'.
'<img src="'.$pic_large.'" alt="user picture" id="" class="col-md-12 col-xs-12 img-responsive '.$pic_class.'"'.$dimensions.'></a>';
//~ echo '<span class="badge padding-5">'.$badge.'</span>';
}

function show_user_thumbnail($user='',$pic_class='',$length=''){
  // if reward addon is active, Get reward badge
  if(addon_is_active('rewards')){
    $badge = get_reward_badge($user);
    }
  if($user !== ''){
    $user = trim(mysql_prep($user));
  }else{ $user = $_SESSION['username']; }

  if($length != ''){
    $dimensions = 'width="'.$length.'" height="'.$length.'"';
    } else {$dimensions = '';}

$query = query_db("SELECT `picture`, `picture_thumbnail` FROM `user` WHERE `user_name`='{$user}'",
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
if (is_logged_in()){
  if($_GET['clean-url']['user'] === $_SESSION['username'] || is_admin()){
    echo '<a href="' .BASE_PATH .'index.php/user/action/edit-user/user/'.$_GET['clean-url']['user'].'"> Edit </a>';
    }
  }
}

function show_user_delete_link(){
if (is_logged_in()){
  if($_GET['clean-url']['user'] == $_SESSION['username'] || is_admin()){
    $url_user = trim(sanitize($_GET['clean-url']['user']));
    $user = get_user_details($url_user);
    echo '<a href="' .BASE_PATH .'user/process.php?user='.$_GET['user'].'&delete=delete_user&id='.$user['id'].'&control='.$_SESSION['control'].'" onclick="return confirm(\'Are you sure you want to delete this user?\');"> Delete </a>';
    }
  }
}

function show_user_profile($user=''){
  if($user===''){$user = trim($_GET['clean-url']['user']);}

  //~ echo 'user is '. $user;
  if(is_my_profile_page() || is_admin()){
    echo '<div class="col-md-12 text-muted col-xs-12 p-2 m-2">';
    show_user_edit_link();
    echo ' | ';
    show_user_delete_link();
  }
    
  echo '</div>';
  echo '<div class="row">';
    echo '<div class="col-md-5 col-xs-12 text-center">';
      show_user_pic($user);
      echo '<p align="center " class="px-5">'; 
      $user = $_GET['clean-url']['user'];
        show_num_followers($user);
        echo ' | ';
        show_num_followed($user);
      echo ' </p>';
      echo '<div class="text-center">';
        follow_user();
        unfollow_user();  
      echo '</div>';
      
    echo '</div>';
     
  
    
    
    echo '<div class="col-md-6 col-xs-12 ">';
    show_artiste_biography(); 
    echo '</div>';
    
    echo '<div class="col-md-12 col-xs-12 p-4 well text-center"> Ad slot user';
    
    echo '</div>';
    
    echo '<div class="col-md-12 col-xs-12 p-3">';
    //~ if(is_user_page()){ 
      //~ get_url_content(BASE_PATH.'index.php/payplay/action/show-artiste-biography');
      get_url_content(BASE_PATH.'index.php/payplay/action/show-links-to-my-songs/');
      //~ show_links_to_my_songs();
    //~ }
    echo '</div>';
    
    echo '<div class="col-md-7 col-xs-12 m-1 ">';
    if(is_my_profile_page()){ 
      //~ get_url_content(BASE_PATH.'index.php/payplay/action/show-artiste-biography');
      
    }
    echo '</div>';
    
  if (isset($_SESSION['username'])){
    $q = query_db("SELECT * FROM `user` WHERE `user_name`='{$user}'",
    "USER Selection failed! ");
    
    if($q){
      $row = $q['result'][0];
      $_SESSION['user_being_viewed_id'] = $row['id'];
      $_SESSION['user_being_viewed'] = $row['user_name'];
    }
  
  }

    // LAST sEEN

      $last_seen = round(((time() - $row['last_login']) / 60 ),0);
      if($last_seen < 1 ){
        $last_seen = 'Online';
        $suffix = " !";
      }else if($last_seen > 1 && $last_seen < 59 ){
        $suffix = " mins ago!";
      }else if($last_seen > 59 && $last_seen < 1439){
        $suffix = " hours ago!";
        $last_seen = round((($last_seen / 60)),0);
      }else if($last_seen > 1439 && $last_seen < 43169){
        $suffix = " days ago!";
        $last_seen = round((($last_seen / 24) / 60),0);
      }else if($last_seen >= 43169){
        $suffix = " months ago!";
        $last_seen = round(((($last_seen / 30) / 24) / 60),0);
        }

     
      //~ echo "<div class='col-md-12 col-xs-12 px-3 mx-2'>
      //~ <em>Last seen ". $last_seen . $suffix ."</em>
      //~ </div>";
      

      
      //~ if(addon_is_active('messaging')){

        //~ $unread = display_unread_messages();
        //~ if($unread['count'] !== 0  && $_SESSION['username'] === $_SESSION['user_being_viewed']){
        //~ $_SESSION['new_message_count'] = $unread['count'];

        //~ echo '<br><strong> Messages :</strong><big> <a href="'.
         //~ ADDONS_PATH .'messaging">'. $unread['count'] .'</big>  unread </a>';
        //~ }
      //~ }
      
      
  }

function link_to_user_by_id($user_id=''){
  $user_details = get_user_details_by_id($user_id);
  $username = $user_details['user_name'];
  echo '<a href="'.BASE_PATH.'index.php/user/action/show-user-profile/user/'.$username.'">'.$username.'</a>';
  }


function link_to_user_by_username($username=''){
  $username = strtolower($username);
  $user = get_user_details($username);
  echo '<a href="'.BASE_PATH.'index.php/user/action/show-user-profile/user/'.$username.'">'.$username.'</a>';
  }


function list_users(){
if (isset($_SESSION['username'])){
  if(empty($_GET['user']) && !empty ($_SESSION['username'])){
  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM  `user` ORDER BY  `id` DESC
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
  
  if(isset($_SESSION['username'])){
    if(isset($_GET['clean-url']['user'])){
      $user = sanitize($_GET['clean-url']['user']);
      
    if($_GET['clean-url']['action'] !== 'set-bank-details'){
      echo '<div class="col-md-5 col-xs-12 p-2 m-3">';
        echo "<h3 class='p-2 m-2'>Edit "; link_to_user_by_username($user);
        echo "'s account </h3><hr>";

        upload_user_pic(); # Allows users, admins, or managers to upload pics to user profiles.

        # FETCH USER DETAILS
        $fetch_user= query_db("SELECT * FROM `user` WHERE `user_name`='{$user}'",
        "No such user! ");

        $row = $fetch_user['result'][0];

            $form = "<form method='post' action='process.php'>
                <input type='hidden' name='uid' value='{$row['id']}'>";

            if($_GET['field'] !== 'profile_pic'){
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
            }
            $form .= "</form>";
            echo $form;
          }

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

  //print_r($_POST);
  $query_string = $_SERVER['QUERY_STRING'];


  if($query_string === 'forgot_password'){
  echo '<div class="main-content-region well">';
  echo "<h2>You forgot your password?</h2>";

    if(isset($_POST['phone'])){
      $phone = mysql_prep($_POST['phone']);
      $question_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `secret_question`, `user_name` FROM `user` WHERE `phone`='{$phone}'")
      or die (((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

      if($question_query){
      $question_result = mysqli_fetch_array($question_query);
      $_SESSION['forgot']['username'] = $question_result['user_name'];

      echo "<br><big><p align='center'><strong>{$_SESSION['forgot']['username']} </strong>Your secret question is : ". $question_result['secret_question'].
    " ";
      $fetched_question = true;
      }

    } else if(!isset($_POST['secret_question'])) {
      echo "<form action='".BASE_PATH."user/?forgot_password' method='POST'>
      <input type='number' name='phone' value='' placeholder='What is your phone number?'>
      <input type='submit' name='submit' class='button-submit' value='Submit'>
      </form>";
      }

    if($fetched_question){
      echo "<form action='".BASE_PATH."user/?forgot_password' method='POST'>
      What is the answer to your secret Question?<br>
      <input type=text' name='secret_answer' value=''>
      <input type='hidden' name='secret_question' value='".$question_result['secret_question']."'>
      <input type='hidden' name='userphone' value='".$phone."'>
      <input type='hidden' name='username' value='".$question_result['user_name']."'>
      <input type='submit' name='submit_secret_answer' class='button-submit' value='Submit'>
      </form>";
    }

     if(isset($_POST['submit_secret_answer'])){
      $secret_question = trim(mysql_prep($_POST['secret_question']));
      $secret_answer = trim(mysql_prep($_POST['secret_answer']));
      $phone = trim(mysql_prep($_POST['userphone']));
      $username = trim(mysql_prep($_POST['username']));
      $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `secret_answer` FROM `user` WHERE user_name='{$username}' and `secret_question`='{$secret_question}'")
      or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

      $answer_result = mysqli_fetch_array($query);
      //compare values
      if($answer_result['secret_answer'] === $secret_answer){
        $wrong_or_right = "<span class='green-text'> Correct! </span>";
        } else {$wrong_or_right = "<span class='red-text'> incorrect! </span>";}


      echo "<br><big><p align='center'><strong>{$username} </strong>Your secret question is : \"". $question_result['secret_question']."
    \" <strong>".$_POST['secret_answer']."</strong> and the secret answer you supplied is {$wrong_or_right}</p></big>";

      if($answer_result['secret_answer'] === $secret_answer){
      //RESET PASSWORD
      $password = random_password();
      $new_password = sha1($password);
      $query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `password`='{$new_password}' WHERE `phone`='{$phone}' AND `secret_answer`='{$secret_answer}'")
      or die("Failed to generate new password!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

      if($query){
        echo "Your new password is : <big><strong>{$password}</strong></big> <br>";
        echo "Ensure that you change it to something you can remember or WRITE IT DOWN NOW!";
        sms_notify('system',$username,"Your new password is {$password} .Ensure that you change it to something you can remember or WRITE IT DOWN NOW!",'!mportant');
        }
      } else { go_back(); }

    }
  echo '</div>';
  }
}

function random_password( $length = 8 ) {
// random password by http://hughlashbrooke.com/2012/04/23/simple-way-to-generate-a-random-password-in-php/

$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
$password = substr ( str_shuffle ( str_repeat ( $chars ,$length ) ), 0, $length );
return $password;
}

function login_register_switcher(){
  if (!isset($_SESSION['username'])){
   $action = $_GET['action'];

     // check that selected page is login page
     if($action === 'login') {
    // show login form if login is clicked in menu
    show_login_form();
  }elseif($action === 'register') {
    // show register form if requested page is register
    register();
  }else {
    //show_login_form();
    }
  }
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
  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `user_name`, `picture_thumbnail` FROM `user` ORDER BY `id` DESC LIMIT 6")
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
  if(url_contains('user/?user=')){
    if(!empty($_GET['user'])){
      $user = $_GET['user'];
      }
    } return $user;
  }


function user_search(){
  
  //~ if($_GET['clean-url']['action'] == 'show-users'){
    //~ echo 'inside user search';
    $show_more_pager = pagerize($start='',$show_more=20);
      $limit = $_SESSION['pager_limit'];
    if(isset($_POST['filter'])){

      $filter = trim(mysql_prep($_POST['filter']));
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
  global $r;
  $width=50;
  $height=50;
  $dest_folder= $r.'/uploads/files/user/small-size/'. $_SESSION['username'].'.jpg';
  //echo 'Destination folder is '.$dest_folder;
  $output = BASE_PATH.'uploads/files/user/small-size/'. $_SESSION['username'].'.jpg';
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
  global $r;
  $width=240;
  $height=240;
  $dest_folder= $r.'/uploads/files/user/medium-size/'. $_SESSION['username'].'.jpg';
  $output = BASE_PATH.'uploads/files/user/medium-size/'. $_SESSION['username'].'.jpg';
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

  global $r;
  $width=600;
  $height=600;
  $dest_folder= $r.'uploads/files/user/large-size/'. $_SESSION['username'].'.jpg';
  $output = BASE_PATH.'uploads/files/user/large-size/'. $_SESSION['username'].'.jpg';
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
  $query = query_db("UPDATE user set `logged_in`='no' where user_name='{$user}'",
  'FAiled to logout! ');
  if(isset($_COOKIE[session_name()])) { # Expire session cookie
    setcookie(session_name(), '', time()-50000, '/');
	}
	
  session_destroy(); # Destroys session 
  logout_notify();
  redirect_to(BASE_PATH);
}

function process_user(){
  if(isset($_POST['submit']) && $_POST['submit'] ==='register') {
$username = str_ireplace(' ','_',strtolower(trim(mysql_prep($_POST['user_name']))));
$password = trim(mysql_prep($_POST['password']));
$email = trim(mysql_prep($_POST['email']));

$begins_with = substr($_POST['phone'],0,1);
//echo "begins with" .$begins_with;
if($begins_with == '0' || $begins_with == '+'){
$phone = mysql_prep($_POST['phone']);
}else if($begins_with !== '0' && $begins_with !== '+'){
$phone = '0'. mysql_prep($_POST['phone']);
}	
$hashed_password = sha1($password);
$bonus_funds = 0;
$state = strtolower(trim(mysql_prep($_POST['state'])));
$secret_question = trim(mysql_prep($_POST['secret_question']));
$secret_answer = trim(mysql_prep($_POST['secret_answer']));
$post_destination = trim(mysql_prep($_POST['destination']));
$account_type = trim(mysql_prep($_POST['account_type']));
$state = trim(mysql_prep($_POST['state']));
if(isset($_POST['destination'])){
	$_SESSION['destination'] = $post_destination;
	} 
//echo "Secret Answer = ". $secret_answer;
$created = date('c');
$referrer = trim(mysql_prep($_POST['ref_id']));
// testing only

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
$save_to_db = mysqli_query($GLOBALS["___mysqli_ston"], "insert into `user`(`id`, `user_name`, `password`, `email`, `created_time`, `last_login`,`login_count`,`logged_in`, `phone`, `site_funds_amount`, `role`, `account_type`, `state`, `picture`, `picture_thumbnail`, `secret_question`, `secret_answer`, `status`, `bank_account_no`, `bank_name`, `full_name`)
 VALUES ('0', '{$username}', '{$hashed_password}', '{$email}', '{$created}', '{$created}', '0', 'no', '{$phone}', '{$bonus_funds}','authenticated','{$account_type}','{$state}','','','{$secret_question}','{$secret_answer}','not verified','0','','')") 
or die("<div class='error'>Registration failed!</div>" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

upload_user_pic();
}

if($save_to_db){
  if($save_to_db && addon_is_active('referrals')){
		save_referral();
		$subject = 'New referral on Payplay.com.ng';
		$message = "{$username}, who you referred has just created an account on www.payplay.com.ng";
		email($referrer,$subject,$message); 
	}	
		//~ status_message('success', 'Registration Successful!');
		//~ echo "<div class='container'><a href='".BASE_PATH."user/?action=login'><span class='btn btn-success '> Login Now </span></a></div>";
  
  //~ Set account type
  $user = get_user_details($username);
  $user_id = $user['id'];
  if(!empty($account_type)){
    $account_type = trim(mysql_prep($_POST['account_type']));
    $value = query_db("SELECT account_type FROM user WHERE id='{$user_id}'",
    "Could not get account type in user account creation ");
    $acc_type = $value['result'][0]['account_type'];
    
    if($account_type == 'studio'){
      $q = query_db("INSERT INTO `payplay_studios`(`id`, `user_id`) 
      VALUES ('0','{$user_id}')",
      "Could not insert studio account! ");
    }
    
    if($account_type == 'artiste'){
      if(empty($acc_type)){
        $q = query_db("UPDATE user set account_type='{$account_type}' WHERE id='{$user_id}'",
        "Could not update account_type ");
      }
      $q = query_db("INSERT INTO `payplay_artistes`(`id`, `user_id`, `artiste_stage_name`, `biography`, `status`, `expiry_date`) 
      VALUES ('0','{$user_id}','','','inactive','')",
      "Could not set artiste id in force account type selection ");
    } else if($account_type == 'scout'){
      if(empty($acc_type)){
        $q = query_db("UPDATE user set role='{$new_roles}' WHERE id='{$user_id}'",
        "Could not update {$account_type} roles ");
      }
      $q = query_db("INSERT INTO `payplay_scouts`(`id`, `user_id`, `scout_profile`, `status`, `expiry_date`) 
      VALUES ('0','{$user_id}','','pending','')",
      "Could not set scout id in force account type selection ");
    }
  }
    
		//~ $subject = 'New Account on Payplay';
		//~ $message = "{$username}, who you referred has just created an account \n
		//~ Login to send a welcome message and offer assistance.";
		//~ email($referrer,$subject,'');
		
		status_message('success', 'Registration Successful!');
		echo "<div class='container'><a href='".BASE_PATH."user?action=login'><button> Login Now </button></a></div>";
}
}

if(isset($_POST['submit']) && $_POST['submit'] ==='login') {
$begins_with = substr($_POST['phone'],0,1);
//echo "begins with" .$begins_with;
if($begins_with == '0' || $begins_with == '+'){
$phone = mysql_prep($_POST['phone']);
}else if($begins_with !== '0' && $begins_with !== '+'){
$phone = '0'. mysql_prep($_POST['phone']);	
}
//echo $phone;
$password = trim(mysql_prep($_POST['password']));
#print_r($_POST);
$hashed_password = sha1($password);

//login
$login_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`, `user_name`, `created_time`, `last_login`, `login_count`, `phone`, `site_funds_amount`, `role`, `state`, `picture`, `picture_thumbnail`, `secret_question`, `secret_answer` FROM `user` WHERE `phone`='{$phone}' AND `password`='{$hashed_password}'")
or die("<div class='error'>Unsuccessful login attempt!</div>" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

while($login_result = mysqli_fetch_array($login_query)){
	
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
	$_SESSION['picture'] = '<a href="'.BASE_PATH .'user/?user='.$login_result['user_name'] .'">'.
	'<img src="'.$login_result['picture'].'"></a>';
	$_SESSION['picture_thumbnail'] = '<a href="'.BASE_PATH .'user/?user='.$login_result['user_name'] .'">'.
	$_SESSION['secret_question'] = $login_result['secret_question'];
	$_SESSION['secret_answer'] = $login_result['secret_answer'];
	unset($_SESSION['not_logged_in']);
	 #print_r($_SESSION);
	}
	if(isset($_SESSION['username'])){
	$time = time();
	$user = $_SESSION['username'];
	
	//Upate login time
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `last_login`='{$time}' WHERE `user_name`='{$user}'") 
	or die('Failed to update login time' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	if($query){
	$_SESSION['last_login'] = $time;
		}
	echo "<br><br><div class='success'> Login successful! </div>"; 
	if(!empty($post_destination)){
		$_SESSION['destination'] = $post_destination;
		} 
	$destination = BASE_PATH;
	'user/?user='.$_SESSION['username'].'&login=true';
	if(isset($_SESSION['destination'])){
	header("Location: {$_SESSION['destination']}"); exit;
	} else {
		header("Location: {$destination}"); exit;
		}
	echo '<div class="container"><a href="' .BASE_PATH .'user?user='.$user.'&login=true"><button><img src="'.BASE_PATH .'uploads/files/default_images/profile-icon.png"><br> Go to profile </button></a>   '.'&nbsp &nbsp<a href="' .BASE_PATH .'"><button><img src="'.BASE_PATH .'uploads/files/default_images/home-icon.png"><br> Go to Home</button></a>';
		if(is_admin()){
		echo'&nbsp &nbsp<a href="' .ADMIN_PATH .'"><button><img src="'.BASE_PATH .'uploads/files/default_images/admin-icon.png"><br> Go to Admin </button></a></div>';
		} 
	} else {
		sleep(4);
		session_message('error', 'Unsuccessful login attempt');
		redirect_to(BASE_PATH.'/index.php/user/action/show-login-form');
		#go_back();
	}
} 



if(isset($_POST['submit']) && $_POST['submit'] ==='save') {
	
$uid = $_POST['uid'];
$password = trim(mysql_prep($_POST['password']));
$confirm_password = trim(mysql_prep($_POST['confirm_password']));

$begins_with = substr($_POST['phone'],0,1);
//echo "begins with" .$begins_with;
if($begins_with == '0' || $begins_with == '+'){
$phone = mysql_prep($_POST['phone']);
}else if($begins_with !== '0' && $begins_with !== '+'){
$phone = '0'. mysql_prep($_POST['phone']);	
}

if($password !== $confirm_password){
	status_message("error", "Passwords do not match!");
	
	exit;
	} else {

$hashed_password = sha1($password);


	if($_POST['role']){
	$role = trim(mysql_prep($_POST['role']));
	} else { $role = 'authenticated'; }

			if(!empty($_POST['email'])){
				$email = trim(mysql_prep($_POST['email']));
				$update_user=mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `email`='{$email}' WHERE `id`='{$uid}'");
				}
				
				
	if($_POST['password']===''){
		
		if(!empty($_POST['secret_answer'])){
			die ("<span class='red-text'>You must enter your password to update your secret answer!</span>" . go_back());
			
			}
		
		$update_user=mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `phone`='{$phone}', `role`='{$role}' WHERE `id`='{$uid}'") 
		or die("Update user query failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($update_user){
			
			if(addon_is_active('referrals')){ edit_referrer(); }
			status_message("success","Changes saved successfully!");
				}
		} else {
				
			if(empty($_POST['secret_answer'])){
			$update_user=mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `password`='{$hashed_password}', `phone`='{$phone}', `role`='{$role}' WHERE `id`='{$uid}'");
			}
			if(!empty($_POST['secret_answer'])){
			$secret_answer = trim(mysql_prep($_POST['secret_answer']));
			$update_user=mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `password`='{$hashed_password}', `phone`='{$phone}', `role`='{$role}', `secret_answer`='{$secret_answer}' WHERE `id`='{$uid}'");	
		
			}
			
		if($update_user){
			status_message("success","Changes saved successfully!");
			
				}
			}
	}
}

if(isset($_POST['submit-account']) && $_POST['submit-account'] ==='save') {
	$username = $_SESSION['username'];
	$bank_account = mysql_prep($_POST['account_no']);
	$bank_name = trim(mysql_prep($_POST['bank']));
	$full_name = trim(mysql_prep($_POST['full_name']));
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `bank_account_no`='{$bank_account}', `bank_name`='{$bank_name}', `full_name`='{$full_name}' WHERE `user_name`='{$username}'") 
	or die("Failed to save account details " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	if($query){
		status_message('success', 'Account details saved successfully!');
		link_to(BASE_PATH."user/?user={$username}", 'Return to profile');
		} else{ echo "<h2>Nothing happened! why?</h2>"; }
	}

  }

 // end of user functions file
 // in root/core/user.php
?>
