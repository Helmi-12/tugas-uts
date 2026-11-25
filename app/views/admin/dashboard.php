<?php
$pageTitle = 'Dashboard Ringkasan Sistem - NJ Aluminium';
ob_start();

$tampilKategori = (int)($totalCategories ?? 0);
$tampilStok = (int)($totalStokBarang ?? 0);
$tampilPesanan = (int)($totalSemuaPesanan ?? $totalOrders ?? 0);
$tampilKustom = (int)($totalPesananKustom ?? 0);
$tampilOrderKargo = (int)($totalOrders ?? 0);
$categoryStats = $categoryStats ?? [];
$recentOrders = $recentOrders ?? [];
?>

<!-- Statistics Metric Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="bg-slate-800/80 rounded-2xl p-6 border border-slate-700/80 shadow-sm relative overflow-hidden">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kategori Mebel</span>
                <div class="text-3xl font-black text-white mt-1"><?= $tampilKategori ?></div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-brand-500/20 text-brand-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-tags"></i>
            </div>
        </div>
        <a href="index.php?action=admin_kategori" class="mt-4 text-xs font-semibold text-brand-400 hover:text-brand-300 flex items-center">
            Kelola Kategori <i class="fa-solid fa-arrow-right ml-1.5 text-[10px]"></i>
        </a>
    </div>

    <div class="bg-slate-800/80 rounded-2xl p-6 border border-slate-700/80 shadow-sm relative overflow-hidden">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Stok Barang</span>
                <div class="text-3xl font-black text-white mt-1"><?= $tampilStok ?></div>
                <span class="text-[11px] text-slate-400 block mt-0.5">
                    Akumulasi unit/material di gudang
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-warehouse"></i>
            </div>
        </div>
        <a href="index.php?action=admin_stok" class="mt-3 text-xs font-semibold text-cyan-400 hover:text-cyan-300 flex items-center">
            Catat &amp; Kelola Stok <i class="fa-solid fa-arrow-right ml-1.5 text-[10px]"></i>
        </a>
    </div>

    <div class="bg-slate-800/80 rounded-2xl p-6 border border-slate-700/80 shadow-sm relative overflow-hidden">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pesanan Pelanggan</span>
                <div class="text-3xl font-black text-white mt-1"><?= $tampilPesanan ?></div>
                <span class="text-[11px] text-slate-400 block mt-0.5">
                    Pesanan Kustom: <strong class="text-emerald-400"><?= $tampilKustom ?></strong> &bull; Kargo/DP: <strong class="text-brand-400"><?= $tampilOrderKargo ?></strong>
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
        </div>
        <a href="index.php?action=admin_orders" class="mt-3 text-xs font-semibold text-emerald-400 hover:text-emerald-300 flex items-center">
            Kelola Status Transaksi <i class="fa-solid fa-arrow-right ml-1.5 text-[10px]"></i>
        </a>
    </div>
</div>

<!-- Category Distribution Chart -->
<div class="mt-8 bg-slate-800/80 rounded-2xl p-6 border border-slate-700/80 shadow-sm">
    <div class="flex items-center justify-between pb-4 border-b border-slate-700">
        <div>
            <h3 class="text-base font-bold text-white font-['Plus_Jakarta_Sans']">Statistik Kategori Mebel</h3>
            <p class="text-xs text-slate-400">Ringkasan jumlah item/produk per kategori yang terdaftar di sistem</p>
        </div>
        <span class="text-xs font-bold text-brand-400 bg-brand-500/10 px-2.5 py-1 rounded border border-brand-500/20">
            Live Data
        </span>
    </div>
    <div class="h-64 mt-4">
        <canvas id="categoryChart"></canvas>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="mt-8 bg-slate-800/80 rounded-2xl border border-slate-700/80 p-6 shadow-sm">
    <div class="flex items-center justify-between pb-4 border-b border-slate-700">
        <div>
            <h3 class="text-base font-bold text-white font-['Plus_Jakarta_Sans']">Pesanan Terbaru</h3>
            <p class="text-xs text-slate-400">Daftar transaksi pesanan pelanggan dan status pembayaran/produksi</p>
        </div>
        <a href="index.php?action=admin_orders" class="text-xs font-bold text-brand-400 hover:text-brand-300">
            Lihat Semua Pesanan <i class="fa-solid fa-arrow-right ml-1"></i>
        </a>
    </div>

    <div class="mt-4 overflow-x-auto">
        <table class="w-full text-xs text-left text-slate-300">
            <thead class="text-slate-400 border-b border-slate-700">
                <tr>
                    <th class="pb-3">Kode Order</th>
                    <th class="pb-3">Pemesan</th>
                    <th class="pb-3">Ekspedisi Kargo</th>
                    <th class="pb-3">Total Transaksi</th>
                    <th class="pb-3">Status DP</th>
                    <th class="pb-3">Status Produksi</th>
                    <th class="pb-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/60">
                <?php if (empty($recentOrders)): ?>
                    <tr>
                        <td colspan="7" class="py-6 text-center text-slate-500">Belum ada transaksi pesanan yang masuk.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentOrders as $ord): ?>
                        <tr>
                            <td class="py-3 font-mono font-bold text-white"><?= htmlspecialchars($ord['order_code']) ?></td>
                            <td class="py-3"><?= htmlspecialchars($ord['customer_name']) ?> (<?= htmlspecialchars($ord['customer_phone']) ?>)</td>
                            <td class="py-3"><?= htmlspecialchars($ord['courier_name']) ?> (<?= $ord['charged_weight_kg'] ?> kg)</td>
                            <td class="py-3 font-mono font-bold">Rp <?= number_format($ord['total_amount'], 0, ',', '.') ?></td>
                            <td class="py-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold <?= ($ord['payment_status'] === 'Lunas') ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' ?>">
                                    <?= htmlspecialchars($ord['payment_status']) ?>
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-700 text-slate-300">
                                    <?= htmlspecialchars($ord['order_status']) ?>
                                </span>
                            </td>
                            <td class="py-3 text-right">
                                <a href="index.php?action=admin_orders&id=<?= $ord['id'] ?>" class="p-1.5 text-brand-400 hover:text-brand-300 font-bold">
                                    Detail <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('categoryChart').getContext('2d');
        const catLabels = <?= json_encode(array_column($categoryStats, 'nama_kategori')) ?>;
        const catCounts = <?= json_encode(array_column($categoryStats, 'total_produk')) ?>;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: catLabels,
                datasets: [{
                    label: 'Jumlah Item',
                    data: catCounts,
                    backgroundColor: [
                        'rgba(14, 165, 233, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(99, 102, 241, 0.8)'
                    ],
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        ticks: { color: '#94a3b8', font: { size: 10 } },
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#94a3b8', stepSize: 1 },
                        grid: { color: 'rgba(51, 65, 85, 0.4)' }
                    }
                }
            }
        });
    });
</script>

<?php
$adminContent = ob_get_clean();
require_once __DIR__ . '/../layouts/admin_layout.php';
?>