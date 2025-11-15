<?php
ob_start(); // Prevent header issues
session_start();
include_once 'database/db_connection.php';

// Sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

$error = "";

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

            // Password match (supports hashed or plaintext)
            if ($password === $user['password'] || password_verify($password, $user['password'])) {

                // Start sessions
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect by role (INDEX IS OUTSIDE FOLDERS)
                if ($user['role'] === 'admin') {
                    header("Location: admin/homepage_admin.php");
                } else if ($user['role'] === 'volunteer') {
                    header("Location: volunteer/volunteer_homepage.php");
                } else if ($user['role'] === 'user') {
                    header("Location: elderly/home.php");
                } else {
                    header("Location: elderly/user_profile.php");
                }
                exit;

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

ob_end_flush(); // Send output after processing headers
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<p>passwords</p> 
<p>Admin/ username: admin Password: E@cS2025!SYST3M! </p>
 <p>User/ username: Roice Panes Password: !roice@</p>
 <p>Volunteer/ username: John Doe password: !johndoe!</p>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
      <form action="index.php" method="POST" class="p-4 shadow rounded bg-white">
        <h1 class="text-center mb-4">Login</h1>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="mb-3">
          <label class="form-label fw-bold">Username</label>
          <input type="text" name="username" class="form-control rounded-pill" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Password</label>
          <input type="password" name="password" class="form-control rounded-pill" required>
        </div>

        <div class="text-center mt-4">
          <button type="submit" class="btn btn-success rounded-pill px-4">Login</button>
        </div>

        <div class="text-center mt-3">
          <p>Don’t have an account? <a href="login/register.php">Register here</a></p>
        </div>

      </form>
    </div>
  </div>
</div>

</body>
</html>
