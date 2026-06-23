<?php
 $is_admin_page = true;
require_once '../config/database.php';
require_once '../includes/functions.php';
requireLogin();
requireAdmin();

 $db = getDB();

// Proses Hapus User (Dengan Penangkal Error Foreign Key)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $u_id = (int)$_POST['user_id'];
    try {
        $db->prepare("DELETE FROM users WHERE id=? AND role='user'")->execute([$u_id]);
        setFlash('success', 'User berhasil dihapus.');
    } catch (Exception $e) {
        setFlash('danger', 'Gagal menghapus! User ini masih punya riwayat transaksi tersimpan.');
    }
    header("Location: users.php"); exit;
}

 $limit = 15; $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; $start = ($page > 1) ? ($page * $limit) - $limit : 0;
 $total = $db->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn();
 $stmt = $db->prepare("SELECT * FROM users WHERE role='user' ORDER BY created_at DESC LIMIT $start, $limit");
 $stmt->execute(); $users = $stmt->fetchAll();
 $pagination = getPagination($total, $limit, $page, "users.php");

include '../includes/header.php';
?>
<div class="flex bg-slate-50 min-h-screen">
    <?php include 'sidebar_admin.php'; ?>
    <main class="flex-1 p-8 overflow-y-auto">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-2xl font-extrabold text-slate-900 mb-6" data-aos="fade-down">Kelola Users</h1>
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up">
                <div class="p-6 border-b border-slate-100">
                    <input type="text" id="searchUser" placeholder="Cari nama atau email..." class="w-full sm:w-1/3 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                            <tr><th class="py-4 px-6 font-semibold">Nama</th><th class="py-4 px-6 font-semibold">Email</th><th class="py-4 px-6 font-semibold">No Telp</th><th class="py-4 px-6 font-semibold">Aksi</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50" id="tableUsers">
                            <?php foreach ($users as $u): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-4 px-6 text-sm font-semibold text-slate-900"><?= htmlspecialchars($u['nama']) ?></td>
                                <td class="py-4 px-6 text-sm text-slate-600"><?= htmlspecialchars($u['email']) ?></td>
                                <td class="py-4 px-6 text-sm text-slate-600"><?= $u['no_telp'] ?: '-' ?></td>
                                <td class="py-4 px-6 flex items-center gap-3">
                                    <a href="user_detail.php?id=<?= $u['id'] ?>" class="text-blue-500 hover:text-blue-700 transition" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                    <form method="POST" class="inline" onsubmit="return confirm('Yakin hapus user ini? Data riwayat transaksinya tidak akan hilang.')">
                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                        <button type="submit" name="delete_user" class="text-red-400 hover:text-red-600 transition" title="Hapus User"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-100"><?= $pagination ?></div>
            </div>
        </div>
    </main>
</div>
<script>
document.getElementById('searchUser').addEventListener('keyup', function() {
    const val = this.value.toLowerCase();
    document.querySelectorAll('#tableUsers tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
});
</script>
<?php include '../includes/footer.php'; ?>