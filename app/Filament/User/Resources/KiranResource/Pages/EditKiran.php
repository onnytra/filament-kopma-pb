<?php

namespace App\Filament\User\Resources\KiranResource\Pages;

use App\Filament\User\Resources\KiranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKiran extends EditRecord
{
    protected static string $resource = KiranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
