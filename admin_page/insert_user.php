<?php
session_start();
include __DIR__ . '/../db_connect.php';

// Enable PDO errors for debugging
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fname   = trim($_POST['fname']);
    $lname   = trim($_POST['lname']);
    $phone   = trim($_POST['phone']);
    $age     = trim($_POST['age']);
    $address = trim($_POST['address']);
    $gender  = trim($_POST['gender']);
    $uname   = trim($_POST['uname']);
    $pass    = trim($_POST['password']);

    // Handle profile image
    $imageData = null;
    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    }

    // Hash password
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("
            INSERT INTO users 
            (first_name, last_name, address, phone, age, gender, username, password, profile_image)
            VALUES 
            (:fname, :lname, :address, :phone, :age, :gender, :uname, :password, :profile_image)
        ");

        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':uname', $uname);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':profile_image', $imageData, PDO::PARAM_LOB);

        $stmt->execute();

        // SweetAlert success message
        echo '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register Success</title>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: "success",
    title: "Register Successful",
    text: "The elder has been added successfully.",
    timer: 2500,
    showConfirmButton: false
}).then(() => {
    window.location.href = "login.php";
});
</script>
</body>
</html>';
        exit;

    } catch (PDOException $e) {
        die("Insert error: " . $e->getMessage());
    }
}
