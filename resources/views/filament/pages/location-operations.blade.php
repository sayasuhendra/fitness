<x-filament-panels::page>
    <style>
        .location-operations-stack {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .location-operations-grid {
            display: grid;
            gap: 1.5rem;
            align-items: start;
        }

        .location-operations-card {
            min-width: 0;
        }

        .location-operations-card--wide {
            min-width: 0;
        }

        .location-operations-form-grid {
            display: grid;
            gap: 1rem;
        }

        .location-operations-card label {
            display: block;
        }

        .location-operations-card input,
        .location-operations-card select {
            display: block;
            width: 100%;
            min-height: 2.75rem;
            margin-top: 0.25rem;
            padding: 0.5rem 0.75rem;
            border: 1px solid rgb(209 213 219);
            border-radius: 0.5rem;
            background-color: #ffffff;
            color: rgb(17 24 39);
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .location-operations-card select {
            padding-right: 2.25rem;
        }

        .location-operations-card input:focus,
        .location-operations-card select:focus {
            border-color: rgb(168 85 247);
            outline: 2px solid rgb(168 85 247 / 0.22);
            outline-offset: 1px;
        }

        .location-operations-card select option {
            background-color: #ffffff;
            color: rgb(17 24 39);
        }

        .location-operations-actions {
            margin-top: 1.25rem;
        }

        .location-operations-scanner-stack {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .location-operations-video-container {
            overflow: hidden;
            border-radius: 0.75rem;
            border: 1px solid rgb(229 231 235);
            background-color: #030712;
        }

        .dark .location-operations-video-container {
            border-color: rgb(55 65 81);
        }

        .location-operations-camera-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: center;
        }

        .location-operations-manual-scan {
            margin-top: 0.5rem;
            padding-top: 1rem;
            border-top: 1px solid rgb(229 231 235);
        }

        .dark .location-operations-manual-scan {
            border-top-color: rgb(55 65 81);
        }

        .location-operations-hint {
            margin-top: 0.375rem;
            font-size: 0.75rem;
            color: rgb(107 114 128);
        }

        .dark .location-operations-hint {
            color: rgb(156 163 175);
        }

        .location-operations-checkin-result {
            margin-top: 0.5rem;
            padding: 0.875rem;
            border-radius: 0.5rem;
            border: 1px solid rgb(187 247 208);
            background-color: rgb(240 253 244);
            font-size: 0.875rem;
        }

        .dark .location-operations-checkin-result {
            border-color: rgb(20 83 45);
            background-color: rgb(5 46 22);
            color: rgb(187 247 208);
        }

        .location-operations-order-items {
            margin-top: 1.25rem;
            overflow: hidden;
            border: 1px solid rgb(229 231 235);
            border-radius: 0.75rem;
            background-color: #ffffff;
        }

        .location-operations-order-row {
            display: grid;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            border-top: 1px solid rgb(229 231 235);
        }

        .location-operations-order-row:first-child {
            border-top: 0;
        }

        .location-operations-order-total {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem;
            border-top: 1px solid rgb(229 231 235);
            background-color: rgb(249 250 251);
            font-weight: 700;
        }

        .location-operations-empty-order {
            margin-top: 1.25rem;
            border: 1px dashed rgb(209 213 219);
            border-radius: 0.75rem;
            padding: 1rem;
            color: rgb(107 114 128);
            font-size: 0.875rem;
        }

        .dark .location-operations-card input,
        .dark .location-operations-card select {
            border-color: rgb(55 65 81);
            background-color: rgb(17 24 39);
            color: #ffffff;
        }

        .dark .location-operations-card select option {
            background-color: rgb(17 24 39);
            color: #ffffff;
        }

        .dark .location-operations-order-items {
            border-color: rgb(55 65 81);
            background-color: rgb(17 24 39);
        }

        .dark .location-operations-order-row,
        .dark .location-operations-order-total {
            border-color: rgb(55 65 81);
        }

        .dark .location-operations-order-total {
            background-color: rgb(31 41 55);
        }

        .dark .location-operations-empty-order {
            border-color: rgb(75 85 99);
            color: rgb(156 163 175);
        }

        @media (min-width: 768px) {
            .location-operations-form-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .location-operations-order-row {
                grid-template-columns: minmax(0, 1fr) 5rem 8rem 8rem auto;
                align-items: center;
            }

            .location-operations-orders-toolbar {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }

            .location-operations-orders-search {
                width: 18rem;
            }
        }

        .location-operations-orders-toolbar {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgb(229 231 235);
        }

        .location-operations-orders-filters {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
        }

        .location-operations-orders-search {
            width: 100%;
        }

        .location-operations-table-wrap {
            overflow-x: auto;
            border: 1px solid rgb(229 231 235);
            border-radius: 0.75rem;
            background-color: #ffffff;
        }

        .location-operations-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .location-operations-table thead {
            background-color: rgb(249 250 251);
        }

        .location-operations-table th,
        .location-operations-table td {
            padding: 0.75rem 1rem;
            border-top: 1px solid rgb(229 231 235);
            vertical-align: middle;
        }

        .location-operations-table th {
            border-top: 0;
            color: rgb(55 65 81);
            font-weight: 700;
            text-align: left;
            white-space: nowrap;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .location-operations-table td {
            color: rgb(31 41 55);
        }

        .location-operations-table tbody tr:hover {
            background-color: rgb(249 250 251 / 0.8);
        }

        .location-operations-table .text-right {
            text-align: right;
        }

        .location-operations-member-name {
            font-size: 0.875rem;
            font-weight: 700;
            color: rgb(17 24 39);
        }

        .location-operations-member-time {
            font-size: 0.75rem;
            color: rgb(107 114 128);
            margin-top: 0.125rem;
        }

        .location-operations-order-item-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .location-operations-order-item-row {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.8125rem;
            color: rgb(31 41 55);
        }

        .location-operations-order-item-qty {
            color: rgb(107 114 128);
            font-size: 0.8125rem;
        }

        .location-operations-order-price {
            font-weight: 700;
            color: rgb(17 24 39);
        }

        .location-operations-order-method {
            margin-top: 0.25rem;
        }

        .location-operations-order-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.5rem;
            white-space: nowrap;
        }

        .dark .location-operations-orders-toolbar {
            border-bottom-color: rgb(55 65 81);
        }

        .dark .location-operations-table-wrap {
            border-color: rgb(55 65 81);
            background-color: rgb(17 24 39);
        }

        .dark .location-operations-table thead {
            background-color: rgb(31 41 55);
        }

        .dark .location-operations-table th,
        .dark .location-operations-table td {
            border-color: rgb(55 65 81);
        }

        .dark .location-operations-table th {
            color: rgb(229 231 235);
        }

        .dark .location-operations-table td {
            color: rgb(209 213 219);
        }

        .dark .location-operations-table tbody tr:hover {
            background-color: rgb(31 41 55 / 0.5);
        }

        .dark .location-operations-member-name {
            color: #ffffff;
        }

        .dark .location-operations-member-time {
            color: rgb(156 163 175);
        }

        .dark .location-operations-order-item-row {
            color: rgb(229 231 235);
        }

        .dark .location-operations-order-item-qty {
            color: rgb(156 163 175);
        }

        .dark .location-operations-order-price {
            color: #ffffff;
        }

        @media (min-width: 1280px) {
            .location-operations-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .location-operations-card--wide {
                grid-column: 1 / -1;
            }
        }
    </style>

    <div class="location-operations-stack">
    <div class="rounded-xl border border-primary-200 bg-primary-50 px-4 py-3 text-sm text-primary-800 dark:border-primary-900 dark:bg-primary-950 dark:text-primary-200">
        Anda login sebagai <strong>{{ auth()->user()->name }}</strong>.
        Shift transaksi: <strong>{{ \App\Support\AdminShift::label(\App\Support\AdminShift::forUser(auth()->user())) }}</strong>.
        Semua paket, pesanan produk, dan check-in dari halaman ini otomatis masuk laporan shift hari ini.
    </div>

    <div class="location-operations-grid">
        <div class="location-operations-card">
        <x-filament::section>
            <x-slot name="heading">Daftarkan Member Baru</x-slot>
            <x-slot name="description">Buat akun member langsung dari meja admin lokasi.</x-slot>

            <div class="location-operations-form-grid">
                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Nama Member</span>
                    <input wire:model="memberName" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    @error('memberName') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Email</span>
                    <input wire:model="memberEmail" type="email" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    @error('memberEmail') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Nomor HP</span>
                    <input wire:model="memberPhone" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    @error('memberPhone') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Password Awal</span>
                    <input wire:model="memberPassword" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    @error('memberPassword') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                </label>
            </div>

            <div class="location-operations-actions">
                <x-filament::button wire:click="registerMember" icon="heroicon-m-user-plus">
                    Daftarkan Member
                </x-filament::button>
            </div>
        </x-filament::section>
        </div>

        <div class="location-operations-card">
        <x-filament::section>
            <x-slot name="heading">Daftarkan Paket Member</x-slot>
            <x-slot name="description">Aktifkan paket untuk member yang membayar di lokasi.</x-slot>

            <div class="location-operations-form-grid">
                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Member</span>
                    <select wire:model="packageMemberId" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="">Pilih member</option>
                        @foreach ($this->memberOptions() as $id => $label)
                            <option value="{{ $id }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('packageMemberId') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Paket</span>
                    <select wire:model="packageId" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="">Pilih paket</option>
                        @foreach ($this->packageOptions() as $id => $label)
                            <option value="{{ $id }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('packageId') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Pembayaran</span>
                    <select wire:model="packagePaymentMethod" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="cash">Tunai</option>
                        <option value="qris">QRIS</option>
                        <option value="bank_transfer">Transfer Bank</option>
                    </select>
                </label>
            </div>

            <div class="location-operations-actions">
                <x-filament::button wire:click="sellMembership" icon="heroicon-m-ticket">
                    Aktifkan Paket
                </x-filament::button>
            </div>
        </x-filament::section>
        </div>


        <div class="location-operations-card">
        <x-filament::section>
            <x-slot name="heading">Scan QR Member</x-slot>
            <x-slot name="description">Scan QR member dari aplikasi mobile atau masukkan Kode Member.</x-slot>

            <div
                x-data="locationQrScanner({
                    submit: (payload) => $wire.submitScan(payload),
                })"
                x-init="init()"
                class="location-operations-scanner-stack"
            >
                <div class="location-operations-video-container">
                    <video
                        x-ref="video"
                        class="aspect-video w-full bg-gray-950 object-cover"
                        muted
                        playsinline
                    ></video>
                </div>

                <canvas x-ref="canvas" class="hidden"></canvas>

                <div class="location-operations-camera-actions">
                    <x-filament::button type="button" x-on:click="start()" icon="heroicon-m-camera" size="sm">
                        Mulai Kamera
                    </x-filament::button>

                    <x-filament::button type="button" color="gray" x-on:click="stop()" icon="heroicon-m-stop" size="sm">
                        Stop Kamera
                    </x-filament::button>
                </div>

                <div
                    x-show="message"
                    x-text="message"
                    class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800 dark:border-amber-900 dark:bg-amber-950 dark:text-amber-200"
                ></div>

                <div class="location-operations-manual-scan">
                    <label class="block">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Input Kode Member / Scanner Gun</span>
                        <input
                            x-model="manualInput"
                            x-on:keydown.enter.prevent="if (manualInput.trim()) { $wire.submitScan(manualInput.trim()); manualInput = ''; }"
                            type="text"
                            placeholder="Contoh: F - 001"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                        />
                    </label>
                    <p class="location-operations-hint">
                        Tempel payload QR atau ketik kode member (misal: F - 001) lalu tekan Enter atau klik tombol di bawah.
                    </p>
                </div>

                <div class="location-operations-actions">
                    <x-filament::button
                        type="button"
                        x-on:click="if (manualInput.trim()) { $wire.submitScan(manualInput.trim()); manualInput = ''; }"
                        icon="heroicon-m-qr-code"
                    >
                        Check-In Member
                    </x-filament::button>
                </div>

                @if ($lastCheckIn)
                    <div class="location-operations-checkin-result">
                        <div class="font-semibold text-green-800 dark:text-green-300 flex items-center gap-1.5">
                            <x-filament::icon icon="heroicon-m-check-badge" class="w-4 h-4 text-green-600" />
                            Check-In Berhasil!
                        </div>
                        <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                            <div><span class="text-gray-500">Member:</span> <strong>{{ $lastCheckIn['member'] }}</strong></div>
                            <div><span class="text-gray-500">Kode:</span> <strong>{{ $lastCheckIn['member_code'] }}</strong></div>
                            <div><span class="text-gray-500">Waktu:</span> {{ $lastCheckIn['time'] }}</div>
                            <div><span class="text-gray-500">Sesi:</span> {{ $lastCheckIn['sessions_count'] }} sesi</div>
                        </div>
                    </div>
                @endif
            </div>
        </x-filament::section>
        </div>

        <div class="location-operations-card">
        <x-filament::section>
            <x-slot name="heading">Check-In Manual</x-slot>
            <x-slot name="description">Pilih Gym untuk check-in member langsung. Untuk Kelas atau Personal Trainer, pilih booking/sesi yang sudah terjadwal.</x-slot>

            <div class="location-operations-form-grid">
                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Jenis Check-In</span>
                    <select wire:model.live="checkInType" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="gym_visit">Gym</option>
                        <option value="class_attendance">Kelas</option>
                        <option value="personal_trainer_session">Personal Trainer</option>
                    </select>
                    @error('checkInType') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                </label>

                @if ($checkInType === 'gym_visit')
                    <label class="block">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Member</span>
                        <select wire:model="checkInMemberId" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="">Pilih member</option>
                            @foreach ($this->memberOptions() as $id => $label)
                                <option value="{{ $id }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('checkInMemberId') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                    </label>
                @endif

                @if ($checkInType === 'class_attendance')
                    <label class="block">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Booking Kelas</span>
                        <select wire:model="checkInClassBookingId" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="">Pilih booking kelas</option>
                            @foreach ($this->classBookingOptions() as $id => $label)
                                <option value="{{ $id }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('checkInClassBookingId') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                    </label>
                @endif

                @if ($checkInType === 'personal_trainer_session')
                    <label class="block">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Sesi Personal Trainer</span>
                        <select wire:model="checkInPersonalTrainerSessionId" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="">Pilih sesi personal trainer</option>
                            @foreach ($this->personalTrainerSessionOptions() as $id => $label)
                                <option value="{{ $id }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('checkInPersonalTrainerSessionId') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                    </label>
                @endif

                @if ($checkInType !== 'gym_visit')
                    <label class="block">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Jumlah Sesi</span>
                        <select wire:model="checkInSessionUnits" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="1">1 sesi</option>
                            <option value="2">2 sesi sekaligus</option>
                        </select>
                        @error('checkInSessionUnits') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                    </label>
                @endif

                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Lokasi</span>
                    <input wire:model="checkInLocation" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    @error('checkInLocation') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                </label>
            </div>

            <div class="location-operations-actions">
                <x-filament::button wire:click="manualCheckIn" icon="heroicon-m-check-circle">
                    Check-In Sekarang
                </x-filament::button>
            </div>
        </x-filament::section>
        </div>

        <div class="location-operations-card location-operations-card--wide">
        <x-filament::section>
            <x-slot name="heading">Pesan Produk</x-slot>
            <x-slot name="description">Buat pesanan produk dari admin lokasi. Stok langsung berkurang.</x-slot>

            <div class="location-operations-form-grid">
                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Member</span>
                    <select wire:model="orderMemberId" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="">Pilih member...</option>
                        @foreach ($this->memberOptions() as $id => $label)
                            <option value="{{ $id }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('orderMemberId') <span class="text-sm text-danger-600 block mt-1">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Pembayaran</span>
                    <select wire:model="orderPaymentMethod" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="cash">Tunai</option>
                        <option value="qris">QRIS</option>
                        <option value="bank_transfer">Transfer Bank</option>
                    </select>
                    @error('orderPaymentMethod') <span class="text-sm text-danger-600 block mt-1">{{ $message }}</span> @enderror
                </label>
            </div>

            <div class="location-operations-form-grid" style="margin-top: 1rem;">
                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Produk</span>
                    <select wire:model="productId" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="">Pilih produk</option>
                        @foreach ($this->productOptions() as $id => $label)
                            <option value="{{ $id }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('productId') <span class="text-sm text-danger-600 block mt-1">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Jumlah</span>
                    <input wire:model="productQuantity" type="number" min="1" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    @error('productQuantity') <span class="text-sm text-danger-600 block mt-1">{{ $message }}</span> @enderror
                </label>
            </div>

            <div class="location-operations-actions">
                <x-filament::button wire:click="addProductToOrder" icon="heroicon-m-plus">
                    Tambah ke Pesanan
                </x-filament::button>
            </div>

            @if ($orderItems === [])
                <div class="location-operations-empty-order">
                    Belum ada produk di pesanan. Pilih produk dan jumlah di atas, lalu klik <strong>Tambah ke Pesanan</strong>.
                </div>
            @else
                <div class="location-operations-order-items">
                    @foreach ($orderItems as $index => $item)
                        <div class="location-operations-order-row" wire:key="order-item-{{ $item['product_id'] }}">
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Stok tersedia: {{ $item['stock'] }}
                                </div>
                            </div>
                            <div class="text-sm text-gray-700 dark:text-gray-200">
                                {{ $item['quantity'] }} pcs
                            </div>
                            <div class="text-sm text-gray-700 dark:text-gray-200">
                                Rp {{ number_format((float) $item['price'], 0, ',', '.') }}
                            </div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                Rp {{ number_format((float) $item['price'] * (int) $item['quantity'], 0, ',', '.') }}
                            </div>
                            <div>
                                <x-filament::button
                                    wire:click="removeProductFromOrder({{ $index }})"
                                    color="danger"
                                    size="sm"
                                    icon="heroicon-m-trash"
                                >
                                    Hapus
                                </x-filament::button>
                            </div>
                        </div>
                    @endforeach

                    <div class="location-operations-order-total">
                        <span>Total Pesanan</span>
                        <span>Rp {{ number_format($this->orderItemsTotal(), 0, ',', '.') }}</span>
                    </div>
                </div>
            @endif

            <div class="location-operations-actions">
                <x-filament::button wire:click="sellProduct" icon="heroicon-m-shopping-bag">
                    Buat Pesanan Produk
                </x-filament::button>
            </div>
        </x-filament::section>
        </div>
    </div>

    <!-- Manajemen Pesanan Hari Ini (Belum Selesai) -->
    <div class="location-operations-card--wide" wire:poll.15s>
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <span class="flex items-center gap-2">
                        <x-filament::icon icon="heroicon-m-clipboard-document-list" class="h-6 w-6 text-primary-600 dark:text-primary-400" />
                        <span>Manajemen Pesanan Hari Ini (Belum Selesai)</span>
                    </span>
                    <span class="text-xs font-normal text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <span class="inline-block h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Auto-update tiap 15 detik
                    </span>
                </div>
            </x-slot>
            <x-slot name="description">
                Pantau dan proses pesanan produk hari ini dari aplikasi member maupun kasir lokasi yang membutuhkan pembayaran atau penyerahan barang.
            </x-slot>

            @php
                $stats = $this->todayOrdersStats;
                $todayOrders = $this->getTodayOrders();
            @endphp

            <!-- Filter Badges & Search Bar -->
            <div class="location-operations-orders-toolbar">
                <!-- Filter Pills -->
                <div class="location-operations-orders-filters">
                    <x-filament::button
                        type="button"
                        size="sm"
                        wire:click="setTodayOrdersFilter('incomplete')"
                        :color="$todayOrdersFilter === 'incomplete' ? 'primary' : 'gray'"
                        :outlined="$todayOrdersFilter !== 'incomplete'"
                        :badge="$stats['total_incomplete'] > 0 ? (string) $stats['total_incomplete'] : null"
                        :badge-color="$todayOrdersFilter === 'incomplete' ? 'white' : 'warning'"
                    >
                        Belum Selesai
                    </x-filament::button>

                    <x-filament::button
                        type="button"
                        size="sm"
                        wire:click="setTodayOrdersFilter('pending_payment')"
                        :color="$todayOrdersFilter === 'pending_payment' ? 'primary' : 'gray'"
                        :outlined="$todayOrdersFilter !== 'pending_payment'"
                        :badge="$stats['pending_payment'] > 0 ? (string) $stats['pending_payment'] : null"
                        :badge-color="$todayOrdersFilter === 'pending_payment' ? 'white' : 'danger'"
                    >
                        Menunggu Bayar
                    </x-filament::button>

                    <x-filament::button
                        type="button"
                        size="sm"
                        wire:click="setTodayOrdersFilter('ready_to_deliver')"
                        :color="$todayOrdersFilter === 'ready_to_deliver' ? 'primary' : 'gray'"
                        :outlined="$todayOrdersFilter !== 'ready_to_deliver'"
                        :badge="$stats['ready_to_deliver'] > 0 ? (string) $stats['ready_to_deliver'] : null"
                        :badge-color="$todayOrdersFilter === 'ready_to_deliver' ? 'white' : 'info'"
                    >
                        Siap Diserahkan
                    </x-filament::button>

                    <x-filament::button
                        type="button"
                        size="sm"
                        wire:click="setTodayOrdersFilter('completed')"
                        :color="$todayOrdersFilter === 'completed' ? 'primary' : 'gray'"
                        :outlined="$todayOrdersFilter !== 'completed'"
                        :badge="$stats['completed'] > 0 ? (string) $stats['completed'] : null"
                        :badge-color="$todayOrdersFilter === 'completed' ? 'white' : 'success'"
                    >
                        Sudah Selesai
                    </x-filament::button>

                    <x-filament::button
                        type="button"
                        size="sm"
                        wire:click="setTodayOrdersFilter('all_today')"
                        :color="$todayOrdersFilter === 'all_today' ? 'primary' : 'gray'"
                        :outlined="$todayOrdersFilter !== 'all_today'"
                    >
                        Semua Hari Ini
                    </x-filament::button>
                </div>

                <!-- Search Input -->
                <div class="location-operations-orders-search">
                    <x-filament::input.wrapper prefix-icon="heroicon-m-magnifying-glass">
                        <x-filament::input
                            wire:model.live.debounce.300ms="todayOrdersSearch"
                            type="text"
                            placeholder="Cari nama member..."
                        />
                    </x-filament::input.wrapper>
                </div>
            </div>

            <!-- Orders List -->
            @if ($todayOrders->isEmpty())
                <div class="py-12 text-center text-gray-500 dark:text-gray-400">
                    <x-filament::icon icon="heroicon-o-check-badge" class="mx-auto h-12 w-12 text-emerald-500 mb-3" />
                    <p class="text-base font-semibold text-gray-800 dark:text-gray-200">
                        @if ($todayOrdersFilter === 'incomplete')
                            Tidak ada pesanan belum selesai hari ini.
                        @else
                            Tidak ada pesanan yang sesuai filter saat ini.
                        @endif
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Pesanan baru dari aplikasi member atau kasir lokasi akan muncul di sini secara otomatis.
                    </p>
                </div>
            @else
                <div class="location-operations-table-wrap">
                    <table class="location-operations-table">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Item Produk</th>
                                <th>Total & Metode</th>
                                <th>Status Bayar</th>
                                <th>Penyerahan</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($todayOrders as $order)
                                <tr wire:key="today-order-{{ $order->id }}">
                                    <!-- Kolom Member: Cukup Nama dan Jam -->
                                    <td class="whitespace-nowrap">
                                        <div class="location-operations-member-name">
                                            {{ $order->member?->user?->name ?? 'Member #' . $order->member_id }}
                                        </div>
                                        <div class="location-operations-member-time">
                                            {{ $order->created_at->timezone(config('app.timezone'))->format('H:i') }} WIB
                                        </div>
                                    </td>
                                    <td>
                                        <ul class="location-operations-order-item-list">
                                            @foreach ($order->items as $item)
                                                <li class="location-operations-order-item-row">
                                                    <span style="font-weight: 500;">{{ $item->product?->name ?? 'Produk #' . $item->product_id }}</span>
                                                    <span class="location-operations-order-item-qty">&times; {{ $item->quantity }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <div class="location-operations-order-price">
                                            Rp {{ number_format((float) $order->total_price, 0, ',', '.') }}
                                        </div>
                                        <div class="location-operations-order-method">
                                            <x-filament::badge size="sm" color="gray">
                                                {{ match ($order->payment_method) {
                                                    'qris' => 'QRIS',
                                                    'bank_transfer' => 'Transfer Bank',
                                                    'cash' => 'Tunai',
                                                    'manual_transfer' => 'Transfer Manual',
                                                    default => strtoupper($order->payment_method),
                                                } }}
                                            </x-filament::badge>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <x-filament::badge
                                            size="sm"
                                            :color="match ($order->status) {
                                                'paid', 'completed' => 'success',
                                                'cancelled' => 'danger',
                                                default => 'warning',
                                            }"
                                        >
                                            {{ match ($order->status) {
                                                'pending_payment' => 'Menunggu Bayar',
                                                'paid' => 'Sudah Dibayar',
                                                'completed' => 'Selesai',
                                                'cancelled' => 'Dibatalkan',
                                                default => $order->status,
                                            } }}
                                        </x-filament::badge>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        @if ($order->delivered_at)
                                            <x-filament::badge size="sm" color="success" icon="heroicon-m-check">
                                                Sudah ({{ $order->delivered_at->timezone(config('app.timezone'))->format('H:i') }})
                                            </x-filament::badge>
                                        @else
                                            <x-filament::badge size="sm" color="warning" icon="heroicon-m-clock">
                                                Belum Diserahkan
                                            </x-filament::badge>
                                        @endif
                                    </td>
                                    <td class="text-right whitespace-nowrap">
                                        <div class="location-operations-order-actions">
                                            @if ($order->status === 'pending_payment')
                                                <x-filament::button
                                                    wire:click="markOrderAsPaid({{ $order->id }})"
                                                    wire:confirm="Tandai pesanan #{{ $order->id }} sudah dibayar?"
                                                    size="sm"
                                                    color="success"
                                                    icon="heroicon-m-banknotes"
                                                >
                                                    Terima Bayar
                                                </x-filament::button>
                                            @endif

                                            @if ($order->delivered_at === null && in_array($order->status, ['paid', 'completed'], true))
                                                <x-filament::button
                                                    wire:click="deliverOrder({{ $order->id }})"
                                                    wire:confirm="Catat produk pesanan #{{ $order->id }} sudah diserahkan ke member?"
                                                    size="sm"
                                                    color="primary"
                                                    icon="heroicon-m-hand-thumb-up"
                                                >
                                                    Serahkan
                                                </x-filament::button>
                                            @endif

                                            @if ($order->status === 'paid')
                                                <x-filament::button
                                                    wire:click="completeOrder({{ $order->id }})"
                                                    size="sm"
                                                    color="gray"
                                                    icon="heroicon-m-check"
                                                >
                                                    Selesai
                                                </x-filament::button>
                                            @endif

                                            @if (in_array($order->status, ['pending_payment', 'paid'], true))
                                                <x-filament::button
                                                    wire:click="cancelOrder({{ $order->id }})"
                                                    wire:confirm="Batalkan pesanan #{{ $order->id }}? Stok produk akan dikembalikan."
                                                    size="sm"
                                                    color="danger"
                                                    icon="heroicon-m-x-circle"
                                                >
                                                    Batal
                                                </x-filament::button>
                                            @endif

                                            <x-filament::icon-button
                                                :href="\App\Filament\Resources\Orders\OrderResource::getUrl('view', ['record' => $order])"
                                                target="_blank"
                                                tag="a"
                                                color="gray"
                                                icon="heroicon-m-arrow-top-right-on-square"
                                                tooltip="Lihat Detail Pesanan"
                                                size="sm"
                                            />
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-filament::section>
    </div>
    </div>

    <script>
        function initLocationQrScanner() {
            if (window.Alpine && !window.Alpine.data('locationQrScanner')) {
                Alpine.data('locationQrScanner', ({ submit }) => ({
                    detector: null,
                    stream: null,
                    scanning: false,
                    lastPayload: null,
                    message: '',
                    manualInput: '',

                    async init() {
                        if (!('BarcodeDetector' in window)) {
                            this.message = 'Browser belum mendukung deteksi kamera otomatis. Gunakan Chrome Android / browser kompatibel, atau gunakan input kode / barcode scanner gun di bawah.';
                            return;
                        }

                        this.detector = new BarcodeDetector({ formats: ['qr_code'] });
                    },

                    async start() {
                        if (!this.detector) {
                            this.message = 'Scanner kamera tidak tersedia di browser ini. Gunakan input kode / scanner gun di bawah.';
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
                            this.message = 'Kamera tidak bisa dibuka. Pastikan izin kamera diberikan dan akses web menggunakan HTTPS.';
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
                        if (!this.scanning || !this.detector) {
                            return;
                        }

                        try {
                            const barcodes = await this.detector.detect(this.$refs.video);

                            if (barcodes.length > 0) {
                                const rawValue = barcodes[0].rawValue;

                                if (rawValue && rawValue !== this.lastPayload) {
                                    this.lastPayload = rawValue;
                                    submit(rawValue);
                                    this.stop();
                                    return;
                                }
                            }
                        } catch (error) {
                            // ignore dropped frames
                        }

                        if (this.scanning) {
                            requestAnimationFrame(() => this.scanLoop());
                        }
                    },
                }));
            }
        }

        if (window.Alpine) {
            initLocationQrScanner();
        } else {
            document.addEventListener('alpine:init', initLocationQrScanner);
        }
    </script>
</x-filament-panels::page>
