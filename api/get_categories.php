<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

try {
    // Query untuk mengambil kategori unik dari tabel produk
    $sql = "SELECT DISTINCT kategori FROM produk ORDER BY kategori";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $kategori = $stmt->fetchAll();
    
    echo json_encode([
        'status' => 'success',
        'data' => $kategori
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?> 