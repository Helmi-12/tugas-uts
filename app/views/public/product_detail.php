<?php
$pageTitle = htmlspecialchars($product['nama_produk']) . ' - Visual 3D & Kargo Volumetrik';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="bg-slate-900 text-white py-8 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex text-xs text-slate-400 mb-3 space-x-2">
            <a href="index.php?action=home" class="hover:text-white">Beranda</a>
            <span>/</span>
            <a href="index.php?action=katalog" class="hover:text-white">Katalog</a>
            <span>/</span>
            <a href="index.php?action=katalog&kategori_id=<?= $product['kategori_id'] ?>" class="hover:text-white"><?= htmlspecialchars($product['nama_kategori']) ?></a>
            <span>/</span>
            <span class="text-brand-400 truncate"><?= htmlspecialchars($product['nama_produk']) ?></span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="px-2.5 py-1 rounded bg-brand-600/30 text-brand-300 font-bold text-xs border border-brand-500/30">
                    SKU: <?= htmlspecialchars($product['sku']) ?>
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white mt-2 font-['Plus_Jakarta_Sans']">
                    <?= htmlspecialchars($product['nama_produk']) ?>
                </h1>
            </div>
            <div class="text-right">
                <span class="text-xs text-slate-400 block">Harga Spesifikasi:</span>
                <span class="text-3xl font-black text-amber-400 font-['Plus_Jakarta_Sans']">
                    Rp <?= number_format($product['harga'], 0, ',', '.') ?>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Left Col: 3D Visual Commerce & WebAR Studio (Dokumen 1) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-lg p-5">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                        <h2 class="text-sm font-bold text-slate-800">
                            <i class="fa-solid fa-cube text-brand-600 mr-1.5"></i> Visual Commerce 3D Configurator
                        </h2>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="toggleDoorAnimation()" id="btnToggleDoor" class="p-2 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-700 text-xs font-bold transition flex items-center">
                            <i class="fa-solid fa-door-open mr-1"></i> Buka Pintu 3D
                        </button>
                        <button onclick="productViewer.takeScreenshot()" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold" title="Ambil Screenshot 3D">
                            <i class="fa-solid fa-camera mr-1"></i> Screenshot
                        </button>
                        <button onclick="showWebARModal()" class="px-3 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold shadow-sm flex items-center">
                            <i class="fa-solid fa-mobile-screen-button mr-1.5"></i> WebAR (Ruangan)
                        </button>
                    </div>
                </div>

                <!-- 3D Canvas Box -->
                <div id="product3DBox" class="viewer-3d-box mt-4 relative">
                    <!-- Loading or 3D Canvas -->
                </div>

                <!-- Realtime Finishes Customizer -->
                <div class="mt-4 p-4 bg-slate-50 rounded-xl border border-slate-200/80">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <span class="text-xs font-bold text-slate-700 block uppercase tracking-wider">Simulasi Tekstur Material:</span>
                            <span class="text-xs text-slate-500">Klik warna untuk mengubah material finishing secara instan</span>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="changeFinish('wood_grain')" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-900 text-amber-100 hover:opacity-90 shadow-sm flex items-center">
                                🪵 Urat Kayu
                            </button>
                            <button onclick="changeFinish('powder_black')" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-900 text-white hover:opacity-90 shadow-sm flex items-center">
                                ⬛ Black Matte
                            </button>
                            <button onclick="changeFinish('silver_anodize')" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-300 text-slate-800 hover:opacity-90 shadow-sm flex items-center">
                                🪙 Silver Anodize
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Dimension Info Bar -->
                <div class="mt-4 grid grid-cols-4 gap-2 text-center text-xs">
                    <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-[10px] text-slate-400 uppercase font-bold block">Panjang (P)</span>
                        <span class="font-bold text-slate-800 text-sm"><?= $product['panjang_cm'] ?> cm</span>
                    </div>
                    <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-[10px] text-slate-400 uppercase font-bold block">Lebar (L)</span>
                        <span class="font-bold text-slate-800 text-sm"><?= $product['lebar_cm'] ?> cm</span>
                    </div>
                    <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-[10px] text-slate-400 uppercase font-bold block">Tinggi (T)</span>
                        <span class="font-bold text-slate-800 text-sm"><?= $product['tinggi_cm'] ?> cm</span>
                    </div>
                    <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-[10px] text-slate-400 uppercase font-bold block">Berat Riil</span>
                        <span class="font-bold text-brand-600 text-sm"><?= $product['berat_aktual_kg'] ?> kg</span>
                    </div>
                </div>
            </div>

            <!-- Detailed Specifications & SAW Score Values -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center">
                    <i class="fa-solid fa-list-check text-brand-600 mr-2"></i> Parameter Spesifikasi &amp; Skor SAW (C1 - C5)
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="text-slate-400 bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="p-3">Kode Kriteria</th>
                                <th class="p-3">Nama Kriteria</th>
                                <th class="p-3">Spesifikasi Riil</th>
                                <th class="p-3 text-right">Nilai Input Matriks (X)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php foreach ($product['criteria_values'] as $cv): ?>
                                <tr>
                                    <td class="p-3 font-bold text-brand-600"><?= htmlspecialchars($cv['kode_kriteria']) ?></td>
                                    <td class="p-3 font-semibold"><?= htmlspecialchars($cv['nama_kriteria']) ?> (<?= ucfirst($cv['jenis']) ?>)</td>
                                    <td class="p-3 text-slate-600">
                                        <?php if ($cv['kode_kriteria'] === 'C1'): ?>
                                            Rp <?= number_format($product['harga'], 0, ',', '.') ?>
                                        <?php elseif ($cv['kode_kriteria'] === 'C2'): ?>
                                            <?= htmlspecialchars($product['ketebalan']) ?>
                                        <?php elseif ($cv['kode_kriteria'] === 'C3'): ?>
                                            <?= htmlspecialchars($product['merk']) ?>
                                        <?php elseif ($cv['kode_kriteria'] === 'C4'): ?>
                                            <?= htmlspecialchars($product['garansi']) ?>
                                        <?php elseif ($cv['kode_kriteria'] === 'C5'): ?>
                                            <?= htmlspecialchars($product['finishing']) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-3 text-right font-mono font-bold text-slate-900"><?= $cv['nilai'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100 text-xs text-slate-600 leading-relaxed">
                    <strong>Deskripsi Produk:</strong> <?= nl2br(htmlspecialchars($product['deskripsi_spesifikasi'])) ?>
                </div>
            </div>
        </div>

        <!-- Right Col: Volumetric Logistics & Checkout Form (Dokumen 1) -->
        <div class="lg:col-span-5 space-y-6">

            <!-- 1. Kalkulator Kargo Volumetrik (Dokumen 1 Section Formula Logistik) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center">
                        <i class="fa-solid fa-truck-ramp-box text-amber-500 mr-2"></i> Logistik Kargo Volumetrik
                    </h3>
                    <span class="text-[11px] font-bold text-brand-600 bg-brand-50 px-2 py-0.5 rounded border border-brand-200">
                        Formula P&times;L&times;T/4000
                    </span>
                </div>

                <!-- City Selector Form -->
                <div class="mt-4">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Pilih Kota Tujuan Ekspedisi:</label>
                    <select id="cargoCitySelect" onchange="updateShippingEstimate()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold bg-slate-50">
                        <?php foreach ($cargoData['cities'] as $city): ?>
                            <option value="<?= htmlspecialchars($city['name']) ?>" <?= ($city['name'] === $cargoData['city']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($city['name']) ?> (Tarif Dasar: Rp <?= number_format($city['base_rate'], 0, ',', '.') ?>/kg)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Volumetric Calculation Breakdown Cards -->
                <div class="grid grid-cols-2 gap-3 mt-4 text-xs">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-[10px] text-slate-400 block uppercase font-bold">Berat Volumetrik Darat:</span>
                        <span class="font-mono font-bold text-slate-800 text-sm"><?= $cargoData['volumetric_land_sea'] ?> kg</span>
                        <span class="text-[10px] text-slate-500 block mt-0.5">(<?= $product['panjang_cm'] ?>&times;<?= $product['lebar_cm'] ?>&times;<?= $product['tinggi_cm'] ?>) / 4000</span>
                    </div>

                    <div class="p-3 bg-brand-50 rounded-xl border border-brand-200">
                        <span class="text-[10px] text-brand-700 block uppercase font-bold">Charged Weight:</span>
                        <span class="font-mono font-bold text-brand-700 text-sm"><?= $cargoData['charged_weight'] ?> kg</span>
                        <span class="text-[10px] text-brand-600 block mt-0.5">max(<?= $cargoData['actual_weight'] ?>kg, <?= $cargoData['volumetric_land_sea'] ?>kg)</span>
                    </div>
                </div>

                <!-- Courier Options Radio List -->
                <div class="mt-4 space-y-2" id="courierList">
                    <?php foreach ($cargoData['couriers'] as $idx => $cour): ?>
                        <label class="flex items-center justify-between p-3 rounded-xl border cursor-pointer transition <?= ($idx === 0) ? 'border-brand-500 bg-brand-50/40 ring-1 ring-brand-500' : 'border-slate-200 hover:bg-slate-50' ?>">
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="selected_courier" value="<?= $cour['code'] ?>" data-name="<?= htmlspecialchars($cour['name']) ?>" data-cost="<?= $cour['total_cost'] ?>" data-service="<?= htmlspecialchars($cour['service']) ?>" <?= ($idx === 0) ? 'checked' : '' ?> onchange="calculateOrderTotal()" class="text-brand-600 focus:ring-brand-500">
                                <div>
                                    <span class="text-xs font-bold text-slate-900 block"><?= htmlspecialchars($cour['name']) ?></span>
                                    <span class="text-[11px] text-slate-500"><?= htmlspecialchars($cour['service']) ?> • Estimasi: <?= $cour['etd'] ?></span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-extrabold text-slate-900 block">Rp <?= number_format($cour['total_cost'], 0, ',', '.') ?></span>
                                <span class="text-[10px] text-slate-400">Termasuk packing kayu</span>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- 2. Form Pemesanan & Pembayaran DP (Dokumen 1 Section Skema DP & Webhook) -->
            <div id="checkout" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center">
                    <i class="fa-solid fa-credit-card text-emerald-600 mr-2"></i> Pemesanan &amp; Pembayaran DP
                </h3>

                <form id="orderForm" onsubmit="handleOrderSubmit(event)" class="space-y-4">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['nama_produk']) ?>">
                    <input type="hidden" name="product_price" value="<?= $product['harga'] ?>">

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Pemesan:</label>
                        <input type="text" name="customer_name" required placeholder="Nama Lengkap Anda" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-brand-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor WhatsApp:</label>
                            <input type="text" name="customer_phone" required placeholder="08xxxxxxxxxx" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Pesanan:</label>
                            <select name="order_type" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold bg-slate-50">
                                <option value="Pre-Order (PO)">Pre-Order (PO 14 Hari)</option>
                                <option value="Ready Stock">Ready Stock</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Pengiriman Lengkap:</label>
                        <textarea name="customer_address" required rows="2" placeholder="Nama Jalan, RT/RW, Kecamatan, Kota" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-brand-500"></textarea>
                    </div>

                    <!-- Payment Scheme: Full vs DP (Dokumen 1) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Skema Pembayaran Transaksi:</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 block text-xs">
                                <input type="radio" name="payment_scheme" value="Down Payment (DP 50%)" checked onchange="calculateOrderTotal()" class="text-brand-600 mr-1.5">
                                <strong class="text-slate-900">Uang Muka (DP 50%)</strong>
                                <span class="block text-[10px] text-slate-500 mt-1">Sisa 50% dilunasi saat furnitur siap dikirim</span>
                            </label>

                            <label class="p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 block text-xs">
                                <input type="radio" name="payment_scheme" value="Full Payment" onchange="calculateOrderTotal()" class="text-brand-600 mr-1.5">
                                <strong class="text-slate-900">Pelunasan Penuh (100%)</strong>
                                <span class="block text-[10px] text-slate-500 mt-1">Pembayaran sekaligus diawal pesanan</span>
                            </label>
                        </div>
                    </div>

                    <!-- Summary calculation box -->
                    <div class="p-4 bg-slate-900 text-white rounded-xl space-y-2 text-xs">
                        <div class="flex justify-between text-slate-300">
                            <span>Harga Mebel:</span>
                            <span class="font-mono">Rp <?= number_format($product['harga'], 0, ',', '.') ?></span>
                        </div>
                        <div class="flex justify-between text-slate-300">
                            <span>Ongkir Kargo &amp; Packing:</span>
                            <span class="font-mono" id="displayShippingCost">Rp <?= number_format($cargoData['couriers'][0]['total_cost'], 0, ',', '.') ?></span>
                        </div>
                        <div class="flex justify-between text-slate-300 pt-2 border-t border-slate-800 font-bold">
                            <span>Total Nilai Pesanan:</span>
                            <span class="font-mono text-cyan-300 text-sm" id="displayTotalOrder">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-amber-400 font-bold text-sm pt-1">
                            <span id="displayPaymentLabel">Tagihan DP 50%:</span>
                            <span class="font-mono text-base font-black" id="displayPayAmount">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" id="btnSubmitOrder" class="w-full py-3.5 px-4 rounded-xl text-sm font-bold bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white shadow-md shadow-emerald-500/25 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-lock mr-2"></i> Lanjutkan Pembayaran (Simulasi Midtrans Snap)
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- WebAR Modal Dialog (Dokumen 1) -->
<div id="webARModal" class="hidden fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 text-center space-y-4 shadow-2xl relative">
        <button onclick="hideWebARModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="w-14 h-14 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mx-auto">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-900 font-['Plus_Jakarta_Sans']">
            Pengalaman WebAR Tanpa Aplikasi
        </h3>
        <p class="text-xs text-slate-600 leading-relaxed">
            Arahkan kamera smartphone Anda ke QR Code di bawah untuk memproyeksikan furnitur ini secara langsung dengan skala 1:1 di dalam ruangan rumah Anda via <strong>Google Scene Viewer</strong> (Android) atau <strong>Apple Quick Look</strong> (iOS).
        </p>

        <!-- Simulated WebAR QR Code -->
        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 inline-block mx-auto">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=https://mebelalum.id/ar-view?sku=<?= $product['sku'] ?>" alt="WebAR QR Code" class="w-44 h-44 rounded-lg">
        </div>

        <div class="text-[11px] text-slate-500 space-y-1">
            <p>• Format Aset: <strong>.GLB</strong> (glTF 2.0 Draco) &amp; <strong>.USDZ</strong></p>
            <p>• MIME Type Server: <code>model/vnd.usdz+zip</code></p>
        </div>

        <button onclick="hideWebARModal()" class="w-full py-2.5 rounded-xl font-bold bg-slate-900 text-white text-xs">
            Tutup Jendela AR
        </button>
    </div>
