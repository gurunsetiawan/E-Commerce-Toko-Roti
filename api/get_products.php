<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

try {
    // Ambil parameter filter
    $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
    $pencarian = isset($_GET['pencarian']) ? $_GET['pencarian'] : '';
    $urutan = isset($_GET['urutan']) ? $_GET['urutan'] : 'default';
    
    // Query dasar
    $sql = "SELECT * FROM produk WHERE 1=1";
    $params = [];
    
    // Filter berdasarkan kategori
    if (!empty($kategori) && $kategori !== 'semua') {
        $sql .= " AND kategori = ?";
        $params[] = $kategori;
    }
    
    // Filter berdasarkan pencarian
    if (!empty($pencarian)) {
        $sql .= " AND nama LIKE ?";
        $params[] = "%$pencarian%";
    }
    
    // Urutkan berdasarkan harga
    switch ($urutan) {
        case 'rendah-tinggi':
            $sql .= " ORDER BY harga ASC";
            break;
        case 'tinggi-rendah':
            $sql .= " ORDER BY harga DESC";
            break;
        default:
            $sql .= " ORDER BY id ASC";
            break;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $produk = $stmt->fetchAll();
    
    // Format harga ke Rupiah
    foreach ($produk as &$item) {
        $item['harga_formatted'] = 'Rp ' . number_format($item['harga'], 0, ',', '.');
    }
    
    echo json_encode([
        'status' => 'success',
        'data' => $produk,
        'total' => count($produk)
    ]);
    
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan pada server. Silakan coba lagi.'
    ]);
    exit;
}
?> 