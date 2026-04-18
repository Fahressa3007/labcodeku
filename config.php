<?php
// =======================================================
// KONFIGURASI DATABASE - Domain Vault
// =======================================================
// Sesuaikan jika pengaturan XAMPP Anda berbeda
$DB_HOST = 'localhost';
$DB_PORT = 3307;            // Ganti ke 3306 jika MySQL Anda default
$DB_NAME = 'domain_vault';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage() .
        "<br><br>Pastikan Apache & MySQL di XAMPP sudah berjalan, " .
        "dan jalankan <a href='install.php'>install.php</a> terlebih dahulu.");
}

// Mulai session untuk autentikasi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
