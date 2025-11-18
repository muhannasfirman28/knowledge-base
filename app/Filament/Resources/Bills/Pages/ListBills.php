<?php

namespace App\Filament\Resources\Bills\Pages;

use App\Filament\Resources\Bills\BillResource;
use Artisan;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListBills extends ListRecords
{
    protected static string $resource = BillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('generate_today')
            ->label('Generate Tagihan Hari Ini')
            ->color('warning')
            ->action(function () {
                Artisan::call('bill:generate-daily-check');
                
                Notification::make()
                    ->title('Proses Selesai')
                    ->success()
                    ->send();
            }),
        ];
    }
}
