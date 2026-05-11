<?php
// register.php
require_once 'config/database.php';
require_once 'includes/functions.php';

$page_title = 'Daftar';

// Redirect jika sudah login
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = clean($_POST['nama']);
    $email = clean($_POST['email']);
    $no_telp = clean($_POST['no_telp']);
    $alamat = clean($_POST['alamat']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    
    // Validasi
    if (empty($nama)) $errors[] = 'Nama harus diisi';
    if (empty($email)) $errors[] = 'Email harus diisi';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid';
    if (empty($password)) $errors[] = 'Password harus diisi';
    if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter';
    if ($password !== $password_confirm) $errors[] = 'Konfirmasi password tidak cocok';
    
    // Cek email sudah terdaftar
    if (empty($errors)) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email sudah terdaftar';
        }
    }
    
    // Proses registrasi
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("INSERT INTO users (nama, email, no_telp, alamat, password, role) VALUES (?, ?, ?, ?, ?, 'user')");
        
        if ($stmt->execute([$nama, $email, $no_telp, $alamat, $hashed_password])) {
            setFlash('success', 'Pendaftaran berhasil! Silakan login.');
            header('Location: login.php');
            exit();
        } else {
            $errors[] = 'Terjadi kesalahan saat mendaftar';
        }
    }
}

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-plus fa-3x text-success mb-3"></i>
                        <h3 class="fw-bold">Daftar di SAMPURNA</h3>
                        <p class="text-muted">Mulai kelola sampah Anda dengan baik</p>
                    </div>
                    
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap *</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $_POST['nama'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $_POST['email'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?php echo $_POST['no_telp'] ?? ''; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="2"><?php echo $_POST['alamat'] ?? ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password * <small class="text-muted">(min. 6 karakter)</small></label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Konfirmasi Password *</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="agree" required>
                            <label class="form-check-label" for="agree">
                                Saya setuju dengan <a href="#" class="text-success">syarat dan ketentuan</a>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 py-2">
                            <i class="fas fa-user-plus"></i> Daftar Sekarang
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="mb-0">Sudah punya akun? <a href="login.php" class="text-success fw-bold">Login di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>