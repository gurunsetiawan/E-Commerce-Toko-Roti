# 🔧 Troubleshooting Guide

## 🚨 Masalah yang Sering Ditemui

### 1. Error "Gambar produk wajib diupload"

**Penyebab:**
- File tidak terupload dengan benar
- Tipe file tidak didukung
- Ukuran file terlalu besar
- Permission folder tidak tepat

**Solusi:**
1. Pastikan file yang diupload adalah JPG, PNG, atau WebP
2. Ukuran file maksimal 2MB
3. Cek permission folder assets: `ls -la assets/`
4. Akses debug page: http://localhost:8000/debug.php

### 2. Error 404 untuk Chrome DevTools

**Penyebab:** Normal, bukan masalah aplikasi
- Chrome DevTools mencari file yang tidak ada
- Tidak mempengaruhi fungsi aplikasi

**Solusi:** Abaikan error ini, tidak perlu diperbaiki

### 3. Produk tidak tersimpan ke database

**Penyebab:**
- Koneksi database gagal
- Query SQL error
- Permission database tidak tepat

**Solusi:**
1. Cek koneksi database di `config/database.php`
2. Jalankan `php setup_database.php`
3. Cek error log di debug page

### 4. Gambar tidak muncul

**Penyebab:**
- Path gambar salah
- File tidak terupload dengan benar
- Permission folder tidak tepat

**Solusi:**
1. Cek apakah file ada di folder `assets/`
2. Cek permission folder: `chmod 755 assets/`
3. Pastikan path gambar benar di database

## 🔍 Cara Debug

### 1. Akses Debug Page
```
http://localhost:8000/debug.php
```

Debug page akan menampilkan:
- ✅ Konfigurasi PHP
- ✅ Permission folder
- ✅ Koneksi database
- ✅ Error log terbaru
- ✅ Test upload file

### 2. Cek Error Log
```bash
# Cek error log PHP
tail -f /var/log/apache2/error.log
# atau
tail -f /var/log/nginx/error.log
```

### 3. Test Upload Manual
```bash
# Test upload file ke assets
curl -X POST -F "file=@test.jpg" http://localhost:8000/debug.php
```

## 🛠️ Langkah Perbaikan

### Step 1: Cek Konfigurasi
1. Akses http://localhost:8000/debug.php
2. Pastikan semua item berwarna hijau ✅
3. Jika ada yang merah ❌, perbaiki sesuai petunjuk

### Step 2: Test Upload
1. Buka debug page
2. Upload file gambar test
3. Pastikan upload berhasil

### Step 3: Coba Tambah Produk
1. Buka http://localhost:8000/admin/
2. Klik "Tambah Produk"
3. Isi semua field dengan benar
4. Upload gambar (JPG/PNG, max 2MB)
5. Klik "Simpan Produk"

### Step 4: Cek Database
```sql
-- Cek apakah produk tersimpan
SELECT * FROM produk ORDER BY id DESC LIMIT 5;
```

## 📋 Checklist Troubleshooting

- [ ] PHP version 7.4+
- [ ] MySQL server running
- [ ] Database `toko_roti_db` exists
- [ ] Table `produk` exists
- [ ] Folder `assets/` writable
- [ ] File upload enabled in PHP
- [ ] Upload max filesize > 2MB
- [ ] Post max size > 2MB

## 🎯 Solusi Cepat

### Jika upload gambar gagal:
1. Pastikan file JPG/PNG/WebP
2. Ukuran < 2MB
3. Cek permission folder assets
4. Restart PHP server

### Jika database error:
1. Jalankan `php setup_database.php`
2. Cek kredensial di `config/database.php`
3. Pastikan MySQL running

### Jika produk tidak muncul:
1. Refresh halaman
2. Cek apakah ada di database
3. Cek error log

## 📞 Bantuan Lebih Lanjut

Jika masalah masih berlanjut:
1. Screenshot error message
2. Cek debug page
3. Share error log
4. Cek konfigurasi server

---

**Note:** Error 404 untuk Chrome DevTools adalah normal dan tidak perlu diperbaiki. 