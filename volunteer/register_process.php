<?php
// Database connection
$host = 'localhost';
$db   = 'otp';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['uname']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // secure hashing

    // Insert into database
    $sql = "INSERT INTO volunteers (fname, lname, phone, username, password) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$fname, $lname, $phone, $username, $password]);

        // Redirect to login or success page
        header("Location: volunteer_login.php?success=1");
        exit;

    } catch (PDOException $e) {
        // Handle duplicate or errors
        echo "Error: " . $e->getMessage();
    }
}
?>
