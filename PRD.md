PRD.md

Akhwat Gym App - MVP Fase 1

Product Vision

Membangun platform fitness khusus akhwat yang memungkinkan member melakukan pendaftaran, pembelian membership, booking kelas, check-in, serta pembelian produk kesehatan melalui aplikasi mobile Android dan iOS.

⸻

Goals

Business Goals

- Meningkatkan jumlah member aktif.
- Mengurangi pekerjaan administrasi manual.
- Meningkatkan penjualan membership.
- Menambah revenue dari penjualan makanan, minuman, dan suplemen.
- Menyediakan pengalaman digital yang profesional.

User Goals

- Mudah mendaftar menjadi member.
- Mudah membeli membership.
- Mudah melihat jadwal kelas.
- Mudah melakukan booking kelas.
- Mudah membeli produk.

⸻

User Roles

Member

Hak akses:

- Registrasi
- Login
- Kelola profil
- Beli membership
- Booking kelas
- Melihat transaksi
- Melihat riwayat kehadiran
- Membeli produk

Trainer

Hak akses:

- Melihat jadwal mengajar
- Melihat peserta kelas

Admin

Hak akses:

- Mengelola member
- Mengelola trainer
- Mengelola kelas
- Mengelola produk
- Mengelola transaksi
- Melihat laporan

⸻

Core Features

Authentication

Member Registration

Fields:

- Full Name
- Email
- Phone Number
- Password

Acceptance Criteria:

- User dapat membuat akun.
- Email harus unik.
- Password minimal 8 karakter.

⸻

Membership

Features:

- List Membership Package
- Purchase Membership
- Active Membership Status
- Membership History

Acceptance Criteria:

- Member dapat membeli membership.
- Membership otomatis aktif setelah pembayaran sukses.

⸻

Class Booking

Features:

- Class Schedule
- Trainer Information
- Capacity Information
- Book Class
- Cancel Booking

Acceptance Criteria:

- Tidak boleh melebihi kapasitas kelas.
- Member hanya dapat booking jika membership aktif.

⸻

QR Check-In

Features:

- QR Member Card
- Attendance History

Acceptance Criteria:

- Check-in tercatat otomatis.
- Riwayat kehadiran dapat dilihat member.

⸻

Store

Features:

- Product Listing
- Product Categories
- Product Detail
- Cart
- Checkout

Product Categories:

- Healthy Food
- Healthy Drink
- Supplements

Acceptance Criteria:

- Member dapat membeli produk.
- Stok berkurang otomatis setelah transaksi berhasil.

⸻

Payment

Features:

- QRIS
- Bank Transfer
- Payment Gateway

Acceptance Criteria:

- Status pembayaran otomatis terupdate.

⸻

Notification

Features:

- Booking Confirmation
- Payment Confirmation
- Membership Expiration Reminder

⸻

Non Functional Requirements

Performance

- Response time < 2 detik
- API response < 500 ms

Security

- JWT Authentication
- HTTPS
- Password Hashing

Availability

- Uptime target 99%

⸻

Technology Stack

Frontend:

- Flutter

Backend:

- Laravel API

Admin:

- Filament

Database:

- PostgreSQL

Notification:

- Firebase Cloud Messaging

Payment:

- Manual Bank Transfer/QRIS, with future GoPay partner research

⸻

Success Metrics

- 100+ member aktif bulan pertama.
- 80% booking dilakukan melalui aplikasi.
- Pengurangan pekerjaan admin minimal 50%.
