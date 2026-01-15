<?php

namespace App\Filament\Resources\NilaiUjianResource\Pages;

use App\Filament\Resources\NilaiUjianResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateNilaiUjian extends CreateRecord
{
    protected static string $resource = NilaiUjianResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Nilai berhasil disimpan';
    }
    public function getTitle(): string
    {
        return 'Tambah Nilai Ujian';
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
