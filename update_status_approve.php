<?php
// session_start();
// header("Content-Type: application/json");

// ini_set('display_errors', 0);
// ini_set('log_errors', 1);
// ini_set('error_log', 'error.log');

// include "db_connect.php";

// if (!isset($_POST['request_id'])) {
//     echo json_encode(["success" => false, "message" => "Missing request ID"]);
//     exit();
// }

// $request_id = intval($_POST['request_id']);

// try {
//     $stmt = $pdo->prepare("
//         UPDATE assistance_requests
//         SET status = 'completed', updated_at = NOW()
//         WHERE id = ?
//     ");
//     $stmt->execute([$request_id]);

//     echo json_encode(["success" => true]);

// } catch (Exception $e) {
//     echo json_encode([
//         "success" => false,
//         "message" => $e->getMessage()
//     ]);
// }

// exit();


include "db_connect.php";
session_start();

if (!isset($_POST['request_id'])) {
    header("Location: dashboard_elders.php");
    exit();
}

$request_id = $_POST['request_id'];

// Update request status
$stmt = $pdo->prepare("UPDATE assistance_requests SET status = 'completed' WHERE id = ?");
$stmt->execute([$request_id]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Updating...</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
Swal.fire({
    title: "Approved!",
    text: "The assistance request has been marked as completed.",
    icon: "success",
    confirmButtonColor: "#2a7f62",
}).then(() => {
    window.location.href = "dashboard_elders.php";
});
</script>

</body>
</html>



