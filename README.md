# Akhwat Gym Backend

Backend Laravel untuk aplikasi member, admin panel Filament, pembayaran manual QRIS/transfer bank, jadwal kelas, personal trainer, toko, absensi, dan notifikasi.

## Setup Lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

Demo login hasil seeder:

- Owner: `owner@akhwatgym.test` / `password123`
- Super admin: `admin@akhwatgym.test` / `password123`
- Admin lokasi: `admin.lokasi@akhwatgym.test` / `password123`
- Member: `member@akhwatgym.test` / `password123`
- Trainer: `trainer@akhwatgym.test` / `password123`

## Konfigurasi Penting

```env
APP_NAME="Akhwat Gym"
APP_URL=https://fitness.dbaik.com
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public
AKHWAT_GYM_WHATSAPP_NUMBER=6285794132886
```

## Queue dan Scheduler Production

Script deploy `fitness-setup.sh` akan membuat dan menjalankan service berikut secara otomatis:

- `fitness-queue.service`
- `fitness-scheduler.service`
- `fitness-scheduler.timer`

Jalankan deploy:

```bash
cd /var/www/fitness
sudo bash fitness-setup.sh
```

Cek status:

```bash
sudo systemctl status fitness-queue
sudo systemctl status fitness-scheduler.timer
sudo journalctl -u fitness-queue -f
```

Kalau ingin menjalankan manual tanpa systemd:

```bash
php artisan queue:work --tries=3 --timeout=90
php artisan schedule:run
```

## Firebase Push Notification

Mobile app mengirim FCM token ke endpoint:

```http
POST /api/v1/notifications/fcm-token
```

Backend menyimpan token di tabel `device_tokens`, membuat inbox notifikasi di tabel `notifications`, dan akan mengirim push Firebase jika credential server sudah diaktifkan.

Langkah setup Firebase:

1. Buat project di Firebase Console.
2. Tambahkan aplikasi Android dan iOS untuk mobile app.
3. Download `google-services.json` ke `fitness-mobile/android/app/google-services.json`.
4. Download `GoogleService-Info.plist` ke `fitness-mobile/ios/Runner/GoogleService-Info.plist`.
5. Buka Google Cloud Console untuk project Firebase tersebut.
6. Buat service account dengan akses Firebase Cloud Messaging.
7. Generate private key JSON.
8. Simpan JSON service account di server sebagai file yang hanya bisa dibaca user web.

Contoh setup file service account di server:

```bash
sudo mkdir -p /etc/akhwat-gym
sudo nano /etc/akhwat-gym/firebase-service-account.json
sudo chown root:www-data /etc/akhwat-gym/firebase-service-account.json
sudo chmod 640 /etc/akhwat-gym/firebase-service-account.json
```

Env backend yang direkomendasikan:

```env
FIREBASE_PUSH_ENABLED=true
FIREBASE_PROJECT_ID=toko-online-9e33c
FIREBASE_SERVICE_ACCOUNT_FILE=/etc/akhwat-gym/firebase-service-account.json
```

Validasi server config:

```bash
php artisan config:clear
php artisan firebase:check
```

Alternatif jika tetap ingin memakai JSON satu baris di `.env`:

```env
FIREBASE_PUSH_ENABLED=true
FIREBASE_PROJECT_ID=toko-online-9e33c
FIREBASE_SERVICE_ACCOUNT_JSON='{"type":"service_account","project_id":"toko-online-9e33c","private_key_id":"...","private_key":"-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n","client_email":"firebase-adminsdk@toko-online-9e33c.iam.gserviceaccount.com","token_uri":"https://oauth2.googleapis.com/token"}'
```

Setelah env berubah:

```bash
php artisan config:clear
php artisan firebase:check
php artisan config:cache
```

## Deploy Production

```bash
sudo bash fitness-setup.sh
```

## Pembayaran Manual

Owner/admin mengelola rekening bank dan QRIS dari admin panel. Member memilih QRIS atau transfer, upload bukti pembayaran, lalu admin approve/reject dari menu konfirmasi pembayaran.

Status aktif setelah approval:

- Membership menjadi `active`.
- Order menjadi `paid`, lalu stok produk berkurang.
- Booking sekali datang menjadi `confirmed`.
- Sesi personal trainer sekali datang menjadi `scheduled`.

## Dokumentasi API

OpenAPI ada di:

```text
docs/openapi.yaml
```

## Verifikasi

```bash
./vendor/bin/pint
php artisan test
```
