<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Akhwat Gym membantu owner dan admin mengelola member, jadwal kelas, kehadiran, toko, pembayaran, dan laporan operasional dengan lebih rapi.">

        <title>Akhwat Gym</title>
        <link rel="icon" href="{{ asset('favicon.ico') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white text-zinc-950 antialiased">
        <div class="min-h-screen">
            <header class="absolute inset-x-0 top-0 z-30">
                <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-5 sm:px-8" aria-label="Navigasi utama">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 text-white">
                        <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-white p-1.5 shadow-sm ring-1 ring-white/30">
                            <img src="{{ asset('images/brand/akhwat-gym-mark.png') }}" alt="Akhwat Gym" class="h-full w-full object-contain">
                        </span>
                        <span class="text-sm font-semibold uppercase tracking-[0.18em]">Akhwat Gym</span>
                    </a>

                    <div class="hidden items-center gap-8 text-sm font-medium text-white/85 md:flex">
                        <a href="#manfaat" class="transition hover:text-white">Manfaat</a>
                        <a href="#pengelolaan" class="transition hover:text-white">Pengelolaan</a>
                        <a href="#bantuan" class="transition hover:text-white">Bantuan & Kontak</a>
                        <a href="{{ url('/support') }}" class="transition hover:text-white">Pusat Bantuan</a>
                    </div>

                    <a href="{{ url('/admin') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-purple-950 shadow-sm transition hover:bg-fuchsia-50">
                        Masuk Admin
                    </a>
                </nav>
            </header>

            <main>
                <section class="relative min-h-screen overflow-hidden">
                    <img
                        src="{{ asset('images/landing/akhwat-studio.png') }}"
                        alt="Studio fitness khusus akhwat yang modern dan nyaman"
                        class="absolute inset-0 h-full w-full object-cover"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-zinc-950/92 via-purple-950/72 to-zinc-950/20"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/84 via-transparent to-zinc-950/35"></div>

                    <div class="relative z-10 mx-auto flex min-h-screen max-w-7xl items-center px-5 pb-20 pt-32 sm:px-8">
                        <div class="max-w-3xl text-white">
                            <p class="mb-5 inline-flex rounded-lg border border-white/20 bg-white/10 px-3 py-1 text-sm font-medium text-fuchsia-50 backdrop-blur">
                                Ruang kerja yang lebih rapi untuk tim Akhwat Gym
                            </p>
                            <h1 class="max-w-3xl text-5xl font-bold leading-tight sm:text-6xl lg:text-7xl">
                                Kelola studio dengan tenang, rapi, dan percaya diri.
                            </h1>
                            <p class="mt-6 max-w-2xl text-lg leading-8 text-zinc-100 sm:text-xl">
                                Satu tempat untuk membantu owner dan admin mengatur member, jadwal kelas, kehadiran, penjualan produk, pembayaran, dan ringkasan usaha tanpa ribet.
                            </p>

                            <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                                <a href="{{ url('/admin') }}" class="inline-flex items-center justify-center rounded-lg bg-fuchsia-400 px-6 py-3 text-sm font-bold text-purple-950 shadow-lg shadow-purple-950/30 transition hover:bg-fuchsia-300">
                                    Masuk ke Admin
                                </a>
                                <a href="#manfaat" class="inline-flex items-center justify-center rounded-lg border border-white/25 bg-white/10 px-6 py-3 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20">
                                    Lihat Manfaat
                                </a>
                            </div>

                            <div class="mt-12 grid max-w-3xl gap-3 sm:grid-cols-3">
                                <div class="rounded-lg border border-white/15 bg-white/10 p-4 backdrop-blur">
                                    <p class="text-sm font-semibold text-fuchsia-100">Operasional lebih rapi</p>
                                    <p class="mt-2 text-sm leading-6 text-white/80">Data penting tersimpan dalam alur yang mudah dicari.</p>
                                </div>
                                <div class="rounded-lg border border-white/15 bg-white/10 p-4 backdrop-blur">
                                    <p class="text-sm font-semibold text-fuchsia-100">Tim lebih mudah bekerja</p>
                                    <p class="mt-2 text-sm leading-6 text-white/80">Admin bisa melayani member dengan langkah yang jelas.</p>
                                </div>
                                <div class="rounded-lg border border-white/15 bg-white/10 p-4 backdrop-blur">
                                    <p class="text-sm font-semibold text-fuchsia-100">Owner lebih mudah memantau</p>
                                    <p class="mt-2 text-sm leading-6 text-white/80">Ringkasan usaha tersaji tanpa membuka data sensitif di halaman depan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="manfaat" class="bg-zinc-50 py-20">
                    <div class="mx-auto grid max-w-7xl gap-10 px-5 sm:px-8 lg:grid-cols-[0.95fr_1.05fr] lg:items-center">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-[0.18em] text-fuchsia-700">Manfaat untuk studio</p>
                            <h2 class="mt-4 text-3xl font-bold tracking-tight text-zinc-950 sm:text-4xl">
                                Aktivitas harian jadi lebih tertata dari awal sampai akhir.
                            </h2>
                            <p class="mt-5 text-base leading-8 text-zinc-600">
                                Akhwat Gym membantu tim menjaga alur kerja tetap rapi, mulai dari member baru, pemilihan paket, jadwal kelas, kehadiran, hingga pembelian produk di studio.
                            </p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
                                <p class="text-lg font-bold text-zinc-950">Member lebih mudah dilayani</p>
                                <p class="mt-3 text-sm leading-6 text-zinc-600">Informasi member, status paket, dan riwayat aktivitas bisa dikelola dengan lebih teratur.</p>
                            </div>
                            <div class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
                                <p class="text-lg font-bold text-zinc-950">Jadwal kelas lebih jelas</p>
                                <p class="mt-3 text-sm leading-6 text-zinc-600">Admin dapat menyiapkan kelas, trainer, kapasitas, dan lokasi dengan lebih mudah.</p>
                            </div>
                            <div class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
                                <p class="text-lg font-bold text-zinc-950">Kehadiran lebih tertib</p>
                                <p class="mt-3 text-sm leading-6 text-zinc-600">Proses check-in membantu tim mencatat kedatangan member dengan cepat dan rapi.</p>
                            </div>
                            <div class="rounded-lg border border-zinc-200 bg-purple-950 p-6 text-white shadow-sm">
                                <p class="text-lg font-bold">Toko studio ikut terkelola</p>
                                <p class="mt-3 text-sm leading-6 text-fuchsia-50">Produk, stok, dan pesanan bisa dipantau dalam alur kerja yang sama.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="pengelolaan" class="bg-white py-20">
                    <div class="mx-auto max-w-7xl px-5 sm:px-8">
                        <div class="max-w-3xl">
                            <p class="text-sm font-bold uppercase tracking-[0.18em] text-fuchsia-700">Yang bisa dikelola</p>
                            <h2 class="mt-4 text-3xl font-bold tracking-tight text-zinc-950 sm:text-4xl">
                                Semua hal penting untuk menjalankan studio ada dalam satu tempat.
                            </h2>
                        </div>

                        <div class="mt-10 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                            @foreach ([
                                ['title' => 'Member', 'copy' => 'Simpan data member, status paket, dan informasi penting agar pelayanan lebih personal.'],
                                ['title' => 'Paket Latihan', 'copy' => 'Atur pilihan paket yang ditawarkan kepada member sesuai kebutuhan studio.'],
                                ['title' => 'Kelas dan Trainer', 'copy' => 'Kelola jadwal, lokasi, kapasitas kelas, dan trainer yang bertugas.'],
                                ['title' => 'Kehadiran', 'copy' => 'Catat kehadiran member dengan alur check-in yang lebih praktis.'],
                                ['title' => 'Produk Studio', 'copy' => 'Kelola produk pendukung seperti makanan sehat, minuman, dan suplemen.'],
                                ['title' => 'Pembelian', 'copy' => 'Pantau pembelian paket dan pesanan produk dalam riwayat yang mudah dibaca.'],
                                ['title' => 'Pengingat', 'copy' => 'Bantu member mendapat informasi penting seputar booking, pembayaran, dan aktivitas studio.'],
                                ['title' => 'Ringkasan Usaha', 'copy' => 'Owner dapat melihat gambaran umum usaha dari halaman admin yang aman.'],
                            ] as $feature)
                                <article class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                                    <div class="mb-5 h-1.5 w-12 rounded-full bg-fuchsia-500"></div>
                                    <h3 class="text-lg font-bold text-zinc-950">{{ $feature['title'] }}</h3>
                                    <p class="mt-3 text-sm leading-6 text-zinc-600">{{ $feature['copy'] }}</p>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </section>

                <section id="owner" class="bg-purple-950 py-20 text-white">
                    <div class="mx-auto grid max-w-7xl gap-10 px-5 sm:px-8 lg:grid-cols-[1fr_1.1fr] lg:items-center">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-[0.18em] text-fuchsia-200">Untuk owner dan admin</p>
                            <h2 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl">
                                Halaman depan tetap aman. Data penting hanya dibuka dari ruang admin.
                            </h2>
                            <p class="mt-5 text-base leading-8 text-fuchsia-50">
                                Landing page ini hanya menjadi pintu masuk dan pengenalan sistem. Angka bisnis, data member, riwayat pembayaran, dan laporan internal tetap berada di halaman admin yang membutuhkan akses masuk.
                            </p>
                            <a href="{{ url('/admin') }}" class="mt-8 inline-flex rounded-lg bg-white px-5 py-3 text-sm font-bold text-purple-950 transition hover:bg-fuchsia-50">
                                Buka Ruang Admin
                            </a>
                        </div>

                        <div class="rounded-lg border border-white/10 bg-white/10 p-6 shadow-2xl backdrop-blur">
                            <p class="text-sm font-semibold text-fuchsia-100">Fokus halaman admin</p>
                            <div class="mt-5 space-y-3">
                                @foreach ([
                                    'Membantu admin bekerja lebih cepat dan terarah',
                                    'Membantu owner melihat kondisi usaha dari tempat yang aman',
                                    'Menjaga data penting tetap berada di area yang terlindungi',
                                    'Membuat pengalaman member terasa lebih profesional',
                                ] as $item)
                                    <div class="flex gap-3 rounded-lg bg-white/10 p-4">
                                        <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-fuchsia-300 text-xs font-bold text-purple-950">✓</span>
                                        <p class="text-sm leading-6 text-fuchsia-50">{{ $item }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                <section class="bg-zinc-50 py-16">
                    <div class="mx-auto max-w-7xl px-5 sm:px-8">
                        <div class="rounded-lg border border-zinc-200 bg-white p-8 shadow-sm">
                            <p class="text-sm font-bold uppercase tracking-[0.18em] text-fuchsia-700">Siap digunakan tim</p>
                            <div class="mt-5 grid gap-4 md:grid-cols-3">
                                <div>
                                    <p class="font-bold text-zinc-950">Untuk admin</p>
                                    <p class="mt-2 text-sm leading-6 text-zinc-600">Alur kerja harian menjadi lebih jelas, mulai dari member sampai transaksi.</p>
                                </div>
                                <div>
                                    <p class="font-bold text-zinc-950">Untuk owner</p>
                                    <p class="mt-2 text-sm leading-6 text-zinc-600">Kontrol usaha lebih mudah tanpa harus membuka banyak catatan terpisah.</p>
                                </div>
                                <div>
                                    <p class="font-bold text-zinc-950">Untuk member</p>
                                    <p class="mt-2 text-sm leading-6 text-zinc-600">Layanan terasa lebih rapi, cepat, dan nyaman dari awal bergabung.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="bantuan" class="border-t border-zinc-200 bg-white py-20">
                    <div class="mx-auto max-w-7xl px-5 sm:px-8">
                        <div class="max-w-3xl">
                            <p class="text-sm font-bold uppercase tracking-[0.18em] text-fuchsia-700">Layanan Bantuan & Dukungan</p>
                            <h2 class="mt-4 text-3xl font-bold tracking-tight text-zinc-950 sm:text-4xl">
                                Ada pertanyaan atau butuh bantuan terkait aplikasi?
                            </h2>
                            <p class="mt-4 text-base leading-7 text-zinc-600">
                                Tim kami siap membantu Anda seputar penggunaan aplikasi Akhwat Gym, paket membership, jadwal kelas, transaksi, maupun bantuan teknis lainnya.
                            </p>
                        </div>

                        <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            <div class="rounded-2xl border border-zinc-200 bg-fuchsia-50/40 p-6 shadow-sm">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-fuchsia-600 text-white shadow-md shadow-fuchsia-600/20">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-bold text-zinc-950">WhatsApp / Kontak Bantuan</h3>
                                <p class="mt-1 text-sm text-zinc-600">Respon cepat via chat WhatsApp admin kami.</p>
                                <p class="mt-3 text-lg font-bold text-fuchsia-950">0857 9413 2886</p>
                                <a href="https://wa.me/6285794132886?text=Halo%20Admin%20Akhwat%20Gym,%20saya%20butuh%20bantuan%20aplikasi." target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex items-center justify-center rounded-lg bg-fuchsia-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-fuchsia-700">
                                    Chat via WhatsApp &rarr;
                                </a>
                            </div>

                            <div class="rounded-2xl border border-zinc-200 bg-purple-50/40 p-6 shadow-sm">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-700 text-white shadow-md shadow-purple-700/20">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-bold text-zinc-950">Email Dukungan</h3>
                                <p class="mt-1 text-sm text-zinc-600">Pertanyaan umum dan permohonan bantuan.</p>
                                <p class="mt-3 text-lg font-bold text-purple-950">akhwatgymcom@gmail.com</p>
                                <a href="mailto:akhwatgymcom@gmail.com?subject=Bantuan%20Aplikasi%20Akhwat%20Gym" class="mt-4 inline-flex items-center justify-center rounded-lg bg-purple-900 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-purple-800">
                                    Kirim Email &rarr;
                                </a>
                            </div>

                            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-zinc-900 text-white shadow-md">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-bold text-zinc-950">Jam Layanan Bantuan</h3>
                                <p class="mt-1 text-sm text-zinc-600">Senin - Ahad (Setiap Hari)</p>
                                <p class="mt-3 text-base font-bold text-zinc-900">07.00 - 20.00 WIB</p>
                                <a href="{{ url('/support') }}" class="mt-4 inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-zinc-50 px-4 py-2.5 text-sm font-bold text-zinc-800 transition hover:bg-zinc-100">
                                    Buka Halaman Bantuan Lengkap &rarr;
                                </a>
                            </div>
                        </div>

                        <div class="mt-12 rounded-2xl border border-zinc-200 bg-zinc-50 p-6 sm:p-8">
                            <h3 class="text-xl font-bold text-zinc-950">Pertanyaan Umum (FAQ)</h3>
                            <div class="mt-6 grid gap-6 md:grid-cols-2">
                                <div>
                                    <p class="font-bold text-zinc-900">Apakah nomor WhatsApp wajib saat mendaftar?</p>
                                    <p class="mt-2 text-sm leading-6 text-zinc-600">Tidak. Pendaftaran hanya membutuhkan Nama, Email, dan Password. Nomor WhatsApp bersifat opsional.</p>
                                </div>
                                <div>
                                    <p class="font-bold text-zinc-900">Bagaimana jika ada kendala saat pembayaran?</p>
                                    <p class="mt-2 text-sm leading-6 text-zinc-600">Hubungi WhatsApp resmi kami dengan melampirkan bukti transfer agar langsung diverifikasi oleh admin.</p>
                                </div>
                                <div>
                                    <p class="font-bold text-zinc-900">Bagaimana cara mengganti foto profil?</p>
                                    <p class="mt-2 text-sm leading-6 text-zinc-600">Buka menu Profil Saya di aplikasi, pilih Ubah Profil, lalu ketuk ikon kamera untuk memilih foto dari perangkat Anda.</p>
                                </div>
                                <div>
                                    <p class="font-bold text-zinc-900">Bagaimana cara meminta penghapusan akun?</p>
                                    <p class="mt-2 text-sm leading-6 text-zinc-600">Kirim email ke akhwatgymcom@gmail.com atau hubungi admin via WhatsApp untuk memproses penghapusan akun dan data Anda.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            <footer class="border-t border-zinc-200 bg-white py-8">
                <div class="mx-auto flex max-w-7xl flex-col gap-4 px-5 text-sm text-zinc-500 sm:flex-row sm:items-center sm:justify-between sm:px-8">
                    <p>&copy; {{ now()->year }} Akhwat Gym. Sistem pendukung operasional studio yang rapi dan aman.</p>
                    <div class="flex flex-wrap items-center gap-6">
                        <a href="{{ url('/support') }}" class="font-semibold text-fuchsia-700 hover:text-fuchsia-800">Pusat Bantuan</a>
                        <a href="{{ url('/privacy-policy') }}" class="font-semibold text-fuchsia-700 hover:text-fuchsia-800">Kebijakan Privasi</a>
                        <a href="{{ url('/admin') }}" class="font-semibold text-zinc-700 hover:text-zinc-900">Masuk Admin</a>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>

