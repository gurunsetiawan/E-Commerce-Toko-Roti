<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

require_once "../config/database.php";

require_once "../config/database.php";

try {
    $id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

    if ($id <= 0) {
        throw new Exception("ID produk tidak valid");
    }

    $stmt = $pdo->prepare("SELECT * FROM produk WHERE id = ?");
    $stmt->execute([$id]);
    $produk = $stmt->fetch();

    if (!$produk) {
        throw new Exception("Produk tidak ditemukan");
    }

    // Format harga
    $produk["harga_formatted"] =
        "Rp " . number_format($produk["harga"], 0, ",", ".");

    echo json_encode([
        "status" => "success",
        "data" => $produk,
    ]);
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        "status" => "error",
        "message" => "Terjadi kesalahan pada server. Silakan coba lagi.",
    ]);
    exit();
}
?>
