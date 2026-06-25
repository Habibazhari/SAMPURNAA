<?php
 $is_admin_page = true;
require_once '../config/database.php';
require_once '../includes/functions.php';
requireLogin();
requireAdmin();

 $db = getDB();
 $id = (int)($_GET['id'] ?? 0);
 $stmt = $db->prepare("SELECT * FROM users WHERE id=? AND role='user'"); $stmt->execute([$id]); $user = $stmt->fetch();
if (!$user) { header("Location: users.php"); exit; }

 $stmt_trans = $db->prepare("SELECT t.*, b.nama as bank_nama FROM transaksi t JOIN bank_sampah b ON t.bank_sampah_id=b.id WHERE t.user_id=? ORDER BY t.created_at DESC");
 $stmt_trans->execute([$id]); $transaksis = $stmt_trans->fetchAll();

 $tot_berat = $db->prepare("SELECT COALESCE(SUM(total_berat), 0) FROM transaksi WHERE user_id=? AND status='selesai'"); $tot_berat->execute([$id]);
 $badge_status = ['pending' => 'bg-amber-100 text-amber-700', 'diproses' => 'bg-blue-100 text-blue-700', 'selesai' => 'bg-emerald-100 text-emerald-700', 'dibatalkan' => 'bg-red-100 text-red-700'];

include '../includes/header.php';
?>
<div class="flex bg-slate-50 min-h-screen">
    <?php include 'sidebar_admin.php'; ?>
    <main class="flex-1 p-8 overflow-y-auto">
        <div class="max-w-6xl mx-auto">
            <a href="users.php" class="inline-flex items-center text-sm text-slate-600 hover:text-emerald-600 mb-6 font-medium"><i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar User</a>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 text-center" data-aos="fade-up">
                    <img src="../uploads/profil/<?= $user['foto_profil'] ?>" class="w-24 h-24 rounded-full border-4 border-emerald-500 shadow-lg mb-4 mx-auto object-cover" alt="">
                    <h4 class="font-bold text-xl text-slate-900"><?= htmlspecialchars($user['nama']) ?></h4>
                    <p class="text-slate-500 text-sm mb-4"><?= $user['email'] ?></p>
                    <div class="text-center border-t border-slate-100 pt-4 mt-2">
                        <h5 class="text-2xl font-extrabold text-blue-600"><?= number_format($tot_berat->fetchColumn(), 1) ?> <span class="text-sm font-medium text-slate-500">Kg Sampah</span></h5>
                    </div>
                </div>
                
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                    <div class="p-6 border-b border-slate-100"><h4 class="font-bold text-lg text-slate-900">Riwayat Transaksi</h4></div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                                <tr><th class="py-3 px-6 font-semibold">Tgl</th><th class="py-3 px-6 font-semibold">Bank</th><th class="py-3 px-6 font-semibold">Total</th><th class="py-3 px-6 font-semibold">Status</th></tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <?php foreach($transaksis as $t): ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-6 text-sm text-slate-600"><?= date('d M Y', strtotime($t['tanggal'])) ?></td>
                                    <td class="py-3 px-6 text-sm text-slate-900 font-medium"><?= htmlspecialchars($t['bank_nama']) ?></td>
                                    <td class="py-3 px-6 text-sm font-bold text-slate-900"><?= formatRupiah($t['total_harga']) ?></td>
                                    <td class="py-3 px-6"><span class="px-3 py-1 text-xs font-bold rounded-full <?= $badge_status[$t['status']] ?>"><?= ucfirst($t['status']) ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include '../includes/footer.php'; ?>