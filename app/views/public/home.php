<?php
$pageTitle = 'Beranda - NJ Aluminium';
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Flash Messages -->
<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-3 text-sm flex items-center justify-between max-w-7xl mx-auto mt-4 rounded-xl">
        <span><i class="fa-solid fa-circle-check mr-2 text-emerald-500"></i><?= htmlspecialchars($_SESSION['flash_success']) ?></span>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-br from-blue-900 via-blue-800 to-cyan-700 text-white pt-20 pb-28">
    <!-- Background Decoration -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 right-10 w-96 h-96 rounded-full bg-cyan-300 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 rounded-full bg-blue-300 blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-white/10 text-cyan-200 border border-white/20 mb-6">
            <i class="fa-solid fa-star mr-2 text-amber-400"></i>
            Jasa Mebel Aluminium Berkualitas Tinggi
        </div>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight font-['Plus_Jakarta_Sans'] leading-tight mb-6">
            Selamat Datang di<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-amber-300">NJ Aluminium</span>
        </h1>

        <p class="text-lg text-blue-100 max-w-2xl mx-auto mb-10 leading-relaxed">
            Kami menyediakan berbagai produk mebel aluminium berkualitas tinggi — tahan karat, anti rayap, dan bisa disesuaikan dengan ukuran & kebutuhan Anda.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="index.php?action=katalog" class="px-8 py-4 rounded-xl font-bold bg-white text-blue-700 hover:bg-blue-50 shadow-lg transition-all hover:scale-105 inline-flex items-center justify-center">
                <i class="fa-solid fa-boxes-stacked mr-2"></i> Lihat Katalog Produk
            </a>
            <a href="index.php?action=pesanan" class="px-8 py-4 rounded-xl font-bold bg-amber-500 hover:bg-amber-600 text-slate-900 shadow-lg transition-all hover:scale-105 inline-flex items-center justify-center">
                <i class="fa-solid fa-clipboard-list mr-2"></i> Pesan Sekarang
            </a>
        </div>
    </div>
</section>

<!-- Keunggulan Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">Keunggulan Kami</span>
            <h2 class="text-3xl font-extrabold text-slate-900 font-['Plus_Jakarta_Sans'] mt-3">Mengapa Memilih NJ Aluminium?</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 hover:shadow-lg transition-all text-center group">
                <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Anti Rayap &amp; Karat</h3>
                <p class="text-sm text-slate-500">Material aluminium premium tahan terhadap serangan rayap dan korosi.</p>
            </div>

            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 hover:shadow-lg transition-all text-center group">
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-medal"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Garansi 5 Tahun</h3>
                <p class="text-sm text-slate-500">Setiap produk bergaransi hingga 5 tahun untuk ketenangan pikiran Anda.</p>
            </div>

            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 hover:shadow-lg transition-all text-center group">
                <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-2xl mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-ruler-combined"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Custom Ukuran</h3>
                <p class="text-sm text-slate-500">Dibuat sesuai ukuran yang Anda inginkan — pas dan presisi.</p>
            </div>

            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 hover:shadow-lg transition-all text-center group">
                <div class="w-14 h-14 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Konsultasi Gratis</h3>
                <p class="text-sm text-slate-500">Tim kami siap membantu konsultasi desain dan ukuran via WhatsApp.</p>
            </div>
        </div>
    </div>
</section>

<!-- Produk Unggulan Section -->
<section class="py-16 bg-slate-50 border-y border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Produk Kami</span>
                <h2 class="text-3xl font-extrabold text-slate-900 font-['Plus_Jakarta_Sans'] mt-1">Produk Aluminium Unggulan</h2>
                <p class="text-slate-500 mt-2 text-sm">Berbagai pilihan produk mebel aluminium yang dapat kami kerjakan untuk Anda.</p>
            </div>
            <a href="index.php?action=katalog" class="text-sm font-bold text-blue-600 hover:text-blue-700 mt-4 md:mt-0 flex items-center">
                Lihat Semua <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <?php
            $produkUnggulan = [
                ['icon' => 'fa-door-open',      'nama' => 'Pintu Aluminium',    'warna' => 'blue'],
                ['icon' => 'fa-border-all',      'nama' => 'Jendela Aluminium',  'warna' => 'cyan'],
                ['icon' => 'fa-cabinet-filing',  'nama' => 'Lemari Aluminium',   'warna' => 'emerald'],
                ['icon' => 'fa-utensils',        'nama' => 'Kitchen Set',        'warna' => 'amber'],
                ['icon' => 'fa-layer-group',     'nama' => 'Rak Piring',         'warna' => 'purple'],
                ['icon' => 'fa-shirt',           'nama' => 'Jemuran Aluminium',  'warna' => 'rose'],
            ];
            $colors = [
                'blue'    => 'bg-blue-100 text-blue-600',
                'cyan'    => 'bg-cyan-100 text-cyan-600',
                'emerald' => 'bg-emerald-100 text-emerald-600',
                'amber'   => 'bg-amber-100 text-amber-600',
                'purple'  => 'bg-purple-100 text-purple-600',
                'rose'    => 'bg-rose-100 text-rose-600',
            ];
            foreach ($produkUnggulan as $p):
                $colorClass = $colors[$p['warna']];
            ?>
            <a href="index.php?action=katalog" class="bg-white rounded-2xl p-5 border border-slate-200 hover:shadow-lg hover:border-blue-200 transition-all text-center group cursor-pointer">
                <div class="w-12 h-12 rounded-xl <?= $colorClass ?> flex items-center justify-center text-xl mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid <?= $p['icon'] ?>"></i>
                </div>
                <p class="text-xs font-bold text-slate-800 leading-tight"><?= $p['nama'] ?></p>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Cara Pemesanan -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">Cara Pesan</span>
            <h2 class="text-3xl font-extrabold text-slate-900 font-['Plus_Jakarta_Sans'] mt-3">Langkah Mudah Pemesanan</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl font-black mx-auto mb-4">1</div>
                <h3 class="font-bold text-slate-900 mb-2">Pilih Produk</h3>
                <p class="text-sm text-slate-500">Pilih jenis produk aluminium yang ingin Anda pesan dari katalog kami.</p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl font-black mx-auto mb-4">2</div>
                <h3 class="font-bold text-slate-900 mb-2">Isi Form Pesanan</h3>
                <p class="text-sm text-slate-500">Masukkan ukuran (panjang × lebar), bahan, dan detail pesanan Anda.</p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl font-black mx-auto mb-4">3</div>
                <h3 class="font-bold text-slate-900 mb-2">Konfirmasi via WA</h3>
                <p class="text-sm text-slate-500">Tim kami akan menghubungi Anda via WhatsApp untuk konfirmasi pesanan.</p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl font-black mx-auto mb-4">4</div>
                <h3 class="font-bold text-slate-900 mb-2">Produksi &amp; Kirim</h3>
                <p class="text-sm text-slate-500">Produk dikerjakan sesuai pesanan dan dikirim ke lokasi Anda.</p>
            </div>
        </div>

        <div class="mt-10 text-center">
            <a href="index.php?action=pesanan" class="inline-flex items-center px-8 py-3.5 rounded-xl font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-md transition-all">
                Buat Pesanan Sekarang <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
