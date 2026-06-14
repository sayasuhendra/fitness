<?php

namespace App\Filament\Resources\MembershipPackages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MembershipPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Plan Information')
                    ->description('Define who this plan is for and how long it is valid.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(120),
                        Select::make('package_type')
                            ->label('Plan Type')
                            ->options([
                                'membership' => 'Membership',
                                'one_time' => 'One-time Visitor',
                            ])
                            ->default('membership')
                            ->required(),
                        Select::make('billing_cycle')
                            ->label('Billing')
                            ->options([
                                'monthly' => 'Monthly',
                                'yearly' => 'Yearly',
                                'one_time' => 'One Time',
                            ])
                            ->default('monthly')
                            ->required(),
                        Textarea::make('description')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Section::make('Visits & Personal Trainer')
                    ->schema([
                        Toggle::make('includes_personal_trainer')
                            ->label('Includes Personal Trainer')
                            ->default(false),
                        Toggle::make('has_visit_limit')
                            ->label('Limit Visits')
                            ->live()
                            ->default(false),
                        TextInput::make('visit_limit')
                            ->label('Maximum Visits')
                            ->numeric()
                            ->minValue(1)
                            ->visible(fn ($get): bool => (bool) $get('has_visit_limit')),
                        TextInput::make('duration_days')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                    ])
                    ->columns(4),
                Section::make('Price')
                    ->schema([
                        TextInput::make('original_price')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('discount_percent')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->default(0)
                            ->required(),
                        TextInput::make('price')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Available')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(4),
            ]);
    }
}
