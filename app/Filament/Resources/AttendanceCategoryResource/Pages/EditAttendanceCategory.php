<?php

namespace App\Filament\Resources\AttendanceCategoryResource\Pages;

use App\Filament\Resources\AttendanceCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttendanceCategory extends EditRecord
{
    protected static string $resource = AttendanceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
