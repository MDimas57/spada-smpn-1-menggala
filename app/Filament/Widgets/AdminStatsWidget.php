<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\Eskul;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Siswa', Siswa::count())
                ->description('Siswa terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([7, 12, 16, 14, 18, 22, Siswa::count()]),

            Stat::make('Total Guru', Guru::count())
                ->description('Guru aktif')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info')
                ->chart([5, 8, 10, 12, 14, 15, Guru::count()]),

            Stat::make('Total Kelas', Kelas::count())
                ->description('Kelas tersedia')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('warning'),

            Stat::make('Mata Pelajaran', Mapel::count())
                ->description('Mapel aktif')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary'),

            Stat::make('Course Aktif', Course::where('status', 'published')->count())
                ->description('Dari ' . Course::count() . ' total course')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Ekstrakurikuler', Eskul::count())
                ->description('Eskul tersedia')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('danger'),
        ];
    }
}
