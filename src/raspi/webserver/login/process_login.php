<?php

  require('../utility/db.php');
  require('../utility/utility.php');

  if (session_status() == PHP_SESSION_NONE) {
      sec_session_start();
  }

  if(isset($_POST['loginEmail'], $_POST['p'])) {
    $email = $_POST['loginEmail'];
    $password = $_POST['p']; // Recupero la password criptata.

    if(login($email, $password, $conn)) {
      if(!isset($_COOKIE['email'],$_POST['remember'])) setcookie("email", $email, time()+ 7 * 24 * 60 *60);
      header('Location: /embedded/index.php');
    } else {
      $error = $_SESSION['error'];
      unset($_SESSION['error']);
      header('Location: ./login.php?error='.$error);
    }
  }

 ?>
