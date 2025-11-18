<?php

namespace App\Filament\Resources\Tenancies\Pages;

use App\Filament\Resources\Tenancies\TenancyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenancy extends CreateRecord
{
    protected static string $resource = TenancyResource::class;
}
