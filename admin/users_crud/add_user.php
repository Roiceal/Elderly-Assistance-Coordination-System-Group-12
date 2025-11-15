<?php
session_start();
include_once '../../database/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location:  ../../login/login.php");
    exit;
}

$db = new Database();
$conn = $db->getConnection();

$message = "";

// Sanitation function
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize inputs
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone_number = sanitize_input($_POST['phone_number']);
    $address = sanitize_input($_POST['address']);
    $gender = sanitize_input($_POST['gender']);
    $birthday = sanitize_input($_POST['birthday']);
    $role = sanitize_input($_POST['role']);
    $rfid_pin = sanitize_input($_POST['rfid_pin']);

    // Prepare secure SQL
    $sql = "INSERT INTO users 
                (username, email, password, phone_number, address, gender, birthday, role, rfid_pin)
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param(
            "sssssssss",
            $username,
            $email,
            $password,
            $phone_number,
            $address,
            $gender,
            $birthday,
            $role,
            $rfid_pin
        );

        // Execute statement
        if ($stmt->execute()) {
            $message = "User created successfully!";
            header("Location: read_users.php");
            exit;
        } else {
            $message = "Execution Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Prepare Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

    <h2>Create User</h2>

    <?php if ($message): ?>
        <div class="alert alert-info">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Phone Number</label>
            <input type="text" name="phone_number" class="form-control">
        </div>

        <div class="mb-3">
            <label>Address</label>
            <input type="text" name="address" class="form-control">
        </div>

        <div class="mb-3">
            <label>Gender</label>
            <select name="gender" class="form-control">
                <option value="">Select</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Birthday</label>
            <input type="date" name="birthday" class="form-control">
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="user">User</option>
                <option value="volunteer">Volunteer</option>
            </select>
        </div>

        <div class="mb-3">
            <label>RFID PIN</label>
            <input type="text" name="rfid_pin" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Create</button>
        <a href="read_users.php" class="btn btn-secondary">Cancel</a>

    </form>

</body>
</html>
