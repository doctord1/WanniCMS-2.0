<?php
function load_view($folder,$viewname){ 

  if(!empty($viewname)){
    $r = dirname(dirname(__FILE__));
    $file = $r.'/views/'.$folder.'/'.$viewname.'.view';
    require_once($file);
  }
}
function load_list_view($folder,$viewname){ 
  if(!empty($viewname)){
    $r = dirname(dirname(__FILE__));
    $file = $r.'/views/'.$folder.'/'.$viewname.'.view';
    require_once($file);
  }
}

function show_view($entity_name,$entity_id){
  if(!empty($entity_id)){
    load_entity_info($entity_name,$entity_id);
  }
  load_view($entity_name);
  //~ ECHO $entity_name.' view loaded';
}

function load_entity_info($entity_name,$entity_id,$children = array()){
  
  $q = query_db("SELECT * FROM {$entity_name} WHERE id='{$entity_id}'",
  "Could not get relations of {$entity_name}! ");
  if($q){
    $_SESSION["{$entity_name}_info"] = $q['result'][0];
  }
  
  $desc = query_db("show columns from {$entity_name}","Could not describe entity!");
 
  foreach($desc['result'] as $column){
    if(string_contains($column['Field'],'_id')){
      $entity_relation = str_ireplace('_id','',$column['Field']);
      $entity_relation_id = $_SESSION["{$entity_name}_info"]["{$column['Field']}"];
      get_related_info($entity_name,$entity_relation,$entity_relation_id);
    }
  }
}

function get_related_info($entity_name,$entity_relation,$entity_relation_id){
  $sql = "SELECT * FROM {$entity_relation} WHERE id='{$entity_relation_id}'";
  $q = query_db("$sql",
  "Could not get {$entity_relation}s related to {$entity_name}! ");
  if($q){
    $_SESSION["{$entity_name}_info"]["{$entity_relation}"] = $q['result'][0];
  }
}
?>
