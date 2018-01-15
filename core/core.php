<?php 
ob_start();
error_reporting(E_ERROR | E_ALL );
//~ require_once('session.php');

require_once('router.php');
require_once('title.php');
require_once('crud_functions.php');
$_SESSION['LAST_ACTIVITY'] = time();
$_SESSION['base_path'] = BASE_PATH;
$_SESSION['temp_container']='';

if(isset($_POST['destination'])){
  $destination = $_POST['destination'];
	echo "<script> window.location.replace('{$destination}') </script>";
	exit;
}else if(isset($_GET['destination'])){
  $destination = $_GET['destination'];
	echo "<script> window.location.replace('{$destination}') </script>";
	exit;
}


function install_core(){
  $sql['create WanniCMS'] = "CREATE DATABASE IF NOT EXISTS `roomshare` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci";
  $sql['drop core abuse'] = "DROP TABLE IF EXISTS `core_abuse`";
  $sql['create core abuse'] = "CREATE TABLE IF NOT EXISTS `core_abuse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reporter_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `response` varchar(150) NOT NULL,
  `responder_user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1";
  $sql['drop core addons'] = "DROP TABLE IF EXISTS `core_addons`";
  $sql['create core addons'] = "CREATE TABLE IF NOT EXISTS `core_addons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addon_name` varchar(150) NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1";
  $sql['drop core comments'] = "DROP TABLE IF EXISTS `core_comments`";
  $sql['create core comments'] = "CREATE TABLE IF NOT EXISTS `core_comments` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `path` varchar(250) NOT NULL,
  `parent_type` varchar(50) NOT NULL,
  `parent_id` int(65) NOT NULL,
  `parent_page_author` varchar(150) NOT NULL,
  `comment_author` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `created` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1";
  $sql['drop core currency'] = "DROP TABLE IF EXISTS `core_currency`";
  $sql['create core currency'] = "CREATE TABLE IF NOT EXISTS `core_currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exchange_rate` text NOT NULL,
  `retrieval_date` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1";
  $sql['insert into core currency'] = "INSERT INTO `core_currency` (`id`, `exchange_rate`, `retrieval_date`) VALUES
(1, '{\n  \"disclaimer\": \"Usage subject to terms: https://openexchangerates.org/terms\",\n  \"license\": \"https://openexchangerates.org/license\",\n  \"timestamp\": 1513209600,\n  \"base\": \"USD\",\n  \"rates\": {\n    \"AED\": 3.673097,\n    \"AFN\": 69.033535,\n    \"ALL\": 112.772999,\n    \"AMD\": 484.459839,\n    \"ANG\": 1.784998,\n    \"AOA\": 165.9235,\n    \"ARS\": 17.303,\n    \"AUD\": 1.310714,\n    \"AWG\": 1.786821,\n    \"AZN\": 1.6895,\n    \"BAM\": 1.665172,\n    \"BBD\": 2,\n    \"BDT\": 82.36534,\n    \"BGN\": 1.65582,\n    \"BHD\": 0.377229,\n    \"BIF\": 1755.267406,\n    \"BMD\": 1,\n    \"BND\": 1.350339,\n    \"BOB\": 6.859113,\n    \"BRL\": 3.310104,\n    \"BSD\": 1,\n    \"BTC\": 0.000060516512,\n    \"BTN\": 64.423471,\n    \"BWP\": 10.352,\n    \"BYN\": 2.03545,\n    \"BZD\": 2.009975,\n    \"CAD\": 1.281921,\n    \"CDF\": 1562.881563,\n    \"CHF\": 0.985518,\n    \"CLF\": 0.02388,\n    \"CLP\": 646.777985,\n    \"CNH\": 6.6077,\n    \"CNY\": 6.6196,\n    \"COP\": 2985.73975,\n    \"CRC\": 565.260435,\n    \"CUC\": 1,\n    \"CUP\": 25.5,\n    \"CVE\": 94.1,\n    \"CZK\": 21.6926,\n    \"DJF\": 178.97,\n    \"DKK\": 6.288625,\n    \"DOP\": 47.970705,\n    \"DZD\": 115.340212,\n    \"EGP\": 17.8795,\n    \"ERN\": 15.228279,\n    \"ETB\": 27.120382,\n    \"EUR\": 0.844828,\n    \"FJD\": 2.071754,\n    \"FKP\": 0.745784,\n    \"GBP\": 0.745784,\n    \"GEL\": 2.636276,\n    \"GGP\": 0.745784,\n    \"GHS\": 4.496023,\n    \"GIP\": 0.745784,\n    \"GMD\": 47.5,\n    \"GNF\": 9009.133333,\n    \"GTQ\": 7.3499,\n    \"GYD\": 206.150183,\n    \"HKD\": 7.80475,\n    \"HNL\": 23.56,\n    \"HRK\": 6.376794,\n    \"HTG\": 63.858,\n    \"HUF\": 265.846,\n    \"IDR\": 13568.807977,\n    \"ILS\": 3.5231,\n    \"IMP\": 0.745784,\n    \"INR\": 64.329582,\n    \"IQD\": 1183.477494,\n    \"IRR\": 35201.793807,\n    \"ISK\": 103.908402,\n    \"JEP\": 0.745784,\n    \"JMD\": 125.245,\n    \"JOD\": 0.709001,\n    \"JPY\": 112.769,\n    \"KES\": 103.2,\n    \"KGS\": 69.734397,\n    \"KHR\": 4083.333333,\n    \"KMF\": 418.65,\n    \"KPW\": 900,\n    \"KRW\": 1085.16,\n    \"KWD\": 0.302197,\n    \"KYD\": 0.833355,\n    \"KZT\": 333.035,\n    \"LAK\": 8263.8,\n    \"LBP\": 1504.687443,\n    \"LKR\": 152.057514,\n    \"LRD\": 125.486146,\n    \"LSL\": 13.547422,\n    \"LYD\": 1.359251,\n    \"MAD\": 9.4349,\n    \"MDL\": 17.216242,\n    \"MGA\": 3194.576613,\n    \"MKD\": 52.0695,\n    \"MMK\": 1349.799087,\n    \"MNT\": 2434.866458,\n    \"MOP\": 8.040513,\n    \"MRO\": 355.391236,\n    \"MUR\": 34.174,\n    \"MVR\": 15.409873,\n    \"MWK\": 726.11,\n    \"MXN\": 19.016498,\n    \"MYR\": 4.082967,\n    \"MZN\": 60.004931,\n    \"NAD\": 13.545587,\n    \"NGN\": 360.115424,\n    \"NIO\": 30.9,\n    \"NOK\": 8.317083,\n    \"NPR\": 103.074806,\n    \"NZD\": 1.424877,\n    \"OMR\": 0.384996,\n    \"PAB\": 1,\n    \"PEN\": 3.23255,\n    \"PGK\": 3.193584,\n    \"PHP\": 50.49,\n    \"PKR\": 108.674285,\n    \"PLN\": 3.56338,\n    \"PYG\": 5672.702124,\n    \"QAR\": 3.684999,\n    \"RON\": 3.913812,\n    \"RSD\": 100.823851,\n    \"RUB\": 58.5532,\n    \"RWF\": 851.92,\n    \"SAR\": 3.7506,\n    \"SBD\": 7.775606,\n    \"SCR\": 14.003609,\n    \"SDG\": 6.676036,\n    \"SEK\": 8.4104,\n    \"SGD\": 1.347295,\n    \"SHP\": 0.745784,\n    \"SLL\": 7660.273512,\n    \"SOS\": 574.843879,\n    \"SRD\": 7.458,\n    \"SSP\": 130.2634,\n    \"STD\": 20717.098928,\n    \"SVC\": 8.749901,\n    \"SYP\": 514.96999,\n    \"SZL\": 13.540168,\n    \"THB\": 32.502,\n    \"TJS\": 8.814964,\n    \"TMT\": 3.509961,\n    \"TND\": 2.510091,\n    \"TOP\": 2.30228,\n    \"TRY\": 3.815935,\n    \"TTD\": 6.68049,\n    \"TWD\": 30.024,\n    \"TZS\": 2239.9,\n    \"UAH\": 27.059102,\n    \"UGX\": 3596.35,\n    \"USD\": 1,\n    \"UYU\": 28.95634,\n    \"UZS\": 8032.6,\n    \"VEF\": 10.555762,\n    \"VND\": 22713.435659,\n    \"VUV\": 106.89821,\n    \"WST\": 2.547451,\n    \"XAF\": 554.170527,\n    \"XAG\": 0.06234235,\n    \"XAU\": 0.00079745,\n    \"XCD\": 2.70255,\n    \"XDR\": 0.709315,\n    \"XOF\": 554.170527,\n    \"XPD\": 0.00098091,\n    \"XPF\": 100.81474,\n    \"XPT\": 0.0011262,\n    \"YER\": 250.25,\n    \"ZAR\": 13.4671,\n    \"ZMW\": 9.951436,\n    \"ZWL\": 322.355011\n  }\n}', '2017-12-14')";
  $sql['drop core files'] = "DROP TABLE IF EXISTS `core_files`";
  $sql['create core files'] = "CREATE TABLE IF NOT EXISTS `core_files` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `large_path` tinytext NOT NULL,
  `medium_path` tinytext NOT NULL,
  `small_path` tinytext NOT NULL,
  `original_path` tinytext NOT NULL,
  `parent` varchar(150) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL,
  `destination_url` varchar(255) NOT NULL,
  `owner_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1";
  $sql['create core installed'] = "CREATE TABLE IF NOT EXISTS `core_installed` (
  `id` int(1) NOT NULL,
  `value` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1";
  $sql['insert into core installed'] = "INSERT INTO `core_installed` (`id`, `value`) VALUES
(1, 'yes')";
  $sql['drop core page'] = "DROP TABLE IF EXISTS `core_page`";
  $sql['create core page'] = "CREATE TABLE IF NOT EXISTS `core_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(150) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `child_page_number` int(3) NOT NULL DEFAULT '5',
  `page_type` varchar(150) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `created` varchar(50) NOT NULL,
  `last_updated` varchar(50) NOT NULL,
  `author` varchar(150) NOT NULL,
  `editor` varchar(150) NOT NULL,
  `allow_comments` varchar(3) NOT NULL,
  `path` varchar(255) NOT NULL,
  `show_author` varchar(3) NOT NULL DEFAULT 'yes',
  `show_in_streams` varchar(3) NOT NULL DEFAULT 'yes',
  `enrollment_fee` int(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1";
  $sql['drop core page reactions'] = "DROP TABLE IF EXISTS `core_page_reactions`";
  $sql['create core page reactions'] = "CREATE TABLE IF NOT EXISTS `core_page_reactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `reactor_id` int(11) NOT NULL,
  `amount` int(6) NOT NULL,
  `reason` varchar(150) NOT NULL,
  `date` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=latin1";
  $sql['drop core permisssions'] = "DROP TABLE IF EXISTS `core_permissions`";
  $sql['create core permissions'] = "CREATE TABLE IF NOT EXISTS `core_permissions` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `action` varchar(150) NOT NULL,
  `addon_name` varchar(150) NOT NULL,
  `allowed_roles` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1";
  $sql['drop core roles'] = "DROP TABLE IF EXISTS `core_roles`";
  
   
   $sql['create core roles'] = "CREATE TABLE IF NOT EXISTS `core_roles` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1";
  $sql['insert into core roles'] = "INSERT INTO `core_roles` (`id`, `role`) VALUES
    (1, 'anonymous'),
    (2, 'authenthicated'),
    (3, 'admin')";
   $sql['Drop core user'] = "DROP TABLE IF EXISTS `core_user`";
   $sql['create core_user'] = "CREATE TABLE IF NOT EXISTS `core_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(12) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `created_time` varchar(50) NOT NULL,
  `last_login` varchar(50) NOT NULL,
  `login_count` int(11) NOT NULL DEFAULT '2',
  `logged_in` varchar(3) NOT NULL,
  `phone` varchar(14) NOT NULL,
  `country_name` varchar(255) NOT NULL,
  `region_name` varchar(255) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `last_login_ip` varchar(15) NOT NULL,
  `preferred_currency` varchar(3) NOT NULL,
  `site_funds_amount` int(11) NOT NULL DEFAULT '0',
  `role` varchar(50) NOT NULL DEFAULT 'authenticated',
  `account_type` varchar(10) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `picture_thumbnail` varchar(255) NOT NULL,
  `secret_question` varchar(150) NOT NULL,
  `secret_answer` varchar(150) NOT NULL,
  `status` varchar(15) NOT NULL,
  `bank_account_no` varchar(255) NOT NULL,
  `bank_name` varchar(150) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1";
   $sql[] = "INSERT INTO `core_user` (`id`, `user_name`, `password`, `email`, `created_time`, `last_login`, `login_count`, `logged_in`, `phone`, `country_name`, `region_name`, `ip_address`, `last_login_ip`, `preferred_currency`, `site_funds_amount`, `role`, `account_type`, `picture`, `picture_thumbnail`, `secret_question`, `secret_answer`, `status`, `bank_account_no`, `bank_name`, `full_name`) VALUES
(1, 'system', '3ec1483c41072d8e50b72a9147e7fecca2143355', '', '2015-09-23 07:12:31', '1503592655', 7, 'no', '07016566148', '', '', '', '', '', 118270, 'admin', 'Bronze', '', '', '', '', 'subscribed', '', '', '')";
  
  foreach($sql as $key => $value){
    $q = query_db("{$value}","Could not {$key}! ");
  }
  
	if($q){
		status_message('success','Wanni-CMS Core installed successfully!');
  }
}


	
$q1 = mysqli_query($GLOBALS['___mysqli_ston'],
		 "CREATE TABLE IF NOT EXISTS `core_installed` (
  `id` int(1) NOT NULL,
  `value` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1");
$q1 = query_db("SELECT `value` FROM `core_installed`","Could not check if WanniCMS is installed");
$result = $q1['result'][0];

if($result['value'] ==='no' || empty($result)){
	install_core();
	 $destination = BASE_PATH;
	 header("Location: $destination");exit;
	 #echo "<script> window.location.replace('{$destination}') </script>";
}
		

		
function load_system(){
	$core = dirname(__FILE__).'/'; 
	$addons = dirname(dirname(__FILE__)).'/'.'addons/';
  //~ echo $core.': that was core<br>';
  //~ echo $addons. '<br>: that was addons';
 
	foreach (glob($core.'*.php') as $file){
		//~ echo $file .'<br>';
		require_once $file;
  }
  
  foreach (glob($addons.'*.php') as $file){
		//~ echo $file .'<br>';
    if(!string_contains($file,'index.php')){
      require_once $file;
    }
  }
	
}load_system();	

function load_stylesheets(){
   $styles_dir = dirname(dirname(__FILE__)).'/'.'styles/';
    foreach (glob($styles_dir.'*.css') as $stylesheet){
    $stylesheet = str_ireplace($styles_dir,'',$stylesheet);
      echo '<link href="'.BASE_PATH.'/styles/'.$stylesheet.'" rel="stylesheet" type="text/css">';
    }
  }
function load_scripts(){
   load_jquery();
  $scripts_dir = dirname(dirname(__FILE__)).'/'.'scripts/';
    foreach (glob($scripts_dir.'*.js') as $script){
      $this_script = str_ireplace($scripts_dir,'',$script);
      echo '<script src="'.BASE_PATH.'/scripts/'.$this_script.'"></script>';
    }
  }

function install_required(){
	
	$r = dirname(dirname(__FILE__)); #do not edit
	$r = $r .'/';
	#echo $r;
	$core = array('page','blocks','sections','funds_manager','menus','libraries',
					'scripts','styles','regions','uploads','admin','addons','config',
					'slideshow','user','includes','documentation');
	$list = array();
	
	$exception = array('libraries','scripts','styles','regions','addons','config','includes');
	
	#GET OPTIONAL ADDONS				
	if ($handle = opendir($r)) {
		while (false !== ($addon_folder = readdir($handle))) {
			if ($addon_folder != "." 
				&& $addon_folder != ".." 
				&& $addon_folder != ".git"
				&& $addon_folder != "admin"
				&& is_dir($addon_folder)) {
					if(in_array($addon_folder,$core)){
						include_once($r.$addon_folder."/details.php");
						if(!in_array($addon_folder,$exception)){
							$required = $r.$addon_folder."/includes/functions.php";
						
							$q = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `addons`
							(`id`, `addon_name`, `description`, `required_files`, `status`, `version`, `core`) 
							VALUES ('','{$addon_folder}','{$my_addon_desc}','{$required}','1','{$version}','yes')");
						}
					}
			}
		}
				closedir($handle);
			}
	}



function status_message($class, $string){
	
	if (isset($_GET['class'])){
		
		$class = $_GET['class'];
	}
	if (isset($_GET['status_message'])){
		
		$string = $_GET['status_message'];
	}
	
	$alert = "<div class='alert'>";
	$success = "<div class='success'>";
	$error = "<div class='error'>";
	
	if ($class === "alert"){
	$message = $alert .$string ."</div>";
	
	} else if ($class === "success"){
	$message = $success .$string ."</div>";	
		
	} else if ($class === "error"){
	$message = $error .$string ."</div>";	
		
	} else { $message ="";}
	
	echo $message ;
	
}



// * # SHOW THE TOP BAR WITH LINKS TO HOMEPAGE AND ADMIN PAGE
function show_top_bar(){
	
	$bar = '<nav class="top-bar full-width" id="navbar">';
	//<a href="#"><span class="home-link" id="toggle-sidebar"><img src="'.BASE_PATH.'uploads/files/default_images/menu24.png"></span></a>
	$bar .='&nbsp;<a href="' .BASE_PATH . '" class ="home-link"><img src="'.BASE_PATH .'uploads/files/default_images/Home-32.png">&nbsp;</a>&nbsp;' ;
	
	
	if(isset($_SESSION['addon_home'])){
	$bar = $bar . ' &nbsp;'.$_SESSION['addon_home'];	
	}
	
	
	if(isset($_SESSION['user_id'])){
	
	$bar .= 
	'&nbsp;&nbsp;<div class="greet"><a href="'.BASE_PATH .'user?user='.$_SESSION['username'].'">' .$_SESSION['username'] .
	'</a>&nbsp|<a href="' .BASE_PATH .'index.php/user/logout.php" class ="home-link">LOGOUT</a></div>' ;
	}
	
	$bar = $bar .'</nav>';
	echo $bar;
}

# Selecting styles
function select_style() {// Largely INCOMPLETE
  $query = mysqli_query($GLOBALS["___mysqli_ston"], "select stylesheet from config") or die("Could not select style!" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  $result = mysqli_fetch_array($query);   
  //uncomment the next line to test if theme selection works!
  # echo $result['stylesheet'] ."Theme selection successful!!";
  
  $style = '<link href="'. BASE_PATH .'styles/'. strtolower(STYLESHEET) .'" rel="stylesheet">';
  
  return $style;
}



function query_string_in_url(){
	$url = $_GET;
	if(!empty($url)){
		return true;
	} else {
		return false;
		}
}



function start_addons_page(){

	$page_title = set_page_title();
	
	start_page();
	
	if(addon_is_available('funds_manager')){
		//get_user_funds();
		}
	
	$toolbar = show_top_bar();
	echo $toolbar; 
	remove_file();
	
	}	

function start_addon_config_page(){
	
	echo '
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title> <?php echo $page_title ?> </title>
<link href="'.STYLESHEET.'" rel="stylesheet">
</head>
<body>
 
<!-- HEADER REGION START -->
<section class="holder">
	<section class="top-bar"> <a href="' .BASE_PATH . '" class ="home-link">Home - ' .APPLICATION_NAME .'</a>
		<div class="back-to-control"><a href="'.ADMIN_PATH.'">GO TO ADMIN </a> 
		</div>
	</section>
</section>';
	}

function show_config_form_buttons(){
	echo 
'<form enctype="multipart/form-data" method="POST" action="'. $_SERVER["PHP_SELF"] . '">' .
'<input type="hidden" name="status" value="1">' .
'<input type="submit" name="activate" value="ACTIVATE" class="activate-button">';

echo 
'<input type="hidden" name="status" value="0">' .
'<input type="submit" name="deactivate" value="DEACTIVATE" class="deactivate-button">';

echo 
'<input type="submit" name="update" value="UPGRADE" class="pull-right deactivate-button">';

echo 
'<input type="submit" name="uninstall" value="UNINSTALL" class="uninstall-button"></form><hr></p>';

// CONFIG ENDS HERE

	}


function is_home_page(){
  $url = $_SESSION['current_url'];
  if($_SESSION['current_url'] == BASE_PATH){
    return true;
	} else { return false; } 
	
}


function add_ckeditor(){	
	echo '<script type="text/javascript" src="'. BASE_PATH .'libraries/ckeditor/ckeditor.js"></script>
	<script>
                // Replace the <textarea id="content-area"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( "#content-area" );
            </script>';
}

function show_more_button($current_page = ''){
	
	$url = $_SESSION['current_url'];
	
	if(!empty($_GET['start_from']) && is_numeric($_GET['start_from'])){
		$number_holder = trim(mysql_prep($_GET['start_from']));
	} else { $number_holder = 0; }
	
	echo "<div class='show-more'>
		<form method='post' action='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."'>".
		'Show more <input type="number" class="small-input-box" name="show_more" size ="3" value="11">'.
		' Starting from <input type="number" name="start_from" value="'.$number_holder.'">
		<input type="submit" name="submit" value="show older pages" class="button-primary">'.
		"<input type='submit' name='clear_page_list_values' value='reset'>
		</form></div>";
	
}

function show_more_execute($parent,$number_holder=''){
	# Executes the action of show_more_button()
	
	if(isset($_POST["{$parent}_list_limit"])){
		$post_limit = $_POST["{$parent}_list_limit"];
	} else { $post_limit = 10;}
	if(isset($_POST["{$parent}_list_number_holder"])){	
		$step = $_POST["{$parent}_list_number_holder"];
	} else{ $step = 0; }
	
	if(isset($_POST["clear_{$parent}_list_values"])){
			unset($_POST);
			$number_holder = '';
			$post_limit = 10;
			$step = 0;
			}	
			
		$limit = "LIMIT ". $step .", ".$post_limit;
		$number_holder = $post_limit + $step;	
	
	$output = array('parent'=>$parent,'limit'=>$limit, 'number_holder'=>$number_holder);
	return $output;
	}

function add_nicedit_editor(){
	
echo '<script src="'.BASE_PATH.'libraries/nicedit/nicEdit.js" type="text/javascript"></script>
 ';

echo"
<script type='text/javascript'>

function addArea() { 
	area = new nicEditor({
		iconsPath : '".BASE_PATH."libraries/nicedit/nicEditorIcons.gif',
		buttonList : ['bold','italic','underline','strikeThrough','left','center','right','ol','ul','strikethrough',
		'image','upload','link','unlink','image']}).panelInstance('content-area');
		
		}
function removeArea() {
area.removeInstance('content-area');}
</script>";
	
}

function add_tinymce_editor(){
	//$is_mobile = check_user_agent('mobile');
	
	//#Local tinymce 
	////echo '<script type="text/javascript" src="'. BASE_PATH .'libraries/tinymce/js/tinymce/tinymce.min.js">';
	#'<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
//	echo'</script>
	//<script type="text/javascript">
//    selector: "textarea#content-area"
//	});
//	</script>';
	
}

function link_to($path='', $name='', $class='', $type='',$extra_get=''){
	//valid types are 'button' or 'link'
	if($type !== '' && $type === 'button'){
		$name = "<button class='{$class}'>{$name}</button>";
		$pre ='';
		$post='';
		}
	else if($type === '' || $type === 'link'){
		$name = $name;
		$pre = "<div class='{$class}'>";
		$post = "</div>";
		}
	if($path === 'self'){
		$path = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] .$extra_get;
		}
	echo $pre."<a href='{$path}'>{$name}</a>".$post;
}

function do_header() {	
	echo '<span id="top"></span>';
   //~ echo '<a href="#top">
   //~ <span class="img-circular teal padding-10 glyphicon glyphicon-menu-up pull-right back-to-top"></span>
   //~ </a>';
   
	if(!is_logged_in()){
		echo '<div class="pull-right margin-10"><a href="'.BASE_PATH .'user/?action=login"><div class="btn btn-primary">Login </div></a>' .
			'<a href="' .BASE_PATH .'user/?action=register"><div  class="btn btn-success">Signup </div></a><br>
      </div>' ;	
		} 
   
	//~ show_logo(); # Displays logo	
	if(is_logged_in()){
		show_welcome_message(); 
	//	$output = get_region_blocks('header');
	//	if($output !== ''){
	//		echo $output;
	//		}
	}
}

function do_highlight() {
//	if(is_logged_in()){	
//	$output = get_region_blocks('highlight');
//	if($output !== ''){
//		echo $output;
//		}
//	}
}

function do_three_column_region(){
//	$column1 = get_region_blocks('3_column_column_1','three-column-title','three-column-even');
//	$column2 = get_region_blocks('3_column_column_2','three-column-title','three-column-even');
//	$column3 = get_region_blocks('3_column_column_3','three-column-title','three-column-even');

	$region = '';
//	if($column1 !== ''){ $region = $region .'<div class="floating-container">'. $column1 .'</div>'; }
//	if($column2 !== ''){ $region = $region .'<div class="floating-container">'. $column2 .'</div>';  }
//	if($column3 !== ''){ $region = $region .'<div class="floating-container">'. $column3 .'</div>'; }
	
//	if($region !== ''){ echo $region;}
	
	}

function do_main_content(){
	//if(is_logged_in()){
	//	$output = get_region_blocks('main content');
	//	if($output !== ''){
	//		echo $output;
	//		}
	//}
}

function do_ads(){
	//$output = get_region_blocks('ads');
	//if($output !== ''){
	//	echo $output ;
	//	}		
}

function do_left_sidebar(){
//	if(is_logged_in()){
  echo '<div class="sweet_title">Menus</div>';
 // echo '<div class="padding-10">';
  //get_top_menu_items();
  echo '</div>';
//	$output = get_region_blocks('left sidebar');
//	if($output !== ''){
//		echo $output;
//		}
  
//	}
  
}

function do_right_sidebar(){
//	if(is_logged_in()){
//	$output = get_region_blocks('right sidebar');
//	if($output !== ''){
	//	echo $output;
	//	}
//	if($_GET['page_name'] !=='home' && !url_contains('user')){
//		}
//		echo '</div>';
//	}
}

function do_top_right_sidebar(){
	
	//$output = get_region_blocks('top right sidebar');
//	if($output !== ''){
//		echo $output;
	//	}
//	if($_GET['page_name'] !=='home' && !url_contains('user')){
		
//		}
//		echo '</div>';
}

function do_footer(){
	unset($_SESSION['status_message']);
  
  echo '<p align="center">&copy; '.date('Y').' '.APPLICATION_NAME.' - All rights reserved. </p>';
	 
	//~ add_nicedit_editor();
		
	if(url_contains('messaging/?mid')){
		echo '<script type="text/javascript">
		var myVar;    
		var url = BasePath + "addons/messaging/new-pings.php?_=" + Math.random();
		function showNewMessages(){
			$("#new-messages").load(url +" #new-pings").fadeIn("slow");
			myVar = setTimeout(showNewMessages, 10000);
		}
		function stopFunction(){
			clearTimeout(myVar); // stop the timer
		}
		$(document).ready(function(){
			showNewMessages();

		});
		</script>';
		}
    
   //~ echo '<!-- Go to www.addthis.com/dashboard to customize your tools --> <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-583a9f4c273aaaed"></script>  '.
    //~ "<div class='text-center'>Powered by - Wanni CMS</div>" .
	   //~ "</div>";
     
	load_scripts();
	load_bootstrap();
  
  
    //5. Close connection
	# do this at the end of the page
	if(isset($connection)) {
	((is_null($___mysqli_res = mysqli_close($connection))) ? false : $___mysqli_res);
		}	   
}

function show_logo(){
	$logo = "<div class='logo'>" .'<a href="'. BASE_PATH.'"><img src="'.BASE_PATH.'uploads/files/default_images/logo.png"></a>
  </div>';
	echo $logo;
	}
	
function show_welcome_message(){
	echo "<div class='welcome-message hidden'>" .WELCOME_MESSAGE ." v.".SITE_VERSION."</div>";
	}

function show_application_name(){
	echo "<div class='application-name'>". APPLICATION_NAME ."</div>";
	}

function string_contains($haystack='', $needle='') {

	//check for needle in haystack
	 $lookup = strpos($haystack, $needle);

	 
	 //If string is found, set the value of found_context
	if($lookup !== false) {
		return true; 
	}
	
	//If not found, set UNSET the value of found_context
	else {return false; }
 }

function register_update($addon,$function,$version){
	if(addon_is_active($addon) || $addon == 'system'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `updates`(`id`, `type`, `function`, `version`, `status`) 
		VALUES ('0','{$addon},'{$function}','{$version}','')") 
		or die("Problems registering update ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query ){
			status_message('alert','Update registered');
			}
		}
	}

function update($addon=''){
	if($addon == ''){
		$query =mysqli_query($GLOBALS["___mysqli_ston"], "SELECT function FROM updates WHERE version > '{SITE_VERSION}'");
		
		while($result = mysqli_fetch_array($query)){
			eval($result['function']);
			}
		} else {
			if(addon_is_active($addon)){
				$query =mysqli_query($GLOBALS["___mysqli_ston"], "SELECT function FROM updates WHERE addon='{$addon}' AND version > '{SITE_VERSION}'");
		
				while($result = mysqli_fetch_array($query)){
					eval($result['function']);
					}
				}
			}
	}

function better_strip_tags( $str, $allowable_tags = '<a><p><br><em><strong><h1><h2><h3><ul><del><strikethrough><blockquote>', $strip_attrs = false, $preserve_comments = false, callable $callback = null ) {
 // Features:
//* allowable tags (as in strip_tags),
//* optional stripping attributes of the allowable tags,
//* optional comment preserving,
//* deleting broken and unclosed tags and comments,
//* optional callback function call for every piece processed allowing for flexible replacements.

  
  $allowable_tags = array_map( 'strtolower', array_filter( // lowercase
      preg_split( '/(?:>|^)\\s*(?:<|$)/', $allowable_tags, -1, PREG_SPLIT_NO_EMPTY ), // get tag names
      function( $tag ) { return preg_match( '/^[a-z][a-z0-9_]*$/i', $tag ); } // filter broken
  ) );
  $comments_and_stuff = preg_split( '/(<!--.*?(?:-->|$))/', $str, -1, PREG_SPLIT_DELIM_CAPTURE );
  foreach ( $comments_and_stuff as $i => $comment_or_stuff ) {
    if ( $i % 2 ) { // html comment
      if ( !( $preserve_comments && preg_match( '/<!--.*?-->/', $comment_or_stuff ) ) ) {
        $comments_and_stuff[$i] = '';
      }
    } else { // stuff between comments
      $tags_and_text = preg_split( "/(<(?:[^>\"']++|\"[^\"]*+(?:\"|$)|'[^']*+(?:'|$))*(?:>|$))/", $comment_or_stuff, -1, PREG_SPLIT_DELIM_CAPTURE );
      foreach ( $tags_and_text as $j => $tag_or_text ) {
        $is_broken = false;
        $is_allowable = true;
        $result = $tag_or_text;
        if ( $j % 2 ) { // tag
          if ( preg_match( "%^(</?)([a-z][a-z0-9_]*)\\b(?:[^>\"'/]++|/+?|\"[^\"]*\"|'[^']*')*?(/?>)%i", $tag_or_text, $matches ) ) {
            $tag = strtolower( $matches[2] );
            if ( in_array( $tag, $allowable_tags ) ) {
              if ( $strip_attrs ) {
                $opening = $matches[1];
                $closing = ( $opening === '</' ) ? '>' : $closing;
                $result = $opening . $tag . $closing;
              }
            } else {
              $is_allowable = false;
              $result = '';
            }
          } else {
            $is_broken = true;
            $result = '';
          }
        } else { // text
          $tag = false;
        }
        if ( !$is_broken && isset( $callback ) ) {
          // allow result modification
          call_user_func_array( $callback, array( &$result, $tag_or_text, $tag, $is_allowable ) );
        }
        $tags_and_text[$j] = $result;
      }
      $comments_and_stuff[$i] = implode( '', $tags_and_text );
    }
  }
  $str = implode( '', $comments_and_stuff );
  return $str;
}

function strip_non_alphanumeric( $string ) {
	return preg_replace( "/[^a-z0-9]/i", "", $string );
}

function parse_text_for_output($string){ // Should handle the formatting and output of text
		$string = urldecode(better_strip_tags($string));
		
		$pattern = '/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)(\.png|\.jpg|\.gif|\.jpeg)+/';
		//$ret = preg_replace(urldecode($pattern),'PIC_HERE',$string);
		//echo '<img src="'.$ret.'" width="45%"/>';
		
		if(preg_match_all($pattern,$string,$matches)){
			foreach($matches[0] as $match){	
			$match1 = str_ireplace('+','%2B',$match);
			//echo $match;
			$string = str_ireplace($match,"<a href='".$match1."' rel='prettyPhoto[gen_gal]'>".'<img class="inline-block thumbnail img-responsive" src="'.$match1.'" ></a>',$string);
			
			}
		}	
		
		$pattern = '/(?<!\S)@\w+(?!\S)/';
		// Explanation: This will match any word containing alphanumeric characters, starting with "@." 
		// It will not match words with "@" anywhere but the start of the word.
		if(preg_match_all($pattern,$string,$matches)){
			foreach($matches[0] as $match){
			$changed_match = str_ireplace($match, "<a href='".BASE_PATH."user/?user=".str_ireplace('@','',$match)."'>{$match}</a>", $match);
			$new_string = preg_replace("/{$match}/", $changed_match, $string);
			$new_string = str_ireplace('&amp;','&',$new_string);
			$string = $new_string;
			}
			
			
		//$content = preg_replace('$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', ' <a href="http://$1" target="_blank">$1</a> ', $new_string."");
		//$content = preg_replace('$(www\.[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', '<a target="_blank" href="http://$1">$1</a> ', $content."");
	
		}
		if(addon_is_active('hashtags')){
		$hashtag = '/(?<!\S)#\w+(?!\S)/';
		// Explanation: This will match any word containing alphanumeric characters, starting with "#." 
		// It will not match words with "#" anywhere but the start of the word.
		
		//print_r($matches[0]);
			if(preg_match_all($hashtag,$string,$matches)){
				foreach($matches[0] as $match){
				$changed_match = str_ireplace($match, "<a href='".ADDONS_PATH."hashtags?hashtag=".str_ireplace('#','',$match)."'>{$match}</a>", $match);
				$new_string = preg_replace("/{$match}/", $changed_match, $string);
				$new_string = str_ireplace('&amp;','&',$new_string);
				$string = $new_string;
				}
			}
		}
	$string = convertYoutube($string); 
	$string = str_ireplace('https://player.vimeo.com/video/','https://vimeo.com/',$string);

	$string = preg_replace_callback('#https://vimeo.com/\d*#', function($string) {  return convertVimeo($string[0]);
}, $string);

	if(string_contains($string,'www.') 
	|| string_contains($string,'http://') 
	|| string_contains($string,'https://')){
		$content =  preg_replace('$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', ' <a href="$1" target="_blank">$1</a> ', $string);
		$content = preg_replace('$(www\.[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', '<a target="_blank" href="http://$1">$1</a> ', $content);
		$string = $content;
		}
	
		
			
	
	//$string = str_ireplace('<a href="<iframe width="','',$string);
	
	unset($matches);
	return html_entity_decode($string);
	
}

function convertVimeo($string,$width='')
{
//extract the ID
if(preg_match(
        '/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/',
        $string,
        $matches
    ))
    {

//the ID of the Vimeo URL: 71673549 
$id = $matches[2];  

//set a custom width and height
if($width !== ''){
		$height = $width - 105;
		} else {
			$width = 420;
			$height = 315;
			}    
$vid ='<div class="margin-3 col-xs-12 col-md-12 embed-responsive embed-responsive-4by3">
<iframe class="embed-responsive-item" width="'.$width.'" height="'.$height.'" src="http://player.vimeo.com/video/'.$id.'?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=ffffff" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div><br>';
 
 return $vid;  
   }
}

function convertYoutube($string='', $width='') {
	if($width !== ''){
		$height = $width - 105;
		} else {
			$width = 420;
			$height = 315;
			}
	$output = preg_replace(
		"/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
		"<div class='margin-3 col-xs-12 col-md-12 embed-responsive embed-responsive-4by3'><iframe class='embed-responsive-item' width=\"{$width}\" height=\"{$height}\" src=\"http://www.youtube.com/embed/$2\" target='blank' allowfullscreen></iframe></div>",
		$string
	);
	return $output;
}

function show_timeago($time){
  $time = strtotime($time);
  $elapsed_time = time() - $time;

    if ($elapsed_time < 1) {
        echo '0 secs';
    }

    $a = array(
        1 => 'sec',
        60 => 'min',
        60 * 60 => 'hour',
        24 * 60 * 60 => 'day',
        30 * 24 * 60 * 60 => 'month',
        12 * 30 * 24 * 60 * 60 => 'year',
    );

    foreach ($a as $secs => $text) {
        $d = $elapsed_time / $secs;
        if ($d >= 1) {
          $r = round($d);
          $out = " <span class='tiny-text text-muted pull-right'> - " . $r . ' ' . $text . ($r > 1 ? 's' : '') . ' ago </span>';
        }
    }
    echo $out;
  }

function time_elapsed($time) {
    $time = strtotime($time);
    echo $time;
    $elapsed_time = time() - $time;
    echo '<br>Time now: '.time(). '<br>';
    echo $elapsed_time;

    if ($elapsed_time < 1) {
        return '0 seconds';
    }

    $a = array(12 * 30 * 24 * 60 * 60 => 'year',
        30 * 24 * 60 * 60 => 'month',
        24 * 60 * 60 => 'day',
        60 * 60 => 'hour',
        60 => 'min',
        1 => 'sec'
    );

    foreach ($a as $secs => $text) {
        $d = $elapsed_time / $secs;
        if ($d >= 1) {
            $r = round($d);
            return " - " . $r . ' ' . $text . ($r > 1 ? 's' : '') . ' left';
        }
    }
}

function get_session(){
echo "<script type='text/javascript'>
	var SessionUser = '{$_SESSION['username']}';
	";
	$basepath = str_ireplace('http://', '',$_SESSION['base_path']) ;
echo "var BasePath = '{$_SESSION['base_path']}';
</script>";
}
//~ get_session();

function show_with_editor($string, $rows=''){
	if($rows===''){$rows = 5;}
	echo '<a class="add-nicedit" onclick="addArea();">[ Show Editor]</a> &nbsp&nbsp <a class="remove-nicedit"  onclick="removeArea();">[ Hide Editor ]</a>
<br><textarea name="content" id="content-area" rows="'.$rows.'">'  .urldecode($string) .'</textarea>' ;
	
	}
   
 function mysql_escape_gpc($dirty)
{
    if (ini_get('magic_quotes_gpc'))
    {
        return $dirty;
    }
    else
    {
        return ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dirty) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
    }
}  

 	
function no_configurable_settings(){
	status_message('alert','This addon has no configuration');
}

function show_addon_config_path(){ // to be used only on addon index pages
	if(is_admin()){
		echo "<p align='center'><a href='".$_SESSION['current_url']."config'><button>Configure addon</button></a></p>";
		}
	}
  
function is_mobile(){
  if(check_user_agent('mobile')){
    //~ echo 'is mobile';
    return true;
  } else {
    return false;
  }
}
 
function check_user_agent( $type ='' ) {
	 
 # USER-AGENTS
	
 
	$user_agent = strtolower ( $_SERVER['HTTP_USER_AGENT'] );
	if ( $type == 'bot' ) {
			// matches popular bots
			if ( preg_match ( "/googlebot|adsbot|yahooseeker|yahoobot|msnbot|watchmouse|pingdom\.com|feedfetcher-google/", $user_agent ) ) {
					return true;
					// watchmouse|pingdom\.com are "uptime services"
			}
	} else if ( $type == 'mobile' ) {
			// matches popular mobile devices that have small screens and/or touch inputs
			// mobile devices have regional trends; some of these will have varying popularity in Europe, Asia, and America
			// detailed demographics are unknown, and South America, the Pacific Islands, and Africa trends might not be represented, here
	if ( preg_match ( "/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent ) ) {
					// these are the most common
					return true;
				}
	}else if ( $type == 'opera mini' ) {
			// matches popular mobile devices that do not support ajax
			if ( preg_match ( "/opera mini/", $user_agent ) ) {
					// these are the most common
					return true;
				}
	} else if ( $type == 'browser' ) {
			// matches core browser types
			if ( preg_match ( "/mozilla\/|opera\/|safari\/", $user_agent ) ) {
					return true;
			}
	} else if ( preg_match ( "/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent ) ) {
					// these are less common, and might not be worth checking
					return true;
			}
	
	return false;
	
	/** HOW TO USE
	 * 
	 * <?php $ismobile = check_user_agent('mobile'); 
	 * if($ismobile) {
	 * return 'yes';
	 * } else{
	 * return 'no';
	 * }
	 * ?>
	 **/
       
}
  
function get_addons_list(){
	 $addons_number = 0;
	 if($_SESSION['role']==='admin'){
		$result = mysqli_query($GLOBALS["___mysqli_ston"], "Select * FROM addons ") or die("Cannot get addons List!"). ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		
		echo "<div class='clear'><h1>Registered Addons</h1><hr>";
		while($row = mysqli_fetch_array($result)){
			$clean_name = str_ireplace('_',' ',$row['addon_name']);
			$addons_number++;
			if($row['core']==='yes'){
				echo "{$addons_number} &nbsp<a href='" .BASE_PATH .$row['addon_name'] ."'><big>" .
				ucfirst($clean_name) ."</big></a> | '" .$row['description'] ."' | " .
				"<a href='" .BASE_PATH .$row['addon_name'] ."/config'> Configure </a> <br><hr>";
			} else {
				echo "{$addons_number} &nbsp<a href='" .ADDONS_PATH .$row['addon_name'] ."'><big>" .
				ucfirst($clean_name) ."</big></a> | '" .$row['description'] ."' | " .
				"<a href='" .ADDONS_PATH .$row['addon_name'] ."/config'> Configure </a> <br><hr>";
				}
		} echo '</div>';
	} else { deny_access(); }
}
 
function mysql_prep($string){
	
	$value = htmlentities($string);
	$value = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $value) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$value = htmlspecialchars($string);
	$value = str_ireplace("'","&#39;",$value);
	return $value;
}

function activity_record(
	
	$parent_id = '',
	$actor='',
	$action='',
	$subject_name='',
	$actor_path='',
	$subject_path='',
	$date ='',
	$parent=''
	){
	if($date === ''){$date= date('c');}	
	if($parent ==='project' 
	|| $parent === 'task'
	|| $parent === 'suggestion'){
		$parent = 'project_manager';
		}
	if($parent_id ==''){ $parent_id = 0; }
	
		if(!is_admin()){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `activity`(`id`,`parent_id`,`actor`,`action`,`subject_name`,`actor_path`,`subject_path`,`date`,`parent`) 
		VALUES ('0','{$parent_id}','{$actor}','{$action}','{$subject_name}','{$actor_path}','{$subject_path}','{$date}','{$parent}')")
		or die("Failed to insert activity" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		}
	
	}

	
function show_activity($parent=''){
	
	
	//DELETES ACTIVITY
	if(isset($_GET['activity_delete']) && ($_SESSION['control'] == $_GET['control'])){
		$id = $_GET['activity_delete'];
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `activity` WHERE `id`='{$id}'") or die('Failed to delete activity' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		}
	
	if($parent ===''){
		
		if(isset($_GET['fundraiser_name'])){
			$parent = 'fundraiser';
		} else if(isset($_GET['contest_name'])){
			$parent = 'contest';
		} else if(isset($_GET['project_name'])){
			$parent = 'project';
		}
	}
	# activity_limit routing
	
	if(!empty($_POST['activity_parent'])){
		$_SESSION['activity_parent'] = $_POST['activity_parent'];
		}
		
	if(isset($_POST['activity_parent_name_holder'])){
		$_SESSION["activity_parent_name_holder"] = $_POST['activity_parent_name_holder'];
		}
		
	if(isset($_POST['activity_limit'])){
		$_SESSION["activity_limit"] = $_POST['activity_limit'] + $_POST['activity_number_holder'];
		}
	if(isset($_POST['activity_number_holder'])){
		$_SESSION['activity_number_holder'] = $_POST['activity_number_holder'];
		}
		
	if(isset($_POST['clear_activity_session_values'])){
		
		unset($_SESSION["activity_parent"]);
		unset($_SESSION['activity_number_holder']);
		unset($_SESSION["activity_limit"]);
		unset($_SESSION['activity_parent_name_holder']);
	}
		# SET QUERY VALUES
		
	if(isset($_SESSION["activity_parent"])){
	$parent = $_SESSION["activity_parent"];
	}
			
	if(isset($_SESSION["activity_limit"])){
	$limit = $_SESSION["activity_limit"];
	}
	
		if(isset($_SESSION['activity_number_holder'])){
		$_SESSION["activity_limit"] = $limit + $_SESSION['activity_number_holder'];
		$number_holder = $_SESSION["activity_limit"];
		$_POST['activity_limit']=''; 
		$condition2 = " LIMIT 0, {$_SESSION["activity_limit"]}";
		}
	
	 else { 
		 $limit = 12; 
		 $condition2 = " LIMIT 0, {$limit}";
		 }
		 
	if($parent!==''){$condition = " WHERE `parent`='{$parent}'";
	}else{$condition = "";} 
		# DO QUERY
	
	//get contacts of user
	if(is_logged_in()){
		$owner = $_SESSION['username'];
	}	
	
	
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `contact_name` FROM `contacts` WHERE `owner`='{$owner}' LIMIT 9") 
		or die("Error selecting contacts " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))) ;
		
		
	if($parent =='user'){
		$user = user_being_viewed();
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT DISTINCT * FROM `activity` WHERE `actor`='{$user}' ORDER BY `id` DESC {$condition2}") 
		or die("Failed to get user activity ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	} else {	
	
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT DISTINCT * FROM `activity`{$condition}  GROUP BY date, actor ORDER BY `id` DESC {$condition2}")
	 	or die("FAiled to fetch {$parent} activity!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	}
	 	$count = mysqli_num_rows($query);
			if($count < 1){
			//	echo '<div class="main-content-region">';
				status_message('alert','No activity!');
				
			} else { echo '<h2 class="text-center white-text">Recent Activity</h2><div class="padding-10">'; }
			
	 	while($result = mysqli_fetch_array($query)){
			
			echo '<a href="'.$result['actor_path'].'">'. $result['actor'].'</a>'
					.$result['action'].' <a href="'.$result['subject_path'].'">'.str_ireplace('-',' ',urldecode($result['subject_name'])).'</a> '
					." <time class='timeago' datetime='".$result['date']."'>".$result['date']."</time>";
					
					if(is_admin()){
					 echo "<span class='tiny-edit-text'><a href='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."&activity_delete=".$result['id']."&control=".$_SESSION['control']."'><em>delete</em></a></span>";
					}echo " <br><hr>";
			}
		
	
	echo "<div class='show-more'>
		<form method='post' action='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."'>".
		"<input type='hidden' name='activity_limit' value='".$limit."'>
		<input type='hidden' name='activity_number_holder' value='".$number_holder."'>
		<input type='hidden' name='activity_parent' value='".$parent."'>
		<input type='submit' name='submit' value='show me more' class='button-primary'>
		<input type='submit' name='clear_activity_session_values' value='reset'>
		</form></div>";
		
		echo "</div>";
	
	}
	
function show_more($parent=''){
	
	
	//DELETES ACTIVITY
	if(isset($_GET["{$parent}_delete"]) && ($_SESSION['control'] == $_GET['control'])){
		$id = $_GET['activity_delete'];
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `{$parent}` WHERE `id`='{$id}'") or die('Failed to delete record' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		}
	
	if($parent ===''){
		
		if(isset($_GET['fundraiser_name'])){
			$parent = 'fundraiser';
		} else if(isset($_GET['contest_name'])){
			$parent = 'contest';
		} else if(isset($_GET['project_name'])){
			$parent = 'project';
		}
	}
	# post_limit routing
	
	if(!empty($_POST["{$parent}_parent"])){
		$_SESSION['activity_parent'] = $_POST['activity_parent'];
		}
		
	if(isset($_POST["{$parent}_parent_name_holder"])){
		$_SESSION["{$parent}_parent_name_holder"] = $_POST["{$parent}_parent_name_holder"];
		}
		
	if(isset($_POST["{$parent}_limit"])){
		$_SESSION["{$parent}_limit"] = $_POST["{$parent}_limit"] + $_POST["{$parent}_number_holder"];
		}
	if(isset($_POST["{$parent}_number_holder"])){
		$_SESSION["{$parent}_number_holder"] = $_POST["{$parent}_number_holder"];
		}
		
	if(isset($_POST["clear_{$parent}_session_values"])){
		
		unset($_SESSION["{$parent}_parent"]);
		unset($_SESSION["{$parent}_number_holder"]);
		unset($_SESSION["{$parent}_limit"]);
		unset($_SESSION["{$parent}_parent_name_holder"]);
	}
		# SET QUERY VALUES
		
	if(isset($_SESSION["{$parent}_parent"])){
	$parent = $_SESSION["{$parent}_parent"];
	}
			
	if(isset($_SESSION["{$parent}_limit"])){
	$limit = $_SESSION["{$parent}_limit"];
	}
	
		if(isset($_SESSION["{$parent}_number_holder"])){
		$_SESSION["{$parent}_limit"] = $limit + $_SESSION["{$parent}_number_holder"];
		$number_holder = $_SESSION["activity_limit"];
		$_POST["{$parent}_limit"]=''; 
		$condition2 = ' LIMIT 0, {$_SESSION["{$parent}_limit"]}';
		}
	
	 else { 
		 $limit = 10; 
		 $condition2 = " LIMIT 0, {$limit}";
		 }
		 
	if($parent!==''){$condition = " WHERE `parent`='{$parent}'";
	}else{$condition = "";} 
		# DO QUERY
		
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `{$parent}`{$condition} ORDER BY `id` DESC {$condition2}")
	 	or die("FAiled to1 fetch {$parent} !" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 	
	 	$count = mysqli_num_rows($query);
	 	if($count < 1){
			echo '<div class="main-content-region">';
			status_message('alert','No post!');
			
		}else{
	 	
	 	
	 	
	 	
	 	while($result = mysqli_fetch_array($query)){
			
			echo '<a href="'.$result["{$parent}_name"].'">'. $result['actor'].'</a>'
					.$result['action'].' <a href="'.$result['subject_path'].'">'.str_ireplace('-',' ',$result['subject_name']).'</a> '
					." <time class='timeago' datetime='".$result['date']."'>".$result['date']."</time>";
					
					if(is_admin()){
					 echo "<span class='tiny-edit-text'><a href='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."&activity_delete=".$result['id']."&control=".$_SESSION['control']."'><em>delete</em></a></span>";
					}echo " <br><hr>";
			}
		}
	
	echo "<div class='show-more'>
		<form method='post' action='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."'>".
		"<input type='hidden' name='activity_limit' value='".$limit."'>
		<input type='hidden' name='activity_number_holder' value='".$number_holder."'>
		<input type='hidden' name='activity_parent' value='".$parent."'>
		<input type='submit' name='submit' value='show me more' class='button-primary'>
		<input type='submit' name='clear_activity_session_values' value='reset'>
		</form></div>";
		
		echo "</div>";
	
	
	
	}
	

function pagerize($start='',$show_more='10'){ 
	//to use $limit = $_SESSION['pager_limit'];
	
	if(isset($_POST['show_more'])){
	$_SESSION['pager_number_holder'] = mysql_prep($_POST['show_more']) + $_SESSION['pager_start'];
	$_SESSION['pager_show_more'] = mysql_prep($_POST['show_more']);
	$_SESSION['pager_calling_page'] = mysql_prep($_POST['pager_calling_page']);
	$_SESSION['pager_switch'] = 1;
	}
	
	if($start ==='' && isset($_SESSION['pager_number_holder'])){
		$_SESSION['pager_start'] = $_SESSION['pager_number_holder'];
		} else if(isset($_POST['show_more'])){
			$_SESSION['pager_start'] = mysql_prep($_POST['show_more']);
			} else {
				$_SESSION['pager_start'] = '0';
				}
		
	if(isset($_POST['show_more'])){
		$_SESSION['pager_show_more'] = mysql_prep($_POST['show_more']);
		} else {
			$_SESSION['pager_show_more'] = $show_more;
			}
		

	$_SESSION['pager_limit'] = "LIMIT {$_SESSION['pager_start']}, {$_SESSION['pager_show_more']}" ;
	// Reset session values
	if(isset($_POST['clear_pager_session_values']) || 
	($_SESSION['pager_calling_page'] != $_SESSION['current_url'] && $_SESSION['pager_switch'] == 1)){
		$_SESSION['pager_start'] = '0';
		$_SESSION['pager_show_more'] = '0';
		$_SESSION['pager_number_holder'] ='0';
		$_SESSION['pager_switch'] = 0;
		unset($_POST['show_more']);
		redirect_to($_SESSION['current_url']);
		}
	
	$output = "<div class='clear show-more'>
		<form method='post' action='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."'>".
		"<input type='submit' name='submit' value='show me more' class='button-primary'>
		<input type='text' name='show_more' value='".$show_more."'>
		<input type='hidden' name='pager_calling_page' value='".$_SESSION['current_url']."'>
		<input type='submit' name='clear_pager_session_values' value='reset'>
		</form></div>";
		
	return $output;	
	
}


function go_back($location =''){
	
	if($location ===''){
	echo "<span class='clear-bottom text-center'><a href='" .$_SERVER['HTTP_REFERER'] ."'> Go BACK</a></span>";
	} else {
		echo "<span class='clear-bottom text-center'><a href='" .$location ."'> Go BACK</a></span>";
		}
	}


function deny_access(){
	 status_message("alert","You do not have Permission to access this area!");
	 
	}
	
function log_in_to_continue(){
	if(!is_logged_in()){
	 echo "<div class='main-content-region'><p align='center'><a href='".BASE_PATH."user?action=login&redirect_to=".$_SESSION['current_url']."'>Log in </a> 
	 or <a href='".BASE_PATH."user?action=register&redirect_to=".$_SESSION['current_url']."'>Signup </a> to continue .</p></div>";
		}
	}

function log_in_to_comment(){
	 echo "<p align='center'>You must <a href='".BASE_PATH."user?redirect_to=".$_SESSION['current_url']."'>Log in </a> 
	 or <a href='".BASE_PATH."user?action=register&redirect_to=".$_SESSION['current_url']."'>Signup </a> to comment .</p>";
	}

function are_you_lost(){
	
	if(empty($_SESSION['username'])){
		echo '<div class="container">
				<h2 align="center">Are you lost?</h2><br>
				<p align="center">  &nbsp	 		
				<a href="'.BASE_PATH.'"> Go Home </a>&nbsp|&nbsp
				<a href="'.BASE_PATH.'admin"> Go to admin area </a><br>
				<a href="'.ADDONS_PATH.'shop/cart.php"> Go to shopping cart </a>&nbsp or&nbsp
				<a href="'.ADDONS_PATH.'shop/catalog"> Check out the Catalog </a></p></div>';
				}
	}
	

function do_search($table='', $column=''){
	#print_r($_SESSION); // testing

		if(isset($_SESSION['search_table'])){
			$table = $_SESSION['search_table'];
			
			$valid = array('page','contest','fundraiser','jobs','user','project_manager_project','project_manager_task','project_manager_suggestion','project_manager_ticket','company');
			
			if(! in_array($table,$valid)){
				$table = 'page';
			}
		}
			
		if(!empty($_SESSION['do_search'])){
			
			if($_SESSION['search_term'] !== ''){
			$string = str_ireplace(' ','-',trim(mysql_prep($_SESSION['search_term'])));
			} else { $string = ''; }
			
			if($column === ''){
			$column = $table."_name";
			} else if(isset($_SESSION['search_column'])){
				$column = $_SESSION['search_column'];
				}
			
			$route ='';
			$route2  = '';
      $string = str_ireplace('-',' ',$string);
			if(!empty($string)){ 
        if(string_contains($string,'!user')){
          $string = trim(str_ireplace('!user','',$string));
          $column = 'user_name';
          $table = 'user';
            if(isset($result['phone'])){
            $action = '<span class="">
            <a href="tel:'.$result['phone'].'">
            <i class="glyphicon glyphicon-earphone btn btn-xs btn-primary"> Call </i>
            </a></span>';
            }
          }elseif(string_contains($string,'!fundraiser')){
          $string = trim(str_ireplace('!fundraiser','',$string));
          $column = 'fundraiser_name';
          $table = 'fundraiser';
          }elseif(string_contains($string,'!contest')){
          $string = trim(str_ireplace('!contest','',$string));
          $column = 'contest_name';
          $table = 'contest';
          }elseif(string_contains($string,'!job')){
          $string = trim(str_ireplace('!job','',$string));
          $column = 'title';
          $table = 'jobs';
          }elseif(string_contains($string,'!task')){
          $string = trim(str_ireplace('!task','',$string));
          $column = 'task_name';
          $table = 'project_manager_task';
          }elseif(string_contains($string,'!project')){
          $string = trim(str_ireplace('!project','',$string));
          $column = 'project_name';
          $table = 'project_manager_project';
          }elseif(string_contains($string,'!post')){
          $string = trim(str_ireplace('!post','',$string));
          $column = 'page_name';
          $table = 'page';
          }elseif(string_contains($string,'!discussion')){
          $string = trim(str_ireplace('!discussion','',$string));
          $column = 'page_name';
          $table = 'page';
          }
          $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `{$table}` WHERE `{$column}` LIKE '%{$string}%' LIMIT 0, 10") 
        or die("Search failed ".mysqli_error($GLOBALS['___mysqli_ston']));
        
        $num = mysqli_num_rows($query);
        
        if($num < 1){
        $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `page` WHERE `page_name` LIKE '%{$string}%' LIMIT 0, 10") or die
        ("Search Failed !". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
         if($num < 1){
        $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `page` WHERE `content` LIKE '%{$string}%' LIMIT 0, 10") or die
        ("Search Failed !". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
          }
          if($num < 1){
          $num =0;
          echo "<strong>{$num}</strong> results found ! ";
          echo "<br>No results were found for your search";
          }
        } else {
          echo 'You searched for <span class="green-text"><em>'.$string.'</em></span><br>
        <strong>'.$num.'</strong> results found ! <hr><br>
        <h2> Results are :</h2><ol>';
          }
			$pic = '';
      $action ='';	
      $inbox_me = '';
			while($result = mysqli_fetch_array($query)){
			
			//print_r($result);
			if(isset($result['user_name'])){
      $me = $_SESSION['username'];
			$route = 'user/?' ;	
			$route2 = 'user';
			$route3 = '';
      $content ='';
      $content_type = 'user';
      $inbox_me = "<span class='pull-right tiny-text thumbnail'><a href='".ADDONS_PATH."messaging/?mid=&parent_id={$result['user_name']}.{$me}&unread=no&older=yesreciever={$result['user_name']}'> Send Msg </a></span>";
		
        if(isset($result['phone'])){
        $pic = default_pic_fallback($result['picture_thumbnail'],'small');
        $pic = '<img class="img-circular margin-3 inline" src="'.$pic.'" width="45">';
          if(is_admin()){
          $action = '<span class="pull-right"><a href="tel:'.$result['phone'].'"><span class="tiny-text thumbnail"><i class="glyphicon glyphicon-earphone"></i> Call </span></a></span>';
          }
        }
			} else if(isset($result['fundraiser_name'])){
			$route = 'addons/fundraiser/'.'?action=show&';
			$route2 = str_ireplace(' ','-',$column);
			$route3 = '&fid='.$result['id'];
      $content = urldecode(substr($result['reason'],0,150));
      $content_type = 'fundraiser';
			} else if(isset($result['project_name'])){
			$route = 'addons/project_manager/'.'?action=show&';
			$route2 = str_ireplace(' ','-',$column);
			$route3 = '';
      $content_type = 'project';
			} else if(isset($result['contest_name'])){
			$route = '/addons/contest/?' ;	
			$route2 = 'contest_name';
			$route3 = '';
      $content = urldecode(substr($result['description'],0,150));
      $content_type = 'contest';
			} else if(isset($result['title'])){
			$route = '/addons/jobs/?' ;	
			$route2 = 'job_title';
			$route3 = '&jid='.$result['id'];
      $content_type = 'job';
			} else if(isset($result['page_name'])){
				if($result['page_type'] === 'contest'){
				$route = 'addons/contest/?' ;	
				$route2 = 'contest_name';
				$route3 = '';
        $route3 = '&cid='.$result['id'];
				} else if($result['page_type'] === 'event'){
				$route = '/addons/event/?' ;	
				$route2 = 'event_name';
				$route3 = '';
				} else {
					$route = '?page_name' ;
					$route2 = '';
					$route3 = '&tid='.$result['id'];
          $content = urldecode(substr($result['content'], 0,150));
          $content_type = $result['page_type'];
					}
			
		}
		
			echo '<li><a href="'.BASE_PATH.$route.$route2.'='.str_ireplace('#','',str_ireplace(' ','-',$result["{$column}"])) .$route3.'"> '.$pic.ucfirst(str_ireplace('-',' ',urldecode($result["{$column}"])))."</a>";
      if(!empty($content)){
      echo '<br>'.$content ;
      }
      echo "{$inbox_me} {$action}<small><br><em>";
			echo $content_type;
			echo "</em></small></li><hr>";
				} echo '<ol>'; 
			}
		
			$_SESSION['search_term'] ='';
			
		}
	}
  
function shorten_string($string, $amount){
  if(strlen($string)>$amount){
   $string = trim(substr($string,0,$amount)).'...';
    }
  return $string;
  }
	
function sanitize($string){
  $value = str_ireplace("<div><br></div>","<br>",$string);
  $value = better_strip_tags($string);
  $value = htmlspecialchars($string);
  $value = str_ireplace("'","&#39;",$value);
  $value = str_ireplace(",","&#44;",$value);
  return $value;
}

function curl_get($url=''){	
	$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_URL => sprintf($url, $name, $mobNo), 
));
$response = curl_exec($curl);
curl_close($curl);
return $response;

}

function _isCurl(){
	var_dump(curbl_version());
 return function_exists('curl_version');
}
 // Form functions
function form_start($method='post',$action=''){
	if($action ==''){
		$action = $_SESSION['current_url'];
		}
	echo '<form method="'.$method.'" action="'.$action.'">';
	}
	

function currency_filter($amount=''){
	
		echo SITE_CURRENCY ;
		echo number_format($amount,2,'.',',');
	}
  
function currency_convert($to){
  $from = 'USD';
  $api_link = 'https://openexchangerates.org/api/latest.json?app_id=4c9c9121db304439a5e7cfd7dbb895c9';
  }
	
function call_to_action_front(){

	//center_start();
	if(addon_is_active('fundraiser')){
	go_to_fundraiser(); 
	}
	if(addon_is_active('project_manager')){
	go_to_project_manager();
	}
	if(addon_is_active('funds_manager')){
	fund_account_link(); 
	}
	//center_stop();

}

function not_found_error(){
	if(isset($_GET['error'])){
		if($_GET['error'] === '404'){
			echo "<h2>Hmmn... thats strange, <br> I could not find the page you were looking for</h2>";
			}
		}
	}

#  ADMIN FOOTER



function restrict_config_to_admin(){
	if((url_contains('config/index.php') || url_contains('config.php') || url_contains('/config')) && !is_admin()){
		go_back(); 
		echo '<br>';
		deny_access();
		die();
		}
	}
restrict_config_to_admin();


function email($user,$subject,$message){
	$user = get_user_details($user);
	$user_email = $user['email'];
	$header = 'From:noreply@geniusaid.org \r\n';
	$header .= 'Cc:notices@geniusaid.org \r\n';
	$header .= "MIME-Version:1.0\r\n";
	$header .= "Content-type: text/html \r\n";
	
	$retval = mail($user_email,$subject,$message,$headers,$parameters);
	if($retval == true){
		session_message('success','Message sent successfully.');
		} else {
			session_message('error','Message not sent.');
			}
	}


function query_db($query,$error_message){
	$q = mysqli_query($GLOBALS['___mysqli_ston'],
		"{$query}") 
		or die("{$error_message}".mysqli_error($GLOBALS['___mysqli_ston']));
		$num = mysqli_num_rows($q);
		$count = 0;
		$output = array();
		$output['result'] = array();
		$output['num_results'] = $num;
		
		while($result = mysqli_fetch_array($q)){
			if(isset($result['count'])){
				$output['count'] = $result['count'];
				}
      $output['result'][$count] = $result;
      $count++;
			}
		return $output;
	}

function do_admin_footer() {
	
  echo "" .
	"<script src='" .BASE_PATH ."libraries/jquery/jquery-1.11.2.min.js'></script>" .
	"<script src='" .BASE_PATH ."libraries/uikit/js/uikit.min.js'></script>" .
	"<script src='" .BASE_PATH ."libraries/nivo-slider/jquery.nivo.slider.pack.js'></script>".
	"<script type='text/javascript' src='" .ADMIN_PATH .'/' ."scripts/script.js'></script>" .
	"Wanni CMS" .
	"" .
	"</body>" . 
	"</html>";
    
# Close connection
# do this at the end of the page
if(isset($connection)) { ((is_null($___mysqli_res = mysqli_close($connection))) ? false : $___mysqli_res); }

}




?>
