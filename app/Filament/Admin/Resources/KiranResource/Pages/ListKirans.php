<?php

namespace App\Filament\Admin\Resources\KiranResource\Pages;

use App\Filament\Admin\Resources\KiranResource;
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
