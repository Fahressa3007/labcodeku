<?php
// =======================================================
// CREATE USER - Domain Vault
// Hanya dipakai kalau Anda import database.sql manual.
// Script ini hanya insert user default (Nathan / domainku123).
// =======================================================
require_once 'config.php';

$USERNAME = 'Nathan';
$PASSWORD = 'domainku123';

header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html><html><head><title>Create User</title>
<style>
body{font-family:system-ui,sans-serif;max-width:640px;margin:40px auto;padding:24px;background:#f1f5f9;color:#0f172a}
.box{background:#fff;padding:28px;border-radius:14px;box-shadow:0 4px 20px rgba(0,0,0,.05)}
h1{margin-top:0;color:#4f46e5}.ok{color:#16a34a}.err{color:#dc2626}.warn{color:#d97706}
code{background:#f1f5f9;padding:2px 6px;border-radius:4px}
a.btn{display:inline-block;background:#4f46e5;color:#fff;padding:10px 18px;border-radius:8px;text-decoration:none;margin-top:14px;font-weight:600}
</style></head><body><div class='box'>";
echo "<h1>👤 Create Default User</h1>";

try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$USERNAME]);

    if ($stmt->fetch()) {
        echo "<p class='warn'>⚠ User <code>$USERNAME</code> sudah ada. Tidak ada perubahan.</p>";
    } else {
        $hash = password_hash($PASSWORD, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$USERNAME, $hash]);
        echo "<p class='ok'>✓ User berhasil dibuat</p>";
        echo "<p>Username: <code>$USERNAME</code><br>Password: <code>$PASSWORD</code></p>";
    }

    echo "<p class='err'><b>⚠ Penting:</b> Hapus file <code>create_user.php</code> setelah selesai.</p>";
    echo "<a class='btn' href='index.php'>Buka Aplikasi →</a>";

} catch (PDOException $e) {
    echo "<p class='err'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Pastikan database <code>domain_vault</code> dan tabel <code>users</code> sudah ada (import <code>database.sql</code> dulu via phpMyAdmin).</p>";
}

echo "</div></body></html>";
