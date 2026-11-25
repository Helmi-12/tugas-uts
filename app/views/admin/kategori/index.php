<?php
$pageTitle = 'Manajemen Kategori Mebel - Admin SPK';
ob_start();
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-slate-800 gap-4">
    <div>
        <h2 class="text-xl font-bold text-white font-['Plus_Jakarta_Sans']">Kategori Mebel Aluminium</h2>
        <p class="text-xs text-slate-400">Kelola kelompok jenis furnitur aluminium yang ditawarkan kepada pelanggan.</p>
    </div>
    <button onclick="openModalTambah()" class="px-4 py-2.5 rounded-xl font-bold bg-brand-600 hover:bg-brand-700 text-white text-xs shadow-md shadow-brand-500/20 flex items-center">
        <i class="fa-solid fa-plus mr-1.5"></i> Tambah Kategori Baru
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
    <?php foreach ($categories as $cat): ?>
        <div class="bg-slate-800/80 rounded-2xl border border-slate-700/80 p-5 flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-start justify-between">
                    <div class="w-12 h-12 rounded-xl bg-slate-900 overflow-hidden border border-slate-700">
                        <img src="<?= htmlspecialchars($cat['foto'] ?: 'assets/images/cat-kitchen.svg') ?>" class="w-full h-full object-cover">
                    </div>
                    <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-brand-500/20 text-brand-300 border border-brand-500/30">
                        <?= $cat['total_produk'] ?> Produk
                    </span>
                </div>
                <h3 class="text-base font-bold text-white mt-3"><?= htmlspecialchars($cat['nama_kategori']) ?></h3>
                <p class="text-xs text-slate-400 mt-1 line-clamp-2"><?= htmlspecialchars($cat['deskripsi']) ?></p>
            </div>

            <div class="pt-3 border-t border-slate-700 flex items-center justify-between">
                <span class="text-[11px] text-slate-500 font-mono">slug: <?= htmlspecialchars($cat['slug']) ?></span>
                <div class="flex space-x-2">
                    <button onclick='openModalEdit(<?= json_encode($cat) ?>)' class="p-2 text-xs rounded-lg bg-slate-700 hover:bg-slate-600 text-slate-200">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <a href="index.php?action=admin_kategori_delete&id=<?= $cat['id'] ?>" onclick="return confirm('Yakin ingin menghapus kategori ini beserta produk terkait?')" class="p-2 text-xs rounded-lg bg-rose-500/20 hover:bg-rose-500/30 text-rose-400">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal Tambah / Edit Kategori -->
<div id="modalKategori" class="hidden fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl relative text-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <h3 class="text-base font-bold text-white font-['Plus_Jakarta_Sans']" id="modalTitle">Tambah Kategori Mebel</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form method="POST" action="index.php?action=admin_kategori_save" class="space-y-4">
            <input type="hidden" name="id" id="formCatId" value="">

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Nama Kategori:</label>
                <input type="text" name="nama_kategori" id="formCatNama" required placeholder="Contoh: Kitchen Set Aluminium" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">URL Foto / Asset:</label>
                <input type="text" name="foto" id="formCatFoto" placeholder="assets/images/cat-kitchen.svg" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Deskripsi Singkat:</label>
                <textarea name="deskripsi" id="formCatDeskripsi" rows="3" placeholder="Jelaskan karakteristik dan keunggulan mebel..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-950 text-xs font-semibold text-white focus:ring-brand-500"></textarea>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl font-bold bg-brand-600 hover:bg-brand-700 text-white text-xs shadow">
                Simpan Kategori
            </button>
        </form>
    </div>
</div>

<script>
    function openModalTambah() {
        document.getElementById('modalTitle').innerText = 'Tambah Kategori Mebel';
        document.getElementById('formCatId').value = '';
        document.getElementById('formCatNama').value = '';
        document.getElementById('formCatFoto').value = 'assets/images/cat-kitchen.svg';
        document.getElementById('formCatDeskripsi').value = '';
        document.getElementById('modalKategori').classList.remove('hidden');
    }

    function openModalEdit(cat) {
        document.getElementById('modalTitle').innerText = 'Edit Kategori Mebel';
        document.getElementById('formCatId').value = cat.id;
        document.getElementById('formCatNama').value = cat.nama_kategori;
        document.getElementById('formCatFoto').value = cat.foto || '';
        document.getElementById('formCatDeskripsi').value = cat.deskripsi || '';
        document.getElementById('modalKategori').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modalKategori').classList.add('hidden');
    }
</script>

<?php
$adminContent = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin_layout.php';
?>

