<?php

namespace App\Filament\Resources\ModulResource\Pages;

use App\Filament\Resources\ModulResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;


class CreateModul extends CreateRecord
{
    protected static string $resource = ModulResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Logika:
        // Jika yang login GURU, paksa guru_id pakai ID dia sendiri.
        // Jika ADMIN, biarkan data['guru_id'] dari form lewat apa adanya.

        if (Auth::user()->hasRole('guru')) {
            $data['guru_id'] = Auth::user()->guru->id;
        }

        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); 
    }
     public function getTitle(): string
    {
        return 'Buat Modul';
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
