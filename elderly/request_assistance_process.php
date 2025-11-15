<?php
session_start();
include_once '../database/db_connection.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

// Sanitize Function
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $db = new Database();
    $conn = $db->getConnection();

    // Logged in user ID
    $user_id = $_SESSION['user_id'];

    // Get + sanitize inputs
    $fullname = sanitize_input($_POST['fullname']);
    $address = sanitize_input($_POST['address']);
    $contact = sanitize_input($_POST['contact']);
    $type = sanitize_input($_POST['type']);
    $details = sanitize_input($_POST['details']);
    $date_requested = date("Y-m-d H:i:s");

    // Execute stored procedure
    $stmt = $conn->prepare("CALL add_assistance_request(?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss",
        $user_id,
        $fullname,
        $address,
        $contact,
        $type,
        $details,
        $date_requested
    );

    if ($stmt->execute()) {
        echo "<script>alert('Request submitted successfully!'); window.location='request_assistance.php';</script>";
    } else {
        echo "<script>alert('Error submitting request: " . htmlspecialchars($stmt->error) . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
