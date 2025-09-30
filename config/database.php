<?php
// config/database.php

$DB_HOST = 'localhost';
$DB_NAME = 'tiket';
$DB_USER = 'root';
$DB_PASS = '';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Set charset
$conn->set_charset('utf8mb4');

// helper untuk escape (jika perlu)
function esc($data) {
    global $conn;
    return htmlspecialchars($conn->real_escape_string($data));
}
?>