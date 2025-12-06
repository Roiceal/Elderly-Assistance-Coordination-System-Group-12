<!doctype html>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RFID Attendance</title>

  <!-- Bootstrap CSS -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background: #f4f6f9;
      font-family: 'Poppins', sans-serif;
      color: #343a40;
    }

    /* Sidebar */
    #sidebar {
      width: 250px;
      background-color: #1c1c1c;
      position: fixed;
      height: 100vh;
      padding-top: 20px;
      transition: width 0.3s;
    }

    #sidebar.collapsed {
      width: 70px;
    }

    #sidebar .nav-link {
      color: #fff;
      padding: 12px 20px;
      display: flex;
      align-items: center;
      gap: 15px;
      border-radius: 8px;
      transition: all 0.2s;
    }

    #sidebar .nav-link:hover {
      background-color: #333;
      padding-left: 25px;
    }

    #sidebar.collapsed .text {
      display: none;
    }

    /* Content */
    #content {
      margin-left: 250px;
      transition: margin-left 0.3s;
      padding: 20px;
    }

    #content.expanded {
      margin-left: 70px;
    }

    /* RFID Card */
    .main-container {
      display: flex;
      gap: 30px;
      flex-wrap: wrap;
      margin-top: 40px;
      justify-content: flex-start;
      align-items: flex-start;
    }

    .rfid-card {
      width: 320px;
      background: #1c1c1c;
      color: white;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      padding: 0;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .rfid-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
    }

    .rfid-card img {
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
      width: 100%;
      height: auto;
      object-fit: contain;
      margin-top: 15px;
      display: block;
    }

    .rfid-card .card-body {
      padding: 20px;
    }

    .rfid-card input.rfid {
      border-radius: 12px;
      padding: 10px;
      border: none;
      box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.1);
      margin-bottom: 15px;
    }

    .rfid-card p {
      margin: 5px 0;
      font-weight: 500;
    }

    /* Attendance Log */
    .attendance-log {
      flex: 1;
      background: white;
      border-radius: 20px;
      padding: 20px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      min-width: 500px;
    }

    .attendance-log .header-bar {
      border-bottom: 2px solid #e0e0e0;
      padding-bottom: 10px;
      margin-bottom: 15px;
    }

    .attendance-log h4 {
      font-weight: 700;
      color: #343a40;
    }

    .table {
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .table th {
      background-color: #1c1c1c;
      color: white;
      font-weight: 600;
      text-align: center;
    }

    .table td {
      vertical-align: middle;
      text-align: center;
      font-weight: 500;
      color: #495057;
      word-wrap: break-word;
    }

    .table-striped>tbody>tr:nth-of-type(odd) {
      background-color: #f8f9fa;
    }

    .table-responsive {
      max-height: 600px;
      overflow-y: auto;
    }

    @media(max-width: 992px) {
      .main-container {
        flex-direction: column;
        align-items: center;
      }

      .rfid-card,
      .attendance-log {
        width: 100%;
      }
    }
  </style>

</head>

<body>

  <!-- Sidebar -->

  <div id="sidebar">
    <button class="btn btn-sm btn-outline-light mb-3 ms-3" id="toggleSidebar">
      <i class="bi bi-list"></i>
    </button>
    <ul class="nav flex-column mt-3">
      <li class="nav-item"><a href="../admin_page/admin.php" class="nav-link"><i class="bi bi-speedometer2"></i><span class="text">Dashboard</span></a></li>
      <li class="nav-item"><a href="../admin_page/userslist.php" class="nav-link"><i class="bi bi-people"></i><span class="text">Users</span></a></li>
      <li class="nav-item"><a href="../admin_page/map/location_map.php" class="nav-link"><i class="bi bi-bell"></i><span class="text">Locate Elder</span></a></li>
      <li class="nav-item"><a href="../admin_page/assign_volunteer.php" class="nav-link"><i class="bi bi-list-check"></i><span class="text">Volunteer Assignment</span></a></li>
      <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-calendar-event"></i><span class="text">Events</span></a></li>
      <li class="nav-item"><a href="../admin_page/make_announcement.php" class="nav-link"><i class="bi bi-graph-up"></i><span class="text">Annoucement</span></a></li>
      <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-gear"></i><span class="text">Settings</span></a></li>
      <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-card-checklist"></i><span class="text">Attendance</span></a></li>
      <li class="nav-item"><a href="../logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a></li>
    </ul>
  </div>

  <!-- Content -->

  <div id="content">
    <div class="main-container">


      <!-- RFID Card -->
      <div class="card rfid-card">
        <img class="card-img-top" alt="RFID Image" id="img" src="images/image.png">
        <div class="card-body">
          <input type="text" id="rfidcard" class="form-control rfid" placeholder="Tap RFID card here">
          <p id="name">Name: <span class="fw-bold"></span></p>
          <p id="age">Age: <span class="fw-bold"></span></p>
          <p id="DOB">Date of Birth: <span class="fw-bold"></span></p>
          <p id="card_id">Card ID: <span class="fw-bold"></span></p>
        </div>
      </div>

      <!-- Attendance Log -->
      <div class="attendance-log">
        <div class="header-bar">
          <h4>Attendance</h4>
        </div>
        <div class="table-responsive">
          <table class="table table-striped center">
            <thead>
              <tr>
                <th>Name</th>
                <th>Card ID</th>
                <th>Address</th>
                <th>Time In</th>
                <th>Time Out</th>
              </tr>
            </thead>
            <tbody id="attendanceTable">
              <!-- Attendance data dynamically loaded here -->
            </tbody>
          </table>
        </div>
      </div>

    </div>

  </div>

  <!-- JS -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Sidebar toggle
    document.getElementById("toggleSidebar").addEventListener("click", () => {
      const sidebar = document.getElementById("sidebar");
      const content = document.getElementById("content");
      sidebar.classList.toggle("collapsed");
      content.classList.toggle("expanded");
    });
  </script>

  <script src="script.js"></script>

</body>

</html>