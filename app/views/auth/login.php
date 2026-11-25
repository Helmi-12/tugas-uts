<?php
$pageTitle = 'Login Pengguna - NJ Aluminium';
$activeTab = $activeTab ?? 'pelanggan';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-[80vh] flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full space-y-6">
        
        <!-- Header / Logo -->
        <div class="text-center">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-700 via-brand-600 to-cyan-500 text-white flex items-center justify-center text-2xl font-black mx-auto shadow-xl shadow-brand-500/25">
                NJ
            </div>
            <h2 class="mt-4 text-2xl sm:text-3xl font-extrabold text-slate-900 font-['Plus_Jakarta_Sans'] tracking-tight">
                Selamat Datang di NJ Aluminium
            </h2>
            <p class="mt-1.5 text-xs text-slate-500 max-w-sm mx-auto">
                Silakan login untuk mengakses katalog produk mebel aluminium presisi, engine rekomendasi SPK SAW, dan visualisasi 3D.
            </p>
        </div>

        <!-- Flash Messages -->
        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl text-xs flex items-center shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-rose-500 mr-2.5 text-lg flex-shrink-0"></i>
                <span class="font-medium"><?= htmlspecialchars($_SESSION['flash_error']) ?></span>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl text-xs flex items-center shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-500 mr-2.5 text-lg flex-shrink-0"></i>
                <span class="font-medium"><?= htmlspecialchars($_SESSION['flash_success']) ?></span>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <!-- Auth Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xl shadow-slate-200/50">
            
            <!-- Navigation Tabs (Pelanggan vs Admin vs Register) -->
            <div class="flex rounded-2xl bg-slate-100 p-1.5 mb-6 text-xs font-bold">
                <button type="button" id="tabBtnPelanggan" onclick="switchAuthTab('pelanggan')" class="flex-1 py-2.5 px-3 rounded-xl transition-all flex items-center justify-center space-x-1.5 <?= ($activeTab === 'pelanggan') ? 'bg-white text-brand-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' ?>">
                    <i class="fa-solid fa-bag-shopping text-sm text-brand-600"></i>
                    <span>Pelanggan</span>
                </button>
                <button type="button" id="tabBtnAdmin" onclick="switchAuthTab('admin')" class="flex-1 py-2.5 px-3 rounded-xl transition-all flex items-center justify-center space-x-1.5 <?= ($activeTab === 'admin') ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' ?>">
                    <i class="fa-solid fa-user-shield text-sm text-slate-800"></i>
                    <span>Administrator</span>
                </button>
                <button type="button" id="tabBtnRegister" onclick="switchAuthTab('register')" class="flex-1 py-2.5 px-3 rounded-xl transition-all flex items-center justify-center space-x-1.5 <?= ($activeTab === 'register') ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' ?>">
                    <i class="fa-solid fa-user-plus text-sm text-emerald-600"></i>
                    <span>Daftar Akun</span>
                </button>
            </div>

            <!-- PANEL 1: LOGIN PELANGGAN -->
            <div id="panelPelanggan" class="<?= ($activeTab === 'pelanggan') ? '' : 'hidden' ?>">
                <div class="mb-5 pb-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800 font-['Plus_Jakarta_Sans']">Masuk sebagai Pembeli / Pelanggan</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Akses katalog mebel, cari rekomendasi budget, & simulasi 3D.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-brand-50 text-brand-700 border border-brand-200">
                        <i class="fa-solid fa-cart-shopping mr-1"></i> Pelanggan
                    </span>
                </div>

                <form method="POST" action="index.php?action=login_post" class="space-y-4">
                    <input type="hidden" name="role_login" value="pelanggan">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5 tracking-wider">Username Pelanggan:</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3 text-slate-400 text-xs"><i class="fa-solid fa-user"></i></span>
                            <input type="text" id="custUsername" name="username" required placeholder="Contoh: budi" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password:</label>
                        </div>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3 text-slate-400 text-xs"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" id="custPassword" name="password" required placeholder="••••••••" class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                            <button type="button" onclick="togglePassword('custPassword', this)" class="absolute right-3.5 top-2.5 text-slate-400 hover:text-slate-600 text-xs">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl font-bold bg-brand-600 hover:bg-brand-700 text-white text-xs shadow-md shadow-brand-500/25 transition-all hover:scale-[1.01] flex items-center justify-center">
                        <i class="fa-solid fa-right-to-bracket mr-2"></i> Masuk ke Beranda NJ Aluminium
                    </button>
                </form>

                <!-- Quick Fill Pelanggan -->
                <div class="mt-5 pt-4 border-t border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">
                        <i class="fa-solid fa-bolt text-amber-500 mr-1"></i> Akun Pengujian Demo Pelanggan:
                    </span>
                    <button type="button" onclick="fillCustomer('budi', 'budi123')" class="w-full p-2.5 rounded-xl bg-slate-50 hover:bg-brand-50/60 border border-slate-200 text-left transition flex items-center justify-between group">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-7 h-7 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center text-xs font-bold">
                                B
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-800 block">Budi Pratama (Pelanggan)</span>
                                <span class="text-[10px] text-slate-400 font-mono">budi / budi123</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-brand-600 group-hover:underline">Gunakan &rarr;</span>
                    </button>
                </div>

                <div class="mt-4 text-center">
                    <span class="text-xs text-slate-500">Belum punya akun pelanggan? </span>
                    <button type="button" onclick="switchAuthTab('register')" class="text-xs font-bold text-brand-600 hover:underline">
                        Daftar Akun Baru Sekarang
                    </button>
                </div>
            </div>

            <!-- PANEL 2: LOGIN ADMINISTRATOR -->
            <div id="panelAdmin" class="<?= ($activeTab === 'admin') ? '' : 'hidden' ?>">
                <div class="mb-5 pb-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 font-['Plus_Jakarta_Sans']">Masuk sebagai Administrator</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Kelola data master kriteria SAW, katalog produk, dan pesanan.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-900 text-amber-400 border border-slate-800">
                        <i class="fa-solid fa-shield-halved mr-1"></i> Admin Panel
                    </span>
                </div>

                <form method="POST" action="index.php?action=login_post" class="space-y-4">
                    <input type="hidden" name="role_login" value="admin">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5 tracking-wider">Username Admin:</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3 text-slate-400 text-xs"><i class="fa-solid fa-user-gear"></i></span>
                           <input type="text" id="adminUsername" name="username" required placeholder="nj aluminium" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-2 focus:ring-slate-800 focus:outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5 tracking-wider">Password:</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3 text-slate-400 text-xs"><i class="fa-solid fa-key"></i></span>
                            <input type="password" id="adminPassword" name="password" required placeholder="••••••••" class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-2 focus:ring-slate-800 focus:outline-none transition">
                            <button type="button" onclick="togglePassword('adminPassword', this)" class="absolute right-3.5 top-2.5 text-slate-400 hover:text-slate-600 text-xs">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl font-bold bg-slate-900 hover:bg-slate-800 text-white text-xs shadow-md shadow-slate-900/20 transition-all hover:scale-[1.01] flex items-center justify-center">
                        <i class="fa-solid fa-lock-open mr-2 text-amber-400"></i> Masuk ke Dashboard Admin
                    </button>
                </form>
            </div>

            

            <!-- PANEL 3: DAFTAR AKUN BARU -->
            <div id="panelRegister" class="<?= ($activeTab === 'register') ? '' : 'hidden' ?>">
                <div class="mb-5 pb-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 font-['Plus_Jakarta_Sans']">Registrasi Akun Pelanggan Baru</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Daftar akun secara instan untuk melakukan pesanan & rekomendasi SAW.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                        <i class="fa-solid fa-user-plus mr-1"></i> Gratis
                    </span>
                </div>

                <form method="POST" action="index.php?action=register_post" class="space-y-3.5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap:</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3 text-slate-400 text-xs"><i class="fa-solid fa-id-card"></i></span>
                            <input type="text" name="nama_lengkap" required placeholder="Contoh: Rahmat Hidayat" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Username:</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-3 text-slate-400 text-xs"><i class="fa-solid fa-user"></i></span>
                                <input type="text" name="username" required placeholder="username_anda" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password:</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-3 text-slate-400 text-xs"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" id="regPassword" name="password" required placeholder="••••••••" class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                                <button type="button" onclick="togglePassword('regPassword', this)" class="absolute right-3.5 top-2.5 text-slate-400 hover:text-slate-600 text-xs">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No. WhatsApp / HP:</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-3 text-slate-400 text-xs"><i class="fa-brands fa-whatsapp text-emerald-600"></i></span>
                                <input type="tel" name="phone" required placeholder="08123456789" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email (Opsional):</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-3 text-slate-400 text-xs"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" name="email" placeholder="nama@email.com" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl font-bold bg-emerald-600 hover:bg-emerald-700 text-white text-xs shadow-md shadow-emerald-600/25 transition-all hover:scale-[1.01] flex items-center justify-center mt-2">
                        <i class="fa-solid fa-user-check mr-2"></i> Buat Akun &amp; Masuk ke Beranda
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <span class="text-xs text-slate-500">Sudah memiliki akun? </span>
                    <button type="button" onclick="switchAuthTab('pelanggan')" class="text-xs font-bold text-brand-600 hover:underline">
                        Masuk di Sini
                    </button>
                </div>
            </div>

        </div>

        <!-- Security Note -->
        <div class="text-center text-[11px] text-slate-400 flex items-center justify-center space-x-2">
            <i class="fa-solid fa-lock text-emerald-500"></i>
            <span>Sistem terlindungi dengan enkripsi Bcrypt password &amp; sesi aman.</span>
        </div>

    </div>
</div>

<script>
    function switchAuthTab(tab) {
        const panels = ['pelanggan', 'admin', 'register'];
        panels.forEach(p => {
            const el = document.getElementById('panel' + p.charAt(0).toUpperCase() + p.slice(1));
            const btn = document.getElementById('tabBtn' + p.charAt(0).toUpperCase() + p.slice(1));
            if (p === tab) {
                el.classList.remove('hidden');
                btn.className = "flex-1 py-2.5 px-3 rounded-xl transition-all flex items-center justify-center space-x-1.5 bg-white text-slate-900 shadow-sm";
            } else {
                el.classList.add('hidden');
                btn.className = "flex-1 py-2.5 px-3 rounded-xl transition-all flex items-center justify-center space-x-1.5 text-slate-600 hover:text-slate-900";
            }
        });
    }

    function fillCustomer(u, p) {
        document.getElementById('custUsername').value = u;
        document.getElementById('custPassword').value = p;
    }

    function fillAdmin(u, p) {
        document.getElementById('adminUsername').value = u;
        document.getElementById('adminPassword').value = p;
    }

    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fa-solid fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fa-solid fa-eye';
        }
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
