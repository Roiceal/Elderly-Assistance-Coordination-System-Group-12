<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "sampleattendance");
if ($conn->connect_error) { exit; }

$sql = "SELECT * FROM attendance_records ORDER BY time_in DESC";
$result = $conn->query($sql);
$rows = [];
while($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
echo json_encode($rows);

$conn->close();
?>
