<?php
require_once "config/database.php";

// Ambil ID produk dari parameter URL
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit();
}

// Ambil data produk
try {
    require_once "config/database.php";
    $stmt = $pdo->prepare("SELECT * FROM produk WHERE id = ?");
    $stmt->execute([$id]);
    $produk = $stmt->fetch();

    if (!$produk) {
        header("Location: index.php");
        exit();
    }
} catch (PDOException $e) {
    header("Location: index.php");
    exit();
}

// Tambah logic PHP untuk handle add to cart
session_start();
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_to_cart"])) {
    $cart = &$_SESSION["cart"];
    $pid = $produk["id"];
    $qty = max(1, intval($_POST["qty"] ?? 1));
    if ($produk["stok"] > 0) {
        if (isset($cart[$pid])) {
            $cart[$pid]["qty"] += $qty;
        } else {
            $cart[$pid] = [
                "id" => $produk["id"],
                "nama" => $produk["nama"],
                "harga" => $produk["harga"],
                "gambar" => $produk["gambar"],
                "qty" => $qty,
            ];
        }
        $add_success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produk["nama"]) ?> - Bake & Joy</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #fdfaf6;
            font-family: 'Poppins', sans-serif;
        }
        .navbar-brand, .card-title, h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }
        .product-image {
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }
        .price {
            font-size: 2rem;
            font-weight: 700;
            color: #d63384;
        }
        .stock-badge {
            font-size: 1rem;
        }
        .description {
            line-height: 1.8;
            color: #6c757d;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                🥐 Bake & Joy
            </a>
            <div class="d-flex">
                <a href="index.php" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali ke Toko
                </a>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <img src="view_image.php?file=<?= htmlspecialchars(
                    basename($produk["gambar"]),
                ) ?>"
                     alt="<?= htmlspecialchars($produk["nama"]) ?>"
                     class="img-fluid product-image w-100"
                     onerror="this.onerror=null;this.src='view_image.php?file=placeholder.jpg';">
            </div>
            <div class="col-lg-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Toko</a></li>
                        <li class="breadcrumb-item"><a href="index.php"><?= htmlspecialchars(
                            $produk["kategori"],
                        ) ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars(
                            $produk["nama"],
                        ) ?></li>
                    </ol>
                </nav>

                <h1 class="mb-3"><?= htmlspecialchars($produk["nama"]) ?></h1>

                <div class="mb-3">
                    <span class="badge bg-primary stock-badge"><?= htmlspecialchars(
                        $produk["kategori"],
                    ) ?></span>
                    <span class="badge bg-<?= $produk["stok"] > 10
                        ? "success"
                        : ($produk["stok"] > 0
                            ? "warning"
                            : "danger") ?> stock-badge ms-2">
                        Stok: <?= $produk["stok"] ?>
                    </span>
                </div>

                <div class="price mb-4">
                    Rp <?= number_format($produk["harga"], 0, ",", ".") ?>
                </div>

                <div class="description mb-4">
                    <?= nl2br(htmlspecialchars($produk["deskripsi"])) ?>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-2">
                        <?php if (isset($add_success) && $add_success): ?>
                        <div class="alert alert-success">Produk berhasil ditambahkan ke keranjang! <a href="cart.php" class="alert-link">Lihat Keranjang</a></div>
                        <?php endif; ?>
                        <form method="POST" class="d-flex align-items-center mb-2">
                            <input type="hidden" name="add_to_cart" value="1">
                            <input type="number" name="qty" value="1" min="1" max="<?= $produk[
                                "stok"
                            ] ?>" class="form-control me-2" style="width:90px;" <?= $produk[
    "stok"
] < 1
    ? "disabled"
    : "" ?>>
                            <button class="btn btn-primary btn-lg w-100" type="submit" <?= $produk[
                                "stok"
                            ] < 1
                                ? "disabled"
                                : "" ?>>
                            <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                        </button>
                        </form>
                        <a href="cart.php" class="btn btn-outline-success btn-lg w-100 mb-2"><i class="bi bi-basket"></i> Lihat Keranjang</a>
                    </div>
                    <div class="col-md-6 mb-2">
                        <button class="btn btn-outline-secondary btn-lg w-100" disabled>
                            <i class="bi bi-heart"></i> Wishlist
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="bi bi-info-circle"></i> Informasi Produk
                        </h6>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">ID Produk</small>
                                <p class="mb-0 fw-bold">#<?= $produk[
                                    "id"
                                ] ?></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Ditambahkan</small>
                                <p class="mb-0 fw-bold"><?= date(
                                    "d M Y",
                                    strtotime($produk["created_at"]),
                                ) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        <div class="mt-5">
            <h3 class="mb-4">Produk Terkait</h3>
            <div class="row g-4" id="related-products">
                <div class="col-12 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat produk terkait...</p>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-light text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0 text-muted">&copy; 2025 Bake & Joy. Dibuat dengan PHP, MySQL, dan Bootstrap 5.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load related products
        async function loadRelatedProducts() {
            try {
                const response = await fetch(`api/get_products.php?kategori=${encodeURIComponent('<?= $produk[
                    "kategori"
                ] ?>')}&urutan=default`);
                const result = await response.json();

                if (result.status === 'success') {
                    const relatedProducts = result.data.filter(p => p.id !== <?= $produk[
                        "id"
                    ] ?>).slice(0, 4);
                    const container = document.getElementById('related-products');

                    if (relatedProducts.length === 0) {
                        container.innerHTML = '<div class="col-12 text-center"><p class="text-muted">Tidak ada produk terkait</p></div>';
                        return;
                    }

                    container.innerHTML = '';
                    relatedProducts.forEach(produk => {
                        const productCard = `
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card h-100 shadow-sm">
                                    <img src="view_image.php?file=${produk.gambar}" class="card-img-top" alt="${produk.nama}"
                                         style="height: 200px; object-fit: cover;" onerror="this.onerror=null;this.src='view_image.php?file=placeholder.jpg';">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title">${produk.nama}</h6>
                                        <p class="card-text small text-muted">${produk.deskripsi.substring(0, 60)}...</p>
                                        <span class="badge bg-primary align-self-start mt-auto mb-2">${produk.kategori}</span>
                                        <p class="text-success fw-bold mb-0 text-end">${produk.harga_formatted}</p>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <a href="detail_produk.php?id=${produk.id}" class="btn btn-outline-primary btn-sm w-100">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.innerHTML += productCard;
                    });
                }
            } catch (error) {
                console.error('Error loading related products:', error);
                document.getElementById('related-products').innerHTML =
                    '<div class="col-12 text-center"><p class="text-muted">Gagal memuat produk terkait</p></div>';
            }
        }

        // Load related products when page loads
        document.addEventListener('DOMContentLoaded', loadRelatedProducts);
    </script>

</body>
</html>
