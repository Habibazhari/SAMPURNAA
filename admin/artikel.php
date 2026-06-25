<?php
 $is_admin_page = true; 
require_once '../config/database.php'; require_once '../includes/functions.php'; 
requireLogin(); requireAdmin();
 $db = getDB();

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete']; 
    $stmt_img = $db->prepare("SELECT gambar FROM artikel WHERE id=?"); $stmt_img->execute([$id]); $img = $stmt_img->fetch();
    if ($img && $img['gambar'] && file_exists('../uploads/artikel/'.$img['gambar'])) unlink('../uploads/artikel/'.$img['gambar']);
    $db->prepare("DELETE FROM artikel WHERE id=?")->execute([$id]); 
    setFlash('success', 'Artikel dihapus.'); 
    header("Location: artikel.php"); exit;
}

 $stmt = $db->query("SELECT a.*, u.nama as author FROM artikel a JOIN users u ON a.author_id=u.id ORDER BY a.created_at DESC");
 $artikels = $stmt->fetchAll();

include '../includes/header.php';
?>
<div class="flex bg-slate-50 min-h-screen">
    <?php include 'sidebar_admin.php'; ?>
    <main class="flex-1 p-8 overflow-y-auto">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-6" data-aos="fade-down">
                <h1 class="text-2xl font-extrabold text-slate-900">Kelola Artikel</h1>
                <a href="artikel_tambah.php" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm transition text-sm"><i class="fas fa-plus mr-2"></i>Tambah Artikel</a>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                        <tr><th class="py-3 px-6 font-semibold">Judul</th><th class="py-3 px-6 font-semibold">Kategori</th><th class="py-3 px-6 font-semibold">Tanggal</th><th class="py-3 px-6 font-semibold">Aksi</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php foreach ($artikels as $a): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-6"><div class="font-semibold text-slate-900"><?= htmlspecialchars($a['judul']) ?></div><div class="text-xs text-slate-500">Oleh: <?= htmlspecialchars($a['author']) ?></div></td>
                            <td class="py-3 px-6"><span class="bg-slate-100 text-slate-700 text-xs font-bold px-3 py-1 rounded-full"><?= htmlspecialchars($a['kategori']) ?></span></td>
                            <td class="py-3 px-6 text-sm text-slate-600"><?= date('d M Y', strtotime($a['created_at'])) ?></td>
                            <td class="py-3 px-6 flex space-x-3">
                                <a href="artikel_edit.php?id=<?= $a['id'] ?>" class="text-blue-500 hover:text-blue-700 transition"><i class="fas fa-edit"></i></a>
                                <a href="artikel.php?delete=<?= $a['id'] ?>" onclick="return confirm('Hapus artikel ini?')" class="text-red-400 hover:text-red-600 transition"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<?php include '../includes/footer.php'; ?>