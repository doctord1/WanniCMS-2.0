<?php

function show_in_bootsrap_carousel($carousel_name,$images_array){
	# $images should be an array of images
  echo '<div id="'.$carousel_name.'" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">';
    $img_count = $images_array['num_results'];
    $count = 0;
    while($count < $img_count){
      echo '<li data-target="#'.$carousel_name.'" data-slide-to="'.$count.'" class=""></li>';
      $count++;
    }
    echo '</ol>'; 
    echo '
    <div class="carousel-inner">';
    
    $active = 'active';
    if(!empty($images_array['result'])){
      foreach ($images_array['result'] as $image){ 
        echo '<div class="carousel-item '.$active.'">' ;        
        echo '<img class="d-block w-100" src="'.$image['path'].'" alt="slide">';
        if(!empty($image['caption'])){
          echo '
          <div class="carousel-caption d-none d-md-block">
            <h5>'.$image['caption'].'</h5>
            <p>'.$image['description'].'</p>
          </div>';
        }
        echo '</div>';
        $active = '';
      }
    } else if(empty($images) && is_home_page()){
        global $r;
        $dir= $r.'images/default-slideshow/';
        $dir = str_ireplace('regions/','',$dir);
        $images = scandir($dir);

      foreach ($images as $image){  
        if($image !== '.' && $image !== '..' && is_file($dir.$image)){  
          echo '
          <div class="carousel-item ';
          if($images[3] == $image){
            echo ' active';
          }
          echo '">' ; 
          echo '<img src="'.BASE_PATH.'images/default-slideshow/'.$image.'" data-thumb="'.$image.'" alt="" />';
          echo '</div>';
        }
      }
		}
    echo '
    <!-- Carousel nav -->
    <a class="carousel-control-prev left" href="#'.$carousel_name.'" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next right" href="#'.$carousel_name.'"
    data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>';
  echo '</div>';
	
  echo '</div>';
  
}


function upload_slideshow_pics(){ #NEEDS WORK
	
$submit =  $_POST['submit'];
$uploaddir = dirname(dirname(dirname(__FILE__))).'/slideshow/images/';
$uploadfile = $uploaddir . basename($_FILES['image_field']['name']);

# ONSUBMIT
if (isset($submit)){
   $type = $_FILES['image_field']['type'];

   if ($_SESSION['role'] ==='manager' || $_SESSION['role'] ==='admin'){
	   
	 $move = move_uploaded_file($_FILES['image_field']['tmp_name'], $uploadfile);

		if($move ==1){ 
			echo "<div class='success'>File is valid, and was successfully uploaded.\n</div>";
				
			} else { echo "<div class='alert'>Error : No file uploaded!\n</div>"; }
		
	}
	
}
	
	# UPLOAD FORM
	echo '<hr><div class=""><h2> Upload Files </h2><form action="'
	.$SERVER["PHP_SELF"] .'" method="post" enctype="multipart/form-data">
	<!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    <!-- Name of input element determines name in $_FILES array -->
	Send this file: <input type="file" size="500" name="image_field" value="">
	<input type="submit" name="submit" value="upload" class="submit">
	</form></div>';
#echo 'Here is some more debugging info:' .$_FILES['image_field']['error']; //testing

}

function show_uploaded_slides(){
	global $r;
	$dir= $r.'slideshow/images/';
	$images = scandir($dir);
	
	//do delete
	if(!empty($_GET['delete'])){
		$delete = trim(mysql_prep($_GET['delete']));
		$rm = unlink($dir.$delete);
		unlink($dir."large-size/".$delete);
		unlink($dir."medium-size/".$delete);
		unlink($dir."small-size/".$delete);
		
		if($rm){
			session_message("success", "File deleted successfully!");
			redirect_to(BASE_PATH.'slideshow');
			} else { status_message("error", "Unlink failed!");}
		}
	
	
	if(!empty($images)){
		echo '<div class="">';
    
    foreach ($images as $image){  
		if($image !== '.' && $image !== '..' && is_file($dir.$image)){  
		echo '<span><img src="'.BASE_PATH.'slideshow/images/'.$image.'" alt="" width="200" height="100" hspace="5" /></span>
		<span class="tiny-text"><a href="'.BASE_PATH.'slideshow/?delete='.$image.'">delete</a></span>';
		}
	}

	echo '</div>';
		}
	
}

	
 // end of slideshow functions file
 // in root/slideshow/includes/functions.php
?>
