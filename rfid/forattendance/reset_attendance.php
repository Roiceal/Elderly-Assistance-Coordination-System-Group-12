<?php
include 'db_connect.php';
date_default_timezone_set('Asia/Manila');

// Run backup only once per day
$today = date('Y-m-d');
$last_backup_file = 'last_backup.txt';

// Check last backup date
$last_backup = file_exists($last_backup_file) ? trim(file_get_contents($last_backup_file)) : '';

if ($last_backup !== $today) {
    $backupTable = "attendance_backup_" . date('Y_m_d');

    try {
        // Create backup table if not exists
        $createTableSql = "CREATE TABLE IF NOT EXISTS `$backupTable` LIKE `attendance_records`";
        if (!$conn->query($createTableSql)) {
            throw new Exception("Failed to create backup table: " . $conn->error);
        }

        // Copy attendance records to backup table
        $insertSql = "INSERT INTO `$backupTable` SELECT * FROM `attendance_records`";
        if (!$conn->query($insertSql)) {
            throw new Exception("Failed to insert records into backup table: " . $conn->error);
        }

        // Clear original attendance_records table
        $deleteSql = "DELETE FROM `attendance_records`";
        if (!$conn->query($deleteSql)) {
            throw new Exception("Failed to clear attendance records: " . $conn->error);
        }

        // Update last backup date
        file_put_contents($last_backup_file, $today);

        echo json_encode([
            "success" => true,
            "message" => "Attendance records backed up successfully to `$backupTable`."
        ]);

    } catch (Exception $e) {
        // echo json_encode([
        //     "success" => false,
        //     "message" => $e->getMessage()
        // ]);
    }
} else {
    // echo json_encode([
    //     "success" => false,
    //     "message" => "Backup already performed today."
    // ]);
}

$conn->close();
?>
