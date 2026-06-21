<?php
 $is_petugas_page = true;
require_once '../config/database.php';
require_once '../includes/functions.php';
requireLogin();
requirePetugas();

 $db = getDB();

// Handle Update Status & Penimbangan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $t_id = (int)$_POST['trans_id'];
    $new_status = clean($_POST['status']);
    
    $stmt_old = $db->prepare("SELECT * FROM transaksi WHERE id=?");
    $stmt_old->execute([$t_id]);
    $trans_old = $stmt_old->fetch();
    
    if ($trans_old) {
        $final_berat = $trans_old['total_berat'];
        $final_harga = $trans_old['total_harga'];

        // Jika status diubah jadi SELESAI, ambil input aktual dari Petugas
        if ($new_status === 'selesai') {
            $final_berat = (float)str_replace(',', '.', $_POST['actual_berat'] ?? $trans_old['total_berat']);
            $final_harga = (float)str_replace(',', '.', $_POST['actual_harga'] ?? $trans_old['total_harga']);
        }

        $stmt_up = $db->prepare("UPDATE transaksi SET status=?, total_berat=?, total_harga=? WHERE id=?");
        $stmt_up->execute([$new_status, $final_berat, $final_harga, $t_id]);
        
        setFlash('success', 'Status transaksi berhasil diupdate.');
        header("Location: transaksi.php"); exit;
    }
}

 $stats = $db->query("SELECT status, COUNT(*) as total FROM transaksi GROUP BY status")->fetchAll(PDO::FETCH_KEY_PAIR);

 $filter = isset($_GET['status']) ? clean($_GET['status']) : '';
 $where = "1=1"; $params = [];
if ($filter && in_array($filter, ['pending', 'diproses', 'selesai', 'dibatalkan'])) {
    $where .= " AND t.status = ?"; $params[] = $filter;
}

 $limit = 10; $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; $start = ($page > 1) ? ($page * $limit) - $limit : 0;
 $stmt_total = $db->prepare("SELECT COUNT(*) FROM transaksi t WHERE $where"); $stmt_total->execute($params); $total = $stmt_total->fetchColumn();

 $stmt = $db->prepare("SELECT t.*, u.nama as user_nama, b.nama as bank_nama FROM transaksi t JOIN users u ON t.user_id=u.id JOIN bank_sampah b ON t.bank_sampah_id=b.id WHERE $where ORDER BY t.created_at DESC LIMIT $start, $limit");
 $stmt->execute($params); $transaksis = $stmt->fetchAll();

 $base_url = "transaksi.php?" . http_build_query(array_filter(['status'=>$filter]));
 $pagination = getPagination($total, $limit, $page, $base_url);

 $badge_status = ['pending' => 'bg-amber-100 text-amber-700', 'diproses' => 'bg-blue-100 text-blue-700', 'selesai' => 'bg-emerald-100 text-emerald-700', 'dibatalkan' => 'bg-red-100 text-red-700'];

include '../includes/header.php';
?>

