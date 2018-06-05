<?php
// check of de gebruiker toegang heeft.
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
}
