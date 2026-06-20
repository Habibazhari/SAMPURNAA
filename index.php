<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

 $db = getDB();
 $stmt_user = $db->query("SELECT COUNT(*) as total FROM users WHERE role='user'"); $total_user = $stmt_user->fetch()['total'];
 $stmt_sampah = $db->query("SELECT COALESCE(SUM(total_berat), 0) as total FROM transaksi WHERE status='selesai'"); $total_sampah = $stmt_sampah->fetch()['total'];
 $stmt_transaksi = $db->query("SELECT COUNT(*) as total FROM transaksi WHERE status='selesai'"); $total_transaksi = $stmt_transaksi->fetch()['total'];
 $stmt_bank = $db->query("SELECT COUNT(*) as total FROM bank_sampah WHERE status='aktif'"); $total_bank = $stmt_bank->fetch()['total'];
 $stmt_artikel = $db->query("SELECT a.*, u.nama as author FROM artikel a JOIN users u ON a.author_id = u.id ORDER BY a.created_at DESC LIMIT 3");
 $artikels = $stmt_artikel->fetchAll();

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-br from-emerald-50 via-white to-teal-50 pt-20 pb-32">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-emerald-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-teal-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div data-aos="zoom-in" data-aos-duration="1000">
            <span class="inline-block bg-emerald-100 text-emerald-700 font-bold px-4 py-1.5 rounded-full text-sm mb-6 shadow-sm">#PilahSampahmu</span>
            <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 tracking-tight mb-6 leading-tight">
                Ubah Sampah <br> Jadi <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-500">Keuntungan</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto mb-10">
                Platform pengelolaan sampah berbasis komunitas. Pilah sampahmu, setorkan ke bank sampah, dan dapatkan uang!
            </p>
        </div>
        
        <div data-aos="fade-up" data-aos-delay="200" class="flex flex-col sm:flex-row gap-4 justify-center">
            <?php if (isLoggedIn()): ?>
                <a href="dashboard.php" class="px-8 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-full shadow-lg hover:shadow-emerald-500/40 transition-all duration-300 transform hover:-translate-y-1">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Saya
                </a>
            <?php else: ?>
                <a href="register.php" class="px-8 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-full shadow-lg hover:shadow-emerald-500/40 transition-all duration-300 transform hover:-translate-y-1">
                    <i class="fas fa-rocket mr-2"></i>Mulai Sekarang
                </a>
                <a href="login.php" class="px-8 py-3.5 bg-white text-emerald-700 font-bold rounded-full shadow-lg border border-emerald-100 hover:border-emerald-300 transition-all duration-300 transform hover:-translate-y-1">
                    Sudah Punya Akun?
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="margin-top: -80px;">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="glass-card p-6 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 text-center" data-aos="fade-up" data-aos-delay="100">
            <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl"><i class="fas fa-users"></i></div>
            <h3 class="text-3xl font-extrabold text-gray-900"><?= number_format($total_user) ?></h3>
            <p class="text-gray-500 text-sm font-medium mt-1">Warga Aktif</p>
        </div>
        <div class="glass-card p-6 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 text-center" data-aos="fade-up" data-aos-delay="200">
            <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl"><i class="fas fa-weight-hanging"></i></div>
            <h3 class="text-3xl font-extrabold text-gray-900"><?= number_format($total_sampah, 0) ?></h3>
            <p class="text-gray-500 text-sm font-medium mt-1">Kg Sampah Daur Ulang</p>
        </div>
        <div class="glass-card p-6 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 text-center" data-aos="fade-up" data-aos-delay="300">
            <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl"><i class="fas fa-exchange-alt"></i></div>
            <h3 class="text-3xl font-extrabold text-gray-900"><?= number_format($total_transaksi) ?></h3>
            <p class="text-gray-500 text-sm font-medium mt-1">Transaksi Sukses</p>
        </div>
        <div class="glass-card p-6 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 text-center" data-aos="fade-up" data-aos-delay="400">
            <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl"><i class="fas fa-warehouse"></i></div>
            <h3 class="text-3xl font-extrabold text-gray-900"><?= number_format($total_bank) ?></h3>
            <p class="text-gray-500 text-sm font-medium mt-1">Bank Sampah Mitra</p>
        </div>
    </div>
