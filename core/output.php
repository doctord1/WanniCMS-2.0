<?php
function process_data_for_output($data_array){
  if(url_contains(BASE_PATH.'json/')){
    $output = json_encode($data_array);
    header('Content-Type: application/json');
    echo $output;
    exit;
  } else {
    return $data_array;
  }
}
?>
