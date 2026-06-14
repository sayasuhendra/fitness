<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\MembershipPurchaseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembershipPurchase extends Model
{
    /** @use HasFactory<MembershipPurchaseFactory> */
    use HasFactory;

    protected $fillable = [
        'member_id',
        'membership_package_id',
        'starts_at',
        'expires_at',
        'status',
        'payment_method',
        'amount',
        'payment_reference',
    ];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime', 'expires_at' => 'datetime', 'amount' => 'decimal:2'];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(MembershipPackage::class, 'membership_package_id');
    }
}
