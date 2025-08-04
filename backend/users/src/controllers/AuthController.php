<?php
namespace Controllers;

use Models\User;

class AuthController
{
    private User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function register(array $data): void
    {
        header('Content-Type: application/json');

        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');

        if (!$username || !$password) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Username dan password wajib diisi.']);
            return;
        }

        if ($this->userModel->findByUsername($username)) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Username sudah digunakan.']);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $created = $this->userModel->create($username, $hashedPassword);

        if ($created) {
            http_response_code(201);
            echo json_encode(['success' => true, 'message' => 'Registrasi berhasil.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data user.']);
        }
    }

    public function login(array $data): void
    {
        header('Content-Type: application/json');

        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');

        if (!$username || !$password) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Username dan password wajib diisi.']);
            return;
        }

        $user = $this->userModel->findByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Username atau password salah.']);
            return;
        }

        require_once __DIR__ . '/../../helpers/jwt_helper.php';
        $token = generateJWT([
            'id' => $user['id'],
            'username' => $user['username']
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Login berhasil.',
            'token' => $token
        ]);
    }
}
