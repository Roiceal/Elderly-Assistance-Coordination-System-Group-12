<?php
session_start();
require "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fname   = trim($_POST['fname']);
    $lname   = trim($_POST['lname']);
    $phone   = trim($_POST['phone']);
    $age     = trim($_POST['age']);
    $gender  = trim($_POST['gender']);
    $uname   = trim($_POST['uname']);
    $pass    = trim($_POST['password']);

    // Image
    $imageData = null;
    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    }

    // Hash password
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

    try {

        $stmt = $pdo->prepare("
            INSERT INTO users 
                (first_name, last_name, phone, age, gender, username, password, profile_image)
            VALUES 
                (:fname, :lname, :phone, :age, :gender, :uname, :password, :profile_image)
        ");

        // Correct bindings
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':uname', $uname);  // FIXED
        $stmt->bindParam(':password', $hashedPassword);  // FIXED
        $stmt->bindParam(':profile_image', $imageData, PDO::PARAM_LOB);

        $success = $stmt->execute();

        if ($success) {
            echo "<script>alert('Registration successful! You may now log in.');
        window.location.href='../login.php';
        </script>";
            exit;
        } else {
            die("Error: Registration failed.");
        }
    } catch (PDOException $e) {
        die("Insert error: " . $e->getMessage());
    }
}
