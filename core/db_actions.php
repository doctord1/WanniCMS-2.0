<?php
require_once('session.php');
function db_add_item($table,$values){
  //~ $values is a comma seperated list of items excluding the id column
  $clean_name = str_ireplace('_',' ',$table);
  $values = explode(',',$values);
  $cols = query_db("SHOW columns from {$table}",
  "Could not get columns from {$table}! ");
  //~ print_r($cols);
  $sql = "INSERT INTO {$table} (";
  foreach($cols['result'] as $result){
    $sql .= $result['Field'].', ' ;
  }
  $sql = rtrim($sql,', ');
  $sql .= ") VALUES('0',";
  foreach($values as $value){
    $value = trim(sanitize($value));
    $sql .= "'{$value}',";
  }
  $sql = rtrim($sql,',');
  $sql .= ")";
  //~ echo $sql;
  $q = query_db("{$sql}","Could not add {$clean_name} item! ");
  if($q){
  session_message('success',"{$table} item added successfully! ");
  return true;
  }
}

function db_delete_item($table,$id){
  if(is_admin()){
    $clean_name = str_ireplace('_',' ',$table);
    $q = query_db("DELETE from {$table} where id='{$id}'",
    "Could not delete {$clean_name} ! ");
    if($q){
      $_SESSION['status-message'] = "<div class='alert alert-success'> {$clean_name} deleted </div>";
      redirect_to(BASE_PATH.'index.php/home');
    }
  }
}

function db_get_all_items($table,$sort='ASC',$start='0',$stop=''){
  $clean_name = str_ireplace('_',' ',$table);
  if(!empty($start) || !empty($stop)){
    $limit_condition = "LIMIT {$start}, {$stop}";
  } else {
    $limit_condition = '';
  }
  if($sort == 'RANDOM'){
    $sort = '';
    $order_by_condition = "ORDER BY RAND()";
  } else {
    $order_by_condition = "ORDER BY id {$sort}";
  }
  $q = query_db("SELECT * FROM {$table} {$order_by_condition} {$limit_condition}",
  "Could not get all {$clean_name} items! ");
  if($q){
    return $q;
  }
}

function db_get_item($table,$id){
  $clean_name = str_ireplace('_',' ',$table);
  $q = query_db("SELECT * FROM {$table} WHERE id='{$id}' ",
  "Could not get {$clean_name} details! ");
  if($q){
    return $q['result'];
  }
}

function db_get_num_items($table){
  $clean_name = str_ireplace('_',' ',$table);
  $q = query_db("SELECT count(id) FROM {$table}",
  "Could not num {$clean_name} items! ");
  if($q){
    return $q['num_results'];
  }
}

function db_update_field($table,$column,$id,$update_value){
  //~ If is a number field, increments $column in $table by $update_value
  //~ Else if is a character field, replaces $column text with $update_value
  $clean_table_name = str_ireplace('_',' ',$table);
  $clean_column_name = str_ireplace('_',' ',$column);

  $q = query_db("SELECT {$column} FROM {$table} WHERE id='{$id}'",
  "Could not get target column! ");
  $prev_col_data = $q['result'][0]["{$column}"];
  $new_col_data = $prev_col_data + $update_value;
  $q = query_db("UPDATE {$table} SET {$column}='{$new_col_data}' WHERE id='{$id}'",
  "Could not update {$clean_column_name} in {$clean_table_name}! ");
  if($q){
    session_message('success','Field updated!');
  }
}

function db_update_item($table,$id,$values){
  //~ $values is a json list of items in the format shop_name:'Judy shop',phone:0087393,etc
  //~ example usage - db_update_item('shop',1,'{"shop_name": "Aquafina","email":"ifo@aqf.ina"}');

  $clean_name = str_ireplace('_',' ',$table);
  $cols = query_db("SHOW columns from {$table}",
  "Could not get columns from {$table}! ");
  $values = json_decode($values,true);
  $keys_array = array_keys($values);
  print_r($keys_array);
  //~ print_r($values);
  $sql = "UPDATE {$table} SET ";
  foreach($cols['result'] as $result){
    if(in_array($result['Field'],$keys_array)){
      $sql .= $result['Field']."='".$values["{$result['Field']}"]."',";
    }
  }
  $sql = rtrim($sql,',');
  $sql .= ' WHERE id='.$id;
  echo $sql;
  $q = query_db("{$sql}","Could not update {$clean_name} item! ");
  if($q){
    session_message('success',"{$clean_name} updated successfully!");
  }
}

function db_update_row($table,$id,$values){
  //~ Alias for db_update_item
  //~ $values is a json list of items in the format shop_name:'Judy shop',phone:0087393,etc
  //~ example usage - db_update_item('shop',1,'{"shop_name": "Aquafina","email":"ifo@aqf.ina"}');

  $clean_name = str_ireplace('_',' ',$table);
  $cols = query_db("SHOW columns from {$table}",
  "Could not get columns from {$table}! ");
  $values = json_decode($values,true);
  $keys_array = array_keys($values);
  print_r($keys_array);
  //~ print_r($values);
  $sql = "UPDATE {$table} SET ";
  foreach($cols['result'] as $result){
    if(in_array($result['Field'],$keys_array)){
      $sql .= $result['Field']."='".$values["{$result['Field']}"]."',";
    }
  }
  $sql = rtrim($sql,',');
  $sql .= ' WHERE id='.$id;
  echo $sql;
  $q = query_db("{$sql}","Could not update {$clean_name} item! ");
  if($q){
    session_message('success',"{$clean_name} updated successfully!");
  }
}

?>
