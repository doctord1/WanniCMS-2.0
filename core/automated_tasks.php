<?php
//~ Select draw winners
if(addon_is_available('draws')){
  $time = getdate();
  if($time['hours'] >'20' && $time['hours'] < '24'){
  $active_draws = get_active_draws();
  foreach($active_draws as $draw){
    select_draw_winner($draw['id'],$draw['category']);
    }
  }
}
?>
