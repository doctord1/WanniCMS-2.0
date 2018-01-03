<?php

function view_item($table=''){
	if($_GET['action'] == "view_{$table}"){
	$id = $_GET['tid'];
	$output = array();
	
	$query= mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM {$table} WHERE id='{$id}'");
	$result = mysqli_fetch_array($query);
		
	foreach($result as $key => $value){
		$output[$key]= $value;
		}	
	return $output;
	}
}

function delete_item($table='',$destination=''){
	if($_GET['action'] == "delete_{$table}" && !isset($_GET['do_delete'])){
	
	if(isset($_GET['tid'])){	
	$id = trim(mysql_prep($_GET['tid']));
	}else if(isset($_GET['fid'])){
		$id = trim(mysql_prep($_GET['tid']));
		}
	
	
	$query= mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM {$table} WHERE id={$id}") or die("Failed to delete item" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	unlink($_SERVER['DOCUMENT_ROOT'].'/addons/ads/ad_images/'.$result['name']);
	redirect_to($_SESSION['prev_url']);
	
	} else if($_GET['do_delete'] == 'true'){
		
	$id = trim(mysql_prep($_GET['tid']));
	
	$query= mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM {$table} WHERE id={$id}")or die("Failed to delete item" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	if($query){
		session_message('success', 'item deleted!');
			if($destination !=''){
			redirect_to($destination);
			}
		}
	
	}

}
	
function show_delete_link($type='',$file_name=''){
	// type can be button or defaults  to link
	// filename for unlinking files where neccessary
	$id = trim(mysql_prep($_GET['tid']));
	if(true == url_contains('?')){
			$concat = '&';
			} else { $concat = '?'; }
	if($type == 'button'){
		echo '<a href="'.$_SESSION['current_url'].$concat.'do_delete=true&file_name='.$file_name.'"><button class="error">Delete</button></a>';
		} else {
		echo '<a href="'.$_SESSION['current_url'].$concat.'do_delete=true&file_name='.$file_name.'">Delete</a>';			
			}
	}
	



function query($action='',$table='',$id='',$sql=''){
	
	if($action == 'view'){
		$output = array();
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM {$table} WHERE id='{$id}'");
	$result = mysqli_fetch_array($query);
		
		$output[]= $result[''];
		
		}
	
	}
	

function get_crud_create_input($name='',$type = ''){
	global $hide_columns;
	if(!in_array($name,$hide_columns)){
		$clean_column = ucfirst(str_ireplace('_',' ',$name));
		//echo "Clean Column is :" .$clean_column;
		 if(string_contains($type, 'int') && $name != 'id'){
		$input = '<input type="number" name="'.$name.'" class="form-control" placeholder="'.$clean_column.'">'; 
		} else if(string_contains($type, 'varchar') || string_contains($type, 'date') || string_contains($type, 'tinytext')){
		$input = '<input type="text" name="'.$name.'" class="form-control" placeholder="'.$clean_column.'">'; 
		} else if(string_contains($type, 'text')){
		$input = '<textarea name="'.$name.'" class="form-control" placeholder="'.$clean_column.'"></textarea>'; 
		}
	}
		return $input;
	
 }
  
function get_crud_edit_input($id='',$name='',$type = ''){
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM {$table} WHERE `id`='{$id}'");
	$result = mysqli_fetch_assoc($query);
	print_r($query);
	
	$clean_column = ucfirst(str_ireplace('_',' ',$name));
	//echo "Clean Column is :" .$clean_column;
	 if(string_contains($type, 'varchar')){
	$input = '<input type="number" name="'.$name.'" placeholder="'.$clean_column.'">'; 
	} else if(string_contains($name, 'varchar') || string_contains($type, 'date') || string_contains($type, 'tinytext')){
	$input = '<input type="text" name="'.$column.'" placeholder="'.$clean_column.'">'; 
	} else if(string_contains($name, 'text')){
	$input = '<textarea name="'.$name.'" placeholder="'.$clean_column.'"></textarea>'; 
	}
	return $input;
 }
  
		 
