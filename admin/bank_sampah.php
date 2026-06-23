<?php
 $is_admin_page = true; 
require_once '../config/database.php'; require_once '../includes/functions.php'; 
requireLogin(); requireAdmin();
 $db = getDB();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? ''; $nama = clean($_POST['nama']); $alamat = clean($_POST['alamat']); $kecamatan = clean($_POST['kecamatan']); $kota = clean($_POST['kota']); $no_telp = clean($_POST['no_telp']); $jam_buka = clean($_POST['jam_buka']); $status = clean($_POST['status']);
    if ($action == 'add') { $db->prepare("INSERT INTO bank_sampah (nama, alamat, kecamatan, kota, no_telp, jam_buka, status) VALUES (?,?,?,?,?,?,?)")->execute([$nama, $alamat, $kecamatan, $kota, $no_telp, $jam_buka, $status]); setFlash('success', 'Bank Sampah ditambahkan.'); }
    elseif ($action == 'edit') { $id = (int)$_POST['id']; $db->prepare("UPDATE bank_sampah SET nama=?, alamat=?, kecamatan=?, kota=?, no_telp=?, jam_buka=?, status=? WHERE id=?")->execute([$nama, $alamat, $kecamatan, $kota, $no_telp, $jam_buka, $status, $id]); setFlash('success', 'Bank Sampah diupdate.'); }
    elseif ($action == 'delete') { $id = (int)$_POST['id']; $db->prepare("DELETE FROM bank_sampah WHERE id=?")->execute([$id]); setFlash('success', 'Bank Sampah dihapus.'); }
    header("Location: bank_sampah.php"); exit;
}
 $banks = $db->query("SELECT * FROM bank_sampah ORDER BY kota, nama ASC")->fetchAll();
include '../includes/header.php';
?>
<div class="flex bg-slate-50 min-h-screen">
    <?php include 'sidebar_admin.php'; ?>
    <main class="flex-1 p-8 overflow-y-auto">
        <div class="max-w-6xl mx-auto">
            <div x-data="{ openModal: false, action: 'add', id: '', nama: '', alamat: '', kecamatan: '', kota: '', no_telp: '', jam_buka: '', status: 'aktif' }">
                <div class="flex items-center justify-between mb-6" data-aos="fade-down">
                    <h1 class="text-2xl font-extrabold text-slate-900">Bank Sampah</h1>
                    <button @click="action='add'; id=''; nama=''; alamat=''; kecamatan=''; kota=''; no_telp=''; jam_buka=''; status='aktif'; openModal=true" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm transition text-sm">
                        <i class="fas fa-plus mr-2"></i>Tambah Bank
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                            <tr><th class="py-3 px-6 font-semibold">Info</th><th class="py-3 px-6 font-semibold">Lokasi</th><th class="py-3 px-6 font-semibold">Jam Buka</th><th class="py-3 px-6 font-semibold">Status</th><th class="py-3 px-6 font-semibold">Aksi</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php foreach ($banks as $b): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-6"><div class="font-semibold text-slate-900"><?= htmlspecialchars($b['nama']) ?></div><div class="text-xs text-slate-500"><?= htmlspecialchars($b['alamat']) ?></div></td>
                                <td class="py-3 px-6 text-sm text-slate-600"><?= htmlspecialchars($b['kecamatan']) ?>, <?= htmlspecialchars($b['kota']) ?></td>
                                <td class="py-3 px-6 text-sm text-slate-600"><?= htmlspecialchars($b['jam_buka']) ?></td>
                                <td class="py-3 px-6"><span class="px-3 py-1 text-xs font-bold rounded-full <?= $b['status']=='aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' ?>"><?= ucfirst($b['status']) ?></span></td>
                                <td class="py-3 px-6 flex space-x-3">
                                    <button @click="action='edit'; id='<?= $b['id'] ?>'; nama='<?= htmlspecialchars($b['nama']) ?>'; alamat='<?= htmlspecialchars($b['alamat']) ?>'; kecamatan='<?= htmlspecialchars($b['kecamatan']) ?>'; kota='<?= htmlspecialchars($b['kota']) ?>'; no_telp='<?= htmlspecialchars($b['no_telp']) ?>'; jam_buka='<?= htmlspecialchars($b['jam_buka']) ?>'; status='<?= $b['status'] ?>'; openModal=true" class="text-blue-500 hover:text-blue-700 transition"><i class="fas fa-edit"></i></button>
                                    <form method="POST" class="inline" onsubmit="return confirm('Hapus?')"><input type="hidden" name="id" value="<?= $b['id'] ?>"><input type="hidden" name="action" value="delete"><button class="text-red-400 hover:text-red-600 transition"><i class="fas fa-trash"></i></button></form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-black/50 z-40" @click="openModal = false" style="display: none;"></div>
                <div x-show="openModal" x-transition class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto" style="display: none;">
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative my-8" @click.stop>
                        <button @click="openModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
                        <h3 class="text-lg font-bold text-slate-900 mb-4" x-text="action === 'add' ? 'Tambah Bank Sampah' : 'Edit Bank Sampah'"></h3>
                        <form method="POST" class="space-y-3">
                            <input type="hidden" name="action" :value="action"><input type="hidden" name="id" :value="id">
                            <div><label class="block text-xs font-bold text-slate-500 mb-1">Nama</label><input type="text" name="nama" x-model="nama" class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-emerald-500" required></div>
                            <div><label class="block text-xs font-bold text-slate-500 mb-1">Alamat</label><textarea name="alamat" x-model="alamat" class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-emerald-500" required></textarea></div>
                            <div class="grid grid-cols-2 gap-3">
                                <div><label class="block text-xs font-bold text-slate-500 mb-1">Kecamatan</label><input type="text" name="kecamatan" x-model="kecamatan" class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-emerald-500" required></div>
                                <div><label class="block text-xs font-bold text-slate-500 mb-1">Kota</label><input type="text" name="kota" x-model="kota" class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-emerald-500" required></div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div><label class="block text-xs font-bold text-slate-500 mb-1">No Telp</label><input type="text" name="no_telp" x-model="no_telp" class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-emerald-500"></div>
                                <div><label class="block text-xs font-bold text-slate-500 mb-1">Jam Buka</label><input type="text" name="jam_buka" x-model="jam_buka" class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-emerald-500"></div>
                            </div>
                            <div><label class="block text-xs font-bold text-slate-500 mb-1">Status</label><select name="status" x-model="status" class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-emerald-500"><option value="aktif">Aktif</option><option value="nonaktif">Nonaktif</option></select></div>
                            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 rounded-xl shadow-sm transition mt-2">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include '../includes/footer.php'; ?>