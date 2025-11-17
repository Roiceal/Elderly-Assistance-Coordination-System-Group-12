<?php
// include 'db_connect.php';

// $response = [];

// try {
//     // $sql = "DELETE FROM attendance_records";
//     $sql = "INSERT INTO attendance_backup SELECT * FROM attendance_records";
//     if ($conn->query($sql) === TRUE) {
//         $response['success'] = true;
//     } else {
//         $response['success'] = false;
//         $response['message'] = "Error: " . $conn->error;
//     }

//     $sql = "DELETE FROM attendance_records";
//     $conn->query($sql);
// } catch (Exception $e) {
//     $response['success'] = false;
//     $response['message'] = $e->getMessage();
// }

// echo json_encode($response);
// $conn->close();


include 'db_connect.php';
date_default_timezone_set('Asia/Manila');

while (true) {
    $currentHour = date('H');
    $currentMinute = date('i');

    if ($currentHour == '00' && $currentMinute == '00') {
        try {
            $backupTable = "attendance_backup_" . date('Y_m_d');
            $conn->query("CREATE TABLE IF NOT EXISTS `$backupTable` LIKE `attendance_records`");
            $conn->query("INSERT INTO `$backupTable` SELECT * FROM `attendance_records`");
            $conn->query("DELETE FROM `attendance_records`");
            echo "Backup completed at " . date('Y-m-d H:i:s') . PHP_EOL;

            // Wait one minute to prevent duplicate execution
            sleep(60);

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . PHP_EOL;
        }
    }

    // Sleep 10 seconds before checking again
    sleep(10);
}




?>
