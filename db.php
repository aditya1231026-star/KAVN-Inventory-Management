<?php
$host = "localhost";
$user = "root"; // Default XAMPP/WAMP username
$pass = "";     // Default password is usually empty
$dbname = "kavn_inventory";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>