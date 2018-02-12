<?php 

function setup_project_db(){
  
  
  }

function add_project(){

	if(is_logged_in()){	
		$project_name = strtolower(trim(sanitize($_POST['project_name'])));
		$content = trim(sanitize($_POST['content']));
		$parent = trim(sanitize($_POST['parent']));
		$parent_id = trim(sanitize($_POST['parent_id']));
    if(empty($parent_id)){
      $parent_id = 0;
    }
		$path = BASE_PATH."index.php/project_manager/action/show-project/project-name/{$project_name}";
		$author = $_SESSION['username'];
		$editor = '';
		$created = date('c');
		$last_update ='';
		$status = 'pending';

		if($_POST['submit'] ==='Add project') {
			$query = query_db("INSERT INTO `project_manager_project`
			(`id`, `project_name`, `content`, `author`, `project_manager`, `path`, `editor`, `created`, `last_updated`, `status`, `parent`, `parent_id`) 
			VALUES ('0','{$project_name}', '{$content}', '{$author}', '{$author}', '{$path}', '{$editor}','{$created}','{$last_updated}','{$status}','{$parent}','{$parent_id}')",
      "Error inserting project! ");
			if($query){
				session_message("success", "project saved successfully!"); 
				redirect_to(BASE_PATH.'index.php/project/action/show_projects_list');
			}
		}

		if(isset($_GET['parent'])){
			$parent = $_GET['parent'];
			}
		if(isset($_GET['tid'])){
			$parent_id = $_GET['tid'];
			}
		echo '<h2 align="center">Add Project</h2><hr><form method="post" action="'.$_SESSION['current_url'].'">
		Name :<br><input type="text" name="project_name" value="" placeholder="Project name"><br>
		<input type="hidden" name="parent" value="'.$parent.'"> 
		<input type="hidden" name="parent_id" value="'.$parent_id.'"> 
		Description: <br><textarea name="content" size="5"> What is this project about ?</textarea>
		<input type="submit" name="submit" value="Add project" class="button-primary">
		</form>	';
		
	}
}

function get_project_funded_budget($project_id=''){
   if(empty($project_id)){
    $project_id = sanitize($_GET['project_id']);
    }
  $value = query_db("SELECT funded_budget FROM project_manager_project WHERE id='{$project_id}'",
  "Could not get project funded budget ");
  return $value['result']['0']['funded_budget'];
  }
  
function get_project_allocated_funds($project_id=''){
  if(empty($project_id)){
    $project_id = sanitize($_GET['project_id']);
    }
  $value = query_db("SELECT allocated_funds FROM project_manager_project WHERE id='{$project_id}'",
  "Could not get project allocated funds ");
  return $value['result']['0']['allocated_funds'];
  }
  
function set_project_allocated_funds($project_id='',$amount=''){
  if(empty($project_id)){
    $project_id = sanitize($_GET['project_id']);
    }
  $current_allocated_funds = get_project_allocated_funds($project_id);
  $new_amount = $current_allocated_funds + $amount;
  $value = query_db("UPDATE project_manager_project SET allocated_funds='{$new_amount}' WHERE id='{$project_id}'",
  "Could not update project allocated funds ");
  }
  
function get_project_total_budget($project_id=''){
   if(empty($project_id)){
    $project_id = sanitize($_GET['project_id']);
    }
  $value = query_db("SELECT funded_budget FROM project_manager_project WHERE id='{$project_id}'",
  "Could not get project total budget ");
  return $value['result']['0']['funded_budget'];
  }
  
function increase_project_total_budget($project_id=''){
  if(empty($project_id)){
    $project_id = sanitize($_GET['project_id']);
    }
  if(is_project_manager() && is_project_page()){
    echo '<span class="tiny-text"><a href="'.$_SESSION['current_url'].'&increase_budget_target='.$project_id.'">increase budget</a></span>';
    
    if(url_contains('increase_budget_target=')){
      
        
      if(isset($_POST['increase_budget'])){
        $amount = sanitize($_POST['increase_amount']);
        $project_id = sanitize($_POST['project_id']);
        $budget = get_project_total_budget($project_id);
        $new_budget = $amount + $budget;
        $q = query_db("UPDATE project_manager_project SET funded_budget='{$new_budget}' WHERE id='{$project_id}'",
        "Could not increase project total budget ");
        
        if($q){
          redirect_to($_POST['project_url']);
          }
        }
        
      echo '<form method="post" action="'.$_SESSION['current_url'].'">
      <input type="hidden" name="project_id" value="'.$project_id.'">
      <input type="hidden" name="project_url" value="'.$_SESSION['prev_url'].'">
      <input type="number" name="increase_amount" class="form-control" placeholder="amount to add to budget">
      <input type="submit" name="increase_budget" value="Save" class="btn btn-primary btn-xs">
      </form>';
    }
  }
}
  
  

  
  
function add_funds_to_project($project_id=''){
   if(empty($_GET['project_id'])){
    $project_id = sanitize($_GET['project_id']);
    }
  if(is_project_page()){
     echo '<span class="tiny-text"><a href="'.$_SESSION['current_url'].'&fund_project_target='.$project_id.'">add funds to project</a></span>';
    
    $user_funds = get_user_funds();
    if(url_contains('fund_project_target=')){
      if(isset($_POST['fund_project'])){
        $amount = sanitize($_POST['add_amount']);
        $project_id = sanitize($_POST['project_id']);
        $funded_budget = get_project_funded_budget($project_id);
        //$budget = get_project_total_budget($project_id);
       
        
        if($amount < $user_funds){
          $new_budget = $amount + $funded_budget;
          $minus_amount = -$amount;
          $reason = 'Added funds to '.  link_to_project_by_id($project_id). ' project.';
          
          update_user_funds($user='',$minus_amount ,$reason);
          $q = query_db("UPDATE project_manager_project SET funded_budget='{$new_budget}' WHERE id='{$project_id}'",
          "Could not increase project total budget ");
          
        if($q){
          redirect_to($_SESSION['prev_url']);
          }
        } else {
          status_message('error','You do not have sufficient funds for this operation!<br> fund your account!');
          }
      }
      
      echo 'You have <b class="green-text">'.$_SESSION['preferred_currency'].' '.convert_coins_to_user_currency($user_funds).'</b> in your account.';
      echo '<form method="post" action="'.$_SESSION['current_url'].'">
      <input type="hidden" name="project_id" value="'.$project_id.'">
      <input type="number" name="add_amount" class="form-control" placeholder="amount to add to budget">
      <input type="submit" name="fund_project" value="Save" class="btn btn-primary btn-xs">
      </form>';
      }
    }
  }
  
  
function show_project_transaction_history(){
  
  }
  
function get_office_allocated_funds($office_id=''){
  if(empty($office_id)){
    $office_id = sanitize($_GET['office_id']);
  }
  $value = query_db("SELECT allocated_funds FROM project_manager_office WHERE id='{$office_id}'",
  "Could not get office allocated funds ");
  return $value['result']['0']['allocated_funds'];
  }
  
function get_office_funded_budget($office_id=''){
  if(empty($office_id)){
    $office_id = sanitize($_GET['office_id']);
  }
  $value = query_db("SELECT funded_budget FROM project_manager_office WHERE id='{$office_id}'",
  "Could not get office funded budget ");
  
  return $value['result']['0']['funded_budget'];
  }

function get_project_available_funds(){
  $funds = get_project_funded_budget();
  $allocated = get_project_allocated_funds();
  $available = $funds - $allocated;
  return $available;
  }
  
function get_office_available_funds($office_id){
  $funds = get_office_funded_budget($office_id);
  $allocated = get_office_allocated_funds($office_id);
  $available = $funds - $allocated;
  return $available;
  }
  
function show_project_available_funds(){
  $available = get_project_available_funds();
  echo convert_coins_to_user_currency($available);
  }
  
function show_office_available_funds(){
  $available = get_office_available_funds();
  echo convert_coins_to_user_currency($available);
  }
  
function get_task_reward($task_id){
  if(empty($task_id)){
    $task_id = sanitize($_GET['task_id']);
    }
  $q = query_db("SELECT reward FROM project_manager_task WHERE id='{$task_id}'",
  "Could not get task reward ");
  return $q['result'][0]['reward'];
  }

function increase_office_budget($office_id=''){
  if(is_project_manager() && is_office_page()){
    if(empty($office_id)){
    $office_id = sanitize($_GET['office_id']);
    }
    $project_id = sanitize($_GET['project_id']);
    
    echo '<span class="tiny-text"><a href="'.$_SESSION['current_url'].'&increase_allocation_target='.$office_id.'">increase allocation</a></span>';
    
    if(url_contains('increase_allocation_target=')){
      if(isset($_POST['increase_allocation'])){
        $amount = sanitize($_POST['allocation_amount']);
        $project_id = sanitize($_POST['project_id']);
        $office_funded_budget = get_office_funded_budget();
        $total_project_allocated_funds = get_project_allocated_funds();
        $available_funds = get_project_available_funds();
        
        if($available_funds > $amount){
          $new_office_budget = $amount + $office_funded_budget; 
          $q = query_db("UPDATE project_manager_office SET funded_budget='{$new_office_budget}' WHERE id='{$office_id}'",
          "Could not increase office allocated budget ");
          
          $new_total_project_allocated = $total_project_allocated_funds + $amount;
          $q = query_db("UPDATE project_manager_project SET allocated_funds='{$new_total_project_allocated}' WHERE id='{$project_id}'",
          "Could not update total project allocated funds ");
          if($q){
            redirect_to($_POST['office_url']);
          }
        } else {
          status_message('error','Amount exceeds available Project funds');
          }
      }
      echo '<b>Available funds : ';convert_coins_to_user_currency(show_project_available_funds()); echo '</b>';
      echo '<form method="post" action="'.$_SESSION['current_url'].'">
      <input type="hidden" name="office_id" value="'.$office_id.'">
      <input type="hidden" name="project_id" value="'.$project_id.'">
      <input type="hidden" name="office_url" value="'.$_SESSION['prev_url'].'">
      <input type="number" name="allocation_amount" class="form-control" placeholder="amount to add to office budget">
      <input type="submit" name="increase_allocation" value="Save" class="btn btn-primary btn-xs">
      </form>';
    }
  }
}
  
function deduct_task_reward_from_office_allocated_funds($amount,$task_id){
  //~ Get office id
  $q = query_db("SELECT office_id FROM project_manager_task WHERE id='{$task_id}'",
  "Could not get task office id in increase task reward ");
  $office_id = $q['result'][0]['office_id'];
  echo 'Office id is = '.$office_id;
  //~ Get office available funds
  $office_allocated_funds = get_office_allocated_funds($office_id);
  $office_available_funds = get_office_available_funds($office_id);
  
  //~ Get Task reward amount
  if(is_task_page()){
  $task_reward = $_SESSION['task_reward'];
  }
  
  //~ Reduce allocated_funds by task_reward or die
  if($office_available_funds >= $task_reward){
    $new_office_allocated_funds = $office_allocated_funds + $task_reward;
    $q = query_db("UPDATE project_manager_office SET allocated_funds='{$new_office_allocated_funds}' WHERE id='{$office_id}'",
    "Could not update office allocated funds in increase task reward ");
  } else{
      status_message('error','No funds in office to pay for this task');
      die();
      } 
  return true;
  }

