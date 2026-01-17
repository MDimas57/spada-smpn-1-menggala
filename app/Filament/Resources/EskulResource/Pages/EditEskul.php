<?php

namespace App\Filament\Resources\EskulResource\Pages;

use App\Filament\Resources\EskulResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditEskul extends EditRecord
{
    protected static string $resource = EskulResource::class;

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
     protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); 
    }
}
