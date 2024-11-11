<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PointResource\Pages;
use App\Filament\Admin\Resources\PointResource\RelationManagers;
use App\Models\Absensi;
use App\Models\Point;
use App\Models\Simpanan;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PointResource extends Resource
{
    protected static ?string $model = Point::class;

    protected static ?string $navigationIcon = 'clarity-coin-bag-line';
    protected static ?string $navigationGroup = 'Actions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Point')
                    ->schema([
                        Forms\Components\TextInput::make('point')
                            ->required()
                            ->numeric(),
                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                'simpanan' => 'Simpanan',
                                'belanja' => 'Belanja',
                                'aktivitas' => 'Aktivitas',
                            ])
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn(Forms\Set $set) => $set('user_id', null)),
                        Forms\Components\DateTimePicker::make('date')
                            ->required(),
                    ]),
                Forms\Components\Section::make('User')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Select User')
                            ->required()
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(function (callable $get) {
                                $data = $get('type');

                                if (!$data) {
                                    return [];
                                }

                                if ($data === 'simpanan') {
                                    try {
                                        $simpananApproved = Simpanan::where('status', 'approved')->pluck('user_id');
                                    } catch (\Throwable $th) {
                                        return [];
                                    }
                                    return User::whereIn('id', $simpananApproved)->get();
                                } elseif ($data === 'belanja') {
                                    try {
                                        $user = User::all()->pluck('name', 'id');
                                    } catch (\Throwable $th) {
                                        return [];
                                    }
                                    return $user;
                                } elseif ($data === 'aktivitas') {
                                    try {
                                        $absensiApproved = Absensi::where('status', 'approved')->pluck('user_id');
                                    } catch (\Throwable $th) {
                                        return [];
                                    }
                                    return User::whereIn('id', $absensiApproved)->get();
                                }
                            }),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('select_all')
                                ->label('Select All')
                                ->icon('heroicon-m-check')
                                ->action(function (Forms\Set $set, Forms\Get $get) {
                                    $type = $get('type');
                                    $options = match ($type) {
                                        'simpanan' => User::whereIn('id', Simpanan::where('status', 'approved')->pluck('user_id'))->pluck('id')->toArray(),
                                        'belanja' => User::all()->pluck('id')->toArray(),
                                        'aktivitas' => User::whereIn('id', Absensi::where('status', 'approved')->pluck('user_id'))->pluck('id')->toArray(),
                                        default => []
                                    };
                                    $set('user_id', $options);
                                })
                                ->visible(fn(Forms\Get $get) => (bool) $get('type')),

                            Forms\Components\Actions\Action::make('clear_selection')
                                ->label('Clear Selection')
                                ->icon('heroicon-m-x-mark')
                                ->color('danger')
                                ->action(fn(Forms\Set $set) => $set('user_id', []))
                                ->visible(fn(Forms\Get $get) => (bool) $get('type')),
                        ])
                            ->columnSpanFull()
                    ])
                    ->columns(1)
                    ->visible(fn($livewire) => $livewire instanceof Pages\CreatePoint),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('point')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
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
                SelectFilter::make('type')
                    ->options([
                        'simpanan' => 'Simpanan',
                        'belanja' => 'Belanja',
                        'aktivitas' => 'Aktivitas',
                    ]),
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
            'index' => Pages\ListPoints::route('/'),
            'create' => Pages\CreatePoint::route('/create'),
            'edit' => Pages\EditPoint::route('/{record}/edit'),
        ];
    }
}
