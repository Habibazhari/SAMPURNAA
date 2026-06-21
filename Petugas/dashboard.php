<?php
 $is_petugas_page = true;
require_once '../config/database.php';
require_once '../includes/functions.php';
requireLogin();
requirePetugas();

 $db = getDB();

// Hitung statistik buat petugas
 $tot_pending = $db->query("SELECT COUNT(*) FROM transaksi WHERE status='pending'")->fetchColumn();
 $tot_diproses = $db->query("SELECT COUNT(*) FROM transaksi WHERE status='diproses'")->fetchColumn();
 $tot_selesai_hari_ini = $db->query("SELECT COUNT(*) FROM transaksi WHERE status='selesai' AND DATE(tanggal)=CURDATE()")->fetchColumn();

include '../includes/header.php';
?>

<div class="flex bg-slate-50 min-h-screen">
    <?php include 'sidebar_petugas.php'; ?>

    <main class="flex-1 p-8 overflow-y-auto">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8" data-aos="fade-down">
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900">Dashboard Petugas</h1>
                    <p class="text-slate-500 text-sm mt-1">Selamat datang, <?= htmlspecialchars($_SESSION['nama']) ?>! Silakan cek setoran yang pending.</p>
                </div>
                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-full">Petugas Panel</span>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-clock"></i></div>
                        <a href="transaksi.php?status=pending" class="text-slate-400 hover:text-amber-600 transition"><i class="fas fa-arrow-right"></i></a>
                    </div>
                    <h3 class="text-3xl font-extrabold text-slate-900 mb-1"><?= $tot_pending ?></h3>
                    <p class="text-slate-500 text-sm font-medium">Setoran Pending</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-tasks"></i></div>
                        <a href="transaksi.php?status=diproses" class="text-slate-400 hover:text-blue-600 transition"><i class="fas fa-arrow-right"></i></a>
                    </div>
                    <h3 class="text-3xl font-extrabold text-slate-900 mb-1"><?= $tot_diproses ?></h3>
                    <p class="text-slate-500 text-sm font-medium">Sedang Diproses</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-check-double"></i></div>
                    </div>
                    <h3 class="text-3xl font-extrabold text-slate-900 mb-1"><?= $tot_selesai_hari_ini ?></h3>
                    <p class="text-slate-500 text-sm font-medium">Selesai Hari Ini</p>
                </div>
            </div>

            <!-- Quick Action -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between" data-aos="fade-up">
                <div>
                    <h4 class="font-bold text-slate-900 text-lg">Punya setoran yang menunggu?</h4>
                    <p class="text-slate-500 text-sm">Klik tombol di samping untuk menimbang dan mengkonfirmasi sampah yang sudah disetor warga.</p>
                </div>
                <a href="transaksi.php" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-xl shadow-sm transition whitespace-nowrap">
                    <i class="fas fa-balance-scale mr-2"></i>Proses Transaksi
                </a>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>