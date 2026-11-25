<?php
$pageTitle = 'Laporan Matriks SAW (Bab 4 Skripsi) - Admin SPK';
ob_start();
?>

<!-- Print-Only Official Academic Header for Bab 4 Skripsi -->
<div class="hidden print:block text-slate-900 pb-6 mb-6 border-b-2 border-slate-900">
    <div class="text-center space-y-1">
        <h2 class="text-base font-bold uppercase tracking-wider">Lampiran Bab 4 Laporan Tugas Akhir / Skripsi</h2>
        <h1 class="text-xl font-extrabold uppercase">Rancang Bangun Sistem Pendukung Keputusan Pemilihan Produk Mebel Aluminium Berdasarkan Preferensi Anggaran dan Spesifikasi Menggunakan Metode SAW</h1>
        <p class="text-xs text-slate-600">Dosen Pembimbing / Penguji: Pak Rofi | Tanggal Cetak: <?= date('d F Y, H:i') ?> WIB</p>
    </div>
</div>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-slate-800 gap-4 no-print">
    <div>
        <span class="text-xs font-bold uppercase tracking-wider text-amber-400 bg-amber-500/10 px-2.5 py-1 rounded border border-amber-500/20">
            Bukti Ilmiah Bab 4 Skripsi
        </span>
        <h2 class="text-xl font-bold text-white font-['Plus_Jakarta_Sans'] mt-2">
            Laporan &amp; Simulasi Matriks SAW Lengkap
        </h2>
        <p class="text-xs text-slate-400 mt-0.5">
            Menampilkan dekomposisi Matriks Keputusan ($X$), Normalisasi ($R$), Pembobotan ($W$), dan Skor Akhir ($V$).
        </p>
    </div>
    <div class="flex items-center space-x-3">
        <!-- Filter Category for Report -->
        <form method="GET" action="index.php" class="flex items-center space-x-2">
            <input type="hidden" name="action" value="admin_saw_matrix">
            <select name="kategori_id" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white">
                <option value="0">-- Semua Kategori (18 Alternatif) --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (($selectedCatId ?? 0) == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nama_kategori']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <button onclick="window.print()" class="px-4 py-2 rounded-xl font-bold bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs shadow-md shadow-amber-500/20 flex items-center">
            <i class="fa-solid fa-print mr-2"></i> Cetak Laporan Bab 4
        </button>
    </div>
</div>

<div class="space-y-10 mt-8">

    <!-- 1. Kriteria & Bobot W -->
    <div class="bg-slate-800/80 rounded-2xl border border-slate-700/80 p-6 shadow-sm">
        <h3 class="text-sm font-bold text-white mb-3 flex items-center">
            <span class="w-6 h-6 rounded-full bg-brand-500/20 text-brand-400 flex items-center justify-center text-xs font-bold mr-2">1</span>
            Bobot Kriteria ($W_j$) dan Sifat Atribut
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 border-b border-slate-700">
                    <tr>
                        <th class="p-3">Kode Kriteria</th>
                        <th class="p-3">Nama Kriteria</th>
                        <th class="p-3">Sifat (Benefit / Cost)</th>
                        <th class="p-3 font-mono">Bobot ($W_j$)</th>
                        <th class="p-3">Persentase</th>
                        <th class="p-3">Nilai Ekstrem (Max / Min)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/60">
                    <?php foreach ($sawData['criteria'] as $c): 
                        $cId = $c['id'];
                        $ext = $sawData['extrema'][$cId] ?? ['min' => 0, 'max' => 0];
                    ?>
                        <tr>
                            <td class="p-3 font-mono font-bold text-amber-400"><?= $c['kode_kriteria'] ?></td>
                            <td class="p-3 font-bold text-white"><?= htmlspecialchars($c['nama_kriteria']) ?></td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold <?= (strtolower($c['jenis']) === 'cost') ? 'bg-rose-500/20 text-rose-300' : 'bg-emerald-500/20 text-emerald-300' ?>">
                                    <?= strtoupper($c['jenis']) ?>
                                </span>
                            </td>
                            <td class="p-3 font-mono font-bold text-cyan-300"><?= number_format($c['bobot'], 2) ?></td>
                            <td class="p-3 font-mono"><?= number_format($c['bobot'] * 100, 0) ?>%</td>
                            <td class="p-3 font-mono text-slate-400">
                                <?php if (strtolower($c['jenis']) === 'cost'): ?>
                                    Min: <strong><?= number_format($ext['min'], 0, ',', '.') ?></strong>
                                <?php else: ?>
                                    Max: <strong><?= $ext['max'] ?></strong>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. Matriks Keputusan X -->
    <div class="bg-slate-800/80 rounded-2xl border border-slate-700/80 p-6 shadow-sm">
        <h3 class="text-sm font-bold text-white mb-3 flex items-center">
            <span class="w-6 h-6 rounded-full bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-xs font-bold mr-2">2</span>
            Matriks Keputusan ($X_{m \times n}$)
        </h3>
        <p class="text-xs text-slate-400 mb-4">Nilai parameter spesifikasi riil seluruh alternatif produk yang diuji.</p>
        
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 border-b border-slate-700">
                    <tr>
                        <th class="p-3">Alternatif ($A_i$)</th>
                        <th class="p-3">C1: Harga (Cost)</th>
                        <th class="p-3">C2: Tebal (Benefit)</th>
                        <th class="p-3">C3: Merk (Benefit)</th>
                        <th class="p-3">C4: Garansi (Benefit)</th>
                        <th class="p-3">C5: Finish (Benefit)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/60">
                    <?php foreach ($sawData['results'] as $res): 
                        $p = $res['produk'];
                        $x = $res['raw_x'];
                    ?>
                        <tr class="hover:bg-slate-750 transition">
                            <td class="p-3 font-semibold text-white">
                                <span class="font-mono text-slate-400 text-[10px] mr-1.5"><?= $p['sku'] ?></span>
                                <?= htmlspecialchars($p['nama_produk']) ?>
                            </td>
                            <td class="p-3 font-mono">Rp <?= number_format($x[1] ?? 0, 0, ',', '.') ?></td>
                            <td class="p-3 font-mono font-bold"><?= $x[2] ?? 0 ?></td>
                            <td class="p-3 font-mono font-bold"><?= $x[3] ?? 0 ?></td>
                            <td class="p-3 font-mono font-bold"><?= $x[4] ?? 0 ?></td>
                            <td class="p-3 font-mono font-bold"><?= $x[5] ?? 0 ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. Matriks Normalisasi R -->
    <div class="bg-slate-800/80 rounded-2xl border border-slate-700/80 p-6 shadow-sm">
        <h3 class="text-sm font-bold text-white mb-3 flex items-center">
            <span class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs font-bold mr-2">3</span>
            Matriks Normalisasi ($R = [r_{ij}]$)
        </h3>
        <p class="text-xs text-slate-400 mb-4">Hasil normalisasi dengan formula Benefit: $r_{ij} = x_{ij}/\max(x_j)$ dan Cost: $r_{ij} = \min(x_j)/x_{ij}$.</p>
        
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 border-b border-slate-700">
                    <tr>
                        <th class="p-3">Alternatif ($A_i$)</th>
                        <th class="p-3 font-mono">r_i1 (C1: Cost)</th>
                        <th class="p-3 font-mono">r_i2 (C2: Benefit)</th>
                        <th class="p-3 font-mono">r_i3 (C3: Benefit)</th>
                        <th class="p-3 font-mono">r_i4 (C4: Benefit)</th>
                        <th class="p-3 font-mono">r_i5 (C5: Benefit)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/60">
                    <?php foreach ($sawData['results'] as $res): 
                        $p = $res['produk'];
                        $r = $res['normalized_r'];
                    ?>
                        <tr class="hover:bg-slate-750 transition font-mono">
                            <td class="p-3 font-sans font-semibold text-white"><?= htmlspecialchars($p['nama_produk']) ?></td>
                            <td class="p-3 text-brand-300 font-bold"><?= number_format($r[1] ?? 0, 4) ?></td>
                            <td class="p-3 text-emerald-300 font-bold"><?= number_format($r[2] ?? 0, 4) ?></td>
                            <td class="p-3 text-cyan-300 font-bold"><?= number_format($r[3] ?? 0, 4) ?></td>
                            <td class="p-3 text-purple-300 font-bold"><?= number_format($r[4] ?? 0, 4) ?></td>
                            <td class="p-3 text-pink-300 font-bold"><?= number_format($r[5] ?? 0, 4) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 4. Nilai Akhir Perangkingan V -->
    <div class="bg-slate-800/80 rounded-2xl border border-slate-700/80 p-6 shadow-sm">
        <h3 class="text-sm font-bold text-white mb-3 flex items-center">
            <span class="w-6 h-6 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center text-xs font-bold mr-2">4</span>
            Nilai Preferensi Akhir ($V_i = \sum_{j=1}^n w_j \cdot r_{ij}$) &amp; Perangkingan
        </h3>
        <p class="text-xs text-slate-400 mb-4">Alternatif diurutkan dari nilai preferensi $V_i$ terbesar. Rekomendasi terbaik menempati peringkat 1.</p>
        
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 border-b border-slate-700">
                    <tr>
                        <th class="p-3 text-center w-16">Rank</th>
                        <th class="p-3">Nama Produk Alternatif</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Harga Riil</th>
                        <th class="p-3 font-mono">Perhitungan $V_i$</th>
                        <th class="p-3 font-mono text-right">Skor Akhir ($V$)</th>
                        <th class="p-3 text-right">Match Score</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/60">
                    <?php foreach ($sawData['results'] as $res): 
                        $p = $res['produk'];
                        $r = $res['normalized_r'];
                        $isTop = ($res['rank'] === 1);
                    ?>
                        <tr class="<?= $isTop ? 'bg-amber-500/10' : 'hover:bg-slate-750' ?> transition">
                            <td class="p-3 text-center">
                                <span class="w-7 h-7 rounded-full inline-flex items-center justify-center font-black <?= $isTop ? 'bg-amber-500 text-slate-950 shadow' : 'bg-slate-700 text-slate-300' ?>">
                                    #<?= $res['rank'] ?>
                                </span>
                            </td>
                            <td class="p-3 font-bold <?= $isTop ? 'text-amber-300' : 'text-white' ?>">
                                <?= htmlspecialchars($p['nama_produk']) ?>
                                <?php if ($isTop): ?>
                                    <span class="ml-2 text-[10px] font-bold px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/40">REKOMENDASI TERBAIK</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-3 text-slate-400"><?= htmlspecialchars($p['nama_kategori']) ?></td>
                            <td class="p-3 font-mono">Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                            <td class="p-3 font-mono text-[10px] text-slate-400">
                                (0.30&times;<?= number_format($r[1] ?? 0, 2) ?>)+(0.25&times;<?= number_format($r[2] ?? 0, 2) ?>)+(0.20&times;<?= number_format($r[3] ?? 0, 2) ?>)+(0.15&times;<?= number_format($r[4] ?? 0, 2) ?>)+(0.10&times;<?= number_format($r[5] ?? 0, 2) ?>)
                            </td>
                            <td class="p-3 font-mono font-black text-right text-sm <?= $isTop ? 'text-amber-400' : 'text-white' ?>">
                                <?= number_format($res['score_v'], 4) ?>
                            </td>
                            <td class="p-3 text-right font-bold text-emerald-400">
                                <?= $res['match_percent'] ?>%
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Print-Only Academic Signature Box for Bab 4 -->
<div class="hidden print:block pt-12 mt-12 text-slate-900 text-xs">
    <div class="grid grid-cols-2 gap-12 text-center">
        <div class="space-y-16">
            <p>Mengetahui,<br><strong>Dosen Pembimbing / Penguji</strong></p>
            <p class="font-bold underline">Pak Rofi, S.Kom., M.Kom.<br><span class="font-normal text-[11px] no-underline">NIP: 19850101 201012 1 001</span></p>
        </div>
        <div class="space-y-16">
            <p>Mahasiswa Penyusun Skripsi,<br><strong>Teknik Informatika / Sistem Informasi</strong></p>
            <p class="font-bold underline">Pengembang Sistem MebelAlum<br><span class="font-normal text-[11px] no-underline">NIM: 20261110001</span></p>
        </div>
    </div>
</div>

<?php
$adminContent = ob_get_clean();
require_once __DIR__ . '/../layouts/admin_layout.php';
?>
