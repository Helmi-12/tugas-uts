<?php
$pageTitle = 'Katalog Produk Mebel Aluminium - MebelAlum';
require_once __DIR__ . '/../layouts/header.php';

$activeCat = (int)($_GET['kategori_id'] ?? 0);
?>

<div class="bg-slate-900 text-white py-12 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="text-xs font-bold text-brand-400 uppercase tracking-wider block mb-2">
                <i class="fa-solid fa-boxes-stacked mr-1"></i> Visual Catalog &amp; 3D Configurator
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight font-['Plus_Jakarta_Sans']">
                Katalog Produk Mebel Aluminium
            </h1>
            <p class="text-slate-400 text-sm sm:text-base mt-2">
                Jelajahi berbagai pilihan furnitur aluminium presisi dengan material berkualitas (Alexindo, YKK, Dacon), tahan rayap, tahan lembab, dan awet bertahun-tahun.
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Category Tabs -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-4 scrollbar-thin">
        <a href="index.php?action=katalog&kategori_id=0" class="px-5 py-2.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all <?= ($activeCat === 0) ? 'bg-brand-600 text-white shadow-md' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' ?>">
            Semua Produk (<?= count($products) ?>)
        </a>
        <?php foreach ($categories as $cat): ?>
            <a href="index.php?action=katalog&kategori_id=<?= $cat['id'] ?>" class="px-5 py-2.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all <?= ($activeCat === (int)$cat['id']) ? 'bg-brand-600 text-white shadow-md' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' ?>">
                <?= htmlspecialchars($cat['nama_kategori']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
        <?php foreach ($products as $p): ?>
            <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group">
                <div class="h-60 bg-slate-100 relative overflow-hidden">
                    <img src="<?= htmlspecialchars($p['foto'] ?: 'assets/images/prod-kitchen-1.svg') ?>" alt="<?= htmlspecialchars($p['nama_produk']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>

                    <!-- Category & Brand Badge -->
                    <div class="absolute top-3 left-3 flex space-x-1.5">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-900/80 text-white backdrop-blur-md">
                            <?= htmlspecialchars($p['nama_kategori']) ?>
                        </span>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-brand-600 text-white">
                            <?= htmlspecialchars($p['merk']) ?>
                        </span>
                    </div>

                    <!-- 3D Indicator -->
                    <div class="absolute bottom-3 right-3">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-slate-900/80 text-cyan-300 text-[11px] font-bold backdrop-blur-md">
                            <i class="fa-solid fa-cube mr-1.5"></i> 3D Ready
                        </span>
                    </div>
                </div>

                <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <div class="flex items-baseline justify-between mb-2">
                            <span class="text-xs text-slate-400 font-mono"><?= htmlspecialchars($p['sku']) ?></span>
                            <span class="text-xl font-black text-slate-900 font-['Plus_Jakarta_Sans']">
                                Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                            </span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 group-hover:text-brand-600 transition-colors">
                            <?= htmlspecialchars($p['nama_produk']) ?>
                        </h3>

                        <!-- Specs Pills -->
                        <div class="mt-3 flex flex-wrap gap-1.5 text-[11px]">
                            <span class="px-2.5 py-1 rounded bg-slate-100 font-semibold text-slate-700">
                                📏 Tebal: <strong><?= htmlspecialchars($p['ketebalan']) ?></strong>
                            </span>
                            <span class="px-2.5 py-1 rounded bg-slate-100 font-semibold text-slate-700">
                                🛡️ Garansi: <strong><?= htmlspecialchars($p['garansi']) ?></strong>
                            </span>
                            <span class="px-2.5 py-1 rounded bg-slate-100 font-semibold text-slate-700 truncate max-w-[200px]">
                                🎨 <?= htmlspecialchars($p['finishing']) ?>
                            </span>
                        </div>

                        <p class="text-xs text-slate-500 mt-3 line-clamp-2">
                            <?= htmlspecialchars($p['deskripsi_spesifikasi']) ?>
                        </p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center gap-2">
                        <a href="index.php?action=produk_detail&id=<?= $p['id'] ?>" class="flex-1 py-2.5 px-3 rounded-xl text-xs font-bold bg-brand-600 hover:bg-brand-700 text-white text-center transition-colors shadow-sm">
                            <i class="fa-solid fa-cube mr-1.5"></i> Detail 3D &amp; Kargo
                        </a>
                        <a href="index.php?action=rekomendasi&kategori_id=<?= $p['kategori_id'] ?>&min_budget=<?= (int)($p['harga']*0.7) ?>&max_budget=<?= (int)($p['harga']*1.3) ?>" class="py-2.5 px-3 rounded-xl text-xs font-bold bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 transition-colors" title="Hitung Rekomendasi SAW">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

