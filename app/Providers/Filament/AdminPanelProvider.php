<?php

namespace App\Providers\Filament;

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
        // register a render hook that injects a compact notification icon next to the user menu
        // this will render the Blade partial `filament.partials.header-notifications`
        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn () => view('filament.partials.header-notifications')
        );

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->sidebarCollapsibleOnDesktop() // ✅ Menambahkan fitur collapse sidebar
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets default telah dihapus dari sini
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
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
