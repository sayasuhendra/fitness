<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Akhwat Gym - Studio fitness, gym alat, dan kelas olahraga khusus wanita/akhwat. Privasi terjaga, instruktur muslimah, booking kelas mudah lewat Web Member PWA dan Aplikasi Android Google Play.">
        <meta name="keywords" content="akhwat gym, gym wanita, fitness muslimah, zumba akhwat, poundfit, yoga muslimah, aerobic, gym khusus perempuan">
        <meta property="og:title" content="Akhwat Gym - Studio Fitness Khusus Wanita">
        <meta property="og:description" content="Olahraga aman, nyaman, dan syar'i khusus perempuan. Akses jadwal kelas, beli paket, dan check-in mudah lewat Web Member atau Google Play Store.">
        <meta property="og:image" content="{{ asset('images/brand/akhwat-gym-logo.png') }}">
        <meta property="og:type" content="website">

        <title>Akhwat Gym - Studio Fitness & Gym Khusus Wanita</title>
        <link rel="icon" href="{{ asset('favicon.ico') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white text-zinc-900 antialiased selection:bg-fuchsia-200 selection:text-fuchsia-900">
        <div class="min-h-screen flex flex-col">
            <!-- Top Alert Banner -->
            <aside aria-label="Pengumuman" class="bg-gradient-to-r from-purple-900 via-fuchsia-800 to-pink-700 px-4 py-2 text-center text-xs sm:text-sm font-medium text-white shadow-sm">
                <div class="mx-auto flex max-w-7xl items-center justify-center gap-2 flex-wrap">
                    <span class="inline-flex items-center rounded-full bg-white/20 px-2 py-0.5 text-xs font-semibold backdrop-blur">Kabar Gembira</span>
                    <span>Aplikasi Akhwat Gym kini tersedia di Google Play Store & Web Member PWA!</span>
                    <a href="#akses-aplikasi" class="underline font-bold hover:text-fuchsia-200 ml-1">Lihat Pilihan Akses &darr;</a>
                </div>
            </aside>

            <!-- Navigation Header -->
            <header class="sticky top-0 z-40 border-b border-zinc-100 bg-white/95 backdrop-blur-md transition-all">
                <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8" aria-label="Navigasi Utama">
                    <!-- Brand Logo -->
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-fuchsia-600 to-purple-800 p-1.5 shadow-md shadow-fuchsia-600/20 ring-1 ring-white/50 group-hover:scale-105 transition-transform">
                            <img src="{{ asset('images/brand/akhwat-gym-mark.png') }}" alt="Logo Akhwat Gym" class="h-full w-full object-contain">
                        </span>
                        <div class="flex flex-col">
                            <span class="text-base font-extrabold tracking-tight text-zinc-950 sm:text-lg">AKHWAT GYM</span>
                            <span class="text-[10px] font-semibold tracking-wider text-fuchsia-700 uppercase -mt-0.5">Khusus Wanita & Muslimah</span>
                        </div>
                    </a>

                    <!-- Desktop Nav Links -->
                    <div class="hidden items-center gap-7 text-sm font-semibold text-zinc-600 lg:flex">
                        <a href="#keunggulan" class="transition hover:text-fuchsia-700">Keunggulan</a>
                        <a href="#kelas" class="transition hover:text-fuchsia-700">Kelas Studio</a>
                        <a href="#paket" class="transition hover:text-fuchsia-700">Pilihan Paket</a>
                        <a href="#fitur-aplikasi" class="transition hover:text-fuchsia-700">Fitur Member</a>
                        <a href="#kontak" class="transition hover:text-fuchsia-700">Lokasi & Kontak</a>
                        <a href="{{ url('/support') }}" class="transition hover:text-fuchsia-700">Bantuan</a>
                    </div>

                    <!-- Header CTA Buttons -->
                    <div class="flex items-center gap-2 sm:gap-3">
                        <a href="{{ url('/admin') }}" class="hidden sm:inline-flex items-center text-xs font-semibold text-zinc-500 hover:text-zinc-800 px-2 py-1.5 rounded transition">
                            Admin Login
                        </a>
                        <a href="{{ url('/member') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-fuchsia-600 to-purple-700 px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-md shadow-fuchsia-600/25 transition hover:from-fuchsia-500 hover:to-purple-600 hover:shadow-lg hover:shadow-fuchsia-600/35 focus:ring-2 focus:ring-fuchsia-500 focus:ring-offset-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Buka Web Member</span>
                        </a>
                    </div>
                </nav>
            </header>

            <main class="flex-grow">
                <!-- HERO SECTION -->
                <section class="relative overflow-hidden bg-gradient-to-b from-purple-950 via-zinc-950 to-zinc-950 text-white">
                    <!-- Background Photo with Overlay -->
                    <img
                        src="{{ asset('images/landing/akhwat-studio.png') }}"
                        alt="Studio fitness dan gym khusus wanita yang privat dan berfasilitas lengkap"
                        class="absolute inset-0 h-full w-full object-cover opacity-25 filter blur-[1px]"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-zinc-950 via-purple-950/85 to-zinc-950/80"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-transparent to-purple-950/40"></div>

                    <div class="relative z-10 mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8 lg:py-28">
                        <div class="grid items-center gap-12 lg:grid-cols-12">
                            <!-- Left Hero Content -->
                            <div class="lg:col-span-7">
                                <div class="inline-flex items-center gap-2 rounded-full border border-fuchsia-400/30 bg-fuchsia-500/15 px-3.5 py-1.5 text-xs sm:text-sm font-medium text-fuchsia-200 backdrop-blur-md">
                                    <span class="flex h-2 w-2 rounded-full bg-fuchsia-400 animate-pulse"></span>
                                    <span>🌸 100% Khusus Wanita &bull; Nyaman, Aman, &amp; Syar'i</span>
                                </div>

                                <h1 class="mt-6 text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl text-white leading-tight">
                                    Olahraga Nyaman &amp; Terjaga di <span class="bg-gradient-to-r from-fuchsia-300 via-pink-300 to-purple-200 bg-clip-text text-transparent">Akhwat Gym</span>
                                </h1>

                                <p class="mt-5 text-base sm:text-lg leading-relaxed text-zinc-200 max-w-2xl">
                                    Studio kebugaran khusus perempuan dengan peralatan gym lengkap, kelas aerobik, zumba, poundfit, dan yoga bersama instruktur muslimah bersertifikat. Bebas dari pandangan non-mahram, nikmati latihan dengan tenang dan percaya diri.
                                </p>

                                <!-- Action Buttons: Web PWA & Google Play -->
                                <div class="mt-8 flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                                    <!-- Primary: Web PWA -->
                                    <a href="{{ url('/member') }}" class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-gradient-to-r from-fuchsia-500 via-fuchsia-600 to-purple-700 px-6 py-3.5 text-sm sm:text-base font-bold text-white shadow-xl shadow-fuchsia-600/30 transition hover:from-fuchsia-400 hover:to-purple-600 hover:scale-[1.02] focus:ring-2 focus:ring-fuchsia-400">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                        </svg>
                                        <span>Buka Web Member (PWA)</span>
                                    </a>

                                    <!-- Secondary: Google Play Store -->
                                    <a href="https://play.google.com/store/apps/details?id=com.akhwatgym.app" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-3 rounded-xl border border-white/20 bg-white/10 px-5 py-3 text-sm font-semibold text-white backdrop-blur-md transition hover:bg-white/20 hover:border-white/30">
                                        <!-- Google Play SVG Icon -->
                                        <svg class="h-5 w-5 fill-current text-white" viewBox="0 0 24 24">
                                            <path d="M3.609 1.814L13.792 12 3.61 22.186a2.03 2.03 0 0 1-.61-1.465V3.279c0-.573.228-1.096.609-1.465zm11.242 11.244l2.583 2.583-12.022 6.87 9.439-9.453zm2.583-2.583L5.412 3.491l12.022 6.87-2.583 2.583zm1.189 1.189l3.053-1.745a1.536 1.536 0 0 0 0-2.671l-3.053-1.745-1.927 1.927 1.927 1.934z"/>
                                        </svg>
                                        <div class="text-left leading-tight">
                                            <span class="block text-[10px] uppercase tracking-wider text-zinc-300">Tersedia di</span>
                                            <span class="text-xs sm:text-sm font-bold">Google Play Store</span>
                                        </div>
                                    </a>
                                </div>

                                <!-- Trust Value Badges -->
                                <div class="mt-10 grid grid-cols-2 sm:grid-cols-3 gap-3 border-t border-white/10 pt-8">
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-fuchsia-500/20 text-fuchsia-300 text-sm font-bold">✓</span>
                                        <span class="text-xs sm:text-sm font-medium text-zinc-200">Privasi 100% Bebas Pria</span>
                                    </div>
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-fuchsia-500/20 text-fuchsia-300 text-sm font-bold">✓</span>
                                        <span class="text-xs sm:text-sm font-medium text-zinc-200">Instruktur Berpengalaman</span>
                                    </div>
                                    <div class="flex items-center gap-2.5 col-span-2 sm:col-span-1">
                                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-fuchsia-500/20 text-fuchsia-300 text-sm font-bold">✓</span>
                                        <span class="text-xs sm:text-sm font-medium text-zinc-200">Presensi Cepat QR Code</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Hero Graphic: Phone Mockups -->
                            <div class="lg:col-span-5 flex justify-center">
                                <div class="relative w-full max-w-sm">
                                    <!-- Decorative Ambient Glow -->
                                    <div class="absolute -top-10 -left-10 h-64 w-64 rounded-full bg-fuchsia-600/30 blur-3xl filter pointer-events-none"></div>
                                    <div class="absolute -bottom-10 -right-10 h-64 w-64 rounded-full bg-purple-600/30 blur-3xl filter pointer-events-none"></div>

                                    <!-- Main Phone Frame Mockup -->
                                    <div class="relative mx-auto rounded-[36px] border-4 border-zinc-800 bg-zinc-950 p-2.5 shadow-2xl shadow-purple-950/60 ring-1 ring-white/20">
                                        <!-- Notch / Camera pill -->
                                        <div class="absolute top-4 left-1/2 -translate-x-1/2 h-4 w-24 rounded-full bg-zinc-900 z-20 flex items-center justify-center">
                                            <div class="h-2 w-2 rounded-full bg-zinc-700"></div>
                                        </div>

                                        <!-- Screen Image -->
                                        <div class="overflow-hidden rounded-[28px] bg-zinc-900">
                                            <img
                                                src="{{ asset('images/app/beranda.png') }}"
                                                alt="Antarmuka Beranda Aplikasi Akhwat Gym"
                                                class="w-full h-auto object-cover"
                                            >
                                        </div>

                                        <!-- Floating Badge on Phone -->
                                        <div class="absolute -bottom-4 -left-4 rounded-xl border border-white/20 bg-purple-900/90 p-3 shadow-xl backdrop-blur-md sm:flex items-center gap-3 hidden">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-fuchsia-500 text-white font-bold text-lg">
                                                QR
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-white">Check-in Tanpa Ribet</p>
                                                <p class="text-[11px] text-fuchsia-200">Scan kartu member digital Anda</p>
                                            </div>
                                        </div>

                                        <!-- Floating Badge on Top Right -->
                                        <div class="absolute -top-4 -right-4 rounded-xl border border-white/20 bg-fuchsia-950/90 p-3 shadow-xl backdrop-blur-md hidden sm:flex items-center gap-3">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-pink-500 text-white text-base">
                                                📅
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-white">Jadwal Realtime</p>
                                                <p class="text-[11px] text-pink-200">Poundfit, Zumba, Yoga</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION: KEUNGGULAN (MENGAPA AKHWAT GYM) -->
                <section id="keunggulan" class="bg-zinc-50 py-20">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div class="text-center max-w-3xl mx-auto">
                            <p class="text-xs sm:text-sm font-extrabold uppercase tracking-widest text-fuchsia-700">Kenapa Memilih Kami</p>
                            <h2 class="mt-3 text-3xl font-extrabold text-zinc-950 sm:text-4xl tracking-tight">
                                Tempat Berolahraga yang Menjaga Kenyamanan &amp; Privasi Anda
                            </h2>
                            <p class="mt-4 text-base sm:text-lg text-zinc-600 leading-relaxed">
                                Kami memahami betapa pentingnya ruang yang aman dan bebas dari pandangan luar bagi wanita muslimah untuk beraktivitas fisik secara maksimal.
                            </p>
                        </div>

                        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                            <!-- Feature 1 -->
                            <div class="rounded-2xl border border-zinc-200/80 bg-white p-6 shadow-sm hover:shadow-md transition">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-fuchsia-100 text-fuchsia-700 text-2xl mb-5">
                                    🔒
                                </div>
                                <h3 class="text-lg font-bold text-zinc-950">100% Khusus Wanita</h3>
                                <p class="mt-2 text-sm text-zinc-600 leading-relaxed">
                                    Seluruh staf, trainer, dan pengunjung adalah perempuan. Ruang latihan privat tanpa celah pandangan dari luar, sehingga Anda bebas melepas hijab.
                                </p>
                            </div>

                            <!-- Feature 2 -->
                            <div class="rounded-2xl border border-zinc-200/80 bg-white p-6 shadow-sm hover:shadow-md transition">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-purple-700 text-2xl mb-5">
                                    🧘‍♀️
                                </div>
                                <h3 class="text-lg font-bold text-zinc-950">Kelas Variatif Setiap Hari</h3>
                                <p class="mt-2 text-sm text-zinc-600 leading-relaxed">
                                    Pilihan kelas seru mulai dari Zumba, Poundfit, Yoga Prenatal, Aerobic hingga Fitdance yang dipandu oleh instruktur berpengalaman.
                                </p>
                            </div>

                            <!-- Feature 3 -->
                            <div class="rounded-2xl border border-zinc-200/80 bg-white p-6 shadow-sm hover:shadow-md transition">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-pink-100 text-pink-700 text-2xl mb-5">
                                    🏋️‍♀️
                                </div>
                                <h3 class="text-lg font-bold text-zinc-950">Alat Lengkap &amp; Personal Trainer</h3>
                                <p class="mt-2 text-sm text-zinc-600 leading-relaxed">
                                    Fasilitas kardio dan beban lengkap dengan opsi bimbingan Personal Trainer wanita yang siap membantu mencapai target kebugaran Anda.
                                </p>
                            </div>

                            <!-- Feature 4 -->
                            <div class="rounded-2xl border border-zinc-200/80 bg-white p-6 shadow-sm hover:shadow-md transition">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 text-2xl mb-5">
                                    📲
                                </div>
                                <h3 class="text-lg font-bold text-zinc-950">Kemudahan Digital Member</h3>
                                <p class="mt-2 text-sm text-zinc-600 leading-relaxed">
                                    Cek jadwal, pesan tiket visit, langganan paket, dan scan QR check-in langsung dari Web Member PWA atau aplikasi Google Play Store.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION: FITUR APLIKASI & WEB MEMBER (SHOWCASE SCREENSHOTS) -->
                <section id="fitur-aplikasi" class="bg-white py-20 border-t border-zinc-100">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div class="text-center max-w-3xl mx-auto">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-fuchsia-50 px-3 py-1 text-xs font-bold text-fuchsia-700 border border-fuchsia-200">
                                📱 Aplikasi Mobile &amp; Web PWA
                            </span>
                            <h2 class="mt-3 text-3xl font-extrabold text-zinc-950 sm:text-4xl tracking-tight">
                                Semua Kebutuhan Olahraga dalam Satu Genggaman
                            </h2>
                            <p class="mt-4 text-base sm:text-lg text-zinc-600 leading-relaxed">
                                Nikmati kemudahan akses member Akhwat Gym tanpa perlu antre di resepsionis. Bisa dibuka langsung di browser atau diinstall di smartphone Android Anda.
                            </p>
                        </div>

                        <div class="mt-14 grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                            <!-- Card 1: Booking Kelas -->
                            <div class="group rounded-2xl border border-zinc-200 bg-zinc-50/50 p-4 transition hover:-translate-y-1 hover:border-fuchsia-300 hover:shadow-lg">
                                <div class="overflow-hidden rounded-xl bg-zinc-900 border border-zinc-200 shadow-inner">
                                    <img src="{{ asset('images/app/kelas.png') }}" alt="Booking Kelas Akhwat Gym" class="w-full h-auto object-cover group-hover:scale-105 transition duration-300">
                                </div>
                                <div class="mt-4 px-1">
                                    <div class="inline-block text-[11px] font-bold uppercase tracking-wider text-fuchsia-700">Jadwal &amp; Sesi</div>
                                    <h3 class="mt-1 text-base font-bold text-zinc-950">Booking Kelas Favorit</h3>
                                    <p class="mt-1.5 text-xs text-zinc-600 leading-relaxed">
                                        Pilih jadwal kelas Zumba, Poundfit, atau Yoga dan amankan kuota Anda secara realtime sebelum sesi dimulai.
                                    </p>
                                </div>
                            </div>

                            <!-- Card 2: Pilihan Paket -->
                            <div class="group rounded-2xl border border-zinc-200 bg-zinc-50/50 p-4 transition hover:-translate-y-1 hover:border-fuchsia-300 hover:shadow-lg">
                                <div class="overflow-hidden rounded-xl bg-zinc-900 border border-zinc-200 shadow-inner">
                                    <img src="{{ asset('images/app/paket.png') }}" alt="Pilihan Paket Membership Akhwat Gym" class="w-full h-auto object-cover group-hover:scale-105 transition duration-300">
                                </div>
                                <div class="mt-4 px-1">
                                    <div class="inline-block text-[11px] font-bold uppercase tracking-wider text-purple-700">Fleksibel &amp; Hemat</div>
                                    <h3 class="mt-1 text-base font-bold text-zinc-950">Pilih Paket Sesuai Kebutuhan</h3>
                                    <p class="mt-1.5 text-xs text-zinc-600 leading-relaxed">
                                        Tersedia tiket visit harian mulai Rp 32.500, paket bulanan 4x/8x sesi, hingga pendampingan Personal Trainer.
                                    </p>
                                </div>
                            </div>

                            <!-- Card 3: QR Check-in -->
                            <div class="group rounded-2xl border border-zinc-200 bg-zinc-50/50 p-4 transition hover:-translate-y-1 hover:border-fuchsia-300 hover:shadow-lg">
                                <div class="overflow-hidden rounded-xl bg-zinc-900 border border-zinc-200 shadow-inner">
                                    <img src="{{ asset('images/app/checkin.png') }}" alt="Presensi QR Code Akhwat Gym" class="w-full h-auto object-cover group-hover:scale-105 transition duration-300">
                                </div>
                                <div class="mt-4 px-1">
                                    <div class="inline-block text-[11px] font-bold uppercase tracking-wider text-pink-700">Presensi Cepat</div>
                                    <h3 class="mt-1 text-base font-bold text-zinc-950">QR Code Check-in</h3>
                                    <p class="mt-1.5 text-xs text-zinc-600 leading-relaxed">
                                        Tidak perlu membawa kartu fisik lagi. Cukup scan QR code di studio saat Anda datang untuk mencatat kehadiran.
                                    </p>
                                </div>
                            </div>

                            <!-- Card 4: Toko & QRIS -->
                            <div class="group rounded-2xl border border-zinc-200 bg-zinc-50/50 p-4 transition hover:-translate-y-1 hover:border-fuchsia-300 hover:shadow-lg">
                                <div class="overflow-hidden rounded-xl bg-zinc-900 border border-zinc-200 shadow-inner">
                                    <img src="{{ asset('images/app/belanja.png') }}" alt="Toko Sehat dan Pembayaran QRIS Akhwat Gym" class="w-full h-auto object-cover group-hover:scale-105 transition duration-300">
                                </div>
                                <div class="mt-4 px-1">
                                    <div class="inline-block text-[11px] font-bold uppercase tracking-wider text-emerald-700">Toko Sehat &amp; Minuman</div>
                                    <h3 class="mt-1 text-base font-bold text-zinc-950">Belanja &amp; Bayar QRIS</h3>
                                    <p class="mt-1.5 text-xs text-zinc-600 leading-relaxed">
                                        Beli air mineral, minuman elektrolit, dan suplemen olahraga langsung di studio dengan pembayaran instan QRIS.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Bar inside feature -->
                        <div class="mt-12 rounded-2xl bg-gradient-to-r from-purple-900 via-fuchsia-900 to-pink-900 p-6 sm:p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl">
                            <div>
                                <h3 class="text-xl sm:text-2xl font-bold">Siap memulai perjalanan bugar Anda?</h3>
                                <p class="mt-1 text-sm text-fuchsia-100">Buka Web Member sekarang juga dan pilih kelas pertama Anda!</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                <a href="{{ url('/member') }}" class="rounded-xl bg-white px-5 py-3 text-sm font-bold text-purple-950 shadow-md hover:bg-fuchsia-50 transition">
                                    Buka Web Member &rarr;
                                </a>
                                <a href="https://play.google.com/store/apps/details?id=com.akhwatgym.app" target="_blank" rel="noopener noreferrer" class="rounded-xl border border-white/30 bg-white/10 px-5 py-3 text-sm font-semibold text-white hover:bg-white/20 transition">
                                    Unduh di Play Store
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION: KELAS STUDIO POPULER -->
                <section id="kelas" class="bg-zinc-50 py-20">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div class="text-center max-w-3xl mx-auto">
                            <p class="text-xs sm:text-sm font-extrabold uppercase tracking-widest text-fuchsia-700">Pilihan Latihan</p>
                            <h2 class="mt-3 text-3xl font-extrabold text-zinc-950 sm:text-4xl tracking-tight">
                                Kelas Bugar Menyenangkan untuk Setiap Level
                            </h2>
                            <p class="mt-4 text-base sm:text-lg text-zinc-600 leading-relaxed">
                                Baik Anda pemula yang baru pertama kali berolahraga maupun yang sudah terbiasa aktif, kami menyediakan kelas yang tepat untuk Anda.
                            </p>
                        </div>

                        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            <!-- Poundfit -->
                            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <span class="rounded-lg bg-fuchsia-100 px-3 py-1 text-xs font-bold text-fuchsia-800">Cardio &amp; Rhythm</span>
                                    <span class="text-xs font-semibold text-zinc-500">Tiket Visit / Paket</span>
                                </div>
                                <h3 class="mt-4 text-xl font-bold text-zinc-950">Poundfit</h3>
                                <p class="mt-2 text-sm text-zinc-600 leading-relaxed">
                                    Kombinasi seru antara kardio, pilates, dan gerakan drum menggunakan stik Ripstix diiringi musik bersemangat. Membakar kalori dengan cepat dan meningkatkan stamina.
                                </p>
                                <div class="mt-4 pt-4 border-t border-zinc-100 flex items-center justify-between text-xs text-zinc-500">
                                    <span>⏱️ 50 - 60 Menit</span>
                                    <span>🔥 Kalori Tinggi</span>
                                </div>
                            </div>

                            <!-- Zumba & Zumba Gold -->
                            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <span class="rounded-lg bg-pink-100 px-3 py-1 text-xs font-bold text-pink-800">Dance &amp; Fun</span>
                                    <span class="text-xs font-semibold text-zinc-500">Tiket Visit / Paket</span>
                                </div>
                                <h3 class="mt-4 text-xl font-bold text-zinc-950">Zumba &amp; Zumba Gold</h3>
                                <p class="mt-2 text-sm text-zinc-600 leading-relaxed">
                                    Tarian kardio ceria yang membakar lemak sekaligus melepas penat dan stres. Tersedia juga Zumba Gold dengan koreografi ramah sendi untuk semua usia.
                                </p>
                                <div class="mt-4 pt-4 border-t border-zinc-100 flex items-center justify-between text-xs text-zinc-500">
                                    <span>⏱️ 50 - 60 Menit</span>
                                    <span>💃 Menyenangkan &amp; Ceria</span>
                                </div>
                            </div>

                            <!-- Yoga & Yoga Prenatal -->
                            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <span class="rounded-lg bg-purple-100 px-3 py-1 text-xs font-bold text-purple-800">Mind &amp; Flexibility</span>
                                    <span class="text-xs font-semibold text-zinc-500">Tiket Visit / Paket</span>
                                </div>
                                <h3 class="mt-4 text-xl font-bold text-zinc-950">Yoga &amp; Yoga Prenatal</h3>
                                <p class="mt-2 text-sm text-zinc-600 leading-relaxed">
                                    Latihan peregangan, kelenturan, dan pernapasan untuk menyeimbangkan pikiran serta tubuh. Kelas Prenatal dirancang khusus mendampingi kenyamanan ibu hamil.
                                </p>
                                <div class="mt-4 pt-4 border-t border-zinc-100 flex items-center justify-between text-xs text-zinc-500">
                                    <span>⏱️ 60 Menit</span>
                                    <span>🧘 Relaksasi &amp; Fleksibilitas</span>
                                </div>
                            </div>

                            <!-- Aerobic & Aeromix -->
                            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <span class="rounded-lg bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">Endurance &amp; Stamina</span>
                                    <span class="text-xs font-semibold text-zinc-500">Tiket Visit / Paket</span>
                                </div>
                                <h3 class="mt-4 text-xl font-bold text-zinc-950">Aerobic &amp; Aeromix</h3>
                                <p class="mt-2 text-sm text-zinc-600 leading-relaxed">
                                    Senam ritmik klasik dan moderen dengan alur pemanasan hingga pendinginan optimal. Memperkuat kesehatan kardiovaskular dan stamina tubuh harian.
                                </p>
                                <div class="mt-4 pt-4 border-t border-zinc-100 flex items-center justify-between text-xs text-zinc-500">
                                    <span>⏱️ 50 - 60 Menit</span>
                                    <span>🏃‍♀️ Stamina &amp; Kelincahan</span>
                                </div>
                            </div>

                            <!-- Fitdance & Bomiya -->
                            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <span class="rounded-lg bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">Body Toning</span>
                                    <span class="text-xs font-semibold text-zinc-500">Tiket Visit / Paket</span>
                                </div>
                                <h3 class="mt-4 text-xl font-bold text-zinc-950">Fitdance &amp; Bomiya</h3>
                                <p class="mt-2 text-sm text-zinc-600 leading-relaxed">
                                    Gerakan tari bertenaga dipadukan dengan pembentukan otot inti (core toning) untuk mengencangkan postur tubuh serta membakar kalori secara seimbang.
                                </p>
                                <div class="mt-4 pt-4 border-t border-zinc-100 flex items-center justify-between text-xs text-zinc-500">
                                    <span>⏱️ 50 Menit</span>
                                    <span>✨ Postur &amp; Pengencangan</span>
                                </div>
                            </div>

                            <!-- Gym Alat & Personal Trainer -->
                            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <span class="rounded-lg bg-rose-100 px-3 py-1 text-xs font-bold text-rose-800">Strength &amp; PT</span>
                                    <span class="text-xs font-semibold text-zinc-500">Visit / Member Bulanan</span>
                                </div>
                                <h3 class="mt-4 text-xl font-bold text-zinc-950">Gym Alat &amp; Personal Trainer</h3>
                                <p class="mt-2 text-sm text-zinc-600 leading-relaxed">
                                    Akses ke alat treadmill, sepeda statis, dumbbell, dan mesin beban. Dapatkan pendampingan Personal Trainer wanita untuk bimbingan latihan terarah.
                                </p>
                                <div class="mt-4 pt-4 border-t border-zinc-100 flex items-center justify-between text-xs text-zinc-500">
                                    <span>⏱️ Bebas / 60 Menit PT</span>
                                    <span>💪 Pembentukan Otot Terukur</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION: PILIHAN PAKET LATIHAN -->
                <section id="paket" class="bg-white py-20">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div class="text-center max-w-3xl mx-auto">
                            <p class="text-xs sm:text-sm font-extrabold uppercase tracking-widest text-fuchsia-700">Transparan &amp; Terjangkau</p>
                            <h2 class="mt-3 text-3xl font-extrabold text-zinc-950 sm:text-4xl tracking-tight">
                                Pilihan Paket Latihan Fleksibel
                            </h2>
                            <p class="mt-4 text-base sm:text-lg text-zinc-600 leading-relaxed">
                                Mau coba-coba harian (visit) atau berkomitmen dengan paket hemat bulanan? Anda bebas memilih sesuai jadwal dan kebutuhan Anda.
                            </p>
                        </div>

                        <div class="mt-14 grid gap-8 lg:grid-cols-3">
                            <!-- Paket 1: Tiket Visit (Harian) -->
                            <div class="rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                                <div>
                                    <div class="inline-block rounded-full bg-zinc-100 px-3 py-1 text-xs font-bold text-zinc-700">Coba Sekali Datang</div>
                                    <h3 class="mt-4 text-2xl font-bold text-zinc-950">Tiket Visit Harian</h3>
                                    <p class="mt-2 text-sm text-zinc-600">Cocok bagi Anda yang ingin latihan santai tanpa ikatan bulanan.</p>

                                    <div class="mt-6 flex items-baseline gap-1">
                                        <span class="text-sm font-semibold text-zinc-500">Mulai dari</span>
                                        <span class="text-4xl font-extrabold text-zinc-950">Rp 32.500</span>
                                        <span class="text-xs text-zinc-500">/kedatangan</span>
                                    </div>

                                    <ul class="mt-6 space-y-3 text-sm text-zinc-700">
                                        <li class="flex items-center gap-2">
                                            <span class="text-fuchsia-600 font-bold">✓</span> Gym Alat Visit (Rp 32.500)
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="text-fuchsia-600 font-bold">✓</span> Aerobic Visit (Rp 32.500)
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="text-fuchsia-600 font-bold">✓</span> Zumba Visit (Rp 35.000)
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="text-fuchsia-600 font-bold">✓</span> Poundfit Visit (Rp 50.000)
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="text-fuchsia-600 font-bold">✓</span> Yoga Visit (Rp 52.500)
                                        </li>
                                    </ul>
                                </div>

                                <a href="{{ url('/member') }}" class="mt-8 block w-full rounded-xl border border-zinc-300 bg-zinc-50 py-3 text-center text-sm font-bold text-zinc-900 hover:bg-zinc-100 transition">
                                    Beli Tiket di Web Member
                                </a>
                            </div>

                            <!-- Paket 2: Membership Kelas Hemat (Recommended) -->
                            <div class="relative rounded-2xl border-2 border-fuchsia-600 bg-gradient-to-b from-fuchsia-50/50 via-white to-white p-8 shadow-xl flex flex-col justify-between">
                                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 rounded-full bg-gradient-to-r from-fuchsia-600 to-purple-700 px-4 py-1 text-xs font-bold text-white shadow-md uppercase tracking-wider">
                                    Paling Diminati &bull; Hemat
                                </div>
                                <div>
                                    <div class="inline-block rounded-full bg-fuchsia-100 px-3 py-1 text-xs font-bold text-fuchsia-800">Paket Hemat Kelas</div>
                                    <h3 class="mt-4 text-2xl font-bold text-zinc-950">Member Sesi 4x &amp; 8x</h3>
                                    <p class="mt-2 text-sm text-zinc-600">Pilihan terfavorit untuk menjaga konsistensi latihan rutin mingguan.</p>

                                    <div class="mt-6 flex items-baseline gap-1">
                                        <span class="text-sm font-semibold text-zinc-500">Mulai dari</span>
                                        <span class="text-4xl font-extrabold text-fuchsia-950">Rp 120.000</span>
                                        <span class="text-xs text-zinc-500">/paket</span>
                                    </div>

                                    <ul class="mt-6 space-y-3 text-sm text-zinc-700">
                                        <li class="flex items-center gap-2">
                                            <span class="text-fuchsia-600 font-bold">✓</span> Paket 4x Sesi Kelas (Masa aktif 30 hari)
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="text-fuchsia-600 font-bold">✓</span> Paket 8x Sesi Kelas (Lebih hemat)
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="text-fuchsia-600 font-bold">✓</span> Member All Class (Bebas pilih kelas)
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="text-fuchsia-600 font-bold">✓</span> Prioritas booking kuota kelas
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="text-fuchsia-600 font-bold">✓</span> Kartu member digital di aplikasi
                                        </li>
                                    </ul>
                                </div>

                                <a href="{{ url('/member') }}" class="mt-8 block w-full rounded-xl bg-gradient-to-r from-fuchsia-600 to-purple-700 py-3 text-center text-sm font-bold text-white shadow-lg shadow-fuchsia-600/30 hover:from-fuchsia-500 hover:to-purple-600 transition">
                                    Pilih Paket di Web Member
                                </a>
                            </div>

                            <!-- Paket 3: Gym Member & Personal Trainer -->
                            <div class="rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                                <div>
                                    <div class="inline-block rounded-full bg-purple-100 px-3 py-1 text-xs font-bold text-purple-800">Gym Alat &amp; Private PT</div>
                                    <h3 class="mt-4 text-2xl font-bold text-zinc-950">Gym Member &amp; PT</h3>
                                    <p class="mt-2 text-sm text-zinc-600">Akses gym sepuasnya atau latihan intensif dengan bimbingan pelatih.</p>

                                    <div class="mt-6 flex items-baseline gap-1">
                                        <span class="text-sm font-semibold text-zinc-500">Mulai dari</span>
                                        <span class="text-4xl font-extrabold text-zinc-950">Rp 215.000</span>
                                        <span class="text-xs text-zinc-500">/bulan</span>
                                    </div>

                                    <ul class="mt-6 space-y-3 text-sm text-zinc-700">
                                        <li class="flex items-center gap-2">
                                            <span class="text-fuchsia-600 font-bold">✓</span> Gym Member 1 Bulan (Unlimited akses alat)
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="text-fuchsia-600 font-bold">✓</span> Personal Trainer Visit (Rp 80.000)
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="text-fuchsia-600 font-bold">✓</span> Personal Trainer Member Bulanan
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="text-fuchsia-600 font-bold">✓</span> Pemeriksaan Komposisi Tubuh (Body Fat)
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="text-fuchsia-600 font-bold">✓</span> Fasilitas loker &amp; ruang ganti nyaman
                                        </li>
                                    </ul>
                                </div>

                                <a href="{{ url('/member') }}" class="mt-8 block w-full rounded-xl border border-zinc-300 bg-zinc-50 py-3 text-center text-sm font-bold text-zinc-900 hover:bg-zinc-100 transition">
                                    Lihat Seluruh Paket &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION: DUA CARA AKSES APLIKASI (WEB PWA & GOOGLE PLAY) -->
                <section id="akses-aplikasi" class="bg-gradient-to-br from-zinc-950 via-purple-950 to-zinc-950 py-20 text-white">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div class="grid lg:grid-cols-12 gap-12 items-center">
                            <div class="lg:col-span-6">
                                <span class="rounded-full bg-fuchsia-500/20 px-3.5 py-1 text-xs font-bold text-fuchsia-300 border border-fuchsia-500/30">
                                    Akses Fleksibel Kapan Saja
                                </span>
                                <h2 class="mt-4 text-3xl font-extrabold sm:text-4xl tracking-tight text-white">
                                    Dua Cara Mudah Mengakses Akhwat Gym
                                </h2>
                                <p class="mt-4 text-base sm:text-lg text-zinc-300 leading-relaxed">
                                    Gunakan smartphone Anda tanpa hambatan. Anda bisa membuka langsung lewat browser via Web Member (PWA) atau mengunduh aplikasi resmi di Google Play Store.
                                </p>

                                <div class="mt-8 space-y-4">
                                    <!-- Pilihan 1: Web Member PWA -->
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm">
                                        <div class="flex items-start gap-4">
                                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-fuchsia-500 to-purple-600 text-white font-bold text-xl">
                                                🌐
                                            </div>
                                            <div class="flex-grow">
                                                <h3 class="text-base font-bold text-white">Opsi 1: Web Member (PWA)</h3>
                                                <p class="mt-1 text-xs sm:text-sm text-zinc-300">
                                                    Langsung dibuka di browser (Chrome, Safari, Edge) di iPhone, Android, tablet, atau laptop tanpa perlu download. Bisa disimpan ke Layar Utama (Add to Home Screen).
                                                </p>
                                                <a href="{{ url('/member') }}" class="mt-3 inline-flex items-center gap-2 text-xs font-bold text-fuchsia-300 hover:text-fuchsia-200">
                                                    Buka Web Member Sekarang &rarr;
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pilihan 2: Google Play Store -->
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm">
                                        <div class="flex items-start gap-4">
                                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 text-white font-bold text-xl">
                                                📲
                                            </div>
                                            <div class="flex-grow">
                                                <h3 class="text-base font-bold text-white">Opsi 2: Aplikasi Android Google Play Store</h3>
                                                <p class="mt-1 text-xs sm:text-sm text-zinc-300">
                                                    Unduh aplikasi resmi untuk ponsel Android dengan notifikasi pengingat kelas dan performa aplikasi native yang sangat mulus.
                                                </p>
                                                <a href="https://play.google.com/store/apps/details?id=com.akhwatgym.app" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex items-center gap-2 text-xs font-bold text-fuchsia-300 hover:text-fuchsia-200">
                                                    Download di Google Play Store &rarr;
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="lg:col-span-6 flex justify-center">
                                <div class="rounded-3xl border border-white/20 bg-white/10 p-6 sm:p-8 backdrop-blur-md max-w-md w-full shadow-2xl text-center">
                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white p-2 shadow-lg mb-4">
                                        <img src="{{ asset('images/brand/akhwat-gym-mark.png') }}" alt="Akhwat Gym" class="h-full w-full object-contain">
                                    </div>
                                    <h3 class="text-xl font-bold text-white">Mulai Olahraga Hari Ini</h3>
                                    <p class="mt-2 text-xs sm:text-sm text-zinc-300">
                                        Pendaftaran gratis dan cepat. Cukup masukkan nama, email, dan password Anda untuk langsung menjadi bagian dari Akhwat Gym.
                                    </p>

                                    <div class="mt-6 flex flex-col gap-3">
                                        <a href="{{ url('/member') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-fuchsia-500 to-purple-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg hover:from-fuchsia-400 hover:to-purple-500 transition">
                                            <span>Masuk ke Web Member (PWA)</span>
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                        </a>

                                        <a href="https://play.google.com/store/apps/details?id=com.akhwatgym.app" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl bg-zinc-900 border border-white/20 px-6 py-3 text-sm font-semibold text-white hover:bg-zinc-800 transition">
                                            <svg class="h-4 w-4 fill-current text-white" viewBox="0 0 24 24">
                                                <path d="M3.609 1.814L13.792 12 3.61 22.186a2.03 2.03 0 0 1-.61-1.465V3.279c0-.573.228-1.096.609-1.465zm11.242 11.244l2.583 2.583-12.022 6.87 9.439-9.453zm2.583-2.583L5.412 3.491l12.022 6.87-2.583 2.583zm1.189 1.189l3.053-1.745a1.536 1.536 0 0 0 0-2.671l-3.053-1.745-1.927 1.927 1.927 1.934z"/>
                                            </svg>
                                            <span>Pasang Aplikasi Android</span>
                                        </a>
                                    </div>

                                    <div class="mt-6 pt-5 border-t border-white/10 text-xs text-zinc-400">
                                        Ada kendala atau pertanyaan? <a href="https://wa.me/6285794132886?text=Halo%20Admin%20Akhwat%20Gym,%20saya%20ingin%20tanya%20seputar%20member." target="_blank" rel="noopener noreferrer" class="text-fuchsia-300 font-semibold underline hover:text-white">Chat Admin WhatsApp</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION: BANTUAN, FAQ & KONTAK -->
                <section id="kontak" class="bg-white py-20 border-t border-zinc-200">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div class="text-center max-w-3xl mx-auto">
                            <p class="text-xs sm:text-sm font-extrabold uppercase tracking-widest text-fuchsia-700">Layanan &amp; Bantuan</p>
                            <h2 class="mt-3 text-3xl font-extrabold text-zinc-950 sm:text-4xl tracking-tight">
                                Hubungi Kami &amp; Tanya Jawab
                            </h2>
                            <p class="mt-4 text-base sm:text-lg text-zinc-600 leading-relaxed">
                                Tim kami dengan senang hati menjawab pertanyaan seputar jadwal kelas, pemilihan paket, fasilitas, maupun panduan penggunaan aplikasi.
                            </p>
                        </div>

                        <!-- Contact Cards -->
                        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            <!-- WhatsApp -->
                            <div class="rounded-2xl border border-zinc-200 bg-fuchsia-50/50 p-6 shadow-sm hover:shadow-md transition">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-fuchsia-600 text-white shadow-md shadow-fuchsia-600/25">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-bold text-zinc-950">WhatsApp Admin</h3>
                                <p class="mt-1 text-sm text-zinc-600">Konsultasi kelas, jadwal, atau bantuan transaksi.</p>
                                <p class="mt-3 text-lg font-extrabold text-fuchsia-950">0857 9413 2886</p>
                                <a href="https://wa.me/6285794132886?text=Halo%20Admin%20Akhwat%20Gym,%20saya%20ingin%20tanya%20seputar%20kelas%20dan%20member." target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex items-center justify-center rounded-xl bg-fuchsia-600 px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-sm transition hover:bg-fuchsia-700">
                                    Chat via WhatsApp &rarr;
                                </a>
                            </div>

                            <!-- Email -->
                            <div class="rounded-2xl border border-zinc-200 bg-purple-50/50 p-6 shadow-sm hover:shadow-md transition">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-700 text-white shadow-md shadow-purple-700/25">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-bold text-zinc-950">Email Resmi</h3>
                                <p class="mt-1 text-sm text-zinc-600">Pertanyaan kerja sama dan informasi umum.</p>
                                <p class="mt-3 text-lg font-extrabold text-purple-950">akhwatgymcom@gmail.com</p>
                                <a href="mailto:akhwatgymcom@gmail.com?subject=Pertanyaan%20Akhwat%20Gym" class="mt-4 inline-flex items-center justify-center rounded-xl bg-purple-900 px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-sm transition hover:bg-purple-800">
                                    Kirim Email &rarr;
                                </a>
                            </div>

                            <!-- Jam Operasional -->
                            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-6 shadow-sm hover:shadow-md transition">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-zinc-900 text-white shadow-md">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-bold text-zinc-950">Jam Operasional</h3>
                                <p class="mt-1 text-sm text-zinc-600">Buka Setiap Hari (Senin &ndash; Ahad)</p>
                                <p class="mt-3 text-lg font-extrabold text-zinc-900">07.00 &ndash; 20.00 WIB</p>
                                <a href="{{ url('/support') }}" class="mt-4 inline-flex items-center justify-center rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-xs sm:text-sm font-bold text-zinc-800 transition hover:bg-zinc-100">
                                    Pusat Bantuan &rarr;
                                </a>
                            </div>
                        </div>

                        <!-- FAQ Accordion / Grid -->
                        <div class="mt-14 rounded-3xl border border-zinc-200 bg-zinc-50/70 p-6 sm:p-10">
                            <h3 class="text-xl font-bold text-zinc-950">Pertanyaan yang Sering Diajukan (FAQ)</h3>
                            <div class="mt-6 grid gap-6 md:grid-cols-2">
                                <div class="rounded-xl bg-white p-5 border border-zinc-200/80 shadow-sm">
                                    <h4 class="font-bold text-zinc-900 text-sm sm:text-base">Apakah benar-benar 100% khusus wanita?</h4>
                                    <p class="mt-2 text-xs sm:text-sm text-zinc-600 leading-relaxed">
                                        Ya, mutlak 100%. Mulai dari pintu masuk, staf resepsionis, instruktur kelas, hingga sesama member seluruhnya adalah wanita. Pria tidak diperbolehkan masuk ke area latihan.
                                    </p>
                                </div>

                                <div class="rounded-xl bg-white p-5 border border-zinc-200/80 shadow-sm">
                                    <h4 class="font-bold text-zinc-900 text-sm sm:text-base">Bagaimana cara mendaftar dan membeli paket?</h4>
                                    <p class="mt-2 text-xs sm:text-sm text-zinc-600 leading-relaxed">
                                        Sangat mudah! Buka Web Member di <a href="{{ url('/member') }}" class="text-fuchsia-700 font-bold underline">fitness.dbaik.com/member</a> atau unduh aplikasi di Google Play Store. Daftar akun, pilih paket atau tiket visit yang diinginkan, lalu bayar secara praktis melalui QRIS atau transfer bank.
                                    </p>
                                </div>

                                <div class="rounded-xl bg-white p-5 border border-zinc-200/80 shadow-sm">
                                    <h4 class="font-bold text-zinc-900 text-sm sm:text-base">Saya pemula yang belum pernah ke gym, apakah bisa dibimbing?</h4>
                                    <p class="mt-2 text-xs sm:text-sm text-zinc-600 leading-relaxed">
                                        Sangat bisa! Instruktur kelas kami sangat ramah menyambut pemula. Anda juga bisa mengambil paket Personal Trainer (PT) wanita untuk bimbingan step-by-step penggunaan alat beban dan kardio.
                                    </p>
                                </div>

                                <div class="rounded-xl bg-white p-5 border border-zinc-200/80 shadow-sm">
                                    <h4 class="font-bold text-zinc-900 text-sm sm:text-base">Apakah perlu menginstall aplikasi jika memori HP penuh?</h4>
                                    <p class="mt-2 text-xs sm:text-sm text-zinc-600 leading-relaxed">
                                        Tidak perlu! Web Member PWA kami dirancang khusus agar berfungsi 100% sama dengan aplikasi mobile langsung dari browser Google Chrome atau Safari tanpa memakan memori instalasi.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            <!-- FOOTER -->
            <footer class="border-t border-zinc-200 bg-zinc-900 text-zinc-400 py-12">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid gap-8 md:grid-cols-4 lg:grid-cols-5">
                        <!-- Brand Info -->
                        <div class="md:col-span-2">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-fuchsia-600 to-purple-800 p-1.5 shadow-md">
                                    <img src="{{ asset('images/brand/akhwat-gym-mark.png') }}" alt="Akhwat Gym" class="h-full w-full object-contain">
                                </span>
                                <span class="text-lg font-bold text-white tracking-tight">AKHWAT GYM</span>
                            </div>
                            <p class="mt-4 text-xs sm:text-sm leading-relaxed text-zinc-400 max-w-sm">
                                Studio fitness dan gym ramah muslimah dengan privasi terjaga, instruktur profesional, dan sistem digital modern untuk kenyamanan berolahraga setiap hari.
                            </p>
                            <p class="mt-4 text-xs text-zinc-500">
                                Jam Operasional: Setiap Hari, 07.00 &ndash; 20.00 WIB
                            </p>
                        </div>

                        <!-- Akses Member -->
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-white">Akses Member</p>
                            <ul class="mt-4 space-y-2.5 text-xs sm:text-sm">
                                <li>
                                    <a href="{{ url('/member') }}" class="text-fuchsia-400 hover:text-fuchsia-300 font-semibold flex items-center gap-1.5">
                                        <span>Buka Web Member (PWA)</span>
                                        <span>&rarr;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://play.google.com/store/apps/details?id=com.akhwatgym.app" target="_blank" rel="noopener noreferrer" class="hover:text-white transition">
                                        Google Play Store
                                    </a>
                                </li>
                                <li>
                                    <a href="#kelas" class="hover:text-white transition">Jadwal Kelas</a>
                                </li>
                                <li>
                                    <a href="#paket" class="hover:text-white transition">Daftar Harga &amp; Paket</a>
                                </li>
                            </ul>
                        </div>

                        <!-- Bantuan & Kebijakan -->
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-white">Bantuan &amp; Info</p>
                            <ul class="mt-4 space-y-2.5 text-xs sm:text-sm">
                                <li>
                                    <a href="{{ url('/support') }}" class="hover:text-white transition">Pusat Bantuan</a>
                                </li>
                                <li>
                                    <a href="{{ url('/privacy-policy') }}" class="hover:text-white transition">Kebijakan Privasi</a>
                                </li>
                                <li>
                                    <a href="https://wa.me/6285794132886" target="_blank" rel="noopener noreferrer" class="hover:text-white transition">WhatsApp Admin</a>
                                </li>
                                <li>
                                    <a href="mailto:akhwatgymcom@gmail.com" class="hover:text-white transition">Email Dukungan</a>
                                </li>
                            </ul>
                        </div>

                        <!-- Staff / Admin Portal -->
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-white">Internal</p>
                            <ul class="mt-4 space-y-2.5 text-xs sm:text-sm">
                                <li>
                                    <a href="{{ url('/admin') }}" class="hover:text-white transition inline-flex items-center gap-1.5">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Portal Admin &amp; Staff</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-12 pt-8 border-t border-zinc-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-zinc-500">
                        <p>&copy; {{ now()->year }} Akhwat Gym. Hak Cipta Dilindungi.</p>
                        <p class="text-zinc-500">Didesain dengan sepenuh hati untuk kesehatan &amp; kenyamanan muslimah.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
