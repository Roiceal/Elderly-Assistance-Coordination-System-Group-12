<?php
// ==============================
// iProgSMS PHP Send Message Example
// ==============================

// Your iProgSMS credentials
$apiKey = "b3801576915b73107987929e34ea18b23def900b";       // Replace with your actual API key
$senderId = "IPROGSMS";         // Registered Sender ID
$apiUrl = "https://www.iprogsms.com/api/v3/sms/send"; // iProgSMS API endpoint

// Message data
$phoneNumber = $_POST['phone'] ?? '';   // Example: 639123456789
$message = $_POST['message'] ?? '';     // The message text

// Basic validation
if (!$phoneNumber || !$message) {
    die("Phone number and message are required.");
}

// Prepare POST data
$data = [
    "apikey" => $apiKey,
    "senderid" => $senderId,
    "number" => $phoneNumber,
    "message" => $message
];

// Initialize CURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

// Execute and decode response
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "Error: " . curl_error($ch);
    exit;
}

curl_close($ch);

// Display response
echo "API Response: " . $response;
?>