</div>

<!-- Midtrans Snap Payment Simulation Modal (Dokumen 1) -->
<div id="paymentModal" class="hidden fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-5 shadow-2xl relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold px-2 py-0.5 rounded bg-brand-100 text-brand-700">MIDTRANS SNAP SANDBOX</span>
            </div>
            <button onclick="hidePaymentModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="text-center space-y-1">
            <span class="text-xs text-slate-500">Nomor Transaksi: <strong id="modalOrderCode" class="text-slate-800"></strong></span>
            <div class="text-3xl font-black text-slate-900 font-['Plus_Jakarta_Sans']" id="modalPayAmount"></div>
            <p class="text-xs text-emerald-600 font-semibold" id="modalPayScheme"></p>
        </div>

        <!-- Payment Method Options -->
        <div class="space-y-2 text-xs">
            <label class="flex items-center justify-between p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50">
                <div class="flex items-center space-x-3">
                    <input type="radio" name="pay_channel" value="QRIS Instant (GoPay/OVO/Dana)" checked class="text-brand-600">
                    <span class="font-bold text-slate-800">QRIS (GoPay, ShopeePay, BCA, Dana)</span>
                </div>
                <span class="text-[10px] bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded">Otomatis Terverifikasi</span>
            </label>
            <label class="flex items-center justify-between p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50">
                <div class="flex items-center space-x-3">
                    <input type="radio" name="pay_channel" value="BCA Virtual Account" class="text-brand-600">
                    <span class="font-bold text-slate-800">BCA Virtual Account</span>
                </div>
                <span class="text-[10px] text-slate-400 font-mono">VA: 88019283741</span>
            </label>
            <label class="flex items-center justify-between p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50">
                <div class="flex items-center space-x-3">
                    <input type="radio" name="pay_channel" value="Mandiri Bill Payment" class="text-brand-600">
                    <span class="font-bold text-slate-800">Mandiri Virtual Account</span>
                </div>
                <span class="text-[10px] text-slate-400 font-mono">VA: 70012938411</span>
            </label>
        </div>

        <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-[11px] text-amber-800 flex items-start space-x-2">
            <i class="fa-solid fa-circle-info text-amber-600 mt-0.5"></i>
            <span>Saat mengklik "Simulasikan Bayar Sukses", sistem backend akan mengeksekusi <strong>Webhook Midtrans</strong> (/api/webhook) dan mengupdate status pesanan secara real-time.</span>
        </div>

        <button onclick="triggerWebhookPaymentSuccess()" class="w-full py-3 rounded-xl font-bold bg-emerald-600 hover:bg-emerald-700 text-white text-sm shadow flex items-center justify-center">
            <i class="fa-solid fa-check-double mr-2"></i> Simulasikan Bayar Sukses (Webhook Callback)
        </button>
    </div>
