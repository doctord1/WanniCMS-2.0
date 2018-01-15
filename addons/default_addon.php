<?php
/*
Permissions  Actions: 'sleep', 'wake', 'create', 'edit', 'delete'


*/



function setup_default_addon_db($addon_name){
  //~ You may create your tables using phpmyadmin and export the table definitions when done
  
  $sql = "DATABASE DUMP FROM PHPMYADMIN";
  $q = query_db("{$sql}","Could not setup {$addon_name} db! ");
  if($q){
    return true;
  }
}


?>
