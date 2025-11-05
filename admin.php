<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Elderly Assistance Dashboard</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      background-color: #f8f9fa;
      font-family: Arial, sans-serif;
    }

    /* Navbar */
    .navbar {
      background-color: #343a40; /* dark gray */
    }

    .navbar-brand {
      color: white;
      font-weight: bold;
    }

    .navbar-brand:hover {
      color: #dcdcdc;
    }

    /* Cards for totals */
    .info-card {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
      text-align: center;
    }

    .info-card h5 {
      color: #6c757d;
      font-size: 1rem;
      margin-bottom: 8px;
    }

    .info-card h2 {
      color: #0d6efd;
      font-weight: bold;
    }

    /* Table styling */
    .table {
      background-color: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .table thead {
      background-color: #f1f3f5;
    }

    .table th {
      font-weight: 600;
    }

    /* Logout button */
    .btn-logout {
      background-color: #6c757d;
      color: white;
      border: none;
      transition: 0.3s;
    }

    .btn-logout:hover {
      background-color: #5a6268;
    }

    @media (max-width: 768px) {
      .info-card {
        margin-bottom: 1rem;
      }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Elderly Assistance</a>
      <div class="d-flex">
        <button class="btn btn-logout">Logout</button>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container my-5">

    <!-- Row of cards -->
    <div class="row mb-4">
      <div class="col-md-6 mb-3 mb-md-0">
        <div class="info-card">
          <h5>Total Elderlies</h5>
          <h2>120</h2>
        </div>
      </div>
      <div class="col-md-6">
        <div class="info-card">
          <h5>Total Volunteers</h5>
          <h2>45</h2>
        </div>
      </div>
    </div>

    <!-- Attendance Table -->
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Elderly</th>
            <th>Age</th>
            <th>Address</th>
            <th>Check In</th>
            <th>Check Out</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Erll Jhanwin</td>
            <td>62</td>
            <td>Balintawak Lipa City</td>
            <td>7:00 am</td>
            <td>10:00 am</td>
          </tr>
          <tr>
            <td>2</td>
            <td>Roice Panes</td>
            <td>60</td>
            <td>Balintawak Lipa City</td>
            <td>7:08 am</td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
