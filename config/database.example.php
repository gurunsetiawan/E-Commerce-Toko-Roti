<?php
/**
 * Database Configuration Example
 * 
 * Salin file ini ke `database.php` dan sesuaikan dengan konfigurasi database Anda.
 * Pastikan file `database.php` sudah ditambahkan ke .gitignore
 */

// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'toko_roti_db');
define('DB_USER', 'username_database');  // Ganti dengan username database Anda
define('DB_PASS', 'password_database');  // Ganti dengan password database Anda

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", 
        DB_USER, 
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch(PDOException $e) {
    // Jangan tampilkan error detail di production
    error_log("Koneksi database gagal: " . $e->getMessage());
    die("Terjadi kesalahan. Silakan coba lagi nanti.");
}
