<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
requireUser();

 $db = getDB();
 $uid = $_SESSION['user_id'];

 $stmt_trans = $db->prepare("SELECT COUNT(*) as total FROM transaksi WHERE user_id=? AND status='selesai'"); $stmt_trans->execute([$uid]); $total_trans = $stmt_trans->fetch()['total'];
 $stmt_berat = $db->prepare("SELECT COALESCE(SUM(total_berat), 0) as total FROM transaksi WHERE user_id=? AND status='selesai'"); $stmt_berat->execute([$uid]); $total_berat = $stmt_berat->fetch()['total'];
 $stmt_uang = $db->prepare("SELECT COALESCE(SUM(total_harga), 0) as total FROM transaksi WHERE user_id=? AND status='selesai'"); $stmt_uang->execute([$uid]); $total_uang = $stmt_uang->fetch()['total'];

 $stmt_recent = $db->prepare("SELECT t.*, b.nama as bank_nama FROM transaksi t JOIN bank_sampah b ON t.bank_sampah_id=b.id WHERE t.user_id=? ORDER BY t.created_at DESC LIMIT 5");
 $stmt_recent->execute([$uid]);
 $recent = $stmt_recent->fetchAll();

 $badge_status = ['pending' => 'bg-amber-100 text-amber-700', 'diproses' => 'bg-blue-100 text-blue-700', 'selesai' => 'bg-emerald-100 text-emerald-700', 'dibatalkan' => 'bg-red-100 text-red-700'];

include 'includes/header.php';
?>

<div class="flex bg-slate-50 min-h-screen">
    <?php include 'includes/sidebar_user.php'; ?>

    <main class="flex-1 p-6 lg:p-10 overflow-y-auto">
        <h1 class="text-2xl font-extrabold text-slate-900 mb-6" data-aos="fade-down">Dashboard</h1>

        <!-- Stats Grid (Hanya 3 Kartu) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center justify-between mb-4"><div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-receipt"></i></div></div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1"><?= $total_trans ?></h3>
                <p class="text-gray-500 text-sm font-medium">Transaksi Selesai</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center justify-between mb-4"><div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-weight-hanging"></i></div></div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1"><?= number_format($total_berat, 1) ?></h3>
                <p class="text-gray-500 text-sm font-medium">Kg Sampah</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="300">
                <div class="flex items-center justify-between mb-4"><div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-wallet"></i></div></div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1"><?= formatRupiah($total_uang) ?></h3>
                <p class="text-gray-500 text-sm font-medium">Pendapatan</p>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8" data-aos="fade-up">
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <h4 class="font-bold text-lg text-slate-900">Transaksi Terbaru</h4>
                <a href="transaksi.php" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition">Lihat Semua <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                        <tr><th class="py-4 px-6 font-semibold">Tanggal</th><th class="py-4 px-6 font-semibold">Bank Sampah</th><th class="py-4 px-6 font-semibold">Berat</th><th class="py-4 px-6 font-semibold">Total</th><th class="py-4 px-6 font-semibold">Status</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php if (empty($recent)): ?>
                            <tr><td colspan="5" class="text-center py-10 text-gray-400 font-medium">Belum ada transaksi</td></tr>
                        <?php else: ?>
                            <?php foreach ($recent as $r): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-4 px-6 text-sm text-slate-600"><?= date('d M Y', strtotime($r['tanggal'])) ?></td>
                                <td class="py-4 px-6 text-sm text-slate-900 font-semibold"><?= htmlspecialchars($r['bank_nama']) ?></td>
                                <td class="py-4 px-6 text-sm text-slate-700"><?= $r['total_berat'] ?> Kg</td>
                                <td class="py-4 px-6 text-sm text-slate-900 font-bold"><?= formatRupiah($r['total_harga']) ?></td>
                                <td class="py-4 px-6"><span class="px-3 py-1 text-xs font-bold rounded-full <?= $badge_status[$r['status']] ?>"><?= ucfirst($r['status']) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tips Section -->
        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 p-6 rounded-2xl shadow-lg text-white flex items-center" data-aos="fade-up">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center text-3xl mr-5 backdrop-filter backdrop-blur-sm"><i class="fas fa-lightbulb"></i></div>
            <div>
                <h6 class="font-bold text-lg mb-1">Tips Hari Ini</h6>
                <p class="text-sm opacity-90">Pastikan botol plastik bersih dari sisa minuman dan kardus dalam keadaan kering agar harga penjualan lebih tinggi!</p>
            </div>
        </div>
    </main>
</div>
<?php include 'includes/footer.php'; ?>
