<?php
session_start();

if(!isset($_COOKIE['guest'])) {
    // Set a cookie for guests, expires in 1 day only!
    setcookie("guest", "true", time() + 86400, "/"); 
    $greeting = "Welcome, new visitor!";
} else {
    $greeting = "Welcome back, guest!";
}

$showLogout = false; 
?>
