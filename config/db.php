<?php
// PostgreSQL connection for agile_db

$host     = "localhost";
$port     = "5432";          // Default PostgreSQL port
$dbname   = "agile_db";
$user     = "postgres";      // You said this username is correct
$password = "REPLACE_WITH_YOUR_PASSWORD";

// Build connection string
$connStr = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password}";

// Open connection
$conn = pg_connect($connStr);

// Check connection
if (!$conn) {
    die("Connection failed: " . pg_last_error());
}
?>
