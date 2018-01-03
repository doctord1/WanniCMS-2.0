<?php

function add_page() {

process_post_submission();
$page_type = trim(sanitize($_GET['type']));

if(isset($_GET['page_type']) ){
  $page_type = trim(sanitize($_GET['page_type']));
  }
if(isset($_GET['type']) ){
  $page_type = trim(sanitize($_GET['type']));
  }
if(isset($_GET['parent_id'])){
  $parent_id = sanitize($_GET['parent_id']);
  }
if(isset($_GET['section_name'])){
  $section_name = trim(sanitize($_GET['section_name']));
  }
if(isset($_GET['category'])){
  $category = trim(sanitize($_GET['category']));
  }


// show form
$form = '<div class="edit-form page_content ">
<form method="POST" action="'.$_SESSION['current_url'].'" class="padding-10 ">
<input type="hidden" name="action" value ="insert" >
<input type="hidden" name="back_url" value ="'.$_SERVER['HTTP_REFERER'] .'" >'.
"<input type='hidden' name='redirect_to' value='{$_SESSION['prev_url']}'>".

'Title <input type="text" name="page_name" class="menu-item-form" placeholder="page name" required>
<hr>Visible:(Yes) <input type="checkbox" name="visible" value="1" checked="checked" class="checked"><hr>';
if(is_admin()){
$form = $form .'Menu Type: <select name="menu_type">
<option value="primary">Primary menu</option>
<option value="secondary">Secondary menu</option>
<option value="user">User menu</option>
<option value="none" selected="selected">None</option>
</select><hr>';
}
$form = $form .
'Position:
<input type="text" name="position" value="1" size="3" maxlength="3">
<br>(<em>Starting from 0, higher numbers will appear last</em>)
';


$form = $form.'<input type="hidden"  name="id" value="'.$_GET['id'].'">';
$form = $form.'<input type="hidden" id="'.$page_type.'" name="page_type" value="'.$page_type.'">';
$form = $form.'<input type="hidden"  name="parent_id" value="'.$parent_id.'">';
$form = $form.'<input type="hidden" id="'.$section_name.'" name="section" value="'.$section_name.'">';
$form = $form.'<input type="hidden" id="'.$category.'" name="category" value="'.$category.'">';
$form = $form .'<hr>Content:'.
'<br><textarea name="content" id="content-area" size="12" ></textarea><br>
<em>to disableslideshow and show images in list, add "{show_images_in_lists} to the content"</em>';
if(url_contains('page/add/?type=contest')){
$form .= '<br>Reward: <input type="text" name="reward" value="">';
$form .= '
<hr>Duration :
<select name="duration">
<option>1 day</option>
<option>3 days</option>
<option>1 week</option>
<option>1 month</option>
</select>';
}


$form .= '
<hr><b>Is this a training course?:</b> <input type="checkbox" name="is_training_course" value="yes"><em> tick for yes, leave empty for no</em>
<span id="enrollment_fee_input" class="input-group">
<span class="input-group-addon">'.$_SESSION['preferred_currency'].'</span>
<input type="number" name="enrollment_fee" class="form-control" placeholder="enrollment fee eg 100">
<span class="input-group-addon">.00</span>
</span>
<hr><b>Show author?:</b> <input type="checkbox" name="show_author" value="yes" checked><em> tick for yes, leave empty for no</em>
<hr><b>Show in streams?:</b> <input type="checkbox" name="show_in_streams" value="yes" checked><em> tick for yes, leave empty for no</em>
<hr><input type="submit" name="add_new_post" value="Add Page" class="submit">
</form></div>';

  if(is_logged_in()){
  echo $form; // End of Form
  add_ckeditor();
  } else {
    deny_access();
    }

}


