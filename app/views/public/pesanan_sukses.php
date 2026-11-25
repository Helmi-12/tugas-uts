<?php
$pageTitle = 'Pesanan Berhasil - NJ Aluminium';
require_once __DIR__ . '/../layouts/header.php';

$waPhone = '6287772144696';
$kodePesanan = $pesanan['kode_pesanan'] ?? '-';
$namaPelanggan = $pesanan['nama_pelanggan'] ?? '-';
$noTelepon = $pesanan['no_telepon'] ?? '-';
$jenisProduk = $pesanan['jenis_produk'] ?? '-';
$tinggiCm = (int)($pesanan['tinggi_cm'] ?? 0);
$lebarCm = (int)($pesanan['lebar_cm'] ?? 0);
$bahanPilihan = $pesanan['bahan_pilihan'] ?? '-';
$alamat = $pesanan['alamat'] ?? '-';
$catatan = $pesanan['catatan_tambahan'] ?? '-';

$pesanWa = "Halo NJ Aluminium, saya ingin konfirmasi pesanan kustom:\n"
    . "• Kode Pesanan: {$kodePesanan}\n"
    . "• Nama: {$namaPelanggan}\n"
    . "• No. HP: {$noTelepon}\n"
    . "• Produk: {$jenisProduk}\n"
    . "• Ukuran (T × L): {$tinggiCm} cm × {$lebarCm} cm\n"
    . "• Bahan: {$bahanPilihan}\n"
    . "• Alamat: {$alamat}\n"
    . "• Catatan: {$catatan}";

$waUrl = 'https://wa.me/' . $waPhone . '?text=' . rawurlencode($pesanWa);
?>

<section class="py-20 bg-slate-50">
    <div class="max-w-lg mx-auto px-4 text-center">
        <div class="bg-white rounded-3xl border border-emerald-200 shadow-lg p-10">
            <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-500 flex items-center justify-center text-4xl mx-auto mb-6">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 font-['Plus_Jakarta_Sans'] mb-2">Pesanan Berhasil Dikirim!</h1>
            <p class="text-slate-500 text-sm mb-6">Terima kasih, <strong><?= htmlspecialchars($namaPelanggan) ?></strong>! Pesanan Anda telah kami terima.</p>

            <div class="bg-slate-50 rounded-xl border border-slate-200 p-5 text-left space-y-3 text-sm mb-8">
                <div class="flex justify-between">
                    <span class="text-slate-500">Kode Pesanan</span>
                    <span class="font-bold text-slate-900 font-mono"><?= htmlspecialchars($kodePesanan) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Produk</span>
                    <span class="font-semibold text-slate-800"><?= htmlspecialchars($jenisProduk) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Ukuran (T × L)</span>
                    <span class="font-semibold text-slate-800">
                        <?= htmlspecialchars($tinggiCm . ' cm × ' . $lebarCm . ' cm') ?>
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Bahan</span>
                    <span class="font-semibold text-slate-800"><?= htmlspecialchars($bahanPilihan) ?></span>
                </div>
            </div>

            <div class="space-y-3">
                <a href="<?= htmlspecialchars($waUrl, ENT_QUOTES, 'UTF-8') ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="block w-full py-3 px-6 rounded-xl font-bold bg-emerald-500 hover:bg-emerald-600 text-white text-sm transition-colors shadow">
                    <i class="fa-brands fa-whatsapp mr-2"></i> Konfirmasi via WhatsApp
                </a>
                <a href="index.php?action=home" class="block w-full py-3 px-6 rounded-xl font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm transition-colors">
                    <i class="fa-solid fa-house mr-2"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>