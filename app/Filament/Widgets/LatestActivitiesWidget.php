<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestActivitiesWidget extends BaseWidget
{
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('📋 Course Terbaru')
            ->query(
                Course::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Course')
                    ->description(fn ($record) => 'Kelas: ' . $record->kelas->nama)
                    ->searchable(),

                Tables\Columns\TextColumn::make('guru.user.name')
                    ->label('Guru')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
