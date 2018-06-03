<?php
function set_pager($start=0,$stop='',$calling_function,$params){
  $_SESSION['pager-start'] = $start;
  $_SESSION['pager-stop'] = $stop;
  redirect_to(BASE_PATH.'pager/action/'.$calling_function.'/'.$params);
}
?>
