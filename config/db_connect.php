<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Load environment variables
$host     = getenv('DB_HOST');
$dbname   = getenv('DB_NAME');
$user     = getenv('DB_USER');
$password = getenv('DB_PASS');
$port     = getenv('DB_PORT') ?: 5432;

// Build connection string
$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";

// Connect to PostgreSQL
$conn = pg_connect($conn_string);

// Check connection
if (!$conn) {
    die("Database connection failed: " . pg_last_error());
}
?>
