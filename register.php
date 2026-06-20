<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

 $error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = clean($_POST['nama']);
    $email = clean($_POST['email']);
    $no_telp = clean($_POST['no_telp']);
    $alamat = clean($_POST['alamat']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi_password'];

    if (empty($nama) || empty($email) || empty($password)) {
        $error = "Nama, email, dan password wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } elseif ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        $db = getDB();
        $stmt_check = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt_check->execute([$email]);
        if ($stmt_check->fetch()) {
            $error = "Email sudah terdaftar. Silakan login.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt_insert = $db->prepare("INSERT INTO users (nama, email, password, no_telp, alamat) VALUES (?, ?, ?, ?, ?)");
            if ($stmt_insert->execute([$nama, $email, $hashed, $no_telp, $alamat])) {
                setFlash('success', 'Registrasi berhasil! Silakan login.');
                header("Location: login.php");
                exit;
            } else {
                $error = "Terjadi kesalahan sistem.";
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-emerald-50 to-teal-50 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-emerald-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>

    <div class="w-full max-w-lg relative z-10" data-aos="zoom-in" data-aos-duration="600">
        <div class="glass-card p-8 sm:p-10 rounded-3xl shadow-xl">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-extrabold text-gray-900">Buat Akun Baru</h2>
                <p class="text-gray-500 mt-1 text-sm">Bergabunglah dengan komunitas peduli lingkungan</p>
            </div>

            <?php if ($error): ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-medium flex items-center">
                    <i class="fas fa-times-circle mr-2"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Nama Lengkap *</label>
                    <input type="text" name="nama" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition bg-white/80" required value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Email *</label>
                    <input type="email" name="email" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition bg-white/80" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">No. Telepon</label>
                        <input type="text" name="no_telp" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition bg-white/80" value="<?= isset($_POST['no_telp']) ? htmlspecialchars($_POST['no_telp']) : '' ?>">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Alamat</label>
                        <input type="text" name="alamat" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition bg-white/80" value="<?= isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : '' ?>">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Password *</label>
                        <input type="password" name="password" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition bg-white/80" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Konfirmasi Password *</label>
                        <input type="password" name="konfirmasi_password" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition bg-white/80" required>
                    </div>
                </div>
                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 rounded-xl shadow-lg hover:shadow-emerald-500/30 transition-all duration-300 transform hover:-translate-y-0.5 mt-4">
                    DAFTAR SEKARANG
                </button>
            </form>
            <div class="mt-6 text-center text-sm text-gray-500">
                Sudah punya akun? <a href="login.php" class="text-emerald-600 font-bold hover:text-emerald-700 transition">Login di sini</a>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes blob { 0% { transform: translate(0px, 0px) scale(1); } 33% { transform: translate(30px, -50px) scale(1.1); } 66% { transform: translate(-20px, 20px) scale(0.9); } 100% { transform: translate(0px, 0px) scale(1); } }
    .animate-blob { animation: blob 7s infinite; }
    .animation-delay-2000 { animation-delay: 2s; }
</style>

<?php include 'includes/footer.php'; ?>
