<?php
        $servername = "localhost";
        $username = "c1551887_calcu";
        $password = "dufe88RUso";
        $database = "c1551887_calcu";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>