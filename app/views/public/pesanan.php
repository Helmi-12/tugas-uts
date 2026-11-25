<?php
$pageTitle = 'Form Pesanan - NJ Aluminium';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="py-12 bg-slate-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">

        <!-- Flash -->
        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3 rounded-xl text-sm flex items-center">
                <i class="fa-solid fa-circle-exclamation mr-2 text-rose-500"></i>
                <?= htmlspecialchars($_SESSION['flash_error']) ?>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <div class="text-center mb-8">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">Form Pemesanan</span>
            <h1 class="text-3xl font-extrabold text-slate-900 font-['Plus_Jakarta_Sans'] mt-3">Pesan Produk Aluminium</h1>
            <p class="text-slate-500 mt-2 text-sm">Isi formulir di bawah ini dan kami akan segera menghubungi Anda via WhatsApp.</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
            <form method="POST" action="index.php?action=pesanan_simpan" class="space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Pelanggan -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_pelanggan" required placeholder="Contoh: Budi Santoso"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition-all">
                    </div>

                    <!-- No WhatsApp -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">No. WhatsApp / HP <span class="text-rose-500">*</span></label>
                        <input type="tel" name="no_telepon" required placeholder="Contoh: 08123456789"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition-all">
                    </div>
                </div>

                <!-- Jenis Produk -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Produk yang Dipesan <span class="text-rose-500">*</span></label>
                    <select name="jenis_produk" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition-all bg-white">
                        <option value="">-- Pilih Jenis Produk --</option>
                        <option value="Pintu Aluminium">Pintu Aluminium</option>
                        <option value="Jendela Aluminium">Jendela Aluminium</option>
                        <option value="Lemari Aluminium">Lemari Aluminium</option>
                        <option value="Kitchen Set Aluminium">Kitchen Set Aluminium</option>
                        <option value="Rak Piring Aluminium">Rak Piring Aluminium</option>
                        <option value="Jemuran Aluminium">Jemuran Aluminium</option>
                        <option value="Partisi Aluminium">Partisi Aluminium</option>
                        <option value="Lainnya">Lainnya (sebutkan di catatan)</option>
                    </select>
                </div>

                <!-- Ukuran -->
               <div class="mb-4">
                    <label for="tinggi_cm" class="block text-sm font-medium text-gray-700 mb-1">
                        Tinggi (cm) <span class="text-red-500">*</span>
                     </label>
                    <input
                        type="number"
                        id="tinggi_cm"
                        name="tinggi_cm"
                        min="1"
                        placeholder="Contoh: 180"
                        required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
                    >
                    </div>

                    <div class="mb-4">
                        <label for="lebar_cm" class="block text-sm font-medium text-gray-700 mb-1">
                            Lebar (cm) <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            id="lebar_cm"
                            name="lebar_cm"
                            min="1"
                            placeholder="Contoh: 90"
                            required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
                        >
                    </div>
                <!-- Bahan / Finishing -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Bahan / Finishing yang Diinginkan <span class="text-rose-500">*</span></label>
                    <select name="bahan_pilihan" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition-all bg-white">
                        <option value="">-- Pilih Bahan / Finishing --</option>
                        <option value="YKK AP">YKK AP</option>
                        <option value="Alexindo">Alexindo</option>
                        <option value="Alcomexindo">Alcomexindo</option>
                        <option value="Superex">Superex</option>
                        <option value="Inkalum">Inkalum</option>
                        <option value="SAA">SAA</option>
                        <option value="Lainnya">Lainnya (sebutkan di catatan)</option>
                    </select>
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Pengiriman</label>
                    <input type="text" name="alamat" placeholder="Jl. Contoh No. 1, Kota..."
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition-all">
                </div>

                <!-- Catatan Tambahan -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Catatan Tambahan</label>
                    <textarea name="catatan_tambahan" rows="3" placeholder="Contoh: warna lebih tua, ada kusen tambahan, dll."
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition-all resize-none"></textarea>
                </div>

                <!-- Submit -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 px-6 rounded-xl font-bold bg-blue-600 hover:bg-blue-700 text-white text-base transition-all shadow-md hover:shadow-lg">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Pesanan
                    </button>
                    <p class="text-center text-xs text-slate-400 mt-3">
                        <i class="fa-brands fa-whatsapp text-emerald-500 mr-1"></i>
                        Setelah form dikirim, tim kami akan menghubungi Anda via WhatsApp dalam 1×24 jam.
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
