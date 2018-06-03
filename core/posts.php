<?php

function add_post() {
  say_something();
}


# LIST postS

function get_post_lists() {
  #print_r($_POST);


if(is_admin() && !query_string_in_url()){

  $show_more_postr = postrize();

  $limit = $_SESSION['postr_limit'];

  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `post` ORDER BY `id` DESC {$limit}")
  or die('Could not get data:' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  $count = mysqli_num_rows($query);

  $postlist = '<h2> posts List</h2>';
  $postlist = $postlist . "<table class='table posts-list'><thead><th>&nbsp post title</th><th>Actions</th></thead>";

  if($count < 1){status_message('alert', 'No more results here!');}

  while($row = mysqli_fetch_array($query)){
    $postlist = $postlist

  . '<tr><td class="spreadout">'
  .'<a href="' . BASE_PATH.'?post_name=' .str_ireplace('#','',$row['post_name']) .'&tid='.$row['id'].'"> '
  . ucfirst(urldecode(str_ireplace('-',' ',$row['post_name'])))
  . '</a>'
  .'</td><td class="tiny-text">&nbsp &nbsp<a href="'
  . BASE_PATH ."post/edit?"
  . 'action='
  . 'edit_post&'
  . 'post_name='
  . $row['post_name']
  . '&tid='.$row['id'].'" '
  . '>edit</a> |';

  if(!is_a_training_post($row['id']) || !has_enrolled_members($row_id)){
  $postlist .= '&nbsp <a href="'
  . BASE_PATH ."post/process.php?"
  . 'action='
  . 'delete_post&'
  . 'post_name='
  . $row['post_name'].'&tid='.$row['id']
  . '&dest_url='.BASE_PATH.'post" '
  . '> delete </a>';
  }
  $q = query_db("SELECT * FROM `core_post` WHERE status='published' ORDER BY `id` DESC LIMIT {$num}",
  "Could not get recent posts! ");
  if($q){
    return $q;
  }
}
}

}


function get_recent_posts($num=''){
  if(empty($num)){
    $num = '10';
  }
  $q = query_db("SELECT * FROM `core_post` WHERE status='published' ORDER BY `id` DESC LIMIT {$num}",
  "Could not get recent posts! ");
  if($q){
    $output = process_data_for_output($q);
    //~ print_r($output);
    return $output;
  }
}


function show_recent_posts($num='',$format='media'){
  //~ Format can be media or list
  $q = get_recent_posts($num);
  $_SESSION['temp'] = $q['result'];
  if($format == 'media'){
    load_view('post','show-recent-posts-media-format');
  } else {
    load_view('post','show-recent-posts-list-format');
  }
}

function show_recent_posts_on_homepage(){
  //~ Format can be media or list
  $q = get_recent_posts();
  $_SESSION['temp'] = $q['result'];
  load_view('post','show-recent-posts-home-page');

}

function show_post($post_name=''){
  $p_arr = explode(':',$post_name);
  $id = sanitize($p_arr[1]);
  $q = query_db("SELECT * FROM core_post WHERE id ='{$id}'",
  "Could not get post in show post! ");
  $_SESSION['post'] = $q['result'][0];
  $_SESSION['post']['post_name'] = parse_text_for_output($_SESSION['post']['post_name']);
  $_SESSION['post']['content'] = parse_text_for_output($_SESSION['post']['content']);
  load_view('post','show-post-page');
  //~ load_view('post','show-single-post-page');
}


function show_posts_tagged_with($tag){
  $tag =  sanitize($tag);
  echo '<h1 class="p-2 m-3"> Posts tagged with "'.$tag.'"</h1>';
  $q = query_db("SELECT * FROM core_post WHERE status='published' AND categories LIKE'%{$tag}%' ORDER BY id DESC",
  "Could not get posts tagged with {$tag}! ");
  if($q){
    $_SESSION['temp'] = $q['result'];
    load_view('post','show-recent-posts-media-format');
  }
}

function show_post_categories($categories){
  $categories = urldecode($categories);
  $cats_arr = explode(',',$categories);

  $count = 0;
  foreach($cats_arr as $category){
    if(!empty($category)){
      if($count == 0){
        echo '<b>Tags: </b>';
        $count++;
      }
      echo '<a href="'.BASE_PATH.'tags/'.urlencode($category).'"> '.$category.'</a>, ';
    }
  }
}

# EDIT postS

function edit_post($post_id){
$current_post = $_SESSION['post'];
  if(is_admin() || user_has_role('editor') || $_SESSION['username'] == $current_post['author']){
    if(isset($_POST['edit-post'])){
      $id = sanitize($_POST['id']);
      $post_name = str_ireplace(':','--',strtolower(sanitize($_POST['post-name'])));
      $content = sanitize($_POST['content']);
      $now = date('c');
      //~ $image = upload_file();
      $allowed_file_type = ['.jpg','.png','.jpeg'];
      $file_path = upload_file2('posts',$allowed_file_type);
      if(!empty($_FILES)){
        if(isset($_POST['image_title'])){
          $prev_photos = $current_post['photos'];
          $caption = str_ireplace(' ','-',sanitize($_POST['image_title']));
          $filename = $caption.':'.sanitize($_FILES['media_field']['name']);
        } else {
          $prev_photos = $current_post['photos'];
          $filename = sanitize($_FILES['media_field']['name']);
        }
      } else {
        $filename = '';
      }

      if(isset($_POST['categories'])){
        $categories = trim(sanitize($_POST['categories']));
        $categories = urlencode(str_ireplace('&#44;',',',$categories));
      } else {
        $categories = '';
      }

      if(!empty($filename)){
        if(!empty($prev_photos) && $prev_photos != ':'){
          $new_photos = $prev_photos .','.$filename;
        } else {
          $new_photos = $filename;
        }
      }
      $new_photos = trim($new_photos,',:');
      $new_photos = trim($new_photos,',');
      //~ echo $new_photos; die();
      //~ echo $file_path;
      //~ echo '<br>';
      //~ echo $filename; die();

     if(!empty($new_photos)){
        $q = query_db("UPDATE core_post SET post_name='{$post_name}', content='{$content}', last_updated='{$now}', categories='{$categories}', photos='{$new_photos}' WHERE id='{$id}'",
        "Could not update post! ");
        if($q){
          $_SESSION['status-message'] = '<div class="alert alert-success"> Post updated successfully</div>';
          redirect_to(BASE_PATH.'p/'.str_ireplace('+','-',urlencode($post_name)).':'.$id);
        }
      } else {
        $q = query_db("UPDATE core_post SET post_name='{$post_name}', content='{$content}', last_updated='{$now}', categories='{$categories}' WHERE id='{$id}'",
        "Could not update post! ");
        if($q){
          $_SESSION['status-message'] = '<div class="alert alert-success"> Post updated successfully</div>';
          redirect_to(BASE_PATH.'p/'.str_ireplace('+','-',urlencode($post_name)).':'.$id);
        }
      }
      //~ if($q){
        //~ $_SESSION['status-message'] = '<div class="alert alert-success"> Post updated successfully</div>';
        //~ redirect_to(BASE_PATH.'p/'.str_ireplace('+','-',urlencode($post_name)).':'.$id);
      //~ }
    }
    load_view('post','edit-post');

  } else {
    deny_access();
  }
}





function author_account_type_is($account_type){
  $author = $_SESSION['post_author'];
  $value = query_db("SELECT account_type FROM core_user WHERE user_name='{$author}'",
  "Could not get author account_type! ");
  if($account_type == $value['result'][0]['account_type']){
    return true;
  } else {
    return false;
  }
}

function show_my_posts(){
  if(is_logged_in() && is_user_post()){
    $user = trim(sanitize(user_being_viewed()));
    $q = query_db("SELECT `post_name`, `post_type`, `destination`
    FROM `core_post` WHERE `author`='{$user}' LIMIT 0, {$more}","Could not get my posts! ");

    $_SESSION['temp'] = $q;
    load_view('post','my-posts');
  }
}


function add_child_post(){

}

function remove_child_post(){


}



function is_child_post(){

}

function get_next_post(){
  if(is_child_post()){
  $child_num = sanitize($_GET['child_num']);
  $parent_id = $_SESSION['parent_id'];
  $id = sanitize($_GET['tid']);

  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`, `post_name`, `child_post_number` from post
  WHERE parent_id='{$parent_id}' and child_post_number >='{$child_num}' and id !='{$id}' order by child_post_number ASC limit 1");
  $num = mysqli_num_rows($query);

  $result = mysqli_fetch_array($query);
    if(!empty($num)){
    }
  }
}



function link_to_post_like($post_name,$wrap_round=''){
  $post_name = sanitize($post_name);
  $value = query_db("SELECT id, post_name FROM core_post WHERE post_name LIKE '%{$post_name}%' limit 1",
  "Could not get post like post name ");
  if(!empty($value['result'])){
    $id = $value['result'][0]['id'];
    $title = $value['result'][0]['post_name'];
    $link = $title;
    if(!empty($wrap_round)){
      $title = '';
    }
    $link = '<a class="text-capitalize" href="'.BASE_PATH.'p/'.str_ireplace(' ','-',$link).':'.$id.'"><div class="p-2">'.$wrap_round.'</div>'.$title.'</a>';
    echo $link;
    return $link;
  }
}

function link_to_post_by_id($id){
  $value = query_db("SELECT post_name FROM core_post WHERE id='{$id}'",
  "Could not get post title ");
  $id = $value['result'][0]['id'];
  $title = $value['result'][0]['post_name'];
  $link = '<a href="'.BASE_PATH.'post/action/show-post/'.str_ireplace(' ','-',$title).':'.$id.'">'.$title.'</a>';
  echo $link;
  return $link;
}


function list_child_posts(){
  $id = $_SESSION['post_id'];
  if(!empty($_GET['tid'])){
    $id = sanitize($_GET['tid']);


  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`,`post_name`,`child_post_number` FROM post WHERE parent_id='{$id}' ORDER BY child_post_number ASC") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  $num = mysqli_num_rows($query);
  if(!empty($num)){

    }
  }
}

function change_child_position(){
  if(isset($_GET['tid'])){
    $id = sanitize($_GET['tid']);
    }
    if(isset($_GET['move_child_position'])){
      $child_post_number = sanitize($_GET['move_child_position']);
      $q = mysqli_query($GLOBALS['___mysqli_ston'],
      "UPDATE post set child_post_number='{$child_post_number}' where id='{$id}'")
      or die('Could not reorder child post '.mysqli_error($GLOBALS['___mysqli_ston']));
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
this.post.url = {$_SESSION['current_url']};  // Replace post_URL with your post's canonical URL variable
this.post.identifier = {$_GET['tid']}; // Replace post_IDENTIFIER with your post's unique identifier variable
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

    $path = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $parent_id = $_SESSION['post']['id'];
    $created = date('c');

    //$reply = 'Join the Discussion';
    #print_r($_POST);
      if(isset($_POST['add_comment'])){
        $subject_name = trim(sanitize($_POST['post_name']));
        $content = trim(sanitize($_POST['content']));
        $parent_post_author = $_SESSION['post']['author'];
        $comment_author = $_SESSION['username'];
        $query = query_db("INSERT INTO `core_comments`(`id`,`path`,`parent_type`,`parent_id`,`parent_post_author`,`comment_author`,`content`,`created`) VALUES
        ('0', '{$path}','{$parent_type}','{$parent_id}','{$parent_post_author}','{$comment_author}', '{$content}','{$created}')",
        "Error inserting comments ");

        if($query){
          $post_path = $_SESSION['current_url'];
          $post_link = link_to_post_by_id($_SESSION['post']['id']);
          $q = query_db("SELECT `id` FROM core_comments WHERE `content`='{$content}' AND `created`='{$created}' LIMIT 0,1");
          $result= $q['result'][0];
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
      //~ echo '<div class="row">';
      echo '<div class="col-md-12 col-xs-12 p-3">';


      if(is_logged_in()){
        echo "<h4 id='comments'>{$reply} </h4>";
        //~ echo '<i>'.$placeholder.'</i>';
        echo'<form method="post" action="'.$_SESSION['current_url'].'" id="comment-form" class="whitesmoke padding-20" enctype="multipart/form-data">
        <input type="hidden" name="parent_id" value="'.$parent_id.'">
        <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
        <!-- Name of input element determines name in $_FILES array -->';
        echo ' <textarea name="content" size="5" placeholder="'.$placeholder.'" class="form-control"></textarea>

        <br><input type="submit" name="add_comment" value="'.$button_text.'" class="btn btn-primary">
        </form> ';
        echo '</div>';
      } else {
        log_in_to_comment();

      }
      //~ echo '</div>';

      $path = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
      $parent_id = $_SESSION['post']['id'];
      $query = query_db("SELECT * FROM `core_comments` WHERE `parent_type`='post' AND `parent_id`='{$parent_id}' ORDER BY `id` ASC",
      "comment list error");
      $_SESSION['post']['comments'] = $query['result'];
      $_SESSION['post']['num_comments'] = $query['num_results'];
      if(!empty($query['num_results'])){
         echo "<h4 id='comments'>{$reply} </h4>";
      }

      load_view('comment','comments-list');
}

function get_recent_comments_to_my_posts($num = ''){
  $username = $_SESSION['username'];
  if(empty($num)){
    $num = 2;
  }
  $q = query_db("SELECT * FROM comments where parent_post_author='{$username}' order by id desc limit {$num}",
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
        echo '<div class="p-3 m-2 tiny-text well">';
        link_to_user_by_username($result['comment_author']);
        echo ' commented on your post ';
        link_to_post_title_by_id($result['parent_id']);
        echo ' saying ';
        echo summarize(parse_text_for_output($result['content']),150);
        echo " <time class='timeago' datetime='".$result['created'] ."'>".$result['created'] ."</time>";
        echo ' will you respond? </div>';
      }
    }
  }
}

function edit_comment($comment_id){
  if(is_logged_in() && (is_comment_author($comment_id) || is_author())){

    $query = query_db("SELECT `content`, `parent_id` FROM core_comments WHERE id='{$comment_id}'",
    "Could not edit comment! ");

    $_SESSION['current-comment'] = $query['result'][0];

    if(isset($_POST['edit_comment'])){
      $ref_url = $_POST['ref_url'];
      $content = trim(sanitize($_POST['content']));

      $query = query_db("UPDATE core_comments SET `content`='{$content}' WHERE id='{$comment_id}'",
      "Could not edit comment ");

      if($query){
        $_SESSION['status-message'] = '<div class="alert alert-info">Comment edited and saved! </div>';
        //~ upload_image('','','',$comment_id=$result['id']);
        redirect_to($ref_url);
      }
    }
   load_view('comment','edit-comment');

  }
}


function get_num_comments($post_id=''){
  $query = query_db("SELECT COUNT(id) as count FROM core_comments WHERE parent_id='{$post_id}'",
  "Could not get num comments! ");
  return $query['count']  ;
}


function get_total_num_posts(){
  $value = query_db("SELECT COUNT(id) as count from core_post","Could not get num posts ");
  return $value['count'];
}

function get_num_post_reactions($post_id='',$reaction){
  if(!empty($post_id)){
    $value = query_db("SELECT COUNT(id) as count from core_post_reactions WHERE id='{$post_id}' and reason='{$reaction}'",
    "Could not get total num post reactions! ");
    return $value['count'];
  } else {
    $post_id = $_SESSION['post_id'];
    if(!is_home_post() && $_GET['post_name'] != 'talk'){
      $value = query_db("SELECT COUNT(id) as count from core_post_reactions WHERE post_id='{$post_id}' and reason='{$reaction}'",
      "Could not get total num post reactions! ");
      return $value['count'];
    }
  }
}

function get_total_num_post_reactions($post_id=''){
  if(!empty($post_id)){
    $value = query_db("SELECT COUNT(id) as count from post_reactions WHERE post_id='{$post_id}'",
    "Could not get total num post reactions! ");
    return $value['count'];
  } else {
    $post_id = $_SESSION['post_id'];
    if(!is_home_post() && $_GET['post_name'] != 'talk'){
      $value = query_db("SELECT COUNT(id) as count from post_reactions WHERE post_id='{$post_id}'",
      "Could not get total num post reactions! ");
      return $value['count'];
    }
  }
}


function show_total_num_post_reactions(){
  $num = get_total_num_post_reactions();
  echo $num;
  }

function is_comment_author($comment_id){
  $q = query_db("SELECT comment_author FROM core_comments WHERE id='{$comment_id}'",
  "Could not check if is comment author! ");
  if(!empty($q['result'][0]['comment_author'])){
    if($_SESSION['username'] == $q['result'][0]['comment_author']){
      return true;
    } else {
      return false;
    }
  }
}

function delete_comment($comment_id){
  $comment_id = sanitize($comment_id);
  if(is_logged_in() && (is_author() || is_comment_author($comment_id))){
    $query = query_db("DELETE FROM `core_comments` WHERE id='{$comment_id}'",
    "Could not delete comment! ");

    if($query) {
      $_SESSION['status-message'] = '<div class="alert alert-warning">Comment deleted!</div>';
    }
    redirect_to($_SESSION['prev_url']);
  } else{
    deny_access();
  }
}




function get_promoted_posts($string='', $limit='') {
   $query = mysqli_query($GLOBALS["___mysqli_ston"],
   "SELECT * from post WHERE promoted_on_homepost ='yes' ORDER BY id DESC' LIMIT 10") or die("Failed to get promoted posts" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

  echo "<div class='sweet_title'>Promoted Posts</br></div>";

   while($result= mysqli_fetch_array($query)){
   if ($section==='contest'){
     echo "<li><a href='" .ADDONS_PATH ."contest/?contest_name=" .$result['post_name'] ."&contest=yes'>"
     . ucfirst($result['post_name']) ."</a><p>" ;
     if($_GET['post_name'] !=='home'){
     echo strip_tags(urldecode($result['content'])) ;} echo "</p></li>";
     } else if(isset($_GET['section_name'])) {
     echo "<li><h3><a href='" .BASE_PATH ."?post_name=" .$result['post_name'] ."'>"
    . ucfirst($result['post_name']) ."</a></h3> &nbsp &nbsp" ;
    echo urldecode(substr($result['content'],0,160))."..." ;
    echo "</li>";
     } else {
      echo "<li><a href='" .BASE_PATH ."?post_name=" .$result['post_name'] ."'>"
     . ucfirst($result['post_name']) ."</a>" ;
     if($_GET['post_name'] !=='home'){
     echo urldecode(substr($result['content'],0,160))."..." ;} echo "</li>";
     }
  }
      echo "</ul></div>";

      echo $result['section_name'];
    // show sections

    if($_GET['post_name'] ==='sections'){
    get_grid_sections();
  }


}

function show_new_post_content(){
  if(addon_is_active('post')){
      space_down();
      //Link to posts (show num posts in last 7 days)
      $num_posts = get_total_num_posts();
      echo '
      <div class="sweet_title">';
      if(is_logged_in()){
        echo '<a href="'.BASE_PATH.'?post_name=talk">';
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
      show_post_reactions();

      $num_channels = get_total_num_channels();
      if(empty($num_channels)){
        $num_channels = 0;
      }

      if(is_logged_in()){
        show_featured_content();
      }

      echo '<h4 class="padding-10">Earn Money When People like what you Say </h4>';
      //~ show_latest_content_in('post','',$limit=4);
      get_discussion_content($max_num='4');
      if(is_logged_in()){
        echo '<div class="pull-right tiny-text">
        <a href="'.ADDONS_PATH.'hashtags">#Hashtag Channels</a> <span class="badge tiny-text">'.$num_channels.'<br></span>
        </div>
        <br><div class="block tiny-text"><a href="'.BASE_PATH.'?post_name=talk">show more &raquo;</a></div>';
      }
      echo '</div>';
    }
  }


function get_author_picture(){
  if(!empty($_SESSION['post_author'])){
    $author = get_user_details($_SESSION['post_author']);

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





function say_something(){
  if(is_logged_in()){

    process_post_submission();
    load_view('post','say-something-form');

  } else {
    log_in_to_continue();
  }
}


function show_dummy_text(){
  echo 'Lorem ipsum dolor sit amet consectetur adipiscing elit vel sapien suspendisse erat, aliquam senectus maecenas mus placerat interdum taciti inceptos bibendum nostra, tincidunt litora magnis lobortis ullamcorper cum rutrum fringilla ac congue. Torquent dapibus cras sollicitudin neque feugiat taciti aliquam ultrices leo suscipit, diam dignissim quam tristique sociosqu hac scelerisque bibendum dictumst massa mus, augue lectus viverra faucibus auctor ligula eget gravida proin. Aliquam platea faucibus quisque maecenas diam etiam inceptos, duis vitae pulvinar cras condimentum imperdiet nam, ante parturient natoque libero nascetur ac.';
  }

function dehyphenate($string){
  echo str_ireplace('-',' ',$string);
}

function get_post_by_id($id){
  $id = sanitize($id);
  $q = db_get_item('core_post',$id);
  return $q;
}

function get_post_stream($num=10,$start=''){
  if(empty($start)){
    $limit = $num;
  } else {
    $limit = "{$start}, {$num}";
  }
  $q = query_db("SELECT * FROM `core_post` where status='published' ORDER BY `id` DESC Limit {$limit}",
  "Could not get posts! ");
  $_SESSION['get-posts'] = $q;
}

function show_post_stream(){
  get_post_stream();
  load_view('post','show-post-stream');
}



function show_author_information($author){
  $user = get_user_by_username($author);
  echo '<div class="row p-3 m-2 bg-white border">';
    echo '<div class="col-md-1 col-xs-3 p-2">';
    show_user_pic($author,'rounded-circle ','50px');
    echo '</div>';


  echo '<div class="col-md-11 col-xs-9">
  <span class="d-inline p-2">';
    link_to_user_by_username($author);
    echo ' &raquo ';
    echo '<small class="text-muted">';
      dehyphenate($_SESSION['post']['post_name']);
    echo '</small>
  </span>
  <br>
  <span class="d-inline p-2">';
  if(isset($user['short_note'])){
    echo $user['short_note'];
  }
  echo '
  </span>
  </div>';
  echo '</div>';
}


function process_post_update(){
  //~ print_r($_POST); die();
// edit post

  //update db
  if(isset($_POST['updated'])){
    if(isset($_GET['tid'])){
    $id =  sanitize($_GET['tid']);
    }
  if(isset($_POST['id'])){
    $id =  sanitize($_POST['id']);
    }

  if(isset($_POST['categories'])){
    $categories = trim(sanitize($_POST['categories']));
    $categories = str_ireplace('&#44;',',',$categories);
  }
  if(isset($_POST['parent_id'])){
    $parent_id =  sanitize($_POST['parent_id']);
    }

  if(isset($_POST['post_name'])){
    $post_name = trim(sanitize(str_ireplace(' ','-',$_POST['post_name'])));
    if(isset($_POST['post_name'])){
      //~ $post_name = trim(sanitize($_POST['post_name']));
      $post_name = str_ireplace('%26lt%3Bp%26gt%3B','',$post_name);

      }
    }

  if(isset($_POST['content'])){
    $content = trim(sanitize($_POST['content']));
    if(empty($post_name)){
    //First check for presence of image title
    if(!empty($_POST['image_title'])){
    $post_name = urlencode(str_ireplace(' ','-',trim(sanitize($_POST['image_title']))));
    }else {
      if(strlen($content) > 30){
        $show_dots = '...';
      } else {
        $show_dots = '';
      }
      $post_name = urlencode(str_ireplace(' ','-',substr($content,0,50))).$show_dots;
      }
    }
  }

  if(isset($_POST['child_post_number'])){
    $child_post_number = trim(str_ireplace(' ','-',sanitize($_POST['child_post_number'])));
    }
  if(isset($_POST['post_type'])){
    $post_type = trim(sanitize($_POST['post_type']));
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

  $prev_post_photos = $_SESS['post']['photos'];
  echo $prev_post_photos; die();

  $allowed_file_type = ['.jpg','.png','jpeg'];
  $file_path = upload_file2('posts',$allowed_file_type);


    $q = query_db(
    "UPDATE `post` SET
    `post_name`='{$post_name}',
    `parent_id`='{$parent_id}',
    `child_post_number`='{$child_post_number}',
    `post_type`='{$post_type}',`visible`='{$visible}',
    `content`='{$content}',
    `last_updated`='{$now}',
    `editor`='{$editor}',
    `allow_comments`='{$allow_comments}',
    `path`='{$path}',
    `show_author`='{$show_author}',
    `categories`='{$categories}',
    `show_in_streams`='{$show_in_streams}' WHERE id='{$id}'",
    'Error updating post ');
    }
    if($q){
      session_message('alert','Post updated!');
       // Redirect
       unset($_POST);
      redirect_to($dest_url);
      }
  }

function publish_post($post_id){
$post_id = sanitize($post_id);
  if(is_author() || is_admin()){
    if(isset($_POST['publish_post'])){
      $visible = sanitize($_POST['show']);
      $q = query_db("UPDATE core_post SET status='published' WHERE id='{$post_id}'",
      "Could not publish post ");
      if($q){
        redirect_to($_SESSION['current_url']);
      }
    }

    if($_SESSION['post']['status'] == 'published'){
      echo '<form method="post" action="'.$_SESSION['current_url'].'">
      <input type="hidden" name="show" value="0">
      <input type="submit" name="unpublish_post" value="UnPublish" class="tiny-text btn btn-light border btn-xs">
      </form>';
    }
  }
}

function unpublish_post($post_id){
$post_id = sanitize($post_id);
  if(is_author() || is_admin()){
    if(isset($_POST['unpublish_post'])){
      $q = query_db("UPDATE core_post SET status='unpublished' WHERE id='{$post_id}'",
      "Could not unpublish post ");
      if($q){
        redirect_to($_SESSION['current_url']);
      }
    }

    if($_SESSION['post']['status'] == 'unpublished'){
      echo '<form method="post" action="'.$_SESSION['current_url'].'">
      <input type="hidden" name="show" value="1">
      <input type="submit" name="publish_post" value="Publish" class="tiny-text btn btn-white btn-xs">
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

function delete_post($id){
  if(is_admin() || is_author()){
    $id= sanitize($id);
    $q = query_db("DELETE from core_post where id='{$id}' LIMIT 1",
    'Could not delete post! ');

    if($q){
      $_SESSION['status-message'] = '<div class="alert alert-info">Post deleted!</div>';
      redirect_to(BASE_PATH.'blog');
    }
  }
}

function delete_post_photo($post_id,$photo_name){
  if(is_admin() || is_author()){
    //~ echo $photo_name; die();
    $photo_name = urldecode($photo_name);
    $q = query_db("SELECT photos FROM core_post WHERE id='{$post_id}'",
    "Could not get post photo in delete post photo!");
    $post_photos = explode(',',$q['result'][0]['photos']);
    if (($key = array_search($photo_name, $post_photos)) !== false) {
      unset($post_photos[$key]);
    }
    $post_photos = implode(',',$post_photos);
    //~ print_r($post_photos); die();
    $q = query_db("UPDATE core_post SET photos='{$post_photos}' WHERE id='{$post_id}'",
    "Could not upddate post photos in delete post photo! ");
    if($q){
      unlink(DIR_PATH.' files/photos/'.$photo_name);
      $_SESSION['status-message'] = '<div class="alert alert-info"> Photo deleted!</div>';
      redirect_to($_SESSION['prev_url']);
    }
  }
}

function show_post_reaction_statistics($post_id,$reaction=''){
  if(empty($post_id)){
    $post_id = $_SESSION['post_id'];
  }
  //~ Get num comments
  $comment_count = get_num_comments($post_id);
  $output = '';
  $value = query_db("SELECT author FROM core_post WHERE id='{$post_id}'",
  "Could not get post author in show post reaction statistics ");
  $_SESSION['post_author'] = $value['result'][0]['author'];

  $value = query_db("SELECT count(id) as count FROM core_post_reactions WHERE post_id='{$post_id}' and reason like'%{$reaction}%'",
  "Could not get reaction statistics 1 for post ");
    echo '<div class="text-muted mt-3 pl-2">';
    //~ if(!empty($comment_count)){
      $output .= '('.$comment_count .') comments, ';
    //~ }

    $value = query_db("SELECT count(id) as count FROM core_post_reactions WHERE post_id='{$post_id}' AND reason='like'",
    "Could not get reaction statistics 2 for post ");
    $output .= '('.$value['result'][0]['count'] .') likes ';


    echo $output;
    echo '</div>';
  }



function show_new_reactions_to_all_my_posts(){
  if(is_home_page() && is_logged_in()){
    $owner_id = $_SESSION['user_id'];
    //~ Get most recent post reactions for logged in user
    $value = query_db("SELECT * FROM core_post_reactions WHERE owner_id='{$owner_id}' order by id desc LIMIT 4",
    "Could not get home post reactions in show post reactions!");

  } else { // not homepage
    $post_id = $_SESSION['post']['id'];
    $value = query_db("SELECT * FROM core_post_reactions WHERE post_id='{$post_id}' order by id desc LIMIT 4",
    "Could not get any post reactions in show post reactions!");
  }
    $_SESSION['all-reactions-to-my-posts'] = $value;
    load_view('post','show-new-reactions-to-all-my-posts');
}

function show_post_reactions($post_id=''){

  //~ if(isset($_GET['show-post-reactions'])){

    if($_GET['num'] == 'all' || empty($_GET['num'])){
      $limit_num = '';
    } else {
      $limit_num = ' LIMIT 0, '.sanitize($_GET['num']);
    }
    $post_id = sanitize($_SESSION['post']['id']);
    echo '<b>post reactions for '; link_to_post_title_by_id($post_id); echo '</b>';
    //~ echo 'sessionpost id is '.$_SESSION['post_id'];

    if(is_logged_in() && $post_id == $_SESSION['post_id']){
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
        $author = $_SESSION['post_author'];
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
          link_to_post_title_by_id($post_id);
        echo "</h3>";

        $value = query_db("SELECT id,owner_id,reactor_id,amount,date FROM post_reactions WHERE post_id='{$post_id}' AND owner_id='{$owner_id}' AND reason='{$reaction}' order by id desc {$limit_num}",
        "Could not get post reactions in show post reactions!");

        if($value['num_results'] > 0){
          echo '<ol>';

          foreach($value['result'] as $result){
            if($owner_id == $result['owner_id']){
              $user = get_user_by_id($result['reactor_id']);
              //~ link_to_user_by_id($result['reactor_id']);
              $user_pic = show_user_pic($user,'rounded-circle','35px');
              if($reaction == 'touched'){
                $reaction_past = 'was touched by';
              }
              echo '<li>' .$user_pic['thumbnail'] .$reaction_past .' on '.$result['date'].' -  it, <span class="label label-success">'.$_SESSION['preferred_currency'].' '.convert_to_user_currency($result['amount']).'</span><hr></li>';
            }
          } echo '</ol>';
        } else {
          echo "- No {$reaction} actions to display -";
        }
        if(!isset($_POST['reaction']) && user_has_reacted($like)){
        show_post_reaction_statistics($post_id,'');
        }
      } else{
        status_message('alert','You do not have access to view reactions for this post!');
        }
    }
    else {
      if(is_home_post() && is_logged_in()){
        $owner_id = $_SESSION['user_id'];
        //~ Get most recent post reactions for logged in user
        $value = query_db("SELECT * FROM post_reactions WHERE owner_id='{$owner_id}' order by id desc LIMIT 4",
        "Could not get home post reactions in show post reactions!");

      } else { // not homepost
        $post_id = $_SESSION['post_id'];
        $value = query_db("SELECT * FROM post_reactions WHERE post_id='{$post_id}' order by id desc LIMIT 4",
      "Could not get any post reactions in show post reactions!");
      }

      if($value['num_results'] > 0){
        if(is_home_post()){
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
            echo '<li>' ;link_to_user_by_id($result['reactor_id']); echo $reaction_past; link_to_post_by_id($result['post_id']); echo ' <span class="label label-success">'.$_SESSION['preferred_currency'].' '.convert_to_user_currency($result['amount']).'</span> on '.$result['date'].' <hr></li>';

          } else {
            echo '<li>' ;link_to_user_by_id($result['reactor_id']); echo ' commented on ';
            if($_SESSION['post_id'] == $result['post_id']){
              echo 'it';
            } else {
              link_to_post_by_id($result['post_id']);
            }
            if(!empty($result['amount'])){
              echo ', you received <span class="label label-success">'.$_SESSION['preferred_currency'].' '.convert_to_user_currency($result['amount']).'</span> on '.$result['date'].'<hr></li>';
            }
          }

        } echo '</ol>';
      }
    }
    if(isset($_GET['post_name']) && !is_home_post()){
    if(is_author() || is_admin()){
      echo '
      <span class="tiny-text pull-right padding-10">
        <a href="'.BASE_PATH.'post?show-post-reactions='.$_SESSION['post_id'].'&num=all">view all post reactions</a>
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
  $post_id = $_SESSION['post']['id'];
  $q = query_db("SELECT id FROM core_post_reactions WHERE post_id='{$post_id}' AND reactor_id='{$user_id}' AND reason='{$reaction}'",
  "Could not check if user has reacted!");

  if(!empty($q['result'][0]['id'])){
    return true;
  } else {
    return false;
  }
}

function user_has_commented($post_id,$user_id){
  //~ $user_id = $_SESSION['user_id'];
  $post_id = $_SESSION['post']['id'];
  $q = query_db("SELECT id FROM core_post_reactions WHERE post_id='{$post_id}' AND reactor_id='{$user_id}' AND reason LIKE '%commented%' AND amount='5'",
  "Could not check if user has reacted!");

  if(!empty($q['result'][0]['id'])){
    return true;
  } else {
    return false;
  }
}

function react_to_post(){

  if($_SESSION['post_is_a_course']){
    echo '<em class="tiny-text">This is a training program / course.<br>
    Enrolling will give you access to all child posts in this course.</em>';
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

    if($reaction == 'enroll' && $_SESSION['user_funds_amount'] >= $amount){
      $amount = $_SESSION['post_enroll_amount'];
      $reaction_past = 'enrolled for';
    }

    $post = $_SESSION['post_name'];

  $reactor = $_SESSION['username'];
  $owner = $_SESSION['post_author'];

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
  ){
    $btn_state = 'disabled="disabled"';
  } else {
    $btn_state = '';
  }

  echo '<form method="post" action="'.$_SESSION['current_url'].'">
  <div class="btn-group">';
    //~ if($_SESSION['site_funds_amount'] > 10){
      echo '<button type="submit" class="btn btn-primary btn-xs padding-5" title="I like this" name="reaction" value="like" onclick="this.form.submit()" '.$btn_state.'><i class="glyphicon glyphicon-thumbs-up"></i> Like</button>';

        echo $disabled_message;
    if(!empty($btn_state) && !is_logged_in()){
      echo '<br><span class="tiny-text red-text col-xs-12">Buttons disabled - Log in to react</span>';
      } else
    if(!empty($btn_state) && $_SESSION['site_funds_amount'] < 5){
      echo '<br><span class="tiny-text red-text">Buttons disabled - You are too broke to react!</span>';
      }
  echo '</div>
  </form>';

}

function blog(){
  //~ Show today
  $today =  getdate();
  //~ print_r($today);
  echo '<div class="row border bg-info text-white">
    <h1 align="center" class="p-3 mx-auto">Blog</h1>
  </div>';
  echo '<div class="col-md-12 col-xs-12 p-2 border text-success border-info d-inline bg-white">'.$today['weekday'].' '.$today['month'].' '.$today['mday'].' '.$today['year'].'</div>';
  echo '<div class="row bg-white pb-3 ">';
  show_recent_posts();
  echo '</div>';
  //~ Show blogs in grid view

  //~ Show recent blogs

  //~ Show blog categories
}


//~ SESSION variables and their functions
//~ $_SESSION['post']['recent-posts'] : Holds db info on the recent posts

?>
