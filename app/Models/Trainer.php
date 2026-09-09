<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TrainerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trainer extends Model
{
    /** @use HasFactory<TrainerFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'specialization', 'whatsapp_number', 'bio', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::deleting(function (Trainer $trainer): void {
            if (! $trainer->isForceDeleting()) {
                $trainer->classes()->each(fn (FitnessClass $class) => $class->delete());
                $trainer->classSessions()->each(fn (ClassSession $session) => $session->delete());
            }
        });

        static::restoring(function (Trainer $trainer): void {
            $trainer->classes()->onlyTrashed()->each(fn (FitnessClass $class) => $class->restore());
            $trainer->classSessions()->onlyTrashed()->each(fn (ClassSession $session) => $session->restore());
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(FitnessClass::class);
    }

    public function classSessions(): HasMany
    {
        return $this->hasMany(ClassSession::class);
    }

    public function personalTrainerSessions(): HasMany
    {
        return $this->hasMany(PersonalTrainerSession::class);
    }
}
