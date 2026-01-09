<?php

namespace App\Filament\Widgets;

use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Widgets\Widget;

class AlertWidget extends Widget
{
    protected static string $view = 'filament.widgets.alert-widget';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        // Hide the dashboard widget itself — alerts are now shown in the header notifications
        // Keep the class around in case we want to reuse its logic later.
        return false;
    }

    public function getViewData(): array
    {
        $kelasTanpaWali = Kelas::whereDoesntHave('waliKelas')->count();
        $siswaBelumKelas = Siswa::whereNull('kelas_id')->count();

        return [
            'alerts' => [
                [
                    'type' => 'warning',
                    'icon' => 'heroicon-o-exclamation-triangle',
                    'title' => 'Kelas Tanpa Wali',
                    'message' => "Ada {$kelasTanpaWali} kelas yang belum memiliki wali kelas.",
                    'action' => route('filament.admin.resources.wali-kelas.index'),
                    'actionLabel' => 'Kelola Wali Kelas',
                    'show' => $kelasTanpaWali > 0,
                ],
                [
                    'type' => 'info',
                    'icon' => 'heroicon-o-information-circle',
                    'title' => 'Siswa Belum Masuk Kelas',
                    'message' => "Ada {$siswaBelumKelas} siswa yang belum dimasukkan ke kelas.",
                    'action' => route('filament.admin.resources.siswas.index'),
                    'actionLabel' => 'Kelola Siswa',
                    'show' => $siswaBelumKelas > 0,
                ],
            ],
        ];
    }
}
