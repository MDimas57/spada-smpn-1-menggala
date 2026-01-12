<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NilaiUjianResource\Pages;
use App\Models\NilaiUjian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NilaiUjianResource extends Resource
{
    protected static ?string $model = NilaiUjian::class;

    protected static ?string $navigationGroup = 'Pengelolaan Nilai Siswa';
    protected static ?string $navigationLabel = 'Data Nilai (UTS/UAS)';
    protected static ?string $pluralLabel = 'Nilai Ujian';
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Nilai')
                    ->schema([
                        Forms\Components\Select::make('siswa_id')
                            ->relationship('siswa', 'nis')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nis} - {$record->user->name}")
                            ->searchable(['nis'])
                            ->preload()
                            ->required()
                            ->label('Siswa'),

                        Forms\Components\Select::make('mapel_id')
                            ->relationship('mapel', 'nama')
                            ->required()
                            ->preload()
                            ->label('Mata Pelajaran'),

                        Forms\Components\Select::make('jenis_ujian')
                            ->options([
                                'UTS' => 'UTS (Ujian Tengah Semester)',
                                'UAS' => 'UAS (Ujian Akhir Semester)',
                            ])
                            ->required()
                            ->label('Jenis Ujian'),

                        Forms\Components\Select::make('tahun_ajaran_id')
                            ->relationship('tahunAjaran', 'tahun')
                            ->required()
                            ->label('Tahun Ajaran'),

                        Forms\Components\TextInput::make('nilai')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->required()
                            ->label('Nilai (0-100)'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('siswa.nis')
                    ->label('NIS')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('siswa.user.name')
                    ->label('Nama Siswa')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('siswa.kelas.nama')
                    ->label('Kelas')
                    ->sortable(),

                Tables\Columns\TextColumn::make('mapel.nama')
                    ->label('Mata Pelajaran')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('jenis_ujian')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'UTS' => 'info',
                        'UAS' => 'warning',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('tahunAjaran.tahun')
                    ->label('Tahun Ajaran')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nilai')
                    ->sortable()
                    ->weight('bold')
                    ->color(fn($state): string => $state < 75 ? 'danger' : 'success')
                    ->formatStateUsing(fn($state) => number_format($state, 0)),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_ujian')
                    ->options([
                        'UTS' => 'UTS',
                        'UAS' => 'UAS',
                    ])
                    ->label('Jenis Ujian'),

                Tables\Filters\SelectFilter::make('mapel_id')
                    ->relationship('mapel', 'nama')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('kelas')
                    ->relationship('siswa.kelas', 'nama')
                    ->label('Kelas')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('tahun_ajaran_id')
                    ->relationship('tahunAjaran', 'tahun')
                    ->label('Tahun Ajaran')
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNilaiUjians::route('/'),
            'create' => Pages\CreateNilaiUjian::route('/create'),
            'edit' => Pages\EditNilaiUjian::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return null;
    }
}
