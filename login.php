<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      background-color: #f8f9fa;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .logo {
      width: 100px;
      margin-bottom: 15px;
    }

    .title {
      color: #0d6efd;
      font-weight: bold;
    }

    .link {
      text-decoration: none;
    }

    .link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="row justify-content-center align-items-center">
      <div class="col-lg-8 col-md-10">
        <div class="card p-4 p-md-5 bg-white">
          <div class="row g-4 align-items-center">

            <!-- Left Side: Logo + Text -->
            <div class="col-md-6 text-center text-md-start">
              <img src="logo.png" alt="Eldercare Logo" class="logo img-fluid">
              <h1 class="title h3 mt-2">ELDERCARE CONNECT</h1>
              <p class="text-muted">Assistance Coordination System</p>
            </div>

            <!-- Right Side: Login Form -->
            <div class="col-md-6">
              <h2 class="text-center mb-4">Login to your account</h2>

              <form>
                <div class="mb-3">
                  <label for="pnumber" class="form-label">Phone number</label>
                  <input type="text" class="form-control" id="pnumber" placeholder="Enter your phone number">
                </div>

                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control" id="password" placeholder="Enter your password">
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                  </div>
                  <a href="#" class="text-primary link">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>

                <p class="text-center mb-0">
                  Don't have an account? 
                  <a href="index.php" class="text-primary link">Register here</a>
                </p>
              </form>
            </div>

          </div> <!-- row -->
        </div> <!-- card -->
      </div>
    </div>
  </div>

</body>
</html>
