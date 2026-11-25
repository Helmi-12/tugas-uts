-- ============================================================================
-- DATABASE MYSQL DUMP: SPK PEMILIHAN MEBEL ALUMINIUM (METODE SAW)
-- TUGAS AKHIR / SKRIPSI TEKNIK INFORMATIKA & SISTEM INFORMASI
-- DOSEN PENGUJI / PEMBIMBING: PAK ROFI
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `spk_mebel_saw` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `spk_mebel_saw`;

-- ----------------------------------------------------------------------------
-- 1. Table structure for users
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `nilai_alternatif`;
DROP TABLE IF EXISTS `produk_alternatif`;
DROP TABLE IF EXISTS `kriteria`;
DROP TABLE IF EXISTS `kategori_mebel`;
DROP TABLE IF EXISTS `search_logs`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `role` enum('admin','pelanggan') NOT NULL DEFAULT 'pelanggan',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `email`, `phone`, `role`) VALUES
(1, 'admin', '$2y$10$pLwJm9p3e4p5J/XqM0R0.OIJq7x5cQ0aH1qV1gU6wL3kP0xW4jT0a', 'Administrator Bengkel Aluminium', 'admin@mebelalum.id', '081234567890', 'admin'),
(2, 'pakrofi', '$2y$10$pLwJm9p3e4p5J/XqM0R0.OIJq7x5cQ0aH1qV1gU6wL3kP0xW4jT0a', 'Pak Rofi (Dosen Pembimbing/Penguji)', 'rofi@kampus.ac.id', '081298765432', 'admin'),
(3, 'budi', '$2y$10$pLwJm9p3e4p5J/XqM0R0.OIJq7x5cQ0aH1qV1gU6wL3kP0xW4jT0a', 'Budi Pratama', 'budi@gmail.com', '085712345678', 'pelanggan');

