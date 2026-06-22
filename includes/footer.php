<?php
 $asset_path = isset($is_admin_page) && $is_admin_page === true ? '../' : '';
?>

<footer class="bg-gray-900 text-gray-400 mt-16 pt-12 pb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <div>
                <h5 class="text-white font-extrabold text-lg mb-3"><i class="fas fa-recycle text-emerald-400 mr-2"></i>SAMPURNA</h5>
                <p class="text-sm leading-relaxed">Platform pengelolaan sampah rumah tangga berbasis komunitas. Mari wujudkan lingkungan bersih.</p>
            </div>
            <div>
                <h6 class="text-white font-bold mb-3">Navigasi</h6>
                <ul class="space-y-2 text-sm">
                    <li><a href="<?= $asset_path ?>index.php" class="hover:text-emerald-400 transition">Beranda</a></li>
                    <li><a href="<?= $asset_path ?>bank_sampah.php" class="hover:text-emerald-400 transition">Bank Sampah</a></li>
                    <li><a href="<?= $asset_path ?>artikel.php" class="hover:text-emerald-400 transition">Artikel</a></li>
                </ul>
            </div>
            <div>
                <h6 class="text-white font-bold mb-3">Kontak</h6>
                <ul class="space-y-2 text-sm">
                    <li><i class="fas fa-envelope mr-2 text-emerald-400"></i>admin@sampurna.id</li>
                    <li><i class="fas fa-phone mr-2 text-emerald-400"></i>+62 812 3456 7890</li>
                </ul>
            </div>
        </div>
        <hr class="border-gray-800 mb-6">
        <div class="text-center text-xs text-gray-500">
            &copy; <?= date('Y') ?> SAMPURNA. All rights reserved.
        </div>
    </div>
</footer>

<!-- AOS Animation JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true
    });
</script>
</body>
</html>