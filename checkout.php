<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['harga'] * $item['qty'];
}

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($cart)) {
    // Simulasi payment gateway (mock)
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    if ($nama && $email && $alamat) {
        $success = true;
        $_SESSION['cart'] = [];
    } else {
        $error = 'Semua field wajib diisi.';
    }
}
function rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Bake & Joy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-light bg-light shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">🥐 Bake & Joy</a>
            <a href="cart.php" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali ke Keranjang
            </a>
        </div>
    </nav>
    <div class="container my-5">
        <h2 class="mb-4">Checkout</h2>
        <?php if ($success): ?>
            <div class="alert alert-success">
                <h4 class="alert-heading">Pembayaran Berhasil!</h4>
                <p>Terima kasih, pesanan Anda telah diterima dan akan segera diproses.</p>
                <hr>
                <a href="index.php" class="btn btn-primary">Kembali ke Toko</a>
            </div>
        <?php elseif (empty($cart)): ?>
            <div class="alert alert-info">Keranjang belanja kosong. <a href="index.php" class="alert-link">Belanja sekarang</a></div>
        <?php else: ?>
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">Ringkasan Pesanan</div>
                    <div class="card-body">
                        <ul class="list-group mb-3">
                            <?php foreach ($cart as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <img src="view_image.php?file=<?= htmlspecialchars(basename($item['gambar'])) ?>" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:8px;" onerror="this.onerror=null;this.src='view_image.php?file=placeholder.jpg';">
                                    <span class="ms-2 fw-bold"><?= htmlspecialchars($item['nama']) ?></span>
                                    <span class="badge bg-secondary ms-2">x<?= $item['qty'] ?></span>
                                </div>
                                <span><?= rupiah($item['harga'] * $item['qty']) ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <h5 class="text-end">Total: <span class="text-success"><?= rupiah($total) ?></span></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">Data Pembeli</div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                        <div class="alert alert-danger mb-2"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat Pengiriman</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100 btn-lg">Bayar Sekarang <i class="bi bi-credit-card"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <footer class="bg-light text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0 text-muted">&copy; 2025 Bake & Joy. Dibuat dengan PHP, MySQL, dan Bootstrap 5.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 