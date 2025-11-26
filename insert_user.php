<?php
session_start();
require "db_connect.php"; // change path if needed

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fname   = trim($_POST['fname']);
    $lname   = trim($_POST['lname']);
    $phone   = trim($_POST['phone']);
    $age     = trim($_POST['age']);
    $gender  = trim($_POST['gender']);
    $uname   = trim($_POST['uname']);
    $pass    = trim($_POST['password']);

    // Hash password
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

    try {

        $stmt = $pdo->prepare("
            INSERT INTO users (first_name, last_name, phone, age, gender, username, password)
            VALUES (:fname, :lname, :phone, :age, :gender, :uname, :password)
        ");

        $stmt->execute([
            ':fname'    => $fname,
            ':lname'    => $lname,
            ':phone'    => $phone,
            ':age'      => $age,
            ':gender'   => $gender,
            ':uname'    => $uname,
            ':password' => $hashedPassword
        ]);

        $_SESSION['success'] = "Registration successful!";
        header("Location: login.php");
        exit();

    } catch (PDOException $e) {
        die("Insert error: " . $e->getMessage());
    }
}
?>
