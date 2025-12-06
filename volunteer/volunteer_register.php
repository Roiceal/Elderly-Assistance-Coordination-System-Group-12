<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Volunteer Registration</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      padding: 40px 0;
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

    h2,
    .title {
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

    select.filled {
      color: #2a7f62;
      font-weight: 500;
    }

    input[type="file"]:hover {
      border-color: #2a7f62;
      box-shadow: 0 0 5px rgba(13, 110, 253, 0.5);
    }

    input[type="file"]:focus {
      border-color: #2a7f62;
      box-shadow: 0 0 8px rgba(13, 110, 253, 0.7);
      outline: none;
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
        <div class="card p-3 p-md-5 bg-white">
          <div class="row g-3 align-items-center">

            <!-- LEFT SIDE -->
            <div class="col-md-6 text-center text-md-start">
              <img src="../logo.png" alt="Eldercare Logo" class="logo img-fluid">
              <h1 class="title h3 mt-2">ELDERCARE CONNECT</h1>
              <p class="text-muted">Assistance Coordination System</p>
            </div>

            <!-- RIGHT SIDE FORM -->
            <div class="col-md-6">
              <h2 class="text-center mb-2">Register as a Volunteer</h2>

              <form id="registerForm" action="register_process.php" method="post" enctype="multipart/form-data">

                <div class="mb-3 mt-3">
                  <input type="text" class="form-control" name="fname" placeholder="First name" required>
                </div>

                <div class="mb-3">
                  <input type="text" class="form-control" name="lname" placeholder="Last name" required>
                </div>

                <div class="mb-3">
                  <input type="text" class="form-control" name="phone" placeholder="Phone Number" required>
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
                        <option value="Other">Other</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- IMAGE -->
                <div class="mb-3">
                  <label class="form-label">Upload your image</label>
                  <input class="form-control form-control-lg" type="file" name="image" required>
                  <div class="form-text">Accepted formats: JPG, PNG. Max 2MB.</div>
                </div>

                <div class="mb-3">
                  <input type="text" class="form-control" name="uname" placeholder="Username" required>
                </div>

                <!-- PASSWORD -->
                <div class="mb-2 position-relative">
                  <input type="password" class="form-control" id="password" name="password" placeholder="Your Password" required>
                  <span class="toggle-password"
                    onclick="togglePassword()"
                    style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer;">
                    <i class="bi bi-eye-fill" id="eyeIcon"></i>
                  </span>
                </div>

                <div id="formWarning" class="form-warning"></div>
                <br>
                <!-- PASSWORD CRITERIA -->
                <div id="passwordCriteria" class="mb-3">
                  <p id="length" class="invalid"><i class="bi bi-x-circle-fill"></i> Minimum 8 characters</p>
                  <p id="uppercase" class="invalid"><i class="bi bi-x-circle-fill"></i> At least 1 uppercase letter</p>
                  <p id="lowercase" class="invalid"><i class="bi bi-x-circle-fill"></i> At least 1 lowercase letter</p>
                  <p id="number" class="invalid"><i class="bi bi-x-circle-fill"></i> At least 1 number</p>
                </div>

                <!-- General form warning -->


                <button type="submit" class="btn btn-primary w-100">Register</button>

              </form>

              <br>
              <p class="text-center">Already have an account? <a href="../login.php">Login</a></p>

            </div> <!-- end of form -->

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- VALIDATION SCRIPT -->
  <script>
    function togglePassword() {
      let passwordField = document.getElementById("password");
      let eyeIcon = document.getElementById("eyeIcon");

      if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.classList.remove("bi-eye-fill");
        eyeIcon.classList.add("bi-eye-slash-fill");
      } else {
        passwordField.type = "password";
        eyeIcon.classList.remove("bi-eye-slash-fill");
        eyeIcon.classList.add("bi-eye-fill");
      }
    }


    $(document).ready(function() {

      // Apply filled class on selects
      $('select').on('change', function() {
        $(this).toggleClass('filled', $(this).val() !== '');
      });

      // Password criteria real-time check
      $("#password").on("keyup", function() {
        let pass = $(this).val();

        function setCriteria(selector, condition, text) {
          $(selector)
            .toggleClass("valid", condition)
            .toggleClass("invalid", !condition)
            .html(
              (condition ?
                '<i class="bi bi-check-circle-fill"></i> ' :
                '<i class="bi bi-x-circle-fill"></i> '
              ) + text
            );
        }

        setCriteria("#length", pass.length >= 8, "Minimum 8 characters");
        setCriteria("#uppercase", /[A-Z]/.test(pass), "At least 1 uppercase letter");
        setCriteria("#lowercase", /[a-z]/.test(pass), "At least 1 lowercase letter");
        setCriteria("#number", /\d/.test(pass), "At least 1 number");
      });

      // Form submit validation without alert
      $("form").on("submit", function(e) {
        let valid = true;
        let pass = $("#password").val();
        let strongPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

        // Clear previous warnings
        $("#formWarning").text("");

        // Required field checking
        $("input[required], select[required]").each(function() {
          if ($(this).val().trim() === "") {
            valid = false;
            $("#formWarning").text("Please fill out all required fields.");
            return false;
          }
        });

        // Password validation
        if (!strongPassword.test(pass)) {
          valid = false;
          $("#formWarning").text("Password must be at least 8 characters, include uppercase, lowercase, and a number.");
        }

        if (!valid) {
          e.preventDefault();
        }
      });

    });
  </script>

</body>

</html>