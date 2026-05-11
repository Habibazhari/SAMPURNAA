<?php
// index.php - Homepage
require_once 'config/database.php';
require_once 'includes/functions.php';

$page_title = 'Beranda';

// Get statistics
$db = getDB();
$stmt = $db->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
$total_users = $stmt->fetch()['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM transaksi WHERE status = 'selesai'");
$total_transaksi = $stmt->fetch()['total'];

$stmt = $db->query("SELECT SUM(total_berat) as total FROM transaksi WHERE status = 'selesai'");
$total_sampah = $stmt->fetch()['total'] ?? 0;

$stmt = $db->query("SELECT COUNT(*) as total FROM bank_sampah");
$total_bank_sampah = $stmt->fetch()['total'];

// Get latest articles
$stmt = $db->query("SELECT a.*, u.nama as author FROM artikel a 
                     JOIN users u ON a.author_id = u.id 
                     ORDER BY a.created_at DESC LIMIT 3");
$latest_articles = $stmt->fetchAll();

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1>Kelola Sampah, Raih Manfaat</h1>
                <p class="lead">Platform terpadu untuk mengelola sampah rumah tangga Anda. Dapatkan poin dan uang dari sampah yang Anda kelola dengan benar!</p>
                <div class="d-flex gap-2">
                    <?php if (!isLoggedIn()): ?>
                        <a href="register.php" class="btn btn-light btn-lg">Mulai Sekarang</a>
                        <a href="panduan.php" class="btn btn-outline-light btn-lg">Pelajari Lebih Lanjut</a>
                    <?php else: ?>
                        <a href="dashboard.php" class="btn btn-light btn-lg">Dashboard Saya</a>
                        <a href="transaksi.php" class="btn btn-outline-light btn-lg">Setorkan Sampah</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <img src="https://via.placeholder.com/500x400/28a745/ffffff?text=Sampurna" alt="Hero" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-item">
                    <span class="stat-number"><?php echo number_format($total_users); ?></span>
                    <span class="stat-label">Pengguna Aktif</span>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-item">
                    <span class="stat-number"><?php echo number_format($total_sampah, 0); ?> kg</span>
                    <span class="stat-label">Sampah Terkelola</span>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-item">
                    <span class="stat-number"><?php echo number_format($total_transaksi); ?></span>
                    <span class="stat-label">Transaksi Selesai</span>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-item">
                    <span class="stat-number"><?php echo $total_bank_sampah; ?></span>
                    <span class="stat-label">Bank Sampah</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Mengapa Memilih Sampurna?</h2>
            <p class="text-muted">Platform terlengkap untuk pengelolaan sampah Anda</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h4>Panduan Lengkap</h4>
                        <p class="text-muted">Pelajari cara memilah dan mengelola berbagai jenis sampah dengan benar</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <h4>Sistem Poin</h4>
                        <p class="text-muted">Dapatkan poin dari setiap sampah yang Anda setorkan dan tukarkan dengan hadiah</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h4>Bank Sampah Terdekat</h4>
                        <p class="text-muted">Temukan bank sampah terdekat di sekitar Anda dengan mudah</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h4>Layanan Pickup</h4>
                        <p class="text-muted">Jadwalkan penjemputan sampah langsung ke rumah Anda</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Tracking Real-time</h4>
                        <p class="text-muted">Pantau riwayat transaksi dan kontribusi Anda untuk lingkungan</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <h4>Artikel & Tips</h4>
                        <p class="text-muted">Baca artikel edukatif tentang pengelolaan sampah dan lingkungan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest Articles -->
<?php if (count($latest_articles) > 0): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Artikel Terbaru</h2>
            <p class="text-muted">Tips dan informasi seputar pengelolaan sampah</p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($latest_articles as $article): ?>
            <div class="col-md-4">
                <div class="card article-card h-100">
                    <img src="<?php echo $article['gambar'] ? 'uploads/' . $article['gambar'] : 'https://via.placeholder.com/400x200/28a745/ffffff?text=Artikel'; ?>" class="card-img-top" alt="<?php echo $article['judul']; ?>">
                    <div class="card-body">
                        <span class="badge bg-success mb-2"><?php echo ucfirst($article['kategori']); ?></span>
                        <h5 class="card-title"><?php echo $article['judul']; ?></h5>
                        <p class="card-text text-muted"><?php echo substr(strip_tags($article['konten']), 0, 100); ?>...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-user"></i> <?php echo $article['author']; ?>
                            </small>
                            <a href="artikel_detail.php?id=<?php echo $article['id']; ?>" class="btn btn-sm btn-outline-success">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="artikel.php" class="btn btn-success">Lihat Semua Artikel</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="py-5 bg-success text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Siap Berkontribusi untuk Lingkungan?</h2>
        <p class="lead mb-4">Bergabunglah dengan ribuan pengguna lain yang sudah merasakan manfaat pengelolaan sampah yang baik</p>
        <?php if (!isLoggedIn()): ?>
            <a href="register.php" class="btn btn-light btn-lg">Daftar Sekarang - Gratis!</a>
        <?php else: ?>
            <a href="transaksi.php" class="btn btn-light btn-lg">Setorkan Sampah Sekarang</a>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>