<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class PaymentConfirmation extends Model
{
    protected $fillable = [
        'payable_type',
        'payable_id',
        'member_id',
        'payment_method',
        'amount',
        'status',
        'proof_path',
        'whatsapp_url',
        'member_note',
        'admin_note',
        'verified_by',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'verified_at' => 'datetime',
        ];
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function proofUrl(): ?string
    {
        if ($this->proof_path === null) {
            return null;
        }

        return Storage::disk('public')->url($this->proof_path);
    }
}
