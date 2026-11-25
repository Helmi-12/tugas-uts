    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 pt-12 pb-8 border-t border-slate-800 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-10">
                <!-- Brand -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-cyan-500 flex items-center justify-center text-white font-black text-sm tracking-wider shadow">NJ</div>
                        <span class="text-xl font-bold tracking-tight text-white font-['Plus_Jakarta_Sans']">NJ Aluminium</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Spesialis mebel aluminium berkualitas tinggi. Anti rayap, anti karat, dan dapat disesuaikan dengan ukuran Anda.
                    </p>
                    <div class="flex items-center text-sm text-slate-400">
                        <i class="fa-solid fa-shield-halved text-amber-500 mr-2"></i> Garansi bahan hingga 5 tahun
                    </div>
                </div>

                <!-- Menu Navigasi -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white mb-4">Menu</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="index.php?action=home" class="hover:text-blue-400 transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-xs text-blue-500 mr-2"></i> Beranda</a></li>
                        <li><a href="index.php?action=katalog" class="hover:text-blue-400 transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-xs text-blue-500 mr-2"></i> Katalog Mebel</a></li>
                        <li><a href="index.php?action=pesanan" class="hover:text-blue-400 transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-xs text-blue-500 mr-2"></i> Form Pesanan</a></li>
                        <li><a href="index.php?action=akun" class="hover:text-blue-400 transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-xs text-blue-500 mr-2"></i> Akun Saya</a></li>
                    </ul>
                </div>

                <!-- Kontak -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white mb-4">Hubungi Kami</h3>
                    <div class="space-y-3 text-sm text-slate-400">
                        <div class="flex items-center">
                            <i class="fa-brands fa-whatsapp text-emerald-400 mr-3 text-lg"></i>
                            <a href="https://wa.me/6281234567890" target="_blank" class="hover:text-emerald-400 transition-colors">+62 812-3456-7890</a>
                        </div>
                        <div class="flex items-center">
                            <i class="fa-solid fa-location-dot text-rose-400 mr-3"></i>
                            <span>Workshop NJ Aluminium, Jawa Timur</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fa-regular fa-clock text-blue-400 mr-3"></i>
                            <span>Senin – Sabtu, 08.00 – 17.00 WIB</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center text-xs text-slate-500 gap-3">
                <span>&copy; <?= date('Y') ?> <strong class="text-slate-300">NJ Aluminium</strong>. Semua hak cipta dilindungi.</span>
                <span class="flex items-center space-x-3">
                    <span>Mebel Aluminium Berkualitas</span>
                </span>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Nav -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-slate-200 px-2 py-2 shadow-2xl flex justify-around items-center">
        <a href="index.php?action=home" class="flex flex-col items-center px-3 <?= ($action === 'home') ? 'text-blue-600 font-bold' : 'text-slate-500' ?>">
            <i class="fa-solid fa-house text-lg"></i>
            <span class="text-[10px] mt-0.5">Beranda</span>
        </a>
        <a href="index.php?action=katalog" class="flex flex-col items-center px-3 <?= ($action === 'katalog') ? 'text-blue-600 font-bold' : 'text-slate-500' ?>">
            <i class="fa-solid fa-boxes-stacked text-lg"></i>
            <span class="text-[10px] mt-0.5">Katalog</span>
        </a>
        <a href="index.php?action=pesanan" class="flex flex-col items-center px-3 -mt-4">
            <div class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-lg text-lg">
                <i class="fa-solid fa-clipboard-list"></i>
            </div>
            <span class="text-[10px] font-bold text-blue-600 mt-0.5">Pesan</span>
        </a>
        <a href="index.php?action=akun" class="flex flex-col items-center px-3 <?= ($action === 'akun') ? 'text-blue-600 font-bold' : 'text-slate-500' ?>">
            <i class="fa-solid fa-circle-user text-lg"></i>
            <span class="text-[10px] mt-0.5">Akun</span>
        </a>
    </div>
    <div class="md:hidden h-16"></div><!-- Spacer for mobile bottom nav -->

    <!-- Scripts -->
    <script>
        document.getElementById('mobileMenuBtn')?.addEventListener('click', function() {
            const menu = document.getElementById('mobileMenu');
            menu?.classList.toggle('hidden');
        });
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('sw.js')
                    .then(reg => console.log('PWA SW Registered:', reg.scope))
                    .catch(err => console.warn('PWA SW Failed:', err));
            });
        }
    </script>
</body>
</html>
