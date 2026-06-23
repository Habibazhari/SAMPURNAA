<?php
// Ambil data notifikasi untuk badge sidebar
 $db_sidebar = getDB();
 $notif_transaksi = $db_sidebar->query("SELECT COUNT(*) FROM transaksi WHERE status='pending'")->fetchColumn();
?>

<!-- Admin Sidebar -->
<aside class="hidden lg:flex w-64 bg-slate-900 text-slate-300 flex-col p-6">
    <div class="mb-8"><span class="text-emerald-400 font-extrabold text-xl">SAMPURNA</span></div>
    
    <nav class="flex-1 space-y-1">
        <p class="px-3 text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 mt-4">Menu Utama</p>
        <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
        
        <a href="dashboard.php" class="flex items-center justify-between px-3 py-2.5 rounded-lg transition duration-200 <?= $current_page == 'dashboard.php' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-gray-300 hover:bg-slate-800 hover:text-white' ?>">
            <span class="flex items-center"><i class="fas fa-tachometer-alt w-5 mr-3"></i> Dashboard</span>
        </a>
        
        <a href="users.php" class="flex items-center justify-between px-3 py-2.5 rounded-lg transition duration-200 <?= in_array($current_page, ['users.php', 'user_detail.php']) ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-gray-300 hover:bg-slate-800 hover:text-white' ?>">
            <span class="flex items-center"><i class="fas fa-users w-5 mr-3"></i> Users</span>
        </a>
        
        <!-- Menu Transaksi dengan Notif -->
        <a href="transaksi.php" class="flex items-center justify-between px-3 py-2.5 rounded-lg transition duration-200 <?= $current_page == 'transaksi.php' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-gray-300 hover:bg-slate-800 hover:text-white' ?>">
            <span class="flex items-center"><i class="fas fa-exchange-alt w-5 mr-3"></i> Transaksi</span>
            <?php if($notif_transaksi > 0): ?>
                <span class="bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full leading-none animate-pulse"><?= $notif_transaksi ?></span>
            <?php endif; ?>
        </a>
        
        <a href="jenis_sampah.php" class="flex items-center justify-between px-3 py-2.5 rounded-lg transition duration-200 <?= $current_page == 'jenis_sampah.php' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-gray-300 hover:bg-slate-800 hover:text-white' ?>">
            <span class="flex items-center"><i class="fas fa-trash-alt w-5 mr-3"></i> Jenis Sampah</span>
        </a>
        
        <a href="bank_sampah.php" class="flex items-center justify-between px-3 py-2.5 rounded-lg transition duration-200 <?= $current_page == 'bank_sampah.php' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-gray-300 hover:bg-slate-800 hover:text-white' ?>">
            <span class="flex items-center"><i class="fas fa-warehouse w-5 mr-3"></i> Bank Sampah</span>
        </a>
        
        <a href="artikel.php" class="flex items-center justify-between px-3 py-2.5 rounded-lg transition duration-200 <?= in_array($current_page, ['artikel.php', 'artikel_tambah.php', 'artikel_edit.php']) ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-gray-300 hover:bg-slate-800 hover:text-white' ?>">
            <span class="flex items-center"><i class="fas fa-newspaper w-5 mr-3"></i> Artikel</span>
        </a>
    </nav>

    <div class="mt-auto pt-4 border-t border-slate-800 space-y-2">
        <a href="../index.php" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition duration-200 text-sm">
            <i class="fas fa-globe w-5 mr-3"></i> Lihat Website
        </a>
        <a href="../logout.php" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-red-900/30 hover:text-red-400 transition duration-200 text-sm text-red-300">
            <i class="fas fa-sign-out-alt w-5 mr-3"></i> Logout
        </a>
    </div>
</aside>