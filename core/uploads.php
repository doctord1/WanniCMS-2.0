<?php
# FILE UPLOADS


function upload_image($r='',$folder='', $instruction='') {

  if(is_logged_in()){
  #$folder should end in a forward slash eg $folder = 'user/'
  // Get Global Functions filepath
  global $r;
  // Set relative paths for edit pages, correcting for a regions bug
  if($r==='' && !url_contains('edit_')){
    $r = dirname(__FILE__);
    $r2 = str_ireplace('/regions/','',$r);
    $r = $r2;
    }

// sort out the relevant paths (Sacred)
$destination_url = $_SESSION['current_url'];
$uploaddir = $r.'/uploads/files/';
$uploadfile = $uploaddir .$folder.'/'. basename($_FILES['media_field']['name']);
$m = str_ireplace('/regions/','',$uploadfile); // fixes a bugin upload_no_edit()
$uploadfile = $m;
$path = BASE_PATH.'uploads/files/'.$folder.'/'. basename($_FILES['media_field']['name']);
$m = str_ireplace('/regions/','',$path);
$path = $m;
$rpath = $r.'/uploads/files/'.$folder.'/'. basename($_FILES['media_field']['name']);
$m = str_ireplace('/regions/','',$rpath); // fixes a bugin upload_no_edit()
$rpath= $m;
$owner_id = $_SESSION['user_id'];

  # ONSUBMIT
  if (isset($_FILES['media_field'])){
  // Variables
  $type = $_FILES['media_field']['type'];
  $name = basename($_FILES['media_field']['name']);
  $prev_url = trim(mysql_prep($_POST['ref_url']));
  $created = date('c');


    // Set file parents
    if(isset($_GET['block_name'])){
     $parent = "block";
    } elseif (isset($_GET['block_name'])){
     $parent = "post";
    } elseif(isset($_POST['page_type'])){
      $parent = trim(mysql_prep($_POST['page_type']));
    } elseif(isset($_GET['product_code'])){
      $parent = "product";
    } elseif(isset($_GET['fid'])){
      $parent = "fundraiser";
      $parent_id = mysql_prep($_GET['fid']);
    } elseif(isset($_GET['project_id'])){
      $parent = "project_manager";
      $parent_id = mysql_prep($_GET['project_id']);
    } elseif(isset($_GET['project_id'])){
      $parent = "project_manager";
      $parent_id = mysql_prep($_GET['project_id']);
    } elseif(isset($_GET['contest_name'])){
      $parent = "contest";
      if(isset($_GET['contest_entry_id'])){
        $parent = 'contest entry';
        $parent_id = mysql_prep($_GET['contest_entry_id']);
        }
    } elseif(isset($_GET['item_code'])){
      $parent = "market";
    } elseif(isset($_GET['company_name'])){
      $parent = "company post";
    } else {
      $parent = $_SESSION['page_type'];
    }

    // Set file parent_ids
    if(isset($_SESSION['last_post_insert_id'] ) && !isset($_GET['fid'])){
    $parent_id =  $_SESSION['last_post_insert_id'] ;
    }
    if(isset($_GET['tid']) && !isset($_SESSION['last_post_insert_id'] )){
    $parent_id = mysql_prep($_GET['tid']);
    }
    if(isset($_GET['cid']) && !isset($_SESSION['last_post_insert_id'])){
    $parent_id = mysql_prep($_GET['cid']);
    }


    // Move files to server
    // Limit file types before move
    if(!empty($type)){
      if($type== 'image/jpeg' || $type== 'image/png' || $type== 'image/gif'){
      $move = move_uploaded_file($_FILES['media_field']['tmp_name'], $uploadfile);
      } else {
        status_message('error','File type not allowed! here');
        }
    }
    if($move ==1){

      $newImg = imagecreatetruecolor($nWidth=500, $nHeight=500);
      imagealphablending($newImg, false);
      imagesavealpha($newImg,true);
      $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
      imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
      imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);

      $parent2 = str_ireplace('#','%23',$parent);
      $small_path = resize_pic_small($pic=$rpath);
      $medium_path = resize_pic_medium($pic=$rpath);
      $large_path = resize_pic_large($pic=$rpath);

    // Save file to db
    $query = query_db("INSERT INTO `files`(`id`, `name`, `large_path`, `medium_path`, `small_path`, `original_path`, `parent`, `parent_id`, `type`,`destination_url`,`owner_id`)
     VALUES ('0', '{$name}', '{$large_path}', '{$medium_path}', '{$small_path}', '{$path}', '{$parent}', '{$parent_id}', '{$type}', '{$destination_url}','{$owner_id}')",
     "Could not save image to DB! ");
    if(!$query){
      echo "<div class='alert'>Error : No file uploaded!\n</div>";
    } else {
      unset( $_SESSION['last_post_insert_id'] );
      session_message('success','File is valid, and was successfully uploaded.');
    }
    redirect_to($destination_url);

  } // end if move = 1
}
//echo 'Here is some more debugging info:' .$_FILES['media_field']['error']; //testing

    if($_GET['page_name'] != 'home' && $_GET['page_name'] != 'talk' && !isset($_GET['company_name'])  && !isset($_POST['is_comment'])){
# UPLOAD FORM
  echo '<div id="upload-pic-content"> <h3> Add pictures to slider</h3><form action="'
  .$_SESSION['current_url'].'" method="post" enctype="multipart/form-data">
  <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="contest_entry_id" value="'.$contest_entry_id.'" />
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    <!-- Name of input element determines name in $_FILES array -->
  <input type="file" size="500" name="media_field" value="">
  <input type="submit" name="submit" value="upload media" class="btn btn-primary">
  </form>';
  echo '<em>' .$instruction .'</em></div>';



  upload_attachment();
  show_linked_attachments();
  show_page_images();
  show_free_images();
  }
} // end is_logged_in
}

