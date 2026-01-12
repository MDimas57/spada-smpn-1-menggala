<?php

namespace App\Filament\Auth;

use Filament\Pages\Auth\Login;
use Filament\Support\Enums\Alignment; // Pastikan import ini ada jika mengatur alignment

class CustomLogin extends Login
{
    // Kita akan menyuntikkan CSS khusus lewat fungsi ini
    protected function getLayoutData(): array
    {
        return [
            'hasLogo' => true,
             // Ini trik untuk menyisipkan background
            'bgUrl' => 'https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80', 
        ];
    }
}