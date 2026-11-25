<?php
$pageTitle = 'Stok Barang & Catatan Stok - NJ Aluminium';
ob_start();

$barangList = $barangList ?? [];
$riwayatStok = $riwayatStok ?? [];

$aksiLabel = [
    'awal'     => ['label' => 'Stok Awal', 'class' => 'bg-sky-500/20 text-sky-300 border-sky-500/30'],
    'tambah'   => ['label' => 'Tambah Stok (+)', 'class' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30'],
    'kurang'   => ['label' => 'Kurangi Stok (-)', 'class' => 'bg-rose-500/20 text-rose-300 border-rose-500/30'],
    'set_sisa' => ['label' => 'Catat Sisa Fisik', 'class' => 'bg-amber-500/20 text-amber-300 border-amber-500/30'],
];
?>

<!-- Header Section -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-white font-['Plus_Jakarta_Sans']">Manajemen Stok Barang &amp; Catatan</h2>
        <p class="text-xs text-slate-400 mt-0.5">
            Catat penambahan stok, pengurangan pemakaian, atau sisa stok aktual beserta catatan keterangan.
        </p>
    </div>
    <div class="flex items-center space-x-3 text-xs">
        <div class="px-3.5 py-2 rounded-xl bg-slate-800 border border-slate-700 text-slate-300">
            Total Jenis Barang: <strong class="text-white"><?= count($barangList) ?></strong>
        </div>
    </div>
</div>

<!-- Form Input Grid: Catat/Sesuaikan Stok & Tambah Barang Baru -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6">

    <!-- Form 1: Input Catatan / Penyesuaian / Sisa Stok (7 Kolom) -->
    <div class="lg:col-span-7 bg-slate-800/80 rounded-2xl p-6 border border-slate-700/80 shadow-sm">
        <div class="pb-4 border-b border-slate-700 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-white font-['Plus_Jakarta_Sans']">
                    <i class="fa-solid fa-pen-to-square text-brand-400 mr-1.5"></i> Input Perubahan / Sisa Stok
                </h3>
                <p class="text-[11px] text-slate-400 mt-0.5">
                    Pilih barang, tentukan aksi (tambah, kurangi, atau catat sisa stok), lalu isi catatan.
                </p>
            </div>
        </div>

        <?php if (empty($barangList)): ?>
            <div class="mt-4 p-4 rounded-xl bg-amber-500/10 border border-amber-500/30 text-xs text-amber-300">
                Belum ada data barang. Silakan tambahkan barang terlebih dahulu pada form di sebelah kanan.
            </div>
        <?php else: ?>
            <form method="POST" action="index.php?action=admin_stok_save" class="mt-4 space-y-4">
                <input type="hidden" name="mode" value="sesuaikan">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Pilih Barang / Bahan</label>
                        <select name="stok_barang_id" id="selectBarangStok" onchange="updateStokInfo()" required
                                class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-xs text-white focus:ring-2 focus:ring-brand-500 focus:outline-none">
                            <?php foreach ($barangList as $b): ?>
                                <option value="<?= (int)$b['id'] ?>"
                                        data-stok="<?= (int)$b['jumlah_stok'] ?>"
                                        data-satuan="<?= htmlspecialchars($b['satuan']) ?>">
                                    <?= htmlspecialchars($b['nama_barang']) ?> (Sisa: <?= (int)$b['jumlah_stok'] ?> <?= htmlspecialchars($b['satuan']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p id="infoStokAktif" class="text-[11px] text-cyan-400 mt-1"></p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Jenis Pencatatan</label>
                        <select name="tipe_aksi" id="selectTipeAksi" onchange="updateLabelJumlah()" required
                                class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-xs text-white focus:ring-2 focus:ring-brand-500 focus:outline-none">
                            <option value="tambah">Tambah Stok Masuk (+)</option>
                            <option value="kurang">Kurangi Stok Keluar (-)</option>
                            <option value="set_sisa">Catat Sisa Stok Aktual (=)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label id="labelJumlahInput" class="block text-xs font-bold text-slate-300 mb-1.5">Jumlah Penambahan</label>
                        <input type="number" name="jumlah" min="0" step="1" required placeholder="Contoh: 10"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-xs text-white focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Catatan Keterangan</label>
                        <input type="text" name="catatan" placeholder="Contoh: Sisa stok gudang / Restok hollow"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-xs text-white focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>
                </div>

                <div class="pt-1 flex justify-end">
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl font-bold bg-brand-600 hover:bg-brand-500 text-white text-xs shadow-md shadow-brand-600/20 transition flex items-center">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Catatan Stok
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <!-- Form 2: Tambah Item Barang Baru (5 Kolom) -->
    <div class="lg:col-span-5 bg-slate-800/80 rounded-2xl p-6 border border-slate-700/80 shadow-sm">
        <div class="pb-4 border-b border-slate-700">
            <h3 class="text-sm font-bold text-white font-['Plus_Jakarta_Sans']">
                <i class="fa-solid fa-plus-circle text-emerald-400 mr-1.5"></i> Tambah Barang Baru
            </h3>
            <p class="text-[11px] text-slate-400 mt-0.5">
                Daftarkan material atau produk baru beserta stok awal dan catatannya.
            </p>
        </div>

        <form method="POST" action="index.php?action=admin_stok_save" class="mt-4 space-y-3.5">
            <input type="hidden" name="mode" value="tambah_barang">

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Nama Barang</label>
                <input type="text" name="nama_barang_baru" required placeholder="Contoh: Kusen Aluminium 4 inch"
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-xs text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Satuan</label>
                    <input type="text" name="satuan_baru" value="batang" placeholder="unit / batang / lembar"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-xs text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Stok Awal</label>
                    <input type="number" name="stok_awal" min="0" value="0" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-xs text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Catatan Awal</label>
                <input type="text" name="catatan" placeholder="Contoh: Stok pembukaan gudang"
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-xs text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="pt-1 flex justify-end">
                <button type="submit"
                        class="w-full py-2.5 rounded-xl font-bold bg-emerald-600 hover:bg-emerald-500 text-white text-xs shadow-md shadow-emerald-600/20 transition flex items-center justify-center">
                    <i class="fa-solid fa-box mr-2"></i> Tambah Barang ke Daftar Stok
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Daftar Sisa Stok Barang Saat Ini -->
<div class="mt-8 bg-slate-800/80 rounded-2xl border border-slate-700/80 p-6 shadow-sm">
    <div class="pb-4 border-b border-slate-700">
        <h3 class="text-base font-bold text-white font-['Plus_Jakarta_Sans']">Daftar Sisa Stok Barang Tersedia</h3>
        <p class="text-xs text-slate-400">Ringkasan jumlah stok terkini per item barang</p>
    </div>

    <div class="mt-4 overflow-x-auto">
        <table class="w-full text-xs text-left text-slate-300">
            <thead class="text-slate-400 border-b border-slate-700">
                <tr>
                    <th class="pb-3">#</th>
                    <th class="pb-3">Nama Barang</th>
                    <th class="pb-3">Sisa Stok</th>
                    <th class="pb-3">Satuan</th>
                    <th class="pb-3">Terakhir Diperbarui</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/60">
                <?php if (empty($barangList)): ?>
                    <tr>
                        <td colspan="5" class="py-6 text-center text-slate-500">Belum ada barang yang tercatat di stok.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($barangList as $i => $b): ?>
                        <?php $stokAngka = (int)$b['jumlah_stok']; ?>
                        <tr>
                            <td class="py-3 text-slate-400"><?= $i + 1 ?></td>
                            <td class="py-3 font-bold text-white"><?= htmlspecialchars($b['nama_barang']) ?></td>
                            <td class="py-3 font-mono font-bold">
                                <span class="px-2.5 py-1 rounded-lg <?= $stokAngka > 0 ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300' ?>">
                                    <?= $stokAngka ?>
                                </span>
                            </td>
                            <td class="py-3"><?= htmlspecialchars($b['satuan']) ?></td>
                            <td class="py-3 text-slate-400 font-mono text-[11px]"><?= htmlspecialchars($b['updated_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Riwayat & Catatan Perubahan Stok -->
<div class="mt-8 bg-slate-800/80 rounded-2xl border border-slate-700/80 p-6 shadow-sm">
    <div class="pb-4 border-b border-slate-700">
        <h3 class="text-base font-bold text-white font-['Plus_Jakarta_Sans']">Riwayat &amp; Catatan Stok</h3>
        <p class="text-xs text-slate-400">Catatan lengkap setiap penambahan, pengurangan, maupun pencatatan sisa stok</p>
    </div>

    <div class="mt-4 overflow-x-auto">
        <table class="w-full text-xs text-left text-slate-300">
            <thead class="text-slate-400 border-b border-slate-700">
                <tr>
                    <th class="pb-3">Waktu</th>
                    <th class="pb-3">Barang</th>
                    <th class="pb-3">Jenis</th>
                    <th class="pb-3">Perubahan</th>
                    <th class="pb-3">Sisa Setelahnya</th>
                    <th class="pb-3">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/60">
                <?php if (empty($riwayatStok)): ?>
                    <tr>
                        <td colspan="6" class="py-6 text-center text-slate-500">Belum ada catatan perubahan stok.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($riwayatStok as $log): ?>
                        <?php
                        $tipe = $log['tipe_aksi'] ?? 'tambah';
                        $badge = $aksiLabel[$tipe] ?? ['label' => $tipe, 'class' => 'bg-slate-700 text-slate-300 border-slate-600'];
                        $perubahan = (int)$log['perubahan'];
                        $teksPerubahan = ($perubahan > 0 ? '+' : '') . $perubahan;
                        ?>
                        <tr>
                            <td class="py-3 font-mono text-[11px] text-slate-400"><?= htmlspecialchars($log['created_at']) ?></td>
                            <td class="py-3 font-bold text-white"><?= htmlspecialchars($log['nama_barang']) ?></td>
                            <td class="py-3">
                                <span class="px-2 py-0.5 rounded border text-[10px] font-bold <?= $badge['class'] ?>">
                                    <?= htmlspecialchars($badge['label']) ?>
                                </span>
                            </td>
                            <td class="py-3 font-mono font-bold <?= $perubahan >= 0 ? 'text-emerald-400' : 'text-rose-400' ?>">
                                <?= $teksPerubahan ?> <?= htmlspecialchars($log['satuan']) ?>
                            </td>
                            <td class="py-3 font-mono font-bold text-white">
                                <?= (int)$log['stok_sesudah'] ?> <?= htmlspecialchars($log['satuan']) ?>
                            </td>
                            <td class="py-3 text-slate-300"><?= htmlspecialchars($log['catatan'] ?: '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function updateStokInfo() {
        const sel = document.getElementById('selectBarangStok');
        const info = document.getElementById('infoStokAktif');
        if (!sel || !info) return;
        const opt = sel.options[sel.selectedIndex];
        if (!opt) return;
        info.textContent = 'Stok saat ini: ' + opt.getAttribute('data-stok') + ' ' + opt.getAttribute('data-satuan');
    }

    function updateLabelJumlah() {
        const selAksi = document.getElementById('selectTipeAksi');
        const lbl = document.getElementById('labelJumlahInput');
        if (!selAksi || !lbl) return;
        if (selAksi.value === 'set_sisa') {
            lbl.textContent = 'Jumlah Sisa Stok Aktual';
        } else if (selAksi.value === 'kurang') {
            lbl.textContent = 'Jumlah Stok Dikurangi';
        } else {
            lbl.textContent = 'Jumlah Penambahan';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateStokInfo();
        updateLabelJumlah();
    });
</script>

<?php
$adminContent = ob_get_clean();
require_once __DIR__ . '/../layouts/admin_layout.php';
?>