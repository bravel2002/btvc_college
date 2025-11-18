<?php
$servername = "localhost";
$username = "root";
$password = ""; // leave empty for XAMPP default
$database = "college_portal"; // or whatever your DB name is

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
