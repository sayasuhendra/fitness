<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\ClassBooking;
use App\Models\ClassSession;
use App\Models\FitnessClass;
use App\Models\PaymentConfirmation;
use App\Models\PersonalTrainer;
use App\Models\PersonalTrainerSession;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteInstructorsPermanently extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instructors:delete-permanent 
                            {--force : Bypass confirmation prompt} 
                            {--dry-run : Simulate deletion without modifying database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete specific instructors and their dependent classes, sessions, bookings, and attendances';

    /**
     * Target instructor names to permanently delete.
     *
     * @var array<int, string>
     */
    protected array $targetNames = [
        'Zin Dewi',
        'Fatimah Trainer',
        'Sohendra Test',
        'trainer contoh',
        'asal',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $isForce = (bool) $this->option('force');

        $this->info('====================================================');
        $this->info('  PERMANENT INSTRUCTOR DELETION TOOL');
        if ($isDryRun) {
            $this->warn('  [DRY-RUN MODE] No data will be modified.');
        }
        $this->info('====================================================');

        $itemsToDelete = [];

        foreach ($this->targetNames as $name) {
            $normalizedName = strtolower(trim($name));

            $users = User::query()
                ->where(function ($query) use ($normalizedName): void {
                    $query->whereRaw('LOWER(TRIM(name)) = ?', [$normalizedName])
                        ->orWhereRaw('LOWER(name) LIKE ?', ['%' . $normalizedName . '%']);
                })
                ->get();

            if ($users->isEmpty()) {
                $this->line("<comment>• '{$name}'</comment>: tidak ditemukan user/instruktur yang cocok.");
                continue;
            }

            foreach ($users as $user) {
                // Check trainer record
                $trainer = Trainer::withTrashed()->where('user_id', $user->id)->first();
                $personalTrainer = PersonalTrainer::where('user_id', $user->id)->first();

                $classIds = [];
                $sessionIds = [];
                $bookingCount = 0;
                $attendanceCount = 0;

                if ($trainer !== null) {
                    $classIds = FitnessClass::withTrashed()->where('trainer_id', $trainer->id)->pluck('id')->all();
                    $sessionIds = ClassSession::withTrashed()
                        ->where('trainer_id', $trainer->id)
                        ->orWhereIn('fitness_class_id', $classIds)
                        ->pluck('id')
                        ->all();

                    $bookingCount = ClassBooking::whereIn('fitness_class_id', $classIds)
                        ->orWhereIn('class_session_id', $sessionIds)
                        ->count();

                    $attendanceCount = Attendance::whereIn('fitness_class_id', $classIds)
                        ->orWhereIn('class_session_id', $sessionIds)
                        ->count();
                }

                $hasMemberHistory = false;
                if ($user->member !== null) {
                    $hasMemberHistory = $user->member->orders()->exists() || $user->member->membershipPurchases()->exists();
                }

                $itemsToDelete[] = [
                    'target_query' => $name,
                    'user' => $user,
                    'trainer' => $trainer,
                    'personal_trainer' => $personalTrainer,
                    'class_ids' => $classIds,
                    'session_ids' => $sessionIds,
                    'booking_count' => $bookingCount,
                    'attendance_count' => $attendanceCount,
                    'delete_user' => ! $hasMemberHistory,
                ];
            }
        }

        if (empty($itemsToDelete)) {
            $this->info('Tidak ada data instruktur yang cocok untuk dihapus.');

            return self::SUCCESS;
        }

        $this->newLine();
        $this->info('Data yang teridentifikasi untuk dihapus secara permanen:');

        $tableRows = [];
        foreach ($itemsToDelete as $item) {
            $user = $item['user'];
            $trainer = $item['trainer'];

            $tableRows[] = [
                'Target' => $item['target_query'],
                'User ID' => $user->id,
                'User Name' => $user->name,
                'Email' => $user->email,
                'Trainer ID' => $trainer ? $trainer->id : '-',
                'Classes' => count($item['class_ids']),
                'Sessions' => count($item['session_ids']),
                'Bookings' => $item['booking_count'],
                'Attendances' => $item['attendance_count'],
                'Hapus User?' => $item['delete_user'] ? 'Ya' : 'Tidak (Ada transaksi member)',
            ];
        }

        $this->table(
            ['Target', 'User ID', 'Name', 'Email', 'Trainer ID', 'Classes', 'Sessions', 'Bookings', 'Attendances', 'Hapus User?'],
            $tableRows
        );

        if ($isDryRun) {
            $this->info('Dry-run selesai. Jalankan tanpa opsi --dry-run untuk benar-benar menghapus.');

            return self::SUCCESS;
        }

        if (! $isForce) {
            if (! $this->confirm('Apakah Anda yakin ingin MENGHAPUS PERMANEN seluruh data di atas? Tindakan ini TIDAK DAPAT DIBATALKAN!', false)) {
                $this->warn('Penghapusan dibatalkan oleh pengguna.');

                return self::SUCCESS;
            }
        }

        $this->newLine();
        $this->info('Memulai proses penghapusan permanen...');

        DB::transaction(function () use ($itemsToDelete): void {
            foreach ($itemsToDelete as $item) {
                $user = $item['user'];
                $trainer = $item['trainer'];
                $personalTrainer = $item['personal_trainer'];
                $classIds = $item['class_ids'];
                $sessionIds = $item['session_ids'];

                $this->line("<comment>• Menghapus instruktur:</comment> {$user->name} (User #{$user->id})");

                // 1. Delete payment confirmations and bookings linked to those classes or sessions
                if (! empty($classIds) || ! empty($sessionIds)) {
                    $bookingIds = ClassBooking::whereIn('fitness_class_id', $classIds)
                        ->orWhereIn('class_session_id', $sessionIds)
                        ->pluck('id')
                        ->all();

                    if (! empty($bookingIds)) {
                        PaymentConfirmation::where('payable_type', ClassBooking::class)
                            ->whereIn('payable_id', $bookingIds)
                            ->delete();

                        ClassBooking::whereIn('id', $bookingIds)->delete();
                        $this->line("  - Terhapus " . count($bookingIds) . ' bookings.');
                    }

                    // 2. Delete attendances linked to those classes or sessions
                    $deletedAttendances = Attendance::whereIn('fitness_class_id', $classIds)
                        ->orWhereIn('class_session_id', $sessionIds)
                        ->delete();
                    if ($deletedAttendances > 0) {
                        $this->line("  - Terhapus {$deletedAttendances} data kehadiran/absensi.");
                    }

                    // 3. Force delete class sessions
                    if (! empty($sessionIds)) {
                        ClassSession::withTrashed()->whereIn('id', $sessionIds)->forceDelete();
                        $this->line("  - Terhapus " . count($sessionIds) . ' sesi kelas.');
                    }

                    // 4. Force delete fitness classes
                    if (! empty($classIds)) {
                        FitnessClass::withTrashed()->whereIn('id', $classIds)->forceDelete();
                        $this->line("  - Terhapus " . count($classIds) . ' jadwal kelas.');
                    }
                }

                // 5. Clean up personal training
                if ($trainer !== null) {
                    $ptSessionIds = PersonalTrainerSession::where('trainer_id', $trainer->id)->pluck('id')->all();
                    if (! empty($ptSessionIds)) {
                        Attendance::whereIn('personal_trainer_session_id', $ptSessionIds)->delete();
                        PaymentConfirmation::where('payable_type', PersonalTrainerSession::class)
                            ->whereIn('payable_id', $ptSessionIds)
                            ->delete();
                        PersonalTrainerSession::whereIn('id', $ptSessionIds)->delete();
                        $this->line('  - Terhapus ' . count($ptSessionIds) . ' sesi personal trainer lama.');
                    }
                }

                if ($personalTrainer !== null) {
                    $ptSessionIds2 = PersonalTrainerSession::where('personal_trainer_id', $personalTrainer->id)->pluck('id')->all();
                    if (! empty($ptSessionIds2)) {
                        Attendance::whereIn('personal_trainer_session_id', $ptSessionIds2)->delete();
                        PaymentConfirmation::where('payable_type', PersonalTrainerSession::class)
                            ->whereIn('payable_id', $ptSessionIds2)
                            ->delete();
                        PersonalTrainerSession::whereIn('id', $ptSessionIds2)->delete();
                        $this->line('  - Terhapus ' . count($ptSessionIds2) . ' sesi personal trainer.');
                    }
                    $personalTrainer->delete();
                    $this->line('  - Terhapus profil personal trainer.');
                }

                // 6. Force delete trainer record
                if ($trainer !== null) {
                    $trainer->forceDelete();
                    $this->line("  - Terhapus record instruktur #{$trainer->id}.");
                }

                // 7. Delete user record if safe
                if ($item['delete_user']) {
                    // Remove member record if empty
                    if ($user->member !== null) {
                        $user->member->delete();
                    }
                    $user->delete();
                    $this->line("  - Terhapus akun user #{$user->id} ({$user->email}).");
                } else {
                    $this->warn("  - Akun user #{$user->id} dipertahankan karena memiliki riwayat order/membership.");
                }
            }
        });

        $this->newLine();
        $this->info('✓ Seluruh data instruktur yang ditentukan berhasil dihapus secara permanen.');

        return self::SUCCESS;
    }
}
