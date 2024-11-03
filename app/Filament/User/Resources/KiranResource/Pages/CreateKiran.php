<?php

namespace App\Filament\User\Resources\KiranResource\Pages;

use App\Filament\User\Resources\KiranResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKiran extends CreateRecord
{
    protected static string $resource = KiranResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }

    
}
