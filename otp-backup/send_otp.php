<?php
// send_otp.php
$config = require __DIR__ . '/config.php';
$phone = trim($_POST['phone'] ?? '');
if (!$phone) {
    die('Phone is required.');
}

// basic normalization: convert leading 0 to +63 (Philippines) if appropriate
function normalize_phone($p) {
    $p = preg_replace('/\D+/', '', $p);
    if (strlen($p) == 11 && $p[0] === '0') {
        return '63' . substr($p,1);
    }
    // if already 12 starting with 63
    return $p;
}
$phone_norm = normalize_phone($phone);

// DB
try {
    $pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die('DB connection failed: ' . $e->getMessage());
}

// rate-limiting: count requests in last hour
$stmt = $pdo->prepare("SELECT COUNT(*) FROM otp_codes WHERE phone = ? AND created_at >= (NOW() - INTERVAL 1 HOUR)");
$stmt->execute([$phone_norm]);
$requests_last_hour = (int)$stmt->fetchColumn();
if ($requests_last_hour >= $config['max_requests_per_phone_per_hour']) {
    die('Too many OTP requests. Try again later.');
}

// Generate numeric OTP
$otp = '';
for ($i=0; $i < $config['otp_length']; $i++) {
    $otp .= random_int(0,9);
}

// Hash OTP before storing
$otp_hash = password_hash($otp, PASSWORD_BCRYPT);
$expires_at = (new DateTime())->add(new DateInterval('PT' . $config['otp_ttl_seconds'] . 'S'))->format('Y-m-d H:i:s');

// store
$insert = $pdo->prepare("INSERT INTO otp_codes (phone, otp_hash, expires_at) VALUES (?, ?, ?)");
$insert->execute([$phone_norm, $otp_hash, $expires_at]);

// Prepare SMS message
$message = "Your verification code is: {$otp}. It will expire in " . (int)($config['otp_ttl_seconds'] / 60) . " minutes.";

// Send via IPROG (API expects parameters: api_token, phone_number, message)
// Endpoint from IPROG docs: POST https://sms.iprogtech.com/api/v1/sms_messages?api_token=...&message=...&phone_number=...
$api_token = $config['iprog_api_token'];
$endpoint = "https://sms.iprogtech.com/api/v1/sms_messages";

// Using cURL
$payload = [
    'api_token' => $api_token,
    'phone_number' => $phone_norm, // use 639... format preferred
    'message' => $message
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint . '?' . http_build_query($payload));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if (curl_errno($ch)) {
    $err = curl_error($ch);
    curl_close($ch);
    die('cURL error: ' . $err);
}
curl_close($ch);

// you can parse $response and show friendly message
// example successful response: {"status":200,"message":"Your SMS message has been successfully added to the queue and will be processed shortly.","message_id":"iSms-XHYBk"}
echo "OTP sent (if phone number is valid). Please check your messages.<br>";
echo "<a href='verify_otp.php?phone=" . urlencode($phone_norm) . "'>Enter OTP</a>";

header("location: verify_otp.php?phone=" . urlencode($phone_norm));
?>