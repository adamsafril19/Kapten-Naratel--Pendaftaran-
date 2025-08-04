<?php
$password = 'mypassword123';
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "Hash: " . $hash . PHP_EOL;

if (password_verify($password, $hash)) {
    echo "✔️  Hash cocok\n";
} else {
    echo "❌ Tidak cocok\n";
}
