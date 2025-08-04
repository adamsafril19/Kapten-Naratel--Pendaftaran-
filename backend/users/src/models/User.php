<?php
namespace Models;

use PDO;

class User
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function create(string $username, string $hashedPassword): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (username, password, created_by, updated_by) VALUES (:username, :password, NULL, NULL)"
        );
        return $stmt->execute([
            'username' => $username,
            'password' => $hashedPassword
        ]);
    }
}
