<?php

namespace App\Filament\Resources\ModulResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TugasRelationManager extends RelationManager
{
    protected static string $relationship = 'tugas';
    protected static ?string $title = 'Tugas Siswa';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\RichEditor::make('instruksi')
                    ->label('Instruksi Tugas')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('deadline')
                    ->label('Batas Pengumpulan')
                    ->required(),

                // Lampiran tugas (opsional)
                Forms\Components\FileUpload::make('file_path')
                    ->label('Lampiran Tugas (PDF / DOC / DOCX)')
                    ->directory('tugas-files')
                    ->disk('public')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    ])
                    ->maxSize(10240)
                    ->helperText('Opsional. Maks 10MB.'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul'),
                Tables\Columns\TextColumn::make('deadline')->dateTime(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
