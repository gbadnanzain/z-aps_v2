<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\SoTakeIdListWidget;
use App\Filament\Admin\Widgets\SO_On_ProgressWidget;
use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->profile(isSimple:false)
            ->sidebarFullyCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            /* ->userMenuItems([
                MenuItem::make()
                ->label('Profile')
                ->icon('heroicon-o-user')
                ->url('/profile'),
            ]) */
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')

            ->widgets([

                //Widgets\FilamentInfoWidget::class,
                SoTakeIdListWidget::class,
                SO_On_ProgressWidget::class,
                //Widgets\AccountWidget::class,

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
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class
            ])
            ->plugins([
                //FilamentShieldPlugin::make(),
                FilamentShieldPlugin::make(),

                \Hasnayeen\Themes\ThemesPlugin::make()
               // ->canViewThemesPage(fn () => Auth::user()?->is_admin)
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