function crud_do($table='',$action='',$select=array(''),$title='',$hide_columns=array('')){
	//$select is an array (which could be multi-dimensional for inputing selects from other tables)
	//if $titleis not set, it shows default, but if set to hidden, titles will be hidden
	// otherwise the title is set to programmer defined title
	//$hide columns is an arraythat holds the column names that you want hidden from form or substituted by other values
	if($title == ''){
		$title ='<h1>Create New '.ucfirst($table).'</h1>';
		} else if($title == 'hidden'){
			$title = '';
			}
	
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SHOW COLUMNS FROM {$table}") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	//When form is submitted
	if(isset($_POST['submit_'.$table])){
		//print_r($_POST);
	$insert_query ='';
	$insert_query .= "INSERT INTO {$table}(";
	
	while($result = mysqli_fetch_assoc($query)){
	//print_r($result);
		$insert_query .= "`".$result['Field']."`, ";
	}
	$insert_query = substr($insert_query,0,-2); // remove trailing comma
	$insert_query .= ") VALUES (";
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SHOW COLUMNS FROM {$table}") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	while($result = mysqli_fetch_assoc($query)){ //get columns again
	
		$value = $result['Field'];
	$insert_query .= "'" . $_POST["{$value}"]."',";
	}
	$insert_query = substr($insert_query,0,-1); // remove trailing comma

	
	$insert_query .= ')';
	//echo $insert_query;
	$query = mysqli_query($GLOBALS["___mysqli_ston"], $insert_query) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	if($query){ session_message('success','Item saved successfully'); }	
	redirect_to($_SESSION['current_url']);
	}
	//end form submitted code
	
	
	
	//Start create requested code
	
	if($action == 'create' && !isset($_POST['submit_'.$table])){
		//get table columns
		
		$output = $title;
		$output .= '<form class="edit-form" method="post" action="'.$_SERVER['current_url'].'">';
		
		while ($result = mysqli_fetch_array($query)){
		//print_r($result);
			$type = $result['Type'];
			
			$output .= get_crud_create_input($result['Field'], $type);
		
			}
			$output .= '<input type="submit" name="submit_'.$table.'" value="Create">
			</form>';
		//end create requested code
		
		
		// BEGIN UPDATE
		
	}else if($action== 'update'){
		if($action == 'create' && $_POST['submit_'.$table] != 'Save Edit' && $_GET['action'] == 'create_'.$table ){
		//get table columns
		
			$output = $title;
			$output .= '<form class="edit-form" method="post" action="'.$_SERVER['current_url'].'">';
			
			while ($result = mysqli_fetch_array($query)){
			//print_r($result);
				$type = $result['Type'];
			
			$output .= get_crud_edit_input($id,$result['Field'], $type);
			}
			$output .= '<input type="submit" name="submit_'.$table.'" value="Save Edit">
				</form>';
		}
		
	} else if($action == 'delete'){
		
		
		
	}
	

echo $output;
}

function get_select_list_from_table($table='',$column='',$select_name=''){
	if($table!='' && $column != ''){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT {$column} FROM {$table}") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		if(!empty($select_name)){
			$output = '<select name="'.$select_name.'">';
		} else { $output = '<select name="'.$column.'">'; }
		while($result = mysqli_fetch_array($query)){
			$output .='<option>'.$result["{$column}"].'</option>';
		}$output .= '</select>';
	}
	return $output;
}
	
	

function crud($values = array( // may be removed
	'action'=>'',
	'table'=>'',
	'required_columns'=>array(),
	'insert_values'=>array(),
	'values_to_replace'=>'',// 'name="chidera", class="php"'
	'condition'=>'', // 'WHERE a=b'
	'order'=>'',
	'group'=>'',
	'pager_limit'=>'')){
		
	if(!empty($values['pager_limit'])){
		$pager=pagerize($show_more=$values['pager_limit']);
		$limit = $_SESSION['pager_limit'];
		}	
		
	//Read	
	if(($values['action'] == 'read' || $values['action'] == 'select') && empty($values['required_columns']) && !empty($values['table'])){
		$query = "SELECT * FROM {$values['table']}";
	}else if(($values['action'] == 'read' || $values['action'] == 'select') && !empty($values['required_columns']) && !empty($values['table'])){
		$query = "SELECT ";
		foreach($value['required_columns'] as $column){
			$query .= $column .',';
			}
		$query = "SELECT * FROM ";
	//update
	}else if($values['action'] == 'update' && !empty($values['table']) && !empty($values['condition'])){
		$query = "UPDATE {$values['table']} SET {$values['values_to_replace']} {$value['condition']}";
	//insert
	}else if($values['action'] == 'insert' && !empty($values['table']) && !empty($values['required_columns'])){
		
		$query = "INSERT INTO {$values['table']}(";
		foreach($value['required_columns'] as $column){
			$query .= $column .',';
			}
		$query .= ') VALUES (';
		foreach($value['insert_values'] as $value){
			$query .= $value .',';
			}
		$query .= ')';
	//delete
	}else if($values['action'] == 'delete' && !empty($values['table']) && !empty($values['condition'])){
		$query = "DELETE FROM {$values['table']} {$values['condition']}";
	}
	
}

function form_text($name, $placeholder=''){
	echo '<input type="text" name="'.$name.'" placeholder="'.$placeholder.'">';
	}

function form_edit_text($name='', $value=''){
	echo '<input type="text" name="'.$name.'" value="'.$value.'">';
	}
	
function form_number($name, $placeholder=''){
	echo '<input type="number" name="'.$name.'" placeholder="'.$placeholder.'">';
	}

function form_edit_number($name='', $value=''){
	echo '<input type="number" name="'.$name.'" value="'.$value.'">';
	}
	
function form_password($name, $placeholder=''){
	echo '<input type="password" name="'.$name.'" placeholder="'.$placeholder.'">';
	}

function form_hidden($name, $placeholder=''){
	echo '<input type="text" hidden="'.$name.'" placeholder="'.$placeholder.'">';
	}

function form_textarea($name, $placeholder=''){
	echo '<textarea name="'.$name.'" placeholder="'.$placeholder.'"></textarea>';
	}

function form_edit_textarea($name='', $value=''){
	echo '<textarea name="'.$name.'" value="'.$value.'"></textarea>';
	}

function form_end($name='',$value=''){
	echo ',input type="submit" name="'.$name.'" value="'.$value.'">';
	}
