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
  $rm_info = $_SESSION['room-info'];
  if(is_logged_in()){
    $user_id = $_SESSION['user_id'];
    
    if($user_id == $rm_info['owner_id']){
      return true;
    }
  }
  
}

function get_room_information($room_id){
  $q1 = query_db("SELECT * FROM room WHERE id='{$room_id}'",
  "Could not get Room information");
  if($q1){
    return $q1['result'][0];
  }
}

function set_room_information($room_id){
  $q = get_room_prefernces($room_id);
  if($q){
  $_SESSION['room-info'] = $q['result'][0];
  }
  
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

function get_room_prefernces($room_id){
  $q = query_db("SELECT * FROM room_preferences WHERE room_id='{$room_id}'",
  "Could not get Room Preferences! ");
  if($q){
    return $q['result'][0];
  }
}

function set_room_preferences($room_id){
  $_SESSION['room-info']['preferences'] = get_room_prefernces($room_id);
}

function show_room_list_photo($room_id,$size='150'){
  $q = query_db("SELECT * FROM room_photos WHERE room_id='{$room_id}' order by id ASC ",
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
  $_SESSION['room-info'] = get_room_information($room_id);  
  set_room_preferences($room_id);  
  load_view('room','show-room');
}

function add_room_photos($upload_location, $allowed_file_type = '',$filename=''){ 
  if(is_room_page() && is_room_owner($_SESSION['room-info']['id'])){
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
    
  } else {
    
  }
}

function save_room(){
  //~ print_r($_POST);
  if(isset($_POST['save-room'])){
    $description = sanitize($_POST['room-description']);
    $location = str_ireplace(' ','+',($_POST['location']));
    $num = sanitize($_POST['num']);
    $duration = sanitize($_POST['duration']);
    $max_duration_of_stay = $num .' '. $duration;
    $status = $_POST['status'];
    $user_id = $_SESSION['user_id'];
    
    $occupation = sanitize($_POST['occupation']);
    $gender = sanitize($_POST['gender']);
    $religious_views = sanitize($_POST['religion']);
    $smoker = sanitize($_POST['smoking_habit']);
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
    $max_time_a_person_can_stay = sanitize($_POST['max_time_a_person_can_stay']);
    $max_duration_of_stay = sanitize($_POST['max_duration_of_stay']);
    $room_available_date = sanitize($_POST['room_available_date']);
    $room_expires_date = sanitize($_POST['room_expires_date']);
    
    
    
    $q = query_db("INSERT INTO `room`(`id`, `description`, `owner_id`, `location`, `max_duration_of_stay`, `status`,`last_book_date`, `book_expiry_date`, `num_bookings`) VALUES ('0','{$description}','{$user_id}','{$location}','{$max_duration_of_stay}','never booked','','','')",
    "Could not save room! ");
    
    $q = query_db("SELECT id from room where location='{$location}' ORDER BY id DESC LIMIT 1",
    "Could not get room id! ");
    $room_id = $q['result'][0]['id'];
    
    //get last insert id
    $q = query_db("INSERT INTO `room_preferences`(`id`, `room_id`, `occupation`, `gender`, `religious_views`, `smoker`, `drinker`, `relationship`, `visitors`, `max_num_sleepover_visitors`, `couples_allowed`, `do_you_sleep_in_this_room`, `is_there_a_kitchen_in_this_room`, `if_you_sleep_do_you_cook`, `if_you_cook_can_roommate_share_kitchen`, `bathroom_and_toilet`, `neigborhood_type`, `furnished`, `describe_furnishing`, `prepaid_meter`, `electricity_situation`, `who_pays_electricity_bill`, `power_generator`, `who_buys_fuel`, `max_time_a_person_can_stay`, `max_duration_of_stay`, `room_available_date`, `room_expires_date`) 
    VALUES ('0','{$room_id}','{$occupation}','{$gender}','{$religious_views}','{$smoker}','{$drinker}','{$relationship}','{$visitors}','{$max_num_sleepover_visitors}','{$couples_allowed}','{$do_you_sleep_in_this_room}','{$is_there_a_kitchen_in_this_room}','{$if_you_sleep_do_you_cook}','{$if_you_cook_can_roommate_share_kitchen}','{$bathroom_and_toilet}','{$neigborhood_type}','{$furnished}','{$describe_furnishing}','{$prepaid_meter}','{$electricity_situation}','{$who_pays_electricity_bill}','{$power_generator}','{$who_buys_fuel}','{$max_time_a_person_can_stay}','{$max_duration_of_stay}','{$room_available_date}','{$room_expires_date}')",
    "Could not save room preferences! ");
    if($q){
      if(is_json_request(URL)){
        return 'Success - room location saved!';
      }
      $_SESSION['status-message'] = '<div class="alert alert-success m-2 "> Location Saved!</div>';
      redirect_to($_SESSION['prev_url']);
    }
  }
  
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

function show_rooms_list($q,$image_size='150'){
  $count = $q['num_results'];
  foreach($q['result'] as $result){
    $locs = explode(',',$result['location'],5);
  echo '
  <div class="media p-2 border m-2 bg-white">
    <a href="'.BASE_PATH.'index.php/room/action/show-room/'.$result['id'].'">';
    show_room_list_photo($result['id'],$image_size);
    echo '</a>
    <div class="media-body pl-2">
      <h4 class="media-heading font-weight-bold"><a href="'.BASE_PATH.'index.php/room/action/show-room/'.$result['id'].'">'.$result['description'].'</a></h4>
      <em class="text-muted">';
       $make_tag = 1;
        foreach($locs as $area){
          if($make_tag <= 4){
            echo '<a href="'.BASE_PATH.'index.php/room/action/find-room/'.$area.'">'.str_ireplace('+',' ',$area).'</a>,  ';
            $make_tag++;
          } else {
            echo ' '.str_ireplace('+',' ',$area).'  ';
            $make_tag = '';
          }
        }
      echo '
      <br>
      <div class="d-block clearfix  border relative-bottom p-2 align-bottom">status: '.$result['status'].' | added - ';
      show_timeago($result['timestamp']); 
      echo '
      </div>
      </em>
    </div>
  </div>' ;
  
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
  echo '
  <div class=" embed-responsive embed-responsive-21by9 m-5 mx-auto bg-dark">
    <iframe class="embed-responsive-item" src="//www.google.com/maps/embed/v1/place?q='.$location.'&zoom=17&key=AIzaSyDOZ1oyumjT9vKGd1PVs0hfMLqTbDgHd_A">
    </iframe>
  </div>';
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
    if(isset($_POST['available'])){
      $status = 'available';
    } else {
      $status = '';
    }
    $num = sanitize($_POST['max_time_a_person_can_stay']);
    $duration = sanitize($_POST['max_duration_of_stay']);
    $max_duration_of_stay = $num .' '. $duration;
    $q = query_db("UPDATE room SET description='{$description}', max_duration_of_stay='{$max_duration_of_stay}', status='{$status}' WHERE id='{$id}'",
    "Could not update room! ");
    if($q){
      get_room_information($id);
    }
  }
  load_view('room','edit-room-form');
}





?>
