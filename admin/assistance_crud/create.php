<?php
require_once '../../database/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = new Database();
    $conn = $db->getConnection();

    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $type = $_POST['type'];
    $details = $_POST['details'];
    $date_requested = date('Y-m-d H:i:s');

    // Call the stored procedure
    $stmt = $conn->prepare("CALL add_assistance_request(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $fullname, $address, $contact, $type, $details, $date_requested);

    if ($stmt->execute()) {
        header("Location: read_request.php?success=1");
        exit;
    } else {
        header("Location: add_request.php?error=1");
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>
