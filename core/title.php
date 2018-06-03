<?php
require_once('config.php');

function set_page_title() {
    $title_left = APPLICATION_NAME;
    if(isset($title_left)) {
        $title_tag = $title_left .' - ' . $title_right;
    }
    else { $title_tag = "Wanni CMF" .' - ' .$title_right; }
     return $title_tag;
}


?>

