<?php

namespace App\Filament\Widgets;

use App\Models\TahunAjaran;
use Filament\Widgets\Widget;

class TahunAjaranInfoWidget extends Widget
{
    protected static string $view = 'filament.widgets.tahun-ajaran-info';
    protected static ?int $sort = 0;

    public function getViewData(): array
    {
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();

        return [
            'tahunAjaran' => $tahunAjaranAktif,
        ];
    }
}
