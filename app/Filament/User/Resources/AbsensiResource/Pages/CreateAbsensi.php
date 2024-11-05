<?php

namespace App\Filament\User\Resources\AbsensiResource\Pages;

use App\Filament\User\Resources\AbsensiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAbsensi extends CreateRecord
{
    protected static string $resource = AbsensiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        
        return $data;
    }
}
