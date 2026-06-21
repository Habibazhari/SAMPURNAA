<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
requireUser();

 $db = getDB(); $uid = $_SESSION['user_id'];

// Proses Batalkan Transaksi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['batalkan_transaksi'])) {
    $t_id = (int)$_POST['trans_id'];
    $stmt_batal = $db->prepare("UPDATE transaksi SET status='dibatalkan' WHERE id=? AND user_id=? AND status='pending'");
    $stmt_batal->execute([$t_id, $uid]);
    if ($stmt_batal->rowCount() > 0) {
        setFlash('success', 'Transaksi berhasil dibatalkan.');
    } else {
        setFlash('danger', 'Gagal membatalkan. Transaksi sudah diproses atau bukan milik Anda.');
    }
    header("Location: transaksi.php"); exit;
}

 $filter_status = isset($_GET['status']) ? clean($_GET['status']) : '';
 $where = "t.user_id = ?"; $params = [$uid];
if (!empty($filter_status) && in_array($filter_status, ['pending', 'diproses', 'selesai', 'dibatalkan'])) { $where .= " AND t.status = ?"; $params[] = $filter_status; }

 $limit = 10; $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; $start = ($page > 1) ? ($page * $limit) - $limit : 0;
 $stmt_total = $db->prepare("SELECT COUNT(*) FROM transaksi t WHERE $where"); $stmt_total->execute($params); $total = $stmt_total->fetchColumn();
 $stmt = $db->prepare("SELECT t.*, b.nama as bank_nama FROM transaksi t JOIN bank_sampah b ON t.bank_sampah_id=b.id WHERE $where ORDER BY t.created_at DESC LIMIT $start, $limit");
 $stmt->execute($params); $transaksis = $stmt->fetchAll();
 $base_url = "transaksi.php?" . http_build_query(array_filter(['status' => $filter_status]));
 $pagination = getPagination($total, $limit, $page, $base_url);
 $badge_status = ['pending' => 'bg-amber-100 text-amber-700', 'diproses' => 'bg-blue-100 text-blue-700', 'selesai' => 'bg-emerald-100 text-emerald-700', 'dibatalkan' => 'bg-red-100 text-red-700'];

include 'includes/header.php';
?>
<div class="flex bg-slate-50 min-h-screen">
    <?php include 'includes/sidebar_user.php'; ?>

    <main class="flex-1 p-6 lg:p-10 overflow-y-auto">
        <div class="flex items-center justify-between mb-6" data-aos="fade-down">
            <h1 class="text-2xl font-extrabold text-slate-900">Riwayat Transaksi</h1>
            <a href="transaksi_baru.php" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm transition text-sm"><i class="fas fa-plus mr-2"></i>Transaksi Baru</a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <form method="GET" class="flex items-center gap-2">
                    <select name="status" class="border border-slate-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-emerald-500 bg-white">
                        <option value="">Semua Status</option>
                        <option value="pending" <?= $filter_status=='pending'?'selected':'' ?>>Pending</option>
                        <option value="diproses" <?= $filter_status=='diproses'?'selected':'' ?>>Diproses</option>
                        <option value="selesai" <?= $filter_status=='selesai'?'selected':'' ?>>Selesai</option>
                        <option value="dibatalkan" <?= $filter_status=='dibatalkan'?'selected':'' ?>>Dibatalkan</option>
                    </select>
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition"><i class="fas fa-filter"></i></button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                        <tr><th class="py-3 px-6 font-semibold">ID</th><th class="py-3 px-6 font-semibold">Tanggal</th><th class="py-3 px-6 font-semibold">Bank Sampah</th><th class="py-3 px-6 font-semibold">Total</th><th class="py-3 px-6 font-semibold">Status</th><th class="py-3 px-6 font-semibold">Aksi</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php if (empty($transaksis)): ?>
                            <tr><td colspan="6" class="text-center py-10 text-slate-400 font-medium">Belum ada data transaksi</td></tr>
                        <?php else: ?>
                            <?php foreach ($transaksis as $t): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-6 text-sm font-mono text-slate-500">#TR-<?= str_pad($t['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                <td class="py-3 px-6 text-sm text-slate-600"><?= date('d M Y', strtotime($t['tanggal'])) ?></td>
                                <td class="py-3 px-6 text-sm font-semibold text-slate-900"><?= htmlspecialchars($t['bank_nama']) ?></td>
                                <td class="py-3 px-6 text-sm font-bold text-slate-900"><?= formatRupiah($t['total_harga']) ?></td>
                                <td class="py-3 px-6"><span class="px-3 py-1 text-xs font-bold rounded-full <?= $badge_status[$t['status']] ?>"><?= ucfirst($t['status']) ?></span></td>
                                <td class="py-3 px-6 flex items-center gap-3">
                                    <a href="transaksi_detail.php?id=<?= $t['id'] ?>" class="text-blue-500 hover:text-blue-700 transition" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                    <?php if ($t['status'] === 'pending'): ?>
                                        <form method="POST" class="inline" onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')">
                                            <input type="hidden" name="trans_id" value="<?= $t['id'] ?>">
                                            <button type="submit" name="batalkan_transaksi" class="text-red-400 hover:text-red-600 transition" title="Batalkan Transaksi">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100"><?= $pagination ?></div>
        </div>
    </main>
</div>
<?php include 'includes/footer.php'; ?>