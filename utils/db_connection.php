<?php
// Replace these values with your actual database credentials
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'event_management';

// Create a connection to the database
$conn = mysqli_connect($host, $user, $password, $database);

// Check the connection
if(!$conn) {
  die("Connection failed: ".mysqli_connect_error());
}
?>