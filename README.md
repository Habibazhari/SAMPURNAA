# ♻️ SAMPURNA
### Sistem Aplikasi Manajemen Pengelolaan Sampah Berbasis Komunitas

> **"Ubah Sampah Jadi Keuntungan"** — Platform digital yang menghubungkan warga, petugas, dan bank sampah dalam satu ekosistem pengelolaan sampah yang cerdas dan transparan.

---

## Deskripsi

**SAMPURNA** adalah aplikasi web pengelolaan sampah berbasis komunitas yang dibangun untuk memudahkan warga dalam menyetorkan sampah anorganik ke bank sampah mitra dan mendapatkan imbalan berupa uang. Platform ini mendukung tiga peran pengguna — warga (user), petugas lapangan, dan administrator — dengan alur kerja yang jelas dari pengajuan setoran hingga verifikasi akhir.

Sistem ini cocok digunakan oleh kelompok bank sampah, pemerintah daerah, atau komunitas lingkungan yang ingin mendigitalkan proses pencatatan dan transaksi sampah secara terstruktur.

---

## Fitur Utama

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

## Alur Transaksi

```
Warga buat transaksi → Status: PENDING
        ↓
Petugas terima & verifikasi → Status: DIPROSES
        ↓
Petugas konfirmasi fisik sampah
   ├── Sesuai   → Status: SELESAI  ✅
   └── Tidak    → Status: DIBATALKAN ❌

*) Warga juga bisa membatalkan sendiri selama masih PENDING
```

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
| **XAMPP / Laragon** | Local development environment |
| **phpMyAdmin** | Manajemen database via GUI |

---

## Struktur Direktori

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

# Site Map
```
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
```
---

## Cara Instalasi

### Prasyarat
- PHP >= 7.4
- MySQL >= 5.7
- Web server (Apache/Nginx) — disarankan XAMPP atau Laragon

### Langkah Instalasi

**1. Clone atau ekstrak project**
```bash
# Clone dari repository
git clone https://github.com/username/sampurna.git

# Atau ekstrak ZIP ke folder htdocs / www
```

**2. Pindahkan ke direktori web server**
```
XAMPP  : C:/xampp/htdocs/sampurna/
Laragon: C:/laragon/www/sampurna/
```

**3. Import database**
- Buka `phpMyAdmin` di browser
- Buat database baru bernama `sampurna`
- Import file `config/sampurna.sql`

**4. Konfigurasi koneksi database**

Edit file `config/database.php` dan sesuaikan:
```php
private $host     = "localhost";
private $db_name  = "sampurna";
private $username = "root";
private $password = "";        // sesuaikan password MySQL Anda
```

**5. Buat folder uploads (jika belum ada)**
```bash
mkdir -p uploads/artikel
mkdir -p uploads/profil
```

**6. Jalankan aplikasi**

Buka browser dan akses:
```
http://localhost/sampurna/
```

---

## 👥 Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@sampurna.com | admin123 |
| Petugas | petugas@sampurna.com | petugas123 |
| User | user@sampurna.com | user123 |

---

## 🔐 Sistem Autentikasi & Role

Autentikasi dikelola sepenuhnya via **PHP Session**. Setiap halaman protected memanggil fungsi guard yang sesuai:

```php
requireLogin();    // wajib login (semua role)
requireAdmin();    // khusus admin
requirePetugas();  // khusus petugas
requireUser();     // khusus user biasa (admin & petugas diblokir)
```

Setelah login, sistem secara otomatis mengarahkan pengguna ke dashboard sesuai role-nya masing-masing.

---

## 📷 Screenshot

| Halaman | Tampilan |
|---------|----------|
| Beranda | ![Beranda](docs/screenshots/1.png) |
| Dashboard User | ![Dashboard User](docs/screenshots/4.png) |
| Dashboard Admin | ![Dashboard Admin](docs/screenshots/3.png) |
| Dashboard Petugas | ![Dahboard Petugas](docs/screenshots/2.png) |

---

## 🗄️ Struktur Database

Database yang digunakan adalah **MySQL** dengan nama database `sampurna`, terdiri dari 6 tabel utama:

