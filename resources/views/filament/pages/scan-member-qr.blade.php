<x-filament-panels::page>
    <style>
        .member-qr-page-grid {
            display: grid;
            gap: 1.5rem;
            align-items: start;
        }

        .member-qr-side-stack,
        .member-qr-inner-stack {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .member-qr-side-stack {
            gap: 1.5rem;
        }

        .member-qr-card {
            min-width: 0;
        }

        .member-qr-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 0.25rem;
        }

        .member-qr-page-grid input,
        .member-qr-page-grid textarea {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            padding: 0.5rem 0.75rem;
            border: 1px solid rgb(209 213 219);
            border-radius: 0.5rem;
            background-color: #ffffff;
            color: rgb(17 24 39);
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .member-qr-page-grid input {
            min-height: 2.75rem;
        }

        .member-qr-page-grid textarea {
            min-height: 8rem;
            resize: vertical;
        }

        .member-qr-page-grid input:focus,
        .member-qr-page-grid textarea:focus {
            border-color: rgb(168 85 247);
            outline: 2px solid rgb(168 85 247 / 0.22);
            outline-offset: 1px;
        }

        .dark .member-qr-page-grid input,
        .dark .member-qr-page-grid textarea {
            border-color: rgb(55 65 81);
            background-color: rgb(17 24 39);
            color: #ffffff;
        }

        @media (min-width: 1024px) {
            .member-qr-page-grid {
                grid-template-columns: minmax(0, 1fr) 360px;
            }
        }
    </style>

    <div class="member-qr-page-grid">
        <div class="member-qr-card">
        <x-filament::section>
            <x-slot name="heading">
                Kamera Scanner
            </x-slot>

            <x-slot name="description">
                Gunakan HP admin lokasi, buka halaman ini lewat HTTPS, lalu arahkan kamera ke QR member di aplikasi mobile.
            </x-slot>

            <div
                x-data="memberQrScanner({
                    submit: (payload) => $wire.submitScan(payload),
                })"
                x-init="init()"
                class="member-qr-inner-stack"
            >
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-gray-950 dark:border-gray-700">
                    <video
                        x-ref="video"
                        class="aspect-video w-full bg-gray-950 object-cover"
                        muted
                        playsinline
                    ></video>
                </div>

                <canvas x-ref="canvas" class="hidden"></canvas>

                <div class="member-qr-actions">
                    <x-filament::button type="button" x-on:click="start()" icon="heroicon-m-camera">
                        Mulai Kamera
                    </x-filament::button>

                    <x-filament::button type="button" color="gray" x-on:click="stop()" icon="heroicon-m-stop">
                        Stop
                    </x-filament::button>
                </div>

                <div
                    x-show="message"
                    x-text="message"
                    class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-900 dark:bg-amber-950 dark:text-amber-200"
                ></div>
            </div>
        </x-filament::section>
        </div>

        <div class="member-qr-side-stack">
            <div class="member-qr-card">
            <x-filament::section>
                <x-slot name="heading">
                    Detail Check-In
                </x-slot>

                <div class="member-qr-inner-stack">
                    <label class="block">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Lokasi</span>
                        <input
                            type="text"
                            wire:model.live="location"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                        />
                    </label>

                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Scanner ini mencatat <strong>Gym Visit</strong>. Untuk check-in kelas atau personal trainer yang membutuhkan booking/sesi tertentu, gunakan form Check-In manual.
                    </p>
                </div>
            </x-filament::section>
            </div>

            <div class="member-qr-card">
            <x-filament::section>
                <x-slot name="heading">
                    Input Manual
                </x-slot>

                <div
                    x-data="{ payload: '' }"
                    class="member-qr-inner-stack"
                >
                    <textarea
                        x-model="payload"
                        rows="5"
                        placeholder="Tempel payload QR member jika kamera tidak tersedia."
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    ></textarea>

                    <x-filament::button type="button" x-on:click="$wire.submitScan(payload)">
                        Check-In Manual
                    </x-filament::button>
                </div>
            </x-filament::section>
            </div>

            @if ($lastCheckIn)
                <div class="member-qr-card">
                <x-filament::section>
                    <x-slot name="heading">
                        Check-In Terakhir
                    </x-slot>

                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Member</dt>
                            <dd class="text-gray-950 dark:text-white">{{ $lastCheckIn['member'] }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Kode Member</dt>
                            <dd class="text-gray-950 dark:text-white">{{ $lastCheckIn['member_code'] }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Waktu</dt>
                            <dd class="text-gray-950 dark:text-white">{{ $lastCheckIn['time'] }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Lokasi</dt>
                            <dd class="text-gray-950 dark:text-white">{{ $lastCheckIn['location'] }}</dd>
                        </div>
                    </dl>
                </x-filament::section>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('memberQrScanner', ({ submit }) => ({
                detector: null,
                stream: null,
                scanning: false,
                lastPayload: null,
                message: '',

                async init() {
                    if (!('BarcodeDetector' in window)) {
                        this.message = 'Browser ini belum mendukung scanner kamera otomatis. Gunakan Chrome Android terbaru atau input manual.';
                        return;
                    }

                    this.detector = new BarcodeDetector({ formats: ['qr_code'] });
                },

                async start() {
                    if (!this.detector) {
                        this.message = 'Scanner kamera tidak tersedia di browser ini.';
                        return;
                    }

                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia({
                            video: { facingMode: { ideal: 'environment' } },
                            audio: false,
                        });

                        this.$refs.video.srcObject = this.stream;
                        await this.$refs.video.play();
                        this.scanning = true;
                        this.message = 'Kamera aktif. Arahkan ke QR member.';
                        this.scanLoop();
                    } catch (error) {
                        this.message = 'Kamera tidak bisa dibuka. Pastikan izin kamera diberikan dan halaman memakai HTTPS.';
                    }
                },

                stop() {
                    this.scanning = false;

                    if (this.stream) {
                        this.stream.getTracks().forEach((track) => track.stop());
                        this.stream = null;
                    }

                    this.message = 'Kamera berhenti.';
                },

                async scanLoop() {
                    if (!this.scanning) {
                        return;
                    }

                    const video = this.$refs.video;

                    if (video.readyState >= 2) {
                        try {
                            const codes = await this.detector.detect(video);
                            const payload = codes[0]?.rawValue;

                            if (payload && payload !== this.lastPayload) {
                                this.lastPayload = payload;
                                this.message = 'QR terbaca. Mencatat check-in...';
                                submit(payload);

                                setTimeout(() => {
                                    this.lastPayload = null;
                                }, 3000);
                            }
                        } catch (error) {
                            this.message = 'Scanner belum bisa membaca QR. Coba dekatkan kamera atau naikkan brightness layar member.';
                        }
                    }

                    requestAnimationFrame(() => this.scanLoop());
                },
            }));
        });
    </script>
</x-filament-panels::page>
