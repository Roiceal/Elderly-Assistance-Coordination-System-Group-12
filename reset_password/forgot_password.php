<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - OTP</title>
    <!-- Bootstrap CSS -->
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
        .card-body {
            padding: 2rem;
        }
    </style>
</head>
<body>

<div class="container h-100 d-flex justify-content-center align-items-center">
    <div class="card w-100" style="max-width: 400px;">
        <div class="card-body text-center">
            <h3 class="card-title mb-4">Forgot Password</h3>
            <p class="text-muted mb-4">Enter your phone number to receive an OTP.</p>
            <form method="POST" action="send_otp.php">
                <div class="mb-3">
                    <input type="text" name="phone" class="form-control form-control-lg" placeholder="Phone number" required>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100">Send OTP</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS (optional, for interactivity) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
