# SAMPURNA
Sistem Pengolahan dan Pemilahan Sampah Dengan Sampurna
# DESKRIPSI
SAMPURNA adalah platform website untuk mengelola sampah rumah tangga dengan sistem poin dan reward. Pengguna dapat menyetorkan sampah ke bank sampah, mendapatkan poin, dan menukarnya dengan hadiah. 
# PAGE
1. Sisi USER (Masyarakat)
Fokus pada kemudahan melaporkan sampah dan memantau reward.
Landing Page (index.php): Edukasi sampah, cara kerja sistem, dan statistik global.
Laporan Baru (laporan_tambah.php): User mengisi jenis sampah, berat (estimasi), lokasi, dan foto.
Status Laporan (laporan_saya.php): Menampilkan timeline (Pending → Disetujui → Dijemput/Diproses → Selesai).
2. Sisi ADMIN (Manajer Sistem)
Fokus pada pengawasan dan alokasi sumber daya.
Dashboard Statistik: Grafik laporan masuk per minggu dan performa bank sampah.
Assign Petugas: Fitur utama untuk memilih petugas mana yang akan menangani laporan/jemputan tertentu.
Manajemen Kategori: Mengatur poin per kg untuk tiap jenis sampah (Organik, Plastik, Elektronik).
3. Sisi PETUGAS (Eksekutor Lapangan)
Fokus pada mobilitas dan penyelesaian tugas.
Update Status: Fitur untuk mengubah status dari "Petugas Menuju Lokasi" menjadi "Selesai" setelah sampah diverifikasi.
Riwayat: Melihat total sampah yang berhasil dikumpulkan oleh petugas tersebut sebagai pencapaian kerja.
# STRUKTUR FOLDER
sampurna/
│
├── config/
│   └── database.php              # Konfigurasi koneksi database
│
├── includes/
│   ├── functions.php             # Helper functions (auth, format, dll)
│   ├── header.php                # Template header & navbar
│   └── footer.php                # Template footer
│
├── assets/
│   ├── css/
│   │   └── style.css             # Custom CSS styling
│   ├── js/
│   │   └── script.js             # Custom JavaScript
│   └── img/                      # Gambar statis
│
├── uploads/                      # Folder upload user
│   ├── profil/                   # Foto profil user
│   └── artikel/                  # Gambar artikel
│
├── admin/                        # Halaman khusus admin
│   ├── dashboard.php             # Dashboard admin
│   ├── users.php                 # Kelola users
│   ├── transaksi.php             # Kelola transaksi
│   ├── jenis_sampah.php          # Kelola jenis sampah
│   ├── bank_sampah.php           # Kelola bank sampah
│   ├── artikel.php               # Kelola artikel
│   └── pickup.php                # Kelola pickup request
│
├── index.php                     # Homepage
├── login.php                     # Halaman login
├── register.php                  # Halaman registrasi
├── logout.php                    # Proses logout
├── dashboard.php                 # Dashboard user
├── profil.php                    # Pengaturan profil user
├── transaksi.php                 # Daftar transaksi user
├── transaksi_baru.php            # Form transaksi baru
├── transaksi_detail.php          # Detail transaksi
├── poin.php                      # Halaman poin & reward
├── pickup.php                    # Pickup request user
├── panduan.php                   # Panduan sampah
├── bank_sampah.php               # Daftar bank sampah
├── artikel.php                   # Daftar artikel
├── artikel_detail.php            # Detail artikel
├── tentang.php                   # Tentang kami
└── sampurna.sql                  # File database
# TEKNOLOGI
HTML, PHP, CSS, Javascript, MySQL
# ALAMAT
[http://localhost]
