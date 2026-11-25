# SISTEM PENDUKUNG KEPUTUSAN PEMILIHAN PRODUK MEBEL ALUMINIUM
## Implementasi Metode Simple Additive Weighting (SAW), Visual Commerce 3D/WebAR, dan Manajemen Kargo Volumetrik Berbasis Web

> **Dokumen Rujukan:**
> 1. Analisis Rancang Bangun Perangkat Lunak E-Commerce Mebel
> 2. BLUEPRINT & Spesifikasi Sistem
> **Dosen Pembimbing / Penguji:** Pak Rofi  
> **Tahun Akademik:** 2026

---

## 1. Ringkasan Eksekutif Sistem

Aplikasi ini merupakan ekosistem web terpadu yang memadukan dua pilar utama:
1. **Sistem Pendukung Keputusan (SPK) dengan Metode SAW:** Membantu konsumen memilih variasi spesifikasi mebel aluminium terbaik yang paling efisien berdasarkan alokasi anggaran (budget minimal dan maksimal) serta 5 parameter material kunci (Harga, Ketebalan, Merk, Garansi, Finishing).
2. **Visual Commerce 3D & WebAR serta Kargo Volumetrik:** Mengatasi jurang visualisasi (*visualization gap*) melalui rendering interaktif WebGL Three.js dan WebAR (Google Scene Viewer & Apple AR Quick Look), serta otomatisasi kalkulasi kargo jalur darat/laut $((P \times L \times T) / 4000)$ dan skema pembayaran Down Payment (DP 50%) via Webhook Midtrans.

---

## 2. Cara Menjalankan Aplikasi (Quick Start)

### Opsi A: Satu Klik (Windows Batch File)
Cukup klik ganda berkas **`run.bat`** di direktori utama:
```cmd
run.bat
```
Berkas ini akan secara otomatis:
1. Membuka peramban (browser) ke alamat `http://localhost:8000`
2. Menjalankan web server lokal PHP bawaan

### Opsi B: Terminal / Command Prompt
Buka PowerShell atau CMD di folder ini, lalu jalankan:
```bash
php -S localhost:8000
```
Buka browser pada: **`http://localhost:8000`**

### Database Ready Out-of-the-Box
Sistem secara otomatis menggunakan **SQLite (`database/database.sqlite`)** yang langsung terinisialisasi dan ter-seeding dengan 6 kategori mebel, 18 produk alternatif, 5 kriteria penilaian, dan 90 nilai matriks keputusan $X$.

> **Catatan untuk Sidang / Pengujian MySQL:**  
> Jika dosen/penguji menginginkan MySQL phpMyAdmin, berkas **`database/database.sql`** telah disediakan lengkap dengan struktur DDL dan DML. Cukup impor ke phpMyAdmin dan atur environment `DB_CONNECTION=mysql`.

---

## 3. Akun Pengujian Sistem (Demo Credentials)

| Role | Username | Password | Deskripsi |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `admin123` | Akses penuh dashboard, manajemen kriteria, produk, & laporan |
| **Dosen Penguji** | `pakrofi` | `rofi123` | Akun khusus pengujian skripsi Pak Rofi (Hak Admin) |
| **Pelanggan** | `budi` | `budi123` | Akun pembeli/konsumen untuk simulasi transaksi & DP |

*Catatan: Pada halaman login telah disediakan tombol "Satu Klik Isi Akun Demo" untuk kemudahan presentasi.*

---

## 4. Landasan Teori & Formulasi Algoritma SAW

Metode *Simple Additive Weighting* (SAW) sering dikenal sebagai metode penjumlahan terbobot.

### 4.1 Definisi 5 Kriteria Penilaian

| Kode | Nama Kriteria | Sifat (Type) | Bobot ($W_j$) | Skala Penilaian |
| :---: | :--- | :---: | :---: | :--- |
| **C1** | Harga Produk | **Cost** | **0.30 (30%)** | Nilai rupiah riil. Semakin murah semakin tinggi skor normalisasinya. |
| **C2** | Ketebalan Aluminium | **Benefit** | **0.25 (25%)** | 0.8mm = 1, 1.0mm = 2, 1.2mm = 3, 1.5mm = 4 |
| **C3** | Merk / Kualitas Material | **Benefit** | **0.20 (20%)** | Lokal = 1, Dacon = 2, Alexindo = 3, YKK AP = 4 |
| **C4** | Ketahanan / Garansi | **Benefit** | **0.15 (15%)** | 1 Thn = 1, 2 Thn = 2, 3 Thn = 3, 5 Thn = 4 |
| **C5** | Variasi Finishing/Warna | **Benefit** | **0.10 (10%)** | Silver Anodize = 1, Powder Coating = 2, Urat Kayu = 3 |

Total Bobot Terverifikasi: $\sum_{j=1}^5 W_j = 0.30 + 0.25 + 0.20 + 0.15 + 0.10 = 1.00$ (100%).

---

## 5. Fitur Baru Mobile APK (PWA) & Modul Interaktif

