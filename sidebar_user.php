<!-- Sidebar User Global -->
<aside class="hidden lg:flex w-64 bg-slate-900 text-slate-300 flex-col p-6">
    <div class="mb-8"><a href="index.php" class="text-emerald-400 font-extrabold text-xl">SAMPURNA</a></div>
    <div class="flex flex-col items-center mb-8">
        <img src="uploads/profil/<?= htmlspecialchars($_SESSION['foto_profil']) ?>" class="w-20 h-20 rounded-full border-4 border-emerald-500 shadow-lg mb-3 object-cover" alt="">
        <h4 class="font-bold text-lg text-white"><?= htmlspecialchars($_SESSION['nama']) ?></h4>
    </div>
    <nav class="flex-1 space-y-2">
        <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
        <a href="dashboard.php" class="flex items-center px-4 py-3 <?= $current_page == 'dashboard.php' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-gray-300 hover:bg-slate-800' ?> rounded-xl transition"><i class="fas fa-tachometer-alt w-6 mr-3"></i> Dashboard</a>
        <a href="transaksi.php" class="flex items-center px-4 py-3 <?= in_array($current_page, ['transaksi.php', 'transaksi_detail.php']) ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-gray-300 hover:bg-slate-800' ?> rounded-xl transition"><i class="fas fa-exchange-alt w-6 mr-3"></i> Riwayat</a>
        <a href="transaksi_baru.php" class="flex items-center px-4 py-3 <?= $current_page == 'transaksi_baru.php' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-gray-300 hover:bg-slate-800' ?> rounded-xl transition"><i class="fas fa-plus-circle w-6 mr-3"></i> Setor</a>
        <a href="profil.php" class="flex items-center px-4 py-3 <?= $current_page == 'profil.php' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-gray-300 hover:bg-slate-800' ?> rounded-xl transition"><i class="fas fa-user-edit w-6 mr-3"></i> Profil</a>
    </nav>
    <div class="mt-auto pt-4 border-t border-slate-800">
        <a href="logout.php" class="flex items-center px-4 py-3 text-red-400 hover:bg-red-900/30 rounded-xl transition font-medium text-sm"><i class="fas fa-sign-out-alt w-6 mr-3"></i> Logout</a>
    </div>
</aside>