<?php
require_once('config.php');
$addon_home = $my_addon_name;
$_SESSION['addon_home'] = '<a href="' .ADDONS_PATH . $addon_home .
'" class ="home-link">'.str_ireplace('_', ' ', $addon_home ).'</a>';
$_SESSION['page_context'] = $addon_home;

function set_page_title() {
	$title_left = APPLICATION_NAME;
	
		if(isset($_GET['page_name']) && ($_GET['page_name'] !== 'home')){
			$title_right = 'page >'.$_POST['page_name'];
		}
    
    if(isset($_SESSION['page_context']) && empty($_POST['page_name']) && isset($_GET['page_name']) ){
			$title_right = $_SESSION['page_context'].' > '.$_GET['page_name'];
		}
    
    if(isset($_GET['page_name']) && ($_GET['page_name'] == 'home')){
			$title_right = 'page > '.$_POST['page_name'];
		}
    
    if(isset($_GET['contest_name'])){
			$title_right = $_SESSION['page_context'] .' > '.$_GET['contest_name'];
		}
    if(isset($_GET['fundraiser_name'])){
			$title_right = $_SESSION['page_context'] .' > '.$_GET['fundraiser_name'];
		}
    if(isset($_GET['project_name'])){
			$title_right = $_SESSION['page_context'] .' > '.$_GET['project_name'];
		}
			
	if(isset($title_left)) { 
		$title_tag = $title_left .' - ' . $title_right;
		} 
		else { $title_tag = "Wanni CMS" .' - ' .$title_right; }
	return $title_tag; 
}


?>

