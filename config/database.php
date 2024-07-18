<?php

$host = '123';  // Change to your database host
$port = '5432';  // Change to your database port
$database = '123';  // Change to your database name
$username = '123';  // Change to your database username
$password = '123';  // Change to your database password

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$database;user=$username;password=$password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
