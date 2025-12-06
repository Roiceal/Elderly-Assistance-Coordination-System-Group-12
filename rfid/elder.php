<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "root", "", "elderlyassistancecoordinationdb");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed"]);
    exit;
}

// Check if RFID is provided
if (!isset($_POST['rfid'])) {
    echo json_encode(["success" => false, "message" => "No RFID received"]);
    exit;
}

$rfid = $_POST['rfid'];

// Prepare and execute query
$sql = "SELECT * FROM user_rfid WHERE card_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $rfid);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "success" => true,
        "user_id" => $row["user_id"],
        "name" => $row["name"],
        "address" => $row["address"],
        "age" => $row["age"],
        "image" => $row["image"],  // file path or base64 depending on your setup
        "card_id" => $row["card_id"]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "RFID not registered"]);
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
