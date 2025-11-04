<?php
include 'db_connect.php';

$response = [];

try {
    $sql = "DELETE FROM attendance_records";
    if ($conn->query($sql) === TRUE) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
        $response['message'] = "Error: " . $conn->error;
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
$conn->close();
?>
