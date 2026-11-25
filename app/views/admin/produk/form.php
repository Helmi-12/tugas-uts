<?php
$isEdit = !empty($product);
$pageTitle = $isEdit ? 'Edit Produk Alternatif - Admin SPK' : 'Tambah Produk Alternatif - Admin SPK';
ob_start();

$criteriaMap = [];
if ($isEdit && !empty($product['criteria_values'])) {
    foreach ($product['criteria_values'] as $cv) {
        $criteriaMap[$cv['kriteria_id']] = $cv['nilai'];
    }
}
?>

<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between pb-4 border-b border-slate-800">
        <div>
            <h2 class="text-xl font-bold text-white font-['Plus_Jakarta_Sans']"><?= $isEdit ? 'Edit Produk &amp; Matriks SAW' : 'Tambah Produk Alternatif Baru' ?></h2>
            <p class="text-xs text-slate-400">Pastikan dimensi fisik dan skor kriteria diisi dengan akurat untuk kalkulasi kargo dan SPK.</p>
        </div>
        <a href="index.php?action=admin_produk" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-800 hover:bg-slate-700 text-slate-300">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali
        </a>
    </div>

    <form method="POST" action="index.php?action=admin_produk_save" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="id" value="<?= $product['id'] ?? '' ?>">

        <!-- Section 1: Informasi Produk & Spesifikasi -->
        <div class="bg-slate-800/80 rounded-2xl border border-slate-700/80 p-6 space-y-4">
            <h3 class="text-sm font-bold text-brand-400 uppercase tracking-wider">1. Informasi Umum &amp; Spesifikasi Bahan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Kategori Mebel:</label>
                    <select name="kategori_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (($product['kategori_id'] ?? 0) == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nama_kategori']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Kode SKU Produk:</label>
                    <input type="text" name="sku" required value="<?= htmlspecialchars($product['sku'] ?? 'ALUM-' . rand(100, 999)) ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Nama Produk Alternatif:</label>
                <input type="text" name="nama_produk" required value="<?= htmlspecialchars($product['nama_produk'] ?? '') ?>" placeholder="Contoh: Kitchen Set Alexindo Wood Grain Premium" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Harga (Rp):</label>
                    <input type="number" step="50000" name="harga" id="inputHarga" required value="<?= $product['harga'] ?? 4500000 ?>" oninput="syncC1(this.value)" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-amber-400 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Ketebalan:</label>
                    <input type="text" name="ketebalan" value="<?= htmlspecialchars($product['ketebalan'] ?? '1.2 mm') ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Merk Aluminium:</label>
                    <input type="text" name="merk" value="<?= htmlspecialchars($product['merk'] ?? 'Alexindo') ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Masa Garansi:</label>
                    <input type="text" name="garansi" value="<?= htmlspecialchars($product['garansi'] ?? '3 Tahun') ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Variasi Finishing / Warna:</label>
                <input type="text" name="finishing" value="<?= htmlspecialchars($product['finishing'] ?? 'Anodized Wood Grain (Urat Kayu)') ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Deskripsi Spesifikasi Teknis:</label>
                <textarea name="deskripsi_spesifikasi" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white"><?= htmlspecialchars($product['deskripsi_spesifikasi'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- Section 2: Dimensi & Logistik Kargo (Dokumen 1) -->
        <div class="bg-slate-800/80 rounded-2xl border border-slate-700/80 p-6 space-y-4">
            <h3 class="text-sm font-bold text-cyan-400 uppercase tracking-wider">2. Dimensi Fisik Kargo Volumetrik &amp; Visual 3D</h3>

            <div class="grid grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Panjang (cm):</label>
                    <input type="number" step="1" name="panjang_cm" required value="<?= $product['panjang_cm'] ?? 180 ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Lebar (cm):</label>
                    <input type="number" step="1" name="lebar_cm" required value="<?= $product['lebar_cm'] ?? 60 ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Tinggi (cm):</label>
                    <input type="number" step="1" name="tinggi_cm" required value="<?= $product['tinggi_cm'] ?? 85 ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Berat Aktual (kg):</label>
                    <input type="number" step="0.5" name="berat_aktual_kg" required value="<?= $product['berat_aktual_kg'] ?? 38.0 ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Upload Berkas Foto Baru (JPG/PNG/SVG):</label>
                    <input type="file" name="foto_file" accept="image/*" class="w-full px-3 py-2 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-slate-300 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-600 file:text-white hover:file:bg-brand-700">
                    <input type="text" name="foto" value="<?= htmlspecialchars($product['foto'] ?? 'assets/images/prod-kitchen-1.svg') ?>" placeholder="Atau path aset (assets/images/...)" class="w-full mt-2 px-3.5 py-2 rounded-xl border border-slate-700 bg-slate-950 text-xs font-mono text-slate-400">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">URL File 3D (GLB / WebAR):</label>
                    <input type="text" name="model_glb_url" value="<?= htmlspecialchars($product['model_glb_url'] ?? 'assets/models/kitchen_set.glb') ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white">
                </div>
            </div>
        </div>

        <!-- Section 3: Nilai Matriks Keputusan X untuk SAW (Blueprint Section 2.1 & 4) -->
        <div class="bg-slate-800/80 rounded-2xl border border-slate-700/80 p-6 space-y-4">
            <h3 class="text-sm font-bold text-amber-400 uppercase tracking-wider">3. Nilai Matriks Keputusan ($X$) untuk SPK SAW</h3>
            <p class="text-xs text-slate-400">Masukkan nilai numerik parameter yang akan dinormalisasi ke dalam matriks $R$.</p>

            <div class="space-y-3">
                <!-- C1 -->
                <div class="flex items-center justify-between p-3 bg-slate-900 rounded-xl border border-slate-800">
                    <div>
                        <strong class="text-xs text-amber-400 font-bold">C1: Harga Produk (Cost)</strong>
                        <span class="block text-[11px] text-slate-400">Nilai riil rupiah (Cost: Semakin murah semakin tinggi skor)</span>
                    </div>
                    <input type="number" name="kriteria[1]" id="c1Input" readonly value="<?= $criteriaMap[1] ?? ($product['harga'] ?? 4500000) ?>" class="w-40 px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-950 text-xs font-mono font-bold text-amber-400 text-right">
                </div>

                <!-- C2 -->
                <div class="flex items-center justify-between p-3 bg-slate-900 rounded-xl border border-slate-800">
                    <div>
                        <strong class="text-xs text-emerald-400 font-bold">C2: Ketebalan Aluminium (Benefit)</strong>
                        <span class="block text-[11px] text-slate-400">Skala 1: 0.8mm | 2: 1.0mm | 3: 1.2mm | 4: 1.5mm</span>
                    </div>
                    <select name="kriteria[2]" class="w-40 px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-950 text-xs font-bold text-white text-right">
                        <option value="1" <?= (($criteriaMap[2] ?? 3) == 1) ? 'selected' : '' ?>>1 (0.8 mm)</option>
                        <option value="2" <?= (($criteriaMap[2] ?? 3) == 2) ? 'selected' : '' ?>>2 (1.0 mm)</option>
                        <option value="3" <?= (($criteriaMap[2] ?? 3) == 3) ? 'selected' : '' ?>>3 (1.2 mm)</option>
                        <option value="4" <?= (($criteriaMap[2] ?? 3) == 4) ? 'selected' : '' ?>>4 (1.5 mm)</option>
                    </select>
                </div>

                <!-- C3 -->
                <div class="flex items-center justify-between p-3 bg-slate-900 rounded-xl border border-slate-800">
                    <div>
                        <strong class="text-xs text-cyan-400 font-bold">C3: Merk / Kualitas Material (Benefit)</strong>
                        <span class="block text-[11px] text-slate-400">Skala 1: Lokal/Ekonomis | 2: Dacon | 3: Alexindo | 4: YKK</span>
                    </div>
                    <select name="kriteria[3]" class="w-40 px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-950 text-xs font-bold text-white text-right">
                        <option value="1" <?= (($criteriaMap[3] ?? 3) == 1) ? 'selected' : '' ?>>1 (Lokal)</option>
                        <option value="2" <?= (($criteriaMap[3] ?? 3) == 2) ? 'selected' : '' ?>>2 (Dacon)</option>
                        <option value="3" <?= (($criteriaMap[3] ?? 3) == 3) ? 'selected' : '' ?>>3 (Alexindo)</option>
                        <option value="4" <?= (($criteriaMap[3] ?? 3) == 4) ? 'selected' : '' ?>>4 (YKK)</option>
                    </select>
                </div>

                <!-- C4 -->
                <div class="flex items-center justify-between p-3 bg-slate-900 rounded-xl border border-slate-800">
                    <div>
                        <strong class="text-xs text-purple-400 font-bold">C4: Ketahanan / Garansi (Benefit)</strong>
                        <span class="block text-[11px] text-slate-400">Skala 1: 1 Tahun | 2: 2 Tahun | 3: 3 Tahun | 4: 5 Tahun</span>
                    </div>
                    <select name="kriteria[4]" class="w-40 px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-950 text-xs font-bold text-white text-right">
                        <option value="1" <?= (($criteriaMap[4] ?? 3) == 1) ? 'selected' : '' ?>>1 (1 Tahun)</option>
                        <option value="2" <?= (($criteriaMap[4] ?? 3) == 2) ? 'selected' : '' ?>>2 (2 Tahun)</option>
                        <option value="3" <?= (($criteriaMap[4] ?? 3) == 3) ? 'selected' : '' ?>>3 (3 Tahun)</option>
                        <option value="4" <?= (($criteriaMap[4] ?? 3) == 4) ? 'selected' : '' ?>>4 (5 Tahun)</option>
                    </select>
                </div>

                <!-- C5 -->
                <div class="flex items-center justify-between p-3 bg-slate-900 rounded-xl border border-slate-800">
                    <div>
                        <strong class="text-xs text-pink-400 font-bold">C5: Variasi Finishing / Warna (Benefit)</strong>
                        <span class="block text-[11px] text-slate-400">Skala 1: Silver Anodize | 2: Powder Coating | 3: Urat Kayu</span>
                    </div>
                    <select name="kriteria[5]" class="w-40 px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-950 text-xs font-bold text-white text-right">
                        <option value="1" <?= (($criteriaMap[5] ?? 3) == 1) ? 'selected' : '' ?>>1 (Silver)</option>
                        <option value="2" <?= (($criteriaMap[5] ?? 3) == 2) ? 'selected' : '' ?>>2 (Powder Coating)</option>
                        <option value="3" <?= (($criteriaMap[5] ?? 3) == 3) ? 'selected' : '' ?>>3 (Urat Kayu)</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full py-3.5 rounded-xl font-bold bg-brand-600 hover:bg-brand-700 text-white text-sm shadow-lg shadow-brand-500/25 transition">
            <i class="fa-solid fa-floppy-disk mr-2"></i> <?= $isEdit ? 'Simpan Perubahan Produk' : 'Tambahkan Produk Alternatif' ?>
        </button>
    </form>
</div>

<script>
    function syncC1(val) {
        document.getElementById('c1Input').value = val;
    }
</script>

<?php
$adminContent = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin_layout.php';
?>

