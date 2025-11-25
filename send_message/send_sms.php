<?php 
// send_sms.php
session_start();
$config = require __DIR__ . '/config.php';
include __DIR__ . '/../db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'] ?? '';
    $message = trim($_POST['message'] ?? '');

    if (empty($category) || empty($message)) {
        die("Category and message are required.");
    }

    // Determine table to fetch recipients
    switch ($category) {
        case 'elders':
            $stmt = $pdo->query("SELECT phone FROM users WHERE phone IS NOT NULL");
            break;
        case 'volunteers':
            $stmt = $pdo->query("SELECT phone FROM volunteers WHERE phone IS NOT NULL");
            break;
        case 'admin':
            $stmt = $pdo->query("SELECT phone FROM admins WHERE phone IS NOT NULL");
            break;
        default:
            die("Invalid category selected.");
    }

    $phones = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!$phones) {
        die("No recipients found in this category.");
    }

    // Loop through each phone and send SMS via iProg API
    foreach ($phones as $phoneNumber) {
        $phoneNumber = trim($phoneNumber);
        if ($phoneNumber === '') continue;

        $payload = [
            'api_token' => $config['iprog_api_token'],
            'phone_number' => $phoneNumber,
            'message' => $message
        ];

        $ch = curl_init("https://sms.iprogtech.com/api/v1/sms_messages");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "Error sending to {$phoneNumber}: " . curl_error($ch) . "<br>";
        } else {
            echo "Message sent to {$phoneNumber}: " . htmlspecialchars($response) . "<br>";
        }

        curl_close($ch);
    }

    echo "<br><a href='admin_sms.php' class='btn btn-primary'>Back to SMS Page</a>";
    exit();
}
?>
