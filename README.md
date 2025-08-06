# 🥐 E-Commerce Toko Roti "Bake & Joy"

Aplikasi e-commerce lengkap untuk toko roti yang dibangun dengan PHP, MySQL, dan Bootstrap 5. Aplikasi ini menyediakan antarmuka pengguna yang modern dan mudah digunakan untuk berbelanja berbagai macam roti, kue, dan pastry, serta panel admin untuk mengelola produk dan stok.

## 🚀 Fitur Utama

### 🛒 Untuk Pelanggan
- **Katalog Produk**: Menampilkan berbagai macam roti, kue, dan pastry dengan gambar berkualitas tinggi
- **Detail Produk**: Halaman detail untuk setiap produk dengan deskripsi lengkap dan harga
- **Keranjang Belanja**: Fitur keranjang untuk mengelola produk yang akan dibeli
- **Checkout**: Proses checkout yang sederhana dan aman
- **Filter & Pencarian**:
  - Filter berdasarkan kategori (Roti, Kue, Pastry)
  - Pencarian produk berdasarkan nama
  - Pengurutan berdasarkan harga (termurah/termahal)
- **Responsive Design**: Tampilan yang optimal di semua perangkat (desktop, tablet, mobile)

### 👨‍💻 Untuk Admin
- **Manajemen Produk**:
  - Tambah, edit, dan hapus produk
  - Kelola stok produk
  - Unggah gambar produk
- **Manajemen Kategori**: Kelola kategori produk
- **Keamanan**: Sistem login/logout untuk admin

## 📁 Struktur Folder

```
ecommerce-toko-roti/
├── assets/                      # Folder untuk menyimpan gambar produk
├── config/
│   └── database.php            # Konfigurasi koneksi database
├── admin/                      # Panel Admin
│   ├── index.php               # Dashboard admin
│   ├── login.php               # Halaman login admin
│   ├── logout.php              # Proses logout admin
│   ├── proses_produk.php       # Proses CRUD produk
│   ├── update_admin_password.php # Ganti password admin
│   └── image_formats.php       # Validasi format gambar
├── api/                        # Endpoint API
│   ├── delete_product.php      # Hapus produk
│   ├── get_categories.php      # Ambil daftar kategori
│   ├── get_product.php         # Ambil detail produk
│   ├── get_products.php        # Ambil daftar produk
│   └── update_product.php      # Update data produk
├── cart.php                    # Halaman keranjang belanja
├── checkout.php                # Halaman checkout
├── database.sql                # Skema dan data awal database
├── detail_produk.php           # Halaman detail produk
├── index.php                   # Halaman utama e-commerce
├── setup_database.php          # Script setup database
├── view_image.php              # Menampilkan gambar produk
├── debug.php                   # File untuk keperluan debugging
├── INSTALL.md                  # Panduan instalasi
├── TROUBLESHOOTING.md          # Panduan pemecahan masalah
└── README.md                   # Dokumentasi ini
```

## 🛠️ Persyaratan Sistem

- PHP 7.4 atau lebih baru
- MySQL 5.7 atau lebih baru
- Web server (Apache/Nginx)
- Ekstensi PHP yang diperlukan: PDO, MySQLi, GD Library

## 🚀 Instalasi

### 1. Persyaratan Sistem
- PHP 7.4 atau lebih baru
- MySQL 5.7 atau lebih baru
- Web server (Apache/Nginx)
- Ekstensi PHP: PDO, MySQLi, GD Library

### 2. Setup Awal

1. Clone repositori ini ke direktori web server Anda
2. Pastikan web server memiliki akses tulis ke folder `assets/`:
   ```bash
   chmod -R 755 assets/
   ```
3. Pastikan folder `config/` dapat ditulisi oleh web server (hanya untuk instalasi pertama)

### 3. Setup Database

#### Opsi 1: Menggunakan Setup Otomatis (Direkomendasikan)
1. Buka `setup_config.php` di browser Anda (contoh: `http://localhost/ecommerce-toko-roti/setup_config.php`)
2. Ikuti petunjuk di layar untuk mengisi detail database
3. Setelah konfigurasi selesai, buka `setup_database.php` di browser untuk membuat tabel database

#### Opsi 2: Setup Manual
1. Buat database MySQL baru
2. Import file `database.sql` ke database Anda:
   ```bash
   mysql -u username -p toko_roti_db < database.sql
   ```
3. Salin file `config/database.example.php` ke `config/database.php`
4. Edit `config/database.php` dan sesuaikan dengan kredensial database Anda

### 4. Verifikasi Instalasi
1. Buka aplikasi di browser (contoh: `http://localhost/ecommerce-toko-roti`)
2. Jika ada pesan error, periksa file `error.log` web server Anda

### 5. Keamanan Tambahan
1. Setelah instalasi selesai, pastikan untuk:
   - Mengubah password admin default
   - Menghapus file `setup_config.php` dan `setup_database.php` dari server produksi
   - Memastikan file `config/database.php` tidak dapat diakses langsung dari web
     ```apache
     <Files "database.php">
         Order allow,deny
         Deny from all
     </Files>
     ```

## 🔑 Login Admin

Default kredensial admin:
- **URL**: `/admin/login.php`
- **Username**: admin
- **Password**: admin123

**Penting**: Ganti password default setelah login pertama kali!

## 🌐 Cara Menggunakan

