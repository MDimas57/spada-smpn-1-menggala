<?php

namespace App\Filament\Resources\JawabanKuisResource\Pages;

use App\Filament\Resources\JawabanKuisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJawabanKuis extends EditRecord
{
    protected static string $resource = JawabanKuisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
     protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); 
    }
}
