-- Buat database baru
CREATE DATABASE IF NOT EXISTS smart_library;
USE smart_library;

-- 1. Tabel Users (Admin & Siswa)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'siswa') DEFAULT 'siswa',
    nis VARCHAR(20) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Tabel Kategori Buku
CREATE TABLE kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

-- 3. Tabel Data Buku
CREATE TABLE buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    kategori_id INT,
    stok INT DEFAULT 0,
    tahun_terbit INT,
    cover VARCHAR(255) DEFAULT 'https://placehold.co/100x150',
    deskripsi TEXT,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 4. Tabel Peminjaman & Pengembalian
CREATE TABLE peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    buku_id INT NOT NULL,
    tanggal_pinjam DATE NOT NULL,
    tanggal_jatuh_tempo DATE NOT NULL,
    tanggal_kembali DATE NULL,
    status ENUM('dipinjam', 'dikembalikan', 'terlambat') DEFAULT 'dipinjam',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (buku_id) REFERENCES buku(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Tabel Ebook
CREATE TABLE ebook (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    file_url VARCHAR(255) NULL,
    cover VARCHAR(255) DEFAULT 'https://placehold.co/100x150'
) ENGINE=InnoDB;

-- 6. Tabel Transaksi Pembelian Ebook
CREATE TABLE transaksi_ebook (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    ebook_id INT NOT NULL,
    tanggal_beli DATE NOT NULL,
    status_pembayaran ENUM('lunas', 'pending') DEFAULT 'lunas',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (ebook_id) REFERENCES ebook(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 7. Tabel Notifikasi
CREATE TABLE IF NOT EXISTS notifikasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    pesan TEXT NOT NULL,
    tipe ENUM('info', 'warning', 'error', 'success') DEFAULT 'info',
    dibaca TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================================
-- DATA DUMMY (Untuk Testing & Presentasi)
-- ==========================================
INSERT INTO users (nama, email, password, role, nis) VALUES
('Admin Perpus', 'admin@smkpgri1.sch.id', 'admin123', 'admin', NULL),
('Siswa Contoh', 'siswa@smkpgri1.sch.id', 'siswa123', 'siswa', '24050974035');

INSERT INTO kategori (nama_kategori) VALUES
('Pemrograman'), ('Desain'), ('Mapel'), ('Novel'), ('Sains');

INSERT INTO buku (judul, penulis, kategori_id, stok, tahun_terbit) VALUES
('Pemrograman Web Modern', 'Budi Santoso', 1, 5, 2023),
('Desain Grafis Dasar', 'Ani Wijaya', 2, 3, 2022),
('Fisika Kelas 12', 'Prof. Andi', 3, 10, 2021),
('Bahasa Indonesia', 'Tim Guru', 3, 8, 2020),
('Algoritma & Struktur Data', 'Dewi Sartika', 1, 4, 2024);

INSERT INTO ebook (judul, penulis, harga) VALUES
('Panduan UAS Pemrograman', 'Tim IT', 25000.00),
('Novel Laskar Pelangi', 'Andrea Hirata', 45000.00);
