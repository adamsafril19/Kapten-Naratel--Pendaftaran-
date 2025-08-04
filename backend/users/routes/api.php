<?php

// Enable CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Models\User;
use Controllers\AuthController;

try {
    $pdo = require __DIR__ . '/../database/connection.php';
    $authController = new AuthController(new User($pdo));

    $method = $_SERVER['REQUEST_METHOD'];
    $path = $_GET['path'] ?? '';
    $input = json_decode(file_get_contents("php://input"), true);

    if ($method === 'POST' && $path === 'register') {
        $authController->register($input);
    } elseif ($method === 'POST' && $path === 'login') {
        $authController->login($input);
    } elseif ($method === 'GET' && $path === 'test') {
        echo json_encode([
            'message' => 'Backend is working!',
            'timestamp' => date('Y-m-d H:i:s'),
            'php_version' => phpversion()
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'error' => 'Endpoint not found'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error: ' . $e->getMessage()
    ]);
}
