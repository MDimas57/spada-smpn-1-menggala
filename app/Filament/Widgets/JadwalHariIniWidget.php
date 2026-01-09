<?php

namespace App\Filament\Widgets;

use App\Models\JadwalPelajaran; // ✅ GANTI DARI Jadwal ke JadwalPelajaran
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class JadwalHariIniWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $hariIni = Carbon::now()->locale('id')->dayName;

        $hariMapping = [
            'Minggu' => 'Minggu',
            'Senin' => 'Senin',
            'Selasa' => 'Selasa',
            'Rabu' => 'Rabu',
            'Kamis' => 'Kamis',
            'Jumat' => 'Jumat',
            'Sabtu' => 'Sabtu',
        ];

        $hari = $hariMapping[$hariIni] ?? $hariIni;

        // ✅ GANTI QUERY menggunakan JadwalPelajaran
        $query = JadwalPelajaran::where('hari', $hari)
            ->with(['kelas', 'mapel', 'guru.user'])
            ->orderBy('jam_mulai');

        // Filter untuk Guru - hanya jadwal mengajar mereka
        if (Auth::user()->hasRole('guru')) {
            $guruId = Auth::user()->guru?->id;
            if ($guruId) {
                $query->where('guru_id', $guruId);
            }
        }

        return $table
            ->heading('📅 Jadwal Pelajaran Hari Ini - ' . $hari)
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('jam_mulai')
                    ->label('Waktu')
                    ->formatStateUsing(fn ($record) =>
                        substr($record->jam_mulai, 0, 5) . ' - ' . substr($record->jam_selesai, 0, 5)
                    )
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('mapel.nama')
                    ->label('Mata Pelajaran')
                    ->searchable(),

                Tables\Columns\TextColumn::make('guru.user.name')
                    ->label('Guru Pengampu')
                    ->visible(fn () => Auth::user()->hasRole('admin')),
            ])
            ->paginated(false);
    }
}
