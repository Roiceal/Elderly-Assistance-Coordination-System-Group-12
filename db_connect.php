<?php
$host = "localhost";
$dbname = "elderlyassistancecoordinationdb";
$username = "root";
$password = "";

// $host = "sql100.infinityfree.com";
// $dbname = "if0_40587247_elderlydb";
// $username = "if0_40587247";
// $password = "FUcN4ix4qz";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}
?>
