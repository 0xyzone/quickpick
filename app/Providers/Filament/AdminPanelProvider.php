<?php

namespace App\Providers\Filament;

use App\Filament\Staff\Resources\OrderResource;
use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use App\Filament\Auth\Login;
use Filament\Support\Colors\Color;
use Awcodes\Overlook\OverlookPlugin;
use App\Filament\Resources\HeroResource;
use App\Filament\Resources\RoleResource;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Resources\ExpenseResource;
use Awcodes\Overlook\Widgets\OverlookWidget;
use App\Filament\Resources\PermissionResource;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->navigationGroups([
                'Inventory',
                'Karobar',
                'System'
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandLogo(fn() => view('components.light-application-logo'))
            ->darkModeBrandLogo(fn() => view('components.dark-application-logo'))
            ->favicon(asset('storage/favicon.png'))
            ->id('admin')
            ->path('admin')
            // ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::Violet,
                'custom' => Color::Violet
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->plugins([
                FilamentApexChartsPlugin::make(),
                OverlookPlugin::make()
                ->excludes([
                    ExpenseResource::class,
                    HeroResource::class,
                    RoleResource::class,
                    PermissionResource::class
                ])
            ])
            ->widgets([
                // Widgets\AccountWidget::class,
                OverlookWidget::class
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
