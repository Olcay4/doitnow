<?php
session_start();
require("database/config.php");

$message = '';

// registratieformulier + validatiecontrole
if (isset($_POST['accountRegistration'])) {
    $username = $_POST['username'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    $email = mysqli_real_escape_string($db, $username);
    $checkusernamequery = "select * from user where username ='$username'";
    $checkit = mysqli_query($db, $checkusernamequery);
    $checkUsername = mysqli_fetch_array($checkit);


    //controleer of de velden gevuld zijn
    if (empty($username) || empty($password1) || empty($password2)) {
        $message = "* please enter all fields";

    } elseif ($password1 != $password2) {  // controleert of de wachtwoorden overeenkomen met elkaar
        $message = "* password doesn't match";

    } elseif ($checkUsername) {// check of de mail al geregistreerd is.
        $message = "* username exists already";

    } else { // voer gegevens in database, voor standaard geberuiker.

        $query = "INSERT INTO user ( username, password, points)
          VALUES ( '$username','$password1', 5);";
        mysqli_query($db, $query);
        mysqli_close($db);
        $message = "Registration success, feel free to go back to the login page";
    }
}
?>

<!doctype html>
<html lang="en">

  <head>
    <?php include 'header.php';?>
  </head>

  <body>

    <div class="front">

        <!-- Title -->
        <h4 class="title"> Create a new Account!</h4>

        <form action="registration.php" method="post">

            <div class="mdl-textfield mdl-js-textfield">
                <input class="mdl-textfield__input" type="text" name="username" id="sample1">
                <label class="mdl-textfield__label" for="sample1">Username</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield">
                <input class="mdl-textfield__input" type="password" name="password1" id="sample1">
                <label class="mdl-textfield__label" for="sample1">Password</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield">
                <input class="mdl-textfield__input" type="password" name="password2" id="sample1">
                <label class="mdl-textfield__label" for="sample1">Repeat password</label>
            </div>

            <button name="accountRegistration" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                Create account
            </button>

        </form>
        <a href="index.php">
            <button class="mdl-button mdl-js-button linkfield">Go back to Login!</button>
        </a>
        <p class="message"><?= $message ?></p>
    
    </div>

  </body>

</html>