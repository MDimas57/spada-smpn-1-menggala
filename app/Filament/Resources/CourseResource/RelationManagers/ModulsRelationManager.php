<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ModulsRelationManager extends RelationManager
{
    protected static string $relationship = 'moduls';
    protected static ?string $title = 'Modul Pembelajaran';
    protected static ?string $recordTitleAttribute = 'judul';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->maxLength(255)
                    ->label('Judul Modul')
                    ->placeholder('Contoh: Bab 1 - Pengenalan Aljabar'),

                Forms\Components\RichEditor::make('deskripsi')
                    ->label('Deskripsi')
                    ->placeholder('Jelaskan isi modul ini...')
                    ->columnSpanFull(),

                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ])
                    ->default('draft')
                    ->required(),

                Forms\Components\DateTimePicker::make('publish_at')
                    ->label('Jadwal Publish')
                    ->placeholder('Kosongkan jika ingin publish sekarang'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('judul')
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Modul')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('materis_count')
                    ->label('Materi')
                    ->counts('materis')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('tugas_count')
                    ->label('Tugas')
                    ->counts('tugas')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('kuis_count')
                    ->label('Kuis')
                    ->counts('kuis')
                    ->badge()
                    ->color('danger'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // Otomatis isi data dari course
                        $course = $this->getOwnerRecord();
                        $data['course_id'] = $course->id;
                        $data['guru_id'] = $course->guru_id;
                        $data['kelas_id'] = $course->kelas_id;
                        $data['mapel_id'] = $course->mapel_id;
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
