<?php
// Maak connectie met database
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_database = 'innotechdb';

$db = mysqli_connect($db_host, $db_user, $db_password, $db_database) or die(mysqli_connect_error());
