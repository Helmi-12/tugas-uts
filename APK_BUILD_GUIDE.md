# PANDUAN PEMBUATAN APLIKASI ANDROID (BUILD APK)
## Sistem Pendukung Keputusan Mebel Aluminium & Visual Commerce 3D

Aplikasi ini telah dirancang dengan standar **Progressive Web App (PWA)** lengkap dengan `manifest.json`, Service Worker (`sw.js`), ikon resolusi tinggi, dan antarmuka *Mobile Native App*. Anda dapat mengemasnya menjadi berkas **Android APK** dengan beberapa metode berikut.

---

## METODE 1: Instal Langsung dari Browser Android (Web APK / Frictionless)
Metode ini adalah cara tercepat dan tidak memerlukan instalasi Android Studio:
1. Pastikan server web dapat diakses dari jaringan HP Anda (misal `http://IP-KOMPUTER:8000` atau via ngrok / hosting).
2. Buka URL tersebut di browser **Google Chrome Android**.
3. Klik tombol menu titik tiga (⋮) di kanan atas, lalu pilih **"Tambahkan ke Layar Utama" (Add to Home screen)** atau klik banner pop-up **"Instal MebelAlum"**.
4. Sistem operasi Android akan otomatis membuatkan berkas **WebAPK** native dengan ikon di app drawer, splash screen pembuka, dan navigasi layar penuh tanpa bilah URL peramban.

---

## METODE 2: Build Standalone APK Menggunakan Bubblewrap (Google TWA CLI)

Google menyediakan alat CLI resmi bernama **Bubblewrap** untuk mengubah PWA menjadi berkas Android APK / AAB murni:

### 1. Prasyarat
- Node.js (sudah terinstal pada komputer Anda)
- Java Development Kit (JDK 17+)
- Android SDK Command-line Tools

### 2. Langkah Pembuatan APK
Jalankan perintah berikut di terminal:
```bash
# 1. Instal Bubblewrap CLI secara global
npm install -g @bubblewrap/cli

# 2. Inisialisasi proyek Android dari manifest
bubblewrap init --manifest=http://localhost:8000/manifest.json

# 3. Build berkas APK
bubblewrap build
```
Berkas `app-release-signed.apk` akan otomatis ter-generate di folder proyek dan siap di-instal di perangkat smartphone Android penguji/dosen.

---

## METODE 3: Build APK Menggunakan Capacitor (Native Shell)

Jika ingin membungkus aplikasi dengan runtime WebView native:
```bash
# 1. Inisialisasi Capacitor
npx @capacitor/cli create

# 2. Tambahkan platform Android
npx cap add android

# 3. Buka Android Studio dan Build APK
npx cap open android
```
Di Android Studio, pilih menu: **Build > Build Bundle(s) / APK(s) > Build APK(s)**.
Berkas `.apk` akan dihasilkan di: `android/app/build/outputs/apk/debug/app-debug.apk`.

