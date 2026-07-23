<x-filament-panels::page>
    <style>
        .member-notification-grid {
            display: grid;
            gap: 1.5rem;
            align-items: start;
        }

        .member-notification-card {
            min-width: 0;
        }

        .member-notification-side-stack,
        .member-notification-form-stack {
            display: flex;
            flex-direction: column;
        }

        .member-notification-side-stack {
            gap: 1.5rem;
        }

        .member-notification-form-stack {
            gap: 1.25rem;
        }

        .member-notification-form-grid {
            display: grid;
            gap: 1rem;
        }

        .member-notification-card label {
            display: block;
        }

        .member-notification-grid input,
        .member-notification-grid select,
        .member-notification-grid textarea {
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

        .member-notification-grid input,
        .member-notification-grid select {
            min-height: 2.75rem;
        }

        .member-notification-grid select {
            padding-right: 2.25rem;
        }

        .member-notification-grid textarea {
            min-height: 8rem;
            resize: vertical;
        }

        .member-notification-grid input:focus,
        .member-notification-grid select:focus,
        .member-notification-grid textarea:focus {
            border-color: rgb(168 85 247);
            outline: 2px solid rgb(168 85 247 / 0.22);
            outline-offset: 1px;
        }

        .member-notification-grid select option {
            background-color: #ffffff;
            color: rgb(17 24 39);
        }

        .member-notification-actions {
            margin-top: 0.25rem;
        }

        .dark .member-notification-grid input,
        .dark .member-notification-grid select,
        .dark .member-notification-grid textarea {
            border-color: rgb(55 65 81);
            background-color: rgb(17 24 39);
            color: #ffffff;
        }

        .dark .member-notification-grid select option {
            background-color: rgb(17 24 39);
            color: #ffffff;
        }

        @media (min-width: 768px) {
            .member-notification-form-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .member-notification-grid {
                grid-template-columns: minmax(0, 1fr) 360px;
            }
        }
    </style>

    <div class="member-notification-grid">
        <div class="member-notification-card">
        <x-filament::section>
            <x-slot name="heading">
                Pesan Notifikasi
            </x-slot>

            <x-slot name="description">
                Notifikasi akan masuk ke inbox aplikasi member. Jika Firebase sudah aktif dan perangkat member punya FCM token, pesan juga dikirim sebagai push notification.
            </x-slot>

            <form wire:submit="send" class="member-notification-form-stack">
                <div class="member-notification-form-grid">
                    <label class="block">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Target</span>
                        <select
                            wire:model.live="target"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                        >
                            <option value="all">Semua member</option>
                            <option value="selected">Member tertentu</option>
                        </select>
                        @error('target')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </label>

                    <label class="block">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Tipe Notifikasi</span>
                        <input
                            type="text"
                            wire:model="type"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                        />
                        @error('type')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </label>
                </div>

                @if ($target === 'selected')
                    <label class="block">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Pilih Member</span>
                        <select
                            wire:model="memberIds"
                            multiple
                            size="10"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                        >
                            @foreach ($this->memberOptions as $memberId => $label)
                                <option value="{{ $memberId }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tahan Ctrl/Command untuk memilih lebih dari satu member.</p>
                        @error('memberIds')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </label>
                @endif

                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Judul</span>
                    <input
                        type="text"
                        wire:model="notificationTitle"
                        maxlength="80"
                        placeholder="Contoh: Jadwal kelas terbaru"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                    @error('notificationTitle')
                        <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                    @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Isi Pesan</span>
                    <textarea
                        wire:model="body"
                        rows="5"
                        maxlength="240"
                        placeholder="Tulis pesan singkat yang akan dibaca member."
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    ></textarea>
                    @error('body')
                        <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                    @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Saat Notifikasi Dibuka, Arahkan Member ke</span>
                    <select
                        wire:model="actionUrl"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    >
                        @foreach ($this->actionUrlOptions() as $url => $label)
                            <option value="{{ $url }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pilih halaman yang akan terbuka saat member menekan notifikasi di aplikasi.</p>
                    @error('actionUrl')
                        <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                    @enderror
                </label>

                <div class="member-notification-actions">
                    <x-filament::button type="submit" icon="heroicon-m-paper-airplane">
                        Kirim Notifikasi
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>
        </div>

        <div class="member-notification-side-stack">
            <div class="member-notification-card">
            <x-filament::section>
                <x-slot name="heading">
                    Catatan Pengiriman
                </x-slot>

                <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                    <li>Member tetap menerima pesan di inbox aplikasi meskipun Firebase belum aktif.</li>
                    <li>Push notification membutuhkan FCM token dari perangkat member.</li>
                    <li>Jika member logout atau token Firebase tidak valid, push bisa gagal tetapi inbox tetap tersimpan.</li>
                    <li>Gunakan pesan singkat agar nyaman dibaca dari notifikasi HP.</li>
                </ul>
            </x-filament::section>
            </div>

            @if ($sentCount > 0)
                <div class="member-notification-card">
                <x-filament::section>
                    <x-slot name="heading">
                        Pengiriman Terakhir
                    </x-slot>

                    <p class="text-3xl font-bold text-primary-600">{{ $sentCount }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">member menerima notifikasi terakhir.</p>
                </x-filament::section>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