### Tabel: `users`
Menyimpan data seluruh pengguna sistem (admin, petugas, dan user/warga).

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | INT, PK, AI | ID unik pengguna |
| `nama` | VARCHAR | Nama lengkap |
| `email` | VARCHAR, UNIQUE | Email untuk login |
| `password` | VARCHAR | Password ter-hash |
| `no_telp` | VARCHAR | Nomor telepon |
| `alamat` | TEXT | Alamat lengkap |
| `foto_profil` | VARCHAR | Nama file foto profil |
| `role` | ENUM | `admin` / `petugas` / `user` |
| `created_at` | DATETIME | Waktu registrasi |

---

### Tabel: `bank_sampah`
Menyimpan data bank sampah mitra yang terdaftar dalam sistem.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | INT, PK, AI | ID unik bank sampah |
| `nama` | VARCHAR | Nama bank sampah |
| `alamat` | TEXT | Alamat lengkap |
| `kecamatan` | VARCHAR | Kecamatan lokasi |
| `kota` | VARCHAR | Kota lokasi |
| `no_telp` | VARCHAR | Nomor telepon |
| `jam_buka` | VARCHAR | Jam operasional |
| `status` | ENUM | `aktif` / `nonaktif` |

---

### Tabel: `jenis_sampah`
Menyimpan daftar jenis sampah beserta harga beli per kilogram.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | INT, PK, AI | ID unik jenis sampah |
| `nama` | VARCHAR | Nama jenis sampah |
| `kategori` | VARCHAR | Kategori (plastik, kertas, logam, dll) |
| `harga_per_kg` | DECIMAL | Harga beli per kilogram (Rp) |
| `deskripsi` | TEXT | Keterangan tambahan |

---

### Tabel: `transaksi`
Menyimpan data header setiap transaksi setoran sampah.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | INT, PK, AI | ID unik transaksi |
| `user_id` | INT, FK → users | Pengguna yang menyetor |
| `bank_sampah_id` | INT, FK → bank_sampah | Tujuan bank sampah |
| `tanggal` | DATE | Tanggal setoran |
| `total_berat` | DECIMAL | Total berat seluruh sampah (kg) |
| `total_harga` | DECIMAL | Total nilai uang (Rp) |
| `status` | ENUM | `pending` / `diproses` / `selesai` / `dibatalkan` |
| `created_at` | DATETIME | Waktu transaksi dibuat |

---

### Tabel: `detail_transaksi`
Menyimpan rincian item sampah dalam satu transaksi (relasi many-to-one ke `transaksi`).

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | INT, PK, AI | ID unik detail |
| `transaksi_id` | INT, FK → transaksi | Transaksi induk |
| `jenis_sampah_id` | INT, FK → jenis_sampah | Jenis sampah yang disetor |
| `berat` | DECIMAL | Berat sampah (kg) |
| `subtotal_harga` | DECIMAL | Subtotal harga item ini (Rp) |

---

### Tabel: `artikel`
Menyimpan konten artikel edukasi yang ditulis oleh admin.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | INT, PK, AI | ID unik artikel |
| `author_id` | INT, FK → users | Admin penulis artikel |
| `judul` | VARCHAR | Judul artikel |
| `konten` | LONGTEXT | Isi artikel |
| `gambar` | VARCHAR | Nama file thumbnail |
| `kategori` | VARCHAR | Kategori artikel |
| `created_at` | DATETIME | Waktu artikel dibuat |

---

### Relasi Antar Tabel

```
users ──────────────┬──── transaksi ────┬──── detail_transaksi ──── jenis_sampah
                    │                   │
                    └──── artikel        └──── bank_sampah
```

---

## 👨‍💻 Tim Pengembang

