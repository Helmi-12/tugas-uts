<?php
$pageTitle = 'Pelacakan Pesanan & Status Produksi - MebelAlum';
require_once __DIR__ . '/../layouts/header.php';

$orderCode = trim($_GET['order_code'] ?? '');
?>

<div class="bg-slate-900 text-white py-12 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-brand-500/20 text-brand-300 border border-brand-500/30 mb-3">
                <i class="fa-solid fa-timeline mr-1.5"></i> Pelacakan Lifecycle Produksi &amp; Logistik Kargo
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight font-['Plus_Jakarta_Sans']">
                Lacak Status Pesanan &amp; DP
            </h1>
            <p class="text-slate-400 text-sm sm:text-base mt-2">
                Pantau proses fabrikasi mebel aluminium custom dan pengiriman kargo Anda secara transparan.
            </p>
        </div>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Search Order Code Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm -mt-16 relative z-10">
        <form method="GET" action="index.php" class="flex flex-col sm:flex-row gap-3">
            <input type="hidden" name="action" value="tracking">
            <div class="relative flex-1">
                <span class="absolute left-3.5 top-3.5 text-slate-400"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" name="order_code" value="<?= htmlspecialchars($orderCode) ?>" placeholder="Masukkan Kode Pesanan (Contoh: ALUM-20261110-XXXXX)" required class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 text-sm font-semibold focus:ring-brand-500">
            </div>
            <button type="submit" class="px-6 py-3 rounded-xl font-bold bg-brand-600 hover:bg-brand-700 text-white text-sm shadow">
                Lacak Pesanan
            </button>
        </form>
    </div>

    <?php if ($order): ?>
        <!-- Order Detail & Timeline -->
        <div class="mt-8 bg-white rounded-2xl border border-slate-200 p-8 shadow-sm space-y-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-6 border-b border-slate-200 gap-4">
                <div>
                    <span class="text-xs text-slate-400 font-mono block">KODE PESANAN:</span>
                    <h2 class="text-2xl font-black text-slate-900 font-['Plus_Jakarta_Sans']"><?= htmlspecialchars($order['order_code']) ?></h2>
                    <span class="text-xs text-slate-500">Dibuat pada: <?= $order['created_at'] ?></span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-brand-100 text-brand-800 border border-brand-200">
                        <?= htmlspecialchars($order['order_type']) ?>
                    </span>
                    <span class="px-3 py-1.5 rounded-full text-xs font-bold <?= ($order['payment_status'] === 'Lunas') ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' ?>">
                        <i class="fa-solid fa-wallet mr-1"></i> <?= htmlspecialchars($order['payment_status']) ?>
                    </span>
                </div>
            </div>

            <!-- Visual Production Timeline (Dokumen 1) -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 mb-6 uppercase tracking-wider">Tahapan Status Produksi &amp; Logistik:</h3>
                
                <?php
                $statuses = [
                    'Pending' => '1. Pesanan Diterima & Validasi',
                    'Dalam Proses Produksi' => '2. Pemotongan Profil & Fabrikasi (DP 50% OK)',
                    'Quality Control' => '3. Pengecekan Kualitas & Packing Kayu',
                    'Siap Dikirim' => '4. Pelunasan & Serah Terima Ekspedisi',
                    'Dalam Pengiriman Kargo' => '5. Perjalanan Ekspedisi Kargo',
                    'Selesai' => '6. Pesanan Sampai & Selesai'
                ];
                $currentIndex = array_search($order['order_status'], array_keys($statuses));
                if ($currentIndex === false) $currentIndex = 0;
                ?>

                <div class="grid grid-cols-1 md:grid-cols-6 gap-3 text-center text-xs">
                    <?php $i = 0; foreach ($statuses as $stKey => $stLabel): ?>
                        <div class="p-3 rounded-xl border <?= ($i <= $currentIndex) ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-bold' : 'bg-slate-50 border-slate-200 text-slate-400' ?>">
                            <div class="w-8 h-8 rounded-full mx-auto mb-2 flex items-center justify-center text-xs font-black <?= ($i <= $currentIndex) ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600' ?>">
                                <?= ($i < $currentIndex) ? '<i class="fa-solid fa-check"></i>' : ($i + 1) ?>
                            </div>
                            <span class="block text-[11px]"><?= $stLabel ?></span>
                        </div>
                    <?php $i++; endforeach; ?>
                </div>
            </div>

            <!-- Items & Financial Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-slate-200 text-xs">
                <!-- Customer & Delivery -->
                <div class="space-y-3">
                    <h4 class="font-bold text-slate-900 text-sm">Informasi Pemesan &amp; Ekspedisi:</h4>
                    <p>• <strong>Nama:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                    <p>• <strong>WhatsApp:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
                    <p>• <strong>Tujuan:</strong> <?= htmlspecialchars($order['city_destination']) ?></p>
                    <p>• <strong>Alamat:</strong> <?= nl2br(htmlspecialchars($order['customer_address'])) ?></p>
                    <p>• <strong>Ekspedisi Kargo:</strong> <?= htmlspecialchars($order['courier_name']) ?> (<?= htmlspecialchars($order['courier_service']) ?>)</p>
                    <p>• <strong>Charged Weight:</strong> <?= $order['charged_weight_kg'] ?> kg</p>
                    <?php if (!empty($order['tracking_number'])): ?>
                        <p class="p-2 bg-brand-50 text-brand-800 rounded-lg font-mono font-bold">
                            No. Resi Kargo: <?= htmlspecialchars($order['tracking_number']) ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Financial Breakdown -->
                <div class="space-y-2 bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <h4 class="font-bold text-slate-900 text-sm mb-2">Rincian Finansial Transaksi:</h4>
                    <div class="flex justify-between">
                        <span>Subtotal Mebel:</span>
                        <span class="font-mono font-bold">Rp <?= number_format($order['product_subtotal'], 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Biaya Kargo &amp; Packing:</span>
                        <span class="font-mono font-bold">Rp <?= number_format($order['shipping_cost'], 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between text-slate-900 font-black text-sm pt-2 border-t border-slate-200">
                        <span>Total Pesanan:</span>
                        <span class="font-mono text-brand-700">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between text-amber-700 font-bold pt-1">
                        <span>Nilai DP (Uang Muka 50%):</span>
                        <span class="font-mono">Rp <?= number_format($order['dp_amount'], 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between text-slate-500 pt-1">
                        <span>Sisa Pelunasan:</span>
                        <span class="font-mono">Rp <?= number_format($order['remaining_amount'], 0, ',', '.') ?></span>
                    </div>

                    <?php if ($order['payment_status'] === 'DP Terbayar'): ?>
                        <div class="pt-3">
                            <button onclick="simulateSettlementPayment('<?= $order['order_code'] ?>', <?= $order['remaining_amount'] ?>)" class="w-full py-2.5 px-3 rounded-lg text-xs font-bold bg-brand-600 hover:bg-brand-700 text-white shadow">
                                <i class="fa-solid fa-money-check-dollar mr-1.5"></i> Pelunasan Sisa Tagihan (Rp <?= number_format($order['remaining_amount'], 0, ',', '.') ?>)
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php elseif ($orderCode): ?>
        <div class="mt-8 bg-amber-50 border border-amber-200 rounded-2xl p-8 text-center text-xs text-amber-800">
            <i class="fa-solid fa-triangle-exclamation text-2xl text-amber-600 mb-2"></i>
            <p class="font-bold">Kode pesanan "<?= htmlspecialchars($orderCode) ?>" tidak ditemukan di sistem.</p>
            <p class="mt-1">Mohon pastikan format kode pesanan sudah sesuai, atau periksa riwayat di panel admin.</p>
        </div>
    <?php endif; ?>
</div>

<script>
    async function simulateSettlementPayment(code, amount) {
        const confirm = await Swal.fire({
            title: 'Konfirmasi Pelunasan',
            text: `Apakah Anda ingin mensimulasikan pelunasan sisa tagihan sebesar Rp ${SawClient.formatRupiah(amount)}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lunasi Sekarang'
        });

        if (confirm.isConfirmed) {
            const resp = await fetch('index.php?action=api_midtrans_webhook', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    order_id: code,
                    transaction_status: 'settlement',
                    payment_type: 'Pelunasan Akhir (Bank Transfer)'
                })
            });
            const data = await resp.json();
            Swal.fire('Pelunasan Berhasil!', 'Status pesanan kini telah LUNAS dan siap dikirim via ekspedisi kargo.', 'success').then(() => {
                location.reload();
            });
        }
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

