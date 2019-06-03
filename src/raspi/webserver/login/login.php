<!DOCTYPE html>
<html lang="it">
<head>
  <title>SmartHome_Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <style>
  .active {
    color: #029f5A;
    font-size: 18px;
  }
  .mine{
    color: #666;
    font-weight: bold;
  }
  </style>
  <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>

  <?php
  require_once('../utility/utility.php');
  require_once('../utility/db.php');
  require_once('../nav.php');
  if (session_status() == PHP_SESSION_NONE) {
      sec_session_start();
  }

  if(isset($_GET['error'])) {
    switch ($_GET['error']) {
      case 1: {
        echo '
          <div class="alert alert-danger">
            <strong>Errore!</strong> Dati non corretti
          </div>';
        break;
      }
      case 3: {
        echo '
          <div class="alert alert-danger">
            <strong>Errore!</strong> Inserisci tutti i campi (Lunghezza minima password è 6)
          </div>';
        break;
      }
      case 4: {
        echo '
        <div class="alert alert-danger">
          <strong>Errore!</strong> Account non confermato (controlla le email)
        </div>
        ';
        break;
      }
      case 5: {
        echo '
        <div class="alert alert-danger">
          <strong>Errore!</strong> Si è verificato un errore durante la conferma dell\'account, contattaci
        </div>
        ';
        break;
      }
      default: {
        break;
      }
    }
  }

  if(isset($_GET['captcha'])) {
    echo '
    <div class="alert alert-danger">
    <strong>Errore!</strong> Completa il CAPTCHA
    </div>
    ';
  }

  if(isset($_GET['duplicate'])) {
    echo '
    <div class="alert alert-danger">
    <strong>Errore!</strong> Account già esistente
    </div>
    ';
  }

  if(isset($_GET['registered'])) {
    echo '
    <div class="alert alert-success">
    <strong>Compliementi!</strong> Registrazione avvenuta con successo! Controlla la tua posta per confermare
    </div>
    ';
  }

  if(isset($_GET['confirmed'])) {
    echo '
    <div class="alert alert-success">
    <strong>Compliementi!</strong> Account confermato, ora puoi accedere
    </div>
    ';
  }
  ?>

  <div class="container mt-3">

    <div class="col-sm-6 offset-sm-3">
      <div class="card text-center">
        <img src="/embedded/images/Iot.png" alt="Team Logo" class="card-img-top col-4 offset-4">
        <div class="card-header">
          <ul class="nav nav-pills card-header-pills justify-content-center">
            <li class="nav-item">
              <a href="#" class="active mr-1 mine" id="login-form-link">Login</a>
            </li>
            <li class="nav-item">
              <a href="#" class="mine" id="register-form-link">Registrati</a>
            </li>
          </ul>
        </div>
        <div class="card-block mt-3">

          <!--Login Form-->
          <form name="login-form" id="login-form" method="post" action="process_login.php" autocomplete="on">
            <div class="form-group row justify-content-center">
              <label for="loginEmail" class="col-sm-4 col-form-label">Email</label>
              <div class="col-sm-7">
                <input type="email" class="form-control" name="loginEmail" id="loginEmail" placeholder="esempio@prova.com" required>
              </div>
            </div>
            <div class="form-group row justify-content-center">
              <label for="loginPassword" class="col-sm-4 col-form-label">Password</label>
              <div class="col-sm-7">
                <input type="password" class="form-control" name="loginPassword" id="loginPassword" placeholder="***" required autocomplete="off">
              </div>
            </div>
            <div class="form-check">
              <label for="remember" class="form-check-label">
                <input type="checkbox" class="form-check-input" name='remember' id="remember">
                Ricorda i dati
              </label>
            </div>
            <div class="form-group row justify-content-center">
              <div class="col-sm-10">
                <input type="button" class='btn-primary' value="Entra" id='loginBtn' onclick="check(this.form, this.form.loginPassword, this.form.loginEmail)" />
              </div>
            </div>
          </form>

          <!--Register Form-->
          <form name="register-form" id="register-form" method="post" autocomplete="on" action="process_register.php">
            <div class="form-group row justify-content-center">
              <label for="email" class="col-sm-4 col-form-label">Email</label>
              <div class="col-sm-7">
                <input type="email" class="form-control" name='email' id="email" placeholder="esempio@prova.com" required>
              </div>
            </div>
            <div class="form-group row justify-content-center">
              <label for="password" class="col-sm-4 col-form-label">Password</label>
              <div class="col-sm-7">
                <input type="password" class="form-control" name='password' id="password" placeholder="***" required autocomplete="off">
              </div>
            </div>
            <div class="form-group row justify-content-center">
              <label for="name" class="col-sm-4 col-form-label">Nome</label>
              <div class="col-sm-7">
                <input type="Text" class="form-control" name='name' id="name" placeholder="Mario" required>
              </div>
            </div>
            <div class="form-group row justify-content-center">
              <label for="surname" class="col-sm-4 col-form-label">Cognome</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" name='surname' id="surname" placeholder="Rossi" required>
              </div>
            </div>
            <div class="form-group row justify-content-center">
              <label for="birthdate" class="col-sm-4 col-form-label">Data di Nascita</label>
              <div class="col-sm-7">
                <input type="date" lang="it" class="form-control" name='birthdate' id="birthdate" required>
              </div>
            </div>
            <div class="form-group row justify-content-center">
              <label for="city" class="col-sm-4 col-form-label">Città</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" name='city' id="city" placeholder="Roma" required>
              </div>
            </div>
            <div class="form-group row justify-content-center">
              <label for="phone" class="col-sm-4 col-form-label">Telefono</label>
              <div class="col-sm-7">
                <input type="tel" class="form-control" name='phone' id="phone" placeholder="3332211321" required>
              </div>
            </div>
            <div class="g-recaptcha form-group row justify-content-center" data-sitekey="6Ld-xUIUAAAAAAGpAoe4zekRXXJ4FGNV02u08a3q"></div>
            <div class="form-group row justify-content-center">
              <div class="col-sm-10">
                <button type="button" class="btn btn-primary" onclick="formhash(this.form,this.form.password)">Registra</button>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>

  <?php require_once('../footer.php'); ?>

  <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="/embedded/utility/sha512.js"></script>
  <script>
  $("#register-form").hide();

  $('#login-form-link').click(function(e) {
    $("#login-form").fadeIn(200);
    $("#register-form").fadeOut(100);
    $('#register-form-link').removeClass('active');
    $(this).addClass('active');
    e.preventDefault();
  });

  $('#register-form-link').click(function(e) {
    $("#register-form").fadeIn(200);
    $("#login-form").fadeOut(100);
    $('#login-form-link').removeClass('active');
    $(this).addClass('active');
    e.preventDefault();
  });

  $("#loginPassword").keyup(function(event) {
    if (event.keyCode === 13) {
        $("#loginBtn").click();
    }
  });

  function check(form, password, email) {
    if(password.value.length >= 6 && email.value.length > 0) {
      formhash(form, password);
    } else {
      location.href = 'login.php?error=3';
    }
  }
  </script>
  <script src="/embedded/utility/utility.js"></script>

  <?php
  if(isset($_COOKIE['email'])) {
    $email = $_COOKIE['email'];
    echo "<script>
            $('#loginEmail').val('$email');
          </script>";
  };
  ?>

</body>
</html>
