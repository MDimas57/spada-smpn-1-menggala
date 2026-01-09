<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekapNilaiTugasResource\Pages;
use App\Models\Siswa;
use App\Models\Tugas;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RekapNilaiTugasResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationGroup = 'Pengelolaan Nilai Siswa';
    protected static ?string $navigationLabel = 'Rekap Nilai Tugas';
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $slug = 'rekap-nilai-tugas';

    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        $columns = [
            TextColumn::make('user.name')
                ->label('Nama Siswa')
                ->searchable()
                ->sortable(),

            TextColumn::make('kelas.nama')
                ->label('Kelas')
                ->sortable()
                ->searchable(),
        ];

        // Ambil semua tugas yang ada
        $tugasList = Tugas::orderBy('id')->get();

        // Tambahkan kolom untuk setiap tugas
        foreach ($tugasList as $tugas) {
            $columns[] = TextColumn::make("tugas_{$tugas->id}")
                ->label("Tugas {$tugas->id}")
                ->state(function (Model $record) use ($tugas) {
                    $pengumpulan = $record->pengumpulanTugas()
                        ->where('tugas_id', $tugas->id)
                        ->first();

                    return $pengumpulan && $pengumpulan->nilai !== null
                        ? number_format($pengumpulan->nilai, 1)
                        : '-';
                })
                ->alignCenter()
                ->badge();
        }

        // Tambahkan kolom rata-rata
        $columns[] = TextColumn::make('rata_rata_nilai')
            ->label('Rata-rata')
            ->state(function (Model $record) {
                $avg = $record->pengumpulanTugas()
                    ->whereNotNull('nilai')
                    ->avg('nilai');

                return $avg ? number_format($avg, 1) : '-';
            })
            ->color(fn($state) => $state === '-' ? 'gray' : ($state < 75 ? 'danger' : 'success'))
            ->badge()
            ->alignCenter();

        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->with(['user', 'kelas', 'pengumpulanTugas']))
            ->columns($columns)
            ->filters([
                SelectFilter::make('kelas_id')
                    ->label('Filter Kelas')
                    ->relationship('kelas', 'nama'),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRekapNilaiTugas::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
