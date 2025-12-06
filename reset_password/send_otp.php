<?php
session_start();
$config = require __DIR__ . '/config.php';

// Get phone input
$phone_input = trim($_POST['phone'] ?? '');
if (!$phone_input) die('Phone number is required.');

// Normalize phone
$phone_numeric = preg_replace('/\D+/', '', $phone_input);
$phones_to_check = [$phone_numeric];
if (strlen($phone_numeric) == 11 && $phone_numeric[0] === '0') {
    $phones_to_check[] = '63' . substr($phone_numeric, 1);
} elseif (strlen($phone_numeric) == 12 && substr($phone_numeric, 0, 2) === '63') {
    $phones_to_check[] = '0' . substr($phone_numeric, 2);
}

// Connect to DB
$pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Dynamic IN placeholders
$placeholders = implode(',', array_fill(0, count($phones_to_check), '?'));
$stmt = $pdo->prepare("SELECT * FROM users WHERE phone IN ($placeholders)");
$stmt->execute($phones_to_check);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) die("No user found with this phone number.");

$phone_db = $user['phone'];

// Generate OTP
$otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$otp_hash = password_hash($otp, PASSWORD_BCRYPT);
$expires_at = (new DateTime())->add(new DateInterval('PT' . $config['otp_ttl_seconds'] . 'S'))->format('Y-m-d H:i:s');

// Store OTP in DB
$stmt = $pdo->prepare("INSERT INTO otp_codes (phone, otp_hash, expires_at) VALUES (?, ?, ?)");
$stmt->execute([$phone_db, $otp_hash, $expires_at]);

// Send OTP via SMS API
$payload = [
    'api_token' => $config['iprog_api_token'],
    'phone_number' => $phone_db,
    'message' => "Your verification code is: {$otp}"
];

$ch = curl_init("https://sms.iprogtech.com/api/v1/sms_messages");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
curl_close($ch);

// Store phone in session for verification
$_SESSION['reset_phone'] = $phone_db;

// Redirect to verify OTP page
header("Location: verify_otp.php");
exit;
