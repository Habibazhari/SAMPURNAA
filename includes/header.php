<?php
// includes/header.php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

 $is_admin_page = isset($is_admin_page) && $is_admin_page === true;
 $is_petugas_page = isset($is_petugas_page) && $is_petugas_page === true;

// Tentukan path asset berdasarkan lokasi file
 $asset_path = '../';
if (!$is_admin_page && !$is_petugas_page) {
    $asset_path = '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAMPURNA - Pengelolaan Sampah Cerdas</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: { 50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7', 400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857', 800: '#065f46', 900: '#064e3b' }
                    },
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

<nav class="sticky top-0 z-50 glass-card border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="<?= $asset_path ?>index.php" class="flex items-center space-x-2 text-emerald-700 hover:text-emerald-800 transition">
                <i class="fas fa-recycle text-2xl"></i>
                <span class="text-xl font-extrabold tracking-tight">SAMPURNA</span>
            </a>
            
            <!-- Desktop Menu (Tetap tampil untuk semua role) -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="<?= $asset_path ?>index.php" class="text-gray-600 hover:text-emerald-600 font-medium transition">Beranda</a>
                <a href="<?= $asset_path ?>bank_sampah.php" class="text-gray-600 hover:text-emerald-600 font-medium transition">Bank Sampah</a>
                <a href="<?= $asset_path ?>artikel.php" class="text-gray-600 hover:text-emerald-600 font-medium transition">Artikel</a>
                <a href="<?= $asset_path ?>panduan.php" class="text-gray-600 hover:text-emerald-600 font-medium transition">Panduan</a>
            </div>

            <!-- Auth Section -->
            <div class="hidden md:flex items-center space-x-4">
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="<?= $asset_path ?>admin/dashboard.php" class="bg-amber-400 hover:bg-amber-500 text-gray-900 font-bold px-4 py-2 rounded-full text-sm transition shadow-sm">
                            <i class="fas fa-cog mr-1"></i> Admin Panel
                        </a>
                    <?php elseif (isPetugas()): ?>
                        <a href="<?= $asset_path ?>petugas/dashboard.php" class="bg-blue-400 hover:bg-blue-500 text-white font-bold px-4 py-2 rounded-full text-sm transition shadow-sm">
                            <i class="fas fa-truck mr-1"></i> Petugas Panel
                        </a>
                    <?php endif; ?>
                    
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-emerald-600 focus:outline-none">
                            <i class="fas fa-user-circle text-xl"></i>
                            <span class="font-semibold"><?= htmlspecialchars($_SESSION['nama']) ?></span>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                            <?php if(isAdmin()): ?>
                                <a href="<?= $asset_path ?>admin/dashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard Admin</a>
                            <?php elseif(isPetugas()): ?>
                                <a href="<?= $asset_path ?>petugas/dashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard Petugas</a>
                            <?php else: ?>
                                <a href="<?= $asset_path ?>dashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a>
                            <?php endif; ?>
                            
                            <?php if(!isAdmin() && !isPetugas()): ?>
                                <a href="<?= $asset_path ?>profil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700"><i class="fas fa-id-card mr-2"></i>Profil</a>
                            <?php endif; ?>
                            
                            <hr class="my-1">
                            <a href="<?= $asset_path ?>logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="text-emerald-700 hover:text-emerald-800 font-semibold transition">Login</a>
                    <a href="register.php" class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-5 py-2 rounded-full shadow-sm transition">Daftar</a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenu = !mobileMenu" class="text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div x-show="mobileMenu" x-transition class="md:hidden bg-white border-t border-gray-100 shadow-lg" style="display: none;">
        <div class="px-4 pt-2 pb-4 space-y-2">
            <a href="<?= $asset_path ?>index.php" class="block py-2 px-3 rounded-lg text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium">Beranda</a>
            <a href="<?= $asset_path ?>bank_sampah.php" class="block py-2 px-3 rounded-lg text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium">Bank Sampah</a>
            <a href="<?= $asset_path ?>artikel.php" class="block py-2 px-3 rounded-lg text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium">Artikel</a>
            <a href="<?= $asset_path ?>panduan.php" class="block py-2 px-3 rounded-lg text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium">Panduan</a>
            <?php if (!isLoggedIn()): ?>
                <a href="login.php" class="block w-full text-center mt-3 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-5 py-2 rounded-full shadow-sm transition">Login / Daftar</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Flash Message -->
<?php if($flash = getFlash()): ?>
<div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8" data-aos="fade-down">
    <div class="flex items-center p-4 rounded-2xl shadow-sm border border-gray-100 
        <?php if(strpos($flash, 'alert-success') !== false) echo 'bg-emerald-50 text-emerald-800'; ?>
        <?php if(strpos($flash, 'alert-danger') !== false) echo 'bg-red-50 text-red-800'; ?>
        <?php if(strpos($flash, 'alert-warning') !== false) echo 'bg-amber-50 text-amber-800'; ?>">
        <div class="ml-3 text-sm font-medium">
            <?= strip_tags(str_replace(['<div>', '</div>'], '', $flash)) ?>
        </div>
    </div>
</div>
<?php endif; ?>