</section>

<!-- Cara Kerja -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-emerald-600 font-bold text-sm uppercase tracking-widest">Mudah & Cepat</span>
        <h2 class="text-4xl font-extrabold text-gray-900 mt-2 mb-16" data-aos="fade-up">Cara Kerja SAMPURNA</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-8 rounded-3xl border border-gray-100 hover:border-emerald-200 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-3" data-aos="fade-right" data-aos-delay="100">
                <div class="w-16 h-16 bg-emerald-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl shadow-lg shadow-emerald-500/30">1</div>
                <h4 class="text-xl font-bold mb-3">Pilah Sampah</h4>
                <p class="text-gray-500 leading-relaxed">Pisahkan sampah organik, anorganik, B3, dan elektronik sesuai panduan yang ada.</p>
            </div>
            <div class="p-8 rounded-3xl border border-gray-100 hover:border-emerald-200 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-3" data-aos="fade-up" data-aos-delay="200">
                <div class="w-16 h-16 bg-emerald-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl shadow-lg shadow-emerald-500/30">2</div>
                <h4 class="text-xl font-bold mb-3">Setor ke Bank Sampah</h4>
                <p class="text-gray-500 leading-relaxed">Datang ke bank sampah terdekat dan serahkan sampah yang sudah dipilah.</p>
            </div>
            <div class="p-8 rounded-3xl border border-gray-100 hover:border-emerald-200 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-3" data-aos="fade-left" data-aos-delay="300">
                <div class="w-16 h-16 bg-emerald-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl shadow-lg shadow-emerald-500/30">3</div>
                <h4 class="text-xl font-bold mb-3">Dapat Uang</h4>
                <p class="text-gray-500 leading-relaxed">Petugas akan menimbang sampahmu dan kamu langsung mendapatkan uang tunai.</p>
            </div>
        </div>
    </div>
</section>

<!-- Artikel Terbaru -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-emerald-600 font-bold text-sm uppercase tracking-widest">Baca & Belajar</span>
            <h2 class="text-4xl font-extrabold text-gray-900 mt-2">Artikel Terbaru</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($artikels as $i => $a): ?>
            <div class="bg-white rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-3 overflow-hidden flex flex-col" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
                <div class="h-48 bg-gray-200 relative overflow-hidden">
                    <?php if (!empty($a['gambar']) && file_exists('uploads/artikel/'.$a['gambar'])): ?>
                        <img src="uploads/artikel/<?= $a['gambar'] ?>" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" alt="<?= htmlspecialchars($a['judul']) ?>">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-emerald-50 text-emerald-300"><i class="fas fa-image fa-3x"></i></div>
                    <?php endif; ?>
                    <span class="absolute top-4 right-4 bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg"><?= htmlspecialchars($a['kategori']) ?></span>
                </div>
                <div class="p-6 flex-grow flex flex-col">
                    <h5 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 hover:text-emerald-600 transition"><?= htmlspecialchars($a['judul']) ?></h5>
                    <p class="text-gray-500 text-sm mb-4 line-clamp-3 flex-grow"><?= strip_tags($a['konten']) ?></p>
                    <div class="flex items-center justify-between text-xs text-gray-400 font-medium mt-auto pt-4 border-t border-gray-100">
                        <span><i class="fas fa-user mr-1"></i> <?= htmlspecialchars($a['author']) ?></span>
                        <span><?= date('d M Y', strtotime($a['created_at'])) ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob { animation: blob 7s infinite; }
    .animation-delay-2000 { animation-delay: 2s; }
</style>

<?php include 'includes/footer.php'; ?>
