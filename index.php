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

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- jQuery Validation -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      padding-top: 40px;
      padding-bottom: 40px;
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

    .title,
    h2 {
      color: #1f4f3c;
      font-weight: bold;
    }

    a {
      text-decoration: none;
      color: #2a7f62;
    }

    .btn {
      background-color: #2a7f62;
      border: none;
    }

    .btn:hover {
      background-color: #1f4f3c;
    }

    #passwordCriteria p {
      margin: 0;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .valid {
      color: green;
      font-weight: bold;
    }

    .invalid {
      color: red;
      font-weight: bold;
    }

    .form-warning {
      color: red;
      font-size: 14px;
      margin-top: 5px;
    }
  </style>
</head>

<body>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-md-10">
        <div class="card p-4 p-md-5 bg-white">
          <div class="row g-4 align-items-center">

            <!-- Left Side -->
            <div class="col-md-6 text-center text-md-start">
              <img src="logo.png" alt="Eldercare Logo" class="logo img-fluid">
              <h1 class="title h3 mt-2">ELDERCARE CONNECT</h1>
              <p class="text-muted">Assistance Coordination System</p>
            </div>

            <!-- Registration Form -->
            <div class="col-md-6">
              <h2 class="text-center mb-4">Register your account</h2>

              <form id="registerForm" action="insert_user.php" enctype="multipart/form-data" method="post">

                <div class="mb-3">
                  <input type="text" class="form-control" name="fname" placeholder="First name" required>
                </div>

                <div class="mb-3">
                  <input type="text" class="form-control" name="lname" placeholder="Last name" required>
                </div>

                <div class="mb-3">
                  <input type="text" class="form-control" name="phone" placeholder="Phone number" required>
                </div>

                <div class="row">
                  <div class="col-sm-6">
                    <div class="mb-3">
                      <input type="number" class="form-control" name="age" placeholder="Age" min="1" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="mb-3">
                      <select class="form-select" name="gender" required>
                        <option value="" selected disabled>Select gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Image -->
                <div class="mb-3">
                  <label class="form-label">Upload your image</label>
                  <input class="form-control form-control-lg" type="file" name="image" required>
                  <div class="form-text">Accepted formats: JPG, PNG. Max size: 2MB.</div>
                </div>

                <div class="mb-3">
                  <input type="text" class="form-control" name="uname" placeholder="Username" required>
                </div>

                <!-- Password -->
                <div class="mb-3">
                  <input type="password" id="password" class="form-control" name="password" placeholder="Your Password" required>
                </div>

                <!-- Inline Form Warning -->
                <div id="formWarning" class="form-warning"></div>
                <br>

                <!-- Password Criteria -->
                <div id="passwordCriteria" class="mb-2">
                  <p id="length" class="invalid"><i class="bi bi-x-circle-fill"></i> Minimum 8 characters</p>
                  <p id="uppercase" class="invalid"><i class="bi bi-x-circle-fill"></i> At least 1 uppercase letter</p>
                  <p id="number" class="invalid"><i class="bi bi-x-circle-fill"></i> At least 1 number</p>
                  <p id="special" class="invalid"><i class="bi bi-x-circle-fill"></i> At least 1 special character</p>
                </div>

                

                <button type="submit" class="btn btn-primary w-100">Register</button>
              </form>

              <br>
              <div class="text-center">
                <p>Already have an account? <a href="login.php">Login</a></p>
              </div>

            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function () {

      // Password criteria real-time check
      $("#password").on("keyup", function () {
        let pass = $(this).val();

        function setCriteria(selector, condition, text) {
          $(selector)
            .toggleClass("valid", condition)
            .toggleClass("invalid", !condition)
            .html(
              (condition
                ? '<i class="bi bi-check-circle-fill"></i> '
                : '<i class="bi bi-x-circle-fill"></i> '
              ) + text
            );
        }

        setCriteria("#length", pass.length >= 8, "Minimum 8 characters");
        setCriteria("#uppercase", /[A-Z]/.test(pass), "At least 1 uppercase letter");
        setCriteria("#number", /\d/.test(pass), "At least 1 number");
        setCriteria("#special", /[^A-Za-z0-9]/.test(pass), "At least 1 special character");
      });

      // Form submission validation with inline warnings
      $("#registerForm").on("submit", function (e) {
        let pass = $("#password").val();
        let strongPassword = /^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;
        let valid = true;

        // Clear previous warning
        $("#formWarning").text("");

        // Check required fields
        $("#registerForm [required]").each(function () {
          if ($(this).val().trim() === "") {
            valid = false;
            $("#formWarning").text("Please fill out all required fields.");
            return false; // break loop
          }
        });

        // Check password strength
        if (valid && !strongPassword.test(pass)) {
          valid = false;
          $("#formWarning").text("Password must have at least 8 characters, 1 uppercase, 1 number, and 1 special character.");
        }

        if (!valid) e.preventDefault();
      });

    });
  </script>

</body>

</html>
