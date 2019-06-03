<?php

require_once('../utility/utility.php');
require_once('../utility/db.php');
if (session_status() == PHP_SESSION_NONE) {
    sec_session_start();
}

if(login_check($conn) == true) {
  header('Location: /index.php');
  return;
}else {
  if(isset($_GET,$_GET['email'])) {
    if ($stmt = $conn->prepare("UPDATE user SET confirmed = 1 WHERE username = ?")) {
      $stmt->bind_param('s', $_GET['email']);
      $stmt->execute();
      $stmt->store_result();
      if($stmt->affected_rows > 0) header('Location: login.php?confirmed=True');
      else header('Location: login.php?error=5');
    }
  }
}

 ?>
