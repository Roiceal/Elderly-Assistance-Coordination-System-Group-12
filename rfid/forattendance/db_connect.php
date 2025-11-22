<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elderlyassistancecoordinationdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "DB connection failed."]));
}
?>
