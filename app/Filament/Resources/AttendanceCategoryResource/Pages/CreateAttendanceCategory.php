<?php

namespace App\Filament\Resources\AttendanceCategoryResource\Pages;

use App\Filament\Resources\AttendanceCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendanceCategory extends CreateRecord
{
    protected static string $resource = AttendanceCategoryResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); 
    }
}
