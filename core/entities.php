<?php
function get_entities($entity_name,$column,$search_phrase='',$limit='25'){
  if(!empty($search_phrase)){
    $search_phrase = sanitize($search_phrase);
    $sql = "SELECT * FROM {$entity_name} WHERE {$column} LIKE '%{$search_phrase}%' ORDER BY id DESC LIMIT {$limit}";
  } else {
    $sql = "SELECT * FROM {$entity_name} ORDER BY id DESC LIMIT {$limit}";
  }
  $q = query_db("{$sql}",
  "Could not get entity {$search_phrase} in {$entity_name}! ");

  if(!is_json_request(URL)){
    return $q;
  } else {
    return json_encode($q);
  }
}


?>
