<?php
require '../utility/db.php';
require '../utility/utility.php';
sec_session_start();

if ($insert_stmt = $conn->prepare("UPDATE user SET phoneNumber = ? WHERE username = ?")) {

  $phoneNumber = $_POST['newNumber'];
  $username = $_SESSION['username'];

  $insert_stmt->bind_param('ss', $phoneNumber, $username);

  $insert_stmt->execute();
  $insert_stmt->store_result();

  if($insert_stmt->affected_rows == 0) {
    header('Location: profileSettings.php?error=1');
  }else {
    header('Location: profileSettings.php?phone=True');
  }
}

?>
