<?php
$pageTitle = 'Manajemen Pesanan & DP Kargo - Admin SPK';
ob_start();
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-slate-800 gap-4">
    <div>
        <h2 class="text-xl font-bold text-white font-['Plus_Jakarta_Sans']">Manajemen Pesanan &amp; Logistik Kargo</h2>
        <p class="text-xs text-slate-400">Pantau status transaksi uang muka (DP), verifikasi webhook Midtrans, dan nomor resi kargo.</p>
    </div>
</div>

<div class="mt-6 bg-slate-800/80 rounded-2xl border border-slate-700/80 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-xs text-left text-slate-300">
            <thead class="text-slate-400 bg-slate-900/60 border-b border-slate-700">
                <tr>
                    <th class="p-4">Kode Order</th>
                    <th class="p-4">Pemesan &amp; Alamat</th>
                    <th class="p-4">Ekspedisi Kargo</th>
                    <th class="p-4">Charged Weight</th>
                    <th class="p-4">Total Pesanan</th>
                    <th class="p-4">Skema &amp; Status DP</th>
                    <th class="p-4">Status Produksi</th>
                    <th class="p-4">Resi Kargo</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/60">
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="9" class="p-8 text-center text-slate-500">
                            Belum ada transaksi pesanan yang masuk. Silakan lakukan pemesanan uji coba pada halaman katalog atau produk detail.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $ord): ?>
                        <tr class="hover:bg-slate-750 transition">
                            <td class="p-4">
                                <span class="font-mono font-bold text-white block text-sm"><?= htmlspecialchars($ord['order_code']) ?></span>
                                <span class="text-[10px] text-slate-500"><?= $ord['created_at'] ?></span>
                            </td>
                            <td class="p-4">
                                <strong class="text-white block"><?= htmlspecialchars($ord['customer_name']) ?></strong>
                                <span class="text-slate-400 text-[11px]"><i class="fa-brands fa-whatsapp text-emerald-400 mr-1"></i> <?= htmlspecialchars($ord['customer_phone']) ?></span>
                                <span class="text-slate-500 text-[10px] block truncate max-w-[180px]"><?= htmlspecialchars($ord['city_destination']) ?></span>
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-slate-200 block"><?= htmlspecialchars($ord['courier_name']) ?></span>
                                <span class="text-[10px] text-slate-400"><?= htmlspecialchars($ord['courier_service']) ?></span>
                            </td>
                            <td class="p-4 font-mono font-bold text-brand-300">
                                <?= $ord['charged_weight_kg'] ?> kg
                                <span class="block text-[10px] text-slate-500 font-sans">Ongkir: Rp <?= number_format($ord['shipping_cost'], 0, ',', '.') ?></span>
                            </td>
                            <td class="p-4 font-mono font-bold text-white">
                                Rp <?= number_format($ord['total_amount'], 0, ',', '.') ?>
                                <span class="block text-[10px] text-amber-400 font-sans">DP 50%: Rp <?= number_format($ord['dp_amount'], 0, ',', '.') ?></span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold <?= ($ord['payment_status'] === 'Lunas') ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' ?>">
                                    <?= htmlspecialchars($ord['payment_status']) ?>
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-slate-700 text-slate-200">
                                    <?= htmlspecialchars($ord['order_status']) ?>
                                </span>
                            </td>
                            <td class="p-4 font-mono text-cyan-300 font-bold">
                                <?= $ord['tracking_number'] ?: '<span class="text-slate-600 font-sans">-</span>' ?>
                            </td>
                            <td class="p-4 text-right">
                                <button onclick='openEditOrder(<?= json_encode($ord) ?>)' class="px-3 py-1.5 rounded-lg text-xs font-bold bg-brand-600 hover:bg-brand-700 text-white shadow-sm">
                                    Update Status
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Update Status Pesanan -->
<div id="modalOrder" class="hidden fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl relative text-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <h3 class="text-base font-bold text-white font-['Plus_Jakarta_Sans']">Update Status Pesanan &amp; Kargo</h3>
            <button onclick="closeOrderModal()" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form method="POST" action="index.php?action=admin_orders_update" class="space-y-4">
            <input type="hidden" name="id" id="editOrderId">

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Kode Pesanan:</label>
                <input type="text" id="editOrderCode" readonly class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950/50 text-xs font-mono font-bold text-white">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Status Pembayaran:</label>
                <select name="payment_status" id="editPayStatus" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white">
                    <option value="Menunggu Pembayaran DP">Menunggu Pembayaran DP</option>
                    <option value="DP Terbayar">DP Terbayar (50%)</option>
                    <option value="Lunas">Lunas (100%)</option>
                    <option value="Batal">Batal</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Status Produksi &amp; Ekspedisi:</label>
                <select name="order_status" id="editOrderStatus" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white">
                    <option value="Pending">1. Pending (Menunggu Validasi)</option>
                    <option value="Dalam Proses Produksi">2. Dalam Proses Produksi (Fabrikasi Aluminium)</option>
                    <option value="Quality Control">3. Quality Control &amp; Packing Kayu</option>
                    <option value="Siap Dikirim">4. Siap Dikirim (Menunggu Pelunasan)</option>
                    <option value="Dalam Pengiriman Kargo">5. Dalam Pengiriman Kargo</option>
                    <option value="Selesai">6. Selesai (Diterima)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Nomor Resi Pengiriman Kargo:</label>
                <input type="text" name="tracking_number" id="editTrackingNumber" placeholder="Contoh: JTR-202611-98765" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-mono font-semibold text-cyan-300 focus:ring-brand-500">
            </div>

            <button type="submit" class="w-full py-3 rounded-xl font-bold bg-brand-600 hover:bg-brand-700 text-white text-xs shadow">
                Perbarui Status Pesanan
            </button>
        </form>
    </div>
</div>

<script>
    function openEditOrder(ord) {
        document.getElementById('editOrderId').value = ord.id;
        document.getElementById('editOrderCode').value = ord.order_code;
        document.getElementById('editPayStatus').value = ord.payment_status;
        document.getElementById('editOrderStatus').value = ord.order_status;
        document.getElementById('editTrackingNumber').value = ord.tracking_number || '';
        document.getElementById('modalOrder').classList.remove('hidden');
    }

    function closeOrderModal() {
        document.getElementById('modalOrder').classList.add('hidden');
    }
</script>

<?php
$adminContent = ob_get_clean();
require_once __DIR__ . '/../layouts/admin_layout.php';
?>
