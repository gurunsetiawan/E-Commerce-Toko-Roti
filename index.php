<?php
// Load database configuration
require_once __DIR__ . '/config/database.php';

// Initialize database connection
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
    error_log("Koneksi database gagal: " . $e->getMessage());
    die("Terjadi kesalahan. Silakan coba lagi nanti.");
}

// Ambil data kategori untuk dropdown
try {
    $stmt = $pdo->query(
        "SELECT DISTINCT kategori FROM produk ORDER BY kategori",
    );
    $kategori_list = $stmt->fetchAll();
} catch (PDOException $e) {
    $kategori_list = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Toko Roti "Bake & Joy"</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
        .card {
            border: none;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            border-radius: 15px;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 15px 15px 0 0;
        }
        .harga {
            font-size: 1.25rem;
            font-weight: 600;
            color: #d63384; /* Bootstrap pink */
        }
        .badge {
            font-size: 0.8rem;
            font-weight: 600;
        }
        .filter-section {
            background-color: #fff;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        }
        .loading {
            text-align: center;
            padding: 2rem;
        }
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">
                🥐 Bake & Joy - E-Commerce
            </a>
            <div class="d-flex">
                <a href="admin/" class="btn btn-outline-primary btn-sm me-2">
                    <i class="bi bi-gear"></i> Admin Panel
                </a>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <?php if (isset($_GET["success"])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?= htmlspecialchars(
                    $_GET["success"],
                ) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET["error"])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars(
                    $_GET["error"],
                ) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="text-center mb-5">
            <h1>Etalase Produk Kami</h1>
            <p class="lead text-muted">Temukan roti, kue, dan pastry favorit Anda.</p>
        </div>

        <!-- Stats Section -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-box h1 text-primary mb-2"></i>
                        <h5 class="card-title">Total Produk</h5>
                        <p class="card-text" id="total-produk">-</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-tags h1 text-success mb-2"></i>
                        <h5 class="card-title">Kategori</h5>
                        <p class="card-text" id="total-kategori">-</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-star h1 text-warning mb-2"></i>
                        <h5 class="card-title">Produk Terlaris</h5>
                        <p class="card-text" id="produk-terlaris">-</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-currency-dollar h1 text-info mb-2"></i>
                        <h5 class="card-title">Harga Terendah</h5>
                        <p class="card-text" id="harga-terendah">-</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4 filter-section">
            <div class="col-md-4 mb-3 mb-md-0">
                <label for="filter-kategori" class="form-label fw-bold">Kategori</label>
                <select id="filter-kategori" class="form-select">
                    <option value="semua" selected>Semua Kategori</option>
                    <?php foreach ($kategori_list as $kat): ?>
                        <option value="<?= htmlspecialchars(
                            $kat["kategori"],
                        ) ?>"><?= htmlspecialchars($kat["kategori"]) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <label for="filter-harga" class="form-label fw-bold">Urutkan Harga</label>
                <select id="filter-harga" class="form-select">
                    <option value="default" selected>Default</option>
                    <option value="rendah-tinggi">Terendah ke Tertinggi</option>
                    <option value="tinggi-rendah">Tertinggi ke Terendah</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="input-pencarian" class="form-label fw-bold">Cari Produk</label>
                <input type="text" id="input-pencarian" class="form-control" placeholder="Contoh: Croissant...">
            </div>
        </div>

        <div id="daftar-produk" class="row g-4">
            <div class="col-12 loading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat produk...</p>
            </div>
        </div>

    </main>

    <footer class="bg-light text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0 text-muted">&copy; 2025 Bake & Joy. Dibuat dengan PHP, MySQL, dan Bootstrap 5.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const daftarProdukContainer = document.getElementById('daftar-produk');
        const filterKategori = document.getElementById('filter-kategori');
        const filterHarga = document.getElementById('filter-harga');
        const inputPencarian = document.getElementById('input-pencarian');

        // --- FUNGSI UNTUK MENAMPILKAN PRODUK ---
        function tampilkanProduk(data) {
            daftarProdukContainer.innerHTML = ''; // Kosongkan kontainer sebelum menampilkan data baru

            if (data.length === 0) {
                daftarProdukContainer.innerHTML = `
                    <div class="col-12 text-center">
                        <div class="alert alert-info" role="alert">
                            <h5>Oops! Produk yang Anda cari tidak ditemukan.</h5>
                            <p class="mb-0">Coba ubah filter atau kata kunci pencarian Anda.</p>
                        </div>
                    </div>`;
                return;
            }

            data.forEach(produk => {
                const imgSrc = produk.gambar ? `view_image.php?file=${produk.gambar}` : 'view_image.php?file=placeholder.jpg';
                const productCard = `
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card h-100 shadow-sm">
                            <img src="${imgSrc}" class="card-img-top" alt="${produk.nama}" onerror="this.onerror=null;this.src='view_image.php?file=placeholder.jpg';">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${produk.nama}</h5>
                                <p class="card-text small text-muted">${produk.deskripsi}</p>
                                <span class="badge bg-primary align-self-start mt-auto mb-2">${produk.kategori}</span>
                                <p class="harga mb-0 text-end">${produk.harga_formatted}</p>
                                <small class="text-muted text-end">Stok: ${produk.stok}</small>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="detail_produk.php?id=${produk.id}" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="bi bi-eye"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                `;
                daftarProdukContainer.innerHTML += productCard;
            });
        }

        // --- FUNGSI UNTUK MENGAMBIL DATA DARI API ---
        async function ambilDataProduk() {
            try {
                const kategori = filterKategori.value;
                const urutan = filterHarga.value;
                const pencarian = inputPencarian.value;

                const params = new URLSearchParams({
                    kategori: kategori,
                    urutan: urutan,
                    pencarian: pencarian
                });

                const response = await fetch(`api/get_products.php?${params}`);
                const result = await response.json();

                if (result.status === 'success') {
                    tampilkanProduk(result.data);
                } else {
                    throw new Error(result.message || 'Terjadi kesalahan saat mengambil data');
                }
            } catch (error) {
                console.error('Error:', error);
                daftarProdukContainer.innerHTML = `
                    <div class="col-12 text-center">
                        <div class="alert alert-danger" role="alert">
                            <h5>Terjadi kesalahan!</h5>
                            <p class="mb-0">${error.message}</p>
                        </div>
                    </div>`;
            }
        }

        // --- EVENT LISTENERS ---
        filterKategori.addEventListener('change', ambilDataProduk);
        filterHarga.addEventListener('change', ambilDataProduk);
        inputPencarian.addEventListener('input', debounce(ambilDataProduk, 500));

        // --- FUNGSI DEBOUNCE UNTUK PENCARIAN ---
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // --- FUNGSI UNTUK MEMUAT STATISTIK ---
        async function muatStatistik() {
            try {
                const response = await fetch('api/get_products.php');
                const result = await response.json();

                if (result.status === 'success') {
                    const data = result.data;

                    // Total produk
                    document.getElementById('total-produk').textContent = data.length;

                    // Total kategori
                    const kategoriUnik = [...new Set(data.map(p => p.kategori))];
                    document.getElementById('total-kategori').textContent = kategoriUnik.length;

                    // Produk terlaris (berdasarkan stok tertinggi)
                    const produkTerlaris = data.reduce((prev, current) =>
                        (prev.stok > current.stok) ? prev : current
                    );
                    document.getElementById('produk-terlaris').textContent = produkTerlaris.nama;

                    // Harga terendah
                    const hargaTerendah = data.reduce((prev, current) =>
                        (prev.harga < current.harga) ? prev : current
                    );
                    document.getElementById('harga-terendah').textContent = hargaTerendah.harga_formatted;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // --- INISIASI AWAL ---
        ambilDataProduk(); // Tampilkan semua produk saat halaman pertama kali dimuat
        muatStatistik(); // Muat statistik
    });
    </script>

</body>
</html>
