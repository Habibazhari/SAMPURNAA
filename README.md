# SAMPURNA
> **"Ubah Sampah Jadi Keuntungan"** — Platform digital yang menghubungkan warga, petugas, dan bank sampah dalam satu ekosistem pengelolaan sampah yang cerdas dan transparan.
# Website Name 
SAMPURNA (Pengolahan Sampah dengan Sempurna)

---

# DESKRIPSI
**SAMPURNA** adalah aplikasi web pengelolaan sampah berbasis komunitas yang dibangun untuk memudahkan warga dalam menyetorkan sampah anorganik ke bank sampah mitra dan mendapatkan imbalan berupa uang. Platform ini mendukung tiga peran pengguna — warga (user), petugas lapangan, dan administrator — dengan alur kerja yang jelas dari pengajuan setoran hingga verifikasi akhir.
Sistem ini cocok digunakan oleh kelompok bank sampah, pemerintah daerah, atau komunitas lingkungan yang ingin mendigitalkan proses pencatatan dan transaksi sampah secara terstruktur.

---

# Fitur Utama
## ✨ Fitur Utama

### 🌐 Halaman Publik (tanpa login)
- **Beranda** — Hero section, statistik real-time (total warga, kg sampah, transaksi, bank mitra), cara kerja platform, dan artikel terbaru
- **Direktori Bank Sampah** — Peta interaktif (Leaflet.js) dan kartu informasi seluruh bank sampah mitra aktif beserta alamat, jam buka, dan nomor telepon
- **Artikel Edukasi** — Konten seputar lingkungan dan pengelolaan sampah yang ditulis oleh admin
- **Panduan Pemilahan** — Panduan lengkap cara memilah sampah berdasarkan kategori (organik, anorganik, B3, dan residu)

---

### 👤 Panel Warga (User)
- **Dashboard Personal** — Statistik pribadi: total transaksi selesai, total berat sampah disetorkan, dan total uang diperoleh
- **Setor Sampah Baru** — Form multi-item untuk memilih bank sampah, jenis sampah, dan berat; harga otomatis dihitung dari harga per kg yang sudah dikonfigurasi admin
- **Riwayat Transaksi** — Daftar seluruh transaksi dengan status dan detail lengkap; transaksi berstatus *pending* bisa dibatalkan oleh user
- **Profil** — Edit data diri dan foto profil

---

### 🚛 Panel Petugas
- **Dashboard Petugas** — Ringkasan transaksi aktif yang perlu diproses
- **Kelola Transaksi** — Lihat semua transaksi masuk, update status dari *pending* → *diproses* → *selesai* atau *dibatalkan*

---

### ⚙️ Panel Admin
- **Dashboard Admin** — Statistik global: total user, transaksi, bank sampah aktif, dan artikel
- **Kelola User** — Daftar seluruh akun beserta detail riwayat transaksi per user
- **Kelola Bank Sampah** — Tambah, edit, dan nonaktifkan bank sampah mitra
- **Kelola Jenis Sampah** — Atur kategori, nama, dan harga per kg untuk setiap jenis sampah
- **Kelola Artikel** — Buat, edit, dan hapus artikel edukasi dengan upload foto
- **Kelola Transaksi** — Pantau dan kelola semua transaksi dari seluruh user

---


# Site Map
sampurna/
├── admin/
│   ├── dashboard.php
│   ├── transaksi.php
│   ├── bank_sampah.php
│   ├── jenis_sampah.php
│   ├── artikel.php
│   ├── artikel_tambah.php
│   ├── artikel_edit.php
│   ├── users.php
│   ├── user_detail.php
│   └── sidebar_admin.php
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── script.js
│   ├── img/
│   │   └── hero.svg, logo.svg
│   └── uploads/
│       ├── artikel/
│       └── profil/
├── config/
│   ├── database.php
│   └── sampurna.sql
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── functions.php
├── petugas/
│   ├── dashboard.php
│   └── transaksi.php
├── index.php
├── login.php
├── register.php
├── logout.php
├── dashboard.php
├── transaksi.php
├── transaksi_baru.php
├── transaksi_detail.php
├── bank_sampah.php
├── artikel.php
├── artikel_detail.php
├── profil.php
└── panduan.php

---

## 🛠️ Tech Stack

### Frontend
| Teknologi | Versi | Kegunaan |
|-----------|-------|----------|
| **Tailwind CSS** | CDN v3 | Utility-first styling seluruh halaman |
| **Alpine.js** | v3.x CDN | Reactivity ringan (dropdown, mobile menu, toggle) |
| **AOS (Animate On Scroll)** | v2.3.1 | Animasi scroll pada elemen halaman |
| **Font Awesome** | v6.5.1 | Icon set lengkap di seluruh UI |
| **Plus Jakarta Sans** | Google Fonts | Tipografi utama |
| **Leaflet.js** | v1.9.4 | Peta interaktif di halaman Bank Sampah |

### Backend
| Teknologi | Versi | Kegunaan |
|-----------|-------|----------|
| **PHP** | 7.4+ / 8.x | Bahasa pemrograman server-side utama |
| **PDO (PHP Data Objects)** | Native | Abstraksi koneksi database yang aman (prepared statements) |
| **Session PHP** | Native | Manajemen autentikasi dan flash message |
| **MySQL** | 5.7+ / 8.x | Database relasional utama |

### Tools & Pendukung
| Teknologi | Kegunaan |
|-----------|----------|
| **XAMPP** | Local development environment |
| **phpMyAdmin** | Manajemen database via GUI |

---

## 🗂️ Struktur Direktori

```
sampurna/
├── admin/                  ← Panel admin (10 halaman)
│   ├── dashboard.php
│   ├── transaksi.php
│   ├── bank_sampah.php
│   ├── jenis_sampah.php
│   ├── artikel.php
│   ├── artikel_tambah.php
│   ├── artikel_edit.php
│   ├── users.php
│   ├── user_detail.php
│   └── sidebar_admin.php
├── assets/
│   ├── css/style.css       ← Custom CSS tambahan
│   ├── js/script.js        ← Custom JavaScript
│   └── img/                ← Gambar statis (SVG, logo)
├── config/
│   ├── database.php        ← Konfigurasi koneksi PDO + singleton getDB()
│   └── sampurna.sql        ← Skema dan data awal database
├── includes/
│   ├── header.php          ← HTML head, navbar, flash message
│   ├── footer.php          ← Footer + inisialisasi AOS
│   └── functions.php       ← Auth helpers, flash, format rupiah, pagination
├── petugas/                ← Panel petugas (2 halaman)
│   ├── dashboard.php
│   └── transaksi.php
├── uploads/
│   ├── artikel/            ← Foto thumbnail artikel
│   └── profil/             ← Foto profil user
├── index.php               ← Beranda publik
├── login.php               ← Form login
├── register.php            ← Form registrasi
├── logout.php              ← Hapus sesi & redirect
├── dashboard.php           ← Dashboard user
├── transaksi.php           ← Riwayat transaksi
├── transaksi_baru.php      ← Form setor sampah
├── transaksi_detail.php    ← Detail satu transaksi
├── bank_sampah.php         ← Direktori bank sampah + peta
├── artikel.php             ← Daftar artikel
├── artikel_detail.php      ← Detail artikel
├── profil.php              ← Edit profil user
└── panduan.php             ← Panduan pemilahan sampah
```

---

# ALAMAT
[http://localhost]
