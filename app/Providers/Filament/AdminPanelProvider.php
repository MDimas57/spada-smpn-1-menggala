<?php

namespace App\Providers\Filament;

// --- Import Custom Login & Helper ---
use App\Filament\Auth\CustomLogin;
use Illuminate\Support\Facades\Blade;
// ------------------------------------

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // Tetap pertahankan notifikasi header Anda
        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn () => view('filament.partials.header-notifications')
        );

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            
            // Menggunakan Class Login Custom
            ->login(CustomLogin::class)
            
            // Konfigurasi Logo Sekolah (Tetap Ada)
            ->brandLogo(fn () => view('filament.admin.logo')) 
            ->brandLogoHeight('5rem') 

            // --- BAGIAN CSS BACKGROUND SUDAH DIHAPUS ---

            ->colors([
                'primary' => Color::Amber,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            
            // Daftar Widgets Spesifik Anda
            ->widgets([
                \App\Filament\Widgets\TahunAjaranInfoWidget::class,
                \App\Filament\Widgets\AdminStatsWidget::class,
                \App\Filament\Widgets\GuruStatsWidget::class,
                \App\Filament\Widgets\SiswaPerKelasChart::class,
                \App\Filament\Widgets\CourseStatusChart::class,
                \App\Filament\Widgets\JadwalHariIniWidget::class,
                \App\Filament\Widgets\LatestActivitiesWidget::class,
                \App\Filament\Widgets\TugasBelumDikoreksiWidget::class,
            ])
            
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}