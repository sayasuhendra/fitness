<?php

namespace App\Filament\Resources\PaymentConfirmations\Pages;

use App\Actions\Payments\ApprovePaymentConfirmationAction;
use App\Actions\Payments\RejectPaymentConfirmationAction;
use App\Filament\Resources\PaymentConfirmations\PaymentConfirmationResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewPaymentConfirmation extends ViewRecord
{
    protected static string $resource = PaymentConfirmationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Terima')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->visible(fn (): bool => $this->record->status === 'pending')
                ->schema([
                    Textarea::make('admin_note')
                        ->label('Catatan Admin')
                        ->rows(3),
                ])
                ->requiresConfirmation()
                ->modalHeading('Terima pembayaran ini?')
                ->modalDescription('Status transaksi akan diperbarui sesuai jenis pembayaran member.')
                ->action(function (array $data): void {
                    app(ApprovePaymentConfirmationAction::class)->execute($this->record, auth()->user(), $data['admin_note'] ?? null);

                    Notification::make()
                        ->title('Pembayaran diterima')
                        ->success()
                        ->send();

                    $this->redirect(static::getResource()::getUrl('view', ['record' => $this->record]));
                }),
            Action::make('reject')
                ->label('Tolak')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->visible(fn (): bool => $this->record->status === 'pending')
                ->schema([
                    Textarea::make('admin_note')
                        ->label('Alasan Penolakan')
                        ->rows(3)
                        ->required(),
                ])
                ->requiresConfirmation()
                ->modalHeading('Tolak pembayaran ini?')
                ->modalDescription('Member akan melihat status pembayaran ditolak dan catatan admin.')
                ->action(function (array $data): void {
                    app(RejectPaymentConfirmationAction::class)->execute($this->record, auth()->user(), $data['admin_note'] ?? null);

                    Notification::make()
                        ->title('Pembayaran ditolak')
                        ->danger()
                        ->send();

                    $this->redirect(static::getResource()::getUrl('view', ['record' => $this->record]));
                }),
            EditAction::make(),
        ];
    }
}
