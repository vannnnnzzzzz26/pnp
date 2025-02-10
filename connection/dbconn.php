<?php

$host = 'localhost';
$dbname = 'dbcomplaints';
$username = 'root';
$password = '';

try {
    // Creating a new PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Set error mode to exception
} catch (PDOException $e) {
    // Catching any connection errors
    die("Database connection failed: " . $e->getMessage());
}






?>