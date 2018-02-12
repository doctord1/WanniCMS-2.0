<?php
function report_abuse(){
  $reporter_id = $_SESSION['user_id'];
  $path = $_SESSION['current_url'];
  if(isset($_POST['report-abuse-reason'])){
    $reason = sanitize($_POST['report-abuse-reason']);
    $q = query_db("INSERT INTO `core_abuse`(`id`, `reporter_id`, `path`, `reason`, `response`, `responder_user_id`) VALUES ('0','{$user_id}','{$reason}','','')",
    "Could not save abuse report! ");
    if($q){
      $_SESSION['status-message'] = '<div class="alert alet-success"> Abuse report saved expect a response within 48 hours</div>';
      redirect_to($_SESSION['prev_url']);
    }
  }
}


?>
