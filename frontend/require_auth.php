<?php
// require_auth.php
session_start();

// Set header untuk mencegah cache halaman di browser
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

// Cek apakah JWT tersedia di cookie
if (!isset($_COOKIE['jwt']) || empty($_COOKIE['jwt'])) {
  header("Location: login.php");
  exit();
}

// (Opsional) Verifikasi isi JWT jika perlu
// Contoh: decode token dan cek apakah expired
// Untuk itu kamu perlu library seperti firebase/php-jwt
