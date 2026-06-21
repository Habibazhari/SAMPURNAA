<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
 $db = getDB(); $id = (int)($_GET['id'] ?? 0);

// Filter: Hapus a.deleted_at IS NULL
 $stmt = $db->prepare("SELECT a.*, u.nama as author FROM artikel a JOIN users u ON a.author_id=u.id WHERE a.id=?");
 $stmt->execute([$id]); $artikel = $stmt->fetch();

if (!$artikel) { header("Location: artikel.php"); exit; }

// Filter: Hapus deleted_at IS NULL
 $stmt_lain = $db->prepare("SELECT * FROM artikel WHERE id != ? ORDER BY created_at DESC LIMIT 3");
 $stmt_lain->execute([$id]); $artikel_lain = $stmt_lain->fetchAll();

include 'includes/header.php';
?>
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="artikel.php" class="inline-flex items-center text-sm text-emerald-600 hover:text-emerald-800 mb-6 font-semibold" data-aos="fade-down"><i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Artikel</a>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2" data-aos="fade-right">
                <?php if (!empty($artikel['gambar']) && file_exists('uploads/artikel/'.$artikel['gambar'])): ?>
                    <img src="uploads/artikel/<?= $artikel['gambar'] ?>" class="w-full h-72 md:h-96 object-cover rounded-2xl shadow-md mb-8" alt="<?= htmlspecialchars($artikel['judul']) ?>">
                <?php endif; ?>
                <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full"><?= htmlspecialchars($artikel['kategori']) ?></span>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mt-3 mb-4 leading-tight"><?= htmlspecialchars($artikel['judul']) ?></h1>
                <div class="flex items-center space-x-4 text-sm text-slate-500 mb-8 border-b border-slate-100 pb-6">
                    <span><i class="fas fa-user mr-2 text-emerald-500"></i><?= htmlspecialchars($artikel['author']) ?></span>
                    <span><i class="fas fa-calendar mr-2 text-emerald-500"></i><?= date('d F Y', strtotime($artikel['created_at'])) ?></span>
                </div>
                <div class="prose prose-lg max-w-none text-slate-700 leading-relaxed">
                    <?= $artikel['konten'] ?>
                </div>
            </div>

            <div class="lg:col-span-1" data-aos="fade-left">
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 sticky top-24">
                    <h4 class="font-bold text-slate-900 mb-4 border-b border-slate-200 pb-3">Artikel Lainnya</h4>
                    <div class="space-y-4">
                        <?php foreach ($artikel_lain as $al): ?>
                        <a href="artikel_detail.php?id=<?= $al['id'] ?>" class="block group">
                            <h5 class="text-sm font-semibold text-slate-800 group-hover:text-emerald-600 transition line-clamp-2"><?= htmlspecialchars($al['judul']) ?></h5>
                            <p class="text-xs text-slate-400 mt-1"><?= date('d M Y', strtotime($al['created_at'])) ?></p>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>