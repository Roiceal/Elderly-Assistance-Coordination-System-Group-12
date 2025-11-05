<?php
// validate_otp.php
$config = require __DIR__ . '/config.php';
$phone = trim($_POST['phone'] ?? '');
$otp_submitted = trim($_POST['otp_combined'] ?? $_POST['otp'] ?? '');

if (!$phone || !$otp_submitted) {
    die('Missing phone or otp.');
}

// normalize as earlier
function normalize_phone($p) {
    $p = preg_replace('/\D+/', '', $p);
    if (strlen($p) == 11 && $p[0] === '0') {
        return '63' . substr($p,1);
    }
    return $p;
}
$phone_norm = normalize_phone($phone);

try {
    $pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die('DB connection failed: ' . $e->getMessage());
}

// get latest unused OTP for this phone
$stmt = $pdo->prepare("SELECT id, otp_hash, expires_at, used FROM otp_codes WHERE phone = ? ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$phone_norm]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$message = '';
$success = false;

if (!$row) {
    $message = 'No OTP request found for this phone.';
} elseif ((int)$row['used'] === 1) {
    $message = 'This code has already been used.';
} else {
    $expires_at = new DateTime($row['expires_at']);
    $now = new DateTime();
    if ($now > $expires_at) {
        $message = 'OTP expired. Please request a new one.';
    } elseif (password_verify($otp_submitted, $row['otp_hash'])) {
        // success: mark used
        $upd = $pdo->prepare("UPDATE otp_codes SET used = 1 WHERE id = ?");
        $upd->execute([$row['id']]);
        $message = 'OTP verified successfully!';
        $success = true;
    } else {
        $message = 'Invalid OTP.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>OTP Verification</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f0f2f5;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .container {
        background: #fff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        text-align: center;
        max-width: 400px;
    }
    h2 {
        margin-bottom: 20px;
        color: #333;
    }
    .message {
        font-size: 18px;
        margin-bottom: 30px;
        padding: 15px;
        border-radius: 8px;
    }
    .success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .btn {
        display: inline-block;
        padding: 12px 25px;
        font-size: 16px;
        color: #fff;
        background-color: #007bff;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .btn:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>
<div class="container">
    <h2>OTP Verification</h2>
    <div class="message <?= $success ? 'success' : 'error' ?>">
        <?= htmlspecialchars($message) ?>
    </div>
    <?php if($success): ?>
        <a href="../login.php" class="btn">Proceed to Login</a>
    <?php else: ?>
        <a href="verify_otp.php?phone=<?= htmlspecialchars($phone) ?>" class="btn">Try Again</a>
    <?php endif; ?>
</div>
</body>
</html>
