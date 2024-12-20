<?php

namespace App\Filament\Admin\Resources\ActivityResource\Pages;

use App\Filament\Admin\Resources\ActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActivity extends EditRecord
{
    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['admin_id'] = auth()->id();
        return $data;
    }
}
