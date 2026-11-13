<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\MemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    /** @use HasFactory<MemberFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'member_code', 'joined_at'];

    protected function casts(): array
    {
        return ['joined_at' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function membershipPurchases(): HasMany
    {
        return $this->hasMany(MembershipPurchase::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(ClassBooking::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function personalTrainerSessions(): HasMany
    {
        return $this->hasMany(PersonalTrainerSession::class);
    }

    public function activeMembership(): ?MembershipPurchase
    {
        return $this->membershipPurchases()
            ->where('status', 'active')
            ->where('expires_at', '>=', now())
            ->latest('expires_at')
            ->first();
    }

    public static function nextMemberCode(): string
    {
        $maxNumber = (int) static::query()->max('id');

        $codes = static::query()
            ->where('member_code', 'LIKE', 'MBR%')
            ->pluck('member_code');

        foreach ($codes as $code) {
            if (preg_match('/^MBR(\d+)$/i', (string) $code, $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxNumber) {
                    $maxNumber = $num;
                }
            }
        }

        $candidateNumber = $maxNumber + 1;

        do {
            $code = 'MBR'.str_pad((string) $candidateNumber, 6, '0', STR_PAD_LEFT);
            $candidateNumber++;
        } while (static::query()->where('member_code', $code)->exists());

        return $code;
    }
}
