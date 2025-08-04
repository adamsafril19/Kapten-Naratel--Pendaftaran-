<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

//echo 'ENV loaded: DB = ' . $_ENV['DB_DATABASE'] . "<br>";

// Inisialisasi Capsule
$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => $_ENV['DB_DRIVER'],
    'host'      => $_ENV['DB_HOST'],
    'port'      => $_ENV['DB_PORT'],
    'database'  => $_ENV['DB_DATABASE'],
    'username'  => $_ENV['DB_USERNAME'],
    'password'  => $_ENV['DB_PASSWORD'],
    'charset'   => 'utf8',
    'prefix'    => '',
    'schema'    => $_ENV['DB_SCHEMA'],
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// ✅ Cek koneksi dan return PDO
try {
    $pdo = $capsule->getConnection()->getPdo();
    //echo "✅ Database connected successfully.<br>";
    return $pdo; // <--- ini yang penting agar $pdo tersedia di file lain
} catch (Exception $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
