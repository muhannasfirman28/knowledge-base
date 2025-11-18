<?php

namespace App\Filament\Resources\Tenancies\Pages;

use App\Filament\Resources\Tenancies\TenancyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTenancy extends EditRecord
{
    protected static string $resource = TenancyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
