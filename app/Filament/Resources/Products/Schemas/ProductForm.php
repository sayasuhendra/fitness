<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Produk')
                    ->schema([
                        Select::make('product_category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(160),
                        TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        TextInput::make('cost_price')
                            ->label('Harga Modal')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->visible(fn (): bool => auth()->user()?->hasAnyRole(['Owner', 'Super admin']) ?? false)
                            ->required(),
                        TextInput::make('stock')
                            ->label('Stok')
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Tampilkan di toko mobile')
                            ->default(true)
                            ->required(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Media Produk')
                    ->schema([
                        FileUpload::make('image_url')
                            ->label('Gambar')
                            ->image()
                            ->imageEditor()
                            ->directory('products')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
