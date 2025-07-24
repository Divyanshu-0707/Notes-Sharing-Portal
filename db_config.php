<?php
$host = 'localhost';
$user = 'your_mysql_user';
$pass = 'your_mysql_password';
$db   = 'notes_portal';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
