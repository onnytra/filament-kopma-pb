<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KiranResource\Pages;
use App\Filament\Admin\Resources\KiranResource\RelationManagers;
use App\Models\Kiran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KiranResource extends Resource
{
    protected static ?string $model = Kiran::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationGroup = 'Actions';

    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kiran')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('anonim')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'nia')
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kiran')
                    ->label('Kritik & Saran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('anonim')
                    ->label('User')
                    ->formatStateUsing(fn($record) => $record->anonim ? 'Anonim' : $record->user->name)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
