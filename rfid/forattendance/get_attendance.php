<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');

$conn = new mysqli("localhost", "root", "", "elderlyassistancecoordinationdb");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed"]);
    exit;
}

// Fetch all attendance records with user info
$sql = "
    SELECT a.id, a.card_id, a.time_in, a.time_out, 
           u.name, u.address, u.age, u.image
    FROM attendance_records a
    LEFT JOIN user_rfid u ON a.card_id = u.card_id
    ORDER BY a.time_in DESC
";
$result = $conn->query($sql);

$attendanceData = [];
while ($row = $result->fetch_assoc()) {
    $attendanceData[] = [
        "id" => $row["id"],
        "card_id" => $row["card_id"],
        "name" => $row["name"],
        "address" => $row["address"],
        "age" => $row["age"],
        "image" => $row["image"],
        "time_in" => $row["time_in"],
        "time_out" => $row["time_out"] ? $row["time_out"] : "-"
    ];
}

echo json_encode(["success" => true, "data" => $attendanceData]);

$conn->close();
?>
