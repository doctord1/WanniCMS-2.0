<?php

//~ function resize($newWidth, $targetFile, $originalFile) {

    //~ $targetFile = str_ireplace(BASE_PATH,DIR_PATH,$targetFile);
    //~ $originalFile = str_ireplace(BASE_PATH,DIR_PATH,$originalFile);
    //~ echo '<br><br>'.$originalFile;
    //~ $info = getimagesize($originalFile);
    //~ $mime = $info['mime'];

    //~ switch ($mime) {
            //~ case 'image/jpeg':
                    //~ $image_create_func = 'imagecreatefromjpeg';
                    //~ $image_save_func = 'imagejpeg';
                    //~ $new_image_ext = 'jpg';
                    //~ break;

            //~ case 'image/png':
                    //~ $image_create_func = 'imagecreatefrompng';
                    //~ $image_save_func = 'imagepng';
                    //~ $new_image_ext = 'png';
                    //~ break;

            //~ case 'image/gif':
                    //~ $image_create_func = 'imagecreatefromgif';
                    //~ $image_save_func = 'imagegif';
                    //~ $new_image_ext = 'gif';
                    //~ break;

            //~ default:
                    //~ throw new Exception('Unknown image type.');
    //~ }

    //~ $img = $image_create_func($originalFile);
    //~ list($width, $height) = getimagesize($originalFile);

    //~ $newHeight = ($height / $width) * $newWidth;
    //~ $tmp = imagecreatetruecolor($newWidth, $newHeight);
    //~ imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    //~ if (file_exists($targetFile)) {
      //~ unlink($targetFile);
    //~ }
    //~ $image_save_func($tmp, "$targetFile");
//~ }


function upload_file($upload_location, $allowed_file_type = '',$filename=''){
   //~ returns path of uploaded file
   //~ upload location relative to BASE_PATH
  $r = dirname(__FILE__);
  echo $r;
  if($_POST['uploading-form'] == 'upload-file-form'){
    if(empty($filename)){
      $name = basename($_FILES['file_field']['name']);
    } else {
      $name = $filename.'.' . str_ireplace('image/','',$_FILES['file_field']['type']);
    }
    $type = $_FILES['file_field']['type'];
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
    if($type == 'application/pdf' || $type == 'application/doc'){
      $is_document = true;
    } else {
      $is_document = false;
    }
    if($r==='' && !url_contains('edit_')){
      $r = dirname(__FILE__);
      $r = $r2;
    }

    if(($is_image && $allowed_file_type == 'image')
    || ($is_document && $allowed_file_type == 'document')
    || empty($allowed_file_type)){
      //~ sort out the relevant paths (Sacred)
      $uploaddir = $r.''.$upload_location;
      $uploadfile = $uploaddir .''. $name;
      //~ echo $uploadfile;
      //~ die();
      $path = BASE_PATH.$upload_location.'/'. $name;
      //~ echo '<br> Path is :'.$path; die();

      # ONSUBMIT
      if(isset($_FILES['file_field']) && !empty($_FILES['file_field'])){
        //~ print_r($_POST);print_r($_FILES); die();
        //~ $name = basename($_FILES['file_field']['name']);
        $move = move_uploaded_file($_FILES['file_field']['tmp_name'], $uploadfile);
        //~ echo $move; die();
          if($move ==1){
            //~ echo 'iseeeeee'; die();
            return $path;
          }
      }
    } else { status_message('error','File type not allowed!');  }
  }
}


function createDirIFNotExist($dir){
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }

}

function upload_file2($folder_name='', $allowed_file_type = array(),$file=''){

    if(isset($_FILES)){
      $file =  $_FILES['media_field'];
    }

    //file extention of file .[docx|jpeg|..]
    $file_extension = strrchr($file['name'], ".");
    //~ echo 'file extension is '.$file_extension;
    $dir_path = DIR_PATH . '/' . 'files' . '/'. $folder_name;
    // folder path [http|https]://localhost/projectname/files/uploadedfolder
    $upload_file_path_http = BASE_PATH . 'files' .'/' . $folder_name . '/' . $file['name'];
    // upload file path c://wamp/www/projectname/files/uploadedfolder/filename
    $upload_file_path = $dir_path . '/'. $file['name'];


    //check file Extension in list
    if(in_array($file_extension, $allowed_file_type)){
      //create dir if necessary.
      createDirIfNotExist($dir_path);
      //move upload file from temp directory
      $move = move_uploaded_file($file['tmp_name'], $upload_file_path);
      //~ echo ' '.$file['name'];
        if($move ==1){
          //~ echo 'iseeeeee'; die();
          return $upload_file_path_http;
        }
    } else {
        status_message('error', 'File type Not allowed!');

    }
}



function get_resized_image($image_path,$new_width){
  if(string_contains($image_path,BASE_PATH)){
    $img = array();
    $img['path_array'] = explode('/',$image_path);
    $img['dir_path'] = str_ireplace(BASE_PATH,DIR_PATH,$image_path);

    $img['target_image'] = array_pop($img['path_array']);
    $img['ext'] =  pathinfo($image_path, PATHINFO_EXTENSION);

    $img['dest'] = DIR_PATH.'resized-images/';
    createDirIFNotExist($img['dest']);
    $img['dest'] .= rtrim($img['target_image'],'.'.$img['ext']).'-w-'.$new_width.'px.'.$img['ext'];

    if(!file_exists($img['dest'])) {

      $img['info'] = getimagesize($img['dir_path']);

      //~ print_r($img);
      $mime = $img['info']['mime'];

      switch ($mime) {
              case 'image/jpeg':
                      $image_create_func = 'imagecreatefromjpeg';
                      $image_save_func = 'imagejpeg';
                      $new_image_ext = 'jpg';
                      break;

              case 'image/png':
                      $image_create_func = 'imagecreatefrompng';
                      $image_save_func = 'imagepng';
                      $new_image_ext = 'png';
                      break;

              case 'image/gif':
                      $image_create_func = 'imagecreatefromgif';
                      $image_save_func = 'imagegif';
                      $new_image_ext = 'gif';
                      break;

              default:
              throw new Exception('Unknown image type.');
      }

      $pic = $image_create_func($img['dir_path']);
      list($width, $height) = $img['info'];
      //~ echo 'Widthis :'. $width;

      $new_height = ($height / $width) * $new_width;
      $tmp = imagecreatetruecolor($new_width, $new_height);
      imagecopyresampled($tmp, $pic, 0, 0, 0, 0, $new_width, $new_height, $width, $height);


        $image_save_func($tmp, $img['dest']);
      //return destination file
      return str_ireplace(DIR_PATH,BASE_PATH,$img['dest']);
    } else {
      return str_ireplace(DIR_PATH,BASE_PATH,$img['dest']);
    }


  } else {
    return $image_path;
  }
}


function show_resized_image($image_path,$new_width){
  //usage
  //~ get_resized_image(BASE_PATH.'files/bg.jpg',200);
  echo get_resized_image($image_path,$new_width);

}


//~ echo 'end of core uploads.php file' ;
// in root/core/uploads.php
?>
