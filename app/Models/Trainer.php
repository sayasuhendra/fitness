<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TrainerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trainer extends Model
{
    /** @use HasFactory<TrainerFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'specialization', 'bio', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(FitnessClass::class);
    }

    public function personalTrainerSessions(): HasMany
    {
        return $this->hasMany(PersonalTrainerSession::class);
    }
}
