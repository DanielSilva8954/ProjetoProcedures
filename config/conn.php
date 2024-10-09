<?php

$db_name = "proceduresgsc";
$host = "localhost";
$user = "root";
$pass = "";

$conn = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
    
//Habilitar erros PDO
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

?>

