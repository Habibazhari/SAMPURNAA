<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
requireUser();

 $db = getDB(); $uid = $_SESSION['user_id'];
 $stmt = $db->prepare("SELECT * FROM users WHERE id=?"); $stmt->execute([$uid]); $user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = clean($_POST['nama']); $no_telp = clean($_POST['no_telp']); $alamat = clean($_POST['alamat']); $password_baru = $_POST['password_baru']; $foto_nama = $user['foto_profil'];

    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['foto_profil']['tmp_name']; 
        $file_ext = strtolower(pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION)); 
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/jpg'];
        $file_mime = mime_content_type($file_tmp);
        
        if (in_array($file_ext, ['jpg', 'jpeg', 'png']) && in_array($file_mime, $allowed_mimes)) {
            if (!is_dir('uploads/profil')) mkdir('uploads/profil', 0777, true);
            if ($foto_nama !== 'default.png' && file_exists('uploads/profil/' . $foto_nama)) unlink('uploads/profil/' . $foto_nama);
            $foto_nama = uniqid() . '.' . $file_ext; 
            move_uploaded_file($file_tmp, 'uploads/profil/' . $foto_nama);
        } else {
            $error = "Format file tidak didukung. Hanya file JPG/PNG asli yang diizinkan.";
        }
    }

    if (!isset($error)) {
        try {
            if (!empty($password_baru)) {
                if (strlen($password_baru) < 6) throw new Exception("Password baru minimal 6 karakter.");
                $hashed = password_hash($password_baru, PASSWORD_DEFAULT);
                $db->prepare("UPDATE users SET nama=?, no_telp=?, alamat=?, password=?, foto_profil=? WHERE id=?")->execute([$nama, $no_telp, $alamat, $hashed, $foto_nama, $uid]);
            } else {
                $db->prepare("UPDATE users SET nama=?, no_telp=?, alamat=?, foto_profil=? WHERE id=?")->execute([$nama, $no_telp, $alamat, $foto_nama, $uid]);
            }
            $_SESSION['nama'] = $nama; $_SESSION['foto_profil'] = $foto_nama;
            setFlash('success', 'Profil berhasil diperbarui!'); header("Location: profil.php"); exit;
        } catch (Exception $e) { $error = $e->getMessage(); }
    }
}

include 'includes/header.php';
?>
<div class="flex bg-slate-50 min-h-screen">
    <?php include 'includes/sidebar_user.php'; ?>

    <main class="flex-1 p-6 lg:p-10 overflow-y-auto">
        <h1 class="text-2xl font-extrabold text-slate-900 mb-6" data-aos="fade-down">Pengaturan Profil</h1>
        
        <?php if (isset($error)): ?>
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-medium flex items-center"><i class="fas fa-times-circle mr-2"></i> <?= $error ?></div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 max-w-3xl" data-aos="fade-up">
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="flex flex-col sm:flex-row items-center gap-6 mb-6">
                    <div class="relative group">
                        <img src="uploads/profil/<?= htmlspecialchars($user['foto_profil']) ?>" class="w-32 h-32 rounded-2xl object-cover shadow-md border-2 border-slate-100" alt="Profil">
                        <div class="absolute inset-0 bg-black bg-opacity-40 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer" onclick="document.getElementById('foto_profil').click()">
                            <i class="fas fa-camera text-white text-xl"></i>
                        </div>
                        <input type="file" id="foto_profil" name="foto_profil" class="hidden" accept="image/jpeg,image/png">
                    </div>
                    <div class="text-center sm:text-left">
                        <h4 class="font-bold text-lg text-slate-900">Ubah Foto</h4>
                        <p class="text-xs text-slate-500">JPG, JPEG, atau PNG. Klik ikon kamera.</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Email (Tidak bisa diubah)</label>
                        <input type="email" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm bg-slate-50 cursor-not-allowed" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500" required value="<?= htmlspecialchars($user['nama']) ?>">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">No. Telepon</label>
                        <input type="text" name="no_telp" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500" value="<?= htmlspecialchars($user['no_telp']) ?>">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Password Baru</label>
                        <input type="password" name="password_baru" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500" placeholder="Kosongkan jika tidak diganti">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500" rows="3"><?= htmlspecialchars($user['alamat']) ?></textarea>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 px-8 rounded-xl shadow-sm transition"><i class="fas fa-save mr-2"></i>Simpan</button>
                </div>
            </form>
        </div>
    </main>
</div>
<?php include 'includes/footer.php'; ?>
