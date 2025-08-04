<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generateJWT($payload) {
    $key = $_ENV['JWT_SECRET'];
    $issuedAt = time();
    $expire = $issuedAt + 3600; // 1 jam

    $token = [
        'iat' => $issuedAt,
        'exp' => $expire,
        'data' => $payload
    ];

    return JWT::encode($token, $key, 'HS256');
}

function decodeJWT($jwt) {
    $key = $_ENV['JWT_SECRET'];
    return JWT::decode($jwt, new Key($key, 'HS256'));
}
