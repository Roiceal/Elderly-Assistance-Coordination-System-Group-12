<?php
require_once '../database/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $db = new Database();
    $conn = $db->getConnection();

    // Get form inputs
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $type = $_POST['type'];
    $details = $_POST['details'];
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
        echo "<script>alert('Request submitted successfully!'); window.location='request_assistance.html';</script>";
    } else {
        echo "<script>alert('Error submitting request: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
