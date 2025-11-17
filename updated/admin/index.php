<?php
ob_start();
session_start();
include_once '../database/db_connection.php';

function sanitize_input($data) {
    return htmlspecialchars(trim(stripslashes($data)), ENT_QUOTES, 'UTF-8');
}

$error = "";

// Success flag and redirect URL variables for SweetAlert
$login_success = false;
$redirect_url = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->getConnection();

    $username = sanitize_input($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please fill in both fields.";
    } else {
        $stmt = $conn->prepare("SELECT user_id, username, password, role FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if ($password === $user['password'] || password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                $login_success = true;
                if ($user['role'] === 'admin') {
                    $redirect_url = 'homepage_admin.php';
                } else if ($user['role'] === 'volunteer') {
                    $redirect_url = 'volunteer/volunteer_homepage.php';
                } else if ($user['role'] === 'user') {
                    $redirect_url = 'elderly/home.php';
                } else {
                    $redirect_url = 'elderly/user_profile.php';
                }
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found.";
        }

        $stmt->close();
    }
    $conn->close();
}

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="login-wrapper">
    <form action="index.php" method="POST" class="login-card">
        <div class="login-logo-wrapper">
            <img src="../images/logo.png" alt="Logo" class="login-logo">
        </div>
        <h1 class="text-center mb-4">Login</h1>
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <div class="mb-3">
          <label class="form-label fw-bold">Username</label>
          <input type="text" name="username" class="form-control rounded-pill" placeholder="Enter Username" required>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold">Password</label>
          <input type="password" name="password" class="form-control rounded-pill" placeholder="Enter Password" required>
        </div>
        <button type="submit" class="btn btn-success w-100 rounded-pill mt-3">Login</button>
        <p class="text-center mt-3">Don’t have an account? <a href="../login/register.php">Register here</a></p>
    </form>
</div>

<!-- this is for the sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
<?php if (!empty($login_success) && !empty($redirect_url)): ?>
    // SweetAlert shown after successful login, then redirect to correct page
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Login Successful!',
            text: 'Welcome back!',
            icon: 'success', 
            showConfirmButton: false,
            timer: 1800
        }).then(() => {
            window.location.href = "<?= $redirect_url ?>";
        });
        // Fallback automatic redirect after timer in case user closes/modal dismiss
        setTimeout(function() {
          window.location.href = "<?= $redirect_url ?>";
        }, 2000);
    } else {
        alert('SweetAlert2 not loaded. Login successful! Redirecting...');
        setTimeout(() => { window.location.href = "<?= $redirect_url ?>"; }, 1500);
    }
<?php endif; ?>
</script>
</body>
</html>