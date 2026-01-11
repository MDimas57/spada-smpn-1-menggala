<?php

namespace App\Filament\Resources\AttendanceCategoryResource\Pages;

use App\Filament\Resources\AttendanceCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttendanceCategories extends ListRecords
{
    protected static string $resource = AttendanceCategoryResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Kategori Absensi'),
        ];
    }

}
