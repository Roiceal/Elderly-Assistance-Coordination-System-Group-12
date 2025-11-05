<?php
$config = require __DIR__ . '/config.php';
$phone = trim($_POST['phone'] ?? $_GET['phone'] ?? '');
if (!$phone) die('Phone number is required.');

// Normalize phone
function normalize_phone($p) {
    $p = preg_replace('/\D+/', '', $p);
    if (strlen($p) == 11 && $p[0] === '0') return '63' . substr($p, 1);
    return $p;
}
$phone_norm = normalize_phone($phone);

// Connect DB
try {
    $pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die('DB connection failed: ' . $e->getMessage());
}

// Check OTP limit (3/hour)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM otp_codes WHERE phone = ? AND created_at >= (NOW() - INTERVAL 1 HOUR)");
$stmt->execute([$phone_norm]);
$requests_last_hour = (int)$stmt->fetchColumn();
if ($requests_last_hour >= $config['max_requests_per_phone_per_hour']) {
    die('<h3 style="color:red;text-align:center;margin-top:50px;">❌ You have reached the maximum of 3 OTP requests per hour.<br>Please try again later.</h3>');
}

// Generate OTP
$otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$otp_hash = password_hash($otp, PASSWORD_BCRYPT);
$expires_at = (new DateTime())->add(new DateInterval('PT' . $config['otp_ttl_seconds'] . 'S'))->format('Y-m-d H:i:s');

// Store OTP
$stmt = $pdo->prepare("INSERT INTO otp_codes (phone, otp_hash, expires_at) VALUES (?, ?, ?)");
$stmt->execute([$phone_norm, $otp_hash, $expires_at]);

// Send OTP via Iprog API
$message = "Your verification code is: {$otp}. It will expire in 1 minute.";
$payload = [
    'api_token' => $config['iprog_api_token'],
    'phone_number' => $phone_norm,
    'message' => $message
];

$ch = curl_init("https://sms.iprogtech.com/api/v1/sms_messages");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
curl_close($ch);

// Redirect to verification page
header("Location: verify_otp.php?phone=" . urlencode($phone_norm));
exit;
?>
