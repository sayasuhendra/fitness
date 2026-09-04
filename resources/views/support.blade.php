<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pusat Bantuan & Dukungan | Akhwat Gym</title>
    <meta name="description" content="Layanan bantuan, kontak resmi, dan FAQ aplikasi Akhwat Gym untuk pengguna dan member.">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <style>
        :root {
            color-scheme: light;
            --ink: #18181b;
            --muted: #52525b;
            --brand: #c026d3;
            --brand-hover: #a21caf;
            --brand-dark: #86198f;
            --soft: #fdf4ff;
            --line: #fae8ff;
            --line-neutral: #e4e4e7;
            --card-bg: rgba(255, 255, 255, .96);
            --success: #16a34a;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: linear-gradient(180deg, #ffffff 0%, #fdf4ff 100%);
            color: var(--ink);
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            line-height: 1.65;
        }

        main {
            width: min(960px, calc(100% - 32px));
            margin: 0 auto;
            padding: 40px 0 64px;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--line);
            border-radius: 24px;
            box-shadow: 0 24px 70px rgba(81, 24, 95, .10);
            padding: clamp(24px, 5vw, 52px);
        }

        .header-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--line);
        }

        .header-nav a {
            color: var(--brand-hover);
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            display: inline-block;
            padding: 11px 0;
        }

        .header-nav a:hover {
            text-decoration: underline;
        }

        a:focus-visible {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
            border-radius: 4px;
        }

        .eyebrow {
            color: var(--brand);
            font-size: 14px;
            font-weight: 800;
            letter-spacing: .08em;
            margin: 0 0 8px;
            text-transform: uppercase;
        }

        h1 {
            font-size: clamp(30px, 5vw, 44px);
            line-height: 1.15;
            margin: 0 0 14px;
            color: var(--ink);
        }

        .lead {
            font-size: 17px;
            color: var(--muted);
            margin: 0 0 32px;
        }

        h2 {
            font-size: 22px;
            margin: 36px 0 16px;
            color: var(--ink);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contacts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
            margin: 20px 0 32px;
        }

        .contact-box {
            background: var(--soft);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .contact-title {
            font-size: 13px;
            font-weight: 800;
            color: var(--brand-dark);
            text-transform: uppercase;
            letter-spacing: .05em;
            margin: 0 0 6px;
        }

        .contact-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--ink);
            margin: 0 0 12px;
            overflow-wrap: anywhere;
        }

        .contact-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            background: var(--brand);
            color: white;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            transition: background-color .15s;
        }

        .contact-btn:hover {
            background: var(--brand-hover);
        }

        .hours-box {
            background: #fafafa;
            border: 1px solid var(--line-neutral);
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 28px;
            font-size: 15px;
            color: var(--muted);
        }

        .hours-box strong {
            color: var(--ink);
        }

        .faq-item {
            border: 1px solid var(--line-neutral);
            border-radius: 14px;
            padding: 18px 22px;
            margin-bottom: 12px;
            background: #fff;
            transition: border-color .15s;
        }

        .faq-item:hover {
            border-color: #d4d4d8;
        }

        .faq-question {
            font-size: 17px;
            font-weight: 700;
            color: var(--ink);
            margin: 0 0 8px;
        }

        .faq-answer {
            font-size: 15px;
            color: var(--muted);
            margin: 0;
            line-height: 1.6;
        }

        .footer {
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid var(--line);
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: var(--muted);
        }

        .footer a {
            color: var(--brand-hover);
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            padding: 11px 0;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<main>
    <article class="card">
        <div class="header-nav">
            <a href="{{ url('/') }}">&larr; Kembali ke Beranda</a>
            <a href="{{ url('/privacy-policy') }}">Kebijakan Privasi</a>
        </div>

        <p class="eyebrow">Akhwat Gym Support Center</p>
        <h1>Pusat Bantuan & Layanan Dukungan</h1>
        <p class="lead">
            Selamat datang di layanan bantuan resmi Akhwat Gym. Kami siap membantu Anda jika memiliki pertanyaan,
            mengalami kendala teknis pada aplikasi, atau membutuhkan bantuan seputar membership dan kelas.
        </p>

        <h2>Saluran Kontak Resmi</h2>
        <div class="contacts-grid">
            <div class="contact-box">
                <div>
                    <div class="contact-title">WhatsApp / Kontak Bantuan</div>
                    <div class="contact-value">0857 9413 2886</div>
                </div>
                <a href="https://wa.me/6285794132886?text=Halo%20Admin%20Akhwat%20Gym,%20saya%20membutuhkan%20bantuan%20terkait%20aplikasi." target="_blank" rel="noopener noreferrer" class="contact-btn">
                    Hubungi via WhatsApp
                </a>
            </div>

            <div class="contact-box">
                <div>
                    <div class="contact-title">Email Layanan</div>
                    <div class="contact-value">akhwatgymcom@gmail.com</div>
                </div>
                <a href="mailto:akhwatgymcom@gmail.com?subject=Bantuan%20Aplikasi%20Akhwat%20Gym" class="contact-btn">
                    Kirim Email
                </a>
            </div>
        </div>

        <div class="hours-box">
            <strong>Jam Operasional Bantuan:</strong> Setiap Hari (Senin - Ahad), pukul <strong>07.00 - 20.00 WIB</strong>.<br>
            Pesan di luar jam operasional akan kami respon pada jam kerja berikutnya.
        </div>

        <h2>Pertanyaan yang Sering Diajukan (FAQ)</h2>

        <div class="faq-item">
            <div class="faq-question">1. Bagaimana cara mendaftar akun di aplikasi Akhwat Gym?</div>
            <p class="faq-answer">
                Buka aplikasi Akhwat Gym di perangkat Anda, pilih menu <strong>Daftar Sekarang</strong>, lalu masukkan Nama Lengkap,
                Alamat Email, dan Kata Sandi. Nomor WhatsApp/telepon bersifat <strong>opsional</strong> dan tidak wajib diisi untuk membuat akun.
            </p>
        </div>

        <div class="faq-item">
            <div class="faq-question">2. Mengapa nomor telepon/WhatsApp bersifat opsional?</div>
            <p class="faq-answer">
                Kami menghargai privasi member sesuai pedoman perlindungan data. Nomor telepon hanya digunakan bila Anda
                menginginkan konfirmasi transaksi atau notifikasi kelas via WhatsApp secara langsung dari tim admin.
            </p>
        </div>

        <div class="faq-item">
            <div class="faq-question">3. Bagaimana cara melakukan booking jadwal kelas?</div>
            <p class="faq-answer">
                Pastikan paket membership Anda aktif. Masuk ke tab <strong>Kelas</strong> di aplikasi, pilih jadwal dan instruktur yang diinginkan,
                lalu tekan tombol <strong>Booking Kelas</strong>. Konfirmasi booking akan langsung muncul di halaman aktivitas Anda.
            </p>
        </div>

        <div class="faq-item">
            <div class="faq-question">4. Bagaimana cara konfirmasi pembayaran manual atau QRIS?</div>
            <p class="faq-answer">
                Setelah melakukan transfer atau pemindaian QRIS, buka menu pembayaran pada aplikasi, unggah bukti pembayaran
                atau kirimkan konfirmasi kepada admin melalui tautan WhatsApp yang tersedia. Tim admin kami akan segera memverifikasi transaksi Anda.
            </p>
        </div>

        <div class="faq-item">
            <div class="faq-question">5. Bagaimana jika saya ingin mengganti foto profil atau mengubah data diri?</div>
            <p class="faq-answer">
                Masuk ke menu <strong>Profil Saya</strong> &rarr; pilih <strong>Ubah Profil</strong>. Anda dapat mengetuk ikon kamera pada foto
                untuk memilih foto baru dari galeri perangkat Anda, serta memperbarui nama atau nomor kontak Anda kapan saja.
            </p>
        </div>

        <div class="faq-item">
            <div class="faq-question">6. Bagaimana cara menghapus akun atau meminta penghapusan data?</div>
            <p class="faq-answer">
                Member dapat meminta penghapusan akun atau data pribadi sewaktu-waktu dengan mengirimkan permohonan melalui email ke
                <a href="mailto:akhwatgymcom@gmail.com" style="color: var(--brand-hover); font-weight: 700;">akhwatgymcom@gmail.com</a> atau melalui WhatsApp admin kami.
                Proses akan diselesaikan dalam kurun waktu 1x24 jam kerja.
            </p>
        </div>

        <div class="footer">
            <div>&copy; {{ now()->year }} Akhwat Gym. Hak Cipta Dilindungi.</div>
            <div>
                <a href="{{ url('/') }}">Beranda</a> &bull;
                <a href="{{ url('/support') }}">Pusat Bantuan</a> &bull;
                <a href="{{ url('/privacy-policy') }}">Kebijakan Privasi</a> &bull;
                <a href="{{ url('/admin') }}">Masuk Admin</a>
            </div>
        </div>
    </article>
</main>
</body>
</html>
