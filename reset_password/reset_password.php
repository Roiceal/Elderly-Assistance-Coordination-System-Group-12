<?php
session_start();
$config = require __DIR__ . '/config.php';

if (empty($_SESSION['reset_phone']) || empty($_SESSION['reset_verified'])) {
    die("Unauthorized access.");
}

$phone = $_SESSION['reset_phone'];
$error = '';

$pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (!$password || !$confirm) $error = "Enter password and confirm it.";
    elseif ($password !== $confirm) $error = "Passwords do not match.";
    else {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE phone = ?");
        $stmt->execute([$password_hash, $phone]);

        // Clear session
        unset($_SESSION['reset_phone'], $_SESSION['reset_verified']);

        // After updating password and clearing session
        unset($_SESSION['reset_phone'], $_SESSION['reset_verified']);

        echo <<<HTML
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    <title>Password Reset Success</title>
                </head>
                <body class="bg-light">
                    <div class="d-flex justify-content-center align-items-center vh-100">
                        <div class="card shadow p-4 text-center" style="max-width: 400px; width: 100%;">
                            <h3 class="text-success mb-3">Password Reset Successful!</h3>
                            <p class="mb-4">Your password has been updated. You can now log in with your new password.</p>
                            <a href="../login.php" class="btn btn-success btn-lg">Login</a>
                        </div>
                    </div>
                </body>
                </html>
                HTML;
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        .input-group-text {
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-light">



    <div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <h3 class="text-center mb-4">Reset Password</h3>
            <p class="text-center text-muted">Reset password for <strong><?= htmlspecialchars($phone) ?></strong></p>
            <form method="POST">
                <!-- New Password -->
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" id="password" required>
                        <span class="input-group-text" onclick="togglePassword('password', this)">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" name="confirm" class="form-control" id="confirm" required>
                        <span class="input-group-text" onclick="togglePassword('confirm', this)">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>

                <?php if ($error) echo "<p class='text-danger'>$error</p>"; ?>
                <button type="submit" class="btn btn-primary w-100">Reset Password</button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(fieldId, icon) {
            const input = document.getElementById(fieldId);
            const iTag = icon.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                iTag.classList.remove('fa-eye');
                iTag.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                iTag.classList.remove('fa-eye-slash');
                iTag.classList.add('fa-eye');
            }
        }
    </script>

</body>

</html>