<?php
function search($entity_name,$column,$search_phrase=''){
  //~ $locations = json_encode($locs_array['result']);
  if(isset($_GET['clean-url']["{$entity_name}-search-phrase"])){
    $search_phrase = sanitize($_GET['clean-url']["{$entity_name}-name"]);
  }
  if(isset($_GET['clean-url']["{$entity_name}_id"])){
    $location_id = sanitize($_GET['clean-url']["{$entity_name}-id"]);
  }

  if(isset($_POST["{$entity_name}-search-phrase"])){
    $search_phrase = sanitize($_POST["{$entity_name}-search-phrase"]);
  }
 
  
  
  echo '
  <div id="search-'.$entity_name.'" class="row d-flex flex-row-reverse m3 px-5 pt-3 bg-primary">
    <form method="post" class="flex-item" action="'.BASE_PATH.'index.php/'.$entity_name.'/action/search/'.$entity_name.'/'.$column.'">
      <div class="input-group ">
        <input type="text" name="'.$entity_name.'-search-phrase" class="form-control" placeholder="Find '.$entity_name.'">
        <span class="input-group-append">
        <input type="submit" name="search-'.$entity_name.'" value="Search" class="btn btn-info" id="" />
        </span>
      </div>
    </form>
  </div>';
  
  if(!empty($search_phrase)){
    $_SESSION["{$entity_name}s-list-info"] = get_entities($entity_name,$column,$search_phrase);
    if(!is_json_request()){
      echo '<div class="row">
      <div class="col-md-8 col-xs-12"> 
      <ul class="list-group">';
      foreach($view_info['result'] as $result){
        $link_text = parse_text_for_output($result['description']);
        $endpoint = str_ireplace(' ','+',$result['id']);
        echo  '<li class="list-group-item">
          <a href="'.BASE_PATH.'index.php/room/action/show/room/'.$endpoint.'">'.$link_text.'</a> ';
        show_timeago($result['timestamp']);
        echo '</li>';
        
      }
    echo '</ul>
      
    </div>';
    }
  }
  
}


function show_search_form($entity_name,$column,$search_phrase=''){
  
  search($entity_name,$column,$search_phrase='');
}


?>
