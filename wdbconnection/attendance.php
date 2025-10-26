<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sampleattendance";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed"]);
    exit;
}

if (!isset($_POST['card_id']) || !isset($_POST['name'])) {
    echo json_encode(["success" => false, "message" => "Missing data"]);
    exit;
}

$card_id = $_POST['card_id'];
$name = $_POST['name'];
$time_in = date("Y-m-d H:i:s");


$sql_check = "SELECT * FROM attendance_records WHERE card_id=? AND DATE(time_in)=CURDATE()";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $card_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Already checked in today"]);
    exit;
}


$sql = "INSERT INTO attendance_records (card_id, name, time_in) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $card_id, $name, $time_in);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Attendance recorded"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to record attendance"]);
}

$stmt->close();
$conn->close();
?>
