<?php

namespace App\Filament\Resources\JawabanKuisResource\Pages;

use App\Filament\Resources\JawabanKuisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJawabanKuis extends ListRecords
{
    protected static string $resource = JawabanKuisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tidak ada tombol create karena jawaban dibuat otomatis saat submit
        ];
    }
}
