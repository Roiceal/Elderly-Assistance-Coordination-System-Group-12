<?php
$config = require __DIR__ . '/config.php';

// Get numbers and message from form
$phoneNumbers = $_POST['phone'] ?? '';
$message = $_POST['message'] ?? '';

// Validate
if (empty($phoneNumbers) || empty($message)) {
    die("Phone number(s) and message are required.");
}

// Split the numbers by comma or space
$numbersArray = preg_split('/[\s,]+/', trim($phoneNumbers));

// Prepare to send each message
foreach ($numbersArray as $phoneNumber) {
    // Clean whitespace and ensure not empty
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
        echo "Response for {$phoneNumber}: " . htmlspecialchars($response) . "<br>";
    }

    curl_close($ch);
}
?>
