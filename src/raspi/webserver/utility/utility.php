<?php

function sec_session_start() {
  $session_name = 'sec_session_id'; // Imposta un nome di sessione
  $secure = false; // Imposta il parametro a true se vuoi usare il protocollo 'https'.
  $httponly = true; // Questo impedirà ad un javascript di essere in grado di accedere all'id di sessione.
  ini_set('session.use_only_cookies', 1); // Forza la sessione ad utilizzare solo i cookie.
  $cookieParams = session_get_cookie_params(); // Legge i parametri correnti relativi ai cookie.
  session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
  session_name($session_name); // Imposta il nome di sessione con quello prescelto all'inizio della funzione.
  session_start(); // Avvia la sessione php.
  session_regenerate_id(); // Rigenera la sessione e cancella quella creata in precedenza.
}

function sendMail($nameSender, $mailSender, $mailReceiver, $subject, $message){
  $header = "MIME-Version: 1.0\r\n";
  $header .= "Content-type: text/html; charset=iso-8859-1\r\n";
  $header .= "From: ".$nameSender." <".$mailSender."> \r\n";
  $header .= "Reply-to: [email]".$mailSender."[/email]\r\n";
  $oggetto = $subject;
  $destinatario = $mailReceiver;

  $messaggio =
  '<html>

  <head>
  <title>'.$subject.'</title>
  </head>

  <body>'
  .$message.'
  </body>
  </html>';

  mail($mailReceiver, $oggetto, $messaggio, $header);
}

function login($email, $password, $conn) {
  // Usando statement sql 'prepared' non sarà possibile attuare un attacco di tipo SQL injection.
  if ($stmt = $conn->prepare("SELECT username, password, confirmed, salt FROM user WHERE username = ? LIMIT 1")) {
    $stmt->bind_param('s', $email); // esegue il bind del parametro '$email'.
    $stmt->execute(); // esegue la query appena creata.
    $stmt->store_result();
    $stmt->bind_result($username, $db_password, $confirmed, $salt); // recupera il risultato della query e lo memorizza nelle relative variabili.
    $stmt->fetch();
    $password = hash('sha512', $password.$salt); // codifica la password usando una chiave univoca.
    if($stmt->num_rows == 1) { // se l'utente esiste
      if($db_password == $password) { // Verifica che la password memorizzata nel database corrisponda alla password fornita dall'utente.
        // Password corretta!
        if(is_null($confirmed)) {
          $_SESSION['error'] = 4;
          return false;
        }else {
          $user_browser = $_SERVER['HTTP_USER_AGENT']; // Recupero il parametro 'user-agent' relativo all'utente corrente.
          $_SESSION['username'] = $username;
          $_SESSION['login_string'] = hash('sha512', $password.$user_browser);
        }
        // Login eseguito con successo.
        return true;
      }else {
        // Password incorretta.
        // Registriamo il tentativo fallito nel database.
        $_SESSION['error'] = 1;
        return false;
      }
    } else return false;
  }
}

function login_check($conn) {
  // Verifica che tutte le variabili di sessione siano impostate correttamente
  if(isset($_SESSION['username'], $_SESSION['login_string'])) {
    $login_string = $_SESSION['login_string'];
    $username = $_SESSION['username'];
    $user_browser = $_SERVER['HTTP_USER_AGENT']; // reperisce la stringa 'user-agent' dell'utente.
    if ($stmt = $conn->prepare("SELECT password FROM user WHERE username = ? LIMIT 1")) {
      $stmt->bind_param('s', $username); // esegue il bind del parametro '$user_id'.
      $stmt->execute(); // Esegue la query creata.
      $stmt->store_result();

      if($stmt->num_rows == 1) { // se l'utente esiste
        $stmt->bind_result($password); // recupera le variabili dal risultato ottenuto.
        $stmt->fetch();
        $login_check = hash('sha512', $password.$user_browser);
        if($login_check == $login_string) {
          // Login eseguito!!!!
          return true;
        } else {
          //  Login non eseguito
          return false;
        }
      } else {
        // Login non eseguito
        return false;
      }
    } else {
      // Login non eseguito
      return false;
    }
  } else {
    // Login non eseguito
    return false;
  }
}

?>
