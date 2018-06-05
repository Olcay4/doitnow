<?php
session_start();

//zet alle sessies uit.
session_unset();

//vernietig de sessie.
session_destroy();

// voer de uigelogde gebruiker door naar login pagina.
header('location: index.php');
exit();
