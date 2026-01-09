<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Modul;
use App\Models\PengumpulanTugas;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class GuruStatsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return auth()->user()->hasRole('guru');
    }

    protected function getStats(): array
    {
        $guruId = Auth::user()->guru?->id;

        if (!$guruId) {
            return [];
        }

        // Hitung total siswa yang diajar oleh guru ini
        $totalSiswa = \App\Models\Siswa::whereHas('kelas', function($q) use ($guruId) {
            $q->whereHas('gurus', function($g) use ($guruId) {
                $g->where('gurus.id', $guruId);
            });
        })->count();

        // Hitung course yang published (aktif)
        $courseAktif = Course::where('guru_id', $guruId)
            ->where('status', 'published')
            ->count();

        // Total guru tidak relevan untuk dashboard guru individu,
        // jadi kita tampilkan total modul yang dibuat
        $totalModul = Modul::where('guru_id', $guruId)->count();

        // Hitung total kelas yang diampu
        $totalKelas = \App\Models\Kelas::whereHas('gurus', function($q) use ($guruId) {
            $q->where('gurus.id', $guruId);
        })->count();

        return [
            Stat::make('Total Siswa', $totalSiswa)
                ->description('Siswa terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Course Aktif', $courseAktif)
                ->description('Course published')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('warning')
                ->chart([3, 5, 3, 7, 6, 4, 3, 2]),

            Stat::make('Total Modul', $totalModul)
                ->description('Modul pembelajaran')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('danger')
                ->chart([4, 6, 5, 3, 7, 4, 6, 5]),

            Stat::make('Total Kelas', $totalKelas)
                ->description('Kelas tersedia')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('success')
                ->chart([5, 4, 6, 7, 3, 5, 4, 6]),
        ];
    }
}
