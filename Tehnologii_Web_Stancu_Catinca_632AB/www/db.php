<?php
// db.php
$host = "RuSh_DB"; // Numele serviciului din docker-compose
$user = "root";
$pass = "root";
$db   = "rush_app";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}
?>