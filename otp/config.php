<?php
// config.php
return [
    'db' => [
        'dsn' => 'mysql:host=localhost;dbname=elderlyassistancecoordinationdb;charset=utf8mb4',
        'user' => 'root',
        'pass' => '',
    ],
    // IPROG API token (get from your IPROG account)
    'iprog_api_token' => 'b3801576915b73107987929e34ea18b23def900b',

    // OTP settings
    'otp_ttl_seconds' => 120, // ⏰ 1 minute expiration
    'otp_length' => 6,
    'max_requests_per_phone_per_hour' => 3,
];
?>
