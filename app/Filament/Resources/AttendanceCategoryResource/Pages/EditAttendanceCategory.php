<?php

namespace App\Filament\Resources\AttendanceCategoryResource\Pages;

use App\Filament\Resources\AttendanceCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditAttendanceCategory extends EditRecord
{
    protected static string $resource = AttendanceCategoryResource::class;

       protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus'),
        ];
    }
     protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Batal');
    }
     protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Simpan');
    }

    protected function getSaveAndContinueFormAction(): Action
    {
        return parent::getSaveAndContinueFormAction()
            ->label('Simpan Perubahan');
    }

    protected function getDeleteFormAction(): Action
    {
        return parent::getDeleteFormAction()
            ->label('Hapus');
    }
     protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); 
    }
}
