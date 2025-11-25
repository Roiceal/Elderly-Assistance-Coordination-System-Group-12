<?php
session_start();
include __DIR__ . '/../../db_connect.php';

// Fetch all locations
$stmt = $pdo->query("SELECT * FROM user_locations ORDER BY created_at DESC");
$locations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Elderly Assistance Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
        }

        /* Sidebar */
        #sidebar {
            width: 250px;
            background: black;
            color: white;
            position: fixed;
            top: 0;
            height: 100vh;
            padding-top: 20px;
            transition: width 0.3s;
        }

        #sidebar.collapsed {
            width: 70px;
        }

        #sidebar .nav-link {
            color: white;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        #sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            padding-left: 25px;
        }

        #sidebar.collapsed .text {
            display: none;
        }

        /* Content */
        #content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s;
        }

        #topbar {
            background-color: white;
            color: black;
            padding: 10px 20px;
            display: flex;
            margin-bottom: 20px;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #333;
        }


        /* Map & Table columns */

        /* Table Styling */

        table thead {
            background-color: white;
            color: black;
        }

        table.dataTable tbody tr {
            color: black;
            background: white;
        }

        th {
            background-color: white;
        }

        #map {
            width: 100%;
            height: 600px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .table-container {
            max-height: 600px;
            overflow-y: auto;
        }

        tr.clickable:hover {
            cursor: pointer;
            background-color: #f0f8ff;
        }

        /* Responsive */
        @media (max-width: 991px) {
            #content {
                margin-left: 0;
            }

            #map {
                height: 400px;
                margin-bottom: 20px;
            }

            .table-container {
                max-height: none;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div id="sidebar" class="d-none d-md-block">
        <button class="btn btn-sm btn-outline-light mb-3 ms-3" id="toggleSidebar"><i class="bi bi-list"></i></button>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="admin.php" class="nav-link"><i class="bi bi-speedometer2"></i><span class="text">Dashboard</span></a></li>
            <li class="nav-item"><a href="../userslist.php" class="nav-link"><i class="bi bi-people"></i><span class="text">Users</span></a></li>
            <li class="nav-item"><a href="map/location_map.php" class="nav-link"><i class="bi bi-bell"></i><span class="text">Locate Elder</span></a></li>
             <li class="nav-item"><a href="../assign_volunteer.php" class="nav-link"><i class="bi bi-list-check"></i><span class="text">Volunteer Assignment</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-calendar-event"></i><span class="text">Events</span></a></li>
            <li class="nav-item"><a href="../make_announcement.php" class="nav-link"><i class="bi bi-graph-up"></i><span class="text">Annoucement</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-gear"></i><span class="text">Settings</span></a></li>
            <li class="nav-item"><a href="../../rfid" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Attendance</span></a></li>
            <li class="nav-item"><a href="../../logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a></li>
        </ul>
    </div>

    <!-- Content -->
    <div id="content">
        <div id="topbar">
            <h4>Locate Elder</h4>
            <div>
                <i class="bi bi-person-circle fs-4"></i> Admin
            </div>
        </div>
        <div class="container-fluid">
            <div class="row g-4">
                <!-- Column 1: Map -->
                <div class="col-lg-5">
                    <div id="map"></div>
                </div>

                <!-- Column 2: Table -->
                <div class="col-lg-7">
                    <div class="table-container">
                        <h4>Saved Locations</h4>
                        <table class="table table-bordered table-striped" id="locationsTable">
                            <thead class="">
                                <tr>
                                    <th>Username</th>
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
                                            <td><?= htmlspecialchars($loc['username']) ?></td>
                                            <td class="address-cell"><?= htmlspecialchars($loc['address'] ?: 'Fetching...') ?></td>
                                            <td><?= htmlspecialchars($loc['created_at']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center">No locations saved yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <script>
        $(document).ready(function() {
            $('#locationsTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [
                    [2, "desc"]
                ]
            });
        });

        // Sidebar toggle
        document.getElementById("toggleSidebar").addEventListener("click", () => {
            const sidebar = document.getElementById("sidebar");
            const content = document.getElementById("content");
            sidebar.classList.toggle("collapsed");
        });

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
            const marker = L.marker([lat, lng]).addTo(map)
                .bindPopup(`Address: ${address}<br>Lat: ${lat}, Lng: ${lng}`);
            markers.push({
                marker,
                row
            });
        });

        // Click row → move map
        document.querySelectorAll('tr.clickable').forEach(row => {
            row.addEventListener('click', () => {
                const lat = parseFloat(row.dataset.lat);
                const lng = parseFloat(row.dataset.lng);
                map.setView([lat, lng], 16);
                const m = markers.find(m => m.row === row);
                if (m) m.marker.openPopup();
            });
        });

        // Auto-update missing addresses
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