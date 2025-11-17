<?php
include 'db_connect.php';

date_default_timezone_set('Asia/Manila'); // Set your timezone

$response = [];

try {
    // Generate backup table name with current date
    $backupTable = "attendance_backup_" . date('Y_m_d');

    // Step 1: Create backup table (same structure as attendance_records)
    $createTableSQL = "CREATE TABLE IF NOT EXISTS `$backupTable` LIKE `attendance_records`";
    if (!$conn->query($createTableSQL)) {
        throw new Exception("Error creating backup table: " . $conn->error);
    }

    // Step 2: Add a timestamp column for record keeping (if not exists)
    $conn->query("ALTER TABLE `$backupTable` 
                  ADD COLUMN IF NOT EXISTS backup_time DATETIME DEFAULT CURRENT_TIMESTAMP");

    // Step 3: Copy all records into the backup table
    $copySQL = "INSERT INTO `$backupTable` SELECT * FROM `attendance_records`";
    if (!$conn->query($copySQL)) {
        throw new Exception("Error copying data: " . $conn->error);
    }

    // Step 4: Delete all records from the original table
    $deleteSQL = "DELETE FROM `attendance_records`";
    if (!$conn->query($deleteSQL)) {
        throw new Exception("Error deleting original records: " . $conn->error);
    }

    $response['success'] = true;
    $response['message'] = "Backup completed successfully. Data copied to $backupTable and deleted from main table.";
    $response['timestamp'] = date('Y-m-d H:i:s');

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT);

$conn->close();
?>
