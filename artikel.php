<?php
// artikel.php - Daftar Artikel untuk User
require_once 'config/database.php';
require_once 'includes/functions.php';

$page_title = 'Artikel';
$db = getDB();

// Filter kategori
$filter_kategori = $_GET['kategori'] ?? '';
$filter_query = $filter_kategori ? "WHERE a.kategori = '$filter_kategori'" : '';

// Pagination
$page = $_GET['page'] ?? 1;
$limit = 9;
$offset = ($page - 1) * $limit;

// Get total
$stmt = $db->query("SELECT COUNT(*) as total FROM artikel a $filter_query");
$total = $stmt->fetch()['total'];

// Get artikel
$stmt = $db->query("SELECT a.*, u.nama as author 
                    FROM artikel a 
                    JOIN users u ON a.author_id = u.id 
                    $filter_query
                    ORDER BY a.created_at DESC 
                    LIMIT $limit OFFSET $offset");
$artikel_list = $stmt->fetchAll();

// Get featured artikel (artikel terpopuler)
$stmt = $db->query("SELECT a.*, u.nama as author 
                    FROM artikel a 
                    JOIN users u ON a.author_id = u.id 
                    ORDER BY a.views DESC 
                    LIMIT 3");
$featured_articles = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="py-5 bg-light">
    <div class="container">
        <!-- Hero Section -->
        <div class="text-center mb-5">
            <h1 class="fw-bold mb-3">Artikel & Tips</h1>
            <p class="lead text-muted">Baca artikel edukatif seputar pengelolaan sampah dan lingkungan</p>
        </div>
        
        <!-- Featured Articles -->
        <?php if (count($featured_articles) > 0 && !$filter_kategori): ?>
        <div class="mb-5">
            <h3 class="fw-bold mb-4">
                <i class="fas fa-fire text-danger"></i> Artikel Terpopuler
            </h3>
            <div class="row g-4">
                <?php foreach ($featured_articles as $featured): ?>
                <div class="col-md-4">
                    <div class="card article-card h-100 shadow-sm">
                        <div class="position-relative">
                            <img src="<?php echo $featured['gambar'] ? 'uploads/artikel/' . $featured['gambar'] : 'https://via.placeholder.com/400x250/28a745/ffffff?text=' . urlencode($featured['judul']); ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo $featured['judul']; ?>">
                            <span class="position-absolute top-0 end-0 m-2 badge bg-danger">
                                <i class="fas fa-eye"></i> <?php echo $featured['views']; ?> views
                            </span>
                        </div>
                        <div class="card-body">
                            <?php
                            $badge_colors = ['tips' => 'success', 'edukasi' => 'info', 'berita' => 'danger', 'tutorial' => 'warning'];
                            $badge_color = $badge_colors[$featured['kategori']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?php echo $badge_color; ?> mb-2">
                                <?php echo ucfirst($featured['kategori']); ?>
                            </span>
                            <h5 class="card-title"><?php echo $featured['judul']; ?></h5>
                            <p class="card-text text-muted">
                                <?php echo substr(strip_tags($featured['konten']), 0, 120); ?>...
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-user"></i> <?php echo $featured['author']; ?>
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> <?php echo date('d M Y', strtotime($featured['created_at'])); ?>
                                </small>
                            </div>
                            <a href="artikel_detail.php?id=<?php echo $featured['id']; ?>" class="btn btn-success btn-sm mt-3 w-100">
                                Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <hr class="my-5">
        <?php endif; ?>
        
        <!-- Filter Kategori -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <strong><i class="fas fa-filter"></i> Filter:</strong>
                    </div>
                    <div class="col-md-10">
                        <div class="btn-group" role="group">
                            <a href="artikel.php" class="btn btn-sm <?php echo !$filter_kategori ? 'btn-success' : 'btn-outline-success'; ?>">
                                Semua
                            </a>
                            <a href="artikel.php?kategori=tips" class="btn btn-sm <?php echo $filter_kategori === 'tips' ? 'btn-success' : 'btn-outline-success'; ?>">
                                Tips
                            </a>
                            <a href="artikel.php?kategori=edukasi" class="btn btn-sm <?php echo $filter_kategori === 'edukasi' ? 'btn-info' : 'btn-outline-info'; ?>">
                                Edukasi
                            </a>
                            <a href="artikel.php?kategori=berita" class="btn btn-sm <?php echo $filter_kategori === 'berita' ? 'btn-danger' : 'btn-outline-danger'; ?>">
                                Berita
                            </a>
                            <a href="artikel.php?kategori=tutorial" class="btn btn-sm <?php echo $filter_kategori === 'tutorial' ? 'btn-warning' : 'btn-outline-warning'; ?>">
                                Tutorial
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-10">
                        <input type="text" id="searchArtikel" class="form-control" placeholder="Cari artikel...">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-success w-100">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Artikel List -->
        <?php if (count($artikel_list) > 0): ?>
            <div class="row g-4 mb-4">
                <?php foreach ($artikel_list as $artikel): ?>
                <div class="col-md-4 artikel-item">
                    <div class="card article-card h-100 shadow-sm">
                        <img src="<?php echo $artikel['gambar'] ? 'uploads/artikel/' . $artikel['gambar'] : 'https://via.placeholder.com/400x250/28a745/ffffff?text=' . urlencode($artikel['judul']); ?>" 
                             class="card-img-top" 
                             alt="<?php echo $artikel['judul']; ?>"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <?php
                            $badge_colors = ['tips' => 'success', 'edukasi' => 'info', 'berita' => 'danger', 'tutorial' => 'warning'];
                            $badge_color = $badge_colors[$artikel['kategori']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?php echo $badge_color; ?> mb-2 align-self-start">
                                <?php echo ucfirst($artikel['kategori']); ?>
                            </span>
                            <h5 class="card-title"><?php echo $artikel['judul']; ?></h5>
                            <p class="card-text text-muted flex-grow-1">
                                <?php echo substr(strip_tags($artikel['konten']), 0, 100); ?>...
                            </p>
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-user"></i> <?php echo $artikel['author']; ?>
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> <?php echo date('d M Y', strtotime($artikel['created_at'])); ?>
                                </small>
                                <small class="text-muted float-end">
                                    <i class="fas fa-eye"></i> <?php echo $artikel['views']; ?>
                                </small>
                            </div>
                            <a href="artikel_detail.php?id=<?php echo $artikel['id']; ?>" class="btn btn-outline-success btn-sm w-100">
                                Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php 
            $base_url = 'artikel.php' . ($filter_kategori ? '?kategori=' . $filter_kategori . '&' : '?');
            echo getPagination($total, $limit, $page, $base_url); 
            ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Belum Ada Artikel</h4>
                <p class="text-muted">Artikel akan segera ditambahkan</p>
            </div>
        <?php endif; ?>
        
        <!-- CTA Section -->
        <div class="card bg-success text-white mt-5">
            <div class="card-body text-center p-5">
                <h3 class="mb-3">Ingin Berkontribusi?</h3>
                <p class="lead mb-4">Bagikan pengetahuan Anda tentang pengelolaan sampah dengan menulis artikel</p>
                <?php if (isLoggedIn()): ?>
                    <a href="mailto:info@sampurna.com" class="btn btn-light btn-lg">
                        <i class="fas fa-envelope"></i> Kirim Artikel
                    </a>
                <?php else: ?>
                    <a href="register.php" class="btn btn-light btn-lg">
                        <i class="fas fa-user-plus"></i> Daftar Sekarang
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Simple search functionality
document.getElementById('searchArtikel')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const items = document.querySelectorAll('.artikel-item');
    
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>