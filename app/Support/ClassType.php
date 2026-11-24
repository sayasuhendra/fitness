<?php

declare(strict_types=1);

namespace App\Support;

class ClassType
{
    public const GYM = 'gym';
    public const PERSONAL_TRAINER = 'personal_trainer';
    public const ZUMBA = 'zumba';
    public const ZUMBA_GOLD = 'zumba_gold';
    public const AEROBIC = 'aerobic';
    public const AEROMIX = 'aeromix';
    public const FITDANCE = 'fitdance';
    public const BOMIYA = 'bomiya';
    public const CIRCUIT_TRAINING = 'circuit_training';
    public const SALSATION = 'salsation';
    public const YOGA = 'yoga';
    public const PRENATAL_YOGA = 'prenatal_yoga';
    public const POUNDFIT = 'poundfit';
    public const PILATES = 'pilates';
    public const STRENGTH = 'strength';
    public const BODY_FAT = 'body_fat';
    public const GENERAL = 'general';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::ZUMBA => 'Zumba',
            self::ZUMBA_GOLD => 'Zumba Gold',
            self::AEROBIC => 'Aerobic',
            self::AEROMIX => 'Aeromix',
            self::FITDANCE => 'Fitdance',
            self::BOMIYA => 'Bomiya',
            self::CIRCUIT_TRAINING => 'Circuit Training',
            self::SALSATION => 'Salsation',
            self::YOGA => 'Yoga',
            self::PRENATAL_YOGA => 'Yoga Prenatal',
            self::POUNDFIT => 'Poundfit',
            self::PILATES => 'Pilates',
            self::STRENGTH => 'Strength',
            self::GYM => 'Gym',
            self::PERSONAL_TRAINER => 'Personal Trainer',
            self::BODY_FAT => 'Body Fat',
            self::GENERAL => 'General (Umum)',
        ];
    }

    public static function label(?string $type): string
    {
        return static::options()[$type] ?? ucfirst(str_replace('_', ' ', (string) $type));
    }
}
