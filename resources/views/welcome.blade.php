<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Fitness Akhwat is a women-only fitness management platform for memberships, class booking, QR attendance, store checkout, payments, and admin operations.">

        <title>Fitness Akhwat Management Platform</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white text-zinc-950 antialiased">
        <div class="min-h-screen">
            <header class="absolute inset-x-0 top-0 z-30">
                <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-5 sm:px-8" aria-label="Primary navigation">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 text-white">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/15 ring-1 ring-white/30 backdrop-blur">
                            <span class="text-lg font-bold">FA</span>
                        </span>
                        <span class="text-sm font-semibold uppercase tracking-[0.18em]">Fitness Akhwat</span>
                    </a>

                    <div class="hidden items-center gap-8 text-sm font-medium text-white/85 md:flex">
                        <a href="#platform" class="transition hover:text-white">Platform</a>
                        <a href="#features" class="transition hover:text-white">Features</a>
                        <a href="#operations" class="transition hover:text-white">Operations</a>
                    </div>

                    <a href="{{ url('/admin') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-emerald-950 shadow-sm transition hover:bg-emerald-50">
                        Admin Panel
                    </a>
                </nav>
            </header>

            <main>
                <section class="relative min-h-screen overflow-hidden">
                    <img
                        src="{{ asset('images/landing/akhwat-studio.png') }}"
                        alt="Women-only fitness class inside a bright modern studio"
                        class="absolute inset-0 h-full w-full object-cover"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-zinc-950/90 via-emerald-950/68 to-zinc-950/20"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/82 via-transparent to-zinc-950/35"></div>

                    <div class="relative z-10 mx-auto flex min-h-screen max-w-7xl items-center px-5 pb-20 pt-32 sm:px-8">
                        <div class="max-w-3xl text-white">
                            <p class="mb-5 inline-flex rounded-lg border border-white/20 bg-white/10 px-3 py-1 text-sm font-medium text-emerald-50 backdrop-blur">
                                Women-only fitness management, built for real operations
                            </p>
                            <h1 class="max-w-2xl text-5xl font-bold leading-tight sm:text-6xl lg:text-7xl">
                                Fitness Akhwat
                            </h1>
                            <p class="mt-6 max-w-2xl text-lg leading-8 text-zinc-100 sm:text-xl">
                                A production-ready platform for memberships, class booking, QR attendance, product checkout, Midtrans payments, mobile members, and Filament-powered administration.
                            </p>

                            <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                                <a href="{{ url('/admin') }}" class="inline-flex items-center justify-center rounded-lg bg-emerald-400 px-6 py-3 text-sm font-bold text-emerald-950 shadow-lg shadow-emerald-950/30 transition hover:bg-emerald-300">
                                    Open Admin
                                </a>
                                <a href="#features" class="inline-flex items-center justify-center rounded-lg border border-white/25 bg-white/10 px-6 py-3 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20">
                                    Explore Features
                                </a>
                            </div>

                            <dl class="mt-12 grid max-w-2xl grid-cols-3 gap-3">
                                <div class="rounded-lg border border-white/15 bg-white/10 p-4 backdrop-blur">
                                    <dt class="text-xs uppercase tracking-[0.16em] text-emerald-100">Core Modules</dt>
                                    <dd class="mt-2 text-3xl font-bold">8</dd>
                                </div>
                                <div class="rounded-lg border border-white/15 bg-white/10 p-4 backdrop-blur">
                                    <dt class="text-xs uppercase tracking-[0.16em] text-emerald-100">API First</dt>
                                    <dd class="mt-2 text-3xl font-bold">v1</dd>
                                </div>
                                <div class="rounded-lg border border-white/15 bg-white/10 p-4 backdrop-blur">
                                    <dt class="text-xs uppercase tracking-[0.16em] text-emerald-100">Admin</dt>
                                    <dd class="mt-2 text-3xl font-bold">Live</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </section>

                <section id="platform" class="bg-zinc-50 py-20">
                    <div class="mx-auto grid max-w-7xl gap-10 px-5 sm:px-8 lg:grid-cols-[0.95fr_1.05fr] lg:items-center">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-[0.18em] text-emerald-700">One operating system</p>
                            <h2 class="mt-4 text-3xl font-bold tracking-tight text-zinc-950 sm:text-4xl">
                                Member experience, trainer schedules, payments, and reporting in one flow.
                            </h2>
                            <p class="mt-5 text-base leading-8 text-zinc-600">
                                The backend powers a mobile app and admin panel with Laravel Sanctum, clean service layers, API resources, queues, notifications, and Filament resources for day-to-day operations.
                            </p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
                                <p class="text-sm font-semibold text-zinc-500">Today</p>
                                <p class="mt-2 text-3xl font-bold text-zinc-950">42</p>
                                <p class="mt-1 text-sm text-zinc-600">Booked class seats</p>
                            </div>
                            <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
                                <p class="text-sm font-semibold text-zinc-500">Check-ins</p>
                                <p class="mt-2 text-3xl font-bold text-emerald-700">87%</p>
                                <p class="mt-1 text-sm text-zinc-600">QR attendance completion</p>
                            </div>
                            <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
                                <p class="text-sm font-semibold text-zinc-500">Revenue</p>
                                <p class="mt-2 text-3xl font-bold text-zinc-950">IDR 18.4M</p>
                                <p class="mt-1 text-sm text-zinc-600">Membership and store sales</p>
                            </div>
                            <div class="rounded-lg border border-zinc-200 bg-emerald-950 p-5 text-white shadow-sm">
                                <p class="text-sm font-semibold text-emerald-100">Payments</p>
                                <p class="mt-2 text-3xl font-bold">Midtrans</p>
                                <p class="mt-1 text-sm text-emerald-50">Checkout-ready integration</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="features" class="bg-white py-20">
                    <div class="mx-auto max-w-7xl px-5 sm:px-8">
                        <div class="max-w-3xl">
                            <p class="text-sm font-bold uppercase tracking-[0.18em] text-emerald-700">Feature complete</p>
                            <h2 class="mt-4 text-3xl font-bold tracking-tight text-zinc-950 sm:text-4xl">
                                Everything from registration to reporting.
                            </h2>
                        </div>

                        <div class="mt-10 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                            @foreach ([
                                ['title' => 'Authentication', 'copy' => 'Register, login, forgot password, secure profile APIs, and Sanctum token sessions.'],
                                ['title' => 'Membership', 'copy' => 'Packages, purchases, active membership tracking, member history, and admin visibility.'],
                                ['title' => 'Class Booking', 'copy' => 'Trainer profiles, schedules, booking capacity, cancellation, and attendance readiness.'],
                                ['title' => 'Attendance', 'copy' => 'QR check-in flow with history for members and operational reports for staff.'],
                                ['title' => 'Store', 'copy' => 'Categories, products, cart, checkout, transaction states, and stock-aware workflows.'],
                                ['title' => 'Payments', 'copy' => 'Midtrans-ready transaction model for memberships and product checkout.'],
                                ['title' => 'Notifications', 'copy' => 'Firebase push notification hooks for booking, payment, and membership events.'],
                                ['title' => 'Admin Panel', 'copy' => 'Filament resources for members, trainers, schedules, products, transactions, and reports.'],
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

                <section id="operations" class="bg-emerald-950 py-20 text-white">
                    <div class="mx-auto grid max-w-7xl gap-10 px-5 sm:px-8 lg:grid-cols-[1fr_1.1fr] lg:items-start">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-[0.18em] text-emerald-200">Admin operations</p>
                            <h2 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl">
                                Built for the team behind the studio.
                            </h2>
                            <p class="mt-5 text-base leading-8 text-emerald-50">
                                Staff can manage members, trainers, class schedules, product catalogs, transactions, and reports without jumping between disconnected tools.
                            </p>
                            <a href="{{ url('/admin') }}" class="mt-8 inline-flex rounded-lg bg-white px-5 py-3 text-sm font-bold text-emerald-950 transition hover:bg-emerald-50">
                                Go to Filament Admin
                            </a>
                        </div>

                        <div class="rounded-lg border border-white/10 bg-white/10 p-5 shadow-2xl backdrop-blur">
                            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                                <div>
                                    <p class="text-sm font-semibold text-emerald-100">Analytics Dashboard</p>
                                    <p class="text-xs text-emerald-50/80">Live operations snapshot</p>
                                </div>
                                <span class="rounded-lg bg-emerald-400 px-3 py-1 text-xs font-bold text-emerald-950">Healthy</span>
                            </div>

                            <div class="mt-5 grid gap-3 sm:grid-cols-3">
                                <div class="rounded-lg bg-white p-4 text-zinc-950">
                                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-zinc-500">Members</p>
                                    <p class="mt-3 text-2xl font-bold">1,248</p>
                                </div>
                                <div class="rounded-lg bg-white p-4 text-zinc-950">
                                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-zinc-500">Bookings</p>
                                    <p class="mt-3 text-2xl font-bold">316</p>
                                </div>
                                <div class="rounded-lg bg-white p-4 text-zinc-950">
                                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-zinc-500">Sales</p>
                                    <p class="mt-3 text-2xl font-bold">96</p>
                                </div>
                            </div>

                            <div class="mt-5 space-y-3">
                                @foreach ([
                                    ['label' => 'Membership renewals', 'value' => '72%'],
                                    ['label' => 'Class occupancy', 'value' => '84%'],
                                    ['label' => 'Store checkout completion', 'value' => '68%'],
                                ] as $metric)
                                    <div class="rounded-lg bg-white/10 p-4">
                                        <div class="flex items-center justify-between text-sm">
                                            <span>{{ $metric['label'] }}</span>
                                            <span class="font-bold">{{ $metric['value'] }}</span>
                                        </div>
                                        <div class="mt-3 h-2 rounded-full bg-white/15">
                                            <div class="h-2 rounded-full bg-emerald-300" style="width: {{ $metric['value'] }}"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                <section class="bg-zinc-50 py-16">
                    <div class="mx-auto max-w-7xl px-5 sm:px-8">
                        <div class="rounded-lg border border-zinc-200 bg-white p-8 shadow-sm">
                            <p class="text-sm font-bold uppercase tracking-[0.18em] text-emerald-700">Production stack</p>
                            <div class="mt-6 flex flex-wrap gap-3">
                                @foreach (['Laravel 13+', 'PHP 8.4', 'PostgreSQL', 'Sanctum', 'Filament v5', 'Queues', 'Notifications', 'OpenAPI', 'Flutter', 'Riverpod', 'Dio', 'Caddy', 'GitHub Actions'] as $tool)
                                    <span class="rounded-lg border border-zinc-200 bg-zinc-50 px-4 py-2 text-sm font-semibold text-zinc-700">{{ $tool }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            <footer class="bg-white py-8">
                <div class="mx-auto flex max-w-7xl flex-col gap-3 px-5 text-sm text-zinc-500 sm:flex-row sm:items-center sm:justify-between sm:px-8">
                    <p>&copy; {{ now()->year }} Fitness Akhwat. Built for focused, privacy-aware fitness operations.</p>
                    <a href="{{ url('/admin') }}" class="font-semibold text-emerald-700 hover:text-emerald-800">Admin access</a>
                </div>
            </footer>
        </div>
    </body>
</html>
