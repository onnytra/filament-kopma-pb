<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\KiranResource\Pages;
use App\Filament\User\Resources\KiranResource\RelationManagers;
use App\Models\Kiran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KiranResource extends Resource
{
    protected static ?string $model = Kiran::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kiran')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('anonim')
                    ->required(),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kiran')
                    ->searchable(),
                Tables\Columns\IconColumn::make('anonim')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKirans::route('/'),
            'create' => Pages\CreateKiran::route('/create'),
            'edit' => Pages\EditKiran::route('/{record}/edit'),
        ];
    }
}
