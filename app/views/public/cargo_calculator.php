<?php
$pageTitle = 'Kalkulator Logistik Kargo Volumetrik - MebelAlum';
require_once __DIR__ . '/../layouts/header.php';

$p = (float)($_GET['p'] ?? 200);
$l = (float)($_GET['l'] ?? 60);
$t = (float)($_GET['t'] ?? 85);
$beratAktual = (float)($_GET['berat'] ?? 42);
$selectedCity = $_GET['city'] ?? 'DKI Jakarta & Bodetabek';

$cargoResult = VolumetricCargo::calculateShippingOptions($p, $l, $t, $beratAktual, $selectedCity);
$cities = VolumetricCargo::getDestinationCities();
?>

<div class="bg-slate-900 text-white py-12 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-brand-500/20 text-brand-300 border border-brand-500/30 mb-3">
                <i class="fa-solid fa-truck-ramp-box mr-1.5"></i> Modul Ekspedisi &amp; Logistik Kargo (Dokumen 1)
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight font-['Plus_Jakarta_Sans']">
                Kalkulator Kargo Volumetrik Furnitur
            </h1>
            <p class="text-slate-400 text-sm sm:text-base mt-2">
                Hitung estimasi bobot volumetrik dan ongkos kirim kargo khusus mebel berdimensi besar menggunakan formula standar ekspedisi logistik Indonesia.
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Input Form Col -->
        <div class="lg:col-span-5">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center">
                    <i class="fa-solid fa-ruler text-brand-600 mr-2"></i> Parameter Dimensi Furnitur
                </h3>

                <form method="GET" action="index.php" class="space-y-4">
                    <input type="hidden" name="action" value="kalkulator_kargo">

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Panjang (cm):</label>
                            <input type="number" name="p" step="1" value="<?= $p ?>" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Lebar (cm):</label>
                            <input type="number" name="l" step="1" value="<?= $l ?>" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tinggi (cm):</label>
                            <input type="number" name="t" step="1" value="<?= $t ?>" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm font-semibold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Berat Timbangan Aktual (kg):</label>
                        <input type="number" name="berat" step="0.5" value="<?= $beratAktual ?>" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kota Tujuan Pengiriman:</label>
                        <select name="city" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold bg-slate-50">
                            <?php foreach ($cities as $c): ?>
                                <option value="<?= htmlspecialchars($c['name']) ?>" <?= ($c['name'] === $selectedCity) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl font-bold bg-brand-600 hover:bg-brand-700 text-white text-sm shadow flex items-center justify-center">
                        <i class="fa-solid fa-calculator mr-2"></i> Hitung Berat Terpakai &amp; Tarif
                    </button>
                </form>

                <!-- Formula reference notice -->
                <div class="mt-6 p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs space-y-2 text-slate-600">
                    <p class="font-bold text-slate-800"><i class="fa-solid fa-book text-brand-600 mr-1.5"></i> Formula Standar Dokumen 1:</p>
                    <p>• <strong>Kargo Darat/Laut:</strong> (P &times; L &times; T) / 4000 = <strong class="text-slate-900"><?= $cargoResult['volumetric_land_sea'] ?> kg</strong></p>
                    <p>• <strong>Kargo Udara:</strong> (P &times; L &times; T) / 6000 = <strong class="text-slate-900"><?= $cargoResult['volumetric_air'] ?> kg</strong></p>
                    <p>• <strong>Kubikasi (m&sup3;):</strong> (P &times; L &times; T) / 1.000.000 = <strong class="text-slate-900"><?= $cargoResult['cubic_meters'] ?> m&sup3;</strong></p>
                    <p class="pt-1 border-t border-slate-200 text-brand-700 font-bold">
                        Charged Weight: max(<?= $beratAktual ?>kg, <?= $cargoResult['volumetric_land_sea'] ?>kg) = <?= $cargoResult['charged_weight'] ?> kg
                    </p>
                </div>
            </div>
        </div>

        <!-- Result Breakdown Col -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                    <h3 class="text-base font-bold text-slate-900">
                        Hasil Perhitungan Ekspedisi ke <span class="text-brand-600"><?= htmlspecialchars($cargoResult['city']) ?></span>
                    </h3>
                    <span class="text-xs font-bold px-2.5 py-1 rounded bg-emerald-100 text-emerald-800">
                        Charged: <?= $cargoResult['charged_weight'] ?> kg
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <?php foreach ($cargoResult['couriers'] as $cour): ?>
                        <div class="p-5 rounded-xl border border-slate-200 hover:border-brand-500 hover:shadow-md transition bg-slate-50/50 flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($cour['name']) ?></h4>
                                        <span class="text-xs text-slate-500"><?= htmlspecialchars($cour['service']) ?></span>
                                    </div>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-brand-100 text-brand-700">
                                        <?= $cour['etd'] ?>
                                    </span>
                                </div>

                                <div class="mt-3 text-xs text-slate-600 space-y-1">
                                    <p>• Berat Terhitung: <strong><?= $cour['effective_weight'] ?> kg</strong> (Min: <?= $cour['min_weight'] ?>kg)</p>
                                    <p>• Biaya Kirim: Rp <?= number_format($cour['freight_cost'], 0, ',', '.') ?></p>
                                    <p>• Packing Kayu: Rp <?= number_format($cour['wooden_pack_fee'], 0, ',', '.') ?></p>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-200 flex items-baseline justify-between">
                                <span class="text-xs text-slate-500">Total Tarif:</span>
                                <span class="text-lg font-black text-slate-900 font-['Plus_Jakarta_Sans']">
                                    Rp <?= number_format($cour['total_cost'], 0, ',', '.') ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-6 text-center">
                    <a href="index.php?action=rekomendasi" class="inline-flex items-center text-xs font-bold text-brand-600 hover:text-brand-800">
                        <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Form Hitung Rekomendasi SAW
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

