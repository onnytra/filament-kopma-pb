<?php

namespace App\Filament\Admin\Resources\SuratResource\Pages;

use App\Filament\Admin\Resources\SuratResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSurat extends CreateRecord
{
    protected static string $resource = SuratResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['admin_id'] = auth()->id();

        return $data;
    }
}
