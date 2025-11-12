# ✅ Checklist Deploy - Quick Reference

## Sebelum Upload ke Hosting

-   [ ] **Build Assets**

    ```bash
    npm run build
    ```

    Pastikan folder `public/build` ada dan berisi file

-   [ ] **Cek .env untuk Production**
    -   `APP_ENV=production`
    -   `APP_DEBUG=false`
    -   `APP_URL=https://yourdomain.com` (sesuai domain hosting)

## Setelah Upload ke Hosting

-   [ ] **Hapus File `public/hot`** ⚠️ PENTING!

    ```bash
    rm public/hot
    # atau di Windows: del public\hot
    ```

    File ini membuat aplikasi mencoba load dari dev server!

-   [ ] **Install Dependencies**

    ```bash
    composer install --no-dev --optimize-autoloader
    ```

-   [ ] **Generate App Key** (jika belum)

    ```bash
    php artisan key:generate
    ```

-   [ ] **Buat Storage Symlink**

    ```bash
    php artisan storage:link
    ```

-   [ ] **Set Permission** (Linux/Unix)

    ```bash
    chmod -R 755 storage
    chmod -R 755 bootstrap/cache
    ```

-   [ ] **Clear & Optimize Cache**

    ```bash
    php artisan config:clear
    php artisan cache:clear
    php artisan route:clear
    php artisan view:clear

    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```

-   [ ] **Run Migration** (jika perlu)
    ```bash
    php artisan migrate --force
    ```

## Verifikasi

-   [ ] Buka website di browser
-   [ ] Cek Console Browser (F12) - tidak ada error 404 untuk CSS/JS
-   [ ] Tampilan website sudah normal
-   [ ] Gambar/foto bisa di-load
-   [ ] Tidak ada error di halaman

## Troubleshooting Cepat

**Tampilan rusak?**

1. **Hapus file `public/hot` di hosting!** ⚠️ Ini penyebab utama!
2. Cek `public/build` folder ada di hosting
3. Cek `public/build/manifest.json` ada
4. Clear cache: `php artisan config:clear && php artisan view:clear`
5. Rebuild: `npm run build` lalu upload lagi folder `public/build`

**Gambar tidak muncul?**

1. Run: `php artisan storage:link`
2. Cek permission folder `storage/app/public`

**Error 500?**

1. Cek `storage/logs/laravel.log`
2. Pastikan `APP_DEBUG=false` di production
3. Pastikan permission folder benar
