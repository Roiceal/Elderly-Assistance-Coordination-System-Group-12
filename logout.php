<?php
session_start();
session_unset();
// Destroy the session
session_destroy();
// Redirect to the login page (or home page)
header("Location:login.php");
exit;
?>