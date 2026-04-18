# 🔐 Domain Vault — Panduan Instalasi (XAMPP)

Aplikasi penyimpan kredensial domain dengan database MySQL, dark/light mode, pencarian, edit, copy password, dan generator password.

## 📦 Struktur File

```
domain-vault/
├── config.php      # Koneksi database
├── install.php     # Script setup otomatis (jalankan sekali)
├── api.php         # Backend API (login, CRUD)
├── index.php       # UI utama (login + dashboard)
└── README.md       # File ini
```

## 🚀 Cara Instalasi

### 1. Salin folder ke XAMPP
Copy seluruh folder `domain-vault` ke dalam:
```
C:\xampp\htdocs\domain-vault
```

### 2. Jalankan XAMPP
Buka **XAMPP Control Panel** dan **Start** layanan:
- ✅ Apache
- ✅ MySQL

### 3. Setup Database (Otomatis)
Buka di browser:
```
http://localhost/domain-vault/install.php
```
Script ini akan otomatis:
- Membuat database `domain_vault`
- Membuat tabel `users` dan `vault_data`
- Menambahkan user default: **Nathan** / **domainku123**

### 4. Hapus installer (PENTING untuk keamanan)
Setelah setup berhasil, **hapus file `install.php`**.

### 5. Gunakan Aplikasi
Buka:
```
http://localhost/domain-vault/index.php
```

Login dengan:
- **Username:** `Nathan`
- **Password:** `domainku123`

---

## ✨ Fitur

| Fitur | Keterangan |
|---|---|
| 🌗 Dark / Light Mode | Toggle di pojok kanan atas, tersimpan di `localStorage` |
| 🔍 Pencarian | Filter berdasarkan nama domain atau email |
| ✏️ Edit Kredensial | Klik ikon pensil di kartu |
| 📋 Copy Password | Satu klik salin ke clipboard |
| 🎲 Generate Password | Buat password acak 16 karakter |
| 👁️ Show / Hide Password | Toggle tampilan password |
| 🔐 Password di-hash | Password login user disimpan dengan `password_hash()` (bcrypt) |
| 📱 Responsif | Tampilan menyesuaikan mobile & desktop |

---

## ⚙️ Konfigurasi Database

Jika pengaturan MySQL Anda berbeda, edit `config.php`:

```php
$DB_HOST = 'localhost';
$DB_NAME = 'domain_vault';
$DB_USER = 'root';
$DB_PASS = '';       // Kosong untuk XAMPP default
```

---

## 🔒 Catatan Keamanan

- **Password user login** (tabel `users`) aman karena di-hash dengan bcrypt.
- **Password kredensial** (tabel `vault_data`) disimpan **plain text** agar bisa ditampilkan kembali — sama seperti versi localStorage sebelumnya. Untuk penggunaan produksi, pertimbangkan enkripsi dengan master key.
- Selalu hapus `install.php` setelah instalasi.
- Jangan upload folder ini ke server publik tanpa HTTPS dan hardening tambahan.

---

## 🛠️ Troubleshooting

**"Koneksi database gagal"**  
→ Pastikan MySQL di XAMPP aktif dan jalankan `install.php` dulu.

**Halaman kosong / error 500**  
→ Aktifkan error di `php.ini` atau cek `C:\xampp\apache\logs\error.log`.

**User default tidak bisa login**  
→ Hapus row di tabel `users` via phpMyAdmin, lalu akses ulang `install.php`.
