<?php

namespace App\Providers\Filament;

use App\Filament\App\Pages\AboutUs;
use App\Filament\App\Pages\BloodSupply;
use App\Filament\App\Pages\Donations;
use App\Filament\App\Pages\Home;
use App\Filament\Pages\DonationsForm;
use App\Filament\Pages\DonorForm;
use App\Http\Middleware\RedirectByRole;
use App\View\Components\CustomHeader;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Support\Colors\Color;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    protected static ?string $navigationLabel = 'Custom Navigation Label';

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->renderHook(
                'panels::topbar.end',  // Posisi di akhir top bar
                fn() => view('components.custom-header')
            )
            ->id('app')
            ->path('app')
            ->authGuard('web')
            // ->header(CustomHeader::class)
            ->colors([
                'primary' => Color::Red,
            ])
            ->brandName('Leonicare')
            ->viteTheme('resources/css/app.css')
            ->topNavigation()
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\Filament\App\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\Filament\App\Pages')
            ->pages([])
            ->navigationItems([
                NavigationItem::make('Home')
                    ->url('/app')
                    ->sort(-100),  // Angka terkecil = posisi pertama
                NavigationItem::make('Donations')
                    ->url('/app/donations')
                    ->sort(-50),
                NavigationItem::make('Blood Supply')
                    ->url('/app/blood-supply')
                    ->sort(-30),
                NavigationItem::make('Event')
                    ->url('/app/event')
                    ->sort(-20),
                NavigationItem::make('About Us')
                    ->url('/app/about')
                    ->icon('heroicon-o-information-circle')
                    ->sort(0),
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('My Profile')
                    ->url('profile')
                    ->icon('heroicon-o-cog-6-tooth'),
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\Filament\App\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                RedirectByRole::class,
            ], isPersistent: true);
    }
}
