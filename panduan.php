<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
include 'includes/header.php';
?>

<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-emerald-600 font-bold text-sm uppercase tracking-widest">Belajar Memilah</span>
            <h2 class="text-4xl font-extrabold text-slate-900 mt-2">Panduan Pemilahan Sampah</h2>
            <p class="text-slate-500 mt-3 max-w-xl mx-auto">Langkah sederhana untuk memulai pemilahan sampah dari rumah Anda.</p>
        </div>

        <!-- Kategori Sampah Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-2xl mb-4"><i class="fas fa-leaf"></i></div>
                <h5 class="font-bold text-slate-900 mb-2">Organik</h5>
                <p class="text-slate-500 text-sm mb-3">Mudah terurai secara alami.</p>
                <ul class="text-xs text-slate-600 space-y-1">
                    <li><i class="fas fa-check text-emerald-500 mr-1"></i> Sisa makanan</li>
                    <li><i class="fas fa-check text-emerald-500 mr-1"></i> Dedaunan</li>
                    <li><i class="fas fa-check text-emerald-500 mr-1"></i> Kulit buah</li>
                </ul>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl mb-4"><i class="fas fa-recycle"></i></div>
                <h5 class="font-bold text-slate-900 mb-2">Anorganik</h5>
                <p class="text-slate-500 text-sm mb-3">Sulit terurai, bisa daur ulang.</p>
                <ul class="text-xs text-slate-600 space-y-1">
                    <li><i class="fas fa-check text-blue-500 mr-1"></i> Botol plastik</li>
                    <li><i class="fas fa-check text-blue-500 mr-1"></i> Kertas/Kardus</li>
                    <li><i class="fas fa-check text-blue-500 mr-1"></i> Kaleng</li>
                </ul>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-2xl mb-4"><i class="fas fa-radiation"></i></div>
                <h5 class="font-bold text-slate-900 mb-2">B3 (Berbahaya)</h5>
                <p class="text-slate-500 text-sm mb-3">Bahan kimia berbahaya.</p>
                <ul class="text-xs text-slate-600 space-y-1">
                    <li><i class="fas fa-check text-amber-500 mr-1"></i> Baterai bekas</li>
                    <li><i class="fas fa-check text-amber-500 mr-1"></i> Obat nyamuk</li>
                    <li><i class="fas fa-check text-amber-500 mr-1"></i> Lampu neon</li>
                </ul>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="400">
                <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-2xl mb-4"><i class="fas fa-microchip"></i></div>
                <h5 class="font-bold text-slate-900 mb-2">Elektronik</h5>
                <p class="text-slate-500 text-sm mb-3">Perangkat rusak/ bekas.</p>
                <ul class="text-xs text-slate-600 space-y-1">
                    <li><i class="fas fa-check text-purple-500 mr-1"></i> Kabel bekas</li>
                    <li><i class="fas fa-check text-purple-500 mr-1"></i> Charger rusak</li>
                    <li><i class="fas fa-check text-purple-500 mr-1"></i> Komputer bekas</li>
                </ul>
            </div>
        </div>

        <!-- Cara Kerja Setor -->
        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 p-8 md:p-12 rounded-3xl shadow-xl text-white mb-16" data-aos="zoom-in">
            <h3 class="text-2xl font-extrabold mb-6 text-center">Cara Kerja Sistem Setor Sampah</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="bg-white/10 backdrop-filter backdrop-blur-sm p-5 rounded-2xl border border-white/20">
                    <i class="fas fa-hand-holding-usd text-3xl mb-3"></i>
                    <h5 class="font-bold text-lg">1. Setor Sampah</h5>
                    <p class="text-sm opacity-90 mt-2">Buat transaksi setor di bank sampah terdekat melalui aplikasi.</p>
                </div>
                <div class="bg-white/10 backdrop-filter backdrop-blur-sm p-5 rounded-2xl border border-white/20">
                    <i class="fas fa-balance-scale text-3xl mb-3"></i>
                    <h5 class="font-bold text-lg">2. Timbang Sampah</h5>
                    <p class="text-sm opacity-90 mt-2">Serahkan sampah ke petugas, lalu petugas akan menimbang berat aslinya.</p>
                </div>
                <div class="bg-white/10 backdrop-filter backdrop-blur-sm p-5 rounded-2xl border border-white/20">
                    <i class="fas fa-money-bill-wave text-3xl mb-3"></i>
                    <h5 class="font-bold text-lg">3. Dapatkan Uang</h5>
                    <p class="text-sm opacity-90 mt-2">Terima uang tunai langsung dari petugas sesuai harga setoran.</p>
                </div>
            </div>
        </div>

        <!-- FAQ Accordion Alpine.js -->
        <div class="max-w-3xl mx-auto" data-aos="fade-up">
            <h3 class="text-2xl font-extrabold text-slate-900 mb-6 text-center">Pertanyaan Umum (FAQ)</h3>
            <div class="space-y-3" x-data="{ faq: null }">
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    <button @click="faq !== 1 ? faq = 1 : faq = null" :class="{'bg-emerald-50 text-emerald-700': faq === 1}" class="w-full flex items-center justify-between p-5 text-left font-semibold text-slate-800 transition">
                        <span>Apakah sampah harus dicuci sebelum disetor?</span>
                        <i :class="faq === 1 ? 'rotate-180' : ''" class="fas fa-chevron-down transition-transform duration-300 text-slate-400"></i>
                    </button>
                    <div x-show="faq === 1" x-transition class="px-5 pb-5 text-sm text-slate-600 leading-relaxed">
                        Ya, untuk sampah anorganik (seperti botol plastik, kemasan), pastikan dibilas bersih. Sampah yang kotor akan menurunkan nilai jual atau ditolak.
                    </div>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    <button @click="faq !== 2 ? faq = 2 : faq = null" :class="{'bg-emerald-50 text-emerald-700': faq === 2}" class="w-full flex items-center justify-between p-5 text-left font-semibold text-slate-800 transition">
                        <span>Berapa lama proses konfirmasi transaksi?</span>
                        <i :class="faq === 2 ? 'rotate-180' : ''" class="fas fa-chevron-down transition-transform duration-300 text-slate-400"></i>
                    </button>
                    <div x-show="faq === 2" x-transition class="px-5 pb-5 text-sm text-slate-600 leading-relaxed">
                        Setelah Anda membuat transaksi di aplikasi, petugas akan langsung menerima dan menimbang sampah Anda saat Anda datang ke bank sampah. Status transaksi akan berubah menjadi "Selesai" saat pembayaran diterima.
                    </div>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    <button @click="faq !== 3 ? faq = 3 : faq = null" :class="{'bg-emerald-50 text-emerald-700': faq === 3}" class="w-full flex items-center justify-between p-5 text-left font-semibold text-slate-800 transition">
                        <span>Kapan saya mendapatkan uang dari setor sampah?</span>
                        <i :class="faq === 3 ? 'rotate-180' : ''" class="fas fa-chevron-down transition-transform duration-300 text-slate-400"></i>
                    </button>
                    <div x-show="faq === 3" x-transition class="px-5 pb-5 text-sm text-slate-600 leading-relaxed">
                        Anda akan langsung menerima uang tunai saat menyerahkan sampah ke petugas bank sampah. Pastikan petugas mengubah status transaksi Anda menjadi "Selesai" di sistem.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>