<?php
require_once 'db_connection.php'; // include your Database class

if (isset($_POST["submit"])) {
    // Collect form data
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];
    $role = $_POST["role"];
    $rfid_pin = trim($_POST["rfid_pin"]);

    $errors = array();

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        array_push($errors, "Please fill in all required fields.");
    }

    if ($password !== $passwordRepeat) {
        array_push($errors, "Passwords do not match.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Please enter a valid email address.");
    }

    // RFID pin check (optional)
    if (!empty($rfid_pin) && !preg_match("/^[0-9]{4,10}$/", $rfid_pin)) {
        array_push($errors, "RFID pin must be numeric and between 4–10 digits (if provided).");
    }

    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger text-center'>$error</div>";
        }
    } else {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Connect to DB
        $db = new Database();
        $conn = $db->getConnection();

        // Check for duplicate username or email
        $check = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "<div class='alert alert-danger text-center'>Username or email already exists.</div>";
        } else {
            // Insert new user (only use the fields you want)
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, rfid_pin) VALUES (?, ?, ?, ?, ?)");
            $rfidValue = !empty($rfid_pin) ? $rfid_pin : NULL;
            $stmt->bind_param("sssss", $username, $email, $hashedPassword, $role, $rfidValue);

            if ($stmt->execute()) {
                echo "<div class='alert alert-success text-center'>Registration successful!</div>";
                header("Location: verification.php");
                exit();
            } else {
                echo "<div class='alert alert-danger text-center'>Error: " . htmlspecialchars($stmt->error) . "</div>";
            }

            $stmt->close();
        }

        $check->close();
        $db->close();
    }
}
?>

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

<div class="register">
    <form action="" method="POST">
        <div class="box">
            <input type="text" class="form-control rounded-pill" placeholder="Enter your Username" name="username" required>
        </div>
        <div class="box">
            <input type="email" class="form-control rounded-pill" placeholder="Enter your Email" name="email" required>
        </div>
        <div class="box">
            <input type="password" class="form-control rounded-pill" placeholder="Enter your Password" name="password" required>
        </div>
        <div class="box">
            <input type="password" class="form-control rounded-pill" placeholder="Re-type Password" name="repeat_password" required>
        </div>
        <div class="box">
            <select name="role" id="role" required class="form-control rounded-pill">
                <option value="" disabled selected hidden>Select Role</option>
                <option value="user">User</option>
                <option value="volunteer">Volunteer</option>
            </select>
        </div>
        <div class="box">
            <input type="number" class="form-control rounded-pill" placeholder="Enter RFID Pin (optional)" name="rfid_pin">
        </div>
        <div class="button">
            <button type="submit" name="submit" class="btn btn-success rounded-pill d-grid gap-2 col-6 mx-auto">Register</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
