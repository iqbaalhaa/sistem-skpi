# Setup Ngrok untuk Sistem SKPI

## Masalah CSS Tidak Terbaca dengan Ngrok

Masalah ini terjadi karena Laravel menggunakan `APP_URL` untuk generate asset URLs, dan saat menggunakan ngrok, URL berubah menjadi URL ngrok yang berbeda.

## Solusi 1: Menggunakan ASSET_URL (Recommended)

1. **Edit file `.env`** dan tambahkan konfigurasi berikut:

```env
APP_URL=http://localhost
ASSET_URL=
```

2. **Saat menjalankan ngrok**, set ASSET_URL ke URL ngrok Anda:

```bash
# Contoh: jika ngrok URL adalah https://abc123.ngrok.io
ASSET_URL=https://abc123.ngrok.io
```

## Solusi 2: Menggunakan APP_URL (Alternatif)

1. **Edit file `.env`** dan ubah APP_URL ke URL ngrok:

```env
APP_URL=https://abc123.ngrok.io
```

## Solusi 3: Menggunakan Asset Helper (Paling Fleksibel)

Modifikasi file `config/app.php` untuk mendeteksi ngrok secara otomatis:

```php
'url' => env('APP_URL', request()->getSchemeAndHttpHost()),
```

## Langkah-langkah Implementasi

### Opsi A: Manual (Setiap kali ngrok berubah)

1. Jalankan ngrok:

```bash
ngrok http 8000
```

2. Copy URL ngrok (contoh: https://abc123.ngrok.io)

3. Edit file `.env`:

```env
APP_URL=https://abc123.ngrok.io
```

4. Clear cache Laravel:

```bash
php artisan config:clear
php artisan cache:clear
```

### Opsi B: Otomatis (Recommended)

1. Edit file `config/app.php` pada baris 58:

```php
'url' => env('APP_URL', request()->getSchemeAndHttpHost()),
```

2. Edit file `.env`:

```env
APP_URL=
```

3. Jalankan ngrok tanpa perlu mengubah konfigurasi lagi.

## Testing

1. Jalankan Laravel:

```bash
php artisan serve --port=8000
```

2. Jalankan ngrok:

```bash
ngrok http 8000
```

3. Akses URL ngrok dan pastikan CSS terbaca dengan benar.

## Catatan Penting

-   Pastikan folder `public` dapat diakses melalui web server
-   Asset CSS berada di `public/backend/assets/compiled/css/`
-   Jika masih bermasalah, cek browser developer tools untuk melihat error 404 pada asset files
