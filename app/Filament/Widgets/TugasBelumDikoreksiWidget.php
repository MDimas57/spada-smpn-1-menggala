<?php

namespace App\Filament\Widgets;

use App\Models\PengumpulanTugas;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class TugasBelumDikoreksiWidget extends BaseWidget
{
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->hasRole('guru');
    }

    public function table(Table $table): Table
    {
        $guruId = Auth::user()->guru?->id;

        return $table
            ->heading('📝 Tugas Menunggu Koreksi')
            ->query(
                PengumpulanTugas::whereNull('nilai')
                    ->whereHas('tugas', function($q) use ($guruId) {
                        $q->whereHas('modul', function($m) use ($guruId) {
                            $m->where('guru_id', $guruId);
                        });
                    })
                    ->with(['siswa.user', 'siswa.kelas', 'tugas'])
                    ->latest('tanggal_dikumpulkan')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('siswa.user.name')
                    ->label('Nama Siswa')
                    ->description(fn ($record) => $record->siswa->kelas->nama ?? '-')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tugas.judul')
                    ->label('Judul Tugas')
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_dikumpulkan')
                    ->label('Dikumpulkan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn () => 'Belum Dinilai')
                    ->color('warning'),
            ])
            ->paginated(false);
    }
}
