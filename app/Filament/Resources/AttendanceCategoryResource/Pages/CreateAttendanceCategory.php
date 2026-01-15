<?php

namespace App\Filament\Resources\AttendanceCategoryResource\Pages;

use App\Filament\Resources\AttendanceCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateAttendanceCategory extends CreateRecord
{
    protected static string $resource = AttendanceCategoryResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); 
    }

        public function getTitle(): string
    {
        return 'Tambah Kategori';
    }
     public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
    
    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Tambah');
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Tambah & Buat Lagi');
    }
    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Batal');
    }
}
