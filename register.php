<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Registration</title>
</head>
<body>

<?php
if (isset($_POST["submit"])) {
    $fullname = $_POST["fullname"];
    $username = $_POST["username"];
    $phone_number = $_POST["phone_number"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];

    $errors = array();

    if (empty($fullname) || empty($username) || empty($phone_number) || empty($password)) {
        array_push($errors, "Please complete all fields.");
    }
    if ($password != $passwordRepeat) {
        array_push($errors, "Passwords do not match.");
    }

    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger text-center'>$error</div>";
        }
    } else {
        // store to db
        require_once 'db_connection.php';
        echo "<div class='alert alert-success text-center'>Registration successful!</div>";
        header("Location: verification.php");
        exit();
    }
}
?>

<div class="register">
    <form action="" method="POST">
        <div class="box">
            <input type="text" class="form-control rounded-pill" placeholder="Enter your Name" name="fullname">
        </div>
        <div class="box">
            <input type="text" class="form-control rounded-pill" placeholder="Enter your Username" name="username"> 
        </div>
        <div class="box">
            <input type="text" class="form-control rounded-pill" placeholder="Enter your Phone Number" name="phone_number">
        </div>
        <div class="box">
            <input type="password" class="form-control rounded-pill" placeholder="Enter your Password" name="password">
        </div>
        <div class="box">
            <input type="password" class="form-control rounded-pill" placeholder="Re-type Password" name="repeat_password">
        </div>
        <div class="box">
            <select name="role" id="role" required class="form-control rounded-pill">
                <option value="" disabled selected hidden>Select Role</option>
                <option value="user">User</option>
                <option value="volunteer">Volunteer</option>
            </select>
        </div>
        <div class="button">
            <button type="submit" name="submit" class="btn btn-success rounded-pill d-grid gap-2 col-6 mx-auto">Verify</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
