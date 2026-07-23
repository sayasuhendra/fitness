<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Filter Periode</x-slot>
        <div class="grid gap-4 md:grid-cols-[220px_220px_auto] md:items-end">
            <label class="block">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Dari Tanggal</span>
                <input wire:model.live="dateFrom" type="date" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
            </label>
            <label class="block">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Sampai Tanggal</span>
                <input wire:model.live="dateTo" type="date" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
            </label>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Menampilkan pendapatan paket dan penjualan produk yang ditangani admin lokasi.
            </p>
        </div>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">Pendapatan per Shift</x-slot>

        <div wire:poll.15s class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
                <thead>
                    <tr class="text-left font-semibold text-gray-700 dark:text-gray-200">
                        <th class="px-3 py-2">Tanggal</th>
                        <th class="px-3 py-2">Shift</th>
                        <th class="px-3 py-2">Admin</th>
                        <th class="px-3 py-2 text-right">Paket</th>
                        <th class="px-3 py-2 text-right">Produk</th>
                        <th class="px-3 py-2 text-right">Profit Produk</th>
                        <th class="px-3 py-2 text-right">Total Omzet</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($this->rows() as $row)
                        <tr>
                            <td class="px-3 py-2">{{ $row['date'] }}</td>
                            <td class="px-3 py-2">{{ $row['shift_label'] }}</td>
                            <td class="px-3 py-2">{{ $row['admin'] }}</td>
                            <td class="px-3 py-2 text-right">{{ $this->money($row['membership_revenue']) }}</td>
                            <td class="px-3 py-2 text-right">{{ $this->money($row['store_revenue']) }}</td>
                            <td class="px-3 py-2 text-right">{{ $this->money($row['store_profit']) }}</td>
                            <td class="px-3 py-2 text-right font-semibold">{{ $this->money($row['total_revenue']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-8 text-center text-gray-500">
                                Belum ada transaksi admin lokasi pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>