-- ----------------------------------------------------------------------------
-- 2. Table structure for kategori_mebel
-- ----------------------------------------------------------------------------
CREATE TABLE `kategori_mebel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL UNIQUE,
  `foto` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `kategori_mebel` (`id`, `nama_kategori`, `slug`, `foto`, `deskripsi`) VALUES
(1, 'Kitchen Set Aluminium Minimalis', 'kitchen-set-aluminium-minimalis', 'assets/images/cat-kitchen.svg', 'Kabinet dapur full aluminium tahan rayap, tahan lembab, anti karat, dan bebas jamur seumur hidup.'),
(2, 'Lemari Pakaian Aluminium', 'lemari-pakaian-aluminium', 'assets/images/cat-lemari.svg', 'Lemari pakaian 3 pintu dan sliding door rangka aluminium kokoh dengan cermin dan panel composite tebal.'),
(3, 'Rak Piring & Wastafel Aluminium', 'rak-piring-wastafel-aluminium', 'assets/images/cat-rak.svg', 'Rak piring multifungsi tahan korosi air cucian dengan ventilasi udara optimal dan laci piring tertutup.'),
(4, 'Bupet TV & Lemari Hias', 'bupet-tv-lemari-hias', 'assets/images/cat-bupet.svg', 'Bupet display TV modern dengan profil aluminium anodized elegan, kaca tempered, dan rak penyimpanan luas.'),
(5, 'Pintu & Jendela Aluminium Modern', 'pintu-jendela-aluminium-modern', 'assets/images/cat-pintu.svg', 'Kusen, pintu swing/sliding, dan jendela aluminium kedap suara, tahan cuaca panas & hujan ekstrem.'),
(6, 'Jemuran Aluminium Jumbo Lipat', 'jemuran-aluminium-jumbo-lipat', 'assets/images/cat-jemuran.svg', 'Jemuran pakaian lipat aluminium pipa tebal kapasitas besar, kokoh tidak melengkung, awet bertahun-tahun.');

-- ----------------------------------------------------------------------------
-- 3. Table structure for kriteria
-- ----------------------------------------------------------------------------
CREATE TABLE `kriteria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_kriteria` varchar(10) NOT NULL UNIQUE,
  `nama_kriteria` varchar(150) NOT NULL,
  `jenis` enum('benefit','cost') NOT NULL,
  `bobot` float NOT NULL,
  `keterangan_skala` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `kriteria` (`id`, `kode_kriteria`, `nama_kriteria`, `jenis`, `bobot`, `keterangan_skala`) VALUES
(1, 'C1', 'Harga Produk', 'cost', 0.30, 'Semakin murah (mendekati Min Budget), skor normalisasi semakin tinggi.'),
(2, 'C2', 'Ketebalan Aluminium', 'benefit', 0.25, 'Skala 1: 0.8mm | Skala 2: 1.0mm | Skala 3: 1.2mm | Skala 4: 1.5mm'),
(3, 'C3', 'Merk / Kualitas Material', 'benefit', 0.20, 'Skala 1: Lokal/Ekonomis | Skala 2: Dacon | Skala 3: Alexindo | Skala 4: YKK AP'),
(4, 'C4', 'Ketahanan / Garansi', 'benefit', 0.15, 'Skala 1: 1 Tahun | Skala 2: 2 Tahun | Skala 3: 3 Tahun | Skala 4: 5 Tahun'),
(5, 'C5', 'Variasi Finishing/Warna', 'benefit', 0.10, 'Skala 1: Silver Anodize | Skala 2: Powder Coating | Skala 3: Anodized Wood Grain (Urat Kayu)');

-- ----------------------------------------------------------------------------
-- 4. Table structure for produk_alternatif
-- ----------------------------------------------------------------------------
CREATE TABLE `produk_alternatif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_id` int(11) NOT NULL,
  `sku` varchar(50) NOT NULL UNIQUE,
  `nama_produk` varchar(200) NOT NULL,
  `harga` double NOT NULL,
  `ketebalan` varchar(50) NOT NULL,
  `merk` varchar(100) NOT NULL,
  `garansi` varchar(50) NOT NULL,
  `finishing` varchar(100) NOT NULL,
  `panjang_cm` float NOT NULL,
  `lebar_cm` float NOT NULL,
  `tinggi_cm` float NOT NULL,
  `berat_aktual_kg` float NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `model_glb_url` varchar(255) DEFAULT NULL,
  `deskripsi_spesifikasi` text DEFAULT NULL,
  `is_po` tinyint(1) NOT NULL DEFAULT 1,
  `lead_time_days` int(11) DEFAULT 14,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kategori_id` (`kategori_id`),
  CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_mebel` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `produk_alternatif` (`id`, `kategori_id`, `sku`, `nama_produk`, `harga`, `ketebalan`, `merk`, `garansi`, `finishing`, `panjang_cm`, `lebar_cm`, `tinggi_cm`, `berat_aktual_kg`, `foto`, `model_glb_url`, `deskripsi_spesifikasi`, `is_po`, `lead_time_days`) VALUES
(1, 1, 'KS-ALX-01', 'Kitchen Set Alexindo Wood Grain Premium', 6500000, '1.2 mm', 'Alexindo', '3 Tahun', 'Anodized Wood Grain (Urat Kayu)', 200, 60, 85, 42.0, 'assets/images/prod-kitchen-1.svg', 'assets/models/kitchen_set.glb', 'Kitchen set aluminium modern dengan finishing urat kayu mewah merk Alexindo, profil 1.2mm tahan benturan, engsel soft-close stainless 304.', 1, 14),
(2, 1, 'KS-YKK-02', 'Kitchen Set YKK AP Ultra Heavy Duty', 8800000, '1.5 mm', 'YKK', '5 Tahun', 'Powder Coating (Black Matte)', 220, 60, 85, 55.0, 'assets/images/prod-kitchen-2.svg', 'assets/models/kitchen_set.glb', 'Kitchen set kasta tertinggi dengan profil aluminium presisi YKK ketebalan 1.5mm, finishing powder coating hitam elegan anti gores, garansi 5 tahun.', 1, 21),
(3, 1, 'KS-DAC-03', 'Kitchen Set Dacon Standar Minimalis', 4900000, '1.0 mm', 'Dacon', '2 Tahun', 'Powder Coating (White Glaze)', 180, 55, 80, 36.0, 'assets/images/prod-kitchen-3.svg', 'assets/models/kitchen_set.glb', 'Pilihan kitchen set fungsional dan ekonomis dari Dacon 1.0mm dengan warna putih bersih anti jamur.', 1, 10),
(4, 1, 'KS-LOK-04', 'Kitchen Set Ekonomis Silver Anodize', 3600000, '0.8 mm', 'Lokal', '1 Tahun', 'Silver Anodize', 160, 50, 80, 28.0, 'assets/images/prod-kitchen-4.svg', 'assets/models/kitchen_set.glb', 'Solusi kitchen set hemat biaya dengan bahan aluminium lokal 0.8mm silver anodize yang tetap anti rayap dan tahan air.', 0, 0),
(5, 1, 'KS-ALX-05', 'Kitchen Set Alexindo Powder Coating Grey', 5900000, '1.2 mm', 'Alexindo', '3 Tahun', 'Powder Coating (Modern Grey)', 190, 60, 85, 40.0, 'assets/images/prod-kitchen-5.svg', 'assets/models/kitchen_set.glb', 'Kitchen set Alexindo 1.2mm berbalut powder coating abu-abu modern, anti minyak dan mudah dibersihkan.', 1, 14),
(6, 2, 'LM-ALX-01', 'Lemari 3 Pintu Alexindo Wood Texture Full Cermin', 4800000, '1.2 mm', 'Alexindo', '3 Tahun', 'Anodized Wood Grain (Urat Kayu)', 150, 55, 190, 48.0, 'assets/images/prod-lemari-1.svg', 'assets/models/wardrobe.glb', 'Lemari pakaian 3 pintu sliding kaca cermin bevel dengan rangka aluminium kokoh motif kayu jati, rel bawah hettich anti macet.', 1, 10),
(7, 2, 'LM-YKK-02', 'Lemari Wardrobe YKK Luxury 4 Pintu Sliding', 7500000, '1.5 mm', 'YKK', '5 Tahun', 'Powder Coating (Dark Titanium)', 200, 60, 210, 68.0, 'assets/images/prod-lemari-2.svg', 'assets/models/wardrobe.glb', 'Wardrobe sultan dengan aluminium premium YKK 1.5mm, peredam getaran pada pintu, laci aksesoris bersekat bludru.', 1, 18),
(8, 2, 'LM-DAC-03', 'Lemari 3 Pintu Dacon Powder Coating Putih', 3800000, '1.0 mm', 'Dacon', '2 Tahun', 'Powder Coating (White Pearl)', 140, 50, 185, 38.0, 'assets/images/prod-lemari-3.svg', 'assets/models/wardrobe.glb', 'Lemari pakaian aluminium minimalis modern warna putih mutiara, bebas lembab dan rayap untuk kamar tidur tropis.', 1, 7),
(9, 2, 'LM-LOK-04', 'Lemari Pakaian 2 Pintu Silver Basic', 2400000, '0.8 mm', 'Lokal', '1 Tahun', 'Silver Anodize', 100, 48, 180, 24.0, 'assets/images/prod-lemari-4.svg', 'assets/models/wardrobe.glb', 'Lemari pakaian 2 pintu aluminium lokal berkualitas, dilengkapi gantungan baju pipa oval tebal dan rak baju susun.', 0, 0),
(10, 3, 'RK-ALX-01', 'Rak Piring Wastafel Alexindo Deluxe Wood Series', 2800000, '1.2 mm', 'Alexindo', '3 Tahun', 'Anodized Wood Grain (Urat Kayu)', 120, 45, 175, 26.0, 'assets/images/prod-rak-1.svg', 'assets/models/dish_rack.glb', 'Rak piring 3 pintu atas dan bawah dengan bak wastafel cuci piring terintegrasi, finishing serat kayu mewah.', 1, 7),
(11, 3, 'RK-DAC-02', 'Rak Piring Dacon Modern Coating Hitam', 1950000, '1.0 mm', 'Dacon', '2 Tahun', 'Powder Coating (Black Matte)', 100, 42, 165, 21.0, 'assets/images/prod-rak-2.svg', 'assets/models/dish_rack.glb', 'Rak piring aluminium dacon powder coating hitam industrial, sekat keramik tahan gores dan saluran tirisan air.', 0, 0),
(12, 3, 'RK-LOK-03', 'Rak Piring Kompak Ekonomis 3 Pintu Silver', 1350000, '0.8 mm', 'Lokal', '1 Tahun', 'Silver Anodize', 90, 40, 160, 16.0, 'assets/images/prod-rak-3.svg', 'assets/models/dish_rack.glb', 'Rak piring aluminium lokal ekonomis 3 pintu kaca motif bunga/riben, awet dan higienis.', 0, 0),
(13, 4, 'BP-ALX-01', 'Bupet TV Alexindo Modern Floating Wood Design', 3400000, '1.2 mm', 'Alexindo', '3 Tahun', 'Anodized Wood Grain (Urat Kayu)', 180, 45, 60, 29.0, 'assets/images/prod-bupet-1.svg', 'assets/models/tv_stand.glb', 'Meja bupet TV aluminium modern motif kayu dengan laci penyimpanan kabel tersembunyi dan rak soundbar.', 1, 10),
(14, 4, 'BP-YKK-02', 'Lemari Hias Display YKK Glass Corner', 5200000, '1.5 mm', 'YKK', '5 Tahun', 'Powder Coating (Black Matte)', 100, 45, 195, 45.0, 'assets/images/prod-bupet-2.svg', 'assets/models/tv_stand.glb', 'Lemari pajangan hias sudut aluminium YKK dengan lampu LED spot warm white dan kaca tempered 8mm.', 1, 14),
(15, 5, 'PJ-ALX-01', 'Pintu Geser (Sliding Door) Alexindo Kaca Tempered', 2900000, '1.2 mm', 'Alexindo', '3 Tahun', 'Powder Coating (Black Matte)', 90, 8, 215, 22.0, 'assets/images/prod-pintu-1.svg', 'assets/models/sliding_door.glb', 'Pintu geser aluminium minimalis profil Alexindo dengan kaca tempered 5mm dan kunci sliding raven.', 1, 7),
(16, 5, 'PJ-YKK-02', 'Pintu Utama Swing Double YKK Heavy Profile', 5600000, '1.5 mm', 'YKK', '5 Tahun', 'Anodized Wood Grain (Urat Kayu)', 160, 10, 220, 46.0, 'assets/images/prod-pintu-2.svg', 'assets/models/sliding_door.glb', 'Pintu utama dua daun aluminium YKK kokoh motif urat kayu oak, tahan perubahan cuaca dan rayap.', 1, 12),
(17, 6, 'JM-ALX-01', 'Jemuran Baju Jumbo Alexindo 9 Palang Pipa Besar', 1450000, '1.2 mm', 'Alexindo', '3 Tahun', 'Powder Coating (Silver Metallic)', 180, 85, 150, 12.0, 'assets/images/prod-jemuran-1.svg', 'assets/models/drying_rack.glb', 'Jemuran lipat aluminium jumbo 9 palang pipa aluminium tebal Alexindo 1.2mm, mampu menahan beban sprei dan selimut basah tanpa goyang.', 0, 0),
(18, 6, 'JM-DAC-02', 'Jemuran Lipat Dacon Standar 7 Palang', 980000, '1.0 mm', 'Dacon', '2 Tahun', 'Silver Anodize', 150, 80, 140, 9.5, 'assets/images/prod-jemuran-2.svg', 'assets/models/drying_rack.glb', 'Jemuran pakaian 7 palang bahan Dacon 1.0mm dengan roda pengunci, ringan dipindahkan dan awet.', 0, 0);

-- ----------------------------------------------------------------------------
-- 5. Table structure for nilai_alternatif
-- ----------------------------------------------------------------------------
CREATE TABLE `nilai_alternatif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produk_id` int(11) NOT NULL,
  `kriteria_id` int(11) NOT NULL,
  `nilai` float NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_prod_crit` (`produk_id`,`kriteria_id`),
  KEY `kriteria_id` (`kriteria_id`),
  CONSTRAINT `fk_nilai_kriteria` FOREIGN KEY (`kriteria_id`) REFERENCES `kriteria` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_nilai_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk_alternatif` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `nilai_alternatif` (`produk_id`, `kriteria_id`, `nilai`) VALUES
(1, 1, 6500000), (1, 2, 3), (1, 3, 3), (1, 4, 3), (1, 5, 3),
(2, 1, 8800000), (2, 2, 4), (2, 3, 4), (2, 4, 4), (2, 5, 2),
(3, 1, 4900000), (3, 2, 2), (3, 3, 2), (3, 4, 2), (3, 5, 2),
(4, 1, 3600000), (4, 2, 1), (4, 3, 1), (4, 4, 1), (4, 5, 1),
(5, 1, 5900000), (5, 2, 3), (5, 3, 3), (5, 4, 3), (5, 5, 2),
(6, 1, 4800000), (6, 2, 3), (6, 3, 3), (6, 4, 3), (6, 5, 3),
(7, 1, 7500000), (7, 2, 4), (7, 3, 4), (7, 4, 4), (7, 5, 2),
(8, 1, 3800000), (8, 2, 2), (8, 3, 2), (8, 4, 2), (8, 5, 2),
(9, 1, 2400000), (9, 2, 1), (9, 3, 1), (9, 4, 1), (9, 5, 1),
(10, 1, 2800000), (10, 2, 3), (10, 3, 3), (10, 4, 3), (10, 5, 3),
(11, 1, 1950000), (11, 2, 2), (11, 3, 2), (11, 4, 2), (11, 5, 2),
(12, 1, 1350000), (12, 2, 1), (12, 3, 1), (12, 4, 1), (12, 5, 1),
(13, 1, 3400000), (13, 2, 3), (13, 3, 3), (13, 4, 3), (13, 5, 3),
(14, 1, 5200000), (14, 2, 4), (14, 3, 4), (14, 4, 4), (14, 5, 2),
(15, 1, 2900000), (15, 2, 3), (15, 3, 3), (15, 4, 3), (15, 5, 2),
(16, 1, 5600000), (16, 2, 4), (16, 3, 4), (16, 4, 4), (16, 5, 3),
(17, 1, 1450000), (17, 2, 3), (17, 3, 3), (17, 4, 3), (17, 5, 2),
(18, 1, 980000), (18, 2, 2), (18, 3, 2), (18, 4, 2), (18, 5, 1);

-- ----------------------------------------------------------------------------
-- 6. Table structure for orders, order_items, payments
-- ----------------------------------------------------------------------------
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_code` varchar(50) NOT NULL UNIQUE,
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_phone` varchar(50) NOT NULL,
  `customer_address` text NOT NULL,
  `city_destination` varchar(100) NOT NULL,
  `order_type` varchar(50) NOT NULL DEFAULT 'Pre-Order (PO)',
  `payment_scheme` varchar(50) NOT NULL DEFAULT 'Down Payment (DP 50%)',
  `courier_name` varchar(100) NOT NULL,
  `courier_service` varchar(100) NOT NULL,
  `actual_weight_kg` float NOT NULL,
  `volumetric_weight_kg` float NOT NULL,
  `charged_weight_kg` float NOT NULL,
  `shipping_cost` double NOT NULL,
  `product_subtotal` double NOT NULL,
  `total_amount` double NOT NULL,
  `dp_amount` double NOT NULL,
  `remaining_amount` double NOT NULL,
  `payment_status` varchar(50) NOT NULL DEFAULT 'Menunggu DP',
  `order_status` varchar(50) NOT NULL DEFAULT 'Pending',
  `tracking_number` varchar(100) DEFAULT NULL,
  `snap_token` varchar(100) DEFAULT NULL,
  `custom_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `fk_order_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `produk_id` int(11) DEFAULT NULL,
  `nama_produk` varchar(200) NOT NULL,
  `harga_satuan` double NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `subtotal` double NOT NULL,
  `custom_notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `produk_id` (`produk_id`),
  CONSTRAINT `fk_item_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_item_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk_alternatif` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `transaction_code` varchar(100) NOT NULL,
  `transaction_type` varchar(20) NOT NULL,
  `gross_amount` double NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_gateway_status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `fk_payment_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `search_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_id` int(11) DEFAULT NULL,
  `min_budget` double DEFAULT NULL,
  `max_budget` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

