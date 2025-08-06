<?php
session_start();
$cart = $_SESSION['cart'] ?? [];

// Handle update qty
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_qty'])) {
        foreach ($_POST['qty'] as $id => $qty) {
            $qty = max(1, intval($qty));
            if (isset($cart[$id])) {
                $cart[$id]['qty'] = $qty;
            }
        }
        $_SESSION['cart'] = $cart;
        header('Location: cart.php');
        exit;
    } elseif (isset($_POST['remove_item'])) {
        $id = $_POST['remove_item'];
        unset($cart[$id]);
        $_SESSION['cart'] = $cart;
        header('Location: cart.php');
        exit;
    }
}

function rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

$total = 0;
foreach ($cart as $item) {
    $total += $item['harga'] * $item['qty'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Bake & Joy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-light bg-light shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">🥐 Bake & Joy</a>
            <a href="index.php" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali ke Toko
            </a>
        </div>
    </nav>
    <div class="container my-5">
        <h2 class="mb-4">Keranjang Belanja</h2>
        <?php if (empty($cart)): ?>
            <div class="alert alert-info">Keranjang belanja kosong. <a href="index.php" class="alert-link">Belanja sekarang</a></div>
        <?php else: ?>
        <form method="POST">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $item): ?>
                        <tr>
                            <td>
                                <img src="view_image.php?file=<?= htmlspecialchars(basename($item['gambar'])) ?>" alt="" style="width:60px;height:60px;object-fit:cover;border-radius:8px;" onerror="this.onerror=null;this.src='view_image.php?file=placeholder.jpg';">
                                <span class="ms-2 fw-bold"><?= htmlspecialchars($item['nama']) ?></span>
                            </td>
                            <td><?= rupiah($item['harga']) ?></td>
                            <td style="max-width:90px;">
                                <input type="number" name="qty[<?= $item['id'] ?>]" value="<?= $item['qty'] ?>" min="1" class="form-control">
                            </td>
                            <td><?= rupiah($item['harga'] * $item['qty']) ?></td>
                            <td>
                                <button type="submit" name="remove_item" value="<?= $item['id'] ?>" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Lanjut Belanja</a>
                </div>
                <div class="text-end">
                    <h4>Total: <span class="text-success"><?= rupiah($total) ?></span></h4>
                    <button type="submit" name="update_qty" class="btn btn-primary me-2">Update Qty</button>
                    <a href="checkout.php" class="btn btn-success">Checkout <i class="bi bi-credit-card"></i></a>
                </div>
            </div>
        </form>
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