<?php
session_start();
include_once '../../database/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location:  ../../login/login.php");
    exit;
}

$db = new Database();
$conn = $db->getConnection();

// Sanitize input
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Check ID
if (!isset($_GET['id'])) {
    header("Location: read_users.php");
    exit;
}

$user_id = intval(sanitize_input($_GET['id']));

// Prevent deleting admin
$stmt = $conn->prepare("SELECT role FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

if ($user['role'] === 'admin') {
    die("Cannot delete admin user.");
}

$stmt->close();

// Delete user
$stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    header("Location: read_users.php");
    exit;
} else {
    die("Error deleting user.");
}
?>
