<?php
$servername = "localhost"; // Cambia esto si es necesario
$username = "c1551887_calcu"; // Cambia esto si es necesario
$password = "dufe88RUso"; // Cambia esto si es necesario
$dbname = "c1551887_calcu"; // Cambia esto si es necesario

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>