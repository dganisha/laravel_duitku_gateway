# Selamat datang di Website Integrasi Pembayaran Duitku

## Cara Install

- Clone dari git (jika ada)
- Jalankan 'composer install' jika belum ada folder 'vendor'
- Daftar terlebih dahulu di webiste duitku
- Jika telah terdaftar, pastikan sudah membuat 'Proyek' di dashboard Duitku dan memiliki Kode Merchant serta API Key Proyek.
- Lakukan konfigurasi database serta rubah 'APP_URL' di file '.env'
- Jalankan 'php artisan migrate' atau langsung import file database.
- Jika menggunakan migrate, jalankan 'php artisan db:seed' untuk mengisi data.
- Setelah selesai database di import jalankan 'php artisan serve'
- Website sudah dapat digunakan

### Catatan
- Pada table settings, value pada 3 module tersebut harus diganti sesuai dengan data proyek Anda. Untuk 'duitku_url', rubah url tersebut menjadi url production.
- Berikut url duitku untuk development dan juga production :
- Development : https://sandbox.duitku.com
- Production : https://passport.duitku.com

### Akses User
- email : admin@admin.com <br>
  password : admin