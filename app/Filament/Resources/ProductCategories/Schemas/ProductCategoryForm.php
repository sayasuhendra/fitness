<?php

namespace App\Filament\Resources\ProductCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Details')
                    ->schema([
                        TextInput::make('name')
                            ->placeholder('Healthy Food')
                            ->required()
                            ->maxLength(120),
                        TextInput::make('slug')
                            ->placeholder('healthy-food')
                            ->required()
                            ->maxLength(120),
                    ])
                    ->columns(2),
            ]);
    }
}
