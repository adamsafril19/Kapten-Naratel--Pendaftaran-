<?php
session_start();

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    // Redirect ke halaman login
    header("Location: login.html");
    exit;
}
?>