function approve_and_reward_post(){

  if($_POST['approve_post'] == 'approve'){
    $recipient = sanitize($_POST['page_author']);
    $q = query_db("INSERT INTO `reward_freebies`(`id`, `recipient`, `type`, `description`, `date`, `status`)
    VALUES ('0','{$recipient}','')","");
  }
  echo '<form action="'.$_SESSION['current_url'].'" method="post">
    <input type="text" name="page_author" value="'.$_SESSION['page_author'].'" class="btn btn-success btn-xs">
    <input type="submit" name="approve_post" value="approve" class="btn btn-success btn-xs">
  </form>';
}

# LIST PAGES

function get_page_lists() {
  #print_r($_POST);


if(is_admin() && !query_string_in_url()){

  $show_more_pager = pagerize();

  $limit = $_SESSION['pager_limit'];

  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `page` ORDER BY `id` DESC {$limit}")
  or die('Could not get data:' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  $count = mysqli_num_rows($query);

  $pagelist = '<h2> Pages List</h2>';
  $pagelist = $pagelist . "<table class='table pages-list'><thead><th>&nbsp Page title</th><th>Actions</th></thead>";

  if($count < 1){status_message('alert', 'No more results here!');}

  while($row = mysqli_fetch_array($query)){
    $pagelist = $pagelist

  . '<tr><td class="spreadout">'
  .'<a href="' . BASE_PATH.'?page_name=' .str_ireplace('#','',$row['page_name']) .'&tid='.$row['id'].'"> '
  . ucfirst(urldecode(str_ireplace('-',' ',$row['page_name'])))
  . '</a>'
  .'</td><td class="tiny-text">&nbsp &nbsp<a href="'
  . BASE_PATH ."page/edit?"
  . 'action='
  . 'edit_page&'
  . 'page_name='
  . $row['page_name']
  . '&tid='.$row['id'].'" '
  . '>edit</a> |';

  if(!is_a_training_page($row['id']) || !has_enrolled_members($row_id)){
  $pagelist .= '&nbsp <a href="'
  . BASE_PATH ."page/process.php?"
  . 'action='
  . 'delete_post&'
  . 'page_name='
  . $row['page_name'].'&tid='.$row['id']
  . '&dest_url='.BASE_PATH.'page" '
  . '> delete </a>';
  }
  $pagelist .= "</td></tr>";
  }
  $pagelist = $pagelist . "</table>";


  echo $pagelist;

  echo $show_more_pager;

  } else
  if(!is_admin() && !query_string_in_url()){
    deny_access();
  }
}


function add_new_what(){
  $context = query_string_in_url();
  if(empty($context)){

    $holder = '';

    if(is_admin()){
    echo "<div class='row'>
      <a href='".BASE_PATH."page/add/?type=page'><div class='whitesmoke col-md-5 col-xs-12'><h3> Page  </h3>
      Add regular site content that have menus like About us page etc.</div></a>";

    echo "
      <a href='".ADDONS_PATH."notifications'><div class=' col-md-5 col-xs-12'><h3> Notice </h3>
      Add site Notices. </div></a>";
    }

    if(is_logged_in()){
    $output = addon_is_active('fundraiser');
    if($output){
      echo "
      <a href='".ADDONS_PATH."fundraiser?action=add-fundraiser'><div class='whitesmoke col-md-5 col-xs-12'><h3> Fundraiser </h3>
       {$output['description']}.</div></a>";
      }
    //echo "
    //  <a href='".BASE_PATH."page/add/?type=blog'><div class='gainsboro col-md-5'><h3> Blog post </h3>
    //  Add regular site content that have menus like About us page etc.</div> </a>";


    echo "
      <a href='".ADDONS_PATH."company/?add_type=company'><div class='col-md-5 col-xs-12'><h3> company </h3>
      Add company profile where people can interact with your company or business.</div> </a>";

    echo "<a href='".ADDONS_PATH."ads'><div class='whitesmoke col-md-5 col-xs-12'><h3> +Create New Ads </h3>
      Advertise something. </div></a>";
    }
  if(is_admin()){
    echo "
      <a href='".BASE_PATH."page/add/?type=contest'><div class=' col-md-5 col-xs-12'><h3> Contest </h3>
      Contests are competitions that can be voted on and people can win prizes or cash</div></a>";
    }
    echo '</div>';
  }
}

# EDIT PAGES

function edit_pages(){

//$page_type_query = mysql_query("SELECT `page_type_name` FROM `page_type`");
$path = $_SESSION['prev_url'];

if(is_logged_in() && (url_contains('edit_') || ($row['author'] == $_SESSION['username'] || is_admin()))){
$page = trim(urlencode(sanitize($_GET['page_name'])));
$id = sanitize($_GET['tid']);
#echo $block; // Testing

   $query = mysqli_query($GLOBALS["___mysqli_ston"],
   "select * from page " .
   'where id="' .
   $id .
   '" ' .
   " limit 1") or die("Failed to get selected page" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

   $row = mysqli_fetch_array($query);
          $_SESSION['id'] = $row['id'];
          $_SESSION['page_id'] = $row['id'];
          $_SESSION['page_author'] = $row['author'];
          $_SESSION['page_name'] = $row['page_name'];
          $_SESSION['page_parent_id'] = $row['parent_id'];
          $_SESSION['page_content'] = $row['content'];
          $_SESSION['section_name'] = $row['section_name'];
          $_SESSION['category'] = $row['category'];
          $_SESSION['child_page_number'] = $row['child_page_number'];
          $_SESSION['fetched_page'] = true;
   #DETECT and switch between sections and page


   if(isset($_GET['page_name'])){
   $target = trim(sanitize($_row['page_name']));
   $route = '?page_name=';
   } else if (isset($_GET['section_name'])){
   $target = trim(sanitize($_GET['section_name']));
   $route = '?section_name=';
   $end = 'section';

   } $id = $row['id'];
   // get destination for saving
   // $destination =  BASE_PATH .$route .$target ;

   // now we show the page edit form
   echo "<span> You are editing "
  .'<a id="view-page" href="' .BASE_PATH .$route .urlencode($target) .'&tid='.$row['id'].'"><strong><big> '
  . $target .' [' .$end
  . ']</big></strong></a></span>';

  $menu_fetcher = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `menus` WHERE `menu_item_name`='{$page}' LIMIT 1") or die("MENu item fetching failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  $menu_item = mysqli_fetch_array($menu_fetcher);

$form = '<div class="edit-form">' .
'<form method="POST" action="../process.php">'.
'<input type="hidden" name="action" class="form" value ="update">' .
'<input type="hidden" name="path" value="' .$path .'">' .
'<input type="hidden" name="parent_id" value="' .$_SESSION['page_parent_id'] .'">' .
'<input type="hidden" name="child_page_number" value="' .$_SESSION['child_page_number'] .'">' .
'<input type="hidden" name="ref_url" value="' .$_SESSION['prev_url'] .'">' .
'<input type="hidden" name="page_type" value="post">' .
'<input type="hidden" name="id"  value ="' .$id.'">' ;
if($_SESSION["{$page}_has_comments"] === 'yes'){
  $form = $form.'<em>** You cannot change page name because this page already has comments **</em>' .
  '<input type="hidden" name="page_name" value ="' .$row['page_name'] . '" >' ;
  } else {
$form = $form.'Title: <input type="text" name="page_name" value ="' .$row['page_name'] . '" ><hr>' ;
  }
//$form .= '<input type="hidden" name="destination" value="'.$destination.'">';
  if($row['visible'] == '1'){
    $visible_checked = 'checked="checked"';
  } else {
    $visible_checked = '';
  }
if(is_admin() || has_role('manager')){
$form .= 'Visible: <input type="checkbox" name="visible" value="'.$row['visible'].'" '.$visible_checked.'> (Yes)<hr>';
}
$form = $form.'<hr><input type="hidden" name="page_type"value="'.$row['page_type'].'" <br>Content:
<br><textarea name="content" id="content-area" rows="12" >' .urldecode($row['content']) .'</textarea>
<br><em>to disableslideshow and show images in list, add "{show_images_in_lists} to the content"</em>' ;

//~ $category_query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from sections WHERE `is_category`='yes' order by position asc") or die("Failed to select sections!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

//~ $form = $form .'<br>Category : <select name="category" size="1">';
//~ while($category= mysqli_fetch_array($category_query)) {
  //~ $form = $form . "<option value='" .
  //~ $category['section_name'] . "'";
   //~ if($category['section_name'] == $_GET['category']) {
     //~ $form = $form . ' selected="selected" >'.strtoupper($category['section_name']) . '</option>';
     //~ }
     //~ else {
  //~ $form = $form . '>'.strtoupper($category['section_name']) .'</option>';
  //~ }
//~ }
//~ $form = $form .'</select>';

if($row['allow_comments'] == 'yes'){
  $comments = 'checked="checked"';
  } else { $comments = ''; }
if($row['show_in_streams'] == 'yes'){
  $show_in_streams = 'checked="checked"';
  } else { $show_in_streams = ''; }

if($row['show_author'] == 'yes'){
  $show_author_checked = 'checked="checked"';
  } else {
    $show_author_checked = '';
    }
if(!empty($row['parent_id'])){
  $parent_id = $row['parent_id'];
  } else {
    $parent_id = '0';
    }

$form = $form .'<br>
Parent id: <input type="text" name="parent_id" value="'.$parent_id.'"><br>
<em>tick for yes</em><br>
Allow comments ?:<input type="checkbox" name="allow_comments" value="yes" '.$comments.'>
<br>Show author:  <input type="checkbox" name="show_author" value="yes" '.$show_author_checked.'>
<br>Show in streams?:  <input type="checkbox" name="show_in_streams" value="yes" '.$show_in_streams.'>
<br><input type="submit" name="updated" value="Update Post" class="submit">' ;
if(!has_enrolled_members($row['id'])){
  $form .= '<input type="submit" name="action" value="delete post">' ;
}
$form .= '</form></div>';

echo $form;
add_ckeditor();
  } deny_access();
 }


function add_page_type(){// deprecated
  //echo "<form method='post' action='./process.php'>
  //<input type='text' name='page_type' placeholder='page type name' value=''>
  //<input type='submit' value='Add page type' name='add_page_type' class='button-primary'>
  //<input type='submit' value='Delete page type' name='delete_page_type' class='button delete'>
  //</form> ";
  }


function delete_page_type(){
  if(isset($_GET['del_page_type']) && is_admin()){
    $id = sanitize($_GET['del_page_type']);
    $page_type = sanitize($_GET['page_type_name']);

    $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id FROM page WHERE page_type='{$page_type}'");
    $num = mysqli_num_rows($query);

    //echo 'Num = ' . $num ; testing
    if(empty($num)){
      $query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM page_type WHERE id='{$id}'");
      if($query){
        session_message('success','Page type deleted !');
        redirect_to($_SESSION['prev_url']);
        }
      } else { session_message('alert',"You cannot delete [{$page_type}] page type, First delete all posts of this page type.");
        redirect_to($_SESSION['prev_url']); }
    }

  }

function list_page_types(){ // deprecated
  //echo "<h1>Page Types</h1>";
  //$query = mysql_query("SELECT * FROM page_type");
  //while($result = mysql_fetch_array($query)){
  //  echo $result['page_type_name'] ."<span class='tiny-text pull-right'><a href='".$_SESSION['current_url'] ."?del_page_type={$result['id']}&page_type_name={$result['page_type_name']}'>delete</a></span><hr>";
  //  }

  }


function author_account_type_is($account_type){
  $author = $_SESSION['page_author'];
  $value = query_db("SELECT account_type FROM user WHERE user_name='{$author}'",
  "Could not get author account_type! ");
  if($account_type == $value['result'][0]['account_type']){
    return true;
  } else {
    return false;
  }
}

function my_posts(){
  $more = $_GET['show_more_my_posts'];
  if(is_logged_in() && is_user_page()){
  $user = trim(sanitize($_GET['user']));
  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `page_name`, `page_type`, `destination`
  FROM `page`
  WHERE `author`='{$user}' LIMIT 0, {$more}");
  echo '<div class="padding-20">';
  while($result = mysqli_fetch_array($query)){
    echo '<a href="' .$result['destination'] .'">'.urldecode($result['page_name'])
    .'</a><hr><div class="small"><em>'.$result['page_type'].'</em></div>';
    }
    echo '</div>';
  }
}

function record_post_earning($amount,$reason){
  $author = get_user_details($_SESSION['page_author']);
  $owner_id = $author['id'];
  $page_id = $_SESSION['page_id'];
  $reactor_id = $_SESSION['user_id'];
  $date = date('Y-m-d');
  $reason = trim(sanitize($reason));
  $q = query_db("INSERT INTO `page_reactions`(`id`, `page_id`, `owner_id`, `reactor_id`, `amount`, `reason`, `date`)
  VALUES ('0','{$page_id}','{$owner_id}','{$reactor_id}','{$amount}','{$reason}','{$date}')",
  "Could not record post earning ");
  }


function show_total_post_earnings($timeframe = "month"){
  $owner_id = $_SESSION['user_id'];
  if($timeframe == 'month'){
  $period = date('Y-m');
  $date_condition = "AND date LIKE '%{$period}%'";
  $time_text = 'this month';
  } else if($timeframe == 'year'){
  $period = date('Y');
  $date_condition = "AND date LIKE '%{$period}%'";
  $time_text = 'this year';
  } else if(empty($timeframe)){
  $date_condition = "";
  $time_text = 'for all time';
  }
  $page_id = $_SESSION['page_id'];
  $q = query_db("SELECT SUM(amount) as sum FROM page_reactions WHERE page_id='{$page_id}' AND owner_id='{$owner_id}' {$date_condition}",
  "Could not select amount in show total page earnings ");
  if(!empty($q['result'][0]['sum'])){
    echo '<hr>';

    echo '<div class="padding-10 tiny-text inline-block whitesmoke">';
    show_total_num_page_reactions();
    echo ' page reactions '.$time_text.': </span><b><span class="green-text padding-10">';
    echo $_SESSION['preferred_currency'].' '.convert_to_user_currency($q['result'][0]['sum']);
    echo '</span></b></div>';
    } else {
      echo '<div class="padding-10 tiny-text inline-block whitesmoke">';
    show_total_num_page_reactions();
    echo ' page reactions '.$time_text.': </span><b><span class=" padding-10">';
      echo $_SESSION['preferred_currency'].' '.convert_to_user_currency($q['result'][0]['sum']);
      echo '</span></b></div>';
    }
    echo '<hr>';
  }


function get_total_earnings_from_all_posts($timeframe = "month"){
  $owner_id = $_SESSION['user_id'];
  if($timeframe == 'month'){
  $period = date('Y-m');
  } else if($timeframe == 'year'){
  $period = date('Y');
  }
  $q = query_db("SELECT SUM(amount) as sum FROM page_reactions WHERE owner_id='{$owner_id}' AND date LIKE '%{$period}%'",
  "Could not select amount in show total earnings from all posts");
  if(!empty($q['result'][0]['sum'])){
    return $q['result'][0]['sum'];
    } else {
      return ' 0.00';
      }
  }

function show_total_earnings_from_all_posts($timeframe = "month"){
  $output = get_total_earnings_from_all_posts();
  echo $output;
  }

function add_child_page(){
  if($_SESSION['page_type'] =='page' || $_SESSION['page_type'] =='discussion' && is_author()){
  if(is_logged_in() && (is_author() || is_admin())){
    echo "<div id='add-child' class='btn btn-default padding-10 clear whitesmoke pull-right block'>";
    echo "Add child page";
    echo "</div>";
  }
  echo '<div id="start-discussion" class="block margin-10 pull-left">';
  say_something('discussion');
  echo '</div><br>';
  }
}

function remove_child_page(){
  if(isset($_GET['remove_child']) && (is_author() || is_admin())){
    $child_id = sanitize($_GET['remove_child']);
    $query = mysqli_query($GLOBALS['___mysqli_ston'],"UPDATE page SET parent_id='0' WHERE id='{$child_id}'")
    or die('Failed to remove child page '.mysqli_error($GLOBALS['___mysqli_ston']));
    redirect_to($_SESSION['prev_url']);
    }

}

function set_parent(){
  if(isset($_POST['set_parent']) && (is_author() || is_admin())){
    $parent_id = sanitize($_POST['set_parent']);
    }
  }

function is_child_page(){
  if(!empty($_SESSION['parent_id']) || !empty($_GET['parent_id'])){
    return true;
    }
}

function get_next_page(){
  if(is_child_page()){
  $child_num = sanitize($_GET['child_num']);
  $parent_id = $_SESSION['parent_id'];
  $id = sanitize($_GET['tid']);

  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`, `page_name`, `child_page_number` from page
  WHERE parent_id='{$parent_id}' and child_page_number >='{$child_num}' and id !='{$id}' order by child_page_number ASC limit 1");
  $num = mysqli_num_rows($query);

  $result = mysqli_fetch_array($query);
    if(!empty($num)){
    echo '<div class="clear pull-right padding-10 margin-10 inline-block"><span class="badge padding-10"> NEXT </span> : <a href="'.BASE_PATH.'?page_name='.$result['page_name'].'&tid='.$result['id'].'&child_num='.$result['child_page_number'].'">&raquo;'.str_ireplace('-',' ',urldecode(ucfirst($result['page_name']))).'</a></div>';
    }
  }
}

function get_parent_page($parent_type='',$parent_id=''){
  if(empty($parent_id)){
  $parent_id = $_SESSION['parent_id'];
  }
  if(empty($parent_type) || $parent_type == 'discussion' || $parent_type == 'page'){
  $parent_type='page';
  $query = query_db("SELECT `id`,`page_name` FROM {$parent_type} WHERE id='{$parent_id}'",
  "Could not get parent page! ");
  }

  foreach($query['result'] as $result){
    if($parent_type != 'company'){
    echo '<div class="pull-right whitesmoke padding-10 inline-block clear margin-10"><strong>Parent :</strong><a href="'.BASE_PATH.'?page_name='.$result['page_name'].'&tid='.$result['id'].'">'.urldecode(str_ireplace('-',' ',$result['page_name'])).'</a></div>';
    }
  }
}

function link_to_page_title_by_id($id){
  $value = query_db("SELECT page_name FROM page WHERE id='{$id}'",
  "Could not get page title ");
  $title = $value['result'][0]['page_name'];
  $link = '<a href="'.BASE_PATH.'?page_name='.$title.'&tid='.$id.'">'.str_ireplace('%2c',',',str_ireplace('-',' ',html_entity_decode($title))).'</a>';
  echo $link;
  return $link;
}

function link_to_page_by_id($id){
  $value = query_db("SELECT page_name FROM page WHERE id='{$id}'",
  "Could not get page title ");
  $title = $value['result'][0]['page_name'];
  $link =  '<a href="'.BASE_PATH.'?page_name='.$title.'&tid='.$id.'">'.str_ireplace('%2c',',',str_ireplace('-',' ',html_entity_decode($title))).'</a>';
  echo $link;
  return $link;
}


function list_child_pages(){
  $id = $_SESSION['page_id'];
  if(!empty($_GET['tid'])){
    $id = sanitize($_GET['tid']);


  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`,`page_name`,`child_page_number` FROM page WHERE parent_id='{$id}' ORDER BY child_page_number ASC") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  $num = mysqli_num_rows($query);
  if(!empty($num)){
    echo '<div class="block">
    <strong class="padding-10 inline-block">Child pages</strong><br>';
    while($result = mysqli_fetch_array($query)){
      $child_num_up = $result['child_page_number'] - 1 ;
      $child_num_down = $result['child_page_number'] + 1;

      echo '<li class="menu-pad padding-10 clear"><span class="inline-block"><a href="'.BASE_PATH.'?page_name='.$result['page_name'].'&tid='.$result['id'].'&child_num='.$result['child_page_number'].'&next='.$next_id.'">'.ucfirst(str_ireplace('-',' ',urldecode($result['page_name']))).'</a> </span>';
              if(is_author() or is_admin()){
              echo '<span class="pull-right inline-block">
                <a href="'.BASE_PATH.'?page_name='.$result['page_name'].'&tid='.$result['id'].'&move_child_position='.$child_num_up.'"><span class="glyphicon glyphicon-menu-up"></span></a>
                <a href="'.BASE_PATH.'?page_name='.$result['page_name'].'&tid='.$result['id'].'&move_child_position='.$child_num_down.'"><span class="glyphicon glyphicon-menu-down"></span></a>
                <a href="'.$_SESSION['current_url'].'&remove_child='.$result['id'].'"><span class="glyphicon glyphicon-minus"></span></a>
              </span>';
              }
            echo '</li>';
      //}
      }
      echo '</div>';
    }
  }
}

function change_child_position(){
  if(isset($_GET['tid'])){
    $id = sanitize($_GET['tid']);
    }
    if(isset($_GET['move_child_position'])){
      $child_page_number = sanitize($_GET['move_child_position']);
      $q = mysqli_query($GLOBALS['___mysqli_ston'],
      "UPDATE page set child_page_number='{$child_page_number}' where id='{$id}'")
      or die('Could not reorder child page '.mysqli_error($GLOBALS['___mysqli_ston']));
      }
      if($q){
        redirect_to($_SESSION['prev_url']);
        }
  }


function disqus(){
  echo '<div id="disqus_thread"></div>
<script>';
echo "
/**
*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
/*
var disqus_config = function () {
this.page.url = {$_SESSION['current_url']};  // Replace PAGE_URL with your page's canonical URL variable
this.page.identifier = {$_GET['tid']}; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
};
*/
(function() { // DON'T EDIT BELOW THIS LINE
var d = document, s = d.createElement('script');
s.src = '//friendsinmoney.com.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>";

echo '<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>';

  }

function add_comment($subject = '',$reply='',$placeholder='',$button_text='',$upload_allowed=''){
  if(!isset($_GET['go-embed-mode'])){
    remove_file();
    if($subject == ''){
      $parent_type ='page';
      } else { $parent_type = $subject; }
    $path = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    if(isset($_GET['tid'])){
      $parent_id = sanitize($_GET['tid']);
      } else {
    $parent_id = $_SESSION['page_id'];
    }
    $created = date('c');

    //$reply = 'Join the Discussion';
    #print_r($_POST);
      if(isset($_POST['add_comment'])){
        $subject_name = trim(sanitize($_POST['page_name']));
        $content = trim(sanitize($_POST['content']));
        $parent_page_author = $_SESSION['page_author'];
        $comment_author = $_SESSION['username'];
        $query = query_db("INSERT INTO `comments`(`id`,`path`,`parent_type`,`parent_id`,`parent_page_author`,`comment_author`,`content`,`created`) VALUES
        ('0', '{$path}','{$parent_type}','{$parent_id}','{$parent_page_author}','{$comment_author}', '{$content}','{$created}')",
        "Error inserting comments ");

        if($query){
          $page_path = $_SESSION['current_url'];
          $page_link = link_to_page_by_id($_SESSION['page_id']);
          $q = query_db("SELECT `id` FROM comments WHERE `content`='{$content}' AND `created`='{$created}' LIMIT 0,1");
          $result= $q['result'][0];
          if(!is_author()){
            if(!is_on_free_plan($_SESSION['user_id'])){
              if(!user_has_commented($_SESSION['page_id'],$_SESSION['user_id'])){
                record_post_earning($amount=5,$reason="{$comment_author} commented on your post {$page_link}.");
                update_user_funds($user=$_SESSION['page_author'],$amount=5,$reason="{$comment_author} commented on your post {$page_link}");
              }
            } else {
              if(!user_has_commented($_SESSION['page_id'],$_SESSION['user_id'])){
                //~ update_user_funds($user=$comment_author,$amount=-1,$reason="You commented on {$page_link} ");
                record_post_earning($amount=1,$reason="{$comment_author} commented on your post {$page_link}.");
                update_user_funds($user=$_SESSION['page_author'],$amount=1,$reason="{$comment_author} commented on your post {$page_link}");
              }
            }
          }
          increment_user_activity_count_in('comments_made');
          session_message('success','Comment saved!');
          redirect_to($_SESSION['current_url']);
        }
      }

      if(isset($_GET['reply_to'])){
        $parent_id = trim(sanitize($_GET['parent_id']));
        $reply = "Reply to Comment # ".$_GET['reply_to'];
        } else if(empty($reply)){
         $parent_id = '';
          $reply = 'Comments and Responses';}
      if($placeholder == ''){
        $placeholder = 'say something about this';
        }
      if($button_text == ''){
        $button_text = 'Add comment';
        }
      echo '<div class="padding-10">';
      echo "<h4 id='comments'>{$reply} </h4>";

      if(is_logged_in()){
      edit_comment();
      echo '<div class="padding-10">';
      echo '<i>'.$placeholder.'</i><form method="post" action="'.$_SESSION['current_url'].'" id="comment-form" class="whitesmoke padding-20" enctype="multipart/form-data">
      <input type="hidden" name="page_name" value="'.$page .'" placeholder="">
      <input type="hidden" name="parent_id" value="'.$parent_id.'">
      <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
      <!-- Name of input element determines name in $_FILES array -->';
      //~ echo'<em class="tiny-text green-text">Posting a comment will transfer '.$_SESSION['preferred_currency'].' '.convert_to_user_currency(5).' from your account to this post</em>
      echo ' <textarea name="content" size="5" placeholder="'.$placeholder.'"></textarea>

      <br><input type="submit" name="add_comment" value="'.$button_text.'" class="button-primary">
      </form> ';
      echo '</div>';
    }
      # SHOW MORE
      //~ if(isset($_POST['comment_list_limit'])){
        //~ $comment_limit = $_POST['comment_list_limit'];
      //~ } else { $comment_limit = 10;}
      //~ if(isset($_POST['comment_list_number_holder'])){
        //~ $step = $_POST['comment_list_number_holder'];
      //~ } else{ $step = 0; }

      //~ if(isset($_POST['clear_comment_list_values'])){
          //~ unset($_POST);
          //~ $number_holder = '';
          //~ $comment_limit = 10;
          //~ $step = 0;
          //~ }

        //~ $limit = "LIMIT ". $step .", ".$comment_limit;
        //~ $number_holder = $comment_limit + $step;

        echo '<table id="comments-thread"><tbody>';
      // consider deprecating
        if(isset($_GET['tid'])){
      $parent_id = sanitize($_GET['tid']);
      } else {
      $parent_id = $_SESSION['page_id'];
      }
      // ....

      //Fetch associated Photos


      $path = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

      $query = query_db("SELECT * FROM `comments` WHERE `parent_type`='{$parent_type}' AND `parent_id`='{$parent_id}' ORDER BY `id` ASC",
      "comment list error");
      $count = $query['num_results'];
      foreach($query['result'] as $result){

      #Show Comments
        echo "<tr><td class='table-message-plain'>";

        //~ echo '<a href="'.BASE_PATH.'user/?user='.$result['comment_author'] .'">'.$result['comment_author'] .'</a> > ';
        echo "<div class='last-updated pull-right'> <time class='timeago' datetime='".$result['created'] ."'>".$result['created'] ."</time></div><br>";
        link_to_user_by_username($result['comment_author']); echo ' : ';
        echo parse_text_for_output($result['content']);
        if((($result['author'] == $_SESSION['username']) || is_author() || is_admin()) && is_logged_in()){
        echo ' <span class="pull-right tiny-edit-text inline-block"> <a href="'.$_SERVER['REQUEST_URI']."&author=".$result['author'] . "&edit_comment=".$result['id'].'"> edit </a> &nbsp;&nbsp';
        echo '  <a href="'.$_SERVER['REQUEST_URI']."&author=".$result['author'] . "&delete_comment=".$result['id'].'"> delete </a></span><br>';
        }
        echo "</td></tr>";
        }
        echo '</tbody></table>';

      if($count < 1 && is_logged_in()){status_message('alert', 'There are no responses to show!');}
      else { $_SESSION["{$page}_has_comments"] = 'yes';}
      echo '</div>';


  }
}

function get_recent_comments_to_my_posts($num = ''){
  $username = $_SESSION['username'];
  if(empty($num)){
    $num = 2;
  }
  $q = query_db("SELECT * FROM comments where parent_page_author='{$username}' order by id desc limit {$num}",
  "Could not get recent comments! ");
  return $q['result'];
}

function show_recent_comments_to_my_posts(){
  if(is_logged_in()){
    $comments = get_recent_comments_to_my_posts();
    if($comments['num_results'] > 0){
      echo '<b class="tiny-text margin-3">Recent comments to my posts</b><br>';
      foreach($comments as $result){
        #Show Comments
        echo '<div class="padding-10 margin-3 tiny-text well">';
        link_to_user_by_username($result['comment_author']);
        echo ' commented on your post ';
        link_to_page_title_by_id($result['parent_id']);
        echo ' saying ';
        echo summarize(parse_text_for_output($result['content']),150);
        echo " <time class='timeago' datetime='".$result['created'] ."'>".$result['created'] ."</time>";
        //~ if((($result['author'] == $_SESSION['username']) || is_author() || is_admin())&&is_logged_in()){
        //~ echo ' <span class="pull-right tiny-edit-text inline-block"> <a href="'.$_SERVER['REQUEST_URI']."&author=".$result['author'] . "&edit_comment=".$result['id'].'"> edit </a> &nbsp;&nbsp';
        //~ echo '  <a href="'.$_SERVER['REQUEST_URI']."&author=".$result['author'] . "&delete_comment=".$result['id'].'"> delete </a></span><br>';
        //~ }
        echo ' will you respond? </div>';
      }
    }
  }
}

function edit_comment($comment_upload_allowed=''){
  if(isset($_GET['edit_comment'])){

    $ref_url = $_POST['ref_url'];
    $comment_id = sanitize($_GET['edit_comment']);

    $query = query_db("SELECT `content`, `parent_id` FROM comments WHERE id='{$comment_id}'",
    "Could not edit comment! ");

    $result = $query['result'][0];
    $content = $result['content'];
    $parent_id = $result['parent_id'];

    if(isset($_POST['edit_comment'])){
      $ref_url = $_POST['ref_url'];
      $content = trim(sanitize($_POST['content']));

      $query = query_db("UPDATE comments SET `content`='{$content}' WHERE id='{$comment_id}'",
      "Could not edit comment ");

      if($query){
        session_message('success', 'Edited and saved!');
        upload_image('','','',$comment_id=$result['id']);
        redirect_to($ref_url);
      }
    }

    echo '<h3>Editing </h3><form method="post" action="'.$_SESSION['current_url'].'" class="whitesmoke padding-20" enctype="multipart/form-data">
    <input type="hidden" name="parent_id" value="'.$parent_id.'">
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    <input type="hidden" name="ref_url" value="'.$_SESSION['prev_url'].'" />
    <!-- Name of input element determines name in $_FILES array -->';
    //if($comment_upload_allowed === 'true'){
    //echo '<input type="file" size="500" name="image_field" value="" placeholder="choose picture">';
    //}
    echo' <textarea name="content" size="5">'.$content.'</textarea>
    <input type="submit" name="edit_comment" value="Save" class="button-primary">
    </form> ';

    }


  // Modal

  echo '<div class="modal fade" id="editModal" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                &times;</button>
              <h2 class="modal-title" id="myModalLabel">Edit comment</h2>
              </div>
            <div class="modal-body">
            ';




            echo '</div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Submit changes</button>
            </div>
          </div><!-- /.modal-content -->
        </div>
      </div>';

  }


function get_num_comments($page_id=''){
  $query = query_db("SELECT COUNT(id) as count FROM comments WHERE parent_id='{$page_id}'",
  "Could not get num comments! ");
  return $query['count']  ;
}


function get_total_num_posts(){
  $value = query_db("SELECT COUNT(id) as count from page","Could not get num posts ");
  return $value['count'];
}

function get_num_page_reactions($page_id='',$reaction){
  if(!empty($page_id)){
    $value = query_db("SELECT COUNT(id) as count from page_reactions WHERE page_id='{$page_id}' and reason='{$reaction}'",
    "Could not get total num page reactions! ");
    return $value['count'];
  } else {
    $page_id = $_SESSION['page_id'];
    if(!is_home_page() && $_GET['page_name'] != 'talk'){
      $value = query_db("SELECT COUNT(id) as count from page_reactions WHERE page_id='{$page_id}' and reason='{$reaction}'",
      "Could not get total num page reactions! ");
      return $value['count'];
    }
  }
}

function get_total_num_page_reactions($page_id=''){
  if(!empty($page_id)){
    $value = query_db("SELECT COUNT(id) as count from page_reactions WHERE page_id='{$page_id}'",
    "Could not get total num page reactions! ");
    return $value['count'];
  } else {
    $page_id = $_SESSION['page_id'];
    if(!is_home_page() && $_GET['page_name'] != 'talk'){
      $value = query_db("SELECT COUNT(id) as count from page_reactions WHERE page_id='{$page_id}'",
      "Could not get total num page reactions! ");
      return $value['count'];
    }
  }
}


function show_total_num_page_reactions(){
  $num = get_total_num_page_reactions();
  echo $num;
  }



function delete_comment(){
  $comment_author = $_GET['author'];
  $id = trim(sanitize($_GET['delete_comment']));
  if(isset($_GET['delete_comment']) && (is_author() || ($comment_author === $_SESSION['username'])) ){
    $query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `comments` WHERE `id`={$id}")
    or die("Failed to delete comment " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

  if($query) {
    session_message("Comment deleted!");
    }
  redirect_to($_SESSION['prev_url']);
  }
}

function show_front_promoted_posts(){
  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `page` WHERE `promote_on_homepage`='yes'")
  or die("Failed to get Front promoted posts" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  $num = mysqli_num_rows($query);
  if($num<1){ echo "There are no promoted posts yet!";}

  while($result = mysqli_fetch_array($query)){
  echo "<a href='{$result['destination']}'>".str_ireplace('-',' ',$result['page_name'])."</a><br><hr>";
  }
}


function remove_from_promoted_posts($page){

}

function get_promoted_posts($string='', $limit='') {
   $query = mysqli_query($GLOBALS["___mysqli_ston"],
   "SELECT * from page WHERE promoted_on_homepage ='yes' ORDER BY id DESC' LIMIT 10") or die("Failed to get promoted posts" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

  echo "<div class='sweet_title'>Promoted Posts</br></div>";

   while($result= mysqli_fetch_array($query)){
   if ($section==='contest'){
     echo "<li><a href='" .ADDONS_PATH ."contest/?contest_name=" .$result['page_name'] ."&contest=yes'>"
     . ucfirst($result['page_name']) ."</a><p>" ;
     if($_GET['page_name'] !=='home'){
     echo strip_tags(urldecode($result['content'])) ;} echo "</p></li>";
     } else if(isset($_GET['section_name'])) {
     echo "<li><h3><a href='" .BASE_PATH ."?page_name=" .$result['page_name'] ."'>"
    . ucfirst($result['page_name']) ."</a></h3> &nbsp &nbsp" ;
    echo urldecode(substr($result['content'],0,160))."..." ;
    echo "</li>";
     } else {
      echo "<li><a href='" .BASE_PATH ."?page_name=" .$result['page_name'] ."'>"
     . ucfirst($result['page_name']) ."</a>" ;
     if($_GET['page_name'] !=='home'){
     echo urldecode(substr($result['content'],0,160))."..." ;} echo "</li>";
     }
  }
      echo "</ul></div>";

      echo $result['section_name'];
    // show sections

    if($_GET['page_name'] ==='sections'){
    get_grid_sections();
  }


}

function show_contributors(){

}

function show_new_page_content(){
  if(addon_is_active('page')){
      space_down();
      //Link to posts (show num posts in last 7 days)
      $num_posts = get_total_num_posts();
      echo '
      <div class="sweet_title">';
      if(is_logged_in()){
        echo '<a href="'.BASE_PATH.'?page_name=talk">';
      }
      echo 'Talk ';
      if(is_logged_in()){
        echo '('.$num_posts.')';
        echo '</a>';
      }

      if(is_logged_in()){
      echo '<span class="pull-right tiny-text btn btn-primary btn-xs margin-3" data-toggle="modal"
      data-target="#talkModal">+say something</span>';}

      if(is_logged_in()){
        say_something();
      }
      echo '</div><div class="well">';
      show_page_reactions();

      $num_channels = get_total_num_channels();
      if(empty($num_channels)){
        $num_channels = 0;
      }

      if(is_logged_in()){
        show_featured_content();
      }

      echo '<h4 class="padding-10">Earn Money When People like what you Say </h4>';
      //~ show_latest_content_in('page','',$limit=4);
      get_discussion_content($max_num='4');
      if(is_logged_in()){
        echo '<div class="pull-right tiny-text">
        <a href="'.ADDONS_PATH.'hashtags">#Hashtag Channels</a> <span class="badge tiny-text">'.$num_channels.'<br></span>
        </div>
        <br><div class="block tiny-text"><a href="'.BASE_PATH.'?page_name=talk">show more &raquo;</a></div>';
      }
      echo '</div>';
    }
  }

function show_page_content($page='home') {
  if(!url_contains('?page_name=talk') && !url_contains('?page_name=home')){

    unset($_SESSION['page_is_a_course']);
    unset($_SESSION['enrollment_fee']);
    delete_comment();
    change_child_position();
    remove_child_page();
    if(!isset($_GET['section_name'])){
    $_SESSION['page_context'] = 'post-';
    }
    $edit_page = url_contains('edit-');

    if((!empty($_GET['page_name']) && $_GET['page_name'] != 'home') && !empty($_GET['tid'])){
      $id = sanitize($_GET['tid']);
      $page_name = trim(sanitize($_GET['page_name']));
      $file_parent = $page_name .' page';
      $query = query_db("select * from page WHERE id={$id} limit 1",
      "Failed to get selected page! ");
    } else if(isset($_GET['page_name']) && empty($_GET['tid'])){
      $page = trim(urlencode(sanitize($_GET['page_name'])));
      $query = query_db(
      "select `id`, `page_name` from page where page_name LIKE '%{$page}%' limit 1",
      "Failed to get selected page with empty tid! ");
      $num = $query['num_results'];
      if(!empty($num)){
      $temp = $query['result'][0];
        if($temp['page_name'] != 'home'){
        $correct_path = BASE_PATH.'?page_name='.$temp['page_name'].'&tid='.$temp['id'];
        redirect_to($correct_path);
        }
      }
    } else {
      //~ redirect_to(BASE_PATH.'?page_name=home&tid=1');
    }
      $num = $query['num_results'];

      if(!empty($num)){

            $result= $query['result'][0];
            $_SESSION['id'] = $result['id'];
            $_SESSION['page_id'] = $result['id'];
            $_SESSION['page_author'] = $result['author'];
            $_SESSION['page_name'] = $result['page_name'];
            $_SESSION['page_parent_id'] = $result['parent_id'];
            $_SESSION['page_visible'] = $result['visible'];
            $_SESSION['page_type'] = $result['page_type'];
            $_SESSION['page_content'] = $result['content'];
            //~ $_SESSION['section_name'] = $result['section_name'];
            $_SESSION['page_category'] = $result['category'];
            if(!empty($result['enrollment_fee'])){
              $_SESSION['page_is_a_course'] = true;
              $_SESSION['page_enrollment_fee'] = $result['enrollment_fee'];
              } else {
                $_SESSION['page_is_a_course'] = false;
                }
            $_SESSION['fetched_page'] = true;

            if($_GET['page_name'] !== 'home' && $_GET['page_name'] !== 'contact'){ # DO NOT SHOW ALL THESE ON HOMEPAGE OR CONTACT PAGE
              
              if($result['page_name'] !=='home'){
              if(!empty($result['last_updated'])){
                $last_update = "<div class='last-updated tiny-text'>Last Updated -  <time class='timeago pull-right' datetime='".$result['last_updated'] ."'>".$result['last_updated'] ."</time></div>";
              }else if(!empty($result['created'])){
                $last_update = "<div class='last-updated tiny-text'>Created -  <time class='timeago' datetime='".$result['created'] ."'>".$result['created'] ."</time></div>";
              }
              }

              // Echo the Page title


              echo "<div class='sweet_title title'>" . str_ireplace('-',' ',ucfirst(html_entity_decode(urldecode($result['page_name'])))) .$last_update."</br></div>";
              
              echo '  <div class="top-left-links"><ul>';

              # IF OWNER OR MANaGER, THEN SHOW EDIt link
              if(isset($_SESSION['username'])){

              if(($result['author'] === $_SESSION['username']) || ($_SESSION['role'] === 'manager' || $_SESSION['role'] === 'admin'))  {

                echo '<li id="show_blocks_form_link" class="float-right-lists">
                <a href="'.BASE_PATH .'page/edit/?action=edit_page&page_name='. str_ireplace('#','',$_SESSION['page_name']).'&tid='.$result['id'].'&section_name='
            .$_SESSION['section_name'].'&category='.$_SESSION['category'].'"> Edit page </a></li>';
              }
              if(!empty($_SESSION['role'])){
              echo'<li align="right" class="float-right-lists">
              <a href="'.BASE_PATH .'page/add"> +Add new</a></li>';
              }
              echo '</ul>
              </div>';
              }
            $share_buttons ='<div class="block">
              <!-- Go to www.addthis.com/dashboard to customize your tools --> <div class="addthis_inline_share_toolbox"></div>
              </div>';


              # GET PAGE IMAGES
              $is_mobile = check_user_agent('mobile');
              if($is_mobile){
              $size='medium';
              } else {
              $size='large';
              }


              $pics = get_linked_image($parent=$result['page_type'],$parent_id=$result['id'],$pic_size=$size,$limit='',$has_zoom='true',$for_slideshow='true');

              if(empty($pics)){
                $pics = get_linked_image($parent=$result['page_name']." {$result['page_type']}",$parent_id=$result['id'],$pic_size=$size,$limit='',$has_zoom='true',$for_slideshow='true');
              }
              if(empty($pics)){
                $pics = get_linked_image($parent=$result['page_name']." page",$parent_id=$result['id'],$pic_size=$size,$limit='',$has_zoom='true',$for_slideshow='true');
                }


              
              if(($_GET['page_name'] !== 'login') && (!$edit_page)  && ($_GET['page_name'] !== 'sections')){



               echo "";
               //show picture upload form for authors
              if(!url_contains('contact')){

              upload_no_edit();

              } //end picture upload form

               get_parent_page($result['page_type'],$result['parent_id']);

              echo '<div class="col-md-12 col-xs-12 padding-10">';
              #Show page images

              if(!empty($pics)){
                //~ print_r($pics);
                $switch = strpos($result['content'],'show_images_in_lists');
                if($switch > 1){
                  //echo "yes i am inlists ";
                  show_images_in_list($images=$pics);
                  } else {
                  show_slideshow_block($pics);
                }

              }


              #SHOW CONTENT ONLY WHEN USER IS NOT PERFORMING AN ACTION e.g VOTING
              if(! isset($_GET['action'])){
                $content1 = $result['content'];
                $content = str_ireplace("{show_images_in_lists}",'',$content1);
                $content = str_ireplace("\{ \}",'',$content);

              $content = parse_text_for_output($content);
              }


            //~ Show author picture
            echo '<div class=" padding-10 col-md-12 col-xs-12">';
            if($result['show_author'] == 'yes'){
            //~ Ready author picture for display
            if($result['page_type'] == 'company post'){
              if(addon_is_active('company')){
                $userpic = get_company_logo($_SESSION['page_parent_id'],'35px');
              }
              $user = array();
              $user['thumbnail'] = $userpic;
              } else {
              $user = show_user_pic($user=$result['author'] ,$pic_class='img-circular','35px');
              }
              echo $user['thumbnail'] ; link_to_user_by_username($_SESSION['page_author']);
              }
            //~ Show content
            echo '&nbsp;&nbsp;'.$content;

            //Show attachments

            show_linked_attachments();

            echo '</div>';
            if(!user_has_reacted('like')){
              react_to_page();
            } else {
              $reactions = get_num_page_reactions($_SESSION['page_id'],'like');
              echo '<em class="badge padding-10">'.$reactions.' likes</em>';
            }
            if(is_author()){
              show_total_post_earnings('');
              }
            if(addon_is_active('featured_content')){
              show_feature_this_link();
            }
            echo '<br>ID :'.$_SESSION['page_id'].'<br>';

            //~ show_page_reactions();
            process_hashtags($_SESSION['page_content'],$_SESSION['current_url']);
            echo '<br>'.$share_buttons;
            publish_post();
            unpublish_post();


            //Show child pages
            echo '<div align="right" class="col-md-12  col-xs-12">';
              if(!is_child_page()){
              add_child_page();
              }
              get_next_page();
            echo '</div>';
            list_child_pages();

              }
            }
            echo '</div>';


      } //end $num

        else {
          if(!empty($_GET['page_name'])){
          status_message('alert','<h1><span class="glyphicon glyphicon-alert text-center padding-10"></span></h1>There must be a mistake, this page does not exist!');
            if(isset($_GET['page_name'])){
            $page_name_raw = trim(sanitize($_GET['page_name']));
            $page_name = substr($page_name_raw,0,5);
           //echo $page_name;
            $query = query_db("SELECT * FROM page WHERE page_name LIKE '%{$page_name}%' ORDER BY id DESC LIMIT 0, 10",
            "Could not get suggested pages near try these pages! ");
            echo '<h3>Try These pages &raquo;</h3><ol>';
            foreach($query['result'] as $result){

              echo '<li><a href="' . BASE_PATH.'?page_name=' .$result['page_name'] .'&tid='.$result['id'].'"> '
              . ucfirst(urldecode(str_ireplace('-',' ',$result['page_name'])))
              . '</a></li><hr>';
            }
            echo '</ol>';
          }
        }

          //~ go_back();

          }




          if($result['allow_comments'] === 'yes'){
          add_comment();

            if(!is_logged_in()){
             log_in_to_comment();
            }
           //get_author_picture();
          echo "</div>";

    }
    unset($_SESSION['fetched_page']);
  }

}



function get_author_picture(){
  if(!empty($_SESSION['page_author'])){
    $author = get_user_details($_SESSION['page_author']);

    if(empty($author['picture_thumbnail'])){
    $author['picture_thumbnail'] = default_pic_fallback('',$size = 'small');
    }

    //echo $author['picture'];// Testing purposes
    $output = array();
    $_SESSION['author_picture'] = '<a href="'.BASE_PATH .'user/?user='.$author['user_name'] .'">'.
  '<img class="thumbnail" src="'.$author['picture_thumbnail'].'">'.substr($author['user_name'],0,5).'...</a>';
    }
  echo $_SESSION['author_picture'];
  }


function my_authored_posts(){
if(is_user_page()){
  $user = sanitize($_GET['user']);
  } else {
$user = $_SESSION['username'];
}
$get_post_type = trim(sanitize($_GET['post_type']));
$show_more_pager = pagerize();

if(empty($_SESSION['pager_limit'])){
$limit = $_SESSION['pager_limit'];
}else {$limit = 'LIMIT 0, 10';}

if($get_post_type == 'page' || $post_type == 'blog' || $post_type == 'notice' || 'contest'){
$post_type = 'page';
$table = 'page';
} else {
$post_type = 'page';
$table= 'page';
}
$query = query_db("SELECT `id`,`{$post_type}_name`, `page_type` FROM `{$table}` WHERE `author`='{$user}' ORDER BY `id` DESC {$limit}",
"Failed to Get my authored posts! ");

echo '<div class="padding-10">';
foreach($query['result'] as $result){
  echo "<a href='".BASE_PATH."page/?{$post_type}_name={$result['page_name']}&tid={$result['id']}'>".urldecode(str_ireplace('-',' ',$result['page_name'])) ."</a><hr>";
  }
  echo '</div>';
  echo $show_more_pager;
}



function say_something($post_type=''){
  if(is_logged_in()){
    //upload_image();
    if(isset($_GET['hashtag'])){
      $quicktag = '#'.$_GET['hashtag'];
      $category = '#'.trim(sanitize($_GET['hashtag']));
    }
    if(isset($_GET['tid'])){
      $parent_id = sanitize($_GET['tid']);
    }

    process_post_submission();
    //$page_name= trim(sanitize($_GET['page_name']));
    if(is_company_page()){
      $page_type = 'company post';
      $page_name = trim(sanitize($_GET['company_name']));
      $parent_id = sanitize($_GET['tid']);
    } else {
      $page_type = 'discussion';
    }
    if(is_home_page()){
      $form = '
      <div class="modal fade" id="talkModal" tabindex="-1" role="dialog"
        aria-labelledby="talkModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close"
                data-dismiss="modal" aria-hidden="true">
                &times;
              </button>
              <h4 class="modal-title" id="talkModalLabel">
                Say something
            </div>
            <div class="modal-body">
              <form class=" padding-10" action="'.$_SERVER['current_url'].'" method="post" enctype="multipart/form-data">
              <input type="hidden" name="action" value="insert">
              <input type="hidden" name="parent_id" value="'.$parent_id.'">
              <input type="hidden" name="category" value="'.$category.'">

              <input type="hidden" name="ref_url" value="'.$_SESSION['current_url'].'">
              <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
              <!-- Name of input element determines name in $_FILES array -->
              <b>Picture : </b><br>
              <input id="image_field" type="file" size="500" name="media_field" value="" placeholder="choose picture">
              <input id="image_title" type="text" size="500" name="image_title" value="" placeholder="Title / Caption">
              <input type="hidden" name="page_type" value="'.$page_type.'">

              <textarea name="content" id="content-area" placeholder="Talk about this">'.$quicktag.'</textarea>
              <hr><b>Is this a training course?:</b> <input type="checkbox" name="is_training_course" value="yes"><em> tick for yes, leave empty for no</em>
              <br><span id="enrollment_fee_input" class="input-group">
              <span class="input-group-addon">'.$_SESSION['preferred_currency'].'</span>
              <input type="number" name="enrollment_fee" class="form-control" placeholder="enrollment fee eg 100">
              <span class="input-group-addon">.00</span>
              </span><br>
              <input type="submit" name="add_new_post" class="btn btn-primary btn-lg" value="Say it">
              </form>';

            echo $form .= '
            </div>

          </div><!-- /.modal-content -->
        </div><!-- /.modal -->
      </div>';
      echo $form;
    } else {
      $form = '<form class="whitesmoke padding-10" action="'.$_SERVER['current_url'].'" method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="insert">
      <input type="hidden" name="parent_id" value="'.$parent_id.'">
      <input type="hidden" name="category" value="'.$category.'">

      <input type="hidden" name="ref_url" value="'.$_SESSION['current_url'].'">
      <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
      <!-- Name of input element determines name in $_FILES array -->
      <input id="image_field" type="file" size="500" name="media_field" value="" placeholder="choose picture">
      <input id="image_title" type="text" size="500" name="image_title" value="" placeholder="Title / Caption">
      <input type="hidden" name="page_type" value="'.$page_type.'">

      <textarea name="content" id="content-area" placeholder="Talk about this">'.$quicktag.'</textarea>
      <hr><b>Is this a training course?:</b> <input type="checkbox" name="is_training_course" value="yes"><em> tick for yes, leave empty for no</em>
      <br><span id="enrollment_fee_input" class="input-group">
      <span class="input-group-addon">'.$_SESSION['preferred_currency'].'</span>
      <input type="number" name="enrollment_fee" class="form-control" placeholder="enrollment fee eg 100">
      <span class="input-group-addon">.00</span>
      </span><br>
      <input type="submit" name="add_new_post" class="btn btn-primary" value="Say it">
      </form>';

      if(!is_company_page()){
       $form .= '  Quick #channels:
        <span class="padding-5"><a href="'.ADDONS_PATH.'hashtags/?hashtag=complaints">#complaints</a> | </span>
        <span class="padding-5"><a href="'.ADDONS_PATH.'hashtags/?hashtag=suggestion">#suggestion</a> | </span>
        <span class="padding-5"><a href="'.ADDONS_PATH.'hashtags/?hashtag=FiM">#FiM</a> | </span>
        ';
      }
      echo $form;
      add_ckeditor();
    }



  } else {log_in_to_continue();}
}

function get_notices(){
  $this_month = date('m');
  $today = date('d');

  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`, `page_name`, `created` FROM page WHERE section_name='notices' AND visible=1 ORDER BY id DESC LIMIT 0,1") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  while($result = mysqli_fetch_array($query)){
    $post_date_month = substr( $result['created'],5,2);
    $post_date_day = substr( $result['created'],8,2);

    //echo $this_month .'<br>';
    //echo $today .'<br>';
    //echo $post_date_month .'<br>';
    //echo $post_date_day .'<br>';


    if(($this_month > $post_date_month) || ($this_month == $post_date_month)){
    $diff = $today - $post_date_day;
      if($diff < 5){
      $info = '! Notice : <a href="'.BASE_PATH.'?page_name='.$result['page_name'].'">'.str_ireplace('-',' ',$result['page_name']).'</a>';
      echo status_message('alert',$info);
      }

    }
  }
}

function get_discussion_content($max_num=''){
    //if(is_logged_in()){
    $show_more_pager = pagerize($start='',$show_more='10');
    if(isset($_SESSION['pager_limit'])){
    $limit = $_SESSION['pager_limit'];
    } else {
      if(!empty($max_num)){
        $limit = "limit {$max_num}";
      } else {
        $limit = 'limit 10';
      }
    }
    if(is_company_page()){
      $parent_id = sanitize($_GET['tid']);
      }
    $category = trim(sanitize($_GET['section_name']));
    
    
    if($category){
    $query = query_db("SELECT * FROM `page` WHERE WHERE page_type='discussion' AND `category`='{$category}' AND `page_name`!='home' and visible='1' and show_in_streams !='no' ORDER BY `id` DESC {$limit}",
    "Failed to get category content ");
    } else if(url_contains('/user') ){
    $query = query_db("SELECT * FROM `page` where show_in_streams !='no' and visible='1' group by author ORDER BY `id` DESC limit 0, 3",
    "Failed to get discussions ");
    } else if(url_contains('page_name=talk') || is_home_page()){
    $query = query_db("SELECT * FROM `page` where show_in_streams !='no' and visible='1' group by author ORDER BY id DESC limit 0, {$max_num}",
    "Failed to get discussions ");
    } else if(is_company_page() ){
    $query = query_db("SELECT * FROM `page` WHERE page_type='company post' and parent_id='{$parent_id}' and visible='1' and show_in_streams !='no' ORDER BY `id` DESC {$limit}",
    "Failed to get company posts ");
    } else {
    $query = query_db("SELECT * FROM `page` where show_in_streams !='no' and visible='1' group by page_name ORDER BY `id` DESC {$limit}",
    "Failed to get discussions ");
    }
    echo '<section class=""><table class="table"><tbody>';

     # GET PAGE IMAGES
      $is_mobile = check_user_agent('mobile');
      if($is_mobile){
      $size='medium';
      } else {
      $size='large';
      }

    foreach($query['result'] as $result){
        if($result['page_type'] == 'company post'){
          $company = get_company_details($result['parent_id']);
          $user = array();
          $user['thumbnail'] = '<a href="'.$BASE_PATH.'addons/company?company_name='.$company['company_name'].'&action=show&tid='.$company['id'].'"><img class="circle-pic" width="35px" height="35px" src="'.$company['logo'].'?nocache='.time().'"></a>';
        } else {
          $user = show_user_pic($user=$result['author'] ,'circle-pic ',35);
        }
        $read_more_link = "<br><a href='" .BASE_PATH ."?page_name=" .str_ireplace('#','',$result['page_name']) ."&tid=".$result['id']."'>".'read more &raquo</a>';
        $page = $result['page_name'];
        $pics = get_linked_image($parent=$result['page_type'],$parent_id = $result['id'],$pic_size='half',$limit='4','');
        if(empty($pics)){
          $pics = get_linked_image($parent=$result['page_name']." page",$parent_id=$result['id'],$pic_size=$size,$limit='4',$has_zoom='');
        }

        $output=  "<tr><td class=''>{$user['thumbnail']}</td><td class=''>" ;

        $output .= "<div class='last-updated pull-right'> <time class='timeago' datetime='".$result['last_updated'] ."'>".$result['last_updated'] ."</time></div>";
        $output .= "<div class='row'>";
        if(!empty($result['content'])){
          if($result['page_type'] != 'company'){
          $content = str_ireplace('{show_images_in_lists}','',substr(urldecode($result['content']),0,350));
          } else {
          $content = str_ireplace('{show_images_in_lists}','',urldecode($result['content']));
            }
        $content2 = parse_text_for_output($content);
        if(string_contains($content2,'youtube.com/embed')
        || string_contains($content2,'youtu.be/')
        || string_contains($content2,'youtube.com/watch?')
        || string_contains($content2,'vimeo.com/')
        ){
          $content2 .= " <br><span class='margin-3 badge padding-10'>video</span>";
          }
        } else {
          $content = '...';
          $content2 ='...';
          }
        //~ $output2 = "<a href='" .BASE_PATH ."?page_name=" .$result['page_name'] ."&tid=".$result['id']."'>";
        $output2 = "<div>";
        $comments_num = get_num_comments($result['id']);
        $reactions = get_num_page_reactions($result['id'],'like');

    $output2 .= '<em class="tiny-text">('.$comments_num .' comments)</em>'.' <em class="tiny-text">('.$reactions.' likes)</em></div> '.
     "<a href='" .BASE_PATH ."?page_name=" .str_ireplace('#','',$result['page_name']) ."&tid=".$result['id']."#comments'>".
     "<span class='tiny-text pull-right margin-3'>add a comment</span>";


    if(!empty($content2)){
      echo  $output ;
      if(!empty($pics)){
        echo "<a href='" .BASE_PATH ."?page_name=" .str_ireplace('#','',$result['page_name']) ."&tid=".$result['id']."'>";
        foreach($pics as $pic){
        echo $pic;
        }
      echo "</a><br>";

      }
      echo '<div class="col-md-12 col-xs-12"><h4>'.clean_title($result['page_name']).'</h4>'.$content2  .$read_more_link.'<br>'. $output2 ;
    }
    echo "</div> </div></td></tr>" ;


  }echo '</tbody></table></section>';
  if(!is_home_page()){
    echo $show_more_pager;
  }
}


function get_page_id($page_name){
  if(!empty($page_name)){
    $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id from page WHERE page_name='{$page_name}' ORDER BY id DESC LIMIT 0, 1")
    or die("Cannot get page id".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

    $result = mysqli_fetch_array($query);
    return $result['id'];
    }

  }


function process_post_submission(){

# Add pages form processing
// add new page or discussion
  if(isset($_POST['add_new_post'])){
    // Process all post details and save
    if(isset($_POST['page_name'])){
      $page_name = trim(sanitize($_POST['page_name']));
      $page_name = str_ireplace('%26lt%3Bp%26gt%3B','',$page_name);
      }
    if(isset($_POST['page_type'])){
      $page_type = trim(sanitize($_POST['page_type']));
      }
    if(isset($_POST['category'])){
      $category = trim(sanitize($_POST['category']));
      }
    if(isset($_POST['child_page_number'])){
      $child_page_number = trim(sanitize($_POST['child_page_number']));
      } else { $child_page_number = '5';}
    if(isset($_POST['show_in_streams'])){
      $show_in_streams = trim(sanitize($_POST['show_in_streams']));
      } else { $show_in_streams = 'yes';}
    if(isset($_POST['enrollment_fee'])){
      $enrollment_fee = sanitize($_POST['enrollment_fee']);
      } else { $enrollment_fee = '0';}

    if(isset($_POST['content'])){
      $content = trim(sanitize($_POST['content']));
      if(empty($page_name)){
      //First check for presence of image title
      if(!empty($_POST['image_title'])){
      $page_name = urlencode(str_ireplace(' ','-',trim(sanitize($_POST['image_title']))));
      }else {
        if(strlen($content) > 30){
          $show_dots = '...';
          }
        $page_name = urlencode(str_ireplace(' ','-',substr($content,0,50))).$show_dots;
        }
      }

      // account for parent_ids for company posts and child pages
      if(isset($_POST['tid'])){
        $parent_id = sanitize($_POST['tid']);
        }
      if(isset($_GET['tid'])){
        $parent_id = sanitize($_GET['tid']);
        } else {
          $parent_id ='0';
          }
      if(isset($_POST['show_author'])){
        $show_author = sanitize($_POST['show_author']);
        } else{ $show_author = 'yes';}

      // other neccessary variables for insert
      $author = $_SESSION['username'];
      $editor = $_SESSION['username'];
      $now = date('c');


      // Insert to db
      $q = query_db("INSERT INTO `page`(`id`, `page_name`, `parent_id`, `child_page_number`, `page_type`, `visible`,`content`, `created`, `last_updated`, `author`, `editor`, `allow_comments`,`path`,`show_author`,`show_in_streams`,`enrollment_fee`)
      VALUES ('0','{$page_name}','{$parent_id}','{$child_page_number}','{$page_type}','1','{$content}','{$now}','{$now}','{$author}','{$editor}','yes','{$path}','{$show_author}','{$show_in_streams}','{$enrollment_fee}')",
      "Error saving post ");

      if($q){
        // Check for hashtags in post and process them
        $_SESSION['last_post_insert_id'] = mysqli_insert_id($GLOBALS['___mysqli_ston']);
        $last_insert_id = $_SESSION['last_post_insert_id'];
        $value = query_db("SELECT id,page_name,content FROM page WHERE id='{$last_insert_id}'",
        "Could not refetch content in process post submission ");
        $_SESSION['page_id'] = $value['result'][0]['id'];
        $_SESSION['page_name'] = $value['result'][0]['page_name'];
        $content = $value['result'][0]['content'];
        $dest_url = BASE_PATH.'?page_name='.str_ireplace('#','',$page_name).'&tid='.$_SESSION['last_post_insert_id'];
        if(addon_is_active('hashtags')){
        process_hashtags($string=$content, $path='');
        unset($_SESSION['last_post_insert_id']);
        increment_user_activity_count_in('posts_created');
        }
      }
    }

    if(isset($_FILES['media_field'])){
      upload_image();
      }

    // Record actvity

     activity_record(
          $parent_id = $_SESSION['last_post_insert_id'],
          $actor=$author,
          $action=" created the {$page_type}",
          $subject_name = $page_name,
          $actor_path = BASE_PATH.'user/?user='.$author,
          $subject_path=  BASE_PATH.'?page_name=' .$page_name.'&tid='.$_SESSION['last_post_insert_id'],
          $date=$now,
          $parent= $page_type
          );

    //redirect when finished
    unset($_POST);
    if(isset($ref_url)){
      redirect_to($ref_url);
    } else { redirect_to($_SESSION['current_url']); }
  }
}

function process_post_update(){
  //~ print_r($_POST); die();
// edit page

  //update db
  if(isset($_POST['updated'])){
    if(isset($_GET['tid'])){
    $id =  sanitize($_GET['tid']);
    }
  if(isset($_POST['id'])){
    $id =  sanitize($_POST['id']);
    }
  if(isset($_POST['parent_id'])){
    $parent_id =  sanitize($_POST['parent_id']);
    }
  if(isset($_POST['visible'])){
    $visible =  sanitize($_POST['visible']);
    }

  if(empty($visible)){
    $visible = '0';
    }
  if(isset($_POST['content'])){
    $content =  trim(sanitize($_POST['content']));
    }
  if(isset($_POST['page_name'])){
    $page_name = trim(sanitize(str_ireplace(' ','-',$_POST['page_name'])));
    if(isset($_POST['page_name'])){
      //~ $page_name = trim(sanitize($_POST['page_name']));
      $page_name = str_ireplace('%26lt%3Bp%26gt%3B','',$page_name);

      }
    }
  if(isset($_POST['child_page_number'])){
    $child_page_number = trim(str_ireplace(' ','-',sanitize($_POST['child_page_number'])));
    }
  if(isset($_POST['page_type'])){
    $page_type = trim(sanitize($_POST['page_type']));
    }
  if(isset($_POST['ref_url'])){
    $dest_url = trim(sanitize($_POST['ref_url']));
    } else {
      $dest_url = trim(sanitize($_SESSION['prev_url']));
      }
  if(isset($_POST['show_author'])){
        $show_author = sanitize($_POST['show_author']);
        } else {$show_author = 'no';}
  if(isset($_POST['allow_comments'])){
      $allow_comments = trim(sanitize($_POST['allow_comments']));
      } else { $allow_comments = 'no';}

  if(isset($_POST['show_in_streams'])){
      $show_in_streams = trim(sanitize($_POST['show_in_streams']));
      } else { $show_in_streams = 'no';}


  $editor = $_SESSION['username'];
  $now = date('c');


    $q = query_db(
    "UPDATE `page` SET
    `page_name`='{$page_name}',
    `parent_id`='{$parent_id}',
    `child_page_number`='{$child_page_number}',
    `page_type`='{$page_type}',`visible`='{$visible}',
    `content`='{$content}',
    `last_updated`='{$now}',
    `editor`='{$editor}',
    `allow_comments`='{$allow_comments}',
    `path`='{$path}',
    `show_author`='{$show_author}',
    `show_in_streams`='{$show_in_streams}' WHERE id='{$id}'",
    'Error updating post ');
    }
    if($q){
      session_message('alert','Post updated!');

      //Process hashtags
      if(addon_is_active('hashtags')){
        process_hashtags($string=$content, $path='');
        }

      // Record activity

      activity_record(
          $parent_id = $id,
          $actor=$author,
          $action=" created the {$page_type}",
          $subject_name = $page_name,
          $actor_path = BASE_PATH.'user/?user='.$author,
          $subject_path=  BASE_PATH.'?page_name=' .$page_name.'&tid='.$id,
          $date=$now,
          $parent= $page_type
          );
       // Redirect
       unset($_POST);
      redirect_to($dest_url);
      }
  }

function publish_post(){
  if(is_author() || is_admin()){
    if(isset($_POST['publish_post'])){
      $page_id = $_SESSION['page_id'];
      $visible = sanitize($_POST['show']);
      $q = query_db("UPDATE page SET visible='{$visible}' WHERE id='{$page_id}'",
      "Could not publish post ");
      if($q){
        redirect_to($_SESSION['current_url']);
      }
    }

    if($_SESSION['page_visible'] == '1'){
      echo '<form method="post" action="'.$_SESSION['current_url'].'">
      <input type="hidden" name="show" value="0">
      <input type="submit" name="publish_post" value="UnPublish" class="tiny-text btn btn-default btn-xs">
      </form>';
    }
  }
}

function unpublish_post(){
  if(is_author() || is_admin()){
    if(isset($_POST['unpublish_post'])){
      $page_id = $_SESSION['page_id'];
      $visible = sanitize($_POST['hide']);
      $q = query_db("UPDATE page SET visible='{$visible}' WHERE id='{$page_id}'",
      "Could not publish post ");
      if($q){
        redirect_to($_SESSION['current_url']);
      }
    }

    if($_SESSION['page_visible'] == '0'){
      echo '<form method="post" action="'.$_SESSION['current_url'].'">
      <input type="hidden" name="show" value="1">
      <input type="submit" name="publish_post" value="Publish" class="tiny-text btn btn-default btn-xs">
      </form>';
    }
  }
}

function clean_title($title){
  $output = str_ireplace('#','',$title);

  $output = urldecode($output);
  $output = parse_text_for_output($title);
  $output = str_ireplace('-',' ',$output);
  $output = ucfirst($output);
  //~ echo $output;
  return $output;
}

function delete_post(){
  if(($_POST['action'] == 'delete post' || $_GET['action'] == 'delete_post') && (is_admin() || is_author())){
    if(isset($_GET['tid'])){
      $id= sanitize($_GET['tid']);
      } else if(isset($_POST['id'])){
      $id= sanitize($_POST['id']);
      }
    $q = mysqli_query($GLOBALS['___mysqli_ston'],"DELETE from page where id='{$id}' LIMIT 1")
    or die('Could not delete post '.mysqli_error($GLOBALS['___mysqli_ston']));

     if($q){
      if(isset($_GET['dest_url'])){
        $dest_url = $_GET['dest_url'];
        }
      session_message('alert', 'Post deleted !');
      redirect_to($dest_url);
      }
    }

  }

function show_page_reaction_statistics($page_id,$reaction=''){
  if(empty($page_id) && url_contains('/page/?show-page-reactions=')){
    $page_id = $_SESSION['page_id'];
  }
  //~ Get num comments
  $comment_count = get_num_comments($page_id);
  $output = '';
  $value = query_db("SELECT author FROM page WHERE id='{$page_id}'",
  "Could not get page author in show page reaction statistics ");
  $_SESSION['page_author'] = $value['result'][0]['author'];

  $value = query_db("SELECT count(id) as count FROM page_reactions WHERE page_id='{$page_id}' and reason like'%{$reaction}%'",
  "Could not get reaction statistics 1 for page ");
    if(!empty($comment_count)){
      $output .= '('.$comment_count .') comments,<br> ';
    }

    $value = query_db("SELECT count(id) as count FROM page_reactions WHERE page_id='{$page_id}' AND reason='like'",
    "Could not get reaction statistics 2 for page ");
    $output .= '('.$value['result'][0]['count'] .') likes,<br> ';

    $value = query_db("SELECT count(id) as count FROM page_reactions WHERE page_id='{$page_id}' AND reason='love'",
    "Could not get reaction statistics 3 for page ");
    $output .= '('.$value['result'][0]['count'] .') loves,<br> ';

    $value = query_db("SELECT count(id) as count FROM page_reactions WHERE page_id='{$page_id}' AND reason='touched'",
    "Could not get reaction statistics 4 for page ");
    $output .= '('.$value['result'][0]['count'] .') touched,<br> ';

    $value = query_db("SELECT count(id) as count FROM page_reactions WHERE page_id='{$page_id}' AND reason='enrolled'",
    "Could not get reaction statistics 5 for page ");
    $output .= '('.$value['result'][0]['count'] .') enrolled.';

    echo $output;
  }

function show_page_reactions($page_id=''){

  if(isset($_GET['show-page-reactions'])){

    if($_GET['num'] == 'all' || empty($_GET['num'])){
      $limit_num = '';
    } else {
      $limit_num = ' LIMIT 0, '.sanitize($_GET['num']);
    }
    $page_id = sanitize($_GET['show-page-reactions']);
    echo '<b>Page reactions for '; link_to_page_title_by_id($page_id); echo '</b>';
    //~ echo 'sessionpage id is '.$_SESSION['page_id'];

    if(is_logged_in() && $page_id == $_SESSION['page_id']){
      echo '<form method="post" action="'.$_SESSION['current_url'].'">
      <select name="reaction" onchange="this.form.submit()">
        <option>-See who reacted-</option>
        <option>like</option>
        <option>love</option>
        <option>touched</option>
        <option>enroll</option>
        </select>
      </form>';


      if(isset($_POST['reaction'])){
        $author = $_SESSION['page_author'];
        $author_id = get_user_details($author);
        $owner_id = $author_id['id'];
        $reaction = sanitize($_POST['reaction']);

        if($reaction == 'comment'){
          $reaction_past = 'commented on';
        } else if($reaction == 'like'){
          $reaction_past = 'liked';
        } else if($reaction == 'love'){
          $reaction_past = 'loved';
        } else if($reaction == 'touched'){
          $reaction_past = 'were touched by';
        } else if($reaction == 'enroll'){
          $reaction_past = 'enrolled for';
        }
        echo "<h3> People who {$reaction_past}  ";
          link_to_page_title_by_id($page_id);
        echo "</h3>";

        $value = query_db("SELECT id,owner_id,reactor_id,amount,date FROM page_reactions WHERE page_id='{$page_id}' AND owner_id='{$owner_id}' AND reason='{$reaction}' order by id desc {$limit_num}",
        "Could not get page reactions in show page reactions!");

        if($value['num_results'] > 0){
          echo '<ol>';

          foreach($value['result'] as $result){
            if($owner_id == $result['owner_id']){
              $user = get_user_by_id($result['reactor_id']);
              //~ link_to_user_by_id($result['reactor_id']);
              $user_pic = show_user_pic($user,'img-circular','35px');
              if($reaction == 'touched'){
                $reaction_past = 'was touched by';
              }
              echo '<li>' .$user_pic['thumbnail'] .$reaction_past .' on '.$result['date'].' -  it, <span class="label label-success">'.$_SESSION['preferred_currency'].' '.convert_to_user_currency($result['amount']).'</span><hr></li>';
            }
          } echo '</ol>';
        } else {
          echo "- No {$reaction} actions to display -";
        }
      }
      if(!isset($_POST['reaction']) && user_has_reacted($like)){
      show_page_reaction_statistics($page_id,'');
      }
    } else{
      status_message('alert','You do not have access to view reactions for this page!');
      }
  }
  else {
    if(is_home_page() && is_logged_in()){
      $owner_id = $_SESSION['user_id'];
      //~ Get most recent page reactions for logged in user
      $value = query_db("SELECT * FROM page_reactions WHERE owner_id='{$owner_id}' order by id desc LIMIT 4",
      "Could not get home page reactions in show page reactions!");

      //~ else {
        //~ Get most recent page reactions for this page
        //~ $value = query_db("SELECT * FROM page_reactions WHERE page_id='{$page_id}' AND owner_id='{$owner_id}' order by id desc LIMIT 4",
        //~ "Could not get home page reactions in show page reactions!");
      //~ }

    } else { // not homepage
      $page_id = $_SESSION['page_id'];
      $value = query_db("SELECT * FROM page_reactions WHERE page_id='{$page_id}' order by id desc LIMIT 4",
    "Could not get any page reactions in show page reactions!");
    }

    if($value['num_results'] > 0){
      if(is_home_page()){
        echo '<h4>New reactions to your posts</h4>';
      } else {
        echo '<h4>New reactions to this post</h4>';
      }
      echo '<ol class="tiny-text">';
      foreach($value['result'] as $result){
        $user = get_user_by_id($result['reactor_id']);
        //~ link_to_user_by_id($result['reactor_id']);
        $user_pic = show_user_pic($user,'img-circular','35px');

        if($result['reason'] == 'like'){
          $reaction_past = ' liked ';
          echo '<li>' ;link_to_user_by_id($result['reactor_id']); echo $reaction_past; link_to_page_by_id($result['page_id']); echo ' <span class="label label-success">'.$_SESSION['preferred_currency'].' '.convert_to_user_currency($result['amount']).'</span> on '.$result['date'].' <hr></li>';

        //~ } else
        //~ if($result['reason'] == 'love'){
          //~ $reaction_past = ' loved ';
          //~ $output .= '<li>' ;link_to_user_by_id($result['reactor_id']); echo $reaction_past; link_to_page_by_id($result['page_id']); echo ' <span class="label label-success">'.$_SESSION['preferred_currency'].' '.convert_to_user_currency($result['amount']).'</span> on '.$result['date'].'<hr></li>';

        //~ } else
        //~ if($result['reason'] == 'enroll'){
          //~ $reaction_past = ' enrolled in ';
          //~ echo '<li>' ;link_to_user_by_id($result['reactor_id']); echo $reaction_past; link_to_page_by_id($result['page_id']); echo ' <span class="label label-success">'.$_SESSION['preferred_currency'].' '.convert_to_user_currency($result['amount']).'</span> on '.$result['date'].'<hr></li>';

        //~ } else
        //~ if($result['reason'] == 'touched'){
          //~ $reaction_past = ' was touched by ';
          //~ echo '<li>' ;link_to_user_by_id($result['reactor_id']); echo $reaction_past; link_to_page_by_id($result['page_id']); echo ' <span class="label label-success">'.$_SESSION['preferred_currency'].' '.convert_to_user_currency($result['amount']).'</span> on '.$result['date'].'<hr></li>';

        } else {
          echo '<li>' ;link_to_user_by_id($result['reactor_id']); echo ' commented on ';
          if($_SESSION['page_id'] == $result['page_id']){
            echo 'it';
          } else {
            link_to_page_by_id($result['page_id']);
          }
          if(!empty($result['amount'])){
            echo ', you received <span class="label label-success">'.$_SESSION['preferred_currency'].' '.convert_to_user_currency($result['amount']).'</span> on '.$result['date'].'<hr></li>';
          }
        }

      } echo '</ol>';
    }
  }
  if(isset($_GET['page_name']) && !is_home_page()){
  if(is_author() || is_admin()){
    echo '
    <span class="tiny-text pull-right padding-10">
      <a href="'.BASE_PATH.'page?show-page-reactions='.$_SESSION['page_id'].'&num=all">view all page reactions</a>
    </span><br>';
  }
  //~ echo '';
  }
}


function user_has_reacted($reaction){
  if(empty($reaction)){
    $reaction = 'like';
  }
  $user_id = $_SESSION['user_id'];
  $page_id = $_SESSION['page_id'];
  $q = query_db("SELECT id FROM page_reactions WHERE page_id='{$page_id}' AND reactor_id='{$user_id}' AND reason='{$reaction}'",
  "Could not check if user has reacted!");

  if(!empty($q['result'][0]['id'])){
    return true;
  } else {
    return false;
  }
}

function user_has_commented($page_id,$user_id){
  //~ $user_id = $_SESSION['user_id'];
  $page_id = $_SESSION['page_id'];
  $q = query_db("SELECT id FROM page_reactions WHERE page_id='{$page_id}' AND reactor_id='{$user_id}' AND reason LIKE '%commented%' AND amount='5'",
  "Could not check if user has reacted!");

  if(!empty($q['result'][0]['id'])){
    return true;
  } else {
    return false;
  }
}

function react_to_page(){

  if($_SESSION['page_is_a_course']){
    echo '<em class="tiny-text">This is a training program / course.<br>
    Enrolling will give you access to all child pages in this course.</em>';
  }

  if(isset($_POST['reaction'])){
    $reaction = sanitize($_POST['reaction']);
    if($reaction == 'like' ){
      $reaction_past = 'liked';
      if(author_account_type_is('Free')){
      $amount = 2;
      } else {
        $amount = 10;
      }
    }
    //~ else if($reaction == 'love'){
       //~ if(author_account_type_is('Free')){
      //~ $amount = 5;
      //~ } else {
        //~ $amount = 50;
      //~ }
      //~ $reaction_past = 'loved';
    //~ } else if($reaction == 'touched'){
       //~ if(author_account_type_is('Free')){
      //~ $amount = 10;
      //~ } else {
        //~ $amount = 100;
      //~ }
      //~ $reaction_past = 'was touched by';

    if($reaction == 'enroll' && $_SESSION['user_funds_amount'] >= $amount){
      $amount = $_SESSION['page_enroll_amount'];
      $reaction_past = 'enrolled for';
    }

    $post = $_SESSION['page_name'];

  $reactor = $_SESSION['username'];
  $owner = $_SESSION['page_author'];

    record_post_earning($amount,$reaction);

  $reason = "You {$reaction_past} this post ".parse_text_for_output($_SESSION['current_url']);
  if($reaction == 'enroll' && $_SESSION['user_funds_amount'] >= $amount){
    update_user_funds($reactor,-$amount,$reason);
  }
  $reason = "{$reactor} {$reaction_past} your post ".parse_text_for_output($_SESSION['current_url']);
  update_user_funds($owner,$amount,$reason);
  }


  //~ Show reaction buttons
  if(!is_logged_in()
  //~ || !subscription_is_active()
  ){
    $btn_state = 'disabled="disabled"';
  } else {
    $btn_state = '';
  }

  echo '<form method="post" action="'.$_SESSION['current_url'].'">
  <div class="btn-group">';
    //~ if($_SESSION['site_funds_amount'] > 10){
      echo '<button type="submit" class="btn btn-primary btn-xs padding-5" title="I like this" name="reaction" value="like" onclick="this.form.submit()" '.$btn_state.'><i class="glyphicon glyphicon-thumbs-up"></i> Like</button>';
    //~ }
    //~ if($_SESSION['site_funds_amount'] > 50){
      //~ echo '<button type="submit" class="btn btn-danger btn-xs padding-5" title="I so Love this" name="reaction" value="love" onclick="this.form.submit()" '.$btn_state.'><i class="glyphicon glyphicon-heart"></i> Love</button>';
    //~ }
    //~ if($_SESSION['site_funds_amount'] > 100){
      //~ echo '<button type="submit" class="btn btn-primary btn-xs padding-5" title="OMG you touched my life!" name="reaction" value="touched" onclick="this.form.submit()" '.$btn_state.'><i class="glyphicon glyphicon-hand-up"></i> I\'m touched</button>';
    //~ }
    //~ if($_SESSION['page_is_a_course'] && !empty($_SESSION['page_enrollment_fee'])){
      //~ if(is_enrolled_to_page_course()){
        //~ $btn_state = 'disabled=disabled';
        //~ $disabled_message = '<br><div class="tiny-text col-xs-12 red-text">You are enrolled!</div>';
        //~ } else {
          //~ $disabled_message = '';
          //~ }
      //~ if($_SESSION['site_funds_amount'] > $_SESSION['page_enrollment_fee'] && !author_account_type_is('Free')){
        //~ echo '<button type="submit" class="btn btn-success btn-xs padding-5" title="I want to enroll for this course" name="reaction" value="enroll" onclick="this.form.submit()" '.$btn_state.'>
          //~ <i class="glyphicon glyphicon-education"></i>
          //~ Course fee '.$_SESSION['preferred_currency'].' '.convert_to_user_currency($_SESSION['page_enrollment_fee']).'
          //~ </button>';
      //~ }
        echo $disabled_message;
    //~ }
    if(!empty($btn_state) && !is_logged_in()){
      echo '<br><span class="tiny-text red-text col-xs-12">Buttons disabled - Log in to react</span>';
      } else
    if(!empty($btn_state) && $_SESSION['site_funds_amount'] < 5){
      echo '<br><span class="tiny-text red-text">Buttons disabled - You are too broke to react!</span>';
      }
  echo '</div>
  </form>';

      //~ echo' <div class="padding-20 tiny-text">NOTE**
  //~ <span class="tiny-text inline-block">Like - gives '.$_SESSION['preferred_currency'].' '.convert_to_user_currency(10).',</span>
  //~ <span class="tiny-text inline-block">Love - gives '.$_SESSION['preferred_currency'].' '.convert_to_user_currency(50).'</span>
  //~ <span class="tiny-text inline-block">I\'m touched - gives '.$_SESSION['preferred_currency'].' '.convert_to_user_currency(100).'</span>
  //~ <span class="tiny-text inline-block">Enroll me cost - depends on author settings. </span>
  //~ </div>';

}

function is_a_training_page($page_id){
  $q = query_db("select id FROM page WHERE id='{$page_id}' and enrollment_fee!='0'",
  "Could not check if page is a training page ");
  if(!empty($q['num_resultss'])){
    return true;
  } else {
    return false;
  }
}

function has_enrolled_members($page_id){
  $q = query_db("select id FROM page_reactions WHERE page_id='{$page_id}' and reason='enroll'",
  "Could not check if page has enrolled members ");
  if(!empty($q['num_results'])){
    return true;
  } else {
    return false;
  }
}

function is_enrolled_to_page_course(){
  $user_id = $_SESSION['user_id'];
  $page_id = $_SESSION['page_id'];
  $value = query_db("SELECT id FROM page_reactions WHERE page_id='{$page_id}' and reactor_id='{$user_id}' and reason='enroll'",
  "Could not check if enrolled to page course ");
  if(!empty($value['num_results'])){
    return true;
    } else {
      return false;
      }
  }


function show_dashboard(){
  if($_GET['page_name'] == 'home'){
  // Show notifications
  echo '<div class="row whitesmoke margin-3">';
  echo '<div class="col-md-12 col-xs-12 white">';
     echo '<h3>Dashboard</h3>';
    if(addon_is_active('project_manager')){
    //~ Show unpaid task earnings
     echo '<span class="col-md-6 padding-10 col-xs-12 green-text">
      <span class="tiny-text"><span class="red-text">Unpaid</span> Task Earnings this month </span>
        <br><b>'.$_SESSION['preferred_currency'].' '.convert_to_user_currency(get_total_unpaid_task_earnings());
      echo '</b>
        <span class="tiny-text pull-right">
        <a href="'.ADDONS_PATH.'project_manager?action=show-unpaid-earnings-history">show unpaid history</a>
        </span>
      </span>';
    }

    //~ if(addon_is_active('page')){
    //~ Show total page earnings
     echo '<span class="col-md-6 padding-10 col-xs-12 green-text ">
      <span class="tiny-text ">Page Earnings this month (Paid)</span>
        <br><b>'.$_SESSION['preferred_currency'].' '.convert_to_user_currency(get_total_earnings_from_all_posts());
      echo '</b>
        <span class="tiny-text pull-right">
        <a href="'.BASE_PATH.'funds_manager/transaction_history.php?user='.$_SESSION['username'].'">transaction history</a>
        </span></span>
      </div></div>';
    //~ }


     if(addon_is_active('notifications')){
      show_notifications();
    }


    show_new_page_content();

    //$post_info = query_db("","");

    //link to contests (show num active contests)
    show_active_contests();
    //$post_info = query_db("","");

    //~ //link to fundraisers (show num active fundraisers + num new in last 7 days)
    show_active_fundraisers();


    //link to projects(show projects summary - num active projects, num completed, num active and pending tasks)

    //link to companies (show num companies and num available jobs)

    if(addon_is_active('project_manager')){
      space_down();
      $num_projects = get_total_num_projects();
      echo '
      <div class="sweet_title">Projects</div>
        <div class="col-md-12 col-xs-12 well">
        <h4 class="padding-10">Earn money completing Tasks for these Projects</h4>
          <div class="col-md-5 inline-block whitesmoke padding-5 margin-3">
            <a href="'.ADDONS_PATH.'project_manager">Projects ('.$num_projects.')</a>
          </div>';
          if(addon_is_active('company')){
          $num_companies = get_total_num_companies();
          echo '
          <div class="col-md-5 inline-block whitesmoke padding-5 margin-3">
            <a href="'.ADDONS_PATH.'company">Companies ('.$num_companies.')</a>
          </div>';
          }

          $value = get_offices_hiring();
          $num_available_jobs = $value['num_results'];
          if(empty($num_available_jobs)){
            $num_available_jobs = 0;
            }

          echo '<div class="col-md-5 inline-block whitesmoke padding-5 margin-3">
            <a href="'.ADDONS_PATH.'project_manager/?action=show_offices_hiring">Offices hiring <span class="badge badge-danger">'.$num_available_jobs.'</span></a>
          </div>';

          echo '<div class="col-md-5 inline-block whitesmoke padding-5 margin-3">';
            show_num_available_tasks();
          echo '</div>';


          show_my_assigned_tasks();
          show_unattended_task_submissions();
          show_my_disapproved_tasks();
      echo '</div>';

    //~ echo '</div>';
    }

    //link to active draws (show amount in money pot)
    if(addon_is_active('draws')){
      space_down();
      $num_draws = get_total_num_draws();
      echo '<div class="sweet_title">Lucky Thursday Draws
          <a href="'.ADDONS_PATH.'draws">('.$num_draws.')</a>';
          $num_active_draws = get_num_active_draws();
          if(empty($num_active_draws)){
            $num_active_draws = 0;
            $added_class = '';
            } else {
              $added_class = 'red';
              }
        echo '</div>';
    echo '<div class="well"><span class="pull-right"><a href="'.ADDONS_PATH.'draws/">See All Draws</a></span><br>
    <mark>Every thursday</mark>, we gather online and pool our resources in one of several draws.
   By 8.pm that day, one participant is randomly selected from each of the draws and all the money in that pot
   is awarded to that person. <br>Thats why its a <mark>Lucky Thursday</mark> - to prepare you for a happy weekend.
  <hr><em>Note: We keep ten percent of all draw totals for administrative purposes.</em><hr>
    Active Draws <span class="badge '.$added_class.'">'.$num_active_draws.'</span></div>';
    }


    //link to people (show num new in last 7 days, num per role)
    space_down();

    show_new_user_statistics();
      show_revenue_dashboard();

  }
}


?>