<div class="flex bg-slate-50 min-h-screen">
    <?php include 'sidebar_petugas.php'; ?>

    <main class="flex-1 p-8 overflow-y-auto">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-2xl font-extrabold text-slate-900 mb-6" data-aos="fade-down">Konfirmasi & Timbang Sampah</h1>
            
            <!-- Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl text-amber-700 font-bold text-sm" data-aos="fade-up" data-aos-delay="100">Pending: <span class="text-xl ml-1"><?= $stats['pending']??0 ?></span></div>
                <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl text-blue-700 font-bold text-sm" data-aos="fade-up" data-aos-delay="200">Diproses: <span class="text-xl ml-1"><?= $stats['diproses']??0 ?></span></div>
                <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-xl text-emerald-700 font-bold text-sm" data-aos="fade-up" data-aos-delay="300">Selesai: <span class="text-xl ml-1"><?= $stats['selesai']??0 ?></span></div>
                <div class="bg-red-50 border border-red-200 p-4 rounded-xl text-red-700 font-bold text-sm" data-aos="fade-up" data-aos-delay="400">Dibatalkan: <span class="text-xl ml-1"><?= $stats['dibatalkan']??0 ?></span></div>
            </div>

            <!-- Filter & Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up">
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <form method="GET" class="flex items-center gap-2">
                        <select name="status" class="border border-slate-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-emerald-500 bg-white">
                            <option value="">Semua Status</option>
                            <option value="pending" <?= $filter=='pending'?'selected':'' ?>>Pending</option>
                            <option value="diproses" <?= $filter=='diproses'?'selected':'' ?>>Diproses</option>
                            <option value="selesai" <?= $filter=='selesai'?'selected':'' ?>>Selesai</option>
                            <option value="dibatalkan" <?= $filter=='dibatalkan'?'selected':'' ?>>Dibatalkan</option>
                        </select>
                        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition"><i class="fas fa-filter mr-1"></i> Filter</button>
                    </form>
                </div>
                
                <div class="overflow-x-auto">
                    <div x-data="{ openModal: false, editId: '', editStatus: '', editBerat: '', editHarga: '' }">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="py-4 px-6 font-semibold">ID</th>
                                    <th class="py-4 px-6 font-semibold">User</th>
                                    <th class="py-4 px-6 font-semibold">Bank Sampah</th>
                                    <th class="py-4 px-6 font-semibold">Estimasi User</th>
                                    <th class="py-4 px-6 font-semibold">Status</th>
                                    <th class="py-4 px-6 font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <?php foreach($transaksis as $t): ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-4 px-6 text-sm font-mono text-slate-500">#TR-<?= str_pad($t['id'],4,'0',STR_PAD_LEFT) ?></td>
                                    <td class="py-4 px-6 text-sm font-semibold text-slate-900"><?= htmlspecialchars($t['user_nama']) ?></td>
                                    <td class="py-4 px-6 text-sm text-slate-600"><?= htmlspecialchars($t['bank_nama']) ?></td>
                                    <td class="py-4 px-6 text-sm">
                                        <span class="font-bold text-slate-900"><?= formatRupiah($t['total_harga']) ?></span> <br>
                                        <span class="text-xs text-slate-500">(<?= $t['total_berat'] ?> Kg)</span>
                                    </td>
                                    <td class="py-4 px-6"><span class="px-3 py-1 text-xs font-bold rounded-full <?= $badge_status[$t['status']] ?>"><?= ucfirst($t['status']) ?></span></td>
                                    <td class="py-4 px-6">
                                        <button @click="editId = '<?= $t['id'] ?>'; editStatus = '<?= $t['status'] ?>'; editBerat = '<?= $t['total_berat'] ?>'; editHarga = '<?= $t['total_harga'] ?>'; openModal = true" class="text-slate-400 hover:text-blue-600 transition">
                                            <i class="fas fa-edit"></i> Update
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Modal Update Status + Penimbangan -->
                        <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-black/50 z-40" @click="openModal = false" style="display: none;"></div>
                        
                        <div x-show="openModal" x-transition class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto" style="display: none;">
                            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative my-8" @click.stop>
                                <button @click="openModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
                                <h3 class="text-lg font-bold text-slate-900 mb-4">Update Transaksi #TR-<span x-text="editId.padStart(4, '0')"></span></h3>
                                
                                <form method="POST">
                                    <input type="hidden" name="trans_id" :value="editId">
                                    
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-slate-500 mb-1">Ubah Status</label>
                                        <select name="status" x-model="editStatus" class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500" required>
                                            <option value="pending">Pending</option>
                                            <option value="diproses">Diproses</option>
                                            <option value="selesai">Selesai (Konfirmasi)</option>
                                            <option value="dibatalkan">Dibatalkan</option>
                                        </select>
                                    </div>

                                    <!-- Form Penimbangan muncul jika pilih SELESAI -->
                                    <div x-show="editStatus === 'selesai'" x-transition class="space-y-3 p-4 bg-emerald-50 rounded-xl border border-emerald-200 mb-4">
                                        <p class="text-xs font-bold text-emerald-700"><i class="fas fa-balance-scale mr-1"></i> Input Hasil Timbangan Aktual:</p>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 mb-1">Berat Aktual (Kg)</label>
                                            <input type="number" step="0.1" name="actual_berat" x-model="editBerat" class="w-full border border-emerald-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-emerald-500 bg-white" :required="editStatus === 'selesai'">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 mb-1">Total Harga Aktual (Rp)</label>
                                            <input type="number" step="0.01" name="actual_harga" x-model="editHarga" class="w-full border border-emerald-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-emerald-500 bg-white" :required="editStatus === 'selesai'">
                                        </div>
                                    </div>

                                    <button type="submit" name="update_status" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-sm transition">Simpan Perubahan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 border-t border-slate-100"><?= $pagination ?></div>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>