<?php
// Database configuration
$dbHost = 'localhost'; // Change this if your database server is different
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'school_online_exam';

// Create database connection
$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
