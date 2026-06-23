<?php
 $is_admin_page = true;
require_once '../config/database.php';
require_once '../includes/functions.php';
requireLogin();
requireAdmin();

 $db = getDB();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $nama = clean($_POST['nama']); $kategori = clean($_POST['kategori']); $harga = (float)str_replace(',', '.', $_POST['harga_per_kg']); $deskripsi = clean($_POST['deskripsi']);

    if ($action == 'add') {
        $db->prepare("INSERT INTO jenis_sampah (nama, kategori, harga_per_kg, deskripsi) VALUES (?,?,?,?)")->execute([$nama, $kategori, $harga, $deskripsi]);
        setFlash('success', 'Jenis sampah ditambahkan.');
    } elseif ($action == 'edit') {
        $id = (int)$_POST['id'];
        $db->prepare("UPDATE jenis_sampah SET nama=?, kategori=?, harga_per_kg=?, deskripsi=? WHERE id=?")->execute([$nama, $kategori, $harga, $deskripsi, $id]);
        setFlash('success', 'Jenis sampah diupdate.');
    } elseif ($action == 'delete') {
        $id = (int)$_POST['id'];
        $db->prepare("DELETE FROM jenis_sampah WHERE id=?")->execute([$id]);
        setFlash('success', 'Jenis sampah dihapus.');
    }
    header("Location: jenis_sampah.php"); exit;
}

 $data = $db->query("SELECT * FROM jenis_sampah ORDER BY kategori, nama ASC")->fetchAll();
 $grouped = []; foreach ($data as $d) { $grouped[$d['kategori']][] = $d; }
 $kats = ['organik' => 'leaf', 'anorganik' => 'recycle', 'B3' => 'radiation', 'elektronik' => 'microchip'];

include '../includes/header.php';
?>
<div class="flex bg-slate-50 min-h-screen">
    <?php include 'sidebar_admin.php'; ?>
    <main class="flex-1 p-8 overflow-y-auto">
        <div class="max-w-6xl mx-auto">
            <div x-data="{ openModal: false, action: 'add', id: '', nama: '', kategori: 'organik', harga: '', deskripsi: '' }">
                <div class="flex items-center justify-between mb-6" data-aos="fade-down">
                    <h1 class="text-2xl font-extrabold text-slate-900">Jenis Sampah</h1>
                    <button @click="action='add'; id=''; nama=''; kategori='organik'; harga=''; deskripsi=''; openModal=true" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm transition text-sm">
                        <i class="fas fa-plus mr-2"></i>Tambah Jenis
                    </button>
                </div>

                <?php foreach ($grouped as $kat => $items): ?>
                <h5 class="font-bold text-slate-600 uppercase text-sm mb-3 mt-6 flex items-center" data-aos="fade-up">
                    <span class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center mr-2"><i class="fas fa-<?= $kats[$kat]??'trash' ?>"></i></span> 
                    <?= $kat ?>
                </h5>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-4" data-aos="fade-up">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                            <tr><th class="py-3 px-6 font-semibold">Nama</th><th class="py-3 px-6 font-semibold">Harga/Kg</th><th class="py-3 px-6 font-semibold">Aksi</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php foreach ($items as $i): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-6">
                                    <div class="font-semibold text-slate-900"><?= htmlspecialchars($i['nama']) ?></div>
                                    <div class="text-xs text-slate-500"><?= htmlspecialchars($i['deskripsi']) ?></div>
                                </td>
                                <td class="py-3 px-6 font-medium text-blue-600"><?= formatRupiah($i['harga_per_kg']) ?></td>
                                <td class="py-3 px-6 flex space-x-3">
                                    <button @click="action='edit'; id='<?= $i['id'] ?>'; nama='<?= htmlspecialchars($i['nama']) ?>'; kategori='<?= $i['kategori'] ?>'; harga='<?= $i['harga_per_kg'] ?>'; deskripsi='<?= htmlspecialchars($i['deskripsi']) ?>'; openModal=true" class="text-blue-500 hover:text-blue-700 transition"><i class="fas fa-edit"></i></button>
                                    <form method="POST" class="inline" onsubmit="return confirm('Hapus?')"><input type="hidden" name="id" value="<?= $i['id'] ?>"><input type="hidden" name="action" value="delete"><button class="text-red-400 hover:text-red-600 transition"><i class="fas fa-trash"></i></button></form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endforeach; ?>

                <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-black/50 z-40" @click="openModal = false" style="display: none;"></div>
                <div x-show="openModal" x-transition class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto" style="display: none;">
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative my-8" @click.stop>
                        <button @click="openModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
                        <h3 class="text-lg font-bold text-slate-900 mb-4" x-text="action === 'add' ? 'Tambah Jenis Baru' : 'Edit Jenis Sampah'"></h3>
                        <form method="POST">
                            <input type="hidden" name="action" :value="action"><input type="hidden" name="id" :value="id">
                            <div class="space-y-3">
                                <div><label class="block text-xs font-bold text-slate-500 mb-1">Nama</label><input type="text" name="nama" x-model="nama" class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-emerald-500" required></div>
                                <div><label class="block text-xs font-bold text-slate-500 mb-1">Kategori</label>
                                    <select name="kategori" x-model="kategori" class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-emerald-500">
                                        <option value="organik">Organik</option><option value="anorganik">Anorganik</option><option value="B3">B3</option><option value="elektronik">Elektronik</option>
                                    </select>
                                </div>
                                <div><label class="block text-xs font-bold text-slate-500 mb-1">Harga/Kg</label><input type="number" step="0.01" name="harga_per_kg" x-model="harga" class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-emerald-500" required></div>
                                <div><label class="block text-xs font-bold text-slate-500 mb-1">Deskripsi</label><textarea name="deskripsi" x-model="deskripsi" class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-emerald-500" rows="2"></textarea></div>
                            </div>
                            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 rounded-xl shadow-sm transition mt-5">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include '../includes/footer.php'; ?>