<?php

namespace App\Filament\Admin\Resources\SimpananResource\Pages;

use App\Filament\Admin\Resources\SimpananResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSimpanan extends CreateRecord
{
    protected static string $resource = SimpananResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['admin_id'] = auth()->id();
        
        return $data;
    }
}
