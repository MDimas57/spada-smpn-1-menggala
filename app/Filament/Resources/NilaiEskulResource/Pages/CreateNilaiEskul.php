<?php

namespace App\Filament\Resources\NilaiEskulResource\Pages;

use App\Filament\Resources\NilaiEskulResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateNilaiEskul extends CreateRecord
{
    protected static string $resource = NilaiEskulResource::class;

    // Redirect ke halaman list setelah simpan
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function getTitle(): string
    {
        return 'Tambah Nilai Ekskul';
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