function upload_file($upload_location){
   //~ returns path of uploaded file
   //~ upload location relative to BASE_PATH
  global $r;
  if($_POST['uploading-form'] == 'upload-file-form'){
    $type = $_FILES['file_field']['type'];
    if($r==='' && !url_contains('edit_')){
      $r = dirname(__FILE__);
      $r = $r2;
      }

    if($type == 'application/pdf' || $type == 'application/doc' || $type== 'image/jpeg' || $type== 'image/png' || $type== 'image/gif'){
      //~ sort out the relevant paths (Sacred)
      $uploaddir = $r.'/'.$upload_location;
      $uploadfile = $uploaddir .'/'. basename($_FILES['file_field']['name']);
      //~ echo $uploadfile;
      $path = BASE_PATH.$upload_location.'/'. basename($_FILES['file_field']['name']);
      //~ echo '<br>'.$path;


      # ONSUBMIT
      if(isset($_FILES['file_field']) && !empty($_FILES)){
        //~ print_r($_POST);print_r($_FILES); die();
        $name = basename($_FILES['file_field']['name']);
        $move = move_uploaded_file($_FILES['file_field']['tmp_name'], $uploadfile);

        if($move ==1){
          return $path;
          }
      }
    } else { status_message('error','File type not allowed!');  }
  }
}

