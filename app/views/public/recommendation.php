<?php
$pageTitle = 'Hitung Budget & Rekomendasi SAW - MebelAlum';
require_once __DIR__ . '/../layouts/header.php';

$selectedCatId = (int)($_GET['kategori_id'] ?? 1);
$minBudget = isset($_GET['min_budget']) ? (float)$_GET['min_budget'] : 2000000;
$maxBudget = isset($_GET['max_budget']) ? (float)$_GET['max_budget'] : 9000000;
?>

<div class="bg-gradient-to-b from-slate-900 to-slate-800 text-white py-12 border-b border-slate-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 mb-4">
                <i class="fa-solid fa-wand-magic-sparkles mr-2 text-amber-400"></i> Engine SPK Simple Additive Weighting (SAW)
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight font-['Plus_Jakarta_Sans']">
                Kalkulator Anggaran &amp; Rekomendasi Mebel
            </h1>
            <p class="text-slate-300 text-sm sm:text-base mt-2 leading-relaxed">
                Tentukan kategori dan alokasi anggaran Anda. Engine SAW akan memfilter variasi mebel aluminium dan merangking kombinasi spesifikasi bahan terbaik untuk Anda.
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Form Filter Input Budget (Blueprint Section 5.1) -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200/80 p-6 -mt-16 relative z-10">
        <form method="GET" action="index.php" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
            <input type="hidden" name="action" value="rekomendasi">

            <!-- Field 1: Kategori Mebel -->
            <div class="md:col-span-4">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    <i class="fa-solid fa-layer-group text-brand-600 mr-1.5"></i> Jenis Mebel Aluminium
                </label>
                <select name="kategori_id" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm font-semibold bg-slate-50">
                    <option value="0">-- Semua Kategori Mebel --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($selectedCatId == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Field 2: Budget Minimal -->
            <div class="md:col-span-3">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    <i class="fa-solid fa-money-bill-wave text-emerald-600 mr-1.5"></i> Budget Minimal (Rp)
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-3 text-xs font-bold text-slate-400">Rp</span>
                    <input type="number" name="min_budget" step="100000" min="500000" value="<?= (int)$minBudget ?>" required class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm font-semibold">
                </div>
            </div>

            <!-- Field 3: Budget Maksimal -->
            <div class="md:col-span-3">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    <i class="fa-solid fa-wallet text-amber-600 mr-1.5"></i> Budget Maksimal (Rp)
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-3 text-xs font-bold text-slate-400">Rp</span>
                    <input type="number" name="max_budget" step="100000" min="1000000" value="<?= (int)$maxBudget ?>" required class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm font-semibold">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="md:col-span-2">
                <button type="submit" class="w-full py-3 px-4 rounded-xl text-sm font-bold bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 shadow-md shadow-amber-500/20 transition-all flex items-center justify-center">
                    <i class="fa-solid fa-magnifying-glass mr-2"></i> Cari Rekomendasi
                </button>
            </div>
        </form>

        <!-- Quick Budget Presets -->
        <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap items-center gap-2 text-xs text-slate-500">
            <span class="font-semibold text-slate-700">Preset Budget Populer:</span>
            <a href="index.php?action=rekomendasi&kategori_id=<?= $selectedCatId ?>&min_budget=1000000&max_budget=3500000" class="px-2.5 py-1 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                Hemat (1 - 3.5 Juta)
            </a>
            <a href="index.php?action=rekomendasi&kategori_id=<?= $selectedCatId ?>&min_budget=3500000&max_budget=6500000" class="px-2.5 py-1 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                Standar Menengah (3.5 - 6.5 Juta)
            </a>
            <a href="index.php?action=rekomendasi&kategori_id=<?= $selectedCatId ?>&min_budget=6000000&max_budget=10000000" class="px-2.5 py-1 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                Premium / High-End (6 - 10 Juta)
            </a>
        </div>
    </div>

    <!-- Hasil Perhitungan Rekomendasi SAW -->
    <div class="mt-12">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200 mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 font-['Plus_Jakarta_Sans']">
                    Hasil Perangkingan Rekomendasi SAW
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Ditemukan <strong><?= count($sawData['results'] ?? []) ?></strong> produk yang memenuhi rentang budget Rp <?= number_format($minBudget, 0, ',', '.') ?> &mdash; Rp <?= number_format($maxBudget, 0, ',', '.') ?>.
                </p>
            </div>
            
            <?php if (!empty($sawData['results'])): ?>
                <button onclick="document.getElementById('matrixBreakdown').scrollIntoView({behavior: 'smooth'})" class="inline-flex items-center text-xs font-bold text-brand-600 hover:text-brand-800 bg-brand-50 px-3.5 py-2 rounded-lg border border-brand-200">
                    <i class="fa-solid fa-table mr-2"></i> Lihat Transparansi Matriks SAW (X, R, V)
                </button>
            <?php endif; ?>
        </div>

        <?php if (empty($sawData['results'])): ?>
            <!-- Empty state -->
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-10 text-center max-w-xl mx-auto my-12">
                <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Tidak Ditemukan Alternatif pada Rentang Budget Ini</h3>
                <p class="text-sm text-slate-600 mb-6">
                    Cobalah untuk memperlebar batas rentang anggaran Anda (misal min: Rp 1.000.000, max: Rp 8.000.000) atau pilih opsi "-- Semua Kategori Mebel --".
                </p>
                <a href="index.php?action=rekomendasi&kategori_id=0&min_budget=1000000&max_budget=9000000" class="inline-flex items-center px-5 py-2.5 rounded-xl font-bold bg-amber-500 hover:bg-amber-600 text-slate-950 text-sm shadow">
                    Reset Filter Pencarian
                </a>
            </div>
        <?php else: ?>
            <!-- Cards Rekomendasi Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <?php foreach ($sawData['results'] as $idx => $item): 
                    $p = $item['produk'];
                    $rank = $item['rank'];
                    $scoreV = $item['score_v'];
                    $matchPercent = $item['match_percent'];
                    $isWinner = ($rank === 1);
                ?>
                    <div class="bg-white rounded-2xl overflow-hidden border <?= $isWinner ? 'border-amber-400 ring-2 ring-amber-400 shadow-xl' : 'border-slate-200 shadow-sm' ?> hover:shadow-2xl transition-all duration-300 flex flex-col relative group">
                        
                        <!-- Top Banner Badge -->
                        <?php if ($isWinner): ?>
                            <div class="bg-gradient-to-r from-amber-500 via-amber-600 to-amber-500 text-slate-950 font-black text-xs uppercase tracking-wider py-1.5 px-4 text-center shadow flex items-center justify-center">
                                <i class="fa-solid fa-crown mr-1.5 text-slate-950"></i> Rekomendasi Terbaik #1
                            </div>
                        <?php endif; ?>

                        <!-- Image & Floating Badge -->
                        <div class="h-56 bg-slate-100 relative overflow-hidden">
                            <img src="<?= htmlspecialchars($p['foto'] ?: 'assets/images/prod-kitchen-1.svg') ?>" alt="<?= htmlspecialchars($p['nama_produk']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>

                            <!-- Rank Tag -->
                            <div class="absolute top-3 left-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black shadow-md <?= ($rank == 1) ? 'bg-amber-500 text-slate-950' : (($rank == 2) ? 'bg-slate-300 text-slate-900' : 'bg-slate-700 text-white') ?>">
                                    Rank #<?= $rank ?>
                                </span>
                            </div>

                            <!-- Match Score -->
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-900/80 text-cyan-300 backdrop-blur-md border border-cyan-500/30">
                                    <i class="fa-solid fa-chart-simple mr-1.5"></i> Match: <?= $matchPercent ?>%
                                </span>
                            </div>

                            <!-- Bottom Image Title Info -->
                            <div class="absolute bottom-3 left-4 right-4 text-white">
                                <span class="text-[11px] font-semibold text-brand-300 uppercase tracking-wider block">
                                    <?= htmlspecialchars($p['nama_kategori']) ?>
                                </span>
                                <h3 class="text-base font-bold truncate">
                                    <?= htmlspecialchars($p['nama_produk']) ?>
                                </h3>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex-1 flex flex-col justify-between space-y-5">
                            <div>
                                <!-- Price -->
                                <div class="flex items-baseline justify-between mb-4">
                                    <span class="text-xs text-slate-500">Estimasi Biaya:</span>
                                    <span class="text-2xl font-black text-slate-900 font-['Plus_Jakarta_Sans']">
                                        Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                                    </span>
                                </div>

                                <!-- Key Specifications Grid -->
                                <div class="grid grid-cols-2 gap-2 text-xs bg-slate-50 p-3.5 rounded-xl border border-slate-200/80">
                                    <div>
                                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Ketebalan:</span>
                                        <span class="font-bold text-slate-800"><i class="fa-solid fa-ruler-combined text-brand-500 mr-1"></i> <?= htmlspecialchars($p['ketebalan']) ?></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Merk:</span>
                                        <span class="font-bold text-slate-800"><i class="fa-solid fa-award text-amber-500 mr-1"></i> <?= htmlspecialchars($p['merk']) ?></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Garansi:</span>
                                        <span class="font-bold text-slate-800"><i class="fa-solid fa-shield-halved text-emerald-500 mr-1"></i> <?= htmlspecialchars($p['garansi']) ?></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Finishing:</span>
                                        <span class="font-bold text-slate-800 truncate block"><i class="fa-solid fa-palette text-purple-500 mr-1"></i> <?= htmlspecialchars($p['finishing']) ?></span>
                                    </div>
                                </div>

                                <!-- Short Description -->
                                <p class="text-xs text-slate-600 mt-3 line-clamp-2 leading-relaxed">
                                    <?= htmlspecialchars($p['deskripsi_spesifikasi']) ?>
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-2 pt-2 border-t border-slate-100">
                                <!-- WhatsApp Pesan Button (Formatted Message) -->
                                <button onclick="sendOrderWhatsApp(<?= htmlspecialchars(json_encode($p)) ?>, <?= $rank ?>, <?= $matchPercent ?>)" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition-all flex items-center justify-center">
                                    <i class="fa-brands fa-whatsapp text-sm mr-2"></i> Konsultasi &amp; Pesan via WA
                                </button>

                                <div class="grid grid-cols-2 gap-2">
                                    <!-- Detail & 3D WebAR Button -->
                                    <a href="index.php?action=produk_detail&id=<?= $p['id'] ?>" class="py-2.5 px-3 rounded-xl text-xs font-bold bg-brand-50 hover:bg-brand-100 text-brand-700 text-center transition-colors">
                                        <i class="fa-solid fa-cube mr-1"></i> Visual 3D
                                    </a>
                                    <!-- Checkout DP Button -->
                                    <a href="index.php?action=produk_detail&id=<?= $p['id'] ?>#checkout" class="py-2.5 px-3 rounded-xl text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white text-center transition-colors">
                                        <i class="fa-solid fa-credit-card mr-1"></i> Bayar DP
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Transparansi Matriks SAW (X, R, V) - Essential for Skripsi Bab 4 -->
            <div id="matrixBreakdown" class="mt-16 bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-slate-200 gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-purple-600 bg-purple-50 px-3 py-1 rounded-full border border-purple-200">
                            Verifikasi Matematis Bab 4 Skripsi
                        </span>
                        <h3 class="text-2xl font-black text-slate-900 font-['Plus_Jakarta_Sans'] mt-2">
                            Transparansi Perhitungan Matriks SAW
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">
                            Langkah demi langkah normalisasi dan pembobotan matematis SAW sesuai dokumen Blueprint.
                        </p>
                    </div>
                    <a href="index.php?action=admin_saw_matrix" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white transition-all shadow-sm flex items-center">
                        <i class="fa-solid fa-print mr-2"></i> Cetak Format Skripsi Lengkap
                    </a>
                </div>

                <!-- 1. Matriks Keputusan X -->
                <div class="mt-8">
                    <h4 class="text-sm font-bold text-slate-800 mb-3 flex items-center">
                        <span class="w-6 h-6 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-xs font-black mr-2">1</span>
                        Matriks Keputusan ($X$) dari Alternatif Terpilih
                    </h4>
                    <div class="overflow-x-auto rounded-xl border border-slate-200">
                        <table class="matrix-table text-xs">
                            <thead>
                                <tr>
                                    <th>Alternatif</th>
                                    <th>C1: Harga (Cost)</th>
                                    <th>C2: Ketebalan (Benefit)</th>
                                    <th>C3: Merk (Benefit)</th>
                                    <th>C4: Garansi (Benefit)</th>
                                    <th>C5: Finishing (Benefit)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sawData['results'] as $res): 
                                    $p = $res['produk'];
                                    $x = $res['raw_x'];
                                ?>
                                    <tr>
                                        <td class="font-bold text-slate-800"><?= htmlspecialchars($p['nama_produk']) ?></td>
                                        <td>Rp <?= number_format($x[1] ?? 0, 0, ',', '.') ?></td>
                                        <td><?= $x[2] ?? 0 ?> (<?= htmlspecialchars($p['ketebalan']) ?>)</td>
                                        <td><?= $x[3] ?? 0 ?> (<?= htmlspecialchars($p['merk']) ?>)</td>
                                        <td><?= $x[4] ?? 0 ?> (<?= htmlspecialchars($p['garansi']) ?>)</td>
                                        <td><?= $x[5] ?? 0 ?> (<?= htmlspecialchars($p['finishing']) ?>)</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 2. Matriks Normalisasi R -->
                <div class="mt-8">
                    <h4 class="text-sm font-bold text-slate-800 mb-3 flex items-center">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-black mr-2">2</span>
                        Matriks Normalisasi ($R$) Berdasarkan Sifat Kriteria
                    </h4>
                    <div class="overflow-x-auto rounded-xl border border-slate-200">
                        <table class="matrix-table text-xs">
                            <thead>
                                <tr>
                                    <th>Alternatif</th>
                                    <th>r_i1 (Cost)</th>
                                    <th>r_i2 (Benefit)</th>
                                    <th>r_i3 (Benefit)</th>
                                    <th>r_i4 (Benefit)</th>
                                    <th>r_i5 (Benefit)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sawData['results'] as $res): 
                                    $p = $res['produk'];
                                    $r = $res['normalized_r'];
                                ?>
                                    <tr>
                                        <td class="font-bold text-slate-800"><?= htmlspecialchars($p['nama_produk']) ?></td>
                                        <td class="font-mono"><?= number_format($r[1] ?? 0, 4) ?></td>
                                        <td class="font-mono"><?= number_format($r[2] ?? 0, 4) ?></td>
                                        <td class="font-mono"><?= number_format($r[3] ?? 0, 4) ?></td>
                                        <td class="font-mono"><?= number_format($r[4] ?? 0, 4) ?></td>
                                        <td class="font-mono"><?= number_format($r[5] ?? 0, 4) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. Nilai Akhir Perangkingan V -->
                <div class="mt-8">
                    <h4 class="text-sm font-bold text-slate-800 mb-3 flex items-center">
                        <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-black mr-2">3</span>
                        Nilai Akhir Perangkingan ($V_i = \sum w_j \cdot r_{ij}$)
                    </h4>
                    <div class="overflow-x-auto rounded-xl border border-slate-200">
                        <table class="matrix-table text-xs">
                            <thead>
                                <tr>
                                    <th>Peringkat</th>
                                    <th>Nama Alternatif</th>
                                    <th>Formula Pembobotan ($V_i$)</th>
                                    <th>Skor Akhir ($V$)</th>
                                    <th>Match (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sawData['results'] as $res): 
                                    $p = $res['produk'];
                                    $wR = $res['weighted_r'];
                                ?>
                                    <tr class="<?= ($res['rank'] == 1) ? 'highlight-cell' : '' ?>">
                                        <td class="font-black text-center">#<?= $res['rank'] ?></td>
                                        <td class="font-bold"><?= htmlspecialchars($p['nama_produk']) ?></td>
                                        <td class="font-mono text-[11px]">
                                            (0.30&times;<?= number_format($res['normalized_r'][1] ?? 0, 2) ?>) +
                                            (0.25&times;<?= number_format($res['normalized_r'][2] ?? 0, 2) ?>) +
                                            (0.20&times;<?= number_format($res['normalized_r'][3] ?? 0, 2) ?>) +
                                            (0.15&times;<?= number_format($res['normalized_r'][4] ?? 0, 2) ?>) +
                                            (0.10&times;<?= number_format($res['normalized_r'][5] ?? 0, 2) ?>)
                                        </td>
                                        <td class="font-mono font-bold text-brand-700"><?= number_format($res['score_v'], 4) ?></td>
                                        <td class="font-bold text-emerald-600"><?= $res['match_percent'] ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function sendOrderWhatsApp(product, rank, matchPercent) {
        const url = SawClient.formatWhatsAppMessage(product, rank, matchPercent);
        window.open(url, '_blank');
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

