<?php

namespace App\Filament\Resources\DataResource\Pages;

use App\Filament\Resources\DataResource;
use Filament\Resources\Pages\EditRecord;

class EditData extends EditRecord
{
    protected static string $resource = DataResource::class;

    protected function getActions(): array
    {
        return [];
    }
}