function add_project_office(){
  //Tasks are assigned to offices
  if(is_logged_in() && (is_project_manager() || is_admin())){
    if(isset($_GET['project_id'])){
      $project_id = sanitize($_GET['project_id']);
      $company= get_project_parent_company_id($project_id);
      $company_id = $company[0]['parent_id'];
      
      if(empty($company_id)){
        $company_id = 0;
        }
      
    }
    
    if(isset($_POST['add_office'])){
      $budget = trim(sanitize($_POST['budget']));
      $project_funds = get_project_allocated_funds($project_id='');
      if($project_funds > $budget){
      $office_title = trim(sanitize($_POST['office_title']));
      $job_description = trim(sanitize($_POST['job_description']));
      $status = strtolower(trim(sanitize($_POST['status'])));
      
      $company_id = trim(sanitize($_POST['company_id']));
      
      $q = query_db("INSERT INTO `project_manager_office`(`id`, `office_title`, `job_description`, `project_id`, `company_id`, `status`, `funded_budget`, `allocated_funds`) 
      VALUES ('0','{$office_title}','{$job_description}','{$project_id}','{$company_id}','{$status}','','')","Failed to create office ");
      if($q){
        session_message("success","Office saved!");
        redirect_to(ADDONS_PATH.'project_manager/?action=show&project_id='.$project_id);
        }
      } else {status_message('error','Project allocation is less than office budget');}
    } 
      
    
    if(url_contains('project_manager/?action=add_office')){
      //Show add office form
      echo '
      <h3>Add a new Office for '; link_to_project_by_id($project_id); echo' [PROJECT]</h3>';
      echo '
      <form method="post" action="'.$_SESSION['current_url'].'">
      <input type="text" class="form-control" name="office_title" placeholder="Office title eg Operations manager">
      <input type="hidden" name="company_id" value="'.$company_id.'">
      <textarea name="job_description" class="form-control" placeholder="Job description"></textarea>
      <select name="status" class="form-control">
      <option>Hiring</option>
      <option>Fully staffed</option>
      </select>';
      //~ if(addon_is_active('company')){
        //~ $value = get_user_companies();
        //~ print_r($value);
        //~ echo '<select name="company">';
      
        //~ foreach($value as $result){
          //~ echo '<option value="'.$result['id'].'">'.$result['company_name'].'</option>';
          //~ }
        //~ echo '</select>';
      //~ }
      echo '
      <div class="input-group">
      <span class="input-group-addon">'.$_SESSION['preferred_currency'].'</span>
      <input type="text" name="budget" class="form-control" placeholder="Office budget Leave empty if none">
      <span class="input-group-addon">.00</span>
      </div><br>
      <input type="submit" name="add_office" value="Save Office" class="btn btn-primary btn-md">
      </form>';
    }
  }
}

function get_num_project_offices($project_id){
  $value = query_db("SELECT COUNT(*) as count FROM project_manager_office where project_id='{$project_id}",
  "Could not get num project offices ");
  return $value['count'];
  }
  
function get_project_offices($project_id){
  $value = query_db("SELECT * FROM project_manager_office where project_id='{$project_id}'",
  "Could not get project offices ");
  return $value['result'];
  }
  
function get_project_name_by_id($office_id){
  $value = query_db("SELECT office_title FROM project_manager_office WHERE id='{$office_id}'",
  "Could not get project office name by id ");
  return $value['result']['office_title'];
  }
  
function link_to_project_by_id($project_id,$mode='show'){
  if(isset($_GET['project_id'])){
    $project_id = sanitize($_GET['project_id']);
    } 
  $value = query_db("SELECT project_name FROM project_manager_project WHERE id='{$project_id}'",
  "Could not get project office name in link to PROJECT by id ");
  if($mode == 'show'){
  echo '<a href="'.ADDONS_PATH.'project_manager/?action=show&project_id='.$project_id.'">'.ucfirst($value['result'][0]['project_name']).'</a>';
  } else if($mode == 'get'){
    return '<a href="'.ADDONS_PATH.'project_manager/?action=show&project_id='.$project_id.'">'.ucfirst($value['result'][0]['project_name']).'</a>';
  }
  }
  
function get_link_to_project_by_url(){
  if(isset($_GET['project_id'])){
    $project_id = sanitize($_GET['project_id']);
    } 
  $value = query_db("SELECT project_name FROM project_manager_project WHERE id='{$project_id}'",
  "Could not get project office name in link to PROJECT by id ");
  if(!empty($value['num_results'])){
  return '<a href="'.ADDONS_PATH.'project_manager/?action=show&project_id='.$project_id.'">'.strtolower($value['result'][0]['project_name']).'</a>';
  }
}
  
function link_to_task_by_id($task_id){
  $task = get_task_details($task_id);
  $project_id = $task['parent_id'];
  
  $value = query_db("SELECT task_name FROM project_manager_task WHERE id='{$task_id}'",
  "Could not get project task name in link to TASK by id ");
  echo '<a href="'.ADDONS_PATH.'project_manager/?action=show&task_id='.$task_id.'&project_id='.$project_id.'">'.ucfirst($value['result'][0]['task_name']).'</a>';
  }
  
function link_to_office_by_id($office_id,$mode='show'){
  $value = query_db("SELECT * FROM project_manager_office WHERE id='{$office_id}'",
  "Could not get project name in link to OFFICE by id ");
  if($mode == 'show'){
  echo '<a href="'.ADDONS_PATH.'project_manager/?action=show_office&office_id='.$office_id.'&project_id='.$value['result']['0']['project_id'].'">'.$value['result'][0]['office_title'].'</a>';
  } else if($mode == 'get'){
    return '<a href="'.ADDONS_PATH.'project_manager/?action=show_office&office_id='.$office_id.'&project_id='.$value['result']['0']['project_id'].'">'.$value['result'][0]['office_title'].'</a>';
    }
}
  
function get_link_to_office_by_url(){
  if(isset($_GET['office_id'])){
    $office_id = sanitize($_GET['office_id']);
    }
  $value = query_db("SELECT * FROM project_manager_office WHERE id='{$office_id}'",
  "Could not get project name in link to OFFICE by id ");
  if(!empty($value['num_results'])){
  return '<a href="'.ADDONS_PATH.'project_manager/?action=show_office&office_id='.$office_id.'&project_id='.$value['result']['0']['project_id'].'">'.$value['result'][0]['office_title'].'</a>';
  }
}
  
function get_project_office_details($office_id){
  $project_id = sanitize($_GET['project_id']);
  if(isset($_GET['office_id'])){
    $office_id = sanitize($_GET['office_id']);
    }
  $value = query_db("SELECT * FROM project_manager_office where id='{$office_id}' and project_id='{$project_id}'",
  "Could not get project offices ");
  return $value['result'];
  }
  
  
function show_office_page(){
  delete_office($office_id);
  $_SESSION['project_url'] = $_SESSION['prev_url'];
  if(url_contains('project_manager/?action=show_office&office_id') && !isset($_GET['del_office'])){
    $office_id = sanitize($_GET['office_id']);
    $value = get_project_office_details($office_id);
    $project = get_project_details($value['0']['project_id']);
    $company = get_company_details();
    
    echo ' <a class="pull-right inline-block" href="'.ADDONS_PATH.'project_manager/?action=show&project_id='.$value['0']['project_id'].'">&laquo project : '.ucfirst($project['0']['project_name']).'</a>';
    echo '<div class="row">
      <div class="col-md-12 inline-block">
      <h2 class="padding-10">'.$value[0]['office_title'].' Office</h2>';
      if(is_project_manager()){
        increase_office_budget();
        delete_office_team_member();
      }
      echo '
      <div class="page_content padding-10"><b>Job description : </b>'.$value[0]['job_description'].'
        <hr>
        <h4>Budget : '.convert_coins_to_user_currency($value[0]['funded_budget']).'</h4>
        <hr>
        <span class="green-text"><h4>Available funds : ';
        convert_coins_to_user_currency(show_office_available_funds()); 
        echo '</h4></span>
      </div>
      <div class="padding-10"></div>
      <div class="whitesmoke padding-10"><b>Current Status: '.$value[0]['status'].'</b></div>';
      show_change_office_status_form();
      
      $office_id = sanitize($_GET['office_id']);
      if(is_project_manager() || is_admin()){
        echo '<a href="'.ADDONS_PATH.'project_manager/?action=add_team_member&project_id='.$value['0']['project_id'].'&office_id='.$office_id.'">
        <div class="btn btn-primary btn-xs">Add Team Member</div></a>';
        
        echo '<a href="'.ADDONS_PATH.'project_manager/?action=remove_team_member&project_id='.$value['0']['project_id'].'&office_id='.$office_id.'">
        <div class="btn btn-danger btn-xs">Remove Team Member</div></a>';
        
        show_close_office_link();
        
        
      }
      echo '</div>
      </div>';
      if(is_project_manager() || is_admin()){
        echo '<div class="col-md-12">';
          
          echo '<div align="center"><a href="'.ADDONS_PATH.'project_manager/?action=add&type=task&project_id='.$project['0']['id'].'&office_id='.$office_id.'">
                <br><br><div>Add a new task for this office </div>
                </a></div><br>';
          echo '</div>';
    
      }
  }
}


function delete_office($office_id){
  
  if(isset($_GET['del_office']) && (is_project_manager() || is_admin())){
    if(isset($_GET['office_id'])){
      $office_id = sanitize($_GET['office_id']);
      }
    //first check that there are no team members
    $q = query_db("SELECT COUNT(id) FROM project_manager_team WHERE office_id='{$office_id}'",
    "Could not perform check for team members in delete office ");
    if(!empty($q['count'])){
      //check that there are no associated tasks
      $q = query_db("SELECT COUNT(id) FROM project_manager_task WHERE office_id='{$office_id}'",
      "Could not perform check for office tasks in delete office ");
      if(!empty($q['count'])){
        session_message('error','Cannot delete office - delete all tasks and team members first!');
        redirect_to($_SESSION['prev_url']);
        }
    } else {
       
        $q = query_db("DELETE FROM project_manager_office WHERE id='{$office_id}'",
        "Could not delete office ");
        if($q){
          status_message('success','Office Closed (deleted)');
          }
          if(isset($_GET['project_id'])){
            $project_id = sanitize($_GET['project_id']);
            }
        echo '<div align="center">Go back to '; 
        link_to_project_by_id($project_id); 
        echo '</div>';
      }
  }
  
}

function show_close_office_link(){
  if(isset($_GET['office_id'])){
            $office_id = sanitize($_GET['office_id']);
            }
   echo '<div class="pull-right">
   <a href="'.$_SESSION['current_url'].'&del_office='.$office_id.'"  
   class="pull-right tiny-text" onclick="return confirm(\'Are you ure you want to CLOSE this office? \n It will be DELETED and allocated Funds will NOT be returned!\')">close office</a></div>';
  }

function list_project_offices(){
  if(is_project_page()){
    //echo '<div class="col-md-12 padding-10">';
    $project_id = $_SESSION['project_id'];
    $value = query_db("SELECT * FROM project_manager_office where project_id='{$project_id}'",
    "Could not get project offices ");
    echo '<br><br><h3 align="center">Project Offices ('.$value['num_results'].')</h3>';
    echo '<ul>';
      foreach($value['result'] as $office){
        $total_num_tasks = get_num_office_tasks($office['id']);
        $num_pending = get_num_office_tasks($office['id'],'pending');
        $num_started = get_num_office_tasks($office['id'],'started');
        $num_submitted = get_num_office_tasks($office['id'],'submitted');
        $num_completed = get_num_office_tasks($office['id'],'completed');
      //  print_r($office);
      echo '<li>
      <a href="'.ADDONS_PATH.'project_manager/?action=show_office&office_id='.$office['id'].'&project_id='.$project_id.'">'
      .$office['office_title'].' ('.$total_num_tasks.' tasks)</a><br>
      <span class="tiny-text">pending:'.$num_pending.' started:'.$num_started.' submitted:'.$num_submitted.' completed:'.$num_completed.'
      <br><b>Team members :</b> ';
      $team = get_office_team_members($office['id']);
      foreach($team as $member){
        $user = link_to_user_by_id($member['user_id']);
        echo $user.', ';
        }
      echo '</span>
      </li><hr>';
      }
    echo '</ul>';
  }
  //echo '</div>';
}
  
  
function get_num_office_tasks($office_id='',$status=''){
  if(empty($status)){
    $condition = '';
    } else { $condition = " and status='{$status}'"; }
  $value = query_db("SELECT COUNT(id) as count FROM project_manager_task where office_id='{$office_id}' {$condition}",
  "Could not get num office tasks ");
  return $value['count'];
  }
  

function add_office_team_member(){
  if(url_contains('project_manager/?action=add_team_member&')){
    echo '<span class="inline-block pull-right "> &laquo; office : ';
    if(isset($_GET['office_id'])){
      $office_id = sanitize($_GET['office_id']);
      }
    link_to_office_by_id($office_id);
    echo '</span>';
    
      if (is_logged_in() && (is_project_manager() || is_admin())){	
        
        
        $show_more_pager = pagerize($start='',$show_more=20);
        $limit = $_SESSION['pager_limit'];
        
        if(isset($_GET['reallydo'])){
            $project_id = sanitize($_GET['project_id']);
            $office_id = sanitize($_GET['office_id']);
            
            //~ Add team member
            $username = trim(sanitize($_GET['reallydo']));
            $person = get_user_details($username);
            $q = query_db("INSERT IGNORE INTO `project_manager_team`(`id`, `user_id`, `project_id`, `office_id`, `team_lead`) 
            VALUES ('0','{$person['id']}','{$project_id}','{$office_id}','0')",
            "Failed to add team member in reallydo ");
            
            if($q){
              session_message("success","{$username} added to Office Team");
              redirect_to($_SESSION['prev_url']);
              }
          }
        
        if(isset($_POST['find_team_member'])){
          $username = trim(sanitize($_POST['username']));
          $value = query_db("SELECT * FROM user WHERE user_name  LIKE '%{$username}%' ORDER BY id DESC LIMIT 30 ",
          "Could not fetch users for add project team member ");
          $num = $value['num_results'];
        
          echo '<ul>';
          foreach($value['result'] as $result){
            //~ print_r($result);
            $skills= get_user_skills($result['user_name']);
            if($num){
              $picture = get_user_pic_plain($user,$pic_class='img-circular',$photo_with='50');	
              echo '<li>';
              echo "<div class='thumbnail inline-block margin-10'>
                <div class=''>"
                  //.'<a href="'.BASE_PATH.'user/?user='.$result['user_name'].'">&nbsp;&nbsp;'.$result['user_name'].'</a>'	
                  .'<a href="'.BASE_PATH.'user/?user='.$result['user_name'].'" title="'.$result['user_name'].'" alt="'.$result['user_name'].'">'
                  ."".'<img class="img-rounded" src="'.$picture.'">'.''
                ."</div>".
                substr($result['user_name'],0,8) ;
                if(addon_is_active('rewards')){
              $badge = get_reward_badge($result['user_name']);
              }
              echo '<br><span class="badge padding-10">'.$badge ."</span></div></a>";
              echo '<div class="inline-block tiny-text">
                      <div class="pull-left">
                      <b>Skills: </b>'.$skills['interests'].'<br>
                      <b>Work: </b>'.$person['work'].'<br>
                      <b>Teams: </b>'.$person['teams'].'<br>
                    </div>
                    <div class="pull-right">
                      <a class="pull-right" href="'.$_SESSION['current_url'].'&reallydo='.$result['user_name'].'">
                      <span class="btn btn-primary btn-xs">Add to office</span></a>
                    </div>
              </div>';
            } 
            echo '</li><hr>';
          }
          echo '</ul>';
          //echo $show_more_pager;
        }
        
        $info = '<h2 class="block">Find people to add to your team</h2>';
        echo '<div class="block">' .$info ;
        echo '<form method="post" action="'.$_SESSION['current_url'].'">
        <input type="search" name="username" class="form-control" placeholder="search by username">
        <input type="submit" name="find_team_member" value="Search" class="btn btn-primary btn-md">
        </form>
        </div>';
        
        
        
      } 

      
    }
  } 


function remove_office_team_member(){
  if(url_contains('project_manager/?action=remove_team_member&')){
    echo '<span class="inline-block pull-right "> &laquo; office : ';
    if(isset($_GET['office_id'])){
      $office_id = sanitize($_GET['office_id']);
    }
    link_to_office_by_id($office_id);
    echo '</span>';
    
    if (is_logged_in() && (is_project_manager() || is_admin())){	
      $show_more_pager = pagerize($start='',$show_more=20);
      $limit = $_SESSION['pager_limit'];
      
      if(isset($_GET['reallydoremove'])){
        $project_id = sanitize($_GET['project_id']);
        $office_id = sanitize($_GET['office_id']);
        
        //~ Remove team member
        $user_id = sanitize($_GET['reallydoremove']);
        $q = query_db("DELETE FROM project_manager_team WHERE user_id='{$user_id}' AND office_id='{$office_id}'",
        "Could not remove team member in remove office team member ");
        
        if($q){
          session_message("success","User removed from Office Team");
          redirect_to($_SESSION['prev_url']);
        }
      }
      
      if($_GET['action'] == 'remove_team_member'){
        if(isset($office_id)){
          $office_id = sanitize($_GET['office_id']);
        }
        $office_team_members = get_office_team_members($office_id);
        echo '<h4>Remove people from your team</h4>';
        echo '<br><ul>';
        //~ print_r($office_team_members);
        foreach($office_team_members as $member){
          //~ $picture = get_user_pic_plain($user,$pic_class='img-circular',$photo_with='50');	
          echo '<li>';
          link_to_user_by_id($member['user_id']);
          echo '<a class="pull-right" href="'.$_SESSION['current_url'].'&reallydoremove='.$member['user_id'].'">remove</a>';
          echo '</li><hr>';
        }
        echo '</ul>';
        //echo $show_more_pager;
      }
    } 
  }
} 


function get_project_team_members($project_id=''){
  if(empty($project_id)){
    if(isset($_GET['project_id'])){
      $project_id = sanitize($_GET['project_id']);
    } else if(isset($_SESSION['project_id'])){
        $project_id = $_SESSION['project_id'];
      }
  $value = query_db("SELECT DISTINCT user_id FROM project_manager_team WHERE project_id='{$project_id}' GROUP BY user_id",
  "Could not get project team members ");
  return $value['result'];
  }
}
  

function is_project_team_member($project_id=''){
  if(empty($project_id)){
    if(isset($_GET['project_id'])){
      $project_id = sanitize($_GET['project_id']);
    } else if(isset($_SESSION['project_id'])){
        $project_id = $_SESSION['project_id'];
      }
    }
    $user_id = $_SESSION['user_id'];
  //~ if(is_project_page()){
    $value = query_db("SELECT id FROM project_manager_team WHERE project_id='{$project_id}' AND user_id='{$user_id}'",
    "Could not check if is project team member ");
    if($value['num_results'] > 0){
      return true;
      } else {
        return false;
        }
    //~ }
}

function show_project_team_members($project_id=''){
  echo '<div class="inline-block col-md-12">';
  if(empty($project_id)){
    if(isset($_GET['project_id'])){
      $project_id = sanitize($_GET['project_id']);
    } else if(isset($_SESSION['project_id'])){
        $project_id = $_SESSION['project_id'];
      }
  if(is_project_page()){
    $value = query_db("SELECT DISTINCT user_id FROM project_manager_team WHERE project_id='{$project_id}' GROUP BY user_id",
    "Could not get project team members ");
    echo '<h3 align="center">Project team members('.$value['num_results'].')</h3>';
    foreach($value['result'] as $result){
     
      $is_mobile = check_user_agent('mobile'); 
      if(!$is_mobile){
        $user = get_user_by_id($result['user_id']);
        $pic = show_user_pic($user,'circle-pic ',50);
        $team_member = $pic['thumbnail'];
        echo '<span class="inline padding-5">'.$team_member.'</span>';
        } else { 
        $team_member = link_to_user_by_id($result['user_id']);
        echo $team_member.', ';
        }
      }
    }
  }
  echo '</div>';
}
  
function get_office_team_members($office_id=''){
  if(empty($office_id)){
    if(isset($_GET['office_id'])){
      $office_id = sanitize($_GET['office_id']);
      } else {
        $office_id = $_SESSION['office_id'];
        }
    }
  $value = query_db("SELECT DISTINCT user_id FROM project_manager_team WHERE office_id='{$office_id}'",
  "Could not get office team members ");
  return $value['result'];
}
  
function show_office_team_members($office_id=''){
  if(is_office_page()){
    echo '<div class="inline-block col-md-12">';
    if(empty($office_id)){
      if(isset($_GET['office_id'])){
        $office_id = sanitize($_GET['office_id']);
        } else {
          $office_id = $_SESSION['office_id'];
          }
      }
    $value = query_db("SELECT DISTINCT user_id FROM project_manager_team WHERE office_id='{$office_id}'",
    "Could not get office team members ");
    if(is_office_page()){
    echo '<h3 align="center">Office team members('.$value['num_results'].')</h3>';
    }
    foreach($value['result'] as $result){
      if(!$is_mobile){
          $user = get_user_by_id($result['user_id']);
          $pic = show_user_pic($user,'circle-pic ',50);
          $team_member = $pic['thumbnail'];
          echo '<span class="inline padding-5">'.$team_member.'</span>';
          } else { 
          $team_member = link_to_user_by_id($result['user_id']);
          echo $team_member.', ';
          }
    }
    echo '</div>';
  }
}

function delete_office_team_member(){
 
  $office_id = sanitize($_GET['office_id']);
  if(is_project_manager() || is_admin()){
    
    if(is_project_manager() && $_GET['remove-member'] == '2'){
      $user_id = sanitize($_POST['member_id']);
      $q = query_db("DELETE FROM project_manager_team WHERE user_id='{$user_id}' AND office_id='{$office_id}'",
      "Could not remove user from office ");
      if($q){
        session_message('alert','User removed! ');
        $url = str_ireplace('&remove-member=2','',$_SESSION['current_url']);
        redirect_to($url);
        }
    }
    if($_GET['remove-member'] == '1'){
      echo '<div class="thumbnail col-md-12 col-xs-12 block">
      <h4>Remove person from office team</h4>';
      $url = str_ireplace('remove-member=1','remove-member=2',$_SESSION['current_url']);
      echo '<form method="post" action="'.$url.'">';
      $members = get_office_team_members($office_id);
      echo '<select name="member_id">';
      foreach($members as $member){
        $user = get_user_by_id($member['user_id']);
        echo '<option value="'.$member['user_id'].'">'.$user.'</option>';
        }
      echo '</select>
      <input type="submit" name="del_member" value="remove person" class="btn btn-primary">
      </form>';
      echo '</div>';
      } else {
        echo '<a href="'.$_SESSION['current_url'].'&remove-member=1"><span class="inline-block tiny-text"> | remove person |</span></a>';
        }
    
  }
}

function transfer_project_office_member($user_id='',$office_id=''){
  
  }

function get_num_active_projects(){
  $value = query_db("SELECT count(id) as count from project_manager_project where status='started'",
  "Could not get num paid tasks");
  return $value['count'];
  }
  
  
function get_total_num_projects(){
  $value = query_db("SELECT count(id) as count from project_manager_project",
  "Could not get total num paid tasks");
  return $value['count'];
  }
  


function delete_project(){
	$project_id = sanitize($_SESSION['project_id']);
	if(isset( $_GET['del_project']) && (is_project_manager() || is_admin())){
    $project_id = sanitize($_GET['project_id']);
		$q = query_db("DELETE from project_manager_project where id='{$project_id}'",
    'Failed to delete project ');
		
		if($q){
			session_message('success','Project deleted!');
			redirect_to(ADDONS_PATH.'project_manager');
			}
		}
		
	if((is_project_manager() || is_admin()) && url_contains('action=show&project_id=')){
		echo '<a onclick="return confirm(\'Are you sure you want to delete this PROJECT?\')" class="pull-right tiny-text" href="'.$_SESSION['current_url'].'&del_project='.$project_id.'">delete project&nbsp</a>';
	}
}

function do_delete_task(){
  $destination[] = $_SESSION['prev_url'];
	$task_id = $_SESSION['task_id'];
  $office_id = get_task_office_id();
	if(isset($_GET['task_id']) && (is_project_manager() || is_admin()) && $_GET['action'] == 'del_task' && $_SESSION['task_status'] == 'pending'){
		$task_reward = get_task_reward($task_id);
    $office_allocated_funds = get_office_allocated_funds($office_id);
    $new_office_allocated_funds = $office_allocated_funds - $task_reward;
    //~ Return reward amount to office available funds
    $q = query_db("UPDATE project_manager_office SET allocated_funds='{$new_office_allocated_funds}' WHERE id='{$office_id}'",
    "Could not update office available funds in delete task");
    $q = query_db("DELETE from project_manager_task where id='{$task_id}'",
    'Failed to delete task ');
		if($q){
			session_message('success','task deleted!');
			redirect_to($_SESSION['prev_url']);
			} else {
      session_message('alert','Only pending tasks may be deleted');
      }
		} 
  }

function delete_task(){
	if(((is_project_manager() && $_SESSION['task_status'] == 'pending') && url_contains('action=show&task_id=')) || is_admin() && (url_contains('action=show&task_id='))){
		echo '<a onclick="return confirm(\'Are you sure you want to delete this TASK?\')" class="pull-right tiny-text" href="'.ADDONS_PATH.'project_manager/?action=del_task&task_id='.$_SESSION['task_id'].'">delete task&nbsp;</a>';
	}
}


function delete_grapevine_post($grapevine_id){
  if(isset($_GET['del-grapevine'])){
    $id = sanitize($_GET['del-grapevine']);
    $q = query_db("DELETE FROM project_manager_grapevine WHERE id='{$id}'",
    "Could not delete grapevine ");
    if($q){
      session_message('alert','Grapevine deleted');
      redirect_to($_SESSION['prev_url']);
    }
  }
  echo '
  <a href="'.$_SESSION['current_url'].'&del-grapevine='.$grapevine_id.'">
    <i class="tiny-text pull-right inline-block">delete</i>
  </a>';
}


function make_task($string=''){ // makes a project out of a post or other content type
	
	if($string === ''){
		$string = 'Make Task';
		}
	$task_name = strtolower(trim(sanitize($_GET['job_title'])));
	$parent = '';
	$parent_type = 'jobs';
	$content = sanitize($_SESSION['current_url']);
	$path = $_SESSION['current_url'];
	$author = $_SESSION['username'];
	$editor = '';
	$created = date('c');
	$deadline = '';
	$status = 'started';
	$reward = $_SESSION['job_reward'];
	
	echo '<form action='.$_SESSION['current_url'].' method="post">
	<button type="submit" name="make_project" value="yes">'.$string.'</button>
	</form>';
	
	if($_POST['make_task'] ==='yes') {
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `project_manager_task`(`id`, `task_name`, `parent`, `parent_type`, `content`, `author`, `project_manager`, `path`, `editor`, `created`, `deadline`, `assigned_to`, `status`, `priority`, `reward`) 
			VALUES ('0','{$task_name}', '{$parent}', '{$parent_type}' '{$content}', '{$author}', '{$author}', '{$path}', '{$editor}','{$created}','{$deadline}','{$author}','{$status}','{$priority}','{$reward}')") 
			or die ("Error inserting task ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			if($query){ 
				activity_record(
					$actor=$author,
					$action=' created the task ',
					$subject_name = $project_name,
					$actor_path = BASE_PATH.'user/?user='.$author,
					$subject_path= ADDONS_PATH.'project_manager/?action=show&task_name='.$project_name,
					$date = $created,
					$parent='jobs'
					);
				
				status_message("success", "Task saved successfully!"); 
			}
		}
	
	}

function edit_project(){
		
if( is_admin || is_author() || is_project_manager()){
	
		if(isset($_POST['project_name'])){
			$project_name = strtolower(trim(sanitize($_POST['project_name'])));
			}
		if(isset($_POST['content'])){
			$content = trim(sanitize($_POST['content']));
			}
		$updated = date('c');
		$path = $path = ADDONS_PATH."project_manager/?action=show&project_name={$project_name}"; 
		
		if($_POST['submit'] === 'Save project'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_project` SET `project_name`='{$project_name}', 
		`content`='{$content}',`path`='{$path}', `last_updated`='{$updated}' WHERE `id`='{$_SESSION['project_id']}'") 
		or die('Edit project failed! '. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		}
		if($query){
			session_message('success', 'Task edited successfully');
			$destination = $_SESSION['prev_url'];
	echo "<script> window.location.replace('{$destination}') </script>";
			}
		
		if($_GET['action'] === 'edit' && $_GET['project_name'] === $_SESSION['project_name']){
		echo '<h2 align="center">Editing Project : <em><a href="'.ADDONS_PATH.'project_manager/?action=show&project_name='.$_SESSION['project_name'].'">'.$_SESSION['project_name'].'</a></em></h2>
		<hr><form method="post" action="'.$_SESSION['current_url'].'">
		Name :<br><input type="text" name="project_name" value="'.$_SESSION['project_name'].'" placeholder="Project name"><br>
		Description: <br><textarea name="content" size="5"> '.$_SESSION['project_content'].'</textarea>
		<input type="submit" name="submit" value="Save project" class="button-primary">
		</form>	';
		}
		
		
	}
}

function show_edit_project_link(){
	if(is_logged_in() && is_author() && $_SESSION['project_status'] === 'pending'){
		if($_GET['action'] !== 'edit'){
			echo '<a class="u-pull-right" href="'.ADDONS_PATH.'project_manager/?action=edit&project_name='.$_SESSION['project_name'].'">Edit project&nbsp</a>';
		}
	}
}


function show_projects_list(){
	$query = $_SERVER['QUERY_STRING'];
	
	if(($_GET['action']==='show' && $_GET['type']==='project') || empty($query)){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `project_manager_project` ORDER BY `id` DESC LIMIT 30")
		or die ("! something is wrong with project lists" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$list = "<h2>Projects list</h2><hr><ol>";
    $num = mysqli_num_rows($query);
    
		
	while($result = mysqli_fetch_array($query)){
		// decorate based on status
		if($result['status'] === 'completed'){
			$pattern = 'pm-completed';
			} 
			elseif($result['status'] === 'started'){
			$pattern = 'pm-started';	
			}
			else {
			$pattern = 'pm-pending';	
				}
				
			$incomplete = has_incomplete_tasks($result['id']);
			if(!empty($incomplete['count'])){
				$count_num = "<em class='pull-right'><small>Has <strong>{$incomplete['count']}</strong> incomplete tasks</small></em>";
				}else {$count_num = "<em class='pull-right'><small>No pending tasks</small></em>";}
		$list = $list . " <li class='{$pattern}'><big> <a href='" .ADDONS_PATH 
		."project_manager/?action=show&project_id=" 
		.$result['id']  ."'>"
		.ucfirst($result['project_name']) 
		."</a></big>";
		//~ if(is_author() || is_admin() || is_project_manager()){
				$list = $list .
				"&nbsp&nbsp {$count_num} <br>
        <span class='badge'>Budget : ".$_SESSION['preferred_currency']. " ".convert_coins_to_user_currency($result['funded_budget'])."</span></li><hr>";
				//~ }
		
		} $list = $list . "</ol><br>";
		echo $list;
    if(empty($num)){
      echo '<em>There are no projects yet!';
      if(is_logged_in()){
       echo '<a href="'.ADDONS_PATH.'project_manager/?action=add&type=project">create one</a></em>';
       }
      }
	}
	
}

function show_project_page(){ 
	if($_GET['action']==='show' && is_project_page()){	
		go_to_project_manager();
		$project_id = sanitize($_GET['project_id']);
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `project_manager_project` WHERE `id`= '{$project_id}'");
		
		$result = mysqli_fetch_array($query);
		$_SESSION["project_manager"] = $result['project_manager'];
		$_SESSION['project_status'] = $result['status'];
		$_SESSION['project_author'] = $result['author'];
		$_SESSION['id'] = $result['id'];
		$_SESSION['project_id'] = $result['id'];
		$_SESSION['project_name'] = $result['project_name'];
		$_SESSION['project_content'] = $result['content'];
		$_SESSION['project_url'] = ADDONS_PATH.'project_manager/?action=show&project_id='.$result['project_id'];
		
		if($result['parent'] == 'company'){
		$query2 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT company_name, path FROM company WHERE id='{$result['parent_id']}'");
		$result2 = mysqli_fetch_array($query2);
		echo '
    <div class="inline-block">
    <h4>Company : <a href="'.ADDONS_PATH.'company/?company_name='.$result2['company_name'].'&action=show&tid='.$result['parent_id'].'">'.$result2['company_name'].'</a></h4>
    <h3>Project Name : '.ucfirst($result['project_name']).'</h3>
    </div>';
		
		} else {
		echo '<h3>Project Name : '.ucfirst($result['project_name']).'</h3>';
		}
    echo '<br><br>';
		show_start_project_button();
    if(is_project_manager()){
      add_funds_to_project($result['id']);
    }
		$incomplete = has_incomplete_tasks();
		if(!$incomplete['bool']){
		show_mark_as_complete_project_link();
		}
		
    
    echo '<div class="page_content"><b>Project Description: </b>';
    //~ $result['content'] .= '{show_images_in_lists}';
		$output = parse_text_for_output(urldecode($result['content']));
		echo $output;
		
		echo '<br><br><hr>';
    echo '<h4 class="">Budget : '.$_SESSION['preferred_currency'].' '.convert_coins_to_user_currency($result['funded_budget']).'</h4>';
    echo '<h4 class="green-text">Available Funds : '.$_SESSION['preferred_currency'].' ';show_project_available_funds(); echo '</h4><br>';
    echo '<span class="tiny-text">Project status : '.$result['status'].' </span>';
    show_status(); 
    echo '<br><strong> Project Manager : <a href="'.BASE_PATH.'user/?user='.$result['project_manager'].'">'.$result['project_manager'].'</a></strong>';
		echo '<br><br>';
    
    //~ Project photos
    //~ upload_no_edit(true);
    //~ $pics = get_linked_image($parent='project_manager',$parent_id=$result['id'],$pic_size='half',$limit='',$has_zoom='true',$for_slideshow='');
		//~ if(!empty($pics)){  
      //~ print_r($pics);
      //~ $switch = strpos($result['content'],'show_images_in_lists');
      //~ if($switch > 1){
        //~ //echo "yes i am inlists ";
        //~ show_images_in_list($images=$pics);
        //~ } else { 
        //~ show_slideshow_block($pics);
      //~ }
    //~ }
    
    echo '</div>';  
    delete_project();
    
    if(is_project_manager() || is_admin()){
      echo '<a href="'.ADDONS_PATH.'project_manager/?action=add_office&project_id='.$_SESSION['project_id'].'">
      <div class="btn btn-primary btn-sm">Add Office</div></a>';
    }
		
		if(addon_is_active('follow')){
			show_user_follow_button($child_name=$project_name,$parent='project');
			follow($child_name=$project_name);
			unfollow($parent='project',$child_name=$project_name);
		}
		
		
		if(is_project_manager()){ 
			if($_SESSION['project_status'] !== 'pending' 
			&& $_SESSION['project_status'] !== 'completed'
			&& $_SESSION['project_status'] !== 'disapproved' 
			&& $_SESSION['project_status'] !== 'submitted'){
      echo '
      <div class="row">
        <div class="col-md-12">';
          echo '
          <div align="center"><br>
            <a href="'.ADDONS_PATH.'project_manager/?action=add&type=task&project_id='.$_SESSION['project_id'].'">
              <div>Add a new task to this project </div>
            </a>
          </div>';
        echo '</div>';
      echo '</div>';
			}
		}
	} 
    
}

function add_task(){
	//~ print_r($_POST);
  $office_id = sanitize($_GET['office_id']);
	if(is_logged_in() && is_project_manager()){
		
		if($_GET['action']==='add' && (!empty($_GET['task_id']) || !empty($_GET['project_id']))){
			$task_name = trim(sanitize($_POST['task_name']));
			$project_name = trim(sanitize($_GET['project_name']));
			
			if (isset($_GET['project_id'])){
			$parent_id = sanitize($_GET['project_id']);	
			$project_id = sanitize($_GET['project_id']);	
			$parent = $_SESSION['project_name'];	
			$route = '?project_name=';	
			$parent_type = 'project';
			
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `project_manager` FROM `project_manager_project` WHERE `id`='{$project_id}'") 
			or die("Error selecting project_manager" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			$pm = mysqli_fetch_array($query);
			
			
		} else if (isset($_GET['task_id'])){
			$parent_id = sanitize($_GET['task_id']);	
			$route = '?task_name=';
			$parent_type = 'task';
		}
			$content = trim(sanitize($_POST['content']));
			$reward = trim(sanitize($_POST['reward']));
			$author = $_SESSION['username'];
			$project_manager = $pm['project_manager'];
			$_get_task_name = trim(sanitize($_POST['task_name']));
			$path = ADDONS_PATH.'project_manager/?action=show&task_name='.$_get_task_name;
			$editor = '';
			$created = date('c');
			$deadline = sanitize($_POST['deadline']);
			//~ $assigned_to = strtolower(trim(sanitize($_POST['assigned_to'])));
			$assigned_to = strtolower(trim(sanitize($_POST['team_member_assigned'])));
			$office_id = sanitize($_POST['office_id']);
			$status = 'pending';
			$priority = trim(sanitize($_POST['priority']));
			
		if(!empty($_GET['parent']) && !empty($_GET['parent_type'])){
			$parent = trim(sanitize($_GET['parent']));
			$parent_type = trim(sanitize($_GET['parent_type']));
			}
      $available_funds = get_office_available_funds();
			if($_POST['submit'] ==='Add task') {
        if($reward < $available_funds){
          $_get_task_name = trim(sanitize($_SESSION['task_name']));
          $_get_project_name = trim(sanitize($_SESSION['project_name']));
          
          $query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `project_manager_task`
          (`id`, `task_name`, `parent_id`, `parent_type`, `office_id`, `content`, `author`, `project_manager`, `path`, `editor`, `created`, `deadline`, `assigned_to`, `status`, `priority`, `reward`) 
          VALUES ('0','{$task_name}','{$parent_id}','{$parent_type}',{$office_id},'{$content}','{$author}','{$project_manager}','{$path}','{$editor}','{$created}','{$deadline}','{$assigned_to}','{$status}','{$priority}','{$reward}')") 
          or die ("Error inserting task ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
          
          
          if($query){ 
            //~ Update office available_funds
            $total_office_allocated_funds = get_office_allocated_funds();
            $new_total_office_allocated = $reward + $total_office_allocated_funds;
            $q = query_db("UPDATE project_manager_office SET allocated_funds='{$new_total_office_allocated}' WHERE id='{$office_id}'",
            "Could not update office allocated funds ");
            
            activity_record(
            $actor=$author,
            $action=' created the task ',
            $subject_name = $task_name,
            $actor_path = BASE_PATH.'user/?user='.$author,
            $subject_path= ADDONS_PATH.'project_manager/?action=show&task_id='.$task_id,
            $date = $created,
            $parent='project_manager'
            );
            
        
          
            status_message("success", "Task saved successfully!");
            if(isset($_GET['project_id'])){ echo '<h2 align="center">return to <a href="'.ADDONS_PATH.'project_manager/?action=show'.'&project_id='.$_SESSION['project_id'].'">'.$_get_project_name.'</a></h2>';}
            else if(isset($_GET['task_id'])){echo '<h2 align="center">return to <a href="'.ADDONS_PATH.'project_manager/?action=show'.'&task_id='.$_SESSION['task_id'].'">'.$_get_task_name.'</a></h2>';}
          }
        } else {
          status_message('error','Reward exceeds available funds!');
          }
			}

			if(isset($_GET['task_id'])){
				echo '<br><hr><h2> Add a Sub task to this Task : <a href="'.ADDONS_PATH.'project_manager/?action=show&task_id='.$_SESSION['task_id'].'">'.$_SESSION['task_name'].'</a></h2>';
			} else if(url_contains('addons/project_manager/?action=add&type=task&project_id=')){
        $office_id = sanitize($_GET['office_id']);
        $project_name = sanitize($_GET['project_name']);
				echo '<br><hr><h4> Adding a task to '; link_to_office_by_id($office_id);
        echo ' office : in '; link_to_project_by_id($parent_id) ;
        echo '</h4>';
				}
			
			echo '<hr><div class="gainsboro padding-20"><form method="post" action="'.$_SESSION['current_url'].'">
			<input type="text" name="task_name" class="form-control" value="" placeholder="Task name"> 
			Prioriy: 
			<select name="priority">
			<option>urgent</option>
			<option>medium</option>
			<option>low</option>
			</select>
			<input type="hidden" name="project_name" value="'.$project_name.'" placeholder="Project name"><br>
			Description: <br><textarea class="form-control" name="content" size="5"  placeholder="What is this task about ?"></textarea>
			<span>For Office : </span>';
      echo '<select name="office_id">';
      $offices = get_project_offices($project_id);
      foreach($offices as $office){
        echo '<option value="'.$office['id'].'"';
        if($_GET['office_id'] == $office['id']){
          echo ' selected';
        }
        echo '>'.$office['office_title'].'</option>';
        }
      echo'</select><br>
      Assigned to : <br>
      <select name="team_member_assigned">
      <option value="">nobody</option>
      <option value="all">All</option>';
      $office_team_members = get_office_team_members($office_id);
      foreach($office_team_members as $member){
        $username = get_user_by_id($member['user_id']);
        echo '<option>'.$username.'</option>';
        }
      echo '</select>
			Deadline : <input type="date" name="deadline" class="form-control" value=""><br>';
      echo '<b class="green-text">Available Funds : '; show_office_available_funds(); echo '</b><br>';
      echo'
			Reward : '.$_SESSION['preferred_currency'].'  <input type="number" name="reward" class="form-control" value="" placeholder="Reward for completing this task"><br>
			</span><input type="submit" name="submit" value="Add task" class="btn btn-primary">
			</form>	</div>';
			}
	}

}


function edit_task(){
	$path = $_SESSION['prev_url'];
	if( is_admin || is_author() || is_project_manager()){ 
		if(isset($_POST['task_name'])){
			$task_name = trim(sanitize($_POST['task_name']));
			}
		if(isset($_POST['content'])){
			$content = trim(sanitize($_POST['content']));
			}
			
		if(isset($_POST['assigned_to'])){
			$assigned_to = strtolower(trim(sanitize($_POST['assigned_to'])));
			}
		$updated = date('c');
		
		if($_POST['submit'] === 'Save task'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_task` SET `task_name`='{$task_name}', `path`='{$path}', 
		`content`='{$content}', `last_updated`='{$updated}',`assigned_to`='{$assigned_to}' WHERE `id`='{$_SESSION['task_id']}'") 
		or die('Edit task failed! '. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		}
		if($query){
			session_message('success', 'Task edited successfully');
			$destination = ADDONS_PATH.$_SESSION['page_context'].'/?action=show&task_name='.$_GET['task_name'];
			redirect_to($destination);
		//echo "<a class='text-center' href='". $_SESSION['prev_url']."'>Add another task</a>";
			
			}
		
		if($_GET['action'] === 'edit' && $_GET['task_name'] === $_SESSION['task_name']){
		echo '<h2 align="center">Editing Task :<em> <a href="'.$_SESSION['task_url'].'">'.$_SESSION['task_name'].'</a></em></h2><hr>
		<form method="post" action="'.$_SESSION['current_url'].'">
		Name :<br><input type="text" name="task_name" value="'.$_SESSION['task_name'].'" placeholder="Project name"><br>
		<input type="hidden" name="task_name" value="'.$_SESSION['task_id'].'" placeholder="Project name"><br>
		Description: <br><textarea name="content" size="5"> '.$_SESSION['task_content'].'</textarea><br>
		<input type="text" name="assigned_to" value="'.$_SESSION['task_assignee'].'" placeholder="assigned to"><br>
		<input type="submit" name="submit" value="Save task" class="button-primary">
		</form>	';
		}
		
		
	}
}

function show_edit_task_link(){
	if($_GET['action'] !== 'edit' && (is_author() || is_admin())){
		echo '<a class="u-pull-right" href="'.ADDONS_PATH.'project_manager/?action=edit&task_name='.$_SESSION['page_name'].'">Edit task&nbsp</a>';
		}
	};
	
function show_num_available_tasks(){
  $value = query_db("SELECT count(id) as count FROM project_manager_task WHERE assigned_to='' or assigned_to='all' AND reward !=''  and status!='completed'",
  "Could not get available jobs ");
  echo '<a href="'.ADDONS_PATH.'project_manager/?action=show-available-tasks">Available Tasks <span class="badge red">'.$value['count'].'</span></a>';

  }

function show_available_tasks($office_id=''){
  if((is_logged_in() && url_contains('/addons/project_manager/?action=show-available-tasks')) || (!is_logged_in() && !url_contains('/addons/project_manager/'))){
    if(!empty($office_id)){
      $condition = "office_id='{$office_id}' AND";
      } else {
        $condition = '';
        }
      if(!isset($_SESSION['username'])){
        $limit = 'LIMIT 0, 4';
      } else {
        $limit = '';
      }
    $value = query_db("SELECT * FROM project_manager_task WHERE {$condition} assigned_to='' or assigned_to='all' AND reward !='' and status!='completed' {$limit}",
    "Could not get available jobs ");
    if(!empty($value['num_results'])){
        echo '<ol>';
        echo '<h4>Available Tasks</h4><br>';
        foreach($value['result'] as $result){
          echo '<li><span class="inline-block">'; link_to_task_by_id($result['id']); 
          echo '</span>';
          echo '<span class="clear margin-3 label label-success pull-right inline-block">'.$_SESSION['preferred_currency'].' '.convert_coins_to_user_currency($result['reward']).'</span>';
          echo '</li><hr>';
          }
        echo '</ol>';
    }
  }
}

function show_task_list($priority='',$status=''){
  
  if($_GET['action'] !== 'add'){ // used to prevent sub tasks from showing when adding new tasks
    echo '<div id="task-list" class="padding-10"><br>';
     
    if(isset($_POST['status'])){
      $status = sanitize($_POST['status']);
      } else {
        $status = 'all';
        }
        
    if(url_contains('project_manager/?action=show_office&office_id=')){
      if(isset($_GET['project_id'])){
        $parent_id = sanitize($_GET['project_id']);
        $host = "office";
        $title = ucfirst($status).' Tasks /  duties for this ';
      } else if (isset($_GET['task_id'])){
      
        $parent_id = sanitize($_GET['task_id']);
        $host = 'task';
        $title = 'Sub tasks / duties for this ';
      } else { $parent_id = ''; 
        
      }
      $office_id = sanitize($_GET['office_id']);
      $condition1 = " `parent_id`='{$parent_id}' and office_id='{$office_id}'";
      
      if($priority !==''){
        $condition2 = " AND `priority`='{$priority}'";
      }else { $condition2 = "";}
     
      
      if($_POST['status'] == 'completed'){
        $selected = ' selected';
        } else if($_POST['status'] == 'started'){
          $selected = ' selected';
        } else if($_POST['status'] == 'disapproved'){
          $selected = ' selected';
        } else if($_POST['status'] == 'pending'){
          $selected = ' selected';
        } else {
          $selected = '';
          }
          
      if($status !=='' ){
        $condition3 = " AND `status`='{$status}'";
      } else { $condition3 = " AND `status`='pending'";}
      if($status =='all' ){
        $condition3 = "";
      }
      
     
      
      //~ if(is_office_team_member() || is_admin()){  
        if(isset($parent_id)){	
          $office_id = sanitize($_GET['office_id']);
          show_office_task_statistics($office_id);
           echo '<form method="post" action="'.$_SESSION['current_url'].'">
      <b>Sort tasks by : </b>
      <select name="status" onchange="this.form.submit()">
        <option selected>-</option>';
        if($_POST['status'] == 'completed'){
        echo '<option selected>completed</option>';
        } else {
          echo '<option>completed</option>';
          }
        if($_POST['status'] == 'started'){
        echo '<option selected>started</option>';
        } else {
          echo '<option>started</option>';
          }
        if($_POST['status'] == 'disapproved'){
        echo '<option selected>disapproved</option>';
        } else {
          echo '<option>disapproved</option>';
          }
        if($_POST['status'] == 'submitted'){
        echo '<option selected>submitted</option>';
        } else {
          echo '<option>submitted</option>';
          }
        if($_POST['status'] == 'pending'){
        echo '<option>pending</option>';
        } else {
          echo '<option>pending</option>';
          }
        if($_POST['status'] == 'all'){
        echo '<option selected>all</option>';
        } else {
           echo '<option>all</option>';
          }
        echo '
      </select>
      </form>';
          $query = query_db("SELECT * FROM `project_manager_task` 
          WHERE {$condition1} {$condition2} {$condition3} ORDER BY `id` DESC",
          "! something is wrong with task lists");
          $num = $query['num_results'];
          if( $num > 0){
            $list = "<h3 align='center'>{$title} {$host}</h3><ol>";
            $_SESSION['task_list_num'] = mysqli_num_rows($query);

            foreach($query['result'] as $result){
              // check for status and decorate display
              if($result['status'] === 'completed'){
              $pattern = 'pm-completed';
              } 
              elseif($result['status'] === 'started'){
              $pattern = 'pm-started';	
              }
              elseif($result['status'] === 'submitted'){
              $pattern = 'pm-submitted';	
              }
              elseif($result['status'] === 'disapproved'){
              $pattern = 'pm-disapproved';
              }
              elseif($result['status'] === 'pending') {
              $pattern = 'pm-pending';	
              }
              $incomplete = has_incomplete_tasks($result['id']);
              if($incomplete['count'] > 0){
                $count_num = "<em class='pull-right'><small>Has <strong>{$incomplete['count']}</strong> incomplete tasks</small></em>";
              }
              $_SESSION['task_parent_id'] = $result['parent_id'];
              if( $_SESSION['task_parent_id'] !== $result['id'] ){
                $deadline = time_elapsed(strtotime(str_ireplace('/','-',$result['deadline'])));
              $list = $list . "<li class='{$pattern}'><big> <a href='" .ADDONS_PATH ."project_manager/?action=show&task_id=" .$result['id']  ."&project_id={$result['parent_id']}'>".ucfirst($result['task_name']) ."</a></big>
              <span class='label label-success pull-right'> ".$_SESSION['preferred_currency']." ";
              $list .= convert_coins_to_user_currency($result['reward'])."</span>
              <span class='tiny-text text text-warning inline-block'>". $deadline." </span>
              &nbsp{$count_num}</li><hr>";
              $count_num = '';
              }
            } 
            $list = $list . "</ol>";
            
          } else {$list = '<i>Nothing to display</i>';}
          echo $list;
          
          echo '<br>';
          pm_color_codes();
        }
      //~ } else {
        //~ echo '<em >Only Office team members can view tasks!</em>';
      //~ }
     
    } 
    echo '</div>';
  } 
}


function get_task_details($task_id=''){
	if(empty($task_id)){
		$task_id = $_SESSION['task_id'];
		}
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `project_manager_task` WHERE `id`= '{$task_id}'");
	$result = mysqli_fetch_array($query);
	return $result;	
	}

function get_project_details($project_id=''){
	if(empty($project_id)){
		$project_id = $_GET['project_id'];
		}
	$value = query_db("SELECT * FROM `project_manager_project` WHERE `id`='{$project_id}'",
  "Could not get project details ");
	return $value['result'];	
	}
  
function get_project_parent_company_id($project_id=''){
  $value = query_db("SELECT parent_id FROM project_manager_project WHERE id='{$project_id}' and parent='company'",
  "Could not get Parent company id ");
  return $value['result'];
  }

function show_task_page(){
  // Catch reassignment operation
  if(isset($_POST['assigned_to'])){
    $task_id = sanitize($_GET['task_id']);
    $assigned_to = trim(sanitize($_POST['assigned_to']));
    $q = query_db("UPDATE project_manager_task SET assigned_to='{$assigned_to}' WHERE id='{$task_id}'",
    "FAiled to reassign task ");
    if($q){
      redirect_to($_SESSION['current_url']);
    }
  }
  if($_GET['action']==='show' && !empty($_GET['task_id'])){
		$task_id = trim(sanitize($_GET['task_id']));
		
		$result = get_task_details($task_id);	
    if($_SESSION['task_parent_type'] == 'project' ){
      $project_id = $_SESSION['task_parent_id'];
      $project = query_db("SELECT id,project_name,project_manager FROM project_manager_project WHERE id='{$project_id}'",
      "Failed to set parent project details ");
      $_SESSION["project_manager"] = $project['result'][0]['project_manager'];
      $_SESSION['project_status'] = $project['result'][0]['status'];
      $_SESSION['id'] = $project['result'][0]['id'];
      $_SESSION['project_id'] = $project['result'][0]['id'];
      $_SESSION['project_name'] = $project['result'][0]['project_name'];
      }
		$_SESSION['task_assignee'] = $result['assigned_to'];
		$_SESSION['task_author'] = $result['author'];
		$_SESSION['task_status'] = $result['status'];
		$_SESSION['task_id'] = $result['id'];
		$_SESSION['task_office_id'] = $result['office_id'];
		$_SESSION['task_name'] = $result['task_name'];
		$_SESSION['task_content'] = $result['content'];
		$_SESSION['task_parent_id'] = $result['parent_id'];
		$_SESSION['task_parent_type'] = $result['parent_type'];
		$_SESSION['task_reward'] = $result['reward'];
		$_SESSION['task_url'] = ADDONS_PATH.'project_manager/?action=show&task_name='.$result['task_name'].'&tid='.$result['id'];
		
		$project = get_project_details($result['parent_id']);
		if($result['parent_type'] === 'project'){ 
			$route1 = 'project_manager/';
			$route2 = '?action=show&project_id='.$result['parent_id'];
			$parent = $project['project_name'];
		} else if($result['parent_type'] === 'task') {
			$route1 = 'project_manager/';
			$route2 = '?action=show&task_id='.$result['parent_id'];
			$x = get_task_details($result['parent_id']);
			$parent = $x['task_name'];
		
		} else if($result['parent_type'] === 'jobs') {
			$route1 = 'jobs/';
			$route2 = '?job_title=';
		}
		
		echo '<span class="pull-right inline-block">&laquo; office: '; 
    link_to_office_by_id($result['office_id']); 
    echo'</span>';
    echo'
    <span class="inline-block">
    <h4 align="center">Showing Task for ';
    link_to_office_by_id($result['office_id']);
    echo ' office in ';
    link_to_project_by_id($result['parent_id']);
    echo ' project.</h4>
    </span>
    <div class="sweet_title">'.ucfirst($result['task_name']).'</div>';
		
  
		echo '<div class="page_content">';
		$output = parse_text_for_output(urldecode($result['content']));
		
		
		if($result['status']==='completed'){
			
		echo '<div class="completed" id="strikethrough">'. $output .'</div>';
		} else { echo $output; }
		
		echo '<br><b>Reward : </b>'.$_SESSION['preferred_currency'].' '.convert_coins_to_user_currency($result['reward']);
		echo '<br><br><hr>';
		show_status(); 
    if(empty($result['assigned_to'])){
      echo '<strong> Assigned to : nobody</strong>';
    } else if($result['assigned_to'] == 'all'){
      echo '<strong> Assigned to : All</strong>';
    }
    else {
        echo '<strong> Assigned to <a href="'.BASE_PATH.'user/?user='.$result['assigned_to'].'">'.$result['assigned_to'].'</a></strong>';
      }
      if((is_project_manager() || is_admin()) && $_SESSION['task_status'] != 'completed'){
        echo '<br><form method="post" action="'.$_SESSION['current_url'].'">
        <select name="assigned_to" onchange="this.form.submit()">';
        echo '<option value="all" selected>All</option>';
        $team_members = get_office_team_members($result['office_id']);
        foreach($team_members as $team_member){
          $username = get_user_by_id($team_member['user_id']);
          if($_SESSION['task_assignee'] == $username){
            echo '<option value="'.$username.'" selected>'.$username.'</option>';
            } else {
              echo '<option value="'.$username.'">'.$username.'</option>';
              }
        }
        echo '</select>';
        echo '<em>Assign or re-assign this task to any team member in this office.</em><br>
        </form>';
      } 
		echo '<br></div>';
    show_start_task_link();
		show_mark_as_complete_task_link();
		show_mark_as_failed_task_link();
		show_task_completed_link();
		show_task_failed_link();
		
		
		if($_result['status'] !== 'pending' 
			&& $_result['status'] !== 'completed' 
			&& $_result['status'] !== 'submitted' 
      && is_project_manager()){
    
      //~ echo '<br><div align="center"><a href="'.ADDONS_PATH.'project_manager/?action=add&type=task&task_id='.$task_id.'">
      //~ <div>Add a sub task to this task </div>
      //~ </a></div><br>';
    }
    
    apply_to_office($result['office_id']);
    
	}delete_task();
  
	if($_SESSION['task_status'] != 'pending'){
    show_notes_and_reports();
  }
}


//~ function show_task_submission_notes(){
  //~ if(($_SESSION['task_status'] === 'submitted'
  //~ || $_SESSION['task_status'] === 'started' 
  //~ || $_SESSION['task_status'] === 'disapproved' 
  //~ || $_SESSION['task_status'] === 'completed')
  //~ && is_project_manager() && url_contains('action=show&task_id=')){
      
    //~ $parent_id = sanitize($_GET['task_id']);

    //~ $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `project_manager_task_submissions` WHERE `parent_id`='{$parent_id}'") 
    //~ or die('FAiled to get submission notes!' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
    //~ echo '<br><div class="page-content"><h3>Submission notes</h3><ul>';
    //~ while($result = mysqli_fetch_array($query)){
      //~ $task_submission = $result;
      //~ $submission_id = $result['id'];
      //~ //set class based on status
      //~ if($result['status'] === 'disapproved'){
        //~ $class = 'pm-disapproved';
         //~ $approved = '';
      //~ } else if($result['status'] === 'submitted'){
        //~ $class = 'whitesmoke';
         //~ $approved = '';
      //~ } else if($result['status'] === 'completed'){
        //~ $class = 'pm-completed';
        //~ $approved = 'Approved';
      //~ }
      
      //~ echo "<li class='{$class} round-border padding-10'><b>[assignee</b> @{$result['author']}]: {$result['submission_note']}";
      //~ echo "<div class='white padding-10 round-border'><b>[project_manager</b> @{$_SESSION['project_manager']}]: {$approved} {$result['disapproval_note']}";
       //~ if($result['status'] == 'submitted' ){
          //~ echo "<span class='u-pull-right'> ";
          //~ show_mark_approved_link($task_submission);
          //~ show_mark_disapproved_link($task_submission);
          //~ echo '</span>';
        //~ echo "<br>{$result['created']} </li>";
		//~ show_disapprove_with_reason_form($submission_id);
        //~ } 
      
    //~ } echo "</ul></div>";
  //~ }

//~ }

function record_task_earnings($amount,$assignee){
  $user_id = $assignee;
  $task_id = sanitize($_GET['task_id']);
  $project_id = sanitize($_GET['project_id']);
  $timestamp = time();
  if(empty($amount)){
    $amount = 0;
    } else if(!is_numeric($amount)){
      $amount = 0;
      }
  //~ $q = query_db("SELECT id, amount from project_manager_task_earnings where user_id='{$user_id}' AND task_id='{$task_id}'",
  //~ "Could not check user present amount in record task earnings ");
  //~ if($q){
    //~ $amount = $q['result'][0]['amount'] + $amount;
    //~ }
  
  $q = query_db("INSERT INTO `project_manager_task_earnings`(`id`, `user_id`, `task_id`, `project_id`, `amount`, `timestamp`) 
  VALUES ('0','{$user_id}','{$task_id}','{$project_id}','{$amount}','{$timestamp}')",
  "Could not record task earnings ");
  }
  
function get_total_unpaid_task_earnings(){
  $user_id = $_SESSION['user_id'];
  $month = time() - (60*60*24*7*4);
  $q = query_db("SELECT SUM(amount) as sum FROM project_manager_task_earnings WHERE user_id='{$user_id}' AND timestamp>'{$month}' AND status!='paid'",
  "Could not select amount in show total unpaid task earnings ");
  if(!empty($q['result'][0]['sum'])){
    return $q['result'][0]['sum'];
  } else {
    return 0.00;
  }
}
  
function show_total_unpaid_task_earnings(){
  $output = get_total_unpaid_task_earnings();
  echo $output;
  }
  
function show_unpaid_earnings_history(){
  if($_GET['action'] == 'show-unpaid-earnings-history'){
    echo '<div class="col-md-12 col-xs-12 padding-10">';
    echo '<h3>My unpaid earnings history</h3><br>';
    $user_id = $_SESSION['user_id'];
    $value = query_db("SELECT task_id,amount FROM project_manager_task_earnings WHERE user_id='{$user_id}' and status=''",
    "Could not get task id in show unpaid earnings history ");
    foreach($value['result'] as $task_submission){
      $task_id = $task_submission['task_id'];
      $q = query_db("SELECT task_name,project_manager FROM project_manager_task WHERE id='{$task_id}'",
      "Could not get task details in show unpaid earnings history ");
      if(!empty($q['num_results'])){
        echo '<i class="glyphicon glyphicon-ok-circle green-text tiny-text"></i> ';
        echo '<span class="label label-success">'.$_SESSION['preferred_currency'].''.convert_coins_to_user_currency($task_submission['amount']) .'</span> was approved by ';
        link_to_user_by_username($q['result'][0]['project_manager']); 
        echo ' after you completed task [';
        link_to_task_by_id($task_id);
        echo '] <hr>';
      } 
    }
    if($value['num_results'] < 1){
      status_message('alert','You have no unpaid earnings at this time');
      }
    echo '</div>';
  }
}
  
function transfer_task_earnings_to_user_wallet(){
  if(is_admin()){
    echo '<a href="'.ADDONS_PATH.'project_manager/?action=pay-outstanding-earnings">
    <div class="">Pay Outstanding Earnings</div></a>';
  if($_GET['action'] == 'pay-outstanding-earnings'){
    // Get all users and amounts due
    $month = time() - (60*60*24*7*4);
    $value = query_db("SELECT id, user_id, amount FROM project_manager_task_earnings WHERE timestamp >'{$month}' and status !='paid'",
    "Could not get all users in transfer task earnings to user wallet ");
    foreach($value['result'] as $res){
      $user_id = $res['user_id'];
      //~ $q = query_db("SELECT amount  FROM project_manager_task_earnings WHERE user_id='{$user_id}' AND timestamp >'{$month}'",
      //~ "Could not select sum amounts in transfer task earnings to user wallet ");
      
      $id = $res['id'];
      $user = get_user_by_id($user_id);
      //~ print_r($q);die();
      $amount = $res['amount'];
      $date = date('M Y');
      $reason = 'Part of task earnings for '.$date;
      update_user_funds($user,$amount,$reason);
      $mark_paid = query_db("UPDATE project_manager_task_earnings SET status='paid' WHERE id='{$id}'",
      "Could not mark paid in transfer task earnings to user wallet ");
    }
    session_message('success','Users outstanding task earnings have been paid');
    redirect_to($_SESSION['prev_url']);
  }
  }
}

function show_unattended_task_submissions(){
  $value = query_db("SELECT task_id,content FROM project_manager_task_submissions WHERE status='submitted' ORDER BY id DESC LIMIT 5",
  "Could not get unattended task submissions ");
  if($value['num_results'] > 0){
    echo '<div class="padding-10 col-md-12 col-xs-12">
    <b>Task submissions (For Project Managers)</b><br>';
    foreach($value['result'] as $result){
      $task_id = $result['task_id'];
      $task = query_db("SELECT parent_id,parent_type,status FROM project_manager_task WHERE id='{$task_id}' ",
    "Could not get unattended task submissions ");
    $project_id = $task['result'][0]['parent_id'];
      if(is_project_manager($project_id) && $task['result'][0]['parent_type'] == 'project' && $task['result'][0]['status'] != 'completed'){
        echo '<b class="red-text"> * </b>' ;
        link_to_task_by_id($result['task_id']);
        echo ' &raquo; '.substr($result['content'],0,28);
        echo '<hr>';
        }
      }
      echo '</div>';
  }
}


function show_my_disapproved_tasks(){
  $user = $_SESSION['username'];
  $value = query_db("SELECT task_id,content FROM project_manager_task_submissions WHERE status='disapproved' AND author='{$user}' ORDER BY id DESC LIMIT 5",
  "Could not get my disapproved task submissions ");
  if($value['num_results'] > 0){
    echo '<div class="padding-10 col-md-12 col-xs-12">
    <b>Disapproved Tasks</b><br>';
    foreach($value['result'] as $result){
      $task_id = $result['task_id'];
      $task = query_db("SELECT parent_id,parent_type,status FROM project_manager_task WHERE id='{$task_id}' ",
    "Could not get my disapproved task submissions ");
    $project_id = $task['result'][0]['parent_id'];
      if($task['result'][0]['parent_type'] == 'project' && $task['result'][0]['status'] != 'completed'){
        echo '<b class="red-text"> * </b>' ;
        link_to_task_by_id($result['task_id']);
        echo ' &raquo; <span class="red-text">'.substr($result['content'],0,28);
        echo '<hr></span>';
        }
      }
      echo '</div>';
  }
}

function project_manager_menu(){
	echo '<a href="'.ADDONS_PATH.'project_manager/"><div>View Projects</div></a><br>';	
}




function go_to_project_manager(){
	echo '<a class="pull-right padding-10" href="'.ADDONS_PATH.'project_manager">&laquo; Projects</a>';
}

function show_add_task_button(){
	if(is_logged_in() && is_project_manager()){
	$project_id= $_SESSION['project_id'];
	echo '<div align="center"><a href="'.ADDONS_PATH.'project_manager/?action=add&type=task&project_id='.$project_id.'">
			<div>Add a new task to this project </div>
		</a></div><br>';
	}
}
	
	
function search_projects(){
	
		$s = $_SERVER['QUERY_STRING'];
	
	if($s ===''){
		
	echo '<div align="center" class="padding-10">
	<em>Click on a project to add tasks or suggestions</em>';
	
	echo '<h2>Search Projects</h2>';
	show_search_special_form($table='project_manager_project',$column='project_name');	
	echo '</div>';
	echo '<div class="center">';
	do_search($table='project_manager_project',$column='project_manager_project');
	echo '</div>';

	
	}
	
	if(false === $_GET['QUERY_STRING']){
		show_activity($parent='project_manager');
	}	
	
}
	
function show_add_project_link(){
	
	if(isset($_SESSION['username'])){
	echo '<a href="'.ADDONS_PATH.'project_manager/?action=add&type=project">
			<div>Add a new project</div>
		</a><br>';
	}
}

function show_start_project_button(){
	$project_id = $_SESSION['project_id'];
	
	if($_GET['status'] === 'start_project' && is_project_manager()){ 	
		if(!empty($_GET['project_id']) && empty($_GET['task_id'])){
			
			//if is a project page
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_project` SET `status`='started' 
			WHERE `id`='{$project_id}' AND `status`='pending'") or die('failed to start project' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			redirect_to($_SESSION['prev_url']);
		}
	} 
	
		// SHow Button
	if(!empty($_GET['project_id']) && empty($_GET['task_id']) && is_project_manager()){
		
		// if viewer is owner or manager then show 
		if( $_SESSION['project_status'] !== 'completed' && $_SESSION['project_status'] !=='started'){
			echo '<a href="'.$_SESSION['current_url'].'&status=start_project">
				<span class="u-pull-right"><button>Start project</button></span>
			</a>';
		}
	
	} 
}



function is_assignee(){
	if($_SESSION['task_assignee'] === $_SESSION['username']){
		return true;
		} else { return false; }
	
}


function is_project_manager($project_id=''){

  if(!empty($project_id)){
    $value = query_db("SELECT project_manager FROM project_manager_project WHERE id='{$project_id}'",
    "Could not check if is project manager ");
    if($_SESSION['username'] == $value['result'][0]['project_manager']){
      return true;
    } else {
      return false;
    }
  } else if(empty($project_id) 
  && !empty($_SESSION['project_manager']) 
  && $_SESSION['project_manager'] === $_SESSION['username']){
    return true;
  } else {
    return false; 
  }
}

function is_a_project($title=''){
	
	$query = query_db("SELECT id from project_manager_project where project_name='{$title}'");
	$num = $query['num_results'];
	if(!empty($num)){
		return true;
		} else {
      return false;
      }
	}
  
function is_project_page(){
  if(url_contains('project_manager/?action=show&project_id=')
      && !url_contains('task_id=')){
    return true;
    }
  }
  
function is_project_home(){
  if($_SESSION['current_url'] == ADDONS_PATH.'project_manager/'){
    return true;
    } else {
      return false;
      }
  }
  
  
function is_task_page(){
  if(url_contains('project_manager/?action=show&task_id=')){
    return true;
    }
  }
  
function is_office_page(){
  if(url_contains('project_manager/?action=show_office&office_id=')){
    return true;
    }
  }
  
  
function is_office_team_member($office_id=''){
  if(empty($office_id)){
    if(isset($_GET['office_id'])){
      $office_id = sanitize($_GET['office_id']);
    }
  }
  $user_id = $_SESSION['user_id'];
  $value = get_office_team_members($office_id);
  //print_r($value);
  $boolean = '';
  foreach($value as $member){
    //echo $member['user_id'];
    if($user_id == $member['user_id']){
      $boolean = true;
    } 
  }
   
  if(is_logged_in() && $boolean){
    return true;
  } else {
    return false;
  }
}

function get_user_offices($user_id=''){
  // returns an array
  $values = query_db("SELECT DISTINCT office_id, project_id FROM project_manager_team WHERE user_id='{$user_id}'",
  "Could not get user offices ");
  //print_r($values);
  return $values;
  }
  
function get_tasks_assigned_to_all_in_office($office_id){
  $values = query_db("SELECT id FROM project_manager_task 
  WHERE office_id='{$office_id}' and assigned_to='all'",
  "Could not get tasks assigned to all ");
  return $values['result'];
  }
  
function get_user_projects($user_id=''){
  // returns an array
  $values = query_db("SELECT DISTINCT project_id FROM project_manager_team WHERE user_id='{$user_id}'",
  "Could not get user Projects ");
  return $values;
  }

function is_a_task($title=''){
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id from project_manager_task where task_name='{$title}'") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$num = mysqli_num_rows($query);
	if(!empty($num)){
		return true;
		}
	}

function show_mark_as_complete_project_link(){
		if(is_logged_in() && is_project_manager() && ($_SESSION['project_status'] != 'completed' && $_SESSION['project_status'] !== 'pending')){
		if($_GET['status'] === 'complete_project'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_project` SET `status`='completed' WHERE `id`='{$_SESSION['project_id']}' ") 
		or die('cannot mark project as complete!' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		redirect_to($_SESSION['prev_url']);
		}
	
	echo '<a href="'.$_SESSION['current_url'].'&status=complete_project">
	<span class="u-pull-right"><button>Mark as Completed</button></span>
	</a>';
	}
}
	
function show_mark_as_complete_task_link(){

	if(is_logged_in() && is_project_manager() && $_SESSION['task_status'] === 'submitted'){
		
		if($_GET['status'] === 'complete_task'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_task` SET `status`='completed' WHERE `id`='{$_SESSION['task_id']}' ") 
		or die('cannot mark task as complete!' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		redirect_to($_SESSION['prev_url']);
		}
	
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'&status=complete_task">
		<div class="btn btn-primary btn-xs">Mark as Completed</div>
		</a>';
		
	}
}

function show_mark_as_failed_task_link(){

	if(is_logged_in() && is_project_manager() && $_SESSION['task_status'] === 'failed'){
		
		if($_GET['status'] === 'failed'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_task` SET `status`='failed' WHERE `id`='{$_SESSION['task_id']}' ") 
		or die('cannot mark task as failed!' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		redirect_to($_SESSION['prev_url']);
		}
		
	}
}

function show_cancel_task_link(){

	if(is_logged_in() && is_project_manager() && $_SESSION['task_status'] == 'pending'){
		
		if($_GET['status'] === 'complete_task'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_task` SET `status`='completed' WHERE `id`='{$_SESSION['task_id']}' ") 
		or die('cannot mark task as complete!' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		redirect_to($_SESSION['prev_url']);
		}
	
		echo '<a href="'.$_SESSION['current_url'].'&status=complete_task">
		<span class="u-pull-right"><button>Mark as Completed</button></span>
		</a>';
		
	}
}




function show_task_completed_link(){
	if(is_project_manager() && is_logged_in()){
    if($_SESSION['task_status'] === 'started' || $_SESSION['task_status'] === 'disapproved'){

      if($_GET['status'] === 'complete_task'){
        $parent = trim(sanitize($_POST['parent']));
        $id = $_SESSION['task_id'];
        $status = 'completed';
        $author = $_SESSION['username'];
        
        $query = query_db("UPDATE `project_manager_task` SET `status`='completed' WHERE `id`='{$id}' ",
        'Could not mark task as completed !');
        if($_SESSION['task_assignee'] != 'all'){
          record_task_earnings($amount);
        }
        redirect_to($_SESSION['prev_url']);
      } 
      else {
        // Show submit task button
        echo '<a href="'.$_SESSION['current_url'].'&status=complete_task">
       <div class="btn btn-success btn-xs">Mark Task completed</div>
        </a>';
      }

    }
	
		if($_SESSION['status'] === 'disapproved'){
			echo '<a href="'.$_SESSION['current_url'].'&status=submit_task">
			<div class="btn btn-success">Submit task</div>
			</a>';
		}

	}

}

function show_task_failed_link(){
	if(is_project_manager() && is_logged_in()){
    if($_SESSION['task_status'] === 'started' || $_SESSION['task_status'] === 'disapproved'){

      if($_GET['status'] === 'failed_task'){
        $parent = trim(sanitize($_POST['parent']));
        $id = $_SESSION['task_id'];
        $status = 'failed';
        $author = $_SESSION['username'];
        
        $query = query_db("UPDATE `project_manager_task` SET `status`='failed' WHERE `id`='{$id}' ",
        'Could not mark task as failed !');
        redirect_to($_SESSION['prev_url']);
      } 
      else {
        // Show submit task button
        echo '<a href="'.$_SESSION['current_url'].'&status=failed_task">
       <div class="btn btn-danger btn-xs">Mark Task Failed</div>
        </a>';
      }

    }
	
		if($_SESSION['status'] === 'disapproved'){
			echo '<a href="'.$_SESSION['current_url'].'&status=failed_task">
			<div class="btn btn-danger">Failed task</div>
			</a>';
		}

	}

}

function show_start_task_link(){
	if((is_assignee() || $_SESSION['task_assignee'] == 'all' || is_project_manager()) && $_SESSION['task_status'] === 'pending'){
		if($_GET['status'] === 'start_task'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_task` SET `status`='started' WHERE `id`='{$_SESSION['task_id']}' ") 
		or die('cannot start task! ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		if($query){
      $project_id = sanitize($_SESSION['task_parent_id']);
      
      query_db("UPDATE project_manager_project SET status='started' WHERE id='{$project_id}'",
      "Could not mark project as active in show start task link ");
      }
    redirect_to($_SESSION['prev_url']);
		}
	
    if($_SESSION['task_assignee'] == 'any' || is_assignee() || is_project_manager()){
      echo '<a href="'.$_SESSION['current_url'].'&status=start_task">
      <div class="btn btn-primary btn-sm"> Start task </div>
      </a>';
    }
		
	}
}

function show_mark_approved_link($task_submission){
  if(is_logged_in() && is_project_manager() && ($task_submission['status'] == 'submitted')){
     
    $amount = $_SESSION['task_reward'];
    $task_id = $_SESSION['task_id'];
   	if($_GET['status'] === 'approve_task' && $_GET['submission_id'] === $task_submission['id']){
      //~ $author = $task_submission['author';u];
      $id = $task_submission['id'];
      if($_SESSION['task_assignee'] == 'all'){
        //~ Since task is assigned to all, increment task reward every time a new task submission
        //~ is approved so that if there is no more money in the office
        //~ then the submission approval will fail
        //~ so as to prevent beating the system . Important! 
        $state = deduct_task_reward_from_office_allocated_funds($amount,$task_id);
        if($state){
          $query = query_db("UPDATE `project_manager_task_submissions` SET `status`='approved' WHERE `id`='{$id}'",
          "Could not approve task submission! ");
          $task_submitter_details = get_user_details($task_submission['author']);
          $assignee = $task_submitter_details['id'];
          record_task_earnings($amount,$assignee);
          redirect_to($_SESSION['prev_url']);
        }
      } else {
        $query = query_db("UPDATE `project_manager_task_submissions` SET `status`='approved' WHERE `id`='{$id}'",
        "Could not approve task submission! ");
        $query = query_db("UPDATE `project_manager_task` SET `status`='completed' WHERE `id`='{$task_id}' ",
        'Could not set task completed! ');
        $assignee_details = get_user_details($_SESSION['task_assignee']);
        $assignee = $assignee_details['id'];
        record_task_earnings($amount,$assignee);
        redirect_to($_SESSION['prev_url']);
      }
    } 
    
    echo '<a href="'.$_SESSION['current_url'].'&status=approve_task&submission_id='.$task_submission['id'].'">'.
    '&nbsp;<span class="pull-right btn btn-success btn-xs"> Approve </span>
    </a>';
  }
}

function show_mark_disapproved_link($task_submission){	
	if(is_logged_in() && is_project_manager() && $task_submission['status'] == 'submitted'){
    $amount = $_SESSION['task_reward'];
    $task_id = $_SESSION['task_id'];
		if($_GET['status'] == 'disapprove_task' && $_GET['submission_id'] == $task_submission['id']){
      $id = $task_submission['id'];
      if($_SESSION['task_assignee'] != 'all'){
        $query = query_db("UPDATE `project_manager_task` SET `status`='disapproved' WHERE `id`='{$task_id}' ",
        'Could not set task disapproved! ');
        $query = query_db("UPDATE `project_manager_task_submissions` SET `status`='disapproved' WHERE `id`='{$id}'",
        "Could not set task disapproved! ");
      } else {
      $query = query_db("UPDATE `project_manager_task_submissions` SET `status`='disapproved' WHERE `id`='{$id}'",
        "Could not set task disapproved! ");
      }
      redirect_to($_SESSION['prev_url']);
    }
    
     
    echo '<a href="'.$_SESSION['current_url'].'&status=disapprove_task&submission_id='.$task_submission['id'].'">'.
      '<span class="pull-right btn btn-danger btn-xs"> Disapprove </span>
      </a>';
    
  } 
  
}


function show_status(){
  if(url_contains('action=show&task_id=')){
	  $substitute = 'task';
	  } else if(url_contains('action=show&project_id=')){
		  $substitute = 'project';
		  }
	if($_SESSION["{$substitute}_status"] === 'pending'){
	$status = 'pending';
	$pattern = 'pm-pending';
	} else if($_SESSION["{$substitute}_status"] === 'started' ){
	$status = 'started';
	$pattern = 'pm-started';
	} else if($_SESSION["{$substitute}_status"] === 'completed' ){
	$status = 'completed';
	$pattern = 'pm-completed';
	} else if($_SESSION["{$substitute}_status"] === 'submitted' ){
	$status = 'submitted';
	$pattern = 'pm-submitted';
	} else if($_SESSION["{$substitute}_status"] === 'failed' ){
	$status = 'failed';
	$pattern = 'pm-failed';
	} else if($_SESSION["{$substitute}_status"] === 'disapproved' ){
	$status = 'disapproved';
	$pattern = 'pm-disapproved';
	}
	
	echo "<div class='{$pattern} inline-block'> </div>";
}

function has_incomplete_tasks($project_id=''){
	
	$values = array('started','submitted','disapproved','pending');
	$table = 'project_manager_task';
	if($project_id === ''){
	$project = $_SESSION['project_id'];	
	} else { $project = $project_id; }
	$count = 0;
	
	foreach($values as $value){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id` FROM `{$table}` WHERE `parent_id`='{$project}' 
	AND `status`='{$value}'") or die("Nothing selected!" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	$num = mysqli_num_rows($query);	
	$count = $count + $num;
	} 	

	if ($count > 0){
		//echo "has {$count} incomplete tasks!";
		$bool = true;
	} else { //echo 'does not have incomplete tasks';
		 $bool = false;
	}
		$returns = array('count'=>$count, 'bool'=>$bool);
		return $returns;
}


function my_assigned_tasks(){
	$user = $_SESSION['username'];
	
	if(is_logged_in()){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `project_manager_task` WHERE `assigned_to`='{$user}' ORDER BY `id` ASC");
		$num = mysqli_num_rows($query);

		echo "<div>";
		while($result = mysqli_fetch_array($query)){
		if($result['status'] === 'pending'){
		  echo '<li class="pm-pending left-pad-one"><a href="'.ADDONS_PATH.'project_manager/?action=show&task_name='.$result['task_name'].'">'.$result['task_name'].'</a></li>';
		} else if($result['status'] === 'submitted'){
		  echo '<li class="pm-submitted left-pad-one"><a href="'.ADDONS_PATH.'project_manager/?action=show&task_name='.$result['task_name'].'">'.$result['task_name'].'</a><span class="tiny-text pull-right">'.$result['priority'].'</span></li>';
		} else if($result['status'] === 'disapproved'){
		  echo '<li class="pm-disapproved left-pad-one"><a href="'.ADDONS_PATH.'project_manager/?action=show&task_name='.$result['task_name'].'">'.$result['task_name'].'</a><span class="tiny-text pull-right">'.$result['priority'].'</span></li>';
		} 
			
			}
		
		echo "</ul>";
		echo "</div>";
		if(empty($num)){
		  echo '<em>You have no assigned tasks at this time.</em>';  
		}
    }
}


function place_bid(){
	if(!has_bidded() && !empty($_GET['job_title']) 
	&& $_SESSION['job_status'] != 'accepted' 
	&& !is_author()
	&& is_logged_in()){
	$user = $_SESSION['username'];
	$jid = trim(sanitize($_GET['jid']));
	echo '<form method="post" action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" class="gainsboro padding-20">
	<h2 align="center">Apply / Bid for this job</h2>
		<input type="hidden" name="owner" value="'.$user.'">
		<input type="hidden" name="job_id" value="'.$jid.'">
		My Bid : (<em>Tell the job poster why you should get this job and what you will do to make it happen</em>)<textarea name="bid"></textarea>
		<br><input type="text" value="" name="expected_delivery_time" placeholder="Expected delivery time">
		<input type="text" value="" name="location" placeholder="Present location">
		<input type="submit" value="Place bid" name="submit">
		</form>';
	} else{
		if(!is_logged_in()){
		log_in_to_continue();
		status_message('alert','You may not bid, unless you are logged in');
		}
		}
}


function show_notes_and_reports(){
  if(is_task_page() && is_logged_in()){
    if($_POST['submit'] == 'Submit task note' 
    || $_POST['submit'] == 'Respond or leave a note' 
    || isset($_POST['submit_task'])){
      
      $content = trim(sanitize($_POST['task_note']));
      $task_id = trim(sanitize($_SESSION['task_id']));
      $author = $_SESSION['username'];
      $date = date('c');
      
      if($_POST['submit_task'] == 'Submit Task'){
      $status = 'submitted';
      } else {
        $status = '';
        }
      
      $file_path = upload_file('addons/project_manager/files');
      //~ echo $file_path; die();
      $q = query_db("INSERT INTO `project_manager_task_submissions`(`id`, `task_id`, `content`, `file`, `status`, `author`, `created`) 
      VALUES ('0','{$task_id}','{$content}','{$file_path}','{$status}','$author','{$date}')",
      "Could not add task note");
      
      redirect_to($_SESSION['current_url']);
    }
      
    if((is_assignee() || is_project_manager()) 
    && !empty($_GET['task_id'])){
    
    if(is_project_manager()){
    $button_value = 'Respond or leave a note';
    if(empty($_SESSION['task_assignee'])){
      $assignee = 'any';
      }elseif($_SESSION['task_assignee'] == 'all'){
      $assignee = 'all';
      } else {
        $assignee = '<a href="'.BASE_PATH.'user?user='.$_SESSION['task_assignee'].'">'.$_SESSION['task_assignee'].'</a>';
        }
    $help_message = 'Leave a note for : <b>'.$assignee.'</b><br>';
    } else if(is_assignee() || $_SESSION['task_assignee'] == 'all'){
      $help_message = 'Report to  : <a href="'.BASE_PATH.'user?user='.$_SESSION['author'].'">'.$_SESSION['author'].'</a><br>
      Situation report ';
      $button_value = 'Submit task note';
      }
      
    echo '<br><br><h3>Notes and Reports</h3>';
    
    if($_SESSION['task_status'] != 'completed' && $_SESSION['task_status'] != 'failed' ){
      echo '<div class="padding-20 edit-form">
      <form method="post" action="'.$_SESSION['current_url'].'" enctype="multipart/form-data">'.
      $help_message.
      '<textarea name="task_note" required></textarea><!-- MAX_FILE_SIZE must precede the file input field -->
      <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
      <input type="hidden" name="uploading-form" value="upload-file-form" />
      <input type="file" id="file_field" size="500" name="file_field" value="">
      <input type="submit" name="submit" class="btn btn-primary" value="'.$button_value.'">';
      
      $office_available_funds = get_office_available_funds($_SESSION['task_office_id']);
      
      //~ if task is assigned to all, check if office has money to pay before 
      //~ allowing user to submit task, if office does not have money, warn user not to 
      //~ attempt task because he or she may not get paid
      if ($_SESSION['task_assignee'] == 'all' && $_SESSION['task_reward'] > $office_available_funds){
        status_message('alert','This TASK is assigned to all but this office has no funds 
        to pay additional submissions. <br>
        So UNLESS you want to peform this task for free, DO NOT START.');
        } else if($_SESSION['task_reward'] < $office_available_funds){
        echo'<input type="submit" name="submit_task" class="btn btn-success" value="Submit Task">';
      } 
      echo'
      </form></div>';
    }
    
    }
     //~ Get Task Notes
      if(is_admin() || $_SESSION['task_assignee'] == $_SESSION['username'] || is_project_manager()){
        $task_id = trim(sanitize($_GET['task_id']));
        
        //~ echo '<h3>Task notes</h3>';
        echo '<table><tbody>';
        $values = query_db("SELECT * FROM project_manager_task_submissions WHERE task_id='{$task_id}' ORDER BY id DESC",
        "Could not get task notes ");
        if(empty($values['num_results'])){
          echo '<em>-- No notes or reports --</em>';
          }
        
        foreach($values['result'] as $result){
          $pattern = '';
          if($result['status'] === 'approved'){
              $pattern = 'pm-completed';
              } 
              elseif($result['status'] === 'started'){
              $pattern = 'pm-started';	
              }
              elseif($result['status'] === 'submitted'){
              $pattern = 'pm-submitted';	
              }
              elseif($result['status'] === 'disapproved'){
              $pattern = 'pm-disapproved';
              }
              elseif($result['status'] === 'failed'){
              $pattern = 'pm-failed';
              }
              elseif($result['status'] === 'pending') {
              $pattern = 'pm-pending';	
              }
          $author = $result['author'];
          $pic = show_user_pic($result['author'],'img-circular','35px');
          echo '<tr><td class="table-message-sender">'.$pic['thumbnail'].'</td><td class="table-message-plain '.$pattern.'">'.
          "<time class='timeago tiny-text u-pull-right green-text' datetime='".$result['date']."' title='".$result['date']."'></time><br>"
          .parse_text_for_output($result['content']);
          
          if(!empty($result['file'])){
            echo '<br><span class="tiny-text">';
            echo '<a href="'.$result['file'].'">Attached file</a>';
            echo '</span>';
          } 
          
            if($_SESSION['task_status'] != 'pending' && $_SESSION['task_status'] != 'completed' 
             && $result['status'] == 'submitted'){
              
              show_mark_approved_link($result);
              show_mark_disapproved_link($result);
              
            }
          echo '</td></tr>';
        }
      }
        echo '</tbody></table><br>';
        echo '<br>';
        pm_color_codes();
  }
}

function show_project_grapevine(){
  if((is_project_page() || is_office_page()) && (is_project_team_member() || is_admin())){
    $project_id = sanitize($_GET['project_id']);
    $office_id = sanitize($_GET['office_id']);
    if($_POST['post_to_grapevine'] == 'Say something' || $_POST['submit'] == 'Respond or leave a message'){
      $content = trim(sanitize($_POST['grapevine_post']));
      $author = $_SESSION['username'];
      $date = date('c');

      $file_path = upload_file('addons/project_manager/files');
      //~ echo $file_path; die();
      $q = query_db("INSERT INTO `project_manager_grapevine`(`id`, `project_id`, `office_id`, `author`, `content`, `file`, `date`) 
      VALUES ('0','{$project_id}','{$office_id}','{$author}','{$content}','{$file_path}','{$date}')",
      "Could not add task note");
      
      redirect_to($_SESSION['current_url']);
    }
      
    echo '<h3>Grapevine</h3>';
    if(is_project_page()){
      $target = 'project';
      } elseif(is_office_page()){
        $target = 'office';
        }
    echo '<div class="padding-20 edit-form">
    <form method="post" action="'.$_SESSION['current_url'].'" enctype="multipart/form-data">'.
    '<b>Talk to '.$target.' team</b> 
    <br><textarea name="grapevine_post"></textarea><!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    <input type="hidden" name="uploading-form" value="upload-file-form" />
    <input type="file" id="file_field" size="500" name="file_field" value="">
    <input type="submit" name="post_to_grapevine" value="Say something">
    </form></div>';
  
      //~ Get Grapevine posts
  
    echo '<table><tbody>';
    $condition = '';
    if(isset($_GET['project_id'])){
      $project_id = sanitize($_GET['project_id']);
      $condition = "WHERE project_id='{$project_id}'";
    }
      
    if(is_project_page() && !isset($_GET['office_id'])){
      $condition .= "AND office_id='0'";
    } 
    if(isset($_GET['office_id']) && is_office_page()){
    $office_id = sanitize($_GET['office_id']);
    $condition .= "AND office_id='{$office_id}'";
    }
    
    $values = query_db("SELECT * FROM project_manager_grapevine {$condition} ORDER BY id DESC LIMIT 10",
    "Could not get grapevine posts ");
    foreach($values['result'] as $result){
      if(is_office_page()){
        $recipient = link_to_office_by_id($result['office_id'],$mode='get') ;
        $team = ' office team';
        } else if(is_project_page()){
          if(!empty($result['office_id'])){
          $recipient = link_to_office_by_id($result['office_id'],$mode='get') ;
          $team =  ' office team';
          } else {
            $recipient = link_to_project_by_id($result['project_id'],$mode='get') ;
            $team = ' project team';
            }
          }
      
        $top_message = '<span class="tiny-text">'.$result['author'].' > '.$team .' '. strtolower($recipient) .'</span>';
      
      //~ $pic = show_user_pic($result['author'],'circle-pic ',50);
      echo '<tr>'.
      //~ <td class="table-message-sender">'.$pic['thumbnail'].'</td>
      '<td class="table-message-plain">';
      echo $top_message ."<time class='timeago tiny-text u-pull-right green-text' datetime='".$result['date']."' title='".$result['date']."'></time><br>".parse_text_for_output($result['content']);
      if(!empty($result['file'])){
      echo '<br> <span class="tiny-text">';
      echo ' <a href="'.$result['file'].'">Attached file</a>';
      echo '</span>';
      }
      if($result['author'] == $_SESSION['username'] || is_project_manager() || is_admin()){
        delete_grapevine_post($result['id']);
        }
      echo '</td></tr>';
    }
    
    echo '</tbody></table><br>';
  }
}

function show_latest_posts_from_my_grapevines($limit='1'){
  $today = date('Y-m-d');
  $author = $_SESSION['username'];
  $value = query_db("SELECT * FROM project_manager_grapevine WHERE date LIKE '%{$today}%' AND author !='{$author}' ORDER BY id DESC LIMIT {$limit}",
  "Could not get latest posts from my grapevines ");
  //~ print_r($value);
  if(!empty($value['num_results'])){
    echo '<div class="padding-10">';
    echo '<b class="tiny-text">Today\'s Grapevines</b>';
    echo '<table class="">';
    foreach($value['result'] as $result){
      if(is_office_team_member($result['office_id']) || is_project_team_member($result['project_id'])){
        if(empty($result['office_id'])){
          //~ Link to project grapevine
          $recipient = link_to_project_by_id($result['project_id'],$mode='get') ;
          $team = ' project team';
          
          } else {
            //~ Link to office grapevine
            $recipient = link_to_office_by_id($result['office_id'],$mode='get') ;
            $team =  ' office team';
            }
          echo '<tr>'.
          //~ <td class="table-message-sender">'.$pic['thumbnail'].'</td>
          '<td class="table-message-plain">';
          echo '<span class="tiny-text">'; link_to_user_by_username($result['author']); 
          echo ' > '.$team .' '. strtolower($recipient) .'</span>';
          echo "<time class='timeago tiny-text u-pull-right green-text' datetime='".$result['date']."' title='".$result['date']."'></time><br>".parse_text_for_output($result['content']);
          if(!empty($result['file'])){
          echo '<br> <span class="tiny-text">';
          echo ' <a href="'.$result['file'].'">Attached file</a>';
          echo '</span>';
          }
        echo '</td></tr>';
      }
        echo '</table>';
      } echo '</div>';
  }
  }

function has_bidded(){
	$user = $_SESSION['username'];
	if(isset($_GET['jid'])){
	$jid = trim(sanitize($_GET['jid']));
	} else if(isset($_SESSION['job_id'])){
		$jid = trim(sanitize($_SESSION['job_id']));
		}
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`,`owner` FROM `jobs_bids` WHERE `job_id`='{$jid}' AND `owner`='{$user}'") 
	or die('Failed to check if user has placed bid! ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	//$result = mysql_fetch_array($query);
	$_SESSION['has_bidded'] = true;
	
	$result = mysqli_num_rows($query);
	if(!empty($result)){
		return true;
		} else {
		return false;	
			}	
}



function show_my_completed_tasks(){
	if(is_user_page()) { // if page viewed is a user page
	 $user = $_SESSION['user_being_viewed'];
	
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `project_manager_task` WHERE `assigned_to`='{$user}' 
		AND `status`='completed' ORDER BY `id` DESC LIMIT 5") 
		or die("Failed to get completed tasks!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		$num = mysqli_num_rows($query);
		
		echo '<hr><br><h3>My Recently Completed Office Tasks</h3>';
		if(empty($num)){
			echo '<br><em class="tiny-text">- no completed tasks! -</em>';
		} else if($query){
			while($result = mysqli_fetch_array($query)){
			echo '<a href="'.ADDONS_PATH.'project_manager/?action=show&task_id='.$result['id'].'&project_id='.$result['parent_id'].'">'.$result['task_name'].'</a>, &nbsp ';	
			}
		
		} 
		echo '<br><br><hr>';
	}
}

function show_my_assigned_tasks(){
  //~ echo '<div class="row">';
    echo '<div class="col-md-12 col-xs-12 pull-left padding-10">';
    if(is_logged_in()){
      $user = $_SESSION['username'];
      $user_id = $_SESSION['user_id'];
      $value = query_db("SELECT id, priority, reward FROM project_manager_task WHERE assigned_to='{$user}' and status!='completed' order by priority ASC LIMIT 0,5",
      "Could not get user assigned tasks ");
      if(!empty($value['num_results'])){
        echo '
        <div class="col-md-12 col-xs-12 padding-10">
        <b>Tasks assigned to me ('.$value['num_results'].')</b> <b class="pull-right">Priority</b>';
        echo '<br>';
        foreach($value['result'] as $task){
        echo '* ';
        link_to_task_by_id($task['id']);
        $deadline = time_elapsed(strtotime(str_ireplace('/','-',$task['deadline'])));
        echo ' <span class="tiny-text text text-warning inline-block">'. $deadline.' </span>';
        echo ' <span class="tiny-text pull-right inline-block">'.$task['priority'].'</span><hr>';
        }
        echo '</div>';
      } else {
        $value['num_results'] = 0;
        }
      
        $user_offices = get_user_offices($user_id);
        if(!empty($user_offices)){
        // Get tasks assigned to ALL in my offices
        echo '
        <div class="padding-10 col-md-12 col-xs-12">
        <b>Tasks assigned to ALL in my offices</b><br>';
        // Fetch user offices - to get tasks assigned to all
        foreach($user_offices['result'] as $user_office){
          $tasks_for_all = get_tasks_assigned_to_all_in_office($user_office['office_id']);
          foreach($tasks_for_all as $all_task){
            echo '* ';
            link_to_task_by_id($all_task['id']);
            $deadline = time_elapsed(strtotime(str_ireplace('/','-',$all_task['deadline'])));
            echo ' <span class="tiny-text text text-warning inline-block">'. $deadline.' </span>';
            echo ' <span class="tiny-text pull-right inline-block">'.$all_task['priority'].'</span><hr>';
            }
        
          }
          echo '</div>';  
        }
    }
    //~ echo '</div>';
  echo '</div>';
  }
  
function show_office_task_statistics($office_id){
  if(isset($_GET['office_id'])){
  $office_id = sanitize($_GET['office_id']);
  } 
  $total_num_tasks = get_num_office_tasks($office_id);
  $num_pending = get_num_office_tasks($office_id,'pending');
  $num_started = get_num_office_tasks($office_id,'started');
  $num_submitted = get_num_office_tasks($office_id,'submitted');
  $num_completed = get_num_office_tasks($office_id,'completed');
  //  print_r($office);
  echo ' ('.$total_num_tasks.' tasks)<br>
  <span class="tiny-text">pending:'.$num_pending.' started:'.$num_started.' submitted:'.$num_submitted.' completed:'.$num_completed.'
  </span><br>';
  }
  
function show_user_offices($user_id=''){
  if(empty($user_id)){
    $user_id = $_SESSION['user_id'];
    }
  if(is_user_page()){
    $user = sanitize($_GET['user']);
    $user_details = get_user_details($user);
    $user_id = $user_details['id'];
    }
    $offices = get_user_offices($user_id);
    
    echo '<h3>Currently works with</h3>';
    if(!empty($offices['result'])){
      echo '<ul>';
      foreach($offices['result'] as $office){
        echo '<li class="pull-left">';
        if(!empty($office['office_id'])){
          link_to_office_by_id($office['office_id']) ;
          echo ' in <span class="tiny-text">';
          link_to_project_by_id($office['project_id']);
          echo ' project.</span>';
        }
        echo '</li>';
      }
      echo '</ul>';
    } else {
      echo '<em>- none -</em>';
      }
    //echo '</div>';
  }

function has_applied_to_office($office_id=''){

  $user_id = $_SESSION['user_id'];
  $value = query_db("SELECT id FROM project_manager_office_applicants 
  WHERE user_id='{$user_id}' and office_id='{$office_id}'",
  "Could not check if has applied to office ");
  
  if(!empty($value['num_results'])){
    return true;
    } else {
      return false;
    }

}

function apply_to_office($office_id=''){
  if(is_logged_in()){
  $today = time();
  $user_funds = get_user_funds();
  if(isset($_GET['apply_to_office'])){
    $office_id = sanitize($_GET['apply_to_office']);
    }
  
  if(!has_applied_to_office($office_id)){
    $user_id = $_SESSION['user_id'];
    $user = $_SESSION['username'];
    
    if(url_contains('project_manager/?action=show_offices_hiring&apply_to_office=')){
      if($user_funds > 50){
       $office = get_project_office_details($office_id);
      $q = query_db("INSERT INTO `project_manager_office_applicants`(`id`, `user_id`, `office_id`, `timestamp`) 
      VALUES ('0','{$user_id}','{$office_id}','{$today}')","Could not save job application ");
      update_user_funds($user,$amount='-50',$reason='Applied to office '.$office['office_title']);
      add_to_revenue('50', $user.$reason="applied to office {$office['office_title']}");
      if($q){
        redirect_to($_SESSION['prev_url']);
        }
      }else {
      session_message('alert','Insufficient funds! <br>It costs '.$_SESSION['preferred_currency'].' 50.00 to apply, You have '.$_SESSION['preferred_currency'].' '.convert_coins_to_user_currency($user_funds));
      redirect_to($_SESSION['prev_url']);
      }
    } 
    if(!is_office_team_member($office_id)){
    echo '<a href="'.ADDONS_PATH.'project_manager/?action=show_offices_hiring&apply_to_office='.$office_id.'">
    <div class="btn btn-primary btn-xs pull-right">apply</div></a>';
    } else {
    echo '<em class="pull-right tiny-text">You work there</em>';
    }
  } else {
    echo '<em class="pull-right tiny-text">applied</em>';
    }
  }
}


function apply_for_task($task_id=''){
  if(is_logged_in()){
  $today = time();
  $user_funds = get_user_funds();
  if(isset($_GET['apply_to_office'])){
    $office_id = sanitize($_GET['apply_to_office']);
    } else {
      $q = query_db("SELECT office_id FROM project_manager_task WHERE id='{$task_id}'",
      "Could not get office id in apply for task ");
      $office_id = $q['result'][0]['office_id'];
      }
  
  if(!has_applied_to_office($office_id)){
    $user_id = $_SESSION['user_id'];
    $user = $_SESSION['username'];

    if($user_funds > 50){
    $office = get_project_office_details($office_id);
    $q = query_db("INSERT INTO `project_manager_office_applicants`(`id`, `user_id`, `office_id`, `timestamp`) 
    VALUES ('0','{$user_id}','{$office_id}','{$today}')","Could not save task application ");
    update_user_funds($user,$amount='-50',$reason='Applied to office '.$office['office_title']);
    add_to_revenue('50', $user.$reason=' applied to office '.$office['office_title']);
    if($q){
      redirect_to($_SESSION['prev_url']);
      }
    }else {
    session_message('alert','Insufficient funds! <br>It costs '.$_SESSION['preferred_currency'].' 50.00 to apply, You have '.$_SESSION['preferred_currency'].' '.convert_coins_to_user_currency($user_funds,2));
    redirect_to($_SESSION['prev_url']);
    }
    
    if(!is_office_team_member($office_id)){
    echo '<a href="'.ADDONS_PATH.'project_manager/?action=show_offices_hiring&apply_to_office='.$office_id.'">
    <div class="btn btn-primary btn-xs pull-right">apply</div></a>';
    } else {
    echo '<em class="pull-right tiny-text">You work there</em>';
    }
  } else {
    echo '<em class="pull-right tiny-text">applied</em>';
    }
  }
}

function show_office_applicants($office_id=''){
  if(is_office_page() && (is_project_manager() || is_admin())){
    echo '<br><h3>Office Applicants</h3>';
    $office_id = sanitize($_GET['office_id']);
    $values = query_db("SELECT user_id FROM project_manager_office_applicants 
    WHERE office_id='{$office_id}'",
    "Problem showing office applicants ");
    
    foreach($values['result'] as $result){
      link_to_user_by_id($result['user_id']);
      echo ', ';
    }
  }
}

function show_change_office_status_form(){
  if(is_project_manager()){
  $value = get_project_office_details();
  $office_id = sanitize($_GET['office_id']);
  
 // $status = $value['0']['status'];
  //print_r($_POST);
  if(isset($_POST['office_status'])){
    $status = trim(sanitize($_POST['office_status']));
    $q = query_db("UPDATE project_manager_office SET status='{$status}' WHERE id='{$office_id}'",
    "Could not change office status ");
    if($q){
      redirect_to($_SESSION['current_url']);
      }
  }
  
  echo '<form method="post" action="'.$_SESSION['current_url'].'">
    <select name="office_status" onchange="this.form.submit()">
    <option value="">Change status</option>
    <option value="hiring">Hiring</option>
    <option value="operational">Operational</option>
    <option value="operations suspended">Operations Temporaily Suspended</option>
    <option value="completed tasks">Completed all tasks and objectives</option>
    </select>
  </form>';
  }
}
  
function get_task_office_id($task_id=''){
  if(empty($task_id)){
    $task_id = sanitize($_GET['task_id']);
    }
  $value = query_db("SELECT office_id FROM project_manager_task WHERE id='{$task_id}'",
  "Could not get task office id ");
  return $value['result'][0]['office_id'];
  }

function get_offices_hiring(){
  $value = query_db("SELECT * FROM project_manager_office WHERE status='hiring'",
  "Could not get available office jobs ");
  return $value;
  }
  
function show_offices_hiring(){
  if(is_logged_in()){
        if(url_contains('project_manager/?action=show_offices_hiring')){
          echo '<div class="row">
              <div class="col-md-12">';
              $value = get_offices_hiring();
              echo '<h2>Offices Hiring</h2><br>';
              echo '<ul>';
              foreach($value['result'] as $result){
                //get associated company
                $company = get_company_details($result['company_id']);
                echo '<li><a href="'.ADDONS_PATH.'project_manager/?action=show_office&office_id='.$result['id'].'&project_id='.$result['project_id'].'">
                '.$result['office_title'].'</a>';
                echo '<br><span class="badge">Operating Budget : '.$_SESSION['preferred_currency'].'  '.convert_coins_to_user_currency($result['funded_budget']).'</span>';
                apply_to_office($result['id']); 
                echo '<br><span class="tiny-text">Project : '; link_to_project_by_id($result['project_id']); echo'</span>';
                echo '<hr>';
              }
              echo '</li>';
              echo '</ul>';
            echo  '</div>';
          echo '</div>';
        }
      
     
      
      
  }
}

function style_project_manager(){
	echo '<style  type="text/css">
	.pm-completed{ background-color: lightgreen; }
	.pm-started{ background-color: lightblue; }
	.pm-pending{ background-color: lightgrey; }
	.pm-submitted{ background-color: lightgoldenrodyellow; }
	.pm-disapproved{ background-color: lightcoral;}
  .pm-failed{ background-color: black; color: white;}
			
	.pm-completed,
	.pm-started,
	.pm-submitted,
	.pm-failed,
	.pm-disapproved,
	.pm-pending{padding: 5px; }
	</style>';
	
}
	
function pm_color_codes(){
	if(is_office_page() || is_task_page() || is_project_home()){
	echo '<style  type="text/css">
	.code-completed{ background-color: lightgreen;}
	.code-started{ background-color: lightblue; }
	.code-pending{ background-color: lightgrey; }
	.code-submitted{ background-color: lightgoldenrodyellow; }
	.code-failed{ background-color: black; color: white; }
	.code-disapproved{ background-color: lightcoral;}
	
	.code-completed,
	.code-started,
	.code-submitted,
	.code-failed,
	.code-disapproved,
	.code-pending{padding: 5px; width: 10px; height: 5px; line-height: 2em;}
	</style>';
	
	echo '<div class="text-center"><strong>Color codes:</strong> <span class="code-pending">pending  </span> 
			 <span class="code-started">started  </span> 
			 <span class="code-submitted">submitted  </span> 
			 <span class="code-completed">completed / approved </span>
			 <span class="code-failed">failed  </span> 
			 <span class="code-disapproved">disapproved  </span>  </div><br>';
	}
}
//print_r($_SESSION);
 // end of project_manager functions file
 // in root/project_manager/includes/functions.php
