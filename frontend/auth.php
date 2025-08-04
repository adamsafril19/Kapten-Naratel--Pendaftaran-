<?php
// auth.php
require_once 'vendor/autoload.php';
require_once 'config.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (!isset($_COOKIE['token'])) {
  header('Location: login.php');
  exit;
}

try {
  $decoded = JWT::decode($_COOKIE['token'], new Key(JWT_SECRET, 'HS256'));
  $userData = (array) $decoded->user;
} catch (Exception $e) {
  header('Location: login.php');
  exit;
}