function upload_attachment(){
  #$folder should end in a forward slash eg $folder = 'user/'
  global $r;
  $type = $_FILES['media_field']['type'];
  if($r==='' && !url_contains('edit_')){
    $r = dirname(__FILE__);
    $r2 = str_ireplace('/regions/','',$r);
    $r = $r2;
    }

  if($type == 'application/pdf'){
    //~ echo 'PDF here!'; die();
    $submit =  mysql_prep($_POST['submit']);
    $destination_url = $_SESSION['current_url'];
    $uploaddir = $r.'/uploads/files/';
    $uploadfile = $uploaddir .$folder.'/'. basename($_FILES['media_field']['name']);
    $m = str_ireplace('/regions/','',$uploadfile); // fixes a bugin upload_no_edit()
    $uploadfile = $m;
    //echo $uploadfile;
    $path = BASE_PATH.'uploads/files/'.$folder.'/'. basename($_FILES['media_field']['name']);
    $m = str_ireplace('/regions/','',$path);
    $path = $m;
    $rpath = $r.'/uploads/files/'.$folder.'/'. basename($_FILES['media_field']['name']);
    $m = str_ireplace('/regions/','',$rpath); // fixes a bugin upload_no_edit()
    $rpath= $m;


    # ONSUBMIT
    if (($submit== 'upload attachment') && !empty($_FILES)){
    //~ print_r($_POST);print_r($_FILES); die();

    $name = basename($_FILES['media_field']['name']);
    $path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

    if($parent == ''){

    if (!empty($_POST['page_type'])){
    $parent = trim(mysql_prep($_POST["page_type"]));
    }

  else if (isset($_GET['page_name'])){
  $parent = $_SESSION['page_type'];

} elseif (isset($_GET['block_name'])){
   $parent = "block";
} elseif(isset($_GET['section_name'])){
  $parent = "section";
} elseif(isset($_GET['product_code'])){
  $parent = "product";
} elseif(isset($_GET['fundraiser_name'])){
  $parent = "fundraiser";
} elseif(isset($_GET['contest_name'])){
  $parent = "contest";
} elseif(isset($_GET['money_service_code'])){
  $parent = "money service";
} elseif(isset($_GET['company_name'])){
  $parent = "company post";
}
//~ } else {
  //~ //$parent = "pic".$name;
  //~ $pic_mode = true;
  //~ }
}

if(isset($_GET['tid'])){
  $parent_id = mysql_prep($_GET['tid']);
  }
if(isset($_GET['cid'])){
  $parent_id = mysql_prep($_GET['cid']);
  } else
  {$parent_id = $_SESSION['id'];}



if(isset($_POST['contest_entry_id'])){
  $contest_entry_id = mysql_prep($_POST['contest_entry_id']);
  } else {
       $contest_entry_id = 0;
      }



if(isset($_GET['contest_entry_id'])){
  $contest_entry_id = mysql_prep($_GET['contest_entry_id']);
  if(isset($_POST['is_comment'])){
    $is_comment = true;
    $comment_id = '0';
    } else {
      $is_comment = false;
      $comment_id = '0';
      }
  } else {$contest_entry_id = '0';}

  if(empty($comment_id)){
    $comment_id = 0;
    }

  if($type== 'application/pdf'){
  $move = move_uploaded_file($_FILES['media_field']['tmp_name'], $uploadfile);
  } else { status_message('error','Attachment type not allowed!'); }

  if($move ==1){
    $query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `files`(`id`, `name`, `large_path`, `medium_path`, `small_path`, `original_path`, `parent`, `parent_id`, `type`,`destination_url`,`owner`)
     VALUES ('0', '{$name}', '{$large_path}', '{$medium_path}', '{$small_path}', '{$path}', '{$parent2}', '{$parent_id}', '{$type}', '{$destination_url}','{$author}')")
    or die("Could not save image to DB!" . mysqli_error($GLOBALS["___mysqli_ston"]));
    $_SESSION['last_upload'] = $name;
    session_message('success','File is valid, and was successfully uploaded.');

    if(!$query) {
      echo "<div class='alert'>Error : No file uploaded!</div>";
      }
    redirect_to($destination_url);
    }

    }
  }

  echo '<div id="upload-attachment-content"><h3> Add Attachment </h3><form action="'
  .$_SESSION['current_url'].'" method="post" enctype="multipart/form-data">
  <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    <input type="hidden" name="post_type" value="'.$_SESSION['post_type'].'" />
  <input type="file" size="500" name="media_field" value="">
  <input type="submit" name="submit" value="upload attachment">
  </form></div>';
}

