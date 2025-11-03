<?php
require_once 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("CALL delete_request(?)");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Request deleted successfully!'); window.location='read_request.php';</script>";
    } else {
        echo "<script>alert('Error deleting request.'); window.location='read_request.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request ID.'); window.location='read_request.php';</script>";
}
?>
