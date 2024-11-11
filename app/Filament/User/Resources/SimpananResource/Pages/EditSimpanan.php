<?php

namespace App\Filament\User\Resources\SimpananResource\Pages;

use App\Filament\User\Resources\SimpananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSimpanan extends EditRecord
{
    protected static string $resource = SimpananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['status'] = 'Pending';
        return $data;
    }
}
