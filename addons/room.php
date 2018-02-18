<?php

function setup_room_db(){
  $q = query_db("DROP TABLE IF EXISTS `room`","
  Could not drop room table");
  
  
  $q = query_db("CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `description` text NOT NULL,
  `owner_id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `max_duration_of_stay` varchar(150) NOT NULL,
  `status` varchar(50) NOT NULL,
  `last_book_date` varchar(50) NOT NULL,
  `book_expiry_date` varchar(50) NOT NULL,
  `num_bookings` int(6) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1",
  "Could not sreate room table! ");
  
  
  $q = query_db("INSERT INTO `room` (`id`, `description`, `owner_id`, `location`, `max_duration_of_stay`, `status`, `last_book_date`, `book_expiry_date`, `num_bookings`, `timestamp`) VALUES
  (1, 'test room', 1, '7', '', '', '', '', 0, '2018-01-11 13:35:33'),
  (2, 'A new room descriptioon', 2, 'Nigeria&#44;lagos&#44;iyana-ipaja&#44;bus stop&#44;abiola street&#44;no 24', '', '', '', '', 0, '2018-01-12 13:24:46'),
  (3, 'A brand new room', 2, 'Nigeria&#44;lagos&#44;iyana-ipaja&#44;bus stop&#44;abiola street&#44;no 24', '', '', '', '', 0, '2018-01-12 13:24:50'),
  (4, 'this+cannot+be+a+real+room', 2, 'lagos%26%2344%3Bfffioff%26%2344%3Bjygfkf%26%2344%3Bjygfiuf%26%2344%3Biddijdd%26%2344%3B90', '', '', '', '', 0, '2018-01-12 15:59:03'),
  (5, 'this-can-be-a-room-too', 2, 'lagos&#44;iudgiud&#44;dfdhd.sr.rwe.&#44;rttrt&#44;rtryer&#44;890', '', '', '', '', 0, '2018-01-12 16:03:32'),
  (7, 'A room in a duplex inside prince and princess estate', 2, 'Nigeria,FCT,kaura,prince+and+princess+estate,drive+6,no7', '1 weeks', 'never booked', '', '', 0, '2018-01-12 17:24:28'),
  (12, 'jyviv;ibUpiU piu ui ipU iuiupiupuuio', 2, 'Nigeria,lagos,ikotavilla,2nd+gate,royalty+rd,no+24', '', 'never booked', '', '', 0, '2018-01-23 13:25:50'),
  (13, 'kuguio', 2, 'Nigeria,lagos,ikotavilla,2nd+gate,royalty+rd,no+25', '', 'never booked', '', '', 0, '2018-01-23 14:56:41'),
  (14, 'kufdfui', 2, 'Nigeria,lagos,iyana-ipaja,bus+stop,abiola+street,no+26', 'days', 'never booked', '', '', 0, '2018-01-23 16:18:38'),
  (15, 'pojpoj', 2, 'Nigeria,lagos,iyana-ipaja,bus+stop,abiola+street,no+29', 'days', 'never booked', '', '', 0, '2018-01-23 16:32:22')
  ","Could not intall room sample data! ");
  
  
  $q = query_db("CREATE TABLE `room_photos` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `caption` varchar(50) NOT NULL,
  `description` varchar(150) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `status` varchar(150) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1",
"Could not create room photos table! ");
  
  
  $q = query_db("DROP TABLE IF EXISTS `room_preferences`",
"Could not create room preferences! ");
  
  
  $q = query_db("CREATE TABLE `room_preferences` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `occupation` varchar(150) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `religious_views` varchar(20) NOT NULL,
  `smoker` varchar(50) NOT NULL,
  `drinker` varchar(50) NOT NULL,
  `relationship` varchar(50) NOT NULL,
  `visitors` varchar(20) NOT NULL,
  `max_num_sleepover_visitors` varchar(20) NOT NULL,
  `couples_allowed` varchar(3) NOT NULL,
  `do_you_sleep_in_this_room` varchar(3) NOT NULL,
  `is_there_a_kitchen_in_this_room` varchar(3) NOT NULL,
  `if_you_sleep_do_you_cook` varchar(20) NOT NULL,
  `if_you_cook_can_roommate_share_kitchen` varchar(150) NOT NULL,
  `bathroom_and_toilet` varchar(3) NOT NULL,
  `neigborhood_type` varchar(20) NOT NULL,
  `furnished` varchar(3) NOT NULL,
  `describe_furnishing` text NOT NULL,
  `prepaid_meter` varchar(3) NOT NULL,
  `electricity_situation` varchar(20) NOT NULL,
  `who_pays_electricity_bill` varchar(25) NOT NULL,
  `power_generator` varchar(3) NOT NULL,
  `who_buys_fuel` varchar(150) NOT NULL,
  `max_time_a_person_can_stay` int(11) NOT NULL,
  `max_duration_of_stay` varchar(10) NOT NULL,
  `room_available_date` varchar(10) NOT NULL,
  `room_expires_date` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1",
"Could not create room photos table! ");
  
  
  $q = query_db("INSERT INTO `room_preferences` (`id`, `room_id`, `occupation`, `gender`, `religious_views`, `smoker`, `drinker`, `relationship`, `visitors`, `max_num_sleepover_visitors`, `couples_allowed`, `do_you_sleep_in_this_room`, `is_there_a_kitchen_in_this_room`, `if_you_sleep_do_you_cook`, `if_you_cook_can_roommate_share_kitchen`, `bathroom_and_toilet`, `neigborhood_type`, `furnished`, `describe_furnishing`, `prepaid_meter`, `electricity_situation`, `who_pays_electricity_bill`, `power_generator`, `who_buys_fuel`, `max_time_a_person_can_stay`, `max_duration_of_stay`, `room_available_date`, `room_expires_date`) VALUES
  (1, 0, 'occu', 'Female', 'Christian', '', '', '', '', '', '', '', '', '', '', '', '', 'Yes', '', '', '', '', '', '', 0, '', '', ''),
  (2, 13, 'uiuo', 'Female', 'I Dont care', '', '', '', '', '', '', '', '', '', '', '', '', 'Yes', '', '', '', '', '', '', 0, '', '', ''),
  (3, 14, 'vouhvoui', 'Female', 'Christian', '', 'I Love Alcohol (Drinkers welcome)', 'None - (Strictly business)', 'Only on weekends', 'One', '', 'Yes', 'Yes', '', 'Roommate Can use the kitchen anytime (I will pay the cost)', 'Yes', '', 'Yes', '', 'No', 'Constant light', '', 'Yes', '', 1, 'days', '2018-01-24', ''),
  (4, 15, 'student', 'Female', 'Christian', '', 'I Love Alcohol (Drinkers welcome)', 'None - (Strictly business)', 'Yes', 'One', 'Yes', 'Yes', 'Yes', '', 'Roommate Can use the kitchen anytime (I will pay the cost)', 'Yes', '', 'Yes', '', 'No', 'Constant light', 'Landlord', 'Yes', 'Landlord buys fuel and Maintains the Generator', 1, 'days', '2018-01-24', '2018-01-31');
  ",
  "Could not create room_preferences table! ");
  }

function is_room_page(){
  if(string_contains($_SESSION['current_url'],'show-room/')){
    return true;
  } else {
    return false;
  }
}

function is_room_owner($room_id){
  if(isset($_SESSION['room-info'])){
  $rm_info = $_SESSION['room-info'];
  } else {
    $rm_info = get_room_information($room_id);
  }
  if(is_logged_in()){
    $user_id = $_SESSION['user_id'];
    
    if($user_id == $rm_info['owner_id']){
      return true;
    }
  }
  
}

function get_rooms($start='',$num=''){
  if(!empty($start)){
      $start = $start.',';
  }
  $q = query_db("SELECT * FROM room ORDER BY id DESC limit {$start} {$num}",
  "Could not get rooms! ");
  if($q){
    return $q;
  }
}

function get_room_information($room_id){
  $q = query_db("SELECT * FROM room WHERE id='{$room_id}'",
  "Could not get Room information");
  if($q){
    $_SESSION['room-info'] = $q['result'][0];
    return $q['result'][0];
    
  }
}

function set_room_information($room_id){
  $q = get_room_preferences($room_id);
  
  $p = get_room_photos($room_id);
  if(!empty($p['result'])){
    $_SESSION['room-info']['room-photos'] = $p['result'];
  } 
}

function get_room_photos($room_id){
  $q = query_db("SELECT * FROM room_photos WHERE room_id='{$room_id}'",
  "Could not get Room Photos! ");
  if($q){
    return $q;
  }
}

function get_room_preferences($room_id){
  $q = query_db("SELECT * FROM room_preferences WHERE room_id='{$room_id}'",
  "Could not get Room Preferences! ");
  if($q){
    //~ $_SESSION['room-info']['id'] = $room_id;
    $_SESSION['room-info']['preferences'] = $q['result'];
    return $q['result'];
  }
}

function get_rooms_by_preference($filter_conditions){
  $filter_conditions = sanitize($_SESSION['room-filter-conditions']);
  $q = query_db("SELECT room_id FROM room_preferences WHERE {filter_conditions} ORDER BY id DESC limit 25",
  "Could not get rooms by preference! ");
  $_SESSION['filtered-rooms'] = array();
  if($q){
    foreach($q['result']['room_id'] as $room_id){
      $_SESSION['filtered-room-ids'][] = $room_id;
    }
  }
  return $_SESSION['filtered-room-ids'];
}

function show_filtered_rooms($filter=''){
  //~ get_rooms_by_preference();
  $filter = str_ireplace('+',' ',$filter);
  $filters = explode(':',$filter);
  $step=1;
  foreach($filters as $key => $value){
    $key = sanitize($key);
    $value = sanitize($value);
    
    if($step < 2){
      if(empty($_SESSION['room-filter-conditions'])){
        $_SESSION['room-filter-conditions'] = "WHERE {$value}=";
        $step++;
      } else {
        $_SESSION['room-filter-conditions'] .= "AND {$key}='{$value}' ";
        $step++;
      }
    } else {
      $_SESSION['room-filter-conditions'] .= "'{$value}'";
        $step = 1;
    }
  }
  $filter_conditions = $_SESSION['room-filter-conditions'];
  $q = query_db("SELECT room_id FROM room_preferences {$filter_conditions} ORDER BY id DESC limit 25",
  "Could not get rooms by preference! ");
  if($q){ 
    $_SESSION['filtered-rooms-count'] = $q['num_results'];
    unset($_SESSION['filtered-room-ids']);
    foreach($q['result'] as $room){
      $_SESSION['filtered-room-ids'][] = $room['room_id'];
    }
    //~ $_SESSION['filtered-room'] ='';
    
    if(!empty($_SESSION['filtered-rooms-count'])){
        $num_rooms = $_SESSION['filtered-rooms-count'];
    } else {
      $num_rooms = 0;
    }
    
    echo '<div class="col-md-12 col-xs-12 text-muted p-3 mx-auto"> Showing rooms preference '.$_SESSION['room-filter-conditions'].'</div>';
    echo '<div class="p-3"><h3 align="center" class="text-capitalize">'.$num_rooms.' rooms</h3></div>';

    foreach($_SESSION['filtered-room-ids'] as $filtered_room_id){
      $room = get_room_information($filtered_room_id);
      get_room_preferences($filtered_room_id);
      $_SESSION['filtered-room'] = $room;
      
      load_view('room','show-filtered-rooms');
    }
   
  }
  
  unset($_SESSION['room-filter-conditions']);
  
}

function clear_filters(){
  unset($_SESSION['room-filters']);
  redirect_to(BASE_PATH.'index.php/find-rooms');
}

function set_room_preferences($room_id){
  $_SESSION['room-info']['preferences'] = get_room_preferences($room_id);
}

function show_room_list_photo($room_id,$size='150'){
  $q = query_db("SELECT * FROM room_photos WHERE room_id='{$room_id}' order by id DESC ",
  "Could not get Room list Photos! ");
  
  if(empty($q['result'][0]['path'])){
    echo '<img src="http://via.placeholder.com/'.$size.'x'.$size.'" class="p-2 m-2">';
  } else {
    echo '<img src="'.$q['result']['0']['path'].'" class="p-2 m2" width="'.$size.'px" height="'.$size.'px">';
  }
  
}

function show_room_photos($room_id){
  $q = get_room_photos($room_id);
  echo '<div class="col-md-12 col-xs-12 mb-3">';
  show_in_bootsrap_carousel('room-photos',$q);
  echo '</div>';
}

function show_room($room_id){
  show_session_message();
  set_room_preferences($room_id);  
  get_room_information($room_id); 
  load_view('room','show-room');
}

function add_room_photos($upload_location, $allowed_file_type = '',$filename=''){ 
  if(is_room_page() && is_room_owner($_SESSION['room-info']['id'])){
    echo '<div class="col-md-12 col-xs-12 bg-dark text-white p-4">';
     //~ returns path of uploaded file upload location relative to BASE_PATH
    $r = dirname(dirname(__FILE__));
    
    if(isset($_POST['uploading-form']) && $_POST['uploading-form'] == 'upload-file-form'){
      if(empty($filename)){
        $name = $_SESSION['room-info']['id'].'-'. ($_FILES['image_field']['name']);
      } else {
        $name = $filename.'.' . str_ireplace('image/','',$_FILES['file_field']['type']);
      }
      $type = $_FILES['image_field']['type'];
      $caption = sanitize($_POST['photo-caption']);
      $description = sanitize($_POST['photo-description']);

      if(
      $type == 'image/jpeg' || 
      $type== 'image/png' || 
      $type== 'image/gif' || 
      $type == 'image' || 
      string_contains($type,'image/')){
        $is_image = true;
      } else {
        $is_image == false;
      }


      if(($is_image && $allowed_file_type == 'image') 
      || ($is_document && $allowed_file_type == 'document')
      || empty($allowed_file_type)){
        //~ sort out the relevant paths (Sacred)
        $uploaddir = $r.'/'.$upload_location;
        $uploadfile = $uploaddir .''. $name;
        $path = BASE_PATH.$upload_location.''. $name;
  
        
        # READY TO MOVE
        if(isset($_FILES['image_field']) && !empty($_FILES['image_field'])){
          $move = move_uploaded_file($_FILES['image_field']['tmp_name'], $uploadfile);
            if($move ==1){
              $user_id = $_SESSION['user_id'];
              $room_id = $_SESSION['room-info']['id'];
              $q = query_db("INSERT INTO `room_photos`(`id`,`room_id`, `path`, `caption`, `description`, `owner_id`, `status`) 
              VALUES ('','{$room_id}','{$path}','{$caption}','{$description}','{$user_id}','')",
              "Could not save room photo! ");
              if($q){
                $_SESSION['status_message'] = '<div class="alet alert-success">Photo uploaded!</div>';
                redirect_to($_SESSION['current_url']);
              }
            }
        }
      } else { status_message('error','File type not allowed!');  }
    }
    
    echo '
    <h4>Add Room Photos</h4>
    <form action="'.$_SESSION['current_url'].'" method="post" enctype="multipart/form-data">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    <!-- Name of input element determines name in $_FILES array -->
    <input type="hidden" name="uploading-form" value="upload-file-form">
    <input type="file" size="500" name="image_field" class="form-control" value="" required>
    <input type="text" name="photo-caption" placeholder="caption (max 50 chars)" maxlength="50" class="form-control" />
    <textarea name="photo-description" placeholder="description (Max 150 chars)" maxlength="150" class="form-control"></textarea>
    <br>
    <input type="submit" name="save_item" value="Save item" class="btn btn-primary">
    </form>';
    echo '</div>';
  } else {
    
  }
}

function book_a_room(){
  load_view('room','book-a-room');
}

function save_room(){
  //~ print_r($_POST);
  if(isset($_POST['save-room'])){
    $description = sanitize($_POST['room-description']);
    $location = str_ireplace(' ','+',($_POST['location']));
    $num = sanitize(sanitize($_POST['max_time_a_person_can_stay']));
    $duration = sanitize($_POST['max_duration_of_stay']);
    $max_duration_of_stay = $num .' '. $duration;
    $status = $_POST['status'];
    $user_id = $_SESSION['user_id'];
    $room_available_date = sanitize($_POST['room_available_date']);
    $room_expires_date = sanitize($_POST['room_expires_date']);
    $price_per_day = sanitize($_POST['price_per_day']);

    $q = query_db("INSERT INTO `room`(`id`, `description`, `owner_id`, `location`, `max_duration_of_stay`, `status`,`last_book_date`, `book_expiry_date`, `num_bookings`,`room_available_date`, `room_expires_date`,`price_per_day`,`timestamp`) VALUES ('0','{$description}','{$user_id}','{$location}','{$max_duration_of_stay}','never booked','','','','{$room_available_date}','{$room_expires_date}','{$price_per_day}','')",
    "Could not save room! ");
    
    if($q){
      if(is_json_request(URL)){
        return 'Success - room location saved!';
      }
      $_SESSION['status-message'] = '<div class="alert alert-success m-2 "> Location Saved!</div>';
      redirect_to($_SESSION['prev_url']);
    }
  }
  
}

function save_room_preferences($room_id){
  if(isset($_POST['save-room-preferences'])){
    $id = sanitize($room_id);
    $occupation = sanitize($_POST['occupation']);
    $gender = sanitize($_POST['gender']);
    $religious_views = sanitize($_POST['religion']);
    $smoker = sanitize($_POST['smoker']);
    $drinker = sanitize($_POST['drinker']);
    $relationship = sanitize($_POST['relationship']);
    $visitors = sanitize($_POST['visitors']);
    $relationship = sanitize($_POST['relationship']);
    $max_num_sleepover_visitors = sanitize($_POST['max_num_sleepover_visitors']);
    $couples_allowed = sanitize($_POST['couples_allowed']);
    $do_you_sleep_in_this_room = sanitize($_POST['do_you_sleep_in_this_room']);
    $is_there_a_kitchen_in_this_room = sanitize($_POST['is_there_a_kitchen_in_this_room']);
    $if_you_sleep_do_you_cook = sanitize($_POST['if_you_sleep_do_you_cook']);
    $if_you_cook_can_roommate_share_kitchen = sanitize($_POST['if_you_cook_can_roommate_share_kitchen']);
    $bathroom_and_toilet = sanitize($_POST['bathroom_and_toilet']);
    $neighborhood_type = sanitize($_POST['neighborhood_type']);
    $furnished = sanitize($_POST['furnished']);
    $describe_furnishing = sanitize($_POST['describe_furnishing']);
    $prepaid_meter = sanitize($_POST['prepaid_meter']);
    $electricity_situation = sanitize($_POST['electricity_situation']);
    $who_pays_electricity_bill = sanitize($_POST['who_pays_electricity_bill']);
    $power_generator = sanitize($_POST['power_generator']);
    $who_buys_fuel = sanitize($_POST['who_buys_fuel']);
    
    //~ check if record exists
    $rm_pref = get_room_preferences($room_id);
    if(empty($rm_pref)){
      $q = query_db("INSERT INTO `room_preferences`(`id`, `room_id`, `occupation`, `gender`, `religious_views`, `smoker`, `drinker`, `relationship`, `visitors`, `max_num_sleepover_visitors`, `couples_allowed`, `do_you_sleep_in_this_room`, `is_there_a_kitchen_in_this_room`, `if_you_sleep_do_you_cook`, `if_you_cook_can_roommate_share_kitchen`, `bathroom_and_toilet`, `neigborhood_type`, `furnished`, `describe_furnishing`, `prepaid_meter`, `electricity_situation`, `who_pays_electricity_bill`, `power_generator`, `who_buys_fuel`) 
      VALUES ('0','{$room_id}','{$occupation}','{$gender}','{$religious_views}','{$smoker}','{$drinker}','{$relationship}','{$visitors}','{$max_num_sleepover_visitors}','{$couples_allowed}','{$do_you_sleep_in_this_room}','{$is_there_a_kitchen_in_this_room}','{$if_you_sleep_do_you_cook}','{$if_you_cook_can_roommate_share_kitchen}','{$bathroom_and_toilet}','{$neigborhood_type}','{$furnished}','{$describe_furnishing}','{$prepaid_meter}','{$electricity_situation}','{$who_pays_electricity_bill}','{$power_generator}','{$who_buys_fuel}')",
      "Could not save room preferences! ");
    } else {
      $q = query_db("UPDATE `room_preferences` SET occupation='{$occupation}',`gender`='{$gender}',`religious_views`='{$religious_views}',`smoker`='{$smoker}',`drinker`='{$drinker}',`relationship`='{$relationship}',`visitors`='{$visitors}',`max_num_sleepover_visitors`='{$max_num_sleepover_visitors}',`couples_allowed`='{$couples_allowed}',`do_you_sleep_in_this_room`='{$do_you_sleep_in_this_room}',`is_there_a_kitchen_in_this_room`='{$is_there_a_kitchen_in_this_room}',`if_you_sleep_do_you_cook`='{$if_you_sleep_do_you_cook}',`if_you_cook_can_roommate_share_kitchen`='{$if_you_cook_can_roommate_share_kitchen}',`bathroom_and_toilet`='{$bathroom_and_toilet}',`neigborhood_type`='{$neigborhood_type}',`furnished`='{$furnished}',`describe_furnishing`='{$describe_furnishing}',`prepaid_meter`='{$prepaid_meter}',`electricity_situation`='{$electricity_situation}',`who_pays_electricity_bill`='{$who_pays_electricity_bill}',`power_generator`='{$power_generator}',`who_buys_fuel`='{$who_buys_fuel}' WHERE id='{$id}'",
      "Could not update room preferences! ");
    }
    if($q){
      $_SESSION['status-message'] = '<div class="alert alert-success pl-3">Room preferences saved!</div>';
      redirect_to($_SESSION['current_url']);
    }
  }
  
  load_view('room','add-room-preferences');
}

function show_room_preferences($room_id){
  get_room_preferences($room_id);
  load_view('room','show-room-preferences');
}

function show_room_tabs($room_id){
  get_room_information($room_id);
  load_view('room','show-room-tabs');
}

function show_my_rooms(){
  if(!empty($_SESSION['pager-start'])){
    $start = $_SESSION['pager-start'];
  } else {
    $start = 0;
  }
  if(!empty($_SESSION['pager-stop'])){
    $stop = $_SESSION['pager-stop'];
  } else {
    $stop = 3;
  }
  $user_id = $_SESSION['user_id'];
  $q = query_db("SELECT * FROM room WHERE owner_id='{$user_id}' ORDER BY id DESC LIMIT {$start}, 25",
  "Could not get user rooms! ");
  if($q){
    $count = 0;
     
    echo '<div class="p-2">
            <h3>My rooms</h3>';
        foreach($q['result'] as $result){
          if($count < $stop){
            echo '
            <div class="media p-2 border m-2 bg-white">
              <a href="'.BASE_PATH.'index.php/room/action/show-room/'.$result['id'].'">';
              show_room_list_photo($result['id'],'55px');
              echo '</a>
              <div class="media-body pl-2">
                <div class="media-heading font-weight-bold pull-left"><a href="'.BASE_PATH.'index.php/room/action/show-room/'.$result['id'].'">'.$result['description'].'</a></div>
                <em class="text-muted">';
                
                echo '
                <small class="d-block clearfix relative-bottom p-2 align-bottom">status: '.$result['status'].' | added - ';
                show_timeago($result['timestamp']); 
                echo '
                </small>
                </em>
              </div>
            </div>' ;
            $count++;
          }
        }      
        $pager_num = 2;
        echo 'Pager : ';
        echo '<a href="'.BASE_PATH.'index.php/router/action/set-pager/0/'.$stop.'/show-my-rooms"> 1 </a> ';
        while($count < $q['num_results']){
          $start = $stop;
          echo '<a href="'.BASE_PATH.'index.php/router/action/set-pager/'.$stop.'/'.$count++.'/show-my-rooms">'.$pager_num.' </a> ';
          $pager_num ++;
          $count += $stop;
        }
    echo '</div>';    
    //~ show_rooms_list($q,'75px');
  }
  $_SESSION['pager-start'] = 0;
  $_SESSION['pager-stop'] = 0; 
}

function find_room($location=''){
 
  if(!empty($location)){
    $search_phrase = sanitize($location);
  }
  if(isset($_POST['search-phrase'])){
    $search_phrase = urlencode(sanitize($_POST['search-phrase']));
    redirect_to(BASE_PATH.'index.php/room/action/find-room/'.$search_phrase);
  }
 
  echo '
  <div class="row p-4 bg-primary"><h1 class="flex-item mx-auto my-0">Find rooms in any location</h3> 
    <div id="search-room" class="d-inline">
      <form method="post" class="pl-3 flex-item" action="'.BASE_PATH.'index.php/room/action/find-room/">
        <div class="input-group ">
          <input type="text" name="search-phrase" class="form-control" placeholder="type location here...">
          <span class="input-group-append">
          <input type="submit" name="search-rooms" value="Search" class="btn btn-dark" id="" />
          </span>
        </div>
      </form>
      
    </div>
  </div>';
  if(!empty($search_phrase)){
    $q = get_entities('room','location',$search_phrase);
    echo '<center class="p-3">'.$q['num_results'].' Rooms in '.str_ireplace('+',' ',$search_phrase).'</center>';
  }
  
  
  if(!empty($search_phrase)){
    
    if(!is_json_request()){
      show_rooms_list($q);
    }
  } else {
    $q = query_db("SELECT * FROM room ORDER BY id DESC LIMIT 10",
    "Could not get new rooms! ");
    if($q){
      echo '<center class="p-3">New Rooms</center>';
      show_rooms_list($q);
    }
  }
  show_map_of_location($location);
}

function show_rooms_list($q='',$image_size='100'){
  if($q){
    $_SESSION['rooms-list'] = $q;
    load_view('room','show-rooms-list');
  }
}

function add_room(){
  show_session_message();
  if(is_logged_in()){
  load_view('room','add-room-form');


  echo '<div class="col-md-4 col-xs-12 p-5 bg-white text-secondary"> Upload photos after Saving room</div>';
  echo '</div>';
  
  
  show_my_rooms();
  
      echo "
    <script>
      new Vue({
        el: '#add-room',
         data: {
          title: 'Add a New Room Location',
          location: '',
          hint: 'Country',
          regions: ['Country','State','City','Area','Street','House number']
        },
        methods: {
          showHint: function(){
            var string = this.location;
            var res = string.split(',');
            for(var i=0; i < res.length; i++){
              if(res.length >= 0){
              this.hint = this.regions[res.length - 1];
              }
              
            }
          }
        }
      });

    </script>
    ";
  echo '</div>
  </div>';
  } else {
    deny_access();
  }
}

function show_map_of_location($location){
  if(!empty($location)){
    echo '
    <div class=" embed-responsive embed-responsive-21by9 m-2 mx-auto bg-dark">
      <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed/v1/place?q='.$location.'&zoom=14&key=AIzaSyDOZ1oyumjT9vKGd1PVs0hfMLqTbDgHd_A">
      </iframe>
    </div>';
  }
}

function show_rooms_list_actions(){
  report_room();
  rate_room_experience();
  add_room_photos();  
}




function archive_room_data($room_id){
  //~ Only archiving can be done by room owner , actual deleting is done by admin
  
}

function rate_room_experience(){
  
  
}

function edit_room(){
  //~ print_r($_POST);
  if(isset($_POST['update-room'])){
    $id = $_SESSION['room-info']['id'];
    $description = $_POST['description'];
    $price_per_day = $_POST['price_per_day'];
    if(isset($_POST['available'])){
      $status = 'available';
    } else {
      $status = '';
    }
    $num = sanitize($_POST['max_time_a_person_can_stay']);
    $duration = sanitize($_POST['max_duration_of_stay']);
    $max_duration_of_stay = $num .' '. $duration;
    $q = query_db("UPDATE room SET description='{$description}', max_duration_of_stay='{$max_duration_of_stay}', status='{$status}', price_per_day='{$price_per_day}' WHERE id='{$id}'",
    "Could not update room! ");
    if($q){
      redirect_to(BASE_PATH.'index.php/room/action/show-room/'.$id);
      get_room_information($id);
    }
  }
  load_view('room','edit-room-form');
}





?>
