<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');

// db connection
$conn = new mysqli("localhost", "root", "", "sampleattendance");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed"]);
    exit;
}

// stop if no RFID is provided
if (!isset($_POST['rfid'])) {
    echo json_encode(["success" => false, "message" => "No RFID received"]);
    exit;
}

$rfid = $_POST['rfid'];

// check if the rfid exists in the user table
$sql = "SELECT * FROM user WHERE card_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $rfid);
$stmt->execute();
$userResult = $stmt->get_result();

if ($user = $userResult->fetch_assoc()) {
    $name = $user["name"];
    $address = $user["address"];
    $currentTime = date("Y-m-d h:i:s A");

    // get the last attendance record for this user
    $checkSql = "SELECT * FROM attendance_records WHERE card_id = ? ORDER BY id DESC LIMIT 1";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $rfid);
    $checkStmt->execute();
    $attendanceResult = $checkStmt->get_result();
    $lastRecord = $attendanceResult->fetch_assoc();

    if ($lastRecord) {
        if (is_null($lastRecord['time_out'])) {
            // user is currently checked in if so, check them out
            $updateStmt = $conn->prepare("UPDATE attendance_records SET time_out = ? WHERE id = ?");
            $updateStmt->bind_param("si", $currentTime, $lastRecord["id"]);
            $updateStmt->execute();
            $updateStmt->close();

            echo json_encode([
                "success" => true,
                "name" => $lastRecord["name"],
                "card_id" => $rfid,
                "address" => $lastRecord["address"],
                "time_in" => $lastRecord["time_in"],
                "time_out" => $currentTime,
                "message" => "Checked out successfully!"
            ]);
        } else {
            // user already checked out if so, show message
            echo json_encode([
                "success" => false,
                "message" => "You have already checked out."
            ]);
        }
    } else {
        // no previous record if so, check them in
        $insertStmt = $conn->prepare("INSERT INTO attendance_records (card_id, name, address, time_in) VALUES (?, ?, ?, ?)");
        $insertStmt->bind_param("ssss", $rfid, $name, $address, $currentTime);
        $insertStmt->execute();
        $insertStmt->close();

        echo json_encode([
            "success" => true,
            "name" => $name,
            "card_id" => $rfid,
            "address" => $address,
            "time_in" => $currentTime,
            "time_out" => "-",
            "message" => "Checked in successfully!"
        ]);
    }

    $checkStmt->close();
} else {
    echo json_encode(["success" => false, "message" => "RFID not registered"]);
}

$stmt->close();
$conn->close();
?>
