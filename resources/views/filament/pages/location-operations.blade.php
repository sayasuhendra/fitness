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

        @media (min-width: 768px) {
            .location-operations-form-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1280px) {
            .location-operations-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
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
            <x-slot name="heading">Pesan Produk</x-slot>
            <x-slot name="description">Buat pesanan produk dari admin lokasi. Stok langsung berkurang.</x-slot>

            <div class="location-operations-form-grid">
                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Member</span>
                    <select wire:model="orderMemberId" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="">Pilih member</option>
                        @foreach ($this->memberOptions() as $id => $label)
                            <option value="{{ $id }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('orderMemberId') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Produk</span>
                    <select wire:model="productId" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="">Pilih produk</option>
                        @foreach ($this->productOptions() as $id => $label)
                            <option value="{{ $id }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('productId') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Jumlah</span>
                    <input wire:model="productQuantity" type="number" min="1" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    @error('productQuantity') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Pembayaran</span>
                    <select wire:model="orderPaymentMethod" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="cash">Tunai</option>
                        <option value="qris">QRIS</option>
                        <option value="bank_transfer">Transfer Bank</option>
                    </select>
                </label>
            </div>

            <div class="location-operations-actions">
                <x-filament::button wire:click="sellProduct" icon="heroicon-m-shopping-bag">
                    Buat Pesanan Produk
                </x-filament::button>
            </div>
        </x-filament::section>
        </div>

        <div class="location-operations-card">
        <x-filament::section>
            <x-slot name="heading">Check-In Manual</x-slot>
            <x-slot name="description">Gunakan saat member tidak membawa HP atau scanner tidak dipakai.</x-slot>

            <div class="location-operations-form-grid">
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
    </div>
    </div>
</x-filament-panels::page>
