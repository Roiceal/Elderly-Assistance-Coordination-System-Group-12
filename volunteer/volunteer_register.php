<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Add jQuery -->

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    h2 {
      color: #1f4f3c;
      font-weight: bold;
    }

    .title {
      color: #1f4f3c;
      font-weight: bold;
    }

    a {
      text-decoration: none;
      color: #2a7f62 ;
    }

    /* Optional: custom hover and focus effects */
    input[type="file"]:hover {
      border-color: #2a7f62;
      box-shadow: 0 0 5px rgba(13, 110, 253, 0.5);
    }

    input[type="file"]:focus {
      border-color: #2a7f62;
      box-shadow: 0 0 8px rgba(13, 110, 253, 0.7);
      outline: none;
    }

    .form-text {
      color: #6c757d;
    }

    /* Change input text color when it has a value */
    input:valid {
      color: #2a7f62;
      /* Example: primary blue */
      font-weight: 500;
    }

    /* For select elements */
    select.filled {
      color: #2a7f62;
      font-weight: 500;
    }

    .btn{
      background-color: #2a7f62;
      border: none;
    }

    .btn:hover {
      background-color: #1f4f3c;
    }
  </style>
</head>

<body>

  <div class="container">
    <div class="row justify-content-center align-items-center">
      <div class="col-lg-8 col-md-10">
        <div class="card p-1 p-md-5 bg-white">
          <div class="row g-1 align-items-center">

            <!-- Left side: Logo and text -->
            <div class="col-md-6 text-center text-md-start">
              <img src="../logo.png" alt="Eldercare Logo" class="logo img-fluid">
              <h1 class="title h3 mt-2">ELDERCARE CONNECT</h1>
              <p class="text-muted">Assistance Coordination System</p>
            </div>

            <!-- Right side: Form -->
            <div class="col-md-6">
              <h2 class="text-center mb-1">Register as a volunteer</h2>

              <form action="register_process.php" method="post" enctype="multipart/form-data">
                <div class="mb-3 mt-4">
                  <input type="text" class="form-control" id="fname" name="fname" placeholder="First name" required>
                </div>

                <div class="mb-3">
                  <input type="text" class="form-control" id="lname" name="lname" placeholder="Last name" required>
                </div>

                <div class="mb-3">
                  <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number" required>
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

                <!-- <div class="mb-1">
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
                  <div id="passwordHelp" class="form-text mb-2"></div>
                </div>



                <button type="submit" class="btn btn-primary w-100">Register</button>
              </form>
              <br>
              <p>Already have an account?<a href="../login.php"> Login</a></p>
            </div>

          </div> <!-- row -->
        </div> <!-- card -->
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      // Change select color when a value is selected
      $('select').on('change', function() {
        if ($(this).val()) {
          $(this).addClass('filled');
        } else {
          $(this).removeClass('filled');
        }
      });

      // Trigger on page load in case fields are pre-filled
      $('select').each(function() {
        if ($(this).val()) $(this).addClass('filled');
      });
    });

    $(document).ready(function() {
      // Form submit event
      $('form').on('submit', function(e) {
        let valid = true;
        let password = $('#exampleInputPassword1').val();

        // Password strength regex: min 8 chars, 1 uppercase, 1 lowercase, 1 number
        let strongPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

        if (!strongPassword.test(password)) {
          valid = false;
          alert('Password must be at least 8 characters long and include uppercase, lowercase, and a number.');
          $('#exampleInputPassword1').focus();
        }

        // Additional validations (optional)
        $('input[required], select[required]').each(function() {
          if ($(this).val() === '') {
            valid = false;
            $(this).focus();
            alert('Please fill out all required fields.');
            return false; // break loop
          }
        });

        // Prevent form submission if validation fails
        if (!valid) {
          e.preventDefault();
        }
      });

      // Optional: real-time password strength feedback
      $('#exampleInputPassword1').on('input', function() {
        let pwd = $(this).val();
        let strengthText = '';
        if (pwd.length < 8) {
          strengthText = 'Too short';
        } else if (!/[A-Z]/.test(pwd)) {
          strengthText = 'Add uppercase letter';
        } else if (!/[a-z]/.test(pwd)) {
          strengthText = 'Add lowercase letter';
        } else if (!/\d/.test(pwd)) {
          strengthText = 'Add a number';
        } else {
          strengthText = 'Strong password';
        }
        $('#passwordHelp').text(strengthText);
      });
    });
  </script>

</body>

</html>