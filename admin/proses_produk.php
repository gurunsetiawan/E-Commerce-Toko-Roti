<?php
require_once '../config/database.php';

// Pastikan koneksi database tersedia
if (!isset($pdo)) {
    throw new Exception('Koneksi database tidak tersedia. Pastikan konfigurasi database sudah benar.');
}

session_start();
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? null)) {
    header('Location: index.php?error=CSRF token tidak valid');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Debug: Log input data
        error_log("POST data received: " . print_r($_POST, true));
        error_log("FILES data received: " . print_r($_FILES, true));

        // Validasi input
        $nama = trim($_POST['nama']);
        $deskripsi = trim($_POST['deskripsi']);
        $harga = floatval($_POST['harga']);
        $stok = intval($_POST['stok']);
        $kategori = trim($_POST['kategori']);

        // Validasi data
        if (empty($nama) || empty($deskripsi) || $harga <= 0 || $stok < 0 || empty($kategori)) {
            throw new Exception('Semua field harus diisi dengan benar');
        }

        // Proses upload gambar
        $gambar_path = '';
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['gambar'];

            // Debug: Log file info
            error_log("File upload info: " . print_r($file, true));

            // Validasi tipe file
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!in_array($file['type'], $allowed_types)) {
                throw new Exception('Tipe file tidak didukung. Gunakan JPG, PNG, atau WebP. Tipe yang diupload: ' . $file['type']);
            }

            // Validasi ukuran file (maksimal 2MB)
            if ($file['size'] > 2 * 1024 * 1024) {
                throw new Exception('Ukuran file terlalu besar. Maksimal 2MB. Ukuran file: ' . $file['size'] . ' bytes');
            }

            // Generate nama file unik
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'produk_' . time() . '_' . uniqid() . '.' . $extension;
            $upload_path = '../assets/' . $filename;

            // Debug: Log upload path
            error_log("Upload path: " . $upload_path);
            error_log("Upload directory exists: " . (is_dir('../assets/') ? 'Yes' : 'No'));
            error_log("Upload directory writable: " . (is_writable('../assets/') ? 'Yes' : 'No'));

            // Pastikan direktori assets ada dan writable
            if (!is_dir('../assets/')) {
                mkdir('../assets/', 0755, true);
            }

            // Upload file
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $gambar_path = 'assets/' . $filename;
                error_log("File uploaded successfully to: " . $gambar_path);
            } else {
                $upload_error = error_get_last();
                throw new Exception('Gagal mengupload gambar. Error: ' . ($upload_error['message'] ?? 'Unknown error'));
            }
        } else {
            $upload_error = $_FILES['gambar']['error'] ?? 'No file uploaded';
            throw new Exception('Gambar produk wajib diupload. Upload error: ' . $upload_error);
        }

        // Debug: Log database insert
        error_log("Inserting to database: nama=$nama, harga=$harga, gambar=$gambar_path");

        // Insert ke database
        $sql = "INSERT INTO produk (nama, deskripsi, harga, gambar, kategori, stok) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$nama, $deskripsi, $harga, $gambar_path, $kategori, $stok]);

        if ($result) {
            error_log("Database insert successful. ID: " . $pdo->lastInsertId());
            // Redirect dengan pesan sukses
            header('Location: index.php?success=Produk berhasil ditambahkan!');
        } else {
            throw new Exception('Gagal menyimpan ke database');
        }
        exit;

    } catch (Exception $e) {
        error_log("Error in proses_produk.php: " . $e->getMessage());
        // Redirect dengan pesan error
        header('Location: index.php?error=' . urlencode($e->getMessage()));
        exit;
    } else {
        // Jika bukan POST request, redirect ke halaman admin
        header('Location: index.php');
        exit;
    }
    // Kode unreachable dihapus karena tidak relevan
?>
