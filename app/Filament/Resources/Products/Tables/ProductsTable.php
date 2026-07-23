<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_url')
                    ->label('Gambar')
                    ->square(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Produk')
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('cost_price')
                    ->label('Modal')
                    ->money('IDR')
                    ->visible(fn (): bool => auth()->user()?->hasAnyRole(['Owner', 'Super admin']) ?? false)
                    ->sortable(),
                TextColumn::make('margin')
                    ->label('Margin')
                    ->state(fn ($record): float => $record->marginAmount())
                    ->description(fn ($record): string => number_format($record->marginPercentage(), 1).'%')
                    ->money('IDR')
                    ->visible(fn (): bool => auth()->user()?->hasAnyRole(['Owner', 'Super admin']) ?? false)
                    ->sortable(false),
                TextColumn::make('stock')
                    ->label('Stok')
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => $state === 0 ? 'Habis' : "{$state} pcs")
                    ->color(fn (int $state): string => $state === 0 ? 'danger' : ($state <= 5 ? 'warning' : 'success'))
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Tampil')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('product_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('is_active')
                    ->label('Tampil di toko'),
            ])
            ->recordActions([
                Action::make('add_stock')
                    ->label('Tambah Stok')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->schema([
                        TextInput::make('quantity')
                            ->label('Jumlah stok masuk')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->increment('stock', (int) $data['quantity']);

                        Notification::make()
                            ->title('Stok berhasil ditambahkan')
                            ->success()
                            ->send();
                    }),
                Action::make('reduce_stock')
                    ->label('Kurangi Stok')
                    ->icon('heroicon-o-minus-circle')
                    ->color('warning')
                    ->schema([
                        TextInput::make('quantity')
                            ->label('Jumlah stok keluar')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(fn ($record): int => (int) $record->stock)
                            ->required(),
                    ])
                    ->action(function ($record, array $data): void {
                        $quantity = (int) $data['quantity'];

                        if ($quantity > $record->stock) {
                            Notification::make()
                                ->title('Stok tidak cukup')
                                ->danger()
                                ->send();

                            return;
                        }

                        $record->decrement('stock', $quantity);

                        Notification::make()
                            ->title('Stok berhasil dikurangi')
                            ->success()
                            ->send();
                    }),
                EditAction::make()
                    ->label('Edit'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->poll('15s');
    }
}
