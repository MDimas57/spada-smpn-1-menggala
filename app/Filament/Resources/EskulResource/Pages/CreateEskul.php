<?php

namespace App\Filament\Resources\EskulResource\Pages;

use App\Filament\Resources\EskulResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateEskul extends CreateRecord
{
    protected static string $resource = EskulResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); 
    }
    public function getTitle(): string
    {
        return 'Buat Ekskul';
    }
     public function getBreadcrumb(): string
    {
        return 'Buat';
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
