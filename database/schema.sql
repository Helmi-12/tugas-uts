-- ============================================================================
-- DATABASE SCHEMA: SISTEM PENDUKUNG KEPUTUSAN MEBEL ALUMINIUM (METODE SAW)
-- DOKUMEN: BLUEPRINT & ANALISIS RANCANG BANGUN PERANGKAT LUNAK E-COMMERCE MEBEL
-- ============================================================================

CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(150) NOT NULL,
    email VARCHAR(150),
    phone VARCHAR(50),
    role VARCHAR(20) NOT NULL DEFAULT 'pelanggan', -- 'admin', 'pelanggan'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS kategori_mebel (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nama_kategori VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    foto VARCHAR(255),
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS kriteria (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    kode_kriteria VARCHAR(10) NOT NULL UNIQUE, -- C1, C2, C3, C4, C5
    nama_kriteria VARCHAR(150) NOT NULL,
    jenis VARCHAR(10) NOT NULL, -- 'benefit' atau 'cost'
    bobot REAL NOT NULL, -- 0.30, 0.25, 0.20, 0.15, 0.10 (Total = 1.00)
    keterangan_skala TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS produk_alternatif (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    kategori_id INTEGER NOT NULL,
    sku VARCHAR(50) NOT NULL UNIQUE,
    nama_produk VARCHAR(200) NOT NULL,
    harga REAL NOT NULL,
    ketebalan VARCHAR(50) NOT NULL,
    merk VARCHAR(100) NOT NULL,
    garansi VARCHAR(50) NOT NULL,
    finishing VARCHAR(100) NOT NULL,
    panjang_cm REAL NOT NULL,
    lebar_cm REAL NOT NULL,
    tinggi_cm REAL NOT NULL,
    berat_aktual_kg REAL NOT NULL,
    foto VARCHAR(255),
    model_glb_url VARCHAR(255),
    deskripsi_spesifikasi TEXT,
    is_po INTEGER NOT NULL DEFAULT 1, -- 1 = Pre-Order / Custom, 0 = Ready Stock
    lead_time_days INTEGER DEFAULT 14,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori_mebel(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS nilai_alternatif (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    produk_id INTEGER NOT NULL,
    kriteria_id INTEGER NOT NULL,
    nilai REAL NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(produk_id, kriteria_id),
    FOREIGN KEY (produk_id) REFERENCES produk_alternatif(id) ON DELETE CASCADE,
    FOREIGN KEY (kriteria_id) REFERENCES kriteria(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_code VARCHAR(50) NOT NULL UNIQUE,
    customer_id INTEGER,
    customer_name VARCHAR(150) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    customer_address TEXT NOT NULL,
    city_destination VARCHAR(100) NOT NULL,
    order_type VARCHAR(50) NOT NULL DEFAULT 'Pre-Order (PO)',
    payment_scheme VARCHAR(50) NOT NULL DEFAULT 'Down Payment (DP 50%)',
    courier_name VARCHAR(100) NOT NULL,
    courier_service VARCHAR(100) NOT NULL,
    actual_weight_kg REAL NOT NULL,
    volumetric_weight_kg REAL NOT NULL,
    charged_weight_kg REAL NOT NULL,
    shipping_cost REAL NOT NULL,
    product_subtotal REAL NOT NULL,
    total_amount REAL NOT NULL,
    dp_amount REAL NOT NULL,
    remaining_amount REAL NOT NULL,
    payment_status VARCHAR(50) NOT NULL DEFAULT 'Menunggu DP', -- 'Menunggu DP', 'DP Terbayar', 'Lunas', 'Batal'
    order_status VARCHAR(50) NOT NULL DEFAULT 'Pending', -- 'Pending', 'Dalam Proses Produksi', 'Quality Control', 'Siap Dikirim', 'Selesai'
    tracking_number VARCHAR(100),
    snap_token VARCHAR(100),
    custom_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS order_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    produk_id INTEGER,
    nama_produk VARCHAR(200) NOT NULL,
    harga_satuan REAL NOT NULL,
    jumlah INTEGER NOT NULL DEFAULT 1,
    subtotal REAL NOT NULL,
    custom_notes TEXT,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES produk_alternatif(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS payments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    transaction_code VARCHAR(100) NOT NULL,
    transaction_type VARCHAR(20) NOT NULL, -- 'DP', 'Settlement', 'Full'
    gross_amount REAL NOT NULL,
    payment_method VARCHAR(50),
    payment_gateway_status VARCHAR(50), -- 'settlement', 'pending', 'expire'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS search_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    kategori_id INTEGER,
    min_budget REAL,
    max_budget REAL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

