<?php

namespace App\Filament\User\Resources\KiranResource\Pages;

use App\Filament\User\Resources\KiranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKirans extends ListRecords
{
    protected static string $resource = KiranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
