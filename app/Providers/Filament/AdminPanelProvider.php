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
            ->brandLogo(fn () => view('filament.admin.logo')) // Gunakan brandLogo, bukan brandView
            ->brandLogoHeight('5rem') // Penting! Atur tinggi agar logo & teks tidak terpotong (defaultnya kecil)
            // --- CUSTOM CSS: BACKGROUND IMAGE LOKAL & GLASSMORPHISM ---
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => Blade::render(<<<'HTML'
                    <style>
                        /* Background Image dari Local Public Folder */
                        body {
                            /* Memanggil public/images/login-bg.jpg */
                            background-image: url('{{ asset("images/logo-sekolah.png") }}'); 
                            background-size: cover;
                            background-position: center;
                            background-repeat: no-repeat;
                            background-attachment: fixed;
                        }
                        
                        /* Overlay Gelap Transparan (Supaya tulisan terbaca) */
                        body::before {
                            content: "";
                            position: absolute;
                            top: 0; left: 0; width: 100%; height: 100%;
                            background: rgba(0, 0, 0, 0.5); /* Atur kegelapan di sini (0.1 - 0.9) */
                            z-index: -1;
                        }

                        /* Kartu Login Glassmorphism (Transparan & Blur) */
                        .fi-simple-main {
                            background-color: rgba(255, 255, 255, 0.85) !important; /* Putih 85% */
                            backdrop-filter: blur(10px); /* Efek Blur */
                            -webkit-backdrop-filter: blur(10px);
                            border-radius: 1.5rem !important;
                            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3) !important;
                            padding: 3rem !important;
                            max-width: 480px !important;
                            border: 1px solid rgba(255, 255, 255, 0.3);
                        }
                        
                        /* Judul Halaman (Sign In) */
                        .fi-simple-header-heading {
                            color: #1f2937 !important; /* Warna text gelap */
                            font-weight: 800 !important;
                        }

                        /* Mempercantik Tombol Sign In */
                        button[type="submit"] {
                            background: linear-gradient(to right, #d97706, #fbbf24) !important; /* Gradasi Orange */
                            border: none !important;
                            font-weight: bold !important;
                            letter-spacing: 0.5px;
                            transition: all 0.3s ease;
                            box-shadow: 0 4px 6px -1px rgba(217, 119, 6, 0.4);
                        }
                        
                        button[type="submit"]:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 10px 15px -3px rgba(217, 119, 6, 0.5);
                        }
                    </style>
                HTML)
            )
            // ---------------------------------------------------------

            ->colors([
                'primary' => Color::Amber,
            ])
            ->sidebarCollapsibleOnDesktop() // Fitur sidebar collapse tetap ada
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