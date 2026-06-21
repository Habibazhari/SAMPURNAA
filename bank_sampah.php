<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
 $db = getDB();

// Filter: Hanya tampilkan yang aktif (deleted_at sudah dihapus)
 $banks = $db->query("SELECT * FROM bank_sampah WHERE status='aktif' ORDER BY kota, nama ASC")->fetchAll();
include 'includes/header.php';
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10" data-aos="fade-up">
            <span class="text-emerald-600 font-bold text-sm uppercase tracking-widest">Temukan Lokasi</span>
            <h2 class="text-4xl font-extrabold text-slate-900 mt-2">Direktori Bank Sampah</h2>
            <p class="text-slate-500 mt-3 max-w-xl mx-auto">Kunjungi bank sampah mitra terdekat di Mataram untuk menyetorkan sampahmu secara langsung.</p>
        </div>

        <div class="mb-12 rounded-3xl overflow-hidden shadow-xl border border-slate-200" data-aos="zoom-in">
            <div id="map" style="height: 450px; width: 100%; background: #ddd;"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($banks as $i => $b): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden flex flex-col" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
                <div class="bg-emerald-600 p-4 text-white flex items-center justify-between">
                    <h4 class="font-bold text-lg"><?= htmlspecialchars($b['nama']) ?></h4>
                    <span class="bg-white/20 text-xs font-bold px-2 py-1 rounded-lg"><?= htmlspecialchars($b['kecamatan']) ?></span>
                </div>
                <div class="p-5 flex-grow space-y-3 text-sm text-slate-600">
                    <div class="flex items-start"><i class="fas fa-map-marker-alt text-red-400 mt-1 mr-3 w-4 text-center"></i><span><?= htmlspecialchars($b['alamat']) . ', ' . htmlspecialchars($b['kota']) ?></span></div>
                    <div class="flex items-center"><i class="fas fa-clock text-blue-400 mr-3 w-4 text-center"></i><span><?= htmlspecialchars($b['jam_buka']) ?></span></div>
                    <div class="flex items-center"><i class="fas fa-phone text-emerald-400 mr-3 w-4 text-center"></i><span><?= htmlspecialchars($b['no_telp']) ?></span></div>
                </div>
                <div class="px-5 pb-5 pt-2">
                    <?php if (isLoggedIn()): ?>
                        <a href="transaksi_baru.php?bank_id=<?= $b['id'] ?>" class="block w-full text-center bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 rounded-xl shadow-sm transition text-sm">
                            <i class="fas fa-hand-holding-usd mr-2"></i>Setor di Sini
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="block w-full text-center border border-emerald-500 text-emerald-600 hover:bg-emerald-50 font-bold py-2.5 rounded-xl transition text-sm">
                            Login untuk Setor
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const map = L.map('map').setView([-8.5888, 116.1164], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const banksData = <?php echo json_encode($banks); ?>;
    banksData.forEach(bank => {
        if (bank.latitude && bank.longitude) {
            const marker = L.marker([bank.latitude, bank.longitude]).addTo(map);
            const popupContent = `
                <div style="min-width: 150px;">
                    <h4 style="font-weight: bold; color: #059669; margin-bottom: 5px;">${bank.nama}</h4>
                    <p style="font-size: 12px; color: #555; margin-bottom: 8px;">${bank.alamat}, ${bank.kecamatan}</p>
                    <p style="font-size: 12px; margin-bottom: 2px;"><b>Jam:</b> ${bank.jam_buka}</p>
                    <p style="font-size: 12px; margin-bottom: 8px;"><b>Telp:</b> ${bank.no_telp}</p>
                    ${<?php echo isLoggedIn() ? 'true' : 'false'; ?> ? 
                        `<a href="transaksi_baru.php?bank_id=${bank.id}" style="background: #10b981; color: white; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold;">Setor di Sini</a>` : 
                        `<a href="login.php" style="background: #f1f5f9; color: #059669; border: 1px solid #10b981; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold;">Login untuk Setor</a>`
                    }
                </div>
            `;
            marker.bindPopup(popupContent);
        }
    });
</script>
<?php include 'includes/footer.php'; ?>