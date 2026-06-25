<?php
 $is_admin_page = true; 
require_once '../config/database.php'; 
require_once '../includes/functions.php'; 
requireLogin(); requireAdmin();

 $db = getDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = clean($_POST['judul']); 
    $kategori = clean($_POST['kategori']); 
    $konten = $_POST['konten']; // Dibiarkan polos tanpa cleanHtml
    $author_id = $_SESSION['user_id']; 
    $gambar_nama = '';

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) { 
            if (!is_dir('../uploads/artikel')) mkdir('../uploads/artikel', 0777, true); 
            $gambar_nama = uniqid() . '.' . $ext; 
            move_uploaded_file($_FILES['gambar']['tmp_name'], '../uploads/artikel/' . $gambar_nama); 
        }
    }
    
    $db->prepare("INSERT INTO artikel (author_id, judul, konten, gambar, kategori) VALUES (?, ?, ?, ?, ?)")->execute([$author_id, $judul, $konten, $gambar_nama, $kategori]);
    setFlash('success', 'Artikel dipublikasikan.'); 
    header("Location: artikel.php"); 
    exit;
}

include '../includes/header.php';
?>
<div class="flex bg-slate-50 min-h-screen">
    <?php include 'sidebar_admin.php'; ?>
    <main class="flex-1 p-8 overflow-y-auto">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-extrabold text-slate-900 mb-6" data-aos="fade-down">Tambah Artikel Baru</h1>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8" data-aos="fade-up">
                <form method="POST" enctype="multipart/form-data" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Judul Artikel</label>
                        <input type="text" name="judul" class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 text-lg font-semibold" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Kategori</label>
                            <input type="text" name="kategori" class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Gambar Cover</label>
                            <input type="file" name="gambar" class="w-full border border-slate-200 rounded-xl px-4 py-1.5 text-sm file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" accept="image/jpeg,image/png">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Konten</label>
                        <textarea name="konten" rows="12" class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 text-sm" required></textarea>
                    </div>
                    <div class="flex justify-end space-x-3 pt-2">
                        <a href="artikel.php" class="px-6 py-2.5 border border-slate-200 rounded-xl text-slate-600 font-medium hover:bg-slate-50 transition">Batal</a>
                        <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 px-6 rounded-xl shadow-sm transition">Publikasi</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
<?php include '../includes/footer.php'; ?>