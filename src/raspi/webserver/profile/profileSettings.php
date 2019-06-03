<?php

require_once('../utility/utility.php');
require_once('../utility/db.php');
if (session_status() == PHP_SESSION_NONE) {
    sec_session_start();
}

if(login_check($conn) != true) {
  header('Location: /embedded/login/login.php');
  return;
}

?>
<!DOCTYPE html>
<html lang="it">
<head>
  <title>InzenirDlaPida_Impostazioni</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
</head>
<body>


  <?php

  require_once('../nav.php');

  if(isset($_GET['phone'])) {
    echo '
    <div class="alert alert-success">
    <strong>Complimenti!</strong> Numero di Telefono cambiato con successo.
    </div>
    ';
  }

  if(isset($_GET['error'])) {
    switch ($_GET['error']) {
      case 0: {
        echo '
          <div class="alert alert-success">
            <strong>Complimenti!</strong> Password cambiata con successo.
          </div>';
        break;}
      case 1: {
        echo '
          <div class="alert alert-danger">
            <strong>Errore!</strong> Numero di Telefono non cambiato.
          </div>';
        break;}
      case 2: {
        echo '
            <div class="alert alert-danger">
              <strong>Errore!</strong> Errore nel salvataggio password
            </div>';
        break;}
      case 3: {
        echo '
            <div class="alert alert-danger">
              <strong>Errore!</strong> Password sbagliata
            </div>';
        break;}
      case 4: {
        echo '
            <div class="alert alert-danger">
              <strong>Errore!</strong> Non esiste l\'utente
            </div>';
        break;}
      default:
        break;
    }
  }

  ?>

  <div class="w-100  pt-4" id="PAGE-BODY">

    <div class="container-fluid w-100 " id="INFO-PROFILO">
      <div class="row justify-content-center ml-0 mr-0 rounded-top bg-dark">
        <div class="col-auto text-white">
          Impostazioni profilo
        </div>
      </div>
      <div class="container-fluid w-100" >
        <div class="row justify-content-center w-100 pt-4 mr-0 ml-0">
          <ul class="list-group w-100">
            <?php

            if(isset($_SESSION['username'])){
              if ($stmt = $conn->prepare("SELECT name, surname, birthdate, phoneNumber, city FROM user WHERE username = ? LIMIT 1")) {
                $stmt->bind_param('s', $_SESSION['username']);
                // Eseguo la query creata.
                $stmt->execute();
                $stmt->bind_result($name, $surname, $birthdate, $phoneNumber, $city);
                $stmt->store_result();
                $stmt->fetch();
                if($stmt->num_rows == 1) {
                  echo '
                  <li class="list-group-item d-flex justify-content-start flex-wrap p-0">
                    <label class="p-3" >Nome: <span id="name">'.$name.'</span> </label>
                    <label class="p-3" >Cognome: <span id="surname">'.$surname.'</span> </label>
                  </li>
                  <li class="list-group-item  p-0">
                    <label  class="p-3">Email: <span id="email">'.$_SESSION['username'].'</span> </label>
                  </li>
                  <li class="list-group-item  p-0">
                    <label  class="p-3">Data di Nascita: <span id="birthdate">'.$birthdate.'</span> </label>
                  </li>
                  <li class="list-group-item  p-0">
                    <label  class="p-3">Città: <span id="city">'.$city.'</span> </label>
                  </li>
                  <li class="list-group-item  d-flex justify-content-between flex-wrap p-0">
                    <label class="p-3">Password: <span>[*******]</span> </label>
                    <form class="form  h-100 p-3" id="formModifyPwd" method="post" action="modifyPassword.php">
                      <h4>Modifica password</h4>
                      <div class="form-group pt-2">
                        <label for="oldPassword">Password attuale:
                          <input type="password" name="oldPassword" minlength="6" id="oldPassword" required autocomplete="off">
                        </label>
                      </div>
                      <div class="form-group">
                        <label for="newPassword">Nuova password:
                        <input type="password" minlength="6" name="newPassword" id="newPassword" required autocomplete="off">
                        </label>
                      </div>
                      <div class="form-group">
                        <label for="confirmPassword">Conferma nuova:
                          <input type="password" minlength="6" name="confirmPassword" id="confirmPassword" required autocomplete="off">
                        </label>
                      </div>
                      <div class="form-group">
                        <button type="submit" class="btn btn-primary" name="modifyPassword">Modifica</button>
                      </div>
                    </form>
                  </li>
                  <li class="list-group-item  d-flex justify-content-between flex-wrap p-0">
                    <label class="p-3">Numero di telefono: <span id="phoneNumber">'.$phoneNumber.'</span> </label>
                    <form class="form p-3" method="post" id="formModifyNmb" action="modifyPhone.php" >
                      <h4>Modifica numero</h4>
                      <div class="form-group pt-2">
                        <label for="newNumber">Nuovo numero:
                          <input type="tel" minlength="10" maxlength="10" name="newNumber" id="newNumber" required>
                        </label>
                      </div>
                      <button type="submit" class="btn btn-primary">Modifica</button>
                    </form>
                  </li>
                  ';
                      }
                    }
                  }

                  ?>

            </ul>
          </div>
        </div>
      </div>
    </div>
    <?php require_once('../footer.php');?>
    <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
    <script src="../utility/sha512.js"></script>
    <script src="../utility/utility.js"></script>
    <script>
    $("#formModifyPwd").submit(function(e) {
      e.preventDefault();
      $.confirm({
        title: 'Informazione!',
        content: 'Sicuro di voler modificare la password?',
        buttons: {
          conferma: () => {
            formhashChange(this, this.oldPassword, this.newPassword);
            e.currentTarget.submit();
          },
          annulla: function () {
          },
        }
      });
    });

    $("#formModifyNmb").submit(function(e) {
      e.preventDefault();
      $.confirm({
        title: 'Informazione!',
        content: 'Sicuro di voler cambiare il numero?',
        buttons: {
          conferma: function () {
            e.currentTarget.submit();
          },
          annulla: function () {
          },
        }
      });
    });
    </script>
</body>
</html>
