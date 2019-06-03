<?php

  require('../utility/utility.php');
  require('../utility/db.php');
  if (session_status() == PHP_SESSION_NONE) {
      sec_session_start();
  }

  if(login_check($conn) != true) {
    header('Location: /embedded/index.php');
    return;
  }else {
    // Recupera i parametri di sessione.
    $params = session_get_cookie_params();
    // Elimina tutti i valori della sessione.
    $_SESSION = array();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    // Cancella la sessione.
    session_destroy();
    header('Location: /embedded/index.php');
  }

?>
