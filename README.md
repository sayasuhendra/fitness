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

Jalankan worker queue di server:

```bash
php artisan queue:work --tries=3 --timeout=90
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
8. Simpan JSON service account sebagai satu baris env di server.

Contoh env backend:

```env
FIREBASE_PUSH_ENABLED=true
FIREBASE_PROJECT_ID=akhwat-gym
FIREBASE_SERVICE_ACCOUNT_JSON='{"type":"service_account","project_id":"akhwat-gym","private_key_id":"...","private_key":"-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n","client_email":"firebase-adminsdk@akhwat-gym.iam.gserviceaccount.com","token_uri":"https://oauth2.googleapis.com/token"}'
```

Setelah env berubah:

```bash
php artisan config:clear
php artisan optimize
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
