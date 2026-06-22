<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    $role = $_SESSION['role'];
    if ($role === 'admin') header("Location: admin/dashboard.php");
    elseif ($role === 'petugas') header("Location: petugas/dashboard.php");
    else header("Location: dashboard.php");
    exit;
}

 $error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Email dan password wajib diisi.";
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['foto_profil'] = $user['foto_profil'];

            setFlash('success', 'Selamat datang kembali, ' . htmlspecialchars($user['nama']) . '!');
            
            if ($user['role'] === 'admin') header("Location: admin/dashboard.php");
            elseif ($user['role'] === 'petugas') header("Location: petugas/dashboard.php");
            else header("Location: dashboard.php");
            exit;
        } else {
            $error = "Email atau password salah.";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-emerald-50 to-teal-50 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-96 h-96 bg-emerald-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

    <div class="w-full max-w-md relative z-10" data-aos="zoom-in" data-aos-duration="600">
        <div class="glass-card p-8 sm:p-10 rounded-3xl shadow-xl">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-emerald-500/30 text-white text-2xl">
                    <i class="fas fa-recycle"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-gray-900">Masuk ke SAMPURNA</h2>
                <p class="text-gray-500 mt-1 text-sm">Kelola sampahmu dan dapatkan keuntungan</p>
            </div>

            <?php if ($error): ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-medium flex items-center">
                    <i class="fas fa-times-circle mr-2"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400"><i class="fas fa-envelope"></i></div>
                        <input type="email" name="email" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition bg-white/80" placeholder="nama@email.com" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400"><i class="fas fa-lock"></i></div>
                        <input type="password" name="password" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition bg-white/80" placeholder="Masukkan password" required>
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 rounded-xl shadow-lg hover:shadow-emerald-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                    LOGIN
                </button>
            </form>
            
            <div class="mt-6 text-center text-sm text-gray-500">
                Belum punya akun? <a href="register.php" class="text-emerald-600 font-bold hover:text-emerald-700 transition">Daftar Sekarang</a>
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