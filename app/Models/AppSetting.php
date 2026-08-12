<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value'];

    protected function casts(): array
    {
        return ['value' => 'array'];
    }

    public static function getArray(string $key, array $default = []): array
    {
        $value = self::query()->where('key', $key)->first()?->value;

        return is_array($value) ? $value : $default;
    }
}
