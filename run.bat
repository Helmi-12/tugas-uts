@echo off
chcp 65001 >nul
title MebelAlum - SPK SAW dan Visual Commerce 3D
cls

echo ============================================================================
echo   SISTEM PENDUKUNG KEPUTUSAN PEMILIHAN PRODUK MEBEL ALUMINIUM
echo   BERDASARKAN ANGGARAN DAN SPESIFIKASI MENGGUNAKAN METODE SAW
echo   -------------------------------------------------------------------------
echo   Dokumen: Software Blueprint dan Analisis Rancang Bangun
echo   Dosen Pembimbing / Penguji: Pak Rofi
echo ============================================================================
echo.

set "PHP_BIN="

:: 1. Periksa Laragon PHP 8.3
if exist "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe" (
    set "PHP_BIN=C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe"
    goto :start_server
)

:: 2. Periksa Laragon PHP 8.1
if exist "C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" (
    set "PHP_BIN=C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe"
    goto :start_server
)

:: 3. Periksa Laragon versi lain (wildcard search)
for /d %%D in ("C:\laragon\bin\php\php-*") do (
    if exist "%%D\php.exe" (
        set "PHP_BIN=%%D\php.exe"
        goto :start_server
    )
)

:: 4. Periksa XAMPP PHP
if exist "C:\xampp\php\php.exe" (
    set "PHP_BIN=C:\xampp\php\php.exe"
    goto :start_server
)

:: 5. Periksa Herd-Lite PHP
if exist "%USERPROFILE%\.config\herd-lite\bin\php.exe" (
    set "PHP_BIN=%USERPROFILE%\.config\herd-lite\bin\php.exe"
    goto :start_server
)

:: 6. Periksa PHP di system PATH
where php >nul 2>nul
if %ERRORLEVEL% equ 0 (
    set "PHP_BIN=php"
    goto :start_server
)

:: Jika PHP sama sekali tidak ditemukan
echo [ERROR] PHP tidak ditemukan di sistem!
echo Pastikan Laragon atau XAMPP sudah terpasang.
echo.
pause
exit /b 1

:start_server
echo [OK] Menggunakan PHP dari:
echo      %PHP_BIN%
echo.

"%PHP_BIN%" -v | findstr /i "PHP"
echo.

echo [1/2] Membuka browser pada http://localhost:8000 ...
start http://localhost:8000
echo.
echo [2/2] Menjalankan server web lokal pada http://localhost:8000 ...
echo       -------------------------------------------------------------
echo       SERVER AKTIF! Tekan Ctrl+C untuk menghentikan server.
echo       -------------------------------------------------------------
echo.

"%PHP_BIN%" -S 0.0.0.0:8000
pause