### Sebagai Pengunjung
1. Jelajahi katalog produk di halaman utama
2. Klik pada produk untuk melihat detail
3. Tambahkan produk ke keranjang
4. Lakukan checkout

### Sebagai Admin
1. Login ke panel admin
2. Kelola produk (tambah, edit, hapus)
3. Pantau dan kelola stok produk
4. Lihat statistik penjualan

## 🛠️ Troubleshooting

Jika Anda mengalami masalah, lihat file `TROUBLESHOOTING.md` untuk panduan pemecahan masalah umum.

## 🔒 Keamanan

- Selalu gunakan password yang kuat untuk akun admin
- Simpan file konfigurasi di luar direktori web root
- Update sistem secara berkala
- Backup database secara rutin

## 📱 Cara Penggunaan

### Halaman Utama (index.php)
- **Filter Kategori**: Pilih kategori produk (Roti, Kue, Pastry)
- **Urutkan Harga**: Urutkan produk berdasarkan harga (terendah/tertinggi)
- **Pencarian**: Cari produk berdasarkan nama

### Admin Panel (admin/index.php)
- **Lihat Semua Produk**: Tabel berisi semua produk dengan informasi lengkap
- **Tambah Produk**: Modal form untuk menambah produk baru dengan dukungan JPG, PNG, WebP
- **Format Gambar**: Halaman informasi format gambar yang didukung
- **Edit/Hapus**: Tombol untuk edit dan hapus produk (akan dikembangkan lebih lanjut)

## 🎨 Teknologi yang Digunakan

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework CSS**: Bootstrap 5.3.3
- **Icons**: Bootstrap Icons
- **Fonts**: Google Fonts (Poppins, Playfair Display)

## 🔧 API Endpoints

### GET /api/get_products.php
Mengambil data produk dengan filter

**Parameters:**
- `kategori` (optional): Filter berdasarkan kategori
- `pencarian` (optional): Pencarian berdasarkan nama produk
- `urutan` (optional): Urutan harga (default, rendah-tinggi, tinggi-rendah)

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "nama": "Classic Croissant",
            "deskripsi": "Croissant mentega klasik...",
            "harga": "18000.00",
            "harga_formatted": "Rp 18.000",
            "gambar": "assets/classic_croissant.jpg",
            "kategori": "Pastry",
            "stok": 50
        }
    ],
    "total": 12
}
```

### GET /api/get_categories.php
Mengambil daftar kategori

**Response:**
```json
{
    "status": "success",
    "data": [
        {"kategori": "Roti"},
        {"kategori": "Kue"},
        {"kategori": "Pastry"}
    ]
}
```

## 🚀 Pengembangan Selanjutnya

### ✅ Sudah Diimplementasikan
- [x] Sistem login admin
  - Halaman login dengan validasi
  - Sistem session management
  - Logout functionality

- [x] Fitur edit dan hapus produk
  - Form edit produk dengan preview gambar
  - Validasi input
  - Hapus produk dengan konfirmasi

- [x] Sistem keranjang belanja
  - Tambah/hapus produk dari keranjang
  - Update jumlah item
  - Hitung total belanja

- [x] Sistem checkout dasar
  - Form data pelanggan
  - Ringkasan pesanan
  - Proses penyimpanan pesanan

### 🚧 Dalam Pengembangan
- [ ] Sistem pembayaran terintegrasi
  - Integrasi dengan payment gateway
  - Konfirmasi pembayaran
  - Status pembayaran

- [ ] Dashboard admin dengan statistik
  - Grafik penjualan
  - Ringkasan pendapatan
  - Produk terlaris

### ⏳ Fitur yang Akan Datang
- [ ] Sistem review dan rating produk
  - Form ulasan pelanggan
  - Tampilan rating bintang
  - Moderasi ulasan

- [ ] Fitur wishlist
  - Simpan produk favorit
  - Notifikasi diskon produk wishlist
  - Berbagi wishlist

- [ ] Notifikasi email
  - Konfirmasi pesanan
  - Update status pengiriman
  - Promo dan diskon

## 📝 Lisensi

Project ini dibuat untuk tujuan pembelajaran. Silakan gunakan dan modifikasi sesuai kebutuhan.

## 📸 Screenshoot
<img width="1920" height="1080" alt="Screenshot from 2025-08-06 22-34-02" src="https://github.com/user-attachments/assets/87894e49-c0e1-4e4e-b31e-57ca24d6d75f" />
<img width="1920" height="1080" alt="Screenshot from 2025-08-06 22-34-33" src="https://github.com/user-attachments/assets/447e377e-a299-4403-bfc0-6f74e6c4d6bb" />
<img width="1920" height="1080" alt="Screenshot from 2025-08-06 22-34-55" src="https://github.com/user-attachments/assets/31d4b26c-3148-4697-b465-ae06fbb200c8" />
<img width="1920" height="1080" alt="Screenshot from 2025-08-06 22-35-09" src="https://github.com/user-attachments/assets/32b29857-0ddb-44e0-8fd7-200a8455c56b" />
<img width="1920" height="1080" alt="Screenshot from 2025-08-06 22-35-25" src="https://github.com/user-attachments/assets/54da56fe-e49f-47a2-b811-3095df3e37c5" />


## 👨‍💻 Developer

Dibuat dengan ❤️ oleh gurun.setiawan@gmail.com menggunakan PHP, MySQL, dan Bootstrap 5.

---

**Note**: Pastikan web server (Apache/Nginx) dan MySQL server sudah berjalan sebelum mengakses aplikasi. 
