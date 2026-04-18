<?php
// =======================================================
// INSTALLER - Domain Vault
// Jalankan sekali untuk membuat database & user default
// =======================================================

$DB_HOST = 'localhost';
$DB_PORT = 3307;            // Ganti ke 3306 jika MySQL Anda default
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'domain_vault';

$DEFAULT_USERNAME = 'Nathan';
$DEFAULT_PASSWORD = 'domainku123';

header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html><html><head><title>Installer Domain Vault</title>
<style>
body{font-family:system-ui,sans-serif;max-width:640px;margin:40px auto;padding:24px;background:#f1f5f9;color:#0f172a}
.box{background:#fff;padding:28px;border-radius:14px;box-shadow:0 4px 20px rgba(0,0,0,.05)}
h1{margin-top:0;color:#4f46e5}
.ok{color:#16a34a}.err{color:#dc2626}.warn{color:#d97706}
code{background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:.9em}
a.btn{display:inline-block;background:#4f46e5;color:#fff;padding:10px 18px;border-radius:8px;text-decoration:none;margin-top:14px;font-weight:600}
</style></head><body><div class='box'>";
echo "<h1>🔧 Installer Domain Vault</h1>";

try {
    $pdo = new PDO("mysql:host=$DB_HOST;port=$DB_PORT;charset=utf8mb4", $DB_USER, $DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "<p class='ok'>✓ Terhubung ke MySQL</p>";

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$DB_NAME` 
                CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p class='ok'>✓ Database <code>$DB_NAME</code> siap</p>";

    $pdo->exec("USE `$DB_NAME`");

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "<p class='ok'>✓ Tabel <code>users</code> siap</p>";

    $pdo->exec("CREATE TABLE IF NOT EXISTS vault_data (
        id INT AUTO_INCREMENT PRIMARY KEY,
        domain VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_domain (domain)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "<p class='ok'>✓ Tabel <code>vault_data</code> siap</p>";

    // Buat user default jika belum ada
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$DEFAULT_USERNAME]);

    if (!$stmt->fetch()) {
        $hash = password_hash($DEFAULT_PASSWORD, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$DEFAULT_USERNAME, $hash]);
        echo "<p class='ok'>✓ User default dibuat: <code>$DEFAULT_USERNAME</code> / <code>$DEFAULT_PASSWORD</code></p>";
    } else {
        echo "<p class='warn'>⚠ User <code>$DEFAULT_USERNAME</code> sudah ada, dilewati.</p>";
    }

    echo "<h2 class='ok'>✅ Instalasi Selesai!</h2>";
    echo "<p><b class='err'>⚠ Penting:</b> Hapus file <code>install.php</code> setelah ini untuk keamanan.</p>";
    echo "<a class='btn' href='index.php'>Buka Aplikasi →</a>";

} catch (PDOException $e) {
    echo "<p class='err'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Pastikan XAMPP (Apache & MySQL) sudah berjalan.</p>";
}

echo "</div></body></html>";
