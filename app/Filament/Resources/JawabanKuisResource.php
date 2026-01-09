<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JawabanKuisResource\Pages;
use App\Filament\Resources\JawabanKuisResource\RelationManagers;
use App\Models\JawabanKuis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class JawabanKuisResource extends Resource
{
    protected static ?string $model = JawabanKuis::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?string $navigationLabel = 'Penilaian Essay Kuis';
    protected static ?int $navigationSort = 5;
    protected static ?string $pluralModelLabel = 'Penilaian Essay Kuis';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Soal & Siswa')
                    ->schema([
                        Forms\Components\Placeholder::make('kuis_judul')
                            ->label('Kuis')
                            ->content(fn ($record): string => $record->kuis->judul ?? '-')
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        Forms\Components\Placeholder::make('siswa_nama')
                            ->label('Nama Siswa')
                            ->content(fn ($record): string => $record->siswa?->user?->name ?? '-')
                            ->dehydrated(false)
                            ->columnSpan(1),

                        Forms\Components\Placeholder::make('soal_pertanyaan')
                            ->label('Pertanyaan')
                            ->content(fn ($record): string => $record->soal->pertanyaan ?? '-')
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Jawaban Siswa')
                    ->schema([
                        Forms\Components\Placeholder::make('jawaban_siswa_display')
                            ->label('Jawaban Essay')
                            ->content(fn ($record): string => $record->jawaban_siswa ?? '-')
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Penilaian')
                    ->schema([
                        Forms\Components\TextInput::make('skor')
                            ->label('Skor (0-100)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->columnSpan(1)
                            ->hint('Masukkan skor dari 0-100'),

                        Forms\Components\Textarea::make('komentar_guru')
                            ->label('Komentar Guru')
                            ->rows(4)
                            ->columnSpanFull()
                            ->hint('Berikan feedback untuk siswa'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kuis.judul')
                    ->label('Kuis')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('siswa.user.name')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('soal.tipe')
                    ->label('Tipe Soal')
                    ->badge()
                    ->color(fn (string $state) => $state === 'essay' ? 'warning' : 'info'),

                Tables\Columns\TextColumn::make('skor')
                    ->label('Skor')
                    ->badge()
                    ->color(fn (?int $state) => $state === null ? 'gray' : 'success')
                    ->formatStateUsing(fn (?int $state) => $state ?? 'Belum Dinilai'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('skor')
                    ->label('Status Penilaian')
                    ->options([
                        'belum' => 'Belum Dinilai',
                        'sudah' => 'Sudah Dinilai',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value'] === 'belum') {
                            return $query->whereNull('skor');
                        }
                        if ($data['value'] === 'sudah') {
                            return $query->whereNotNull('skor');
                        }
                        return $query;
                    }),

                Tables\Filters\SelectFilter::make('soal.tipe')
                    ->label('Tipe Soal')
                    ->options([
                        'essay' => 'Essay',
                        'pilihan_ganda' => 'Pilihan Ganda',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            return $query->whereHas('soal', function ($q) use ($data) {
                                $q->where('tipe', $data['value']);
                            });
                        }
                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Nilai')
                    ->modalWidth('2xl'),
            ])
            ->bulkActions([
                // Tidak ada bulk actions untuk resource ini
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Filter untuk menampilkan hanya jawaban essay yang belum dinilai
        // atau yang sudah dikerjakan siswa
        $query->whereHas('soal', function ($q) {
            $q->where('tipe', 'essay');
        });

        // Jika user adalah guru, tampilkan hanya jawaban dari modulnya
        if (Auth::user()->hasRole('guru')) {
            $guruId = Auth::user()->guru?->id;
            if ($guruId) {
                $query->whereHas('kuis.modul', function ($q) use ($guruId) {
                    $q->where('guru_id', $guruId);
                });
            }
        }

        return $query;
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
            'index' => Pages\ListJawabanKuis::route('/'),
            'edit' => Pages\EditJawabanKuis::route('/{record}/edit'),
        ];
    }
}

