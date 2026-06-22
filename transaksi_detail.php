<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
requireUser();

 $db = getDB(); $uid = $_SESSION['user_id']; $id = (int)($_GET['id'] ?? 0);

// Proses Batalkan Transaksi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['batalkan_transaksi'])) {
    $t_id = (int)$_POST['trans_id'];
    $stmt_batal = $db->prepare("UPDATE transaksi SET status='dibatalkan' WHERE id=? AND user_id=? AND status='pending'");
    $stmt_batal->execute([$t_id, $uid]);
    if ($stmt_batal->rowCount() > 0) {
        setFlash('success', 'Transaksi berhasil dibatalkan.');
    } else {
        setFlash('danger', 'Gagal membatalkan transaksi.');
    }
    header("Location: transaksi.php"); exit;
}

 $stmt = $db->prepare("SELECT t.*, b.nama as bank_nama, b.alamat as bank_alamat FROM transaksi t JOIN bank_sampah b ON t.bank_sampah_id=b.id WHERE t.id=? AND t.user_id=?");
 $stmt->execute([$id, $uid]); $trans = $stmt->fetch();
if (!$trans) { setFlash('danger', 'Transaksi tidak ditemukan.'); header("Location: transaksi.php"); exit; }

 $stmt_det = $db->prepare("SELECT d.*, j.nama as jenis_nama, j.kategori FROM detail_transaksi d JOIN jenis_sampah j ON d.jenis_sampah_id=j.id WHERE d.transaksi_id=?");
 $stmt_det->execute([$id]); $details = $stmt_det->fetchAll();
 $badge_status = ['pending' => 'bg-amber-100 text-amber-700', 'diproses' => 'bg-blue-100 text-blue-700', 'selesai' => 'bg-emerald-100 text-emerald-700', 'dibatalkan' => 'bg-red-100 text-red-700'];

include 'includes/header.php';
?>
<div class="flex bg-slate-50 min-h-screen">
    <?php include 'includes/sidebar_user.php'; ?>

    <main class="flex-1 p-6 lg:p-10 overflow-y-auto">
        <a href="transaksi.php" class="inline-flex items-center text-sm text-slate-600 hover:text-emerald-600 mb-6 font-medium" data-aos="fade-down"><i class="fas fa-arrow-left mr-2"></i> Kembali ke Riwayat</a>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up">
                <div class="p-6 border-b border-slate-100"><h4 class="font-bold text-slate-900">Item Sampah</h4></div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                            <tr><th class="py-3 px-6 font-semibold">Jenis</th><th class="py-3 px-6 font-semibold">Kategori</th><th class="py-3 px-6 font-semibold">Berat</th><th class="py-3 px-6 font-semibold">Subtotal</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php foreach ($details as $d): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-6 text-sm font-semibold text-slate-900"><?= htmlspecialchars($d['jenis_nama']) ?></td>
                                <td class="py-3 px-6"><span class="bg-slate-100 text-slate-700 text-xs font-bold px-3 py-1 rounded-full"><?= $d['kategori'] ?></span></td>
                                <td class="py-3 px-6 text-sm text-slate-600"><?= $d['berat'] ?> Kg</td>
                                <td class="py-3 px-6 text-sm font-medium text-slate-900"><?= formatRupiah($d['subtotal_harga']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-slate-50 border-t-2 border-slate-200">
                            <tr>
                                <td colspan="2" class="py-4 px-6 text-sm font-extrabold text-slate-900 text-right uppercase">Total</td>
                                <td class="py-4 px-6 text-sm font-extrabold text-slate-900"><?= $trans['total_berat'] ?> Kg</td>
                                <td class="py-4 px-6 text-sm font-extrabold text-emerald-600"><?= formatRupiah($trans['total_harga']) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 h-fit" data-aos="fade-left">
                <h4 class="font-bold text-slate-900 mb-4">Info Transaksi</h4>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between items-center"><span class="text-slate-500">Status</span><span class="px-3 py-1 text-xs font-bold rounded-full <?= $badge_status[$trans['status']] ?>"><?= ucfirst($trans['status']) ?></span></div>
                    <div class="flex justify-between items-center"><span class="text-slate-500">Tanggal</span><span class="font-semibold text-slate-900"><?= date('d M Y', strtotime($trans['tanggal'])) ?></span></div>
                    <hr class="border-slate-100">
                    <div><span class="text-slate-500 block mb-1">Lokasi Bank Sampah</span><span class="font-semibold text-slate-900"><?= htmlspecialchars($trans['bank_nama']) ?></span><p class="text-xs text-slate-500 mt-1"><?= htmlspecialchars($trans['bank_alamat']) ?></p></div>
                </div>

                <?php if ($trans['status'] === 'pending'): ?>
                    <hr class="my-4 border-slate-100">
                    <form method="POST" onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')">
                        <input type="hidden" name="trans_id" value="<?= $trans['id'] ?>">
                        <button type="submit" name="batalkan_transaksi" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2.5 rounded-xl shadow-sm transition text-sm">
                            <i class="fas fa-times-circle mr-2"></i>Batalkan Transaksi
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>
<?php include 'includes/footer.php'; ?>