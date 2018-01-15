<?php


function user_has_role($requested_role){
  if(isset($_SESSION['username'])){
		$role = explode(',',$_SESSION['role']);
		$has_role = '';
		foreach ($role as $r){
      if($r == $requested_role){
        $has_role = true;
      } else { $has_role = false; }
    }
  } return $has_role;
}

	
function is_admin(){
	if(isset($_SESSION['username'])){
		$role = explode(',',$_SESSION['role']);
		
		$is_admin = '';
		foreach ($role as $r){
			//~ echo '['.$r . ']<br>';
		if($r =='admin' || $r == 'manager' || $r == 'superadmin'){
			$is_admin = 'true';
			//~ echo 'true';
			}
		}
		if ($is_admin == 'true'){
			return true;
		} else { return false; }
	}
}



function is_author(){
	if($_SESSION['username'] === $_SESSION['author']){
		return true;
		} else { return false;}
}


/*
 * 
 * name: user_has_permmission_to
 * @param: action name 
 * @param: entity name 
 * @return: true/false
 * 
 */
function user_has_permission_to($action_name,$entity_name,$entity_id){
  $user_id = $_SESSION['user_id'];
  $q = query_db("SELECT owner_id FROM {$entity_name} WHERE id='$entity_id'",
  "Could not get owner in user has permission to! ");
  if($q){
    if($user_id == $q['result']['owner_id']){
      return true;
    } else if(isset($_SESSION['role'])){
      $q = query_db("SELECT allowed_roles FROM core_permissions WHERE action='{$action_name}' AND addon_name='{$entity_name}'",
      "Could not check if user has permission to {$action_name}! ");
      if($q){
        $allowed_roles = $q['result']['allowed_roles'];
        $roles = explode(',',$allowed_roles);
        $user_role = $_SESSION['role'];
        if(in_array($user_role,$roles)){
          return true;
        } else {
          return false;
        }
      }
    }
  }
}

/*
 * 
 * name: set_permissions
 * @param: array of actions
 * @param: entity name
 * @return: void
 * 
 */
function set_permission_actions($entity_name){
  if(isset($_POST['actions']) && isset($_POST['set-permissions'])){
    $actions_csv = trim(parse_text_for_output(sanitize($_POST['actions'])));
    $actions_array = explode(',',$actions_csv);
    //~ print_r($actions_array); die();
  
    foreach($actions_array as $action){
      $action = trim($action);
      $q = db_add_item('core_permissions',$values="{$action},{$entity_name},''");
    }
    if($q){
        $_SESSION['status-message'] = '<div class="alert alert-success alert-dismissible">'.$entity_name.' Permissions Saved</div>';
        redirect_to($_SESSION['current_url']);
      }
  }
  echo '<div class="bg-primary p-3 row">
    <h3 class="w-100 px-3 pt-3 pb-1 text-dark">Set Permission Actions for <b class="text-white">'.$entity_name.'</b> Addon.</h3>
    <form action="'.$_SESSION['current_url'].'" method="post" class="w-100">
    <div class="input-group p-3">
      <input type="text" name="actions" placeholder="Seperate actions by comma" class="form-control" />
    
      <div class="input-group-append">
        <input type="submit" name="set-permissions" value="Save permissions" class="btn btn-dark" />
      </div>
    
    </div>
    </form>
  </div>';
  
  show_session_message();
  
  assign_permission_to_roles('room');
}

function assign_permission_to_roles($addon_name,$action='',$roles_csv=''){
  
  //~ if(is_admin()){
    if(isset($_POST['assign-permissions']) && !empty($_POST['allowed-roles'])){
      $action = trim(sanitize($_POST['action']));
      $allowed_roles = trim(sanitize($_POST['allowed-roles']));
      
      $q = query_db("UPDATE core_permissions SET allowed_roles='{$allowed_roles}' WHERE action='{$action}' AND addon_name='{$addon_name}'",
      "Could not update roles in assign permission to roles! ");
      if($q){
        $_SESSION['status-message'] = '<div class="alert alert-success"> Allowed roles updated for '.$action.' action in '.$addon_name.'</div>';
        redirect_to($_SESSION['current_url']);
      }
    }
  
    $roles = get_roles();
    $perms = get_permissions($addon_name);
    echo '<span class="text-muted p-3 "><b>Roles: </b>';
    foreach($roles['result'] as $role){
      echo ''.$role['role'].',';
    }
    echo '</span>';
    
    echo '<br><span class="text-muted p-3"><b>Actions: </b>';
    foreach($perms['result'] as $perm){
      echo ''.$perm['action'].',';
    }
    echo '</span>';
    
    
    foreach($perms['result'] as $perm){
      if(empty($perm['allowed_roles']) || $perm['allowed_roles'] == "''"){
        $perm['allowed_roles'] = '';
      }
      echo '<div class=" m-2 p-3 border bg-light">
      <h4 class="text-dark">Assign <span class="text-danger">'.$perm['action'].'</span> Permissions to Roles in '.$addon_name.' Addon.</h4>
      <span class="text-muted">
        <b>Allowed roles: </b><span class="text-success">'.$perm['allowed_roles'].'</span>
      </span><br>
      <form action="'.$_SESSION['current_url'].'" method="post">
      <div class="input-group">
        <select name="action">';
          echo '<option>'.$perm['action'].'</option>
        </select>
        <input type="text" name="allowed-roles" placeholder="Seperate roles by comma" value="'.parse_text_for_output($perm['allowed_roles']).'" class="form-control text-muted" />
        
      
        <div class="input-group-append">
          <input type="submit" name="assign-permissions" value="Assign permissions" class="btn btn-info" />
        </div>
      </div>
      </form>
      </div>';
    }
  //~ } else {
      //~ deny_access();
  //~ }
}

function get_roles(){
  $q = query_db("SELECT role FROM core_roles ",
  "Could not get roles! ");
  if($q){
    return $q;
  }
}

function get_permissions($addon_name){
  $q = query_db("SELECT * FROM core_permissions WHERE addon_name='{$addon_name}'",
  "Could not get {$addon_name} permissions! ");
  if($q){
    return $q;
  }
}



?>
