<?php
// File untuk menampilkan informasi format gambar yang didukung
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Format Gambar yang Didukung - Bake & Joy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-image"></i> Format Gambar yang Didukung
                        </h4>
                    </div>
                    <div class="card-body">
                        <h5>📸 Format yang Didukung:</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <i class="bi bi-file-earmark-image text-success h1"></i>
                                        <h6 class="card-title">JPEG/JPG</h6>
                                        <p class="card-text small">
                                            <strong>Keunggulan:</strong><br>
                                            • Kompatibilitas tinggi<br>
                                            • Ukuran file kecil<br>
                                            • Didukung semua browser
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <i class="bi bi-file-earmark-image text-info h1"></i>
                                        <h6 class="card-title">PNG</h6>
                                        <p class="card-text small">
                                            <strong>Keunggulan:</strong><br>
                                            • Kualitas lossless<br>
                                            • Transparansi<br>
                                            • Bagus untuk grafik
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <i class="bi bi-file-earmark-image text-warning h1"></i>
                                        <h6 class="card-title">WebP</h6>
                                        <p class="card-text small">
                                            <strong>Keunggulan:</strong><br>
                                            • Ukuran sangat kecil<br>
                                            • Kualitas tinggi<br>
                                            • Format modern
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5>📋 Spesifikasi Upload:</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="bi bi-check-circle text-success"></i>
                                <strong>Ukuran Maksimal:</strong> 2MB per file
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-check-circle text-success"></i>
                                <strong>Format yang Diterima:</strong> JPG, JPEG, PNG, WebP
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-check-circle text-success"></i>
                                <strong>Resolusi:</strong> Disarankan minimal 300x300 pixel
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-check-circle text-success"></i>
                                <strong>Aspect Ratio:</strong> Disarankan 1:1 (square) atau 4:3
                            </li>
                        </ul>

                        <h5>💡 Tips Upload Gambar:</h5>
                        <div class="alert alert-info">
                            <ul class="mb-0">
                                <li>Gunakan gambar dengan latar belakang putih atau transparan</li>
                                <li>Pastikan produk terlihat jelas dan fokus</li>
                                <li>Kompres gambar jika ukuran terlalu besar</li>
                                <li>Format WebP memberikan ukuran file terkecil dengan kualitas tinggi</li>
                                <li>Test upload di halaman debug jika ada masalah</li>
                            </ul>
                        </div>

                        <h5>🔧 Cara Kompres Gambar:</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Online Tools:</h6>
                                <ul>
                                    <li><a href="https://tinypng.com" target="_blank">TinyPNG</a></li>
                                    <li><a href="https://squoosh.app" target="_blank">Squoosh (Google)</a></li>
                                    <li><a href="https://convertio.co" target="_blank">Convertio</a></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Software Desktop:</h6>
                                <ul>
                                    <li>GIMP (Gratis)</li>
                                    <li>Photoshop</li>
                                    <li>Paint.NET</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="index.php" class="btn btn-primary">
                                <i class="bi bi-arrow-left"></i> Kembali ke Admin Panel
                            </a>
                            <a href="../debug.php" class="btn btn-outline-secondary">
                                <i class="bi bi-tools"></i> Debug Page
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 