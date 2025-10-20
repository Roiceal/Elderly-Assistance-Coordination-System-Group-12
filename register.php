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
    <div class="register">
        <form action="submit" method="$_POST">
            <div class="box">
                <input type="text" class="form-control rounded-pill" placeholder="Enter your Name">
            </div>
            <div class="box">
                <input type="text" class="form-control rounded-pill" placeholder="Enter your Username"> 
            </div>
            <div class="box">
                <input type="text" class="form-control rounded-pill" placeholder="Enter your Phone Number">
            </div>
            <div class="box">
                <input type="text" class="form-control rounded-pill" placeholder="Enter your Password">
            </div>
            <div class="box">
                <input type="text" class="form-control rounded-pill" placeholder="Re-type Password">
            </div>
            <div class="button">
                <button type="button" onclick="window.location.href='verification.php'" class="btn btn-success rounded-pill d-grid gap-2 col-6 mx-auto">verify</button>
            </div>
            </form>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</html>
</html>