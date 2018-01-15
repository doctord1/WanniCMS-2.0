<?php

function install_addon($addon_name,$status){
  //~ 1. Install the database schema
  if(function_exists("setup_{$addon_name}_db")){
    $a = call_user_func("setup_{$addon_name}_db");
  } else {
    $_SESSION['status-message'] = '<div class="alert alert-danger">Install Information does not exist</div>';
  }
    
  //~ 2. Add the addon entry to the addons db
  $q = db_add_item('core_addons', $values = "{$addon_name},{$status}");
  if($q){
    status_message('success',"{$addon} addon installed successfully!");
    redirect_to(BASE_PATH.'index.php/admin/action/administer-addon/'.$addon_name);
  }
}


function enable_addon($addon_name){
  $q = query_db("UPDATE core_addons SET status= 'active' WHERE addon_name='{$addon_name}'",
  "Could not enable {$addon_name} addon! ");
  if($q){
    $_SESSION['status-message'] = '<div class="alert alert-success">'.ucfirst($addon_name).' enabled successfully!</div>';
    redirect_to(BASE_PATH.'index.php/admin/action/administer-addon/'.$addon_name);
  }
}

function disable_addon($addon_name){
  $q = query_db("UPDATE core_addons SET status= 'disabled' WHERE addon_name='{$addon_name}'",
  "Could not enable {$addon_name} addon! ");
  if($q){
    $_SESSION['status-message'] = '<div class="alert alert-success">'.ucfirst($addon_name).' disabled successfully!</div>';
    redirect_to(BASE_PATH.'index.php/admin/action/administer-addon/'.$addon_name);
  }
}

function uninstall_addon($addon_name,$related_tables=array()){
  $q = query_db("DELETE FROM core_addons WHERE addon_name='{$addon_name}'",
  "Could not delete {$addon_name} addon! ");
  $q = query_db("DROP table {$addon_name}'",
  "Could not drop {$addon_name} table! ");
  foreach($related_tables as $table){
    $table = sanitize($table);
    $q = query_db("DROP table {$table}'",
    "Could not drop {$addon_name} table! ");
  }
    
  if($q){
    $_SESSION['status-message'] = '<div class="alert alert-success">'.$addon_name.' uninstalled successfully!</div>';
    redirect_to(BASE_PATH.'index.php/admin/action/administer-addon/'.$addon_name);
  }
}

function set_active_addons(){
  $_SESSION['active-addons'] = array();
  $q = query_db("SELECT addon_name FROM core_addons WHERE status='active'",
  "Could not get active addons! ");
  
  if($q){
    foreach($q['result'] as $result){
      $_SESSION['active-addons'][] = $result['addon_name'];
    }
  }
}


function addon_is_available($addon_name){
	$addons = dirname(dirname(__FILE__)).'/'.'addons/';
  //~ echo $addons. '<br>: that was addons';
  $addons_array = glob($addons.'*.php');
  foreach ($addons_array as $key => $value){
    $addons_array[$key] = str_ireplace($addons,'',$value);
  }
  //~ print_r($addons_array);
  if(in_array($addon_name.'.php',$addons_array)){
		return true;
  } else {
    return false;
  }
}

function addon_is_active($addon_name){
  set_active_addons();
  if(!empty($_SESSION['active-addons'])){
    if(in_array($addon_name,$_SESSION['active-addons'])){
      return true;
    } else {
      return false;
    }
  }
}

function administer_addon($addon_name){
  if(
  //~ is_admin() && 
  addon_is_available($addon_name)){
    echo '
    <h4>Administer '.ucfirst($addon_name).' addon</h4>
    <div class="p-3 m-3 border ">';
    
      
    if(!addon_is_active($addon_name)){
      echo ' <a href="'.BASE_PATH.'index.php/admin/action/install-addon/'.$addon_name.'">
          <button class="btn btn-primary">Install '.$addon_name.'<i class="glyphicon glyphicon-cog"></i></button>
        </a>';
    }
     
    echo '<a href="'.BASE_PATH.'index.php/admin/action/enable-addon/'.$addon_name.'">
      <button class="btn btn-success">Enable<i class="glyphicon glyphicon-ok-circle"></i></button>
    </a>
    
    <a href="'.BASE_PATH.'index.php/admin/action/disable-addon/'.$addon_name.'">
      <button class="btn btn-warning">Disable<i class="glyphicon glyphicon-ban-circle"></i></button>
    </a>
  
    <a href="'.BASE_PATH.'index.php/admin/action/disable-addon/'.$addon_name.'">
      <button class="btn btn-danger">Uninstall<i class="glyphicon glyphicon-trash"></i></button>
    </a>
  
  </div>';
    
  }
}
?>
