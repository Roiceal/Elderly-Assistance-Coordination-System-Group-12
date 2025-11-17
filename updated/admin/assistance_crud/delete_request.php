<?php
include_once '../../database/db_connection.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id']; // cast to integer for safety

    $db = new Database();
    $conn = $db->getConnection();

    // Direct DELETE query instead of stored procedure
    $stmt = $conn->prepare("DELETE FROM assistance_request WHERE request_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Request deleted successfully!'); window.location='read_request.php';</script>";
    } else {
        echo "<script>alert('Error deleting request: " . htmlspecialchars($stmt->error) . "'); window.location='read_request.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request ID.'); window.location='read_request.php';</script>";
}
?>
