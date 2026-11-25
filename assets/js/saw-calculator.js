/**
 * Client-Side SAW Calculator & WhatsApp Integrator
 */

const SawClient = {
    formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    },

    formatWhatsAppMessage(product, rank, score, storePhone = '6281234567890') {
        const text = 
`Halo Admin Bengkel Mebel Aluminium,
Saya telah melakukan perhitungan anggaran dan spesifikasi melalui Sistem Pendukung Keputusan (Metode SAW) di website Anda.

Berikut adalah detail produk rekomendasi terbaik yang saya pilih:
*Ranking SPK:* Peringkat #${rank} (Skor Match: ${score}%)
*Nama Produk:* ${product.nama_produk}
*SKU:* ${product.sku}
*Kategori:* ${product.nama_kategori || 'Mebel Aluminium'}
*Estimasi Harga:* ${this.formatRupiah(product.harga)}
*Ketebalan Profil:* ${product.ketebalan}
*Merk Material:* ${product.merk}
*Finishing:* ${product.finishing}
*Masa Garansi:* ${product.garansi}
*Dimensi Fisik:* ${product.panjang_cm}cm x ${product.lebar_cm}cm x ${product.tinggi_cm}cm

Mohon informasi terkait konsultasi kustomisasi, jadwal survei lokasi, dan tahapan Down Payment (DP). Terima kasih!`;

        const encoded = encodeURIComponent(text);
        return `https://wa.me/${storePhone}?text=${encoded}`;
    }
};

window.SawClient = SawClient;

