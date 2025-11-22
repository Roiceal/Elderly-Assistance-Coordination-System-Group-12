<?php
// $config = require __DIR__ . '/config.php';

// // Get numbers and message from form
// $phoneNumbers = $_POST['phone'] ?? '';
// $message = $_POST['message'] ?? '';

// // Validate
// if (empty($phoneNumbers) || empty($message)) {
//     die("Phone number(s) and message are required.");
// }

// // Split the numbers by comma or space
// $numbersArray = preg_split('/[\s,]+/', trim($phoneNumbers));

// // Prepare to send each message
// foreach ($numbersArray as $phoneNumber) {
//     // Clean whitespace and ensure not empty
//     $phoneNumber = trim($phoneNumber);
//     if ($phoneNumber === '') continue;

//     $payload = [
//         'api_token' => $config['iprog_api_token'],
//         'phone_number' => $phoneNumber,
//         'message' => $message
//     ];

//     $ch = curl_init("https://sms.iprogtech.com/api/v1/sms_messages");
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
//     curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
//     $response = curl_exec($ch);

//     if (curl_errno($ch)) {
//         echo "Error sending to {$phoneNumber}: " . curl_error($ch) . "<br>";
//     } else {
//         echo "Response for {$phoneNumber}: " . htmlspecialchars($response) . "<br>";
//     }

//     curl_close($ch);
// }



$config = require __DIR__ . '/config.php';

// Database connection
$host = 'localhost';
$db   = 'otp';  // change to your database name
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get message from POST
$message = $_POST['message'] ?? '';

if (empty($message)) {
    die("Message is required.");
}

// Fetch all elder phone numbers
$stmt = $pdo->query("SELECT phone FROM users");  // change table/column names if different
$elders = $stmt->fetchAll();

if (!$elders) {
    die("No elders found in the database.");
}

// Loop through each elder and send SMS
foreach ($elders as $elder) {
    $phoneNumber = trim($elder['phone']);
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
?>





