<?php
session_start();
$config = require __DIR__ . '/config.php';

if (empty($_SESSION['reset_phone'])) die("Unauthorized access.");

$phone = $_SESSION['reset_phone'];
$error = '';

$pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp_input = trim($_POST['otp'] ?? '');
    if (!$otp_input) $error = "Enter OTP";
    else {
        $stmt = $pdo->prepare("SELECT * FROM otp_codes WHERE phone = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$phone]);
        $otp_row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$otp_row) $error = "No OTP found";
        elseif (new DateTime() > new DateTime($otp_row['expires_at'])) $error = "OTP expired";
        elseif (!password_verify($otp_input, $otp_row['otp_hash'])) $error = "Invalid OTP";
        else {
            $_SESSION['reset_verified'] = true;
            header("Location: reset_password.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 1.5rem;
            margin: 0 5px;
        }
    </style>
</head>
<body>

<div class="container h-100 d-flex justify-content-center align-items-center">
    <div class="card w-100" style="max-width: 400px;">
        <div class="card-body text-center">
            <h3 class="card-title mb-4">Verify OTP</h3>
            <p class="text-muted mb-4">Enter the 6-digit code sent to <?= htmlspecialchars($phone) ?>.</p>

            <form method="POST" id="otpForm">
                <div class="d-flex justify-content-center mb-3">
                    <?php for ($i=0; $i<6; $i++): ?>
                        <input type="text" maxlength="1" name="otp_digit[]" class="form-control otp-input" required>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="otp" id="otpFull">
                <button type="submit" class="btn btn-primary btn-lg w-100">Verify</button>
                <?php if($error) echo "<p class='text-danger mt-2'>$error</p>"; ?>
            </form>
        </div>
    </div>
</div>

<script>
    const inputs = document.querySelectorAll('.otp-input');
    inputs.forEach((input, idx) => {
        input.addEventListener('input', (e) => {
            if (input.value.length === 1 && idx < inputs.length - 1) {
                inputs[idx + 1].focus();
            }
        });
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && idx > 0) {
                inputs[idx - 1].focus();
            }
        });
    });

    document.getElementById('otpForm').addEventListener('submit', function(e) {
        const otp = Array.from(inputs).map(input => input.value).join('');
        document.getElementById('otpFull').value = otp;
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
