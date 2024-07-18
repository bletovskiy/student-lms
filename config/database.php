<?php

$host = 'localhost';  // Change to your database host
$port = '5432';  // Change to your database port
$database = 'student_lms';  // Change to your database name
$username = 'lms_user';  // Change to your database username
$password = 'zalupa';  // Change to your database password

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$database;user=$username;password=$password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
