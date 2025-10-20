<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
    <body>
     <div class="log">
         <form action="submit" method="$_POST">
                <h1>Login</h1>
         <div class="box">
                 <label for="username">Username</label>
                 <input type="text" id="username" class="form-control rounded-pill" placeholder="Enter Username">
         </div>
          <div class="box">
                <label for="password" id="password">Password</label>
                 <input type="text" id="password" class="form-control rounded-pill"placeholder="Enter Password">
          </div>
           <div class="button">
                <button type="button" onclick="window.location.href='register.php'" class="btn btn-success rounded-pill d-grid gap-2 col-6 mx-auto">Login</button>
           </div>
          <div class="register_link">
            <p class="text-center">Don't have an account? <a href="register.php">Register here</a></p>
          </div>
         </form>
     </div>
</body>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</html>