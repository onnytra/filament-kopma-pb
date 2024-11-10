<?php

namespace App\Filament\Exports;

use App\Models\Test;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TestExporter extends Exporter
{
    protected static ?string $model = Test::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('method'),
            ExportColumn::make('function'),
            ExportColumn::make('variable'),
            ExportColumn::make('value'),
            ExportColumn::make('expected_validity')
                ->state(function (Test $record): string {
                    return $record->expected_validity === 0 ? 'Invalid' : 'Valid';
                }),
            ExportColumn::make('actual_validity')
                ->state(function (Test $record): string {
                    return $record->actual_validity === 0 ? 'Invalid' : 'Valid';
                }),
            ExportColumn::make('status')
                ->state(function (Test $record): string {
                    return $record->status === 0 ? 'Fail' : 'Pass';
                }),
            ExportColumn::make('reason'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your test export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}