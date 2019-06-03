<?php

  define('SERVERNAME',servername);
  define('USERNAME',username);
  define('PASSWORD',password);
  define('DATABASE',database);

  $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die;
  if ($conn->connect_errno)
      die("Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error);

?>
