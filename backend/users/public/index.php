<?php

// public/index.php (users)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Handle OPTIONS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Load environment dan koneksi DB
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../src/models/User.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';

use Controllers\AuthController;
use Models\User;

// Set header JSON
header('Content-Type: application/json');

// Ambil path dari query string (?path=login atau register)
$path = $_GET['path'] ?? null;
$method = $_SERVER['REQUEST_METHOD'];

// Validasi request method
if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Baca data JSON dari body
$data = json_decode(file_get_contents("php://input"), true);

// Inisialisasi controller
$userModel = new User($pdo);
$authController = new AuthController($userModel);

// Routing
switch ($path) {
    case 'login':
        $authController->login($data);
        break;
    case 'register':
        $authController->register($data);
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Invalid path']);
        break;
}
