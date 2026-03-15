<?php
// config/database.php

// Define base URL to fix routing issues
define('BASE_URL', '/clg_php_project/');

$host = 'localhost';
$db_name = 'student_management';
$username = 'root'; // default XAMPP user
$password = ''; // default XAMPP password

try {
    $conn = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
}
?>
