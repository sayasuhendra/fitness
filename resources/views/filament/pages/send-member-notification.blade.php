<x-filament-panels::page>
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
        <x-filament::section>
            <x-slot name="heading">
                Pesan Notifikasi
            </x-slot>

            <x-slot name="description">
                Notifikasi akan masuk ke inbox aplikasi member. Jika Firebase sudah aktif dan perangkat member punya FCM token, pesan juga dikirim sebagai push notification.
            </x-slot>

            <form wire:submit="send" class="space-y-5">
                <div class="grid gap-4 md:grid-cols-2">
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
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Link Tujuan di Aplikasi</span>
                    <input
                        type="text"
                        wire:model="actionUrl"
                        placeholder="/notifications"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Contoh: /notifications, /memberships, /classes, /orders.</p>
                    @error('actionUrl')
                        <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                    @enderror
                </label>

                <x-filament::button type="submit" icon="heroicon-m-paper-airplane">
                    Kirim Notifikasi
                </x-filament::button>
            </form>
        </x-filament::section>

        <div class="space-y-6">
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

            @if ($sentCount > 0)
                <x-filament::section>
                    <x-slot name="heading">
                        Pengiriman Terakhir
                    </x-slot>

                    <p class="text-3xl font-bold text-primary-600">{{ $sentCount }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">member menerima notifikasi terakhir.</p>
                </x-filament::section>
            @endif
        </div>
    </div>
</x-filament-panels::page>
