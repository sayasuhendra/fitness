<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class QrisPaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'image_path',
        'instructions',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function imageUrl(): string
    {
        return Storage::disk('public')->url($this->image_path);
    }
}
