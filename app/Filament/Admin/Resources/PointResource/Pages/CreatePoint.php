<?php

namespace App\Filament\Admin\Resources\PointResource\Pages;

use App\Filament\Admin\Resources\PointResource;
use App\Models\Point;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePoint extends CreateRecord
{
    protected static string $resource = PointResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (is_array($data['user_id'])) {
            $data['user_id'] = $data['user_id'][0];
        }
        return $data;
    }
    protected function afterCreate(): void
    {
        $formData = $this->form->getState();
        $remainingUsers = array_slice($formData['user_id'], 1);
        foreach ($remainingUsers as $userId) {
            Point::create([
                'user_id' => $userId,
                'point' => $formData['point'],
                'type' => $formData['type'],
                'date' => $formData['date'],
            ]);
        }
    }
}
