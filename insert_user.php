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
            echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Success</title>
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
        font-family: "Poppins", sans-serif;
      background-color: whitesmoke;
      color: #fff;
    }</style>
</head>
<body>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "success",
            title: "Regiter Successful",
            text: "Register successful",
            timer: 3000,
            showConfirmButton: false
        }).then(() => {
            window.location.href = "login.php";
        });
    </script>
</body>
</html>';
            exit;
        } else {
            die("Error: Registration failed.");
        }
    } catch (PDOException $e) {
        die("Insert error: " . $e->getMessage());
    }
}
