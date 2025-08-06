<?php
require_once '../config/database.php';

$new_password = 'admin123';
$hash = password_hash($new_password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('UPDATE admin SET password = ? WHERE username = ?');
$stmt->execute([$hash, 'admin']);

if ($stmt->rowCount() > 0) {
    echo "Password admin berhasil diupdate!<br>Username: admin<br>Password: admin123";
} else {
    echo "Gagal update password admin atau admin tidak ditemukan.";
} 