<?php
session_start();
include_once '../../database/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location:  ../../login/login.php");
    exit;
}

$db = new Database();
$conn = $db->getConnection();

// Sanitation function
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Check if ID exists
if (!isset($_GET['id'])) {
    header("Location: read_users.php");
    exit;
}

$user_id = intval($_GET['id']);
$message = "";

/* -------------------------------------------
   FETCH USER (Prevent Editing Admin)
------------------------------------------- */
$sql = "SELECT * FROM users WHERE user_id = ? AND role != 'admin'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("User not found or cannot edit admin.");
}

$user = $result->fetch_assoc();

/* -------------------------------------------
   UPDATE USER
------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize inputs
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $phone_number = sanitize_input($_POST['phone_number']);
    $address = sanitize_input($_POST['address']);
    $gender = sanitize_input($_POST['gender']);
    $birthday = sanitize_input($_POST['birthday']);
    $role = sanitize_input($_POST['role']);
    $rfid_pin = sanitize_input($_POST['rfid_pin']);

    // Prepared UPDATE statement
    $sql_update = "UPDATE users SET 
        username = ?, 
        email = ?, 
        phone_number = ?, 
        address = ?, 
        gender = ?, 
        birthday = ?, 
        role = ?,
        rfid_pin = ?
        WHERE user_id = ?";

    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param(
        "ssssssssi",
        $username,
        $email,
        $phone_number,
        $address,
        $gender,
        $birthday,
        $role,
        $rfid_pin,
        $user_id
    );

    if ($stmt_update->execute()) {
        header("Location: read_users.php");
        exit;
    } else {
        $message = "Error updating user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Update User</h2>
    <?php if($message) echo "<div class='alert alert-danger'>$message</div>"; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control"
                   value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="mb-3">
            <label>Phone Number</label>
            <input type="text" name="phone_number" class="form-control"
                   value="<?php echo htmlspecialchars($user['phone_number']); ?>">
        </div>

        <div class="mb-3">
            <label>Address</label>
            <input type="text" name="address" class="form-control"
                   value="<?php echo htmlspecialchars($user['address']); ?>">
        </div>

        <div class="mb-3">
            <label>Gender</label>
            <select name="gender" class="form-control">
                <option value="Male" <?php if($user['gender']=='Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if($user['gender']=='Female') echo 'selected'; ?>>Female</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Birthday</label>
            <input type="date" name="birthday" class="form-control"
                   value="<?php echo $user['birthday']; ?>">
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="user" <?php if($user['role']=='user') echo 'selected'; ?>>User</option>
                <option value="volunteer" <?php if($user['role']=='volunteer') echo 'selected'; ?>>Volunteer</option>
            </select>
        </div>

        <div class="mb-3">
            <label>RFID PIN</label>
            <input type="text" name="rfid_pin" class="form-control"
                   value="<?php echo htmlspecialchars($user['rfid_pin']); ?>">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="read_users.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>
