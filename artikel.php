<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
 $db = getDB();

 $filter_kategori = isset($_GET['kategori']) ? clean($_GET['kategori']) : '';

// Filter: Hapus deleted_at
 $where = "1=1"; 
 $params = [];
if (!empty($filter_kategori)) { 
    $where .= " AND a.kategori = ?"; 
    $params[] = $filter_kategori; 
}

// Filter: Hapus deleted_at
 $kategori_list = $db->query("SELECT DISTINCT kategori FROM artikel ORDER BY kategori")->fetchAll(PDO::FETCH_COLUMN);

 $limit = 9; $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; $start = ($page > 1) ? ($page * $limit) - $limit : 0;
 $stmt_total = $db->prepare("SELECT COUNT(*) FROM artikel a WHERE $where"); $stmt_total->execute($params); $total = $stmt_total->fetchColumn();

 $stmt = $db->prepare("SELECT a.*, u.nama as author FROM artikel a JOIN users u ON a.author_id=u.id WHERE $where ORDER BY a.created_at DESC LIMIT $start, $limit");
 $stmt->execute($params); $artikels = $stmt->fetchAll();

 $base_url = "artikel.php?" . http_build_query(array_filter(['kategori' => $filter_kategori]));
 $pagination = getPagination($total, $limit, $page, $base_url);

include 'includes/header.php';
?>
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10" data-aos="fade-up">
            <span class="text-emerald-600 font-bold text-sm uppercase tracking-widest">Edukasi & Informasi</span>
            <h2 class="text-4xl font-extrabold text-slate-900 mt-2">Artikel Terbaru</h2>
        </div>

        <div class="flex flex-wrap justify-center gap-2 mb-10" data-aos="fade-up" data-aos-delay="100">
            <a href="artikel.php" class="px-5 py-2 rounded-full text-sm font-semibold transition <?= empty($filter_kategori) ? 'bg-emerald-500 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' ?>">Semua</a>
            <?php foreach ($kategori_list as $kat): ?>
            <a href="artikel.php?kategori=<?= urlencode($kat) ?>" class="px-5 py-2 rounded-full text-sm font-semibold transition <?= $filter_kategori == $kat ? 'bg-emerald-500 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' ?>"><?= htmlspecialchars($kat) ?></a>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($artikels as $i => $a): ?>
            
            <!-- PERBAIKAN: Kartu dibungkus tag <a> agar bisa diklik -->
            <a href="artikel_detail.php?id=<?= $a['id'] ?>" class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden flex flex-col cursor-pointer" data-aos="fade-up" data-aos-delay="<?= $i % 3 * 100 ?>">
                <div class="h-48 bg-slate-200 relative overflow-hidden">
                    <?php if (!empty($a['gambar']) && file_exists('uploads/artikel/'.$a['gambar'])): ?>
                        <img src="uploads/artikel/<?= $a['gambar'] ?>" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" alt="">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-emerald-50 text-emerald-300"><i class="fas fa-image fa-3x"></i></div>
                    <?php endif; ?>
                    <span class="absolute top-4 right-4 bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg"><?= htmlspecialchars($a['kategori']) ?></span>
                </div>
                <div class="p-6 flex-grow flex flex-col">
                    <h5 class="text-lg font-bold text-slate-900 mb-2 line-clamp-2 hover:text-emerald-600 transition"><?= htmlspecialchars($a['judul']) ?></h5>
                    <p class="text-slate-500 text-sm mb-4 line-clamp-3 flex-grow"><?= strip_tags($a['konten']) ?></p>
                    <div class="flex items-center justify-between text-xs text-slate-400 font-medium mt-auto pt-4 border-t border-slate-100">
                        <span><i class="fas fa-user mr-1"></i> <?= htmlspecialchars($a['author']) ?></span>
                        <span><?= date('d M Y', strtotime($a['created_at'])) ?></span>
                    </div>
                </div>
            </a>
            <!-- TUTUP TAG <a> -->
            
            <?php endforeach; ?>
        </div>
        <div class="mt-10"><?= $pagination ?></div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
