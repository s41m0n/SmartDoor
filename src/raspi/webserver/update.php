<?php

require_once('utility/db.php');
require_once('utility/utility.php');
if (session_status() == PHP_SESSION_NONE) {
    sec_session_start();
}

if(isset($_SESSION,$_SESSION['username'])) {

  if ($stmt = $conn->prepare("SELECT temp FROM temperature WHERE idTemperature = ?")) {
    $tmp = "1";
    $stmt->bind_param('s', $tmp);
    $stmt->execute(); // esegue la query appena creata.
    $stmt->store_result();
    $stmt->bind_result($temperature);
    $stmt->fetch();

    if ($stmt = $conn->prepare("SELECT value FROM intensity WHERE idIntensity = ?")) {
      $int = "1";
      $stmt->bind_param('s', $int);
      $stmt->execute(); // esegue la query appena creata.
      $stmt->store_result();
      $stmt->bind_result($intensity);
      $stmt->fetch();

      echo $temperature." ".$intensity;
    }
  }

}else echo 0;

?>
