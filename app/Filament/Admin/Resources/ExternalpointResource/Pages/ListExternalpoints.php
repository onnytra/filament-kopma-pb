<?php

namespace App\Filament\Admin\Resources\ExternalpointResource\Pages;

use App\Filament\Admin\Resources\ExternalpointResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExternalpoints extends ListRecords
{
    protected static string $resource = ExternalpointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
