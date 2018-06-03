<?php
function get_user_personal_data($user_id){
  $value = query_db("SELECT * FROM user_personal_data WHERE user_id='{$user_id}' LIMIT 1",
  "Failed to get user personal data! ");
  if(!empty($value['result'])){
    return $value['result'][0];
  } else {
    return '';
  }
}

function set_user_personal_data($user_id){
  $_SESSION['current_user']['personal-data'] = get_user_personal_data($user_id);
}

function get_user_dating_preferences($user_id){
   $value = query_db("SELECT * FROM dating_preferences WHERE user_id='{$user_id}' LIMIT 1",
  "Failed to get user personal data! ");
  return $value['result'][0];
}

function set_user_dating_preferences(){
   $_SESSION['current_user']['dating-preferences'] = get_user_dating_preferences($user_id);
}

function add_user_personal_data($user){
  if($user == $_SESSION['username'] || is_admin()){
    if(isset($_POST['save-user-personal-data'])){
      $user_id = sanitize($_POST['user_id']);
      $occupation = sanitize($_POST['occupation']);
      $interests = sanitize($_POST['interests']);
      $ethnicity = sanitize($_POST['ethnicity']);
      $religion = sanitize($_POST['religion']);
      $hobbies = sanitize($_POST['hobbies']);
      $complexion = sanitize($_POST['complexion']);
      $disabilities = sanitize($_POST['disabilities']);
      $ip_location = get_user_location_by_ip($ip_address);
      $current_location = $ip_location['country_name'].','.$ip_location['region_name'];

      $q = query_db("INSERT INTO `user_personal_data`(`id`, `user_id`,  `occupation`, `interests`, `ethnicity`, `religion`, `hobbies`, `complexion`, `disabilities`, `current_location`) VALUES ('0','{$user_id}','{$occupation}','{$interests}','{$ethnicity}','{$religion}','{$hobbies}','{$complexion}','{$disabilities}','{$current_location}')",
      "Could not save user personal data! ");
      if($q){
        $_SESSION['status-message'] = '<div class="alert alert-success"> Personal data saved! </div>';
        redirect_to(BASE_PATH.'me');
      }
    }

    load_view('user','add-user-personal-data-form') ;
  }
}

function show_user_personal_data(){
  load_view('user','show-user-personal-data');
}


function edit_user_personal_data(){

  if(isset($_POST['save-edit-user-personal-data'])){
    $user_id = sanitize($_POST['user_id']);
    $occupation = sanitize($_POST['occupation']);
    $interests = sanitize($_POST['interest']);
    $ethnicity = sanitize($_POST['ethnicity']);
    $hobbies = sanitize($_POST['hobbies']);
    $religion = sanitize($_POST['religion']);
    $complexion = sanitize($_POST['complexion']);
    $disabilities = sanitize($_POST['disabilities']);
    $ip_location = get_user_location_by_ip($ip_address);
    $current_location = $ip_location['country_name'].','.$ip_location['region_name'];

    $q = query_db("UPDATE user_personal_data SET occupation='{$occupation}', interests='{$interests}', ethnicity='{$ethnicity}', religion='{$religion}', hobbies='{$hobbies}', complexion='{$complexion}', disabilities='{$disabilities}', current_location='{$current_location}' WHERE user_id='{$user_id}'",
    "Could not save edited personal data! ");

    if($q){
      $_SESSION['status-message'] = '<div class="alert alert-success">Personal data updated sucessfully!</div>';
      redirect_to(BASE_PATH.'me');
    }
  }

  if(is_my_profile_page() || is_admin()){
    $user_id = $_SESSION['current_user']['id'];
    $q = query_db("SELECT * FROM user_personal_data",
    "Could not get user personal data in edit user personal data! ");
    $_SESSION['current_user']['personal-data'] = $q['result'][0];
    load_view('user','edit-user-personal-data-form');
  }
}



//~ SESSION Varialbes and their functions
 //~ $_SESSION['current_user'] : Holds the current user
 //~ $_SESSION['current_user']['personal-data'] : Holds the current user personal data

?>
