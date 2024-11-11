<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SimpananResource\Pages;
use App\Filament\Admin\Resources\SimpananResource\RelationManagers;
use App\Models\Simpanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SimpananResource extends Resource
{
    protected static ?string $model = Simpanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Actions';

    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit(Model $record): bool
    {
        if ($record->status != 'approved') {
            return true;
        }
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        if ($record->status != 'approved') {
            return true;
        }
        return false;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->label('Saving')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('voluntary_amount')
                    ->label('Voluntary Saving')
                    ->integer(),
                Forms\Components\FileUpload::make('proof')
                    ->required()
                    ->label('Proof of Transaction')
                    ->image()
                    ->maxSize(1024)
                    ->directory('simpanan_proof')
                    ->downloadable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->label('Saving'),
                Tables\Columns\TextColumn::make('voluntary_amount')
                    ->label('Voluntary Saving'),
                Tables\Columns\TextColumn::make('datetime')
                    ->label('Date')
                    ->datetime(),
                Tables\Columns\ImageColumn::make('proof')
                    ->label('Proof of Transaction')
                    ->square()
                    ->url(fn(Simpanan $record): ?string => $record->proof ? asset('storage/' . $record->proof) : null, true),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('admin.name')
                    ->label('Reviewer')
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
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (Collection $records) {
                            foreach ($records as $record) {
                                if ($record->status != 'approved') {
                                    $record->delete();
                                }
                            }
                            Notification::make('Successfully Deleted!')
                                ->success()
                                ->title('Deleted')
                                ->send();
                        }),
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
            'index' => Pages\ListSimpanans::route('/'),
            'create' => Pages\CreateSimpanan::route('/create'),
            'edit' => Pages\EditSimpanan::route('/{record}/edit'),
        ];
    }
}
