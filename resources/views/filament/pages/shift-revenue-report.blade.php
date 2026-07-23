<x-filament-panels::page>
    <style>
        .shift-report-stack {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .shift-report-card {
            min-width: 0;
        }

        .shift-report-filter-grid {
            display: grid;
            gap: 1rem;
            align-items: end;
        }

        .shift-report-card label {
            display: block;
        }

        .shift-report-card input {
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

        .shift-report-card input:focus {
            border-color: rgb(168 85 247);
            outline: 2px solid rgb(168 85 247 / 0.22);
            outline-offset: 1px;
        }

        .shift-report-table-wrap {
            overflow-x: auto;
            border: 1px solid rgb(229 231 235);
            border-radius: 0.75rem;
        }

        .shift-report-table {
            min-width: 56rem;
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .shift-report-table thead {
            background-color: rgb(249 250 251);
        }

        .shift-report-table th,
        .shift-report-table td {
            padding: 0.75rem 1rem;
            border-top: 1px solid rgb(229 231 235);
        }

        .shift-report-table th {
            border-top: 0;
            color: rgb(55 65 81);
            font-weight: 700;
            text-align: left;
            white-space: nowrap;
        }

        .shift-report-table td {
            color: rgb(31 41 55);
        }

        .shift-report-table .text-right {
            text-align: right;
        }

        .dark .shift-report-card input {
            border-color: rgb(55 65 81);
            background-color: rgb(17 24 39);
            color: #ffffff;
        }

        .dark .shift-report-table-wrap {
            border-color: rgb(55 65 81);
        }

        .dark .shift-report-table thead {
            background-color: rgb(31 41 55);
        }

        .dark .shift-report-table th,
        .dark .shift-report-table td {
            border-color: rgb(55 65 81);
        }

        .dark .shift-report-table th {
            color: rgb(229 231 235);
        }

        .dark .shift-report-table td {
            color: rgb(209 213 219);
        }

        @media (min-width: 768px) {
            .shift-report-filter-grid {
                grid-template-columns: 220px 220px minmax(0, 1fr);
            }
        }
    </style>

    <div class="shift-report-stack">
        <div class="shift-report-card">
            <x-filament::section>
                <x-slot name="heading">Filter Periode</x-slot>
                <div class="shift-report-filter-grid">
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
        </div>

        <div class="shift-report-card">
            <x-filament::section>
                <x-slot name="heading">Pendapatan per Shift</x-slot>

                <div wire:poll.15s class="shift-report-table-wrap">
                    <table class="shift-report-table">
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
        </div>
    </div>
</x-filament-panels::page>
