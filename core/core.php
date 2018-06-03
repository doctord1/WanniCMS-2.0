<?php
ob_start();
error_reporting(E_ERROR | E_ALL );

require_once('router.php');
require_once('title.php');
$_SESSION['LAST_ACTIVITY'] = time();

if(isset($_POST['destination'])){
  $destination = $_POST['destination'];
  echo "<script> window.location.replace('{$destination}') </script>";
  exit;
}else if(isset($_GET['destination'])){
  $destination = $_GET['destination'];
  echo "<script> window.location.replace('{$destination}') </script>";
  exit;
}


function summarize($string='',$lenght=''){
  if(strlen($string) > $lenght){
    $show_dots = '...';
  } else {
    $show_dots = '';
  }
  $output = parse_text_for_output(urldecode(substr($string,0,$lenght))).$show_dots;
  return $output;
  //~ echo $output;
}

function install_core(){
  $sql['drop core abuse'] = "DROP TABLE IF EXISTS `core_abuse`";
  $sql['create core abuse'] = "CREATE TABLE IF NOT EXISTS `core_abuse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reporter_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `response` varchar(150) NOT NULL,
  `responder_user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
$q1 = query_db("SELECT `value` FROM `core_installed`","Could not check if WanniCMF is installed");
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
   $styles_dir = DIR_PATH.'/'.'styles/';
   $files = glob($styles_dir.'*.css');
   echo '<style type="text/css">';
    foreach (glob($styles_dir.'*.css') as $stylesheet){
    $stylesheet = str_ireplace($styles_dir,'',$stylesheet);
    $this_stylesheet_name = rtrim($stylesheet,'.css');
      if(in_array(DIR_PATH.'/'.'styles/'.$this_stylesheet_name.'min.css',$files)){
        require_once(DIR_PATH.'/styles/'.$this_stylesheet_name.'.min.css');
      } else {
        require_once(DIR_PATH.'/styles/'.$stylesheet);
      }
    }
  echo '</style>';
  }


function load_single_css($filename){
  echo '<style>';
  require_once($filename);
  echo '</style>';
}

function load_bootstrap(){

  echo '
    <script defer src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
  ';
}

function load_jquery(){
   //~ echo '<script async
  //~ src="https://code.jquery.com/jquery-3.3.1.min.js"
  //~ integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  //~ crossorigin="anonymous"></script>';
}



function load_bootstrap_css(){
  echo '<link defer rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">';
}


function load_single_js($filename){
    echo '<script>';
    require_once(DIR_PATH.'/scripts/'.$filename);
    echo '</script>';
}

function load_scripts(){

  $scripts_dir = DIR_PATH.'/'.'scripts/';
  $files = glob($scripts_dir.'*.js');
  //~ print_r($files);

    if(in_array(DIR_PATH.'/'.'scripts/'.'all-one-scripts.min.js',$files)){
      echo '<script defer type="text/javascript" src="'.BASE_PATH.'scripts/all-one-scripts.min.js"></script>';
      echo '<script defer type="text/javascript" src="'.BASE_PATH.'scripts/90.script.js"></script>';
    } else {
      foreach (glob($scripts_dir.'*.js') as $script){
        echo '<script defer>';
        $this_script = str_ireplace($scripts_dir,'',$script);
        $this_script_name = rtrim($this_script,'.js');
        //~ echo $this_script_name;

        if(in_array(DIR_PATH.'/'.'scripts/'.$this_script_name.'min.js',$files)){
          require_once(DIR_PATH.'/scripts/'.$this_script_name.'.min.js');
        } else {
          require_once(DIR_PATH.'/scripts/'.$this_script);
        }
        echo '</script>';
      }
    }

}

function load_library($libName,$file_path,$ext){
     switch($ext){
        case '.css':
          echo '<style>';
           require_once(DIR_PATH.'libraries/'.$libName.'/'.$file_path);
           echo '</style>';
        break;
        case '.js':
          echo '<script>';
          require_once(DIR_PATH.'libraries/'.$libName.'/'.$file_path );
          echo '</script>';
        break;

     }


}


function status_message($class, $message){
  echo '<div class="'.$class.' m-2">'.$message.'</div>';
}


function query_string_in_url(){
  $url = $_GET;
  if(!empty($url)){
    return true;
  } else {
    return false;
    }
}


function is_home_page(){
  $url = $_SESSION['current_url'];
  if($_SESSION['current_url'] == BASE_PATH || $_SESSION['current_url'] == BASE_PATH.'/home'){
    return true;
  } else { return false; }

}


function add_ckeditor(){
  if(url_contains('say-something')
  || url_contains('add-room')
  || url_contains('edit-post')
  || url_contains('edit-room')
  ){
    echo '<script src="https://cdn.ckeditor.com/4.8.0/standard/ckeditor.js"></script>';
    //~ echo '<script type="text/javascript" src="'. BASE_PATH .'libraries/ckeditor/ckeditor.js"></script>';


    echo '
    <script>
    $(document).load(function(){
     // Replace the <textarea id="content-area"> with a CKEditor
      // instance, using default configuration.
      CKEDITOR.replace( "#content-area" );
    });

    </script>';
  }
}
function add_tinymce(){
  if(url_contains('say-something')
  || url_contains('add-room')
  || url_contains('edit-post')
  || url_contains('edit-room')
  ){
    echo '<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
  <script>tinymce.init({ selector:\'.textarea\' });</script>';

    echo '
    <script>
    $(document).load(function(){
     // Replace the <textarea id="content-area"> with a CKEditor
      // instance, using default configuration.
      CKEDITOR.replace( "#content-area" );
    });

    </script>';
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


function better_strip_tags( $str, $allowable_tags = '<a><p><br><em><strong><h1><h2><h3><ul><img><del><strikethrough><blockquote>', $strip_attrs = false, $preserve_comments = false, callable $callback = null ) {
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
      //~ echo $match;
      str_ireplace(BASE_PATH,'',$match);
      $string = str_ireplace($match,'<img class="img-fluid p-2 d-block" src="'.BASE_PATH.$match1.'" >',$string);

      }
    }

    $pattern = '/(?<!\S)@\w+(?!\S)/';
    // Explanation: This will match any word containing alphanumeric characters, starting with "@."
    // It will not match words with "@" anywhere but the start of the word.
    if(preg_match_all($pattern,$string,$matches)){
      foreach($matches[0] as $match){
      $changed_match = str_ireplace($match, "<a href='".BASE_PATH."user/action/show-user-profile/".str_ireplace('@','',$match)."'>{$match}</a>", $match);
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

      //~ if(preg_match_all($hashtag,$string,$matches)){
        //~ foreach($matches[0] as $match){
        //~ $changed_match = str_ireplace($match, "<a href='".ADDONS_PATH."hashtags?hashtag=".str_ireplace('#','',$match)."'>{$match}</a>", $match);
        //~ $new_string = preg_replace("/{$match}/", $changed_match, $string);
        //~ $new_string = str_ireplace('&amp;','&',$new_string);
        //~ $string = $new_string;
        //~ }
      //~ }
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

function timeago($time){
  $time = strtotime($time);
  $elapsed_time = time() - $time;

    if ($elapsed_time < 1) {
        $out = ' <small class="tiny-text text-muted float-right">0 secs</small>';
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
          $out = " <small class='tiny-text text-muted float-right'> " . $r . ' ' . $text . ($r > 1 ? 's' : '') . ' ago </small>';
        }
    }
    return $out;
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
          $out = " <span class='tiny-text text-muted float-right'> " . $r . ' ' . $text . ($r > 1 ? 's' : '') . ' ago </span>';
        }
    }
    echo $out;
  }

function time_elapsed($time) {
    $time = strtotime($time);
    $elapsed_time = time() - $time;

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

function go_back($location =''){
  if($location ===''){
  echo "<span class='clear-bottom text-center'><a href='" .$_SERVER['HTTP_REFERER'] ."'> Go BACK</a></span>";
  } else {
    echo "<span class='clear-bottom text-center'><a href='" .$location ."'> Go BACK</a></span>";
  }
}


function deny_access(){
   status_message("alert alert-info","You do not have Permission to access this area!");

  }

function log_in_to_continue($message=''){
  if(!is_logged_in()){
    if(empty($message)){
      $message = 'continue.';
      $_SESSION['destination'] = $_SESSION['prev_url'];
    }
    echo "<div class='p-3 m-3'>
      <p align='center'>
        <a href='".BASE_PATH."login'>Log in </a>
        or
        <a href='".BASE_PATH."register'>Signup </a> to {$message} .
      </p>
    </div>";
  }
}

function log_in_to_comment(){
  if(!is_logged_in()){
    $_SESSION['destination'] = $_SESSION['prev_url'];
   echo "<p align='center'>You must <a href='".BASE_PATH."login'>Log in </a>
   or <a href='".BASE_PATH."register'>Signup </a> to comment .</p>";
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
  return stripslashes($value);
}

function curl_get($url=''){
  $curl = curl_init();

  curl_setopt_array($curl, array(
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_HEADER => 0,
      CURLOPT_URL => $url,
  ));
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;

}

function _isCurl(){
  var_dump(curbl_version());
 return function_exists('curl_version');
}

function currency_filter($amount=''){

    return $_SESSION['preferred_currency'] .' ' .
    number_format($amount,2,'.',',');
  }

function currency_convert($to){
  $from = 'USD';
  $api_link = 'https://openexchangerates.org/api/latest.json?app_id=4c9c9121db304439a5e7cfd7dbb895c9';
  }


function email($user,$subject,$message){
  $user = get_user_by_username($user);
  $user_email = $user['email'];
  $header = 'From:noreply@staywithme.com.ng \r\n';
  $header .= 'Cc:notices@staywithme.com.ng \r\n';
  $header .= "MIME-Version:1.0\r\n";
  $header .= "Content-type: text/html \r\n";

  $retval = mail($user_email,$subject,$message,$headers,$parameters);
  if($retval == true){
    $_SESSION['status-message'] =  '<div class="alert alert-success">Message sent successfully!</div>';
    } else {
      $_SESSION['status-message'] =  '<div class="alert alert-danger">Message not sent!</div>';
      }
  }


function query_db($query,$error_message){
  $q = mysqli_query($GLOBALS['___mysqli_ston'],
  "{$query}")
  or die("{$error_message}".mysqli_error($GLOBALS['___mysqli_ston']));
  $output = array();
  $output['result'] = array();
  $count = 0;
  $num = mysqli_num_rows($q);
  if(!empty($q)){
    $output['num_results'] = $num;
    while($result = mysqli_fetch_assoc($q)){
      if(isset($result['count'])){
        $output['count'] = $result['count'];
      }
      $output['result'][$count] = $result;
      $count++;
    }
    return $output;
  }
}



function addon_is_available($addon_name){
  $addons_dir = DIR_PATH.'/'.'addons/';
  if(file_exists($addons_dir.$addon_name.'.php')){
    return true;
  } else {
    return false;
  }
}
function addon_is_active($addon_name){
  $addons_dir = DIR_PATH.'/'.'addons/';
  if(file_exists($addons_dir.$addon_name.'.php')){
    return true;
  } else {
    return false;
  }
}


?>
