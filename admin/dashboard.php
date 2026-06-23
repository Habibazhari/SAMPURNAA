<?php
 $is_admin_page = true;
require_once '../config/database.php';
require_once '../includes/functions.php';
requireLogin();
requireAdmin();

 $db = getDB();
 $tot_user = $db->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn();
 $tot_trans = $db->query("SELECT COUNT(*) FROM transaksi")->fetchColumn();
 $tot_bank = $db->query("SELECT COUNT(*) FROM bank_sampah WHERE status='aktif'")->fetchColumn();
 $tot_artikel = $db->query("SELECT COUNT(*) FROM artikel")->fetchColumn();

include '../includes/header.php';
?>

<div class="flex bg-slate-50 min-h-screen">
    <?php include 'sidebar_admin.php'; ?>

    <main class="flex-1 p-8 overflow-y-auto">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-8" data-aos="fade-down">
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900">Dashboard Admin</h1>
                    <p class="text-slate-500 text-sm mt-1">Selamat datang kembali, <?= htmlspecialchars($_SESSION['nama']) ?>!</p>
                </div>
                <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-full">Admin Panel</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-users"></i></div>
                        <a href="users.php" class="text-slate-400 hover:text-blue-600 transition"><i class="fas fa-arrow-right"></i></a>
                    </div>
                    <h3 class="text-3xl font-extrabold text-slate-900 mb-1"><?= $tot_user ?></h3>
                    <p class="text-slate-500 text-sm font-medium">Total Warga</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-receipt"></i></div>
                        <a href="transaksi.php" class="text-slate-400 hover:text-emerald-600 transition"><i class="fas fa-arrow-right"></i></a>
                    </div>
                    <h3 class="text-3xl font-extrabold text-slate-900 mb-1"><?= $tot_trans ?></h3>
                    <p class="text-slate-500 text-sm font-medium">Total Transaksi</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-warehouse"></i></div>
                        <a href="bank_sampah.php" class="text-slate-400 hover:text-amber-600 transition"><i class="fas fa-arrow-right"></i></a>
                    </div>
                    <h3 class="text-3xl font-extrabold text-slate-900 mb-1"><?= $tot_bank ?></h3>
                    <p class="text-slate-500 text-sm font-medium">Bank Sampah</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-newspaper"></i></div>
                        <a href="artikel.php" class="text-slate-400 hover:text-purple-600 transition"><i class="fas fa-arrow-right"></i></a>
                    </div>
                    <h3 class="text-3xl font-extrabold text-slate-900 mb-1"><?= $tot_artikel ?></h3>
                    <p class="text-slate-500 text-sm font-medium">Artikel</p>
                </div>
            </div>

            <h3 class="text-lg font-bold text-slate-900 mb-4" data-aos="fade-up">Akses Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8" data-aos="fade-up" data-aos-delay="100">
                <a href="transaksi.php" class="group bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:border-emerald-200 flex items-center space-x-4 transition-all duration-300 hover:shadow-md">
                    <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition"><i class="fas fa-cash-register"></i></div>
                    <span class="font-semibold text-slate-700 group-hover:text-emerald-700 transition">Kelola Transaksi</span>
                </a>
                <a href="jenis_sampah.php" class="group bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:border-blue-200 flex items-center space-x-4 transition-all duration-300 hover:shadow-md">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition"><i class="fas fa-recycle"></i></div>
                    <span class="font-semibold text-slate-700 group-hover:text-blue-700 transition">Jenis Sampah</span>
                </a>
                <a href="users.php" class="group bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:border-slate-400 flex items-center space-x-4 transition-all duration-300 hover:shadow-md">
                    <div class="w-10 h-10 bg-slate-100 text-slate-600 rounded-lg flex items-center justify-center group-hover:bg-slate-800 group-hover:text-white transition"><i class="fas fa-users-cog"></i></div>
                    <span class="font-semibold text-slate-700 group-hover:text-slate-900 transition">Data Warga & Petugas</span>
                </a>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>