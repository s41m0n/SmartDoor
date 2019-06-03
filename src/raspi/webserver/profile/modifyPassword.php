<?php

require '../utility/db.php';
require '../utility/utility.php';
sec_session_start();

if(isset($_POST['po'], $_POST['pn'], $_SESSION['username'])) {

  if ($stmt = $conn->prepare("SELECT username, password, salt FROM user WHERE username = ? LIMIT 1")) {
    $old = $_POST['po'];
    $new = $_POST['pn'];

    $stmt->bind_param('s', $_SESSION['username']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($email, $db_password, $salt);
    $stmt->fetch();

    if($stmt->num_rows == 1) {
      $old = hash('sha512', $old.$salt);

      if($db_password == $old) {
        $new = hash('sha512', $new.$salt);

        if ($insert_stmt = $conn->prepare("UPDATE user SET password = ? WHERE username = ?")) {

          $insert_stmt->bind_param('ss', $new, $email);
          $insert_stmt->execute();
          $insert_stmt->store_result();

          if($insert_stmt->affected_rows <= 0) {
            header('Location: profileSettings.php?error=2');
          }else {
            $user_browser = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['login_string'] = hash('sha512', $new.$user_browser);
            header('Location: profileSettings.php?error=0');
          }
        }
      } else header('Location: profileSettings.php?error=3');
    } else header('Location: profileSettings.php?error=4');
  }
}

?>