| Nama | NIM | Role & Tanggung Jawab |
|------|-----|----------------------|
| **Neza Habib Azhari** | F1D02410130 | **Fullstack Dev** — Desain halaman publik & user dengan Tailwind CSS (`index.php`, `bank_sampah.php`, `artikel.php`, `artikel_detail.php`, `panduan.php`), integrasi peta interaktif Leaflet.js, halaman transaksi user (`transaksi.php`, `transaksi_baru.php`, `transaksi_detail.php`), sistem autentikasi & manajemen sesi PHP (`login.php`, `register.php`, `logout.php`), arsitektur backend (`includes/functions.php`, `config/database.php`), koneksi MySQL via PDO |
| **Ajriya Danuarta** | F1D02410101 | **Fullstack Dev** — Desain halaman admin dengan Tailwind CSS (`admin/dashboard.php`, `admin/users.php`, `admin/user_detail.php`, `admin/artikel.php`, `admin/bank_sampah.php`, `admin/jenis_sampah.php`, `admin/transaksi.php`), desain & logika panel petugas (`petugas/dashboard.php`, `petugas/transaksi.php`), alur status transaksi (pending → diproses → selesai/dibatalkan), desain database & skema SQL (`config/sampurna.sql`), koneksi MySQL |

---

Bug #1 — Halaman Detail Artikel Tidak Ditemukan (404 / Blank Screen)

* Gejala : Ketika mengklik salah satu artikel di halaman `artikel.php`, halaman
  langsung kosong atau menampilkan error 404 karena file tujuan tidak ditemukan.

* Langkah reproduksi :
   1. Buka halaman `artikel.php`
   2. Klik judul atau tombol "Baca Selengkapnya" pada salah satu artikel
   3. Browser diarahkan ke `artikel_detail.php?id=X`
   4. Halaman kosong / blank screen / 404 Not Found

* Hipotesis penyebab : File `artikel_detail.php` belum tersedia di root project,
  sehingga ketika link dari `artikel.php` mengarahkan ke halaman tersebut, server
  tidak menemukan file yang dimaksud. Selain itu, tabel `artikel` pada database
  belum dibuat, sehingga meskipun file tersedia, query pengambilan data artikel
  akan melempar `PDOException` karena tabel tidak ditemukan.

* Fix (apa yang diubah) :
   * Dibuat file baru `artikel_detail.php` pada root project yang berisi logika
     pengambilan data artikel berdasarkan parameter `id` dari URL, dilengkapi
     pengecekan apakah artikel dengan `id` tersebut benar-benar ada di database,
     serta menampilkan pesan error yang ramah jika artikel tidak ditemukan
   * Ditambahkan tabel `artikel` pada database dengan kolom `id`, `author_id`,
     `judul`, `konten`, `gambar`, `kategori`, dan `created_at`, beserta relasi
     foreign key ke tabel `users` pada kolom `author_id`

* Bukti : Perbaikan dilakukan dengan penambahan file `artikel_detail.php` pada
  root project dan penambahan tabel `artikel` beserta relasinya pada file skema
  database `config/sampurna.sql`

  ---

## 🚀 Saran Pengembangan

| # | Fitur | Deskripsi |
|---|-------|-----------|
| 1 | **Manajemen Petugas per Bank Sampah** | Saat ini satu petugas dapat mengakses dan mengelola transaksi dari seluruh bank sampah yang ada. Ke depannya, sistem dapat dikembangkan dengan menambahkan relasi antara akun petugas dan bank sampah tertentu, sehingga setiap petugas hanya dapat melihat dan memproses transaksi yang masuk ke bank sampah yang menjadi tanggung jawabnya. Hal ini akan meningkatkan akuntabilitas, keamanan data, serta efisiensi pengelolaan di lapangan. |
| 2 | **Fitur Pembayaran Otomatis (Payment Gateway)** | Saat ini pencatatan hasil setoran sampah hanya tersimpan secara digital dalam sistem tanpa adanya transfer dana secara langsung. Ke depannya, sistem dapat diintegrasikan dengan layanan payment gateway seperti Midtrans atau Xendit, sehingga hasil penjualan sampah dapat langsung ditransfer ke rekening atau dompet digital milik user yang menyetorkan sampah begitu transaksi berstatus selesai. Fitur ini akan meningkatkan kepercayaan pengguna sekaligus mempercepat proses pembayaran secara nyata. |

---

<p align="center">Dibuat dengan ♻️ untuk lingkungan yang lebih baik</p>
