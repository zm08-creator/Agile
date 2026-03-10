<?php
try {
    $pdo = new PDO(
        "pgsql:host=localhost;port=5432;dbname=agile_db",
        "postgres",
        "Admin123"
    );
    echo "Connected!";
} catch (PDOException $e) {
    echo $e->getMessage();
}