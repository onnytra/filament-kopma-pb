<?php

namespace App\Filament\User\Resources\SimpananResource\Pages;

use App\Filament\User\Resources\SimpananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSimpanans extends ListRecords
{
    protected static string $resource = SimpananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