</div>

<script>
    let productViewer = null;
    let currentProduct = <?= json_encode($product) ?>;
    let activeOrderId = null;
    let activeOrderCode = null;

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Aluminium3DViewer !== 'undefined') {
            productViewer = new Aluminium3DViewer('product3DBox', {
                width: <?= (float)$product['panjang_cm'] ?>,
                depth: <?= (float)$product['lebar_cm'] ?>,
                height: <?= (float)$product['tinggi_cm'] ?>,
                finish: '<?= strtolower(str_replace(' ', '_', $product['finishing'])) ?>',
                productType: '<?= (strpos($product['nama_kategori'], 'Kitchen') !== false) ? 'kitchen' : ((strpos($product['nama_kategori'], 'Lemari') !== false) ? 'wardrobe' : 'kitchen') ?>'
            });
        }
        calculateOrderTotal();
    });

    function changeFinish(finishKey) {
        if (productViewer) {
            productViewer.setFinish(finishKey);
        }
    }

    function toggleDoorAnimation() {
        if (productViewer) {
            const isOpen = productViewer.toggleDoors();
            const btn = document.getElementById('btnToggleDoor');
            if (isOpen) {
                btn.innerHTML = '<i class="fa-solid fa-door-closed mr-1"></i> Tutup Pintu 3D';
                btn.className = 'p-2 rounded-lg bg-amber-100 text-amber-800 text-xs font-bold transition flex items-center';
            } else {
                btn.innerHTML = '<i class="fa-solid fa-door-open mr-1"></i> Buka Pintu 3D';
                btn.className = 'p-2 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-700 text-xs font-bold transition flex items-center';
            }
        }
    }

    function showWebARModal() {
        document.getElementById('webARModal').classList.remove('hidden');
    }

    function hideWebARModal() {
        document.getElementById('webARModal').classList.add('hidden');
    }

    function hidePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    function updateShippingEstimate() {
        const city = document.getElementById('cargoCitySelect').value;
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('city', city);
        window.location.href = currentUrl.toString();
    }

    function calculateOrderTotal() {
        const productPrice = <?= (float)$product['harga'] ?>;
        const selectedCourierEl = document.querySelector('input[name="selected_courier"]:checked');
        const courierCost = selectedCourierEl ? parseFloat(selectedCourierEl.getAttribute('data-cost')) : 0;
        
        const total = productPrice + courierCost;
        const schemeEl = document.querySelector('input[name="payment_scheme"]:checked');
        const isDP = schemeEl && schemeEl.value.includes('DP');
        const payAmount = isDP ? Math.round(total * 0.5) : total;

        document.getElementById('displayShippingCost').innerText = SawClient.formatRupiah(courierCost);
        document.getElementById('displayTotalOrder').innerText = SawClient.formatRupiah(total);
        document.getElementById('displayPaymentLabel').innerText = isDP ? 'Tagihan DP (50%):' : 'Tagihan Pelunasan (100%):';
        document.getElementById('displayPayAmount').innerText = SawClient.formatRupiah(payAmount);
    }

    async function handleOrderSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = document.getElementById('btnSubmitOrder');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Menghubungi Payment Gateway...';

        const formData = new FormData(form);
        const selectedCourierEl = document.querySelector('input[name="selected_courier"]:checked');
        
        formData.append('courier_name', selectedCourierEl.getAttribute('data-name'));
        formData.append('courier_service', selectedCourierEl.getAttribute('data-service'));
        formData.append('shipping_cost', selectedCourierEl.getAttribute('data-cost'));
        formData.append('city_destination', document.getElementById('cargoCitySelect').value);
        formData.append('actual_weight_kg', '<?= $cargoData['actual_weight'] ?>');
        formData.append('volumetric_weight_kg', '<?= $cargoData['volumetric_land_sea'] ?>');
        formData.append('charged_weight_kg', '<?= $cargoData['charged_weight'] ?>');

        try {
            const resp = await fetch('index.php?action=api_create_order', {
                method: 'POST',
                body: formData
            });
            const data = await resp.json();

            if (data.success) {
                activeOrderId = data.order_id;
                activeOrderCode = data.order_code;

                document.getElementById('modalOrderCode').innerText = data.order_code;
                document.getElementById('modalPayAmount').innerText = SawClient.formatRupiah(data.dp_amount);
                document.getElementById('modalPayScheme').innerText = (data.dp_amount < data.total_amount) 
                    ? 'Skema Down Payment (DP 50%) • Sisa Rp ' + SawClient.formatRupiah(data.remaining_amount) 
                    : 'Pelunasan Penuh (100%)';

                document.getElementById('paymentModal').classList.remove('hidden');
            } else {
                Swal.fire('Gagal Membuat Pesanan', data.message || 'Terjadi kesalahan sistem.', 'error');
            }
        } catch (err) {
            Swal.fire('Error Koneksi', err.message, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-lock mr-2"></i> Lanjutkan Pembayaran (Simulasi Midtrans Snap)';
        }
    }

    async function triggerWebhookPaymentSuccess() {
        if (!activeOrderCode) return;
        const channel = document.querySelector('input[name="pay_channel"]:checked').value;

        try {
            const resp = await fetch('index.php?action=api_midtrans_webhook', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    order_id: activeOrderCode,
                    transaction_status: 'settlement',
                    payment_type: channel
                })
            });
            const res = await resp.json();

            hidePaymentModal();
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran DP Terverifikasi!',
                html: `Webhook Midtrans berhasil diproses!<br>Status Pesanan: <strong class="text-emerald-600">${res.order_status}</strong><br>Kode Resi / Order: <strong>${activeOrderCode}</strong>`,
                confirmButtonText: 'Lacak Status Pesanan'
            }).then(() => {
                window.location.href = `index.php?action=tracking&order_code=${activeOrderCode}`;
            });
        } catch (err) {
            Swal.fire('Gagal Webhook', err.message, 'error');
        }
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

