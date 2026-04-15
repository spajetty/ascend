<?php

$host = "localhost";
$user = "root";
$pass = "Spajetty.21";
$db   = "ascend_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

?>