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
      $date_of_birth = sanitize($_POST['date_of_birth']);
      $occupation = sanitize($_POST['occupation']);
      $interests = sanitize($_POST['interests']);
      $ethnicity = sanitize($_POST['ethnicity']);
      $religiion = sanitize($_POST['religion']);
      $hobbies = sanitize($_POST['hobbies']);
      $complexion = sanitize($_POST['complexion']);
      $disabilities = sanitize($_POST['disabilities']);
      $place_of_primary_assignment = sanitize($_POST['place_of_primary_assignment']);
      $current_location = sanitize($_POST['current_location']);
      $q = query_db("INSERT INTO `user_personal_data`(`id`, `user_id`, `date_of_birth`, `occupation`, `interests`, `ethnicity`, `religion`, `hobbies`, `complexion`, `disabilities`, `place_of_primary_assignment`) VALUES ('0','{$user_id}','{$date_of_birth}','{$occupation}','{$interests}','{$ethnicity}','{$religiion}','{$hobbies}','{$complexion}','{$disabilities}','{$place_of_primary_assignment}','{$current_location}')",
      "Could not save user personal data! ");  
      if($q){
        $_SESSION['status-message'] = '<div class="alert alert-success"> Personal data saved! </div>';
        redirect_to(BASE_PATH.'index.php/me');
      }
    }
    if(is_my_profile_page()){
      echo '<a class="d-block" href="'.BASE_PATH.'index.php/user/action/add-user-personal-data/'.$user.'">Fill your personal data</a>';
    } else{
      load_view('user','add-user-personal-data-form');
    }
  }
}

function show_user_personal_data(){
  load_view('user','user-public-personal-data');
}


function edit_user_personal_data(){
  
}


?>