1. **Progressive Web App (PWA) & APK Ready:**
   - Dilengkapi `manifest.json`, Service Worker `sw.js`, dan antarmuka *Mobile Native Bottom Navigation Bar*.
   - Panduan lengkap kompilasi menjadi berkas Android APK (*Bubblewrap TWA* / *Capacitor*) tersedia di [**`APK_BUILD_GUIDE.md`**](file:///d:/tugas%20pak%20rofi/APK_BUILD_GUIDE.md).
2. **Animasi Buka/Tutup Pintu 3D & WebAR Intent:**
   - Tombol interaktif "Buka Pintu 3D" memutar engsel pintu (*hinge lerp*) untuk memperlihatkan rak aluminium di bagian dalam.
3. **Upload Berkas Gambar Lokal di Panel Admin:**
   - Dukungan unggah gambar langsung dari disk komputer ke `assets/images/uploads/`.
4. **Perhitungan & Laporan Bab 4 Skripsi:**
   - Tampilan Matriks Keputusan ($X$), Matriks Ternormalisasi ($R$), dan Nilai Preferensi ($V_i$) siap cetak format resmi skripsi.

### 4.2 Formulasi Normalisasi Matriks ($R$)
- **Untuk Kriteria Benefit:**
  $$r_{ij} = \frac{x_{ij}}{\max_i(x_{ij})}$$
- **Untuk Kriteria Cost:**
  $$r_{ij} = \frac{\min_i(x_{ij})}{x_{ij}}$$

### 4.3 Nilai Akhir Preferensi ($V_i$)
$$V_i = \sum_{j=1}^n W_j \cdot r_{ij}$$

Alternatif dengan nilai $V_i$ terbesar merupakan rekomendasi produk terbaik (#1).

---

## 5. Formulasi Logistik Kargo Volumetrik (Dokumen 1)

1. **Berat Volumetrik Kargo Darat/Laut (kg):**
   $$\text{Berat Volumetrik} = \frac{\text{Panjang (cm)} \times \text{Lebar (cm)} \times \text{Tinggi (cm)}}{4000}$$
2. **Kubikasi ($\text{m}^3$):**
   $$\text{CBM} = \frac{\text{Panjang (cm)} \times \text{Lebar (cm)} \times \text{Tinggi (cm)}}{1.000.000}$$
3. **Berat Terpakai (*Charged Weight*):**
   $$\text{Charged Weight} = \max(\text{Berat Aktual}, \text{Berat Volumetrik})$$

---

## 6. Struktur Direktori Proyek

```
d:\tugas pak rofi\
├── app/
│   ├── config/
│   │   └── database.php           # PDO SQLite & MySQL Driver
│   ├── controllers/
│   │   ├── HomeController.php     # Beranda & showcase 3D
│   │   ├── CatalogController.php  # Katalog mebel aluminium
│   │   ├── SawController.php      # Engine SPK SAW & filtering budget
│   │   ├── ProductController.php  # 3D Configurator, WebAR, Kargo
│   │   ├── OrderController.php    # Checkout, Simulasi DP, Webhook
│   │   ├── AdminController.php    # Master data & Laporan Bab 4
│   │   └── AuthController.php     # Sesi & Keamanan login
│   ├── helpers/
│   │   ├── SawEngine.php          # Engine komputasi matematis SAW
│   │   └── VolumetricCargo.php    # Formula kargo & kurir logistik
│   ├── models/                    # Kategori, Kriteria, Produk, Nilai, Order, User
│   └── views/
│       ├── layouts/               # Header, Footer, Admin Layout
│       ├── public/                # Halaman antarmuka pengguna
│       ├── admin/                 # Panel admin & matriks laporan
│       └── auth/                  # Login portal
├── assets/
│   ├── css/custom.css             # Glassmorphism & print stylesheet Bab 4
│   ├── js/3d-viewer.js            # Three.js 3D WebGL Configurator
│   ├── js/saw-calculator.js       # Client helper & WhatsApp URL formatter
│   └── images/                    # Asset gambar visual produk & kategori
├── database/
│   ├── database.sqlite            # Database aktif auto-generated
│   ├── database.sql               # Dump MySQL untuk phpMyAdmin
│   ├── schema.sql                 # Struktur tabel DDL
│   └── seed.sql                   # Data awal DML
├── index.php                      # Front Controller & Routing
├── run.bat                        # Launcher praktis Windows
└── README.md                      # Dokumentasi komprehensif
```

---

## 7. Fitur Unggulan untuk Sidang Tugas Akhir (Pak Rofi)

1. **Transparansi Perhitungan Matriks SAW (Bab 4):**
   - Menampilkan tahapan matriks $X$, $R$, perkalian bobot $W$, dan perangkingan $V$ secara transparan.
   - Dilengkapi fitur **"Cetak Laporan Bab 4"** dengan kop surat akademik resmi dan kolom tanda tangan penguji.
2. **Verifikasi Presisi 100% (White Box):**
   - Halaman khusus `index.php?action=verifikasi` yang membandingkan hasil formula Excel dengan sistem ($\Delta = 0.000000$).
3. **Integrasi WhatsApp Otomatis:**
   - Tombol pemesanan langsung memformat pesan WhatsApp berisi rincian produk, skor match SAW, dan estimasi biaya.
4. **Simulasi Webhook Midtrans Real-time:**
   - Menyimulasikan siklus Down Payment 50% hingga status pesanan otomatis berganti menjadi "Dalam Proses Produksi".

