<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
requireUser();

 $db = getDB();
 $uid = $_SESSION['user_id'];

 $banks = $db->query("SELECT * FROM bank_sampah WHERE status='aktif' ORDER BY nama ASC")->fetchAll();
 $jenis_sampah = $db->query("SELECT * FROM jenis_sampah ORDER BY kategori, nama ASC")->fetchAll();
 $preselect_bank = isset($_GET['bank_id']) ? (int)$_GET['bank_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bank_id = (int)$_POST['bank_id'];
    $tanggal = clean($_POST['tanggal']);
    $items = isset($_POST['items']) ? $_POST['items'] : []; 
    
    $total_berat = 0; $total_harga = 0; $details = [];

    foreach ($items as $item) {
        $j_id = (int)($item['jenis_id'] ?? 0);
        $berat = (float)str_replace(',', '.', $item['berat'] ?? 0);
        
        if ($berat > 0 && $j_id > 0) {
            $stmt_js = $db->prepare("SELECT * FROM jenis_sampah WHERE id=?"); 
            $stmt_js->execute([$j_id]); 
            $js = $stmt_js->fetch();
            if ($js) {
                $sub_harga = $berat * $js['harga_per_kg'];
                $total_berat += $berat; $total_harga += $sub_harga;
                $details[] = ['jenis_id' => $j_id, 'berat' => $berat, 'sub_harga' => $sub_harga];
            }
        }
    }

    if (count($details) > 0) {
        try {
            $db->beginTransaction();
            $stmt_t = $db->prepare("INSERT INTO transaksi (user_id, bank_sampah_id, tanggal, total_berat, total_harga, status, created_at) VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
            $stmt_t->execute([$uid, $bank_id, $tanggal, $total_berat, $total_harga]);
            $trans_id = $db->lastInsertId();

            $stmt_d = $db->prepare("INSERT INTO detail_transaksi (transaksi_id, jenis_sampah_id, berat, subtotal_harga) VALUES (?, ?, ?, ?)");
            foreach ($details as $d) { $stmt_d->execute([$trans_id, $d['jenis_id'], $d['berat'], $d['sub_harga']]); }

            $db->commit();
            setFlash('success', 'Transaksi berhasil dibuat! Menunggu konfirmasi Petugas.');
            header("Location: transaksi_detail.php?id=$trans_id"); exit;
        } catch (Exception $e) { $db->rollBack(); $error = "Gagal menyimpan transaksi."; }
    } else { $error = "Tambahkan minimal 1 jenis sampah dengan berat lebih dari 0."; }
}

include 'includes/header.php';
?>

<div class="flex bg-slate-50 min-h-screen">
    <?php include 'includes/sidebar_user.php'; ?>

    <main class="flex-1 p-6 lg:p-10 overflow-y-auto">
        <h1 class="text-2xl font-extrabold text-slate-900 mb-6" data-aos="fade-down">Form Setor Sampah</h1>
        
        <?php if (isset($error)): ?>
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-medium flex items-center"><i class="fas fa-times-circle mr-2"></i> <?= $error ?></div>
        <?php endif; ?>

        <form method="POST" id="formTransaksi">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6" data-aos="fade-up">
                        <h4 class="font-bold text-slate-900 mb-4">Informasi Dasar</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Bank Sampah Tujuan</label>
                                <select name="bank_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 bg-white" required>
                                    <option value="">Pilih Bank Sampah</option>
                                    <?php foreach ($banks as $b): ?>
                                        <option value="<?= $b['id'] ?>" <?= $preselect_bank == $b['id'] ? 'selected' : '' ?>><?= htmlspecialchars($b['nama']) ?> (<?= $b['kota'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Tanggal Setor</label>
                                <input type="date" name="tanggal" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-500" required value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-slate-900">Detail Sampah</h4>
                            <button type="button" id="btnTambahItem" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold py-2 px-4 rounded-lg text-sm transition"><i class="fas fa-plus mr-1"></i> Tambah</button>
                        </div>
                        <div id="wrapItems" class="space-y-3"></div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sticky top-24" data-aos="fade-left">
                        <h4 class="font-bold text-slate-900 mb-6">Ringkasan Estimasi</h4>
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                                <span class="text-slate-500 text-sm">Total Berat</span>
                                <span class="font-extrabold text-slate-900" id="sumBerat">0 Kg</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500 text-sm">Estimasi Harga</span>
                                <span class="font-extrabold text-emerald-600 text-lg" id="sumHarga">Rp 0</span>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-emerald-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Transaksi
                        </button>
                        <p class="text-xs text-slate-400 text-center mt-3">*Harga final dikonfirmasi Petugas</p>
                    </div>
                </div>
            </div>
        </form>
    </main>
</div>

<script>
    const jenisSampah = <?= json_encode($jenis_sampah) ?>;
    let itemIndex = 0;

    function addItem() {
        const wrap = document.getElementById('wrapItems');
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2 item-row bg-slate-50 p-3 rounded-xl border border-slate-100';
        
        let options = '<option value="">Pilih Jenis</option>';
        jenisSampah.forEach(js => {
            options += `<option value="${js.id}" data-harga="${js.harga_per_kg}">${js.nama} (${js.kategori})</option>`;
        });

        div.innerHTML = `
            <div class="flex-1"><select name="items[${itemIndex}][jenis_id]" class="form-select item-jenis text-sm border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 w-full" required>${options}</select></div>
            <div class="w-24"><input type="number" name="items[${itemIndex}][berat]" step="0.1" min="0.1" class="form-control item-berat text-sm border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 w-full" placeholder="Kg" required></div>
            <div class="w-28 text-right font-semibold text-sm text-slate-500 item-subtotal">Rp 0</div>
            <button type="button" class="text-red-300 hover:text-red-500 transition text-lg btnHapus"><i class="fas fa-times-circle"></i></button>
        `;
        wrap.appendChild(div);
        itemIndex++; hitungUlang();
    }

    document.getElementById('wrapItems').addEventListener('input', function(e) { if (e.target.classList.contains('item-jenis') || e.target.classList.contains('item-berat')) { hitungUlang(); } });
    document.getElementById('wrapItems').addEventListener('click', function(e) { if (e.target.closest('.btnHapus')) { e.target.closest('.item-row').remove(); hitungUlang(); } });
    document.getElementById('btnTambahItem').addEventListener('click', addItem);

    function hitungUlang() {
        let totalBerat = 0, totalHarga = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const select = row.querySelector('.item-jenis'); const inputBerat = row.querySelector('.item-berat'); const inputSubtotal = row.querySelector('.item-subtotal');
            const opt = select.options[select.selectedIndex]; const harga = parseFloat(opt.getAttribute('data-harga')) || 0; const berat = parseFloat(inputBerat.value) || 0;
            const subHarga = berat * harga; inputSubtotal.textContent = "Rp " + subHarga.toLocaleString('id-ID');
            totalBerat += berat; totalHarga += subHarga;
        });
        document.getElementById('sumBerat').innerText = totalBerat.toFixed(1) + " Kg";
        document.getElementById('sumHarga').innerText = "Rp " + totalHarga.toLocaleString('id-ID');
    }
    addItem();
</script>
<?php include 'includes/footer.php'; ?>