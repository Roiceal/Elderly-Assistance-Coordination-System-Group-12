<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sampleattendance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed."]);
    exit;
}

if (!isset($_POST['rfid'])) {
    echo json_encode(["success" => false, "message" => "No RFID received"]);
    exit;
}

$rfid = $_POST['rfid'];
$sql = "SELECT * FROM user WHERE card_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $rfid);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "success" => true,
        "name" => $row["name"],
        "address" => $row["address"],
        "age" => $row["age"],
        "DOB" => $row["dob"],
        "image" => $row["image"],
        "card_id" => $row["card_id"]
    ]);
} else {
   echo json_encode([
    "success" => false,
    "message" => "RFID not registered in the system"
]);
}

$stmt->close();
$conn->close();
?>
