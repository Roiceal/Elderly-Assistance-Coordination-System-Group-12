<?php
session_start();
// Database connection
$host = 'localhost';
$db   = 'location';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// $host = 'sql102.infinityfree.com';
// $db   = 'if0_40435320_location';
// $user = 'if0_40435320';
// $pass = 'SyJVNkJSVoj8';
// $charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch all locations
$stmt = $pdo->query("SELECT * FROM user_locations ORDER BY created_at DESC");
$locations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #fff;
        }

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

        #content {
            margin-left: 250px;
            transition: margin-left 0.3s;
            padding: 20px;
        }

        #content.expanded {
            margin-left: 70px;
        }

        .card {
            border-radius: 12px;
            background-color: #1e1e1e;
            color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .table-dark th,
        .table-dark td {
            color: #fff;
        }

        .table-dark thead {
            background-color: #2c2c2c;
        }

        #topbar {
            background-color: #1c1c1c;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #333;
        }

        @media(max-width: 768px) {

            #sidebar,
            #content {
                margin-left: 0 !important;
                width: 100%;
            }
        }

        #map {
            flex: 1;
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            margin-top: 15px;
            height: 500px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #0d6efd;
            font-weight: bold;
        }

        tr.clickable:hover {
            cursor: pointer;
            background-color: #f0f8ff;
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
            <li class="nav-item"><a href="admin.php" class="nav-link"><i class="bi bi-speedometer2"></i><span class="text">Dashboard</span></a></li>
            <li class="nav-item"><a href="userslist.php" class="nav-link"><i class="bi bi-people-fill"></i><span class="text">Users</span></a></li>
            <li class="nav-item"><a href="map/location_map.php" class="nav-link"><i class="bi bi-geo-alt-fill"></i><span class="text">Locate Elder</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-list-check"></i><span class="text">Volunteer Assignment</span></a></li>
            <li class="nav-item"><a href="make_announcement.php" class="nav-link"><i class="bi bi-megaphone-fill"></i><span class="text">Announcement</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-gear-fill"></i><span class="text">Settings</span></a></li>
            <li class="nav-item"><a href="../rfid" class="nav-link"><i class="bi bi-person-badge-fill"></i><span class="text">Attendance</span></a></li>
            <li class="nav-item"><a href="../logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a></li>
        </ul>
    </div>

    <!-- Content -->
    <div id="content">
        <div id="topbar">
            <h4>Elders Locations</h4>
            <div>
                <i class="bi bi-person-circle fs-4"></i> Admin
            </div>
        </div>

        <div id="map"></div>

        <div class="container mt-2">
            <h4 class="mb-1">Saved Locations</h4>
            <table id="locationsTable"
                class="table table-dark table-striped table-bordered dt-responsive nowrap"
                style="width:100%">

                <thead class="text-center">
                    <tr>
                        <th>#</th>
                        <th>IP Address</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Accuracy (m)</th>
                        <th>Address</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($locations)): ?>
                        <?php foreach ($locations as $loc): ?>
                            <tr class="clickable"
                                data-id="<?= $loc['id'] ?>"
                                data-lat="<?= htmlspecialchars($loc['latitude']) ?>"
                                data-lng="<?= htmlspecialchars($loc['longitude']) ?>"
                                data-address="<?= htmlspecialchars($loc['address'] ?: '', ENT_QUOTES) ?>">

                                <td><?= htmlspecialchars($loc['id']) ?></td>
                                <td><?= htmlspecialchars($loc['ip_address']) ?></td>
                                <td><?= htmlspecialchars($loc['latitude']) ?></td>
                                <td><?= htmlspecialchars($loc['longitude']) ?></td>
                                <td><?= htmlspecialchars($loc['accuracy']) ?></td>
                                <td class="address-cell"><?= htmlspecialchars($loc['address'] ?: 'Fetching...') ?></td>
                                <td><?= htmlspecialchars($loc['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- MUST MATCH 7 COLUMNS OR DATATABLE ERROR APPEARS -->
                        <tr>
                            <td>–</td>
                            <td>–</td>
                            <td>–</td>
                            <td>–</td>
                            <td>–</td>
                            <td>No locations saved yet</td>
                            <td>–</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>




        <!-- JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

        <script>
            $(document).ready(function() {
                $('#locationsTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    order: [
                        [0, 'desc']
                    ], // sort by ID descending
                    language: {
                        search: "Search location:"
                    }
                });
            });


            // Sidebar toggle
            document.getElementById("toggleSidebar").addEventListener("click", () => {
                const sidebar = document.getElementById("sidebar");
                const content = document.getElementById("content");
                sidebar.classList.toggle("collapsed");
                content.classList.toggle("expanded");
            });

            // Initialize DataTable
            $(document).ready(function() {
                $('#requestsTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50],
                    order: [
                        [0, 'desc']
                    ],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search requests...",
                        paginate: {
                            previous: "<",
                            next: ">"
                        }
                    }
                });
            });

            // Initialize Leaflet map
            // Initialize map
            var map = L.map('map').setView([13.7565, 121.0583], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Add markers
            var markers = [];
            document.querySelectorAll('tr.clickable').forEach(row => {
                const lat = parseFloat(row.dataset.lat);
                const lng = parseFloat(row.dataset.lng);
                const address = row.dataset.address || '';
                const marker = L.marker([lat, lng]).addTo(map).bindPopup(`Address: ${address}<br>Lat: ${lat}, Lng: ${lng}`);
                markers.push({
                    marker,
                    row
                });
            });

            // Click table row -> move map
            document.querySelectorAll('tr.clickable').forEach(row => {
                row.addEventListener('click', () => {
                    const lat = parseFloat(row.dataset.lat);
                    const lng = parseFloat(row.dataset.lng);
                    map.setView([lat, lng], 16);
                    const m = markers.find(m => m.row === row);
                    if (m) m.marker.openPopup();
                });
            });

            // AJAX to update missing addresses
            document.querySelectorAll('tr.clickable').forEach(row => {
                const addrCell = row.querySelector('.address-cell');
                if (!row.dataset.address) {
                    const id = row.dataset.id;
                    const lat = row.dataset.lat;
                    const lng = row.dataset.lng;
                    fetch(`update_address.php?id=${id}&lat=${lat}&lng=${lng}`)
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                addrCell.textContent = data.address;
                                row.dataset.address = data.address;
                            } else {
                                addrCell.textContent = 'Address not found';
                            }
                        });
                }
            });
        </script>
</body>

</html>