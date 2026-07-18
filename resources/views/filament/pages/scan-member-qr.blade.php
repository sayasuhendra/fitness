<x-filament-panels::page>
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
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
                class="space-y-4"
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

                <div class="flex flex-wrap gap-3">
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

        <div class="space-y-6">
            <x-filament::section>
                <x-slot name="heading">
                    Detail Check-In
                </x-slot>

                <div class="space-y-4">
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

            <x-filament::section>
                <x-slot name="heading">
                    Input Manual
                </x-slot>

                <div
                    x-data="{ payload: '' }"
                    class="space-y-3"
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

            @if ($lastCheckIn)
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
