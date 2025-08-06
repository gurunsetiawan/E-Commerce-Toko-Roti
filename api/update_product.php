<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized'
    ]);
    exit;
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? null)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'CSRF token tidak valid'
        ]);
        exit;
    }
    try {
        // Ambil data dari POST
        $id = intval($_POST['id']);
        $nama = trim($_POST['nama']);
        $deskripsi = trim($_POST['deskripsi']);
        $harga = floatval($_POST['harga']);
        $stok = intval($_POST['stok']);
        $kategori = trim($_POST['kategori']);

        // Validasi data
        if ($id <= 0 || empty($nama) || empty($deskripsi) || $harga <= 0 || $stok < 0 || empty($kategori)) {
            throw new Exception('Semua field harus diisi dengan benar');
        }

        // Cek apakah produk ada
        $stmt = $pdo->prepare("SELECT * FROM produk WHERE id = ?");
        $stmt->execute([$id]);
        $existing_product = $stmt->fetch();
        
        if (!$existing_product) {
            throw new Exception('Produk tidak ditemukan');
        }

        $gambar_path = $existing_product['gambar']; // Gunakan gambar yang ada

        // Proses upload gambar jika ada
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['gambar'];
            
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

            // Pastikan direktori assets ada dan writable
            if (!is_dir('../assets/')) {
                mkdir('../assets/', 0755, true);
            }

            // Upload file
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $gambar_path = 'assets/' . $filename;
                
                // Hapus gambar lama jika berbeda
                if ($existing_product['gambar'] !== $gambar_path && file_exists('../' . $existing_product['gambar'])) {
                    unlink('../' . $existing_product['gambar']);
                }
            } else {
                throw new Exception('Gagal mengupload gambar');
            }
        }

        // Update database
        $sql = "UPDATE produk SET nama = ?, deskripsi = ?, harga = ?, gambar = ?, kategori = ?, stok = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$nama, $deskripsi, $harga, $gambar_path, $kategori, $stok, $id]);

        if ($result) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Produk berhasil diupdate'
            ]);
        } else {
            throw new Exception('Gagal mengupdate produk');
        }

    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada server. Silakan coba lagi.'
        ]);
        exit;
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Method tidak diizinkan'
    ]);
}
?> 