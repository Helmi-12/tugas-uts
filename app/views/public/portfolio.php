<?php
$pageTitle = 'Portofolio Pengerjaan Mebel Aluminium - MebelAlum';
require_once __DIR__ . '/../layouts/header.php';

$projects = [
    [
        'title' => 'Kitchen Set Alexindo Urat Kayu Luxury',
        'client' => 'Bpk. Hendra - Perum Grand Galaxy, Surabaya',
        'specs' => 'Profil Alexindo 1.2mm • Finishing Urat Kayu Oak • Top Table Solid Surface • Engsel Soft-Close 304',
        'image' => 'assets/images/prod-kitchen-1.svg',
        'tag' => 'Kitchen Set',
        'lead_time' => '14 Hari'
    ],
    [
        'title' => 'Wardrobe 4 Pintu Sliding Full Cermin Bevel',
        'client' => 'Ibu Ratna - CitraLand West Surabaya',
        'specs' => 'Profil YKK AP 1.5mm • Finishing Titanium Grey • Kaca Cermin 5mm Asah Poly • Rel Bawah Hettich',
        'image' => 'assets/images/prod-lemari-2.svg',
        'tag' => 'Lemari Pakaian',
        'lead_time' => '12 Hari'
    ],
    [
        'title' => 'Bupet TV & Lemari Hias Display Minimalis',
        'client' => 'Bpk. Agus - Graha Famili, Surabaya',
        'specs' => 'Profil Alexindo 1.2mm • Tempered Glass 8mm • Lampu LED Strip 3000K • Laci Push-to-Open',
        'image' => 'assets/images/prod-bupet-2.svg',
        'tag' => 'Bupet TV',
        'lead_time' => '10 Hari'
    ],
    [
        'title' => 'Pintu Geser (Sliding Door) & Partisi Ruangan',
        'client' => 'Cafe & Bakery Kopi Kita, Malang',
        'specs' => 'Profil Dacon 1.0mm • Powder Coating Black Matte • Kaca Riben 5mm • Handle Stainless 40cm',
        'image' => 'assets/images/prod-pintu-1.svg',
        'tag' => 'Pintu & Kusen',
        'lead_time' => '7 Hari'
    ],
    [
        'title' => 'Kitchen Set Compact Minimalis Putih Bersih',
        'client' => 'Ibu Diana - Apartemen Gunawangsa, Surabaya',
        'specs' => 'Profil Dacon 1.0mm • White Pearl Powder Coating • Rak Piring Stainless Dalam • Anti Rayap',
        'image' => 'assets/images/prod-kitchen-3.svg',
        'tag' => 'Kitchen Set',
        'lead_time' => '8 Hari'
    ],
    [
        'title' => 'Jemuran Pakaian Lipat Jumbo 9 Palang Pipa Besar',
        'client' => 'Laundry Kiloan Barokah, Sidoarjo',
        'specs' => 'Pipa Aluminium Tebal 1.2mm • Roda Heavy Duty Pengunci • Kapasitas Beban 80 kg',
        'image' => 'assets/images/prod-jemuran-1.svg',
        'tag' => 'Jemuran',
        'lead_time' => 'Ready Stock'
    ],
];
?>

<div class="bg-slate-900 text-white py-12 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-brand-500/20 text-brand-300 border border-brand-500/30 mb-3">
                <i class="fa-solid fa-images mr-1.5"></i> Blueprint Section 5.1: Portofolio &amp; Galeri Project
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight font-['Plus_Jakarta_Sans']">
                Portofolio Pengerjaan Riil Mebel Aluminium
            </h1>
            <p class="text-slate-400 text-sm sm:text-base mt-2">
                Dokumentasi hasil instalasi nyata di rumah pelanggan, kantor, dan ruko dengan standar presisi tinggi, tahan rayap, dan bebas karat.
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($projects as $item): ?>
            <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group">
                <div class="h-60 bg-slate-100 relative overflow-hidden">
                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-900/80 text-white backdrop-blur-md">
                        <?= $item['tag'] ?>
                    </span>
                    <span class="absolute bottom-3 right-3 text-[11px] font-bold text-brand-300 bg-slate-900/80 px-2 py-0.5 rounded backdrop-blur-md">
                        Waktu Pengerjaan: <?= $item['lead_time'] ?>
                    </span>
                </div>

                <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <span class="text-xs text-brand-600 font-semibold block mb-1">
                            <i class="fa-solid fa-user-check mr-1"></i> <?= htmlspecialchars($item['client']) ?>
                        </span>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-brand-600 transition-colors">
                            <?= htmlspecialchars($item['title']) ?>
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            <strong>Spesifikasi:</strong> <?= htmlspecialchars($item['specs']) ?>
                        </p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-emerald-600 font-bold flex items-center">
                            <i class="fa-solid fa-shield-halved mr-1"></i> Terpasang Rapi &amp; Garansi
                        </span>
                        <a href="index.php?action=rekomendasi" class="text-xs font-bold text-brand-600 hover:text-brand-700">
                            Hitung Budget Serupa <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

