<?php
// dashboard.php - User Dashboard
require_once 'config/database.php';
require_once 'includes/functions.php';

requireLogin();

$page_title = 'Dashboard';
$user_id = $_SESSION['user_id'];
$db = getDB();

// Get user stats
$stmt = $db->prepare("SELECT COUNT(*) as total FROM transaksi WHERE user_id = ? AND status = 'selesai'");
$stmt->execute([$user_id]);
$total_transaksi = $stmt->fetch()['total'];

$stmt = $db->prepare("SELECT SUM(total_berat) as total FROM transaksi WHERE user_id = ? AND status = 'selesai'");
$stmt->execute([$user_id]);
$total_berat = $stmt->fetch()['total'] ?? 0;

$stmt = $db->prepare("SELECT SUM(total_harga) as total FROM transaksi WHERE user_id = ? AND status = 'selesai'");
$stmt->execute([$user_id]);
$total_pendapatan = $stmt->fetch()['total'] ?? 0;

// Get recent transactions
$stmt = $db->prepare("SELECT t.*, b.nama as bank_sampah FROM transaksi t 
                      JOIN bank_sampah b ON t.bank_sampah_id = b.id 
                      WHERE t.user_id = ? 
                      ORDER BY t.created_at DESC LIMIT 5");
$stmt->execute([$user_id]);
$recent_transactions = $stmt->fetchAll();

// Get pending pickup
$stmt = $db->prepare("SELECT COUNT(*) as total FROM pickup_request WHERE user_id = ? AND status = 'pending'");
$stmt->execute([$user_id]);
$pending_pickup = $stmt->fetch()['total'];

include 'includes/header.php';
?>

<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="dashboard-sidebar">
                <div class="text-center mb-4">
                    <img src="uploads/<?php echo $_SESSION['foto_profil'] ?? 'default.jpg'; ?>" alt="Profile" class="rounded-circle mb-2" width="80" height="80">
                    <h5 class="mb-0"><?php echo $_SESSION['nama']; ?></h5>
                    <small class="text-muted"><?php echo $_SESSION['email']; ?></small>
                </div>
                
                <nav class="nav flex-column">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a class="nav-link" href="transaksi.php">
                        <i class="fas fa-exchange-alt"></i> Transaksi Saya
                    </a>
                    <a class="nav-link" href="poin.php">
                        <i class="fas fa-coins"></i> Poin & Reward
                    </a>
                    <a class="nav-link" href="pickup.php">
                        <i class="fas fa-truck"></i> Pickup Request
                    </a>
                    <a class="nav-link" href="profil.php">
                        <i class="fas fa-user-cog"></i> Pengaturan Profil
                    </a>
                    <a class="nav-link text-danger" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </nav>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Dashboard</h2>
                    <p class="text-muted mb-0">Selamat datang kembali, <?php echo $_SESSION['nama']; ?>!</p>
                </div>
                <a href="transaksi_baru.php" class="btn btn-success">
                    <i class="fas fa-plus"></i> Transaksi Baru
                </a>
            </div>
            
            <!-- Info Boxes -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="info-box info-box-success">
                        <div class="info-box-content">
                            <div>
                                <div class="info-box-number"><?php echo $_SESSION['poin']; ?></div>
                                <div>Total Poin</div>
                            </div>
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-6">
                    <div class="info-box info-box-info">
                        <div class="info-box-content">
                            <div>
                                <div class="info-box-number"><?php echo $total_transaksi; ?></div>
                                <div>Transaksi</div>
                            </div>
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-6">
                    <div class="info-box info-box-warning">
                        <div class="info-box-content">
                            <div>
                                <div class="info-box-number"><?php echo number_format($total_berat, 1); ?> kg</div>
                                <div>Total Sampah</div>
                            </div>
                            <i class="fas fa-weight"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-6">
                    <div class="info-box info-box-success">
                        <div class="info-box-content">
                            <div>
                                <div class="info-box-number" style="font-size: 1.2rem;"><?php echo formatRupiah($total_pendapatan); ?></div>
                                <div>Pendapatan</div>
                            </div>
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if ($pending_pickup > 0): ?>
            <!-- Alert Pickup Pending -->
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Anda memiliki <strong><?php echo $pending_pickup; ?></strong> permintaan pickup yang menunggu konfirmasi. 
                <a href="pickup.php" class="alert-link">Lihat detail</a>
            </div>
            <?php endif; ?>
            
            <!-- Recent Transactions -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Transaksi Terbaru</h5>
                    <a href="transaksi.php" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <?php if (count($recent_transactions) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Bank Sampah</th>
                                        <th>Berat</th>
                                        <th>Total</th>
                                        <th>Poin</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_transactions as $trans): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($trans['tanggal'])); ?></td>
                                        <td><?php echo $trans['bank_sampah']; ?></td>
                                        <td><?php echo number_format($trans['total_berat'], 2); ?> kg</td>
                                        <td><?php echo formatRupiah($trans['total_harga']); ?></td>
                                        <td><span class="badge bg-warning"><?php echo $trans['total_poin']; ?> poin</span></td>
                                        <td>
                                            <?php
                                            $badge_class = [
                                                'pending' => 'secondary',
                                                'diproses' => 'info',
                                                'selesai' => 'success',
                                                'dibatalkan' => 'danger'
                                            ];
                                            ?>
                                            <span class="badge bg-<?php echo $badge_class[$trans['status']]; ?>">
                                                <?php echo ucfirst($trans['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada transaksi</p>
                            <a href="transaksi_baru.php" class="btn btn-success">Mulai Transaksi</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Tips Section -->
            <div class="card mt-4 border-success">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-lightbulb"></i> Tips Hari Ini
                </div>
                <div class="card-body">
                    <h6>Pisahkan sampah organik dan anorganik</h6>
                    <p class="mb-0 text-muted">Memilah sampah sejak dari rumah akan memudahkan proses daur ulang dan meningkatkan nilai jual sampah Anda!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>