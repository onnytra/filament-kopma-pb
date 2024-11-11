<?php

namespace App\Filament\User\Resources\SimpananResource\Pages;

use App\Filament\User\Resources\SimpananResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSimpanan extends CreateRecord
{
    protected static string $resource = SimpananResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['datetime'] = now();
        $data['status'] = 'Pending';
        return $data;
    }
}
