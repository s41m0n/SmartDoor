<!DOCTYPE html>
<html lang="it">
<head>
  <title>SmartHome_Home</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

  <?php
  require_once('./utility/utility.php');
  require_once('./utility/db.php');
  require_once('nav.php');
  if (session_status() == PHP_SESSION_NONE) {
    sec_session_start();
  }
  ?>

  <div class="w-100 pt-4" id="PAGE-BODY" style="padding-bottom:13%">
    <div class="container-fluid w-100" id="data" >
    <?php

    if(isset($_SESSION,$_SESSION['username'])) {
      if ($stmt = $conn->prepare("SELECT temp FROM temperature WHERE idTemperature = ?")) {
        $tmp = "1";
        $stmt->bind_param('s', $tmp);
        $stmt->execute(); // esegue la query appena creata.
        $stmt->store_result();
        $stmt->bind_result($temperature);
        if($stmt->fetch()) {
          echo '<div class="row justify-content-center ml-0 mr-0">
                  <p>Temperature -> </p><div id="temperature">'.$temperature.' °C</div>
                </div>';
        }
      }
      if ($stmt = $conn->prepare("SELECT value FROM intensity WHERE idIntensity = ?")) {
        $int = "1";
        $stmt->bind_param('s', $int);
        $stmt->execute(); // esegue la query appena creata.
        $stmt->store_result();
        $stmt->bind_result($intensity);
        if($stmt->fetch()) {
          echo '<div class="row justify-content-center ml-0 mr-0">
                  <p>Intensità -> </p><div id="intensity">'.$intensity.' %</div>
                </div>';
        }
      }
    }

    ?>
    </div>
    <div class="container-fluid w-100" id="log" >
      <div class="row justify-content-center ml-0 mr-0 rounded-top bg-dark">
        <div class="col-auto text-white">
          <strong>I miei Accessi</strong>
        </div>
      </div>
      <div class="container-fluid w-100 pl-0 pr-0">

        <?php

        if(isset($_SESSION,$_SESSION['username'])) {
          if ($stmt = $conn->prepare("SELECT time, result FROM log WHERE username = ? AND time > ?")) {
            $today = new DateTime('today');
            $today = $today->format('Y-m-d H:i:s');
            $stmt->bind_param('ss', $_SESSION['username'], $today); // esegue il bind del parametro '$email'.
            $stmt->execute(); // esegue la query appena creata.
            $stmt->store_result();
            $stmt->bind_result($logData, $logResult);
            echo '
            <table class="table table-hover table-inverse">
              <thead>
                <tr>
                  <th>Descrizione</th>
                  <th>Data</th>
                  <th>Username</th>
                  <th>Risultato</th>
                </tr>
              </thead>
              <tbody>';
            while($stmt->fetch())
            echo '
            <tr>
              <td>Tentativo d\'accesso</td>
              <td>'.$logData.'</td>
              <td>'.$_SESSION['username'].'</td>
              <td>'.$logResult.'</td>
            </tr>';

            echo '
            </tbody>
          </table>';
          }
        }
        ?>

      </div>
    </div>
  </div>

  <?php
  require_once('footer.php');
  if(isset($_SESSION['username'])){
    echo '
    <script>
    setInterval(function() {
      myUpdate();
    }, 5000);
    </script>';
  }
  ?>

  <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script>
  function myUpdate() {
    $.post("update.php",{}, function(e) {
      if(e != 0) {
        let result = e.split(" ");
        $("#temperature").text(result[0] + " °C");
        $("#intensity").text(result[1] + " %");
      };
    });
  }
  </script>
</body>
</html>
