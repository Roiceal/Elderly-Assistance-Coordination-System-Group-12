<?php

session_start();

if (isset($_SESSION['user_id'])) {
  header("Location: dashboard_elders.php");
  exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration</title>

  <!-- Bootstrap CSS -->
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
      color: #1f4f3c;
      font-weight: bold;
    }

    h2 {
      color: #1f4f3c;
      font-weight: bold;
    }

    a {
      text-decoration: none;
      color: #2a7f62;
    }

    /* Optional: custom hover and focus effects */
    input[type="file"]:hover {
      border-color: #1f4f3c;
      box-shadow: 0 0 5px #1f4f3c(13, 110, 253, 0.5);
    }

    input[type="file"]:focus {
      border-color: #1f4f3c;
      box-shadow: 0 0 8px #1f4f3c(10, 88, 202, 0.5);
      outline: none;
    }

    .form-text {
      color: black;
    }

    .btn{
      background-color: #2a7f62 ;
      border: none;
    }
    .btn:hover{
      background-color: #1f4f3c ;
    }
  </style>
</head>

<body>

  <div class="container">
    <div class="row justify-content-center align-items-center">
      <div class="col-lg-8 col-md-10">
        <div class="card p-4 p-md-5 bg-white">
          <div class="row g-4 align-items-center">

            <!-- Left side: Logo and text -->
            <div class="col-md-6 text-center text-md-start">
              <img src="logo.png" alt="Eldercare Logo" class="logo img-fluid">
              <h1 class="title h3 mt-2">ELDERCARE CONNECT</h1>
              <p class="text-muted">Assistance Coordination System</p>
            </div>

            <!-- Right side: Form -->
            <div class="col-md-6">
              <h2 class="text-center mb-4">Register your account</h2>

              <form action="insert_user.php" enctype="multipart/form-data" method="post">
                <div class="mb-3">
                  <input type="text" class="form-control" id="fname" name="fname" placeholder="First name" required>
                </div>

                <div class="mb-3">
                  <input type="text" class="form-control" id="lname" name="lname" placeholder="Last name" required>
                </div>

                <div class="mb-3">
                  <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone number" required>
                </div>

                <div class="row">
                  <div class="col-sm-6">
                    <div class="mb-3">
                      <input type="number" class="form-control" id="age" name="age" min="1" placeholder="Age" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="mb-3">
                      <select class="form-select" id="gender" name="gender" required>
                        <option value="" selected disabled>Select gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- <div class="mb-3">
                  <label for="image" class="form-label">Upload your image</label>
                  <input type="file" name="image" required>
                </div> -->

                <div class="mb-3">
                  <label for="image" class="form-label">Upload your image</label>
                  <input class="form-control form-control-lg" type="file" id="image" name="image" required>
                  <div class="form-text">Accepted formats: JPG, PNG. Max size: 2MB.</div>
                </div>

                <div class="mb-3">
                  <input type="text" class="form-control" id="uname" name="uname" placeholder="Username" required>
                </div>

                <div class="mb-3">
                  <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Your Password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Register</button>
              </form>

              <br>
              <div class="text-center">
                <p class="text-center">Already have an account?<a href="login.php" class="link"> Login</a></p>
              </div>

            </div>

          </div> <!-- row -->
        </div> <!-- card -->
      </div>
    </div>
  </div>

</body>

</html>