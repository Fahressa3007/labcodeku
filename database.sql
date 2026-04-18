-- =====================================================
-- Domain Vault - Database Schema
-- =====================================================
-- Cara import:
--   1. Buka http://localhost/phpmyadmin
--   2. Klik tab "Import"
--   3. Pilih file database.sql ini
--   4. Klik "Go" / "Kirim"
--
-- Setelah import berhasil, jalankan create_user.php SEKALI
-- untuk membuat user default (Nathan / domainku123),
-- karena password perlu di-hash bcrypt oleh PHP.
-- =====================================================

-- 1. Buat database
CREATE DATABASE IF NOT EXISTS `domain_vault` 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE `domain_vault`;

-- =====================================================
-- 2. Tabel users (untuk login)
-- =====================================================
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL COMMENT 'bcrypt hash via password_hash()',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 3. Tabel vault_data (untuk menyimpan kredensial domain)
-- =====================================================
CREATE TABLE IF NOT EXISTS `vault_data` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `domain` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SELESAI - Schema sudah siap.
-- Langkah berikutnya: buka http://localhost/domain-vault/create_user.php
-- =====================================================
