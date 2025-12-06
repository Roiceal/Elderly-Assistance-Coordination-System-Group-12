<?php
include __DIR__ . '/../db_connect.php';
$search = $_POST['search'] ?? '';

$stmt = $pdo->prepare("
    SELECT id, first_name, last_name, phone 
    FROM users 
    WHERE first_name LIKE ? OR last_name LIKE ? OR phone LIKE ?
");
$stmt->execute(["%$search%", "%$search%", "%$search%"]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $name = $row['first_name'] . " " . $row['last_name'];
    echo "
        <a class='list-group-item list-group-item-action user-item'
           data-id='{$row['id']}'
           data-name='$name'>
            <strong>$name</strong><br>
            <small>Phone: {$row['phone']}</small>
        </a>
    ";
}
?>
