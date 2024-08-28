<?php
// Datos de conexión
$hostname = "localhost";
$username = "root";
$password = "";
$database = "c1551887_sisnew";

// Crear la conexión
$conex = mysqli_connect($hostname, $username, $password, $database);

// Verificar la conexión
if (!$conex) {
    die("La conexión a la base de datos falló: " . mysqli_connect_error());
}

// Cerrar la conexión