<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['bill_ids'] = $this->record->bills->pluck('id')->toArray();
        return $data;
    }

    protected function afterSave(): void
    {
        $transaction = $this->record;
        
        $billIds = $this->data['bill_ids'] ?? [];

        $transaction->bills()->sync($billIds);
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
