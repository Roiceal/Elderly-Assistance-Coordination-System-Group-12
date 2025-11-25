<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_SESSION['phone'])) {
  header("location:dashboard_elders.php");
  exit();
}else if (isset($_SESSION['volunteer_id']) && isset($_SESSION['volunteer_username']) && isset($_SESSION['volunteer_phone'])) {
  header("location:volunteer/volunteer_dashboard.php");
  exit();
}else if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_username'])) {
  header("location:admin_page/admin.php");
  exit();
}

?>
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
      font-family: 'Poppins', sans-serif;
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
      color: #1f4f3c ;
      font-weight: bold;
    }

    .link {
      text-decoration: none;
    }

    .link:hover {
      text-decoration: underline;
    }

    h2 {
      color: #1f4f3c ;
      font-weight: bold;
    }

    .btn{
      background-color: #2a7f62 ;
      border: none;
    }
    .btn:hover{
      background-color: #1f4f3c ;
    }

    a{
      text-decoration: none;
      color: #2a7f62 ;
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

              <form action="login_process.php" method="post">
                <div class="mb-3">
                  <input type="text" class="form-control" id="username" name="username" placeholder="Enter your Username">
                </div>

                <div class="mb-3">
                  <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
                </div>

                <div class="mb-2">
                  <select class="form-select" id="role" name="role" required>
                    <option value="" selected disabled>Select Role</option>
                    <option value="Elder">Elder</option>
                    <option value="Volunteer">Volunteer</option>
                    <option value="Admin">Admin</option>
                  </select>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                  </div>
                  <a href="#" >Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3 ">Login</button>


                <p class="text-center mb-0">
                  Don't have an account?
                  <a href="index.php" class="">Register here</a>
                  <br>or<br>
                  <a href="volunteer/volunteer_register.php" class="">Register as a volunteer</a>
                </p>
              </form>
            </div>
          </div> <!-- row -->
        </div> <!-- card -->
      </div>
    </div>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      if (!window.localStorage.getItem("page_refreshed_once")) {
        window.localStorage.setItem("page_refreshed_once", "true");
        window.location.reload();
      }
    });
  </script>



</body>

</html>