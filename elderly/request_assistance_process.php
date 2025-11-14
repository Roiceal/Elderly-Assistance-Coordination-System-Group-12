<?php
require_once '../database/db_connection.php';

// Sanitize Function
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $db = new Database();
    $conn = $db->getConnection();

    // Get + sanitize form inputs
    $fullname = sanitize_input($_POST['fullname']);
    $address = sanitize_input($_POST['address']);
    $contact = sanitize_input($_POST['contact']);
    $type = sanitize_input($_POST['type']);
    $details = sanitize_input($_POST['details']);
    $date_requested = date("Y-m-d H:i:s");

    // Call the stored procedure
    $stmt = $conn->prepare("CALL add_assistance_request(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss",
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
