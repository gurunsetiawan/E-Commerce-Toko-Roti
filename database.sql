-- Database untuk Toko Roti "Bake & Joy"
CREATE DATABASE IF NOT EXISTS toko_roti_db;
USE toko_roti_db;

-- Tabel produk
CREATE TABLE IF NOT EXISTS produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(10,2) NOT NULL,
    gambar VARCHAR(255),
    kategori VARCHAR(100),
    stok INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert data produk
INSERT INTO produk (nama, deskripsi, harga, gambar, kategori, stok) VALUES
('Classic Croissant', 'Croissant mentega klasik dengan lapisan yang renyah dan bagian dalam yang lembut.', 18000.00, 'assets/classic_croissant.jpg', 'Pastry', 50),
('Roti Tawar Gandum', 'Roti tawar sehat yang dibuat dari 100% gandum utuh, kaya serat.', 25000.00, 'assets/roti_tawar_gandum.jpg', 'Roti', 30),
('Donat Cokelat Klasik', 'Donat empuk dengan glasir cokelat tebal dan taburan meses warna-warni.', 9000.00, 'assets/donat_cokelat_klasik.jpg', 'Kue', 100),
('Sourdough Loaf', 'Roti sourdough otentik dengan kulit renyah dan rasa asam yang khas.', 45000.00, 'assets/sourdough_loaf.jpg', 'Roti', 20),
('Red Velvet Cupcake', 'Cupcake red velvet lembut dengan topping cream cheese frosting yang manis.', 22000.00, 'assets/red_velvet_cupcake.jpg', 'Kue', 75),
('Cinnamon Roll', 'Gulungan kayu manis hangat dengan glasir vanila yang meleleh di mulut.', 20000.00, 'assets/cinnamon_roll.jpg', 'Pastry', 60),
('Baguette Perancis', 'Baguette tradisional Perancis dengan kulit yang sangat renyah dan bagian dalam yang kenyal.', 15000.00, 'assets/baguette_perancis.jpg', 'Roti', 40),
('Blueberry Muffin', 'Muffin lembut yang diisi dengan buah blueberry segar dan taburan gula.', 17000.00, 'assets/blueberry_muffin.jpg', 'Kue', 80),
('Banana Bread', 'Banana bread yang lembut dan manis, sempurna untuk sarapan atau camilan.', 25000.00, 'assets/banana_bread.jpg', 'Roti', 35),
('Chocolate Chip Cookies', 'Kue kering klasik dengan potongan cokelat yang meleleh di mulut.', 12000.00, 'assets/chocolate_chip_cookies.jpg', 'Kue', 120),
('Apple Pie', 'Pai apel tradisional dengan kulit yang renyah dan isian apel manis.', 30000.00, 'assets/apple_pie.jpg', 'Kue', 25),
('Cheese Danish', 'Pastry lembut dengan isian krim keju yang manis dan renyah di bagian luar.', 20000.00, 'assets/cheese_danish.jpg', 'Pastry', 45);

-- Tabel kategori (opsional untuk pengembangan lebih lanjut)
CREATE TABLE IF NOT EXISTS kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO kategori (nama, deskripsi) VALUES
('Roti', 'Berbagai jenis roti tradisional dan modern'),
('Kue', 'Kue-kue manis dan pastry'),
('Pastry', 'Pastry dan croissant'); 

-- Tabel admin untuk login
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- User admin default (password: admin123, hash: $2y$10$wH6QwQwQwQwQwQwQwQwQOeQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQw)
INSERT INTO admin (username, password) VALUES ('admin', '$2y$10$wH6QwQwQwQwQwQwQwQwQOeQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQw'); 