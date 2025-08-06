<?php
session_start();
if (
    !isset($_SESSION["admin_logged_in"]) ||
    $_SESSION["admin_logged_in"] !== true
) {
    header("Location: login.php");
    exit();
}
require_once "../config/database.php";

// CSRF token
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION["csrf_token"];

// Pagination
$page = isset($_GET["page"]) ? max(1, intval($_GET["page"])) : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Ambil daftar produk untuk ditampilkan di tabel
$stmt_produk = $pdo->query("SELECT * FROM produk ORDER BY id ASC");
$produk_list = $stmt_produk->fetchAll(PDO::FETCH_ASSOC);

// Hitung total produk
$stmt_count = $pdo->query("SELECT COUNT(*) FROM produk");
$total_produk = $stmt_count->fetchColumn();
$stmt_stok = $pdo->query("SELECT SUM(stok) FROM produk");
$total_stok = $stmt_stok->fetchColumn();
$stmt_nilai = $pdo->query("SELECT SUM(harga) FROM produk");
$total_nilai = $stmt_nilai->fetchColumn();
// Statistik kategori seluruh produk
$kategori_stats = [];
$stmt_kat = $pdo->query(
    "SELECT kategori, COUNT(*) as jumlah FROM produk GROUP BY kategori",
);
foreach ($stmt_kat as $row) {
    $kategori_stats[$row["kategori"]] = $row["jumlah"];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Toko Roti "Bake & Joy"</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: 700;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .table img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                🥐 Bake & Joy - Admin Panel
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../index.php" target="_blank">
                    <i class="bi bi-eye"></i> Lihat Toko
                </a>
                <a class="nav-link" href="logout.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <?php if (isset($_GET["success"])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> Produk berhasil ditambahkan!
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

                <!-- Dashboard Stats -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Produk</h6>
                                        <h3 class="mb-0"><?= $total_produk ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-box h1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Stok</h6>
                                        <h3 class="mb-0"><?= $total_stok ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-archive h1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Nilai</h6>
                                        <h3 class="mb-0">Rp <?= number_format(
                                            $total_nilai,
                                            0,
                                            ",",
                                            ".",
                                        ) ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-currency-dollar h1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Kategori</h6>
                                        <h3 class="mb-0"><?= count(
                                            $kategori_stats,
                                        ) ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-tags h1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Kelola Produk</h2>
                    <div>
                        <a href="image_formats.php" class="btn btn-outline-info btn-sm me-2">
                            <i class="bi bi-image"></i> Format Gambar
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahProdukModal">
                            <i class="bi bi-plus-circle"></i> Tambah Produk
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Gambar</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($produk_list as $produk): ?>
                                    <tr>
                                        <td><?= $produk["id"] ?></td>
                                        <td>
                                            <img src="../view_image.php?file=<?= htmlspecialchars(
                                                basename($produk["gambar"]),
                                            ) ?>"
                                                 alt="<?= htmlspecialchars(
                                                     $produk["nama"],
                                                 ) ?>"
                                                 onerror="this.onerror=null;this.src='../view_image.php?file=placeholder.jpg';">
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars(
                                                $produk["nama"],
                                            ) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= htmlspecialchars(
                                                substr(
                                                    $produk["deskripsi"],
                                                    0,
                                                    50,
                                                ),
                                            ) ?>...</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?= htmlspecialchars(
                                                $produk["kategori"],
                                            ) ?></span>
                                        </td>
                                        <td>
                                            <strong class="text-success">Rp <?= number_format(
                                                $produk["harga"],
                                                0,
                                                ",",
                                                ".",
                                            ) ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $produk[
                                                "stok"
                                            ] > 10
                                                ? "success"
                                                : ($produk["stok"] > 0
                                                    ? "warning"
                                                    : "danger") ?>">
                                                <?= $produk["stok"] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary"
                                                        onclick="editProduk(<?= $produk[
                                                            "id"
                                                        ] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger"
                                                        onclick="hapusProduk(<?= $produk[
                                                            "id"
                                                        ] ?>)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Produk -->
    <div class="modal fade" id="tambahProdukModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formTambahProduk" action="proses_produk.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(
                        $csrf_token,
                    ) ?>">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Produk</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="harga" class="form-label">Harga</label>
                                            <input type="number" class="form-control" id="harga" name="harga" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="stok" class="form-label">Stok</label>
                                            <input type="number" class="form-control" id="stok" name="stok" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="kategori" class="form-label">Kategori</label>
                                    <select class="form-select" id="kategori" name="kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="Roti">Roti</option>
                                        <option value="Kue">Kue</option>
                                        <option value="Pastry">Pastry</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="gambar" class="form-label">Gambar Produk</label>
                                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
                                    <div class="form-text">Format: JPG, PNG, WebP. Maksimal 2MB</div>
                                </div>
                                <div id="preview-gambar" class="mt-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Produk -->
    <div class="modal fade" id="editProdukModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditProduk" action="../api/update_product.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(
                        $csrf_token,
                    ) ?>">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="edit_nama" class="form-label">Nama Produk</label>
                                    <input type="text" class="form-control" id="edit_nama" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" required></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_harga" class="form-label">Harga</label>
                                            <input type="number" class="form-control" id="edit_harga" name="harga" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_stok" class="form-label">Stok</label>
                                            <input type="number" class="form-control" id="edit_stok" name="stok" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_kategori" class="form-label">Kategori</label>
                                    <select class="form-select" id="edit_kategori" name="kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="Roti">Roti</option>
                                        <option value="Kue">Kue</option>
                                        <option value="Pastry">Pastry</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_gambar" class="form-label">Gambar Produk</label>
                                    <input type="file" class="form-control" id="edit_gambar" name="gambar" accept="image/*">
                                    <div class="form-text">Format: JPG, PNG, WebP. Maksimal 2MB (Opsional untuk update)</div>
                                </div>
                                <div id="edit_preview-gambar" class="mt-2"></div>
                                <div id="current_image" class="mt-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview gambar untuk form tambah
        document.getElementById('gambar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview-gambar');

            if (file) {
                // Validasi ukuran file (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    e.target.value = '';
                    preview.innerHTML = '';
                    return;
                }

                // Validasi tipe file
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Tipe file tidak didukung. Gunakan JPG, PNG, atau WebP.');
                    e.target.value = '';
                    preview.innerHTML = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded" style="max-height: 150px;">`;
                }
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });

        // Preview gambar untuk form edit
        document.getElementById('edit_gambar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('edit_preview-gambar');

            if (file) {
                // Validasi ukuran file (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    e.target.value = '';
                    preview.innerHTML = '';
                    return;
                }

                // Validasi tipe file
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Tipe file tidak didukung. Gunakan JPG, PNG, atau WebP.');
                    e.target.value = '';
                    preview.innerHTML = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded" style="max-height: 150px;">`;
                }
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });

        // Validasi form tambah sebelum submit
        document.getElementById('formTambahProduk').addEventListener('submit', function(e) {
            const nama = document.getElementById('nama').value.trim();
            const deskripsi = document.getElementById('deskripsi').value.trim();
            const harga = document.getElementById('harga').value;
            const stok = document.getElementById('stok').value;
            const kategori = document.getElementById('kategori').value;
            const gambar = document.getElementById('gambar').files[0];

            if (!nama || !deskripsi || !harga || !stok || !kategori) {
                alert('Semua field harus diisi!');
                e.preventDefault();
                return;
            }

            if (!gambar) {
                alert('Gambar produk wajib diupload!');
                e.preventDefault();
                return;
            }

            // Show loading state
            const submitBtn = e.target.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';
            submitBtn.disabled = true;
        });

        // Validasi form edit sebelum submit
        document.getElementById('formEditProduk').addEventListener('submit', function(e) {
            const nama = document.getElementById('edit_nama').value.trim();
            const deskripsi = document.getElementById('edit_deskripsi').value.trim();
            const harga = document.getElementById('edit_harga').value;
            const stok = document.getElementById('edit_stok').value;
            const kategori = document.getElementById('edit_kategori').value;

            if (!nama || !deskripsi || !harga || !stok || !kategori) {
                alert('Semua field harus diisi!');
                e.preventDefault();
                return;
            }

            // Show loading state
            const submitBtn = e.target.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengupdate...';
            submitBtn.disabled = true;
        });

        // Fungsi edit produk
        async function editProduk(id) {
            try {
                const response = await fetch(`../api/get_product.php?id=${id}`);
                const result = await response.json();
                console.log("Edit Produk API Response:", result); // Debugging log

                if (result.status === 'success') {
                    const produk = result.data;

                    // Isi form edit
                    document.getElementById('edit_id').value = produk.id;
                    document.getElementById('edit_nama').value = produk.nama;
                    document.getElementById('edit_deskripsi').value = produk.deskripsi;
                    document.getElementById('edit_harga').value = produk.harga;
                    document.getElementById('edit_stok').value = produk.stok;
                    document.getElementById('edit_kategori').value = produk.kategori;

                    // Tampilkan gambar saat ini
                    document.getElementById('current_image').innerHTML = `
                        <div class="alert alert-info">
                            <strong>Gambar Saat Ini:</strong><br>
                            <img src="../view_image.php?file=${produk.gambar}" class="img-fluid rounded mt-2" style="max-height: 100px;"
                                 onerror="this.onerror=null;this.src='../view_image.php?file=placeholder.jpg';">
                        </div>
                    `;

                    // Reset preview gambar baru
                    document.getElementById('edit_preview-gambar').innerHTML = '';
                    document.getElementById('edit_gambar').value = '';

                    // Tampilkan modal
                    const editModal = new bootstrap.Modal(document.getElementById('editProdukModal'));
                    editModal.show();
                } else {
                    alert('Gagal mengambil data produk: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data produk');
            }
        }

        // Fungsi hapus produk
        async function hapusProduk(id) {
            if (confirm('Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.')) {
                try {
                    const formData = new FormData();
                    formData.append('id', id);
                    formData.append('csrf_token', '<?= $csrf_token ?>');
                    console.log("Hapus Produk ID:", id); // Debugging log

                    const response = await fetch('../api/delete_product.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.status === 'success') {
                        alert('Produk berhasil dihapus!');
                        location.reload(); // Reload halaman untuk update tabel
                    } else {
                        alert('Gagal menghapus produk: ' + result.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus produk');
                }
            }
        }

        // Handle form edit submit
        document.getElementById('formEditProduk').addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log("Form Edit Produk Submitted"); // Debugging log

            try {
                const formData = new FormData(this);

                const response = await fetch('../api/update_product.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    alert('Produk berhasil diupdate!');
                    location.reload(); // Reload halaman untuk update tabel
                } else {
                    alert('Gagal mengupdate produk: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate produk');
            }
        });
    </script>

    <?php if ($total_pages > 1): ?>
    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center mt-4">
        <li class="page-item<?= $page <= 1 ? " disabled" : "" ?>">
          <a class="page-link" href="?page=<?= $page -
              1 ?>" tabindex="-1">&laquo; Prev</a>
        </li>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item<?= $i == $page ? " active" : "" ?>">
            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item<?= $page >= $total_pages ? " disabled" : "" ?>">
          <a class="page-link" href="?page=<?= $page + 1 ?>">Next &raquo;</a>
        </li>
      </ul>
    </nav>
    <?php endif; ?>

</body>
</html>
