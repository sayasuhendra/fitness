<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Actions\Orders\CancelOrderAction;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('complete')
                ->label('Tandai Selesai')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (): bool => in_array($this->record->status, ['paid'], true))
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->update(['status' => 'completed']);

                    Notification::make()
                        ->title('Pesanan ditandai selesai')
                        ->success()
                        ->send();

                    $this->redirect(static::getResource()::getUrl('view', ['record' => $this->record]));
                }),
            Action::make('cancel')
                ->label('Batalkan Pesanan')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (): bool => in_array($this->record->status, ['pending_payment', 'paid'], true))
                ->requiresConfirmation()
                ->modalHeading('Batalkan pesanan ini?')
                ->modalDescription('Stok produk pada pesanan ini akan dikembalikan.')
                ->action(function (): void {
                    app(CancelOrderAction::class)->execute($this->record);

                    Notification::make()
                        ->title('Pesanan dibatalkan dan stok dikembalikan')
                        ->success()
                        ->send();

                    $this->redirect(static::getResource()::getUrl('view', ['record' => $this->record]));
                }),
            Action::make('deliver')
                ->label('Serahkan ke Member')
                ->icon('heroicon-o-hand-thumb-up')
                ->color('success')
                ->visible(fn (): bool => $this->record->delivered_at === null && in_array($this->record->status, ['paid', 'completed'], true))
                ->requiresConfirmation()
                ->modalHeading('Pesanan sudah diserahkan ke member?')
                ->action(function (): void {
                    $this->record->update([
                        'delivered_at' => now(),
                        'delivered_by' => auth()->id(),
                    ]);

                    Notification::make()
                        ->title('Pesanan ditandai sudah diserahkan')
                        ->success()
                        ->send();

                    $this->redirect(static::getResource()::getUrl('view', ['record' => $this->record]));
                }),
            EditAction::make(),
        ];
    }
}
