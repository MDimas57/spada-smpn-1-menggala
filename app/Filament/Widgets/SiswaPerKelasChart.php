<?php

namespace App\Filament\Widgets;

use App\Models\Kelas;
use Filament\Widgets\ChartWidget;

class SiswaPerKelasChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Siswa per Kelas';
    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    protected function getData(): array
    {
        $kelasData = Kelas::withCount('siswas')
            ->orderBy('nama')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Siswa',
                    'data' => $kelasData->pluck('siswas_count')->toArray(),
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.5)',
                        'rgba(16, 185, 129, 0.5)',
                        'rgba(245, 158, 11, 0.5)',
                        'rgba(239, 68, 68, 0.5)',
                        'rgba(139, 92, 246, 0.5)',
                        'rgba(236, 72, 153, 0.5)',
                    ],
                    'borderColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)',
                        'rgb(139, 92, 246)',
                        'rgb(236, 72, 153)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $kelasData->pluck('nama')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
