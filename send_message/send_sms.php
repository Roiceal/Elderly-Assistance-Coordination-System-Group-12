<?php
$config = require __DIR__ . '/config.php';

// Initialize variables for feedback
$responses = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phoneNumbers = $_POST['phone'] ?? '';
    $message = $_POST['message'] ?? '';

    if (empty($phoneNumbers) || empty($message)) {
        $responses[] = ['type' => 'danger', 'text' => 'Phone number(s) and message are required.'];
    } else {
        // Split numbers by comma or whitespace
        $numbersArray = preg_split('/[\s,]+/', trim($phoneNumbers));

        foreach ($numbersArray as $phoneNumber) {
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
                $responses[] = [
                    'type' => 'danger',
                    'text' => "Error sending to {$phoneNumber}: " . curl_error($ch)
                ];
            } else {
                $responses[] = [
                    'type' => 'success',
                    'text' => "Message sent to {$phoneNumber}: " . htmlspecialchars($response)
                ];
            }

            curl_close($ch);
        }
    }
}
?>