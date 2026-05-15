<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
requireLogin();

$page_title = 'Profil';
$user_id = $_SESSION['user_id'];
$db = getDB();

// Get user data
$user = getUserById($user_id);

// Update profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = clean($_POST['nama']);
    $no_telp = clean($_POST['no_telp']);
    $alamat = clean($_POST['alamat']);
    
    $stmt = $db->prepare("UPDATE users SET nama = ?, no_telp = ?, alamat = ? WHERE id = ?");
    if ($stmt->execute([$nama, $no_telp, $alamat, $user_id])) {
        $_SESSION['nama'] = $nama;
        setFlash('success', 'Profil berhasil diupdate!');
        header('Location: profil.php');
        exit();
    }
}

include 'includes/header.php';
?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Pengaturan Profil</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?php echo $user['email']; ?>" disabled>
                            <small class="text-muted">Email tidak dapat diubah</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" value="<?php echo $user['nama']; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" name="no_telp" value="<?php echo $user['no_telp']; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="3"><?php echo $user['alamat']; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>