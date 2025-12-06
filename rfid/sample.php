<?php
session_start();
include __DIR__ . '/../db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register RFID</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .user-item:hover {
            background: #f1f1f1;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4 text-center">Register RFID to User</h2>

    <!-- SEARCH BAR -->
    <div class="card p-3 mb-4 shadow">
        <label class="mb-1">Search Elder:</label>
        <input type="text" id="search" class="form-control" placeholder="Search name or phone...">
        <div id="results" class="list-group mt-2"></div>
    </div>

    <!-- RFID REGISTER FORM -->
    <!-- RFID REGISTER FORM -->
<div class="card p-4 shadow">
    <h4 class="mb-3">RFID Registration Form</h4>

    <form id="rfidForm" method="POST" action="save_rfid.php" enctype="multipart/form-data">
        <input type="hidden" name="user_id" id="user_id">

        <div class="mb-3">
            <label class="form-label">Selected User</label>
            <input type="text" class="form-control" id="selected_user" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">RFID Card ID</label>
            <input type="number" class="form-control" name="card_id" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Image</label>
            <input type="file" class="form-control" name="image" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Register RFID to this User
        </button>
    </form>
</div>

</div>

<script>
$(document).ready(function() {
    // Live search
    $("#search").keyup(function() {
        let query = $(this).val();
        if (query.length < 1) {
            $("#results").html("");
            return;
        }

        $.post("search_users.php", { search: query }, function(data) {
            $("#results").html(data);
        });
    });

    // Auto-fill when clicking a user
    $(document).on("click", ".user-item", function() {
        let id = $(this).data("id");
        let name = $(this).data("name");

        $("#user_id").val(id);
        $("#selected_user").val(name);

        $("#results").html(""); // hide list
        $("#search").val("");   // clear search bar
    });
});
</script>

</body>
</html>
