<?php
require_once __DIR__ . '/../helpers/jwt_helper.php';

function verifyToken(): ?array {
    header('Content-Type: application/json');

    // Ambil header Authorization
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';

    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $jwt = $matches[1];

        try {
            $decoded = decodeJWT($jwt);
            return (array) $decoded->data; // Berisi 'id', 'username'
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid or expired token.']);
            exit;
        }
    }

    http_response_code(401);
    echo json_encode(['error' => 'Authorization token required.']);
    exit;
}
    