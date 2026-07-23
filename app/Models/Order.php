<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'member_id',
        'handled_by',
        'handled_shift',
        'handled_date',
        'status',
        'payment_method',
        'total_price',
        'payment_reference',
        'delivered_at',
        'delivered_by',
    ];

    protected function casts(): array
    {
        return [
            'handled_date' => 'date',
            'total_price' => 'decimal:2',
            'delivered_at' => 'datetime',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function deliveredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentConfirmations(): MorphMany
    {
        return $this->morphMany(PaymentConfirmation::class, 'payable');
    }
}
