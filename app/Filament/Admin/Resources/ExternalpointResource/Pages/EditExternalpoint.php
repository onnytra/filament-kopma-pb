<?php

namespace App\Filament\Admin\Resources\ExternalpointResource\Pages;

use App\Filament\Admin\Resources\ExternalpointResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExternalpoint extends EditRecord
{
    protected static string $resource = ExternalpointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
