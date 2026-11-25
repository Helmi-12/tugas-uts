<?php
$pageTitle = 'Manajemen Kriteria & Bobot SAW - Admin SPK';
ob_start();
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-slate-800 gap-4">
    <div>
        <h2 class="text-xl font-bold text-white font-['Plus_Jakarta_Sans']">Kriteria &amp; Bobot Penilaian SAW</h2>
        <p class="text-xs text-slate-400">Atur bobot preferensi ($W_j$) dan jenis kriteria (Benefit / Cost) sesuai Blueprint.</p>
    </div>
    <div class="flex items-center space-x-2">
        <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold <?= (abs($totalWeight - 1.0) < 0.001) ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30' ?>">
            <i class="fa-solid <?= (abs($totalWeight - 1.0) < 0.001) ? 'fa-circle-check text-emerald-400' : 'fa-circle-exclamation text-rose-400' ?> mr-1.5"></i>
            Total Bobot: <?= number_format($totalWeight, 2) ?> (<?= number_format($totalWeight * 100, 0) ?>%)
        </span>
    </div>
</div>

<div class="mt-6 bg-slate-800/80 rounded-2xl border border-slate-700/80 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-xs text-left text-slate-300">
            <thead class="text-slate-400 bg-slate-900/60 border-b border-slate-700">
                <tr>
                    <th class="p-4">Kode</th>
                    <th class="p-4">Nama Kriteria</th>
                    <th class="p-4">Sifat / Tipe</th>
                    <th class="p-4">Bobot Desimal ($W$)</th>
                    <th class="p-4">Bobot Persen (%)</th>
                    <th class="p-4">Keterangan Skala Nilai</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/60">
                <?php foreach ($criteria as $c): ?>
                    <tr class="hover:bg-slate-750 transition">
                        <td class="p-4 font-mono font-bold text-amber-400 text-sm"><?= htmlspecialchars($c['kode_kriteria']) ?></td>
                        <td class="p-4 font-bold text-white"><?= htmlspecialchars($c['nama_kriteria']) ?></td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded text-[10px] font-bold <?= (strtolower($c['jenis']) === 'cost') ? 'bg-rose-500/20 text-rose-300' : 'bg-emerald-500/20 text-emerald-300' ?>">
                                <?= strtoupper($c['jenis']) ?>
                            </span>
                        </td>
                        <td class="p-4 font-mono font-bold text-cyan-300 text-sm"><?= number_format($c['bobot'], 2) ?></td>
                        <td class="p-4 font-mono text-slate-400"><?= number_format($c['bobot'] * 100, 0) ?>%</td>
                        <td class="p-4 text-slate-400 max-w-xs truncate"><?= htmlspecialchars($c['keterangan_skala']) ?></td>
                        <td class="p-4 text-right">
                            <button onclick='openEditKriteria(<?= json_encode($c) ?>)' class="px-3 py-1.5 rounded-lg text-xs font-bold bg-brand-600 hover:bg-brand-700 text-white transition shadow-sm">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Edit Kriteria -->
<div id="modalEditCrit" class="hidden fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl relative text-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <h3 class="text-base font-bold text-white font-['Plus_Jakarta_Sans']">Edit Kriteria &amp; Bobot</h3>
            <button onclick="closeCritModal()" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form method="POST" action="index.php?action=admin_kriteria_save" class="space-y-4">
            <input type="hidden" name="id" id="editCritId">

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Kode Kriteria:</label>
                <input type="text" id="editCritKode" readonly class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950/50 text-xs font-bold text-amber-400">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Nama Kriteria:</label>
                <input type="text" name="nama_kriteria" id="editCritNama" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Jenis Kriteria:</label>
                    <select name="jenis" id="editCritJenis" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white">
                        <option value="benefit">Benefit (Maksimal)</option>
                        <option value="cost">Cost (Minimal)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Bobot (0.05 - 0.50):</label>
                    <input type="number" step="0.01" min="0.01" max="1.0" name="bobot" id="editCritBobot" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Keterangan Skala Penilaian:</label>
                <textarea name="keterangan_skala" id="editCritKeterangan" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white focus:ring-brand-500"></textarea>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl font-bold bg-brand-600 hover:bg-brand-700 text-white text-xs shadow">
                Perbarui Kriteria &amp; Bobot
            </button>
        </form>
    </div>
</div>

<script>
    function openEditKriteria(crit) {
        document.getElementById('editCritId').value = crit.id;
        document.getElementById('editCritKode').value = crit.kode_kriteria;
        document.getElementById('editCritNama').value = crit.nama_kriteria;
        document.getElementById('editCritJenis').value = crit.jenis;
        document.getElementById('editCritBobot').value = crit.bobot;
        document.getElementById('editCritKeterangan').value = crit.keterangan_skala || '';
        document.getElementById('modalEditCrit').classList.remove('hidden');
    }

    function closeCritModal() {
        document.getElementById('modalEditCrit').classList.add('hidden');
    }
</script>

<?php
$adminContent = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin_layout.php';
?>

