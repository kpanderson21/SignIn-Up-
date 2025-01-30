<?php
// Database connection parameters
$servername = "localhost"; // Database server (usually localhost)
$username = "root";        // Database username
$password = "";            // Database password (empty for local development)
$dbname = "gals"; // The name of your database

// Create a connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
