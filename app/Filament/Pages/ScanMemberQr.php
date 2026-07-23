<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Actions\Attendance\CheckInMemberAction;
use App\Models\Attendance;
use App\Models\Member;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use Throwable;
use UnitEnum;

class ScanMemberQr extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::QrCode;

    protected static ?string $navigationLabel = 'Scan QR Member';

    protected static string|UnitEnum|null $navigationGroup = 'Classes';

    protected static ?int $navigationSort = 29;

    protected string $view = 'filament.pages.scan-member-qr';

    public string $location = 'Akhwat Gym Studio';

    /**
     * @var array<string, string|int|null>|null
     */
    public ?array $lastCheckIn = null;

    public function getTitle(): string|Htmlable
    {
        return 'Scan QR Member';
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('create', Attendance::class) ?? false;
    }

    public function submitScan(string $qrPayload): void
    {
        $qrPayload = trim($qrPayload);

        if ($qrPayload === '') {
            Notification::make()
                ->title('QR belum terbaca')
                ->body('Arahkan kamera ke QR member atau tempel payload QR secara manual.')
                ->warning()
                ->send();

            return;
        }

        try {
            $payload = json_decode(Crypt::decryptString($qrPayload), true, 512, JSON_THROW_ON_ERROR);
            $expiresAt = Carbon::parse($payload['expires_at'] ?? null);

            if ($expiresAt->isPast()) {
                throw ValidationException::withMessages([
                    'qr_payload' => 'QR member sudah kedaluwarsa. Minta member membuka ulang halaman QR.',
                ]);
            }

            $member = Member::query()
                ->with('user')
                ->findOrFail((int) ($payload['member_id'] ?? 0));

            $attendance = app(CheckInMemberAction::class)->execute(
                member: $member,
                attendanceType: 'gym_visit',
                location: $this->location,
                admin: auth()->user(),
            );

            $this->lastCheckIn = [
                'id' => $attendance->id,
                'member' => $member->user?->name ?? "Member #{$member->id}",
                'member_code' => $member->member_code,
                'time' => $attendance->check_in_time->timezone(config('app.timezone'))->format('d M Y H:i'),
                'location' => $attendance->location,
            ];

            Notification::make()
                ->title('Check-in berhasil')
                ->body(($this->lastCheckIn['member'] ?? 'Member').' sudah tercatat hadir.')
                ->success()
                ->send();
        } catch (ValidationException $exception) {
            Notification::make()
                ->title('Check-in ditolak')
                ->body(collect($exception->errors())->flatten()->first() ?? 'Data check-in tidak valid.')
                ->danger()
                ->send();
        } catch (Throwable) {
            Notification::make()
                ->title('QR tidak valid')
                ->body('Pastikan member membuka QR dari aplikasi Akhwat Gym dan QR belum kedaluwarsa.')
                ->danger()
                ->send();
        }
    }
}
