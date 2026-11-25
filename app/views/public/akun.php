<?php
$pageTitle = 'Akun Saya - NJ Aluminium';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">

        <?php if ($currentUser): ?>
            <!-- Logged In: Profile & Riwayat Pesanan -->
            <div class="mb-8">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex items-center space-x-5">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-blue-600 to-cyan-500 flex items-center justify-center text-white text-2xl font-bold shadow-md">
                        <?= strtoupper(substr($currentUser['nama_lengkap'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-extrabold text-slate-900"><?= htmlspecialchars($currentUser['nama_lengkap'] ?? '') ?></h2>
                        <p class="text-sm text-slate-500"><?= htmlspecialchars($currentUser['email'] ?? '') ?></p>
                        <span class="inline-block mt-1 text-xs font-bold px-2.5 py-0.5 rounded-full <?= $isAdmin ? 'bg-slate-900 text-amber-400' : 'bg-blue-100 text-blue-700' ?>">
                            <?= $isAdmin ? 'Administrator' : 'Pelanggan' ?>
                        </span>
                    </div>
                    <?php if ($isAdmin): ?>
                        <a href="index.php?action=admin_dashboard" class="px-4 py-2 rounded-xl text-sm font-bold bg-slate-900 text-white hover:bg-slate-800 transition-colors">
                            <i class="fa-solid fa-chart-pie mr-1.5 text-blue-400"></i> Dashboard Admin
                        </a>
                    <?php endif; ?>
                    <a href="index.php?action=logout" class="px-4 py-2 rounded-xl text-sm font-semibold text-rose-600 hover:bg-rose-50 border border-rose-200 transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-1.5"></i> Keluar
                    </a>
                </div>
            </div>

            <!-- Riwayat Pesanan Kustom -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900"><i class="fa-solid fa-clipboard-list mr-2 text-blue-500"></i>Riwayat Pesanan Saya</h3>
                    <a href="index.php?action=pesanan" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                        + Buat Pesanan Baru
                    </a>
                </div>
                <div class="p-6">
                    <?php if (empty($pesananList)): ?>
                        <div class="text-center py-12 text-slate-400">
                            <div class="text-5xl mb-4">📋</div>
                            <p class="font-semibold">Belum Ada Pesanan</p>
                            <p class="text-sm mt-1">Anda belum membuat pesanan apa pun.</p>
                            <a href="index.php?action=pesanan" class="inline-flex items-center mt-5 px-6 py-2.5 rounded-xl text-sm font-bold bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                                Buat Pesanan Pertama
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($pesananList as $p): ?>
                                <div class="border border-slate-200 rounded-xl p-5">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span class="text-xs font-bold font-mono text-blue-600 bg-blue-50 px-2 py-0.5 rounded"><?= htmlspecialchars($p['kode_pesanan'] ?? '') ?></span>
                                            <h4 class="font-bold text-slate-900 mt-1"><?= htmlspecialchars($p['jenis_produk'] ?? '') ?></h4>
                                            <p class="text-sm text-slate-500 mt-0.5">
                                                <?= htmlspecialchars($p['panjang_cm'] ?? 0) ?> × <?= htmlspecialchars($p['lebar_cm'] ?? 0) ?> cm
                                                <?php if (!empty($p['tinggi_cm'])): ?> × <?= htmlspecialchars($p['tinggi_cm']) ?> cm<?php endif; ?>
                                                &bull; <?= htmlspecialchars($p['bahan_pilihan'] ?? '') ?>
                                            </p>
                                        </div>
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full <?= ($p['status_pesanan'] ?? '') === 'Selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' ?>">
                                            <?= htmlspecialchars($p['status_pesanan'] ?? 'Menunggu Konfirmasi') ?>
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-2"><?= htmlspecialchars($p['created_at'] ?? '') ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?>
            <!-- Not Logged In -->
            <div class="max-w-md mx-auto text-center">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-10">
                    <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center text-3xl mx-auto mb-5">
                        <i class="fa-solid fa-circle-user"></i>
                    </div>
                    <h2 class="text-xl font-extrabold text-slate-900 mb-2">Area Akun</h2>
                    <p class="text-sm text-slate-500 mb-6">Silakan login untuk melihat profil dan riwayat pesanan Anda. Atau buat pesanan tanpa login melalui form pesanan.</p>
                    <div class="space-y-3">
                        <a href="index.php?action=login" class="block w-full py-3 px-6 rounded-xl font-bold bg-blue-600 hover:bg-blue-700 text-white text-sm transition-colors">
                            <i class="fa-solid fa-lock mr-2"></i> Login ke Akun
                        </a>
                        <a href="index.php?action=pesanan" class="block w-full py-3 px-6 rounded-xl font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm transition-colors">
                            <i class="fa-solid fa-clipboard-list mr-2"></i> Pesan Tanpa Login
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
