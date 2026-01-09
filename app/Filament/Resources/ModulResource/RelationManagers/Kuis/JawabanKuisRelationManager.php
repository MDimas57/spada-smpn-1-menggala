<?php

namespace App\Filament\Resources\ModulResource\RelationManagers\Kuis;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class JawabanKuisRelationManager extends RelationManager
{
    protected static string $relationship = 'jawabanSiswa';
    protected static ?string $title = 'Jawaban Essay Siswa';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi')
                    ->schema([
                        Forms\Components\TextInput::make('siswa.user.name')
                            ->label('Nama Siswa')
                            ->disabled(),

                        Forms\Components\TextInput::make('soal.pertanyaan')
                            ->label('Soal')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Jawaban & Penilaian')
                    ->schema([
                        Forms\Components\Textarea::make('jawaban_siswa')
                            ->label('Jawaban Siswa')
                            ->disabled()
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('skor')
                            ->label('Skor (0-100)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),

                        Forms\Components\Textarea::make('komentar_guru')
                            ->label('Komentar')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('siswa.user.name')
                    ->label('Siswa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('jawaban_siswa')
                    ->label('Jawaban')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('skor')
                    ->label('Skor')
                    ->badge()
                    ->color(fn (?int $state) => $state === null ? 'gray' : 'success')
                    ->formatStateUsing(fn (?int $state) => $state ?? 'Belum Dinilai'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tidak ada tombol create
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Nilai'),
            ])
            ->bulkActions([
                // Tidak ada bulk actions
            ]);
    }
}
