<?php
session_start();
require "database/config.php";
global $db;
ob_start();

$bericht = '';

// Inloggen
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $ww = $_POST['password'];
    $gebruiker = [];

    //controleer of de velden gevuld zijn
    if (empty($username) || empty($ww)) {
        $bericht = "* please enter all fields";
    } else {
        validateuser($username, $ww);
    }
}

function validateuser($username, $ww)
{
    global $db;
    $query = "SELECT * FROM user WHERE username = '$username' AND password = '$ww'";
    $pakresultaat = mysqli_query($db, $query);

    if ($pakresultaat) {
        $gebruiker = mysqli_fetch_array($pakresultaat);
        $_SESSION['id'] = $gebruiker['id'];
        $_SESSION['username'] = $gebruiker['username'];
        $_SESSION['password'] = $gebruiker['password'];
        $_SESSION['loggedin'] = true;

        if ($gebruiker["username"] == $username && $_POST["password"] == $ww) {
            $_SESSION['loggedin'] = true;
            header("location: dashboard.php");
            exit;

        } else {
            return $bericht = "* Wrong credentials, try again";
        }

    } else {
        $bericht = "* Wrong credentials, try again";
    }
}

mysqli_close($db);
?>

<!doctype html>
<html lang="en">

  <head>
    <?php include 'header.php';?>
  </head>

  <body>

    <div class="front">

      <!-- Title -->
      <h4 class="title">
        Do it now!
      </h4>

      <form action="index.php" method="post" >
        <div class="mdl-textfield mdl-js-textfield">
          <input class="mdl-textfield__input" type="text" id="sample1" name="username">
          <label class="mdl-textfield__label" for="sample1">Username</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield">
          <input class="mdl-textfield__input" type="password" id="sample1" name="password">
          <label class="mdl-textfield__label" for="sample1">Password</label>
        </div>

        <button name="login" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
          Login
        </button>

      </form>

      <a href="registration.php">
        <button class="mdl-button mdl-js-button linkfield">Sign up now!</button>
      </a>
      <p class="bericht"> <?=$bericht?></p>
    </div>

  </body>

</html>
