<?php
if (session_status() == PHP_SESSION_NONE) {
    sec_session_start();
}

$tmp = '
<header>
    <nav class="navbar bg-dark navbar-dark navbar-fixed-top w-100 justify-content-center" id="upperNav">
      <a class="navbar-brand" href="/embedded/index.php">Embedded_IoT <img src="/embedded/images/Iot.png" class="img-fluid" alt="Responsive image" height="70" width="70"> MySmart_Home</a>
    </nav>
    <nav class="navbar bg-dark navbar-dark navbar-fixed-top w-100 justify-content-between" id="underNav">
      <div class="col-8">
        <a id="profile" href="/embedded/profile/profileSettings.php"><img src="/embedded/images/Utente.png" alt="Responsive image" height="30" width="30"></a>';

if(isset($_SESSION, $_SESSION['username'])) {
  $tmp.= '<p class="text-white">Bentornato <strong>'.$_SESSION['username'].'</strong></p></div>';
  $tmp.= '<p class="text-white">Vuoi effettuare il logout? <a class="text-blue" href="/embedded/login/logout.php">Logout</a></p>';
}
else $tmp.= '</div><p class="text-white">Non hai effettuato il login? <a class="text-blue" href="/embedded/login/login.php">Entra</a></p>';

$tmp.= '
    </nav>
</header>';

echo $tmp;
?>
