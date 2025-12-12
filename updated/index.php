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
<title>Register</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery Validation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #eaf0e1;
}
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    padding: 30px;
}
h2 {
    color: #000;
    font-weight: bold;
}
.btn-register {
    background-color: #c2d69b;
    border: none;
}
.btn-register:hover {
    background-color: #a3be75;
}
.logo {
    height: 250px;
    width: 250px;
}
</style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card col-md-8">
        <div class="row g-4 align-items-stretch">

            <!-- Left Logo/Title -->
            <div class="col-md-5 text-center d-flex flex-column justify-content-center">
                <img src="logo.png" class="img-fluid logo" alt="Logo">
                <h4>Eldery Assistance Coordination System</h4>
            </div>

            <!-- Registration Form -->
            <div class="col-md-7">
                <h2 class="text-center mb-4">Register your account</h2>
                <form action="insert_user.php" method="post" enctype="multipart/form-data" id="registerForm">

                    <!-- Name Fields -->
                    <div class="row mb-3">
                        <div class="col"><input type="text" class="form-control" name="fname" placeholder="First name" required></div>
                        <div class="col"><input type="text" class="form-control" name="mname" placeholder="Middle name"></div>
                        <div class="col"><input type="text" class="form-control" name="lname" placeholder="Last name" required></div>
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                    </div>

                    <!-- Gender -->
                    <label>Gender</label>
                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="Female" required>
                            <label class="form-check-label">Female</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="Male">
                            <label class="form-check-label">Male</label>
                        </div>
                    </div>

                    <!-- Birthday -->
                    <label>Birthday</label>
                    <div class="row mb-3">
                        <div class="col">
                            <select class="form-select" name="month" required>
                                <option value="" selected disabled>Month</option>
                                <?php for($m=1;$m<=12;$m++){ echo "<option>$m</option>"; } ?>
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-select" name="day" required>
                                <option value="" selected disabled>Day</option>
                                <?php for($d=1;$d<=31;$d++){ echo "<option>$d</option>"; } ?>
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-select" name="year" required>
                                <option value="" selected disabled>Year</option>
                                <?php for($y=date("Y")-60; $y>=1900; $y--){ echo "<option>$y</option>"; } ?>
                            </select>
                        </div>
                    </div>

                    <!-- Address -->
                    <label>Address</label>
                    <div class="row mb-3">
                        <div class="col"><select class="form-select" name="city" required><option selected disabled>City</option></select></div>
                        <div class="col"><select class="form-select" name="province" required><option selected disabled>Province</option></select></div>
                        <div class="col"><select class="form-select" name="barangay" required><option selected disabled>Barangay</option></select></div>
                    </div>

                    <!-- Mobile -->
                    <div class="mb-3">
                        <input type="text" class="form-control" name="mobile" placeholder="Mobile number" required>
                    </div>

                    <!-- Password -->
                    <div class="row mb-3">
                        <div class="col"><input type="password" class="form-control" name="password" placeholder="Password" required></div>
                        <div class="col"><input type="password" class="form-control" name="confirm_password" placeholder="Confirm password" required></div>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3">
                        <input type="file" class="form-control" name="image" required>
                    </div>

                    <button type="submit" class="btn btn-register w-100">Register</button>
                    <p class="text-center mt-2">Already have an account? <a href="login.php">Log in</a></p>
                </form>
            </div>

        </div>
    </div>
</div>

</body>
</html>
