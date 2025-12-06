<?php
session_start();
include "../db_connect.php"; // adjust path if needed

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $date_of_event = $_POST['date_of_event'] ?? null;

    if (empty($title) || empty($content)) {
        die("Title and content are required.");
    }

    // Insert into database
    $stmt = $pdo->prepare("
        INSERT INTO announcements (title, content, date_of_event)
        VALUES (?, ?, ?)
    ");

    $stmt->execute([$title, $content, $date_of_event]);

    // SweetAlert response
    echo '
    <!DOCTYPE html>
    <html>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: "success",
                title: "Announcement Posted!",
                text: "Your announcement has been published successfully.",
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = "make_announcement.php";
            });
        </script>
    </body>
    </html>
    ';
    exit();
}
?>
