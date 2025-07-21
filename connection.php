<?php
$host = 'localhost';
$dbname = 'tapauplanet';
$username = 'root';
$password = ''; // Leave empty if you're using XAMPP or WAMP

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
