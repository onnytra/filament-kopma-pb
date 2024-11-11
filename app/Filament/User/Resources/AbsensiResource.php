<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\AbsensiResource\Pages;
use App\Filament\User\Resources\AbsensiResource\RelationManagers;
use App\Models\Absensi;
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

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

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
                Forms\Components\DateTimePicker::make('datetime')
                    ->default(now())
                    ->readOnly(),
                Forms\Components\Select::make('activity_id')
                    ->relationship('activity', 'activity')
                    ->required(),
                Forms\Components\FileUpload::make('photo')
                    ->label('Proof')
                    ->image()
                    ->maxSize(1024)
                    ->directory('absensi_proof')
                    ->downloadable()
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('datetime')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\ImageColumn::make('photo')
                    ->square()
                    ->url(fn(Absensi $record): ?string => $record->proof ? asset('storage/'.$record->proof) : null, true),
                Tables\Columns\TextColumn::make('activity.activity')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
                        Notification::make()
                            ->success()
                            ->title('Deleted')
                            ->send();
                    }),
                ])
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('user_id', auth()->id());
            })
        ;
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
            'index' => Pages\ListAbsensis::route('/'),
            'create' => Pages\CreateAbsensi::route('/create'),
            'edit' => Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }
}
