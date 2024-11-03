<?php

namespace App\Filament\User\Resources\SuratResource\Pages;

use App\Filament\User\Resources\SuratResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSurat extends CreateRecord
{
    protected static string $resource = SuratResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        
        return $data;
    }
}
