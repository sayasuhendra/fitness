<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Carbon;

final class AdminShift
{
    public const SHIFT_1 = 'shift_1';

    public const SHIFT_2 = 'shift_2';

    public static function forUser(?User $user): string
    {
        if (in_array($user?->admin_shift, [self::SHIFT_1, self::SHIFT_2], true)) {
            return $user->admin_shift;
        }

        $hour = now()->hour;

        return $hour < 13 ? self::SHIFT_1 : self::SHIFT_2;
    }

    public static function date(): Carbon
    {
        return today();
    }

    /**
     * @return array{handled_by:int|null, handled_shift:string|null, handled_date:string|null}
     */
    public static function stamp(?User $user): array
    {
        if ($user === null) {
            return [
                'handled_by' => null,
                'handled_shift' => null,
                'handled_date' => null,
            ];
        }

        return [
            'handled_by' => $user->id,
            'handled_shift' => self::forUser($user),
            'handled_date' => self::date()->toDateString(),
        ];
    }

    public static function label(?string $shift): string
    {
        return match ($shift) {
            self::SHIFT_1 => 'Shift 1 (06:00 - 14:00)',
            self::SHIFT_2 => 'Shift 2 (13:00 - 20:00)',
            default => '-',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::SHIFT_1 => self::label(self::SHIFT_1),
            self::SHIFT_2 => self::label(self::SHIFT_2),
        ];
    }
}
