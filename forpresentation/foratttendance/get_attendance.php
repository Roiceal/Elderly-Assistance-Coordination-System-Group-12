<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');

$conn = new mysqli("localhost", "root", "", "sampleattendance");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed"]);
    exit;
}

// this fetch all attendance records
$sql = "SELECT * FROM attendance_records ORDER BY time_in DESC";
$result = $conn->query($sql);

$attendanceData = [];
while ($row = $result->fetch_assoc()) {
    $attendanceData[] = $row;
}

echo json_encode(["success" => true, "data" => $attendanceData]);

$conn->close();
?>