function show_linked_attachments(){
  if(isset($_GET['page_name']) ){
    $parent = trim(mysql_prep($_GET['page_name'])) ." page";
  }elseif(isset($_GET['block_name'])){
    $parent = trim(mysql_prep($_GET['block_name'])) ." block";
  }
  if(isset($_GET['tid'])){
    $parent_id = mysql_prep($_GET['tid']);
    }
   $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `files` WHERE (`parent_id`='{$parent_id}' )
   or (`parent`='{$parent}' AND `destination_url`='{$destination_url}') ORDER BY `id` DESC")
   or die("Failed to get attachments!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
   $num = mysqli_num_rows($query);

   if($num > 0){
    echo '<div id="uploaded-attachment-content" class="clear">';
    if(!empty($num)){
      echo '<h4>Attachments</h4>';
    }
     while($result = mysqli_fetch_array($query)){
       if($result['type'] == 'application/pdf'){
        echo '<a target="_BLANK" href="'.$result['original_path'] .'">'.$result['name'].'</a>
        <a href="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
  .'&delete_pic=' .$result['id'].'&redirect_to='.$_SESSION['current_url']
  .'"><em> | <span class="tiny-text">delete </span></em></a><hr>';
         }
       }
    echo '</div>';
    }

  }

function get_linked_image($parent='', $parent_id='',$pic_size='',$limit='',$has_zoom='',$for_slideshow='false'){
  //~ echo $parent;
  //~ echo $parent_id;
  $contest_entry_id = mysql_prep($_GET['contest_entry_id']);

  if ($limit !==''){
    $sql_suffix = "LIMIT 0, {$limit}";
  } else {
    $sql_suffix = '';
  }
  $destination_url = $_SESSION['current_url'];
  $output = array();
  $query = query_db("SELECT distinct * FROM `files` WHERE parent='{$parent}' and parent_id='{$parent_id}' ORDER BY `id` DESC {$sql_suffix}",
  'Could not get linked images ');

  foreach($query['result'] as $result){
    $file ='';
    if(!empty($result['parent_id']) && $result['type'] != 'application/pdf'){
      if($for_slideshow == 'false'){
        $width ='';
        if($pic_size ==='large'){
          $image_sized = $result['large_path'];
          $width = '500px';
          $height = '400px';
        } else if($pic_size==='small'){
          $image_sized = $result['small_path'];

        } else if($pic_size=== 'medium'){
          $image_sized = $result['medium_path'];
        } else if($pic_size==='original'){
          $image_sized = $result['original_path'];
        } else if($pic_size==='half'){
          $image_sized = $result['large_path'];
          $width = "35%";
        } else if($pic_size==='fit'){
          $image_sized = $result['medium_path'];
          //$width = 'width="100%"';
        } else {
          $image_sized = $result['medium_path'];
        }

        if($has_zoom == 'true' ){
          $file .= "<a href='".$result['original_path']."' rel='prettyPhoto[".$subject_id.$parent.$comment_id."_gal]'>";
        }
        $file .='<img src="' .$image_sized .'" alt="'.$result['name'].'" class="col-md-12 col-xs-12 thumbnail img-responsive" width="'.$width.'">';

        if($has_zoom=='true'){
        $file .= '</a>';
        }
        if((is_file_owner($file_owner=$result['owner']) || is_admin())  && $for_slideshow != 'true' && !empty($for_slideshow)  && $pic_size == 'half' || $pic_size == 'fit'){
          $file .= '<a href="'.$_SESSION['current_url'].'&delete_pic='.$result['id'].'" class="padding-5 tiny-text pull-right inline-block">delete pic</a>';
        }
        $output[] =$file ;
      } else {
        $output[] = $result['large_path'];
      }

    }
  }
  return $output;

}

function is_file_owner($file_owner=''){
  if(is_logged_in() && $_SESSION['username'] == $file_owner){
    return true;
    }
  }


function remove_file(){

   # DELETE ANY REMOVED FILES
  if(isset($_GET['delete_pic'])){
    $file_id= trim(mysql_prep($_GET['delete_pic']));
  } else if(isset($_GET['do_delete'])){
    $file_id= trim(mysql_prep($_GET['delete_pic']));
  }

  if($_GET['delete_pic'] && is_logged_in()){

    $query = query_db("SELECT `id`, `large_path`, `medium_path`, `small_path`, `original_path` FROM `files` WHERE `id`='{$file_id}'",
    "Error fetching filepath! ");
    $result = $query['result'][0];
    $file_id = $result['id'];
    $original_file_path = $result['original_path'];
    $large_file_path = $result['large_path'];
    $medium_file_path = $result['medium_path'];
    $small_file_path = $result['small_path'];

    $lookup = strpos($large_file_path, 'large_size');
    if($lookup > 1){
      unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/files/large_size/'.$result['name']);
    }
    $lookup = strpos($medium_file_path, 'medium_size');

     if($lookup > 1){
      unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/files/medium_size/'.$result['name']);
    }
    $lookup = strpos($small_file_path, 'small_size');
     if($lookup > 1){
      unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/files/small_size/'.$result['name']);
    }
    $lookup = strpos($original_file_path, '_size');
     if($lookup < 1){
      unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/files/'.$result['name']);
    }

    $delete = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM files WHERE id='{$file_id}'")
    or die("Could not delete images!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));


    if($delete){
      session_message('success','File / picture removed!');

      //~ redirect_to($_SESSION['prev_url']);
    }

  }
}




function show_thumbnail($parent='',$id=''){
  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT medium_path from files where parent='{$parent}' and parent_id='{$id}' order by id desc limit 0, 1")
   or die("Something is wrong".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  while($result = mysqli_fetch_array($query)){
  echo "<img src='{$result['medium_path']}' width='50' height='50' hspace='10' vspace='10'>";
  }
}

function show_images_in_list($images='',$width='',$height=''){
  # $images should be an array of images

  if($images !== ''){


    foreach ($images as $image){

    echo '<img class="img-responsive thumbnail" src="'.$image.'">';
    }


    }

}

function show_page_images(){

  $url = $_SESSION['current_url'];
  if (url_contains('edit')){
    if(isset($_SESSION['page_type'])){
    $parent = trim(mysql_prep($_SESSION["page_type"]));
    }
   else if (isset($_GET['page_name'])){
   $parent = $_SESSION['page_type'];

} elseif (isset($_GET['block_name'])){
   $parent = "block";
} elseif(isset($_GET['section_name'])){
  $parent = "section";
} elseif(isset($_GET['product_code'])){
  $parent = "product";
} elseif(isset($_GET['fundraiser_name'])){
  $parent = "fundraiser";
} elseif(isset($_GET['contest_name'])){
  $parent = "contest";
} elseif(isset($_GET['money_service_code'])){
  $parent = "money service";
} elseif(isset($_GET['company_name'])){
  $parent = "company post";
}

  if(isset($_SESSION['id']) && $_SESSION['id'] != 0){
     $parent_id = mysql_prep($_SESSION['id']);
     if(isset($_SESSION['contest_id']) && $_SESSION['contest_id'] != 0){
    $parent_id = mysql_prep($_SESSION['contest_id']);
       }
      if(isset($_GET['tid'])){
      $tid = mysql_prep($_GET['tid']);
      $parent_id = $tid;
      }

     $query = mysqli_query($GLOBALS['___mysqli_ston'],"SELECT * FROM `files` WHERE parent='{$parent}' and parent_id='{$parent_id}' ORDER BY `id` DESC")
     or die("Failed to select images!" . mysqli_error($GLOBALS['___mysqli_ston']));
     }
echo "<p align='center'><hr><big><strong>Uploaded Images</strong></big></p>";

# SET RETURN PATH


while($result= mysqli_fetch_array($query)) {

  $pics = '<img src="' .$result['small_path'] .
  '" width="50" height="50" alt="image">&nbsp &nbsp<br>';

  $text = $result['name'];
  $wrapped_text = wordwrap($text,11,"<br> \n", true);

  $pics = $pics .$wrapped_text .'</td>
  <a href="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
  .'&delete_pic=' .$result['id']
  .'"><em> remove </em></a><hr>';
  echo $pics;
  }

}

}


function show_free_images(){
  $url = $_SESSION['current_url'];
  if (url_contains('uploads')){
    echo "<p align='center'><hr><h1> All FREE Uploaded Images</h1></p>";
    # Get  free uploaded images
    $parent = 'free';

    #echo "Parent is " .$parent .""; //Testing

    # GET RELATED FILES (CHILD IMAGES OR FILES)
    $images_result = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * from files WHERE `parent`='{$parent}' ")
     or die("Failed to select images!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));

    $num = mysqli_num_rows($query);

    while($result= mysqli_fetch_array($images_result)) {

      $pics = '<table><tr>
      <td></div><img src="' .$result['small_path'] .
      '" width="100" height="100" alt="image">&nbsp &nbsp<br>';

      $text = $result['name'];
      $wrapped_text = wordwrap($text,11,"<br> \n", true);

      $pics = $pics .$wrapped_text .'</td>
      <td><a href="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
      .'?delete_pic=' .$result['id']
      .'"><em> remove </em></a></td>

      <td></div></td>

      </tr></table><hr>';
      echo $pics;

      }
      if(empty($num)){
        echo "No free images <br><hr>";
        }


  }


}

function resize_pic_small($pic='',$option='auto'){
  global $r;
  $width=50;
  $height=50;
  $dest_folder= $r.'uploads/files/small-size/'. basename($_FILES['media_field']['name']);
  $m = str_ireplace('regions/','',$dest_folder); // fixes a bugin upload_no_edit()
  $dest_folder = $m;

  //~ try {
    //~ $img = new abeautifulsite\SimpleImage($rpath);
    //~ echo $image;
    //~ $img->resize(50,50)->best_fit(50, 50)->save($dest_folder);
    //~ } catch(Exception $e) {
        //~ echo 'Error: ' . $e->getMessage();
    //~ }
  $output = BASE_PATH.'uploads/files/small-size/'. basename($_FILES['media_field']['name']);
  //~ /**$folder is the folder name, eg thumbnail, medium etc
   //~ * $option is one of : exact, portrait, landscape, auto, crop
   //~ * */
  //~
  //~
  // USING THE RESIZE CLASS

// *** 1) Initialise / load image
$resizeObj = new resize($pic);

// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
$resizeObj -> resizeImage($width, $height, $option);

// *** 3) Save image ('image-name', 'quality [int]')

$resizeObj -> saveImage($dest_folder, 90);

return $output;

}

function resize_pic_medium($pic='',$option='auto'){
  global $r;
  $width=240;
  $height=240;
  $dest_folder= $r.'uploads/files/medium-size/'. basename($_FILES['media_field']['name']);
  $m = str_ireplace('regions/','',$dest_folder); // fixes a bugin upload_no_edit()
  $dest_folder = $m;
  $output = BASE_PATH.'uploads/files/medium-size/'. basename($_FILES['media_field']['name']);
  /**$folder is the folder name, eg thumbnail, medium etc
   * $option is one of : exact, portrait, landscape, auto, crop
   * */


  // USING THE RESIZE CLASS

// *** 1) Initialise / load image
$resizeObj = new resize($pic);

// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
$resizeObj -> resizeImage($width, $height, $option);

// *** 3) Save image ('image-name', 'quality [int]')

$resizeObj -> saveImage($dest_folder, 90);

return $output;
}

function resize_pic_large($pic='',$option='auto'){
  global $r;
  $width=500;
  $height=500;
  $dest_folder= $r.'uploads/files/large-size/'. basename($_FILES['media_field']['name']);
  $m = str_ireplace('regions/','',$dest_folder); // fixes a bugin upload_no_edit()
  $dest_folder = $m;
  $output = BASE_PATH.'uploads/files/large-size/'. basename($_FILES['media_field']['name']);
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

function show_files_listing($start='',$stop=''){

  if(empty($start)){$start = 0;}
  if(empty($stop)){$stop = 50;}

  if(isset($_GET['start'])){$start = trim(mysql_prep($_GET['start']));}
  if(isset($_GET['stop'])){$stop = trim(mysql_prep($_GET['stop']));}

  global $r;
  $dir= $r.'uploads/files/';
  $files = scandir($dir);

  //echo $dir."small-size/".$delete;
  //do requested delete
  if(!empty($_GET['delete'])){
    $delete = trim(mysql_prep($_GET['delete']));
    $rm = unlink($dir.$delete);
    unlink($dir."large-size/".$delete);
    unlink($dir."medium-size/".$delete);
    unlink($dir."small-size/".$delete);

    if($rm){
      status_message("success", "File deleted successfully!");
      } else { status_message("error", "Unlink failed!");}
    }

  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `files` order by id DESC LIMIT {$start},{$stop}")
  or die("Error Fetching files " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  $start = $stop;

  $num = mysqli_num_rows($query);

  //refresh $files
  $files = scandir($dir);
  echo "<h1>Files Listing</h1>
  <table class='table'><thead><th>id</th>
  <th>Preview</th><th>Filename</th>
  </thead><tbody>";
  while($result = mysqli_fetch_array($query)){

    if(in_array($result['name'],$files,TRUE)){
      echo "<tr><td>{$result['id']}</td><td><a href='".$result['large_path']."'><img src='".$result['small_path']."'></a></td><td>".$result['name']."<br>
      <strong>Appears in: </strong> <em>".$result['parent']."</em></td></tr>";
      if(($key = array_search($result['name'], $files)) !== false) {
      unset($files[$key]);
      }
    } else{
      echo "<tr><td>{$result['id']}</td><td><a href='".$result['large_path']."'><img src='".$result['small_path']."'></a></td><td>".$result['name']."<br>
      <strong>Appears in: </strong> <em>".$result['parent']."</em></td></tr>";
      }

  }
  echo "</tbody></table>";

  if(empty($num)){
    status_message("alert","There are no more files !");
    }
  echo "<a href='".BASE_PATH."uploads?start={$start}&stop={$stop}'><button>Show me more</button></a> ";
  echo "<a href='".BASE_PATH."uploads'><button>Reset</button></a> ";



  echo "<br><hr><h1>Unused files</h1>";

  foreach($files as $file){
    if(strpos($file,'_gallery') === false){
      if($file !== '.' && $file !== '..' && is_file($dir.$file)){
      echo "<hr><a href='".BASE_PATH."uploads/files/large-size/{$file}'><img src='".BASE_PATH."uploads/files/small-size/{$file}'></a>&nbsp;&nbsp;
      <a href='".BASE_PATH."uploads?delete=".$file."&control=".$_SESSION['control']."'><em>delete</em></a>";
      }
    }
  }

}



 function upload_no_edit($allow=''){

  if((is_author() || is_admin() || $allow == true)
   && is_logged_in()
   && !url_contains('page_name=home')
   && !url_contains('page_name=talk')
   && !url_contains('section_name=')){

       echo "<div id='pic-toggle' class=''>
     <span class='text-center gainsboro tiny-text inline-block' id='add-picture'>Add media to this post</span><span id='pic-close' class='hidden' style='background-color: gainsboro; padding: 5px; cursor: pointer'> Close x</span><br>
     <div class='content upload-no-edit-slideout hidden tiny-text'>";
     echo "<div class='text-center tiny-text gainsboro margin-3 inline-block' id='upload-pic-toggle' style='padding: 5px; cursor: pointer'>Upload picture</div>
     <div class='text-center gainsboro tiny-text margin-3 inline-block' id='upload-attachment-toggle' style='padding: 5px; cursor: pointer'> Upload attachment</div>";

     upload_image();

       echo "</div></div>";
    // show_page_images();

  }
}

 //~ echo 'end of uploads functions file' ;
 // in root/core/uploads.php
 ?>
