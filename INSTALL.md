# 🚀 Cara Menjalankan E-Commerce Toko Roti

## 📋 Prerequisites

Sebelum menjalankan aplikasi, pastikan Anda memiliki:
- PHP 7.4 atau lebih baru
- MySQL/MariaDB
- Web browser

## 🛠️ Cara Instalasi & Menjalankan

### 1. Setup Database

Jalankan file setup database:
```bash
cd "Tugas 5/ecommerce-toko-roti"
php setup_database.php
```

Atau manual:
```bash
# Import database.sql ke MySQL
mysql -u root -p < database.sql
```

### 2. Menjalankan dengan PHP Built-in Server

```bash
# Dari folder project
cd "Tugas 5/ecommerce-toko-roti"
php -S localhost:8000
```

### 3. Akses Aplikasi

Buka browser dan akses:
- **Halaman Utama:** http://localhost:8000
- **Admin Panel:** http://localhost:8000/admin/
- **Detail Produk:** http://localhost:8000/detail_produk.php?id=1

## 🔧 Konfigurasi Database

Jika menggunakan kredensial database yang berbeda, edit file `config/database.php`:

```php
$host = 'localhost';
$dbname = 'toko_roti_db';
$username = 'root';  // Sesuaikan dengan username Anda
$password = '';       // Sesuaikan dengan password Anda
```

## 📁 Struktur File

```
ecommerce-toko-roti/
├── index.php              # Halaman utama (BUKAN index.html)
├── detail_produk.php      # Halaman detail produk
├── admin/
│   ├── index.php          # Panel admin
│   └── proses_produk.php  # Proses tambah produk
├── api/
│   ├── get_products.php   # API produk
│   └── get_categories.php # API kategori
├── config/
│   └── database.php       # Konfigurasi database
├── assets/                # Gambar produk
├── database.sql           # File SQL
├── setup_database.php     # Setup otomatis
└── README.md              # Dokumentasi
```

## 🤔 Mengapa Tidak Ada index.html?

Aplikasi ini menggunakan **PHP** bukan HTML statis karena:

### ✅ Keunggulan PHP vs HTML Statis:

| Fitur | HTML Statis | PHP Dinamis |
|-------|-------------|-------------|
| Data | Hardcoded dalam JavaScript | Dari database MySQL |
| Filter | Client-side JavaScript | Server-side + AJAX |
| Admin Panel | Tidak ada | Lengkap dengan CRUD |
| Keamanan | Tidak ada validasi server | Validasi server-side |
| Skalabilitas | Terbatas | Mudah dikembangkan |

### 🔄 Contoh Perbedaan:

**HTML Statis (toko_roti.html):**
```html
<script>
const produkData = [
    {nama: "Croissant", harga: 18000}, // Data hardcoded
    // ... data statis
];
</script>
```

**PHP Dinamis (index.php):**
```php
<?php
// Data dari database secara real-time
$stmt = $pdo->query("SELECT * FROM produk");
$produk_list = $stmt->fetchAll();
?>
```

## 🎯 Fitur yang Bisa Diakses:

### Halaman Utama (http://localhost:8000)
- ✅ Tampilan produk dengan Bootstrap 5
- ✅ Filter kategori (Roti, Kue, Pastry)
- ✅ Urutkan harga (terendah/tertinggi)
- ✅ Pencarian produk
- ✅ Statistik dashboard
- ✅ Link ke detail produk

### Admin Panel (http://localhost:8000/admin/)
- ✅ Dashboard dengan statistik
- ✅ Tabel semua produk
- ✅ Tambah produk baru dengan dukungan JPG, PNG, WebP
- ✅ Upload gambar dengan validasi format
- ✅ Halaman informasi format gambar
- ✅ Pesan sukses/error

### Detail Produk (http://localhost:8000/detail_produk.php?id=1)
- ✅ Informasi lengkap produk
- ✅ Gambar besar
- ✅ Produk terkait
- ✅ Breadcrumb navigation

## 🚨 Troubleshooting

### Error: "Connection refused"
- Pastikan MySQL server berjalan
- Cek kredensial database di `config/database.php`

### Error: "Database not found"
- Jalankan `php setup_database.php`
- Atau import manual `database.sql`

### Gambar tidak muncul
- Pastikan folder `assets/` berisi gambar produk
- Cek permission folder

### Port 8000 sudah digunakan
- Ganti port: `php -S localhost:8080`
- Atau `php -S localhost:3000`

## 🎉 Selamat! Aplikasi Siap Digunakan

Setelah menjalankan langkah-langkah di atas, Anda akan memiliki:
- ✅ E-commerce toko roti yang lengkap
- ✅ Admin panel untuk mengelola produk
- ✅ Database dengan 12 produk sample
- ✅ Tampilan responsif dengan Bootstrap 5

**Akses sekarang:** http://localhost:8000 