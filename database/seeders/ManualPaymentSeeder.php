<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\QrisPaymentMethod;
use Illuminate\Database\Seeder;

class ManualPaymentSeeder extends Seeder
{
    public function run(): void
    {
        BankAccount::query()->updateOrCreate(
            ['bank_name' => 'BCA', 'account_number' => '1234567890'],
            [
                'account_name' => 'Akhwat Gym',
                'instructions' => 'Transfer sesuai nominal transaksi, lalu upload bukti pembayaran dari aplikasi.',
                'sort_order' => 1,
                'is_active' => true,
            ],
        );

        BankAccount::query()->updateOrCreate(
            ['bank_name' => 'BSI', 'account_number' => '9876543210'],
            [
                'account_name' => 'Akhwat Gym',
                'instructions' => 'Gunakan berita transfer berisi nama member agar admin mudah mencocokkan pembayaran.',
                'sort_order' => 2,
                'is_active' => true,
            ],
        );

        QrisPaymentMethod::query()->updateOrCreate(
            ['name' => 'QRIS Akhwat Gym'],
            [
                'image_path' => 'payment/qris/demo-qris.svg',
                'instructions' => 'Scan QRIS, lakukan pembayaran, lalu download/simpan bukti transaksi untuk diupload ke aplikasi.',
                'sort_order' => 1,
                'is_active' => true,
            ],
        );

        $this->command?->info('Manual payment methods are ready.');
    }
}
