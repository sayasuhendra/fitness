<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Fitness Akhwat membantu owner dan admin mengelola member, jadwal kelas, kehadiran, toko, pembayaran, dan laporan operasional dengan lebih rapi.">

        <title>Fitness Akhwat</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white text-zinc-950 antialiased">
        <div class="min-h-screen">
            <header class="absolute inset-x-0 top-0 z-30">
                <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-5 sm:px-8" aria-label="Navigasi utama">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 text-white">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/15 ring-1 ring-white/30 backdrop-blur">
                            <span class="text-lg font-bold">FA</span>
                        </span>
                        <span class="text-sm font-semibold uppercase tracking-[0.18em]">Fitness Akhwat</span>
                    </a>

                    <div class="hidden items-center gap-8 text-sm font-medium text-white/85 md:flex">
                        <a href="#manfaat" class="transition hover:text-white">Manfaat</a>
                        <a href="#pengelolaan" class="transition hover:text-white">Pengelolaan</a>
                        <a href="#owner" class="transition hover:text-white">Untuk Owner</a>
                    </div>

                    <a href="{{ url('/admin') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-emerald-950 shadow-sm transition hover:bg-emerald-50">
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
                    <div class="absolute inset-0 bg-gradient-to-r from-zinc-950/92 via-emerald-950/70 to-zinc-950/20"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/84 via-transparent to-zinc-950/35"></div>

                    <div class="relative z-10 mx-auto flex min-h-screen max-w-7xl items-center px-5 pb-20 pt-32 sm:px-8">
                        <div class="max-w-3xl text-white">
                            <p class="mb-5 inline-flex rounded-lg border border-white/20 bg-white/10 px-3 py-1 text-sm font-medium text-emerald-50 backdrop-blur">
                                Ruang kerja yang lebih rapi untuk tim Fitness Akhwat
                            </p>
                            <h1 class="max-w-3xl text-5xl font-bold leading-tight sm:text-6xl lg:text-7xl">
                                Kelola studio dengan tenang, rapi, dan percaya diri.
                            </h1>
                            <p class="mt-6 max-w-2xl text-lg leading-8 text-zinc-100 sm:text-xl">
                                Satu tempat untuk membantu owner dan admin mengatur member, jadwal kelas, kehadiran, penjualan produk, pembayaran, dan ringkasan usaha tanpa ribet.
                            </p>

                            <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                                <a href="{{ url('/admin') }}" class="inline-flex items-center justify-center rounded-lg bg-emerald-400 px-6 py-3 text-sm font-bold text-emerald-950 shadow-lg shadow-emerald-950/30 transition hover:bg-emerald-300">
                                    Masuk ke Admin
                                </a>
                                <a href="#manfaat" class="inline-flex items-center justify-center rounded-lg border border-white/25 bg-white/10 px-6 py-3 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20">
                                    Lihat Manfaat
                                </a>
                            </div>

                            <div class="mt-12 grid max-w-3xl gap-3 sm:grid-cols-3">
                                <div class="rounded-lg border border-white/15 bg-white/10 p-4 backdrop-blur">
                                    <p class="text-sm font-semibold text-emerald-100">Operasional lebih rapi</p>
                                    <p class="mt-2 text-sm leading-6 text-white/80">Data penting tersimpan dalam alur yang mudah dicari.</p>
                                </div>
                                <div class="rounded-lg border border-white/15 bg-white/10 p-4 backdrop-blur">
                                    <p class="text-sm font-semibold text-emerald-100">Tim lebih mudah bekerja</p>
                                    <p class="mt-2 text-sm leading-6 text-white/80">Admin bisa melayani member dengan langkah yang jelas.</p>
                                </div>
                                <div class="rounded-lg border border-white/15 bg-white/10 p-4 backdrop-blur">
                                    <p class="text-sm font-semibold text-emerald-100">Owner lebih mudah memantau</p>
                                    <p class="mt-2 text-sm leading-6 text-white/80">Ringkasan usaha tersaji tanpa membuka data sensitif di halaman depan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="manfaat" class="bg-zinc-50 py-20">
                    <div class="mx-auto grid max-w-7xl gap-10 px-5 sm:px-8 lg:grid-cols-[0.95fr_1.05fr] lg:items-center">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-[0.18em] text-emerald-700">Manfaat untuk studio</p>
                            <h2 class="mt-4 text-3xl font-bold tracking-tight text-zinc-950 sm:text-4xl">
                                Aktivitas harian jadi lebih tertata dari awal sampai akhir.
                            </h2>
                            <p class="mt-5 text-base leading-8 text-zinc-600">
                                Fitness Akhwat membantu tim menjaga alur kerja tetap rapi, mulai dari member baru, pemilihan paket, jadwal kelas, kehadiran, hingga pembelian produk di studio.
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
                            <div class="rounded-lg border border-zinc-200 bg-emerald-950 p-6 text-white shadow-sm">
                                <p class="text-lg font-bold">Toko studio ikut terkelola</p>
                                <p class="mt-3 text-sm leading-6 text-emerald-50">Produk, stok, dan pesanan bisa dipantau dalam alur kerja yang sama.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="pengelolaan" class="bg-white py-20">
                    <div class="mx-auto max-w-7xl px-5 sm:px-8">
                        <div class="max-w-3xl">
                            <p class="text-sm font-bold uppercase tracking-[0.18em] text-emerald-700">Yang bisa dikelola</p>
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
                                    <div class="mb-5 h-1.5 w-12 rounded-full bg-emerald-500"></div>
                                    <h3 class="text-lg font-bold text-zinc-950">{{ $feature['title'] }}</h3>
                                    <p class="mt-3 text-sm leading-6 text-zinc-600">{{ $feature['copy'] }}</p>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </section>

                <section id="owner" class="bg-emerald-950 py-20 text-white">
                    <div class="mx-auto grid max-w-7xl gap-10 px-5 sm:px-8 lg:grid-cols-[1fr_1.1fr] lg:items-center">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-[0.18em] text-emerald-200">Untuk owner dan admin</p>
                            <h2 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl">
                                Halaman depan tetap aman. Data penting hanya dibuka dari ruang admin.
                            </h2>
                            <p class="mt-5 text-base leading-8 text-emerald-50">
                                Landing page ini hanya menjadi pintu masuk dan pengenalan sistem. Angka bisnis, data member, riwayat pembayaran, dan laporan internal tetap berada di halaman admin yang membutuhkan akses masuk.
                            </p>
                            <a href="{{ url('/admin') }}" class="mt-8 inline-flex rounded-lg bg-white px-5 py-3 text-sm font-bold text-emerald-950 transition hover:bg-emerald-50">
                                Buka Ruang Admin
                            </a>
                        </div>

                        <div class="rounded-lg border border-white/10 bg-white/10 p-6 shadow-2xl backdrop-blur">
                            <p class="text-sm font-semibold text-emerald-100">Fokus halaman admin</p>
                            <div class="mt-5 space-y-3">
                                @foreach ([
                                    'Membantu admin bekerja lebih cepat dan terarah',
                                    'Membantu owner melihat kondisi usaha dari tempat yang aman',
                                    'Menjaga data penting tetap berada di area yang terlindungi',
                                    'Membuat pengalaman member terasa lebih profesional',
                                ] as $item)
                                    <div class="flex gap-3 rounded-lg bg-white/10 p-4">
                                        <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-300 text-xs font-bold text-emerald-950">✓</span>
                                        <p class="text-sm leading-6 text-emerald-50">{{ $item }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                <section class="bg-zinc-50 py-16">
                    <div class="mx-auto max-w-7xl px-5 sm:px-8">
                        <div class="rounded-lg border border-zinc-200 bg-white p-8 shadow-sm">
                            <p class="text-sm font-bold uppercase tracking-[0.18em] text-emerald-700">Siap digunakan tim</p>
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
            </main>

            <footer class="bg-white py-8">
                <div class="mx-auto flex max-w-7xl flex-col gap-3 px-5 text-sm text-zinc-500 sm:flex-row sm:items-center sm:justify-between sm:px-8">
                    <p>&copy; {{ now()->year }} Fitness Akhwat. Sistem pendukung operasional studio yang rapi dan aman.</p>
                    <a href="{{ url('/admin') }}" class="font-semibold text-emerald-700 hover:text-emerald-800">Masuk Admin</a>
                </div>
            </footer>
        </div>
    </body>
</html>
