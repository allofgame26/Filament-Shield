<?php

namespace App\Providers\Filament;

use App\Filament\Resources\PeriodeResource;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\UserMenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Resources\CategoryNilaiResource;
use App\Filament\Resources\ClassroomResource;
use App\Filament\Resources\DepartementResource;
use App\Filament\Resources\StudentResource;
use App\Filament\Resources\SubjectResource;
use App\Filament\Resources\TeacherResource;
use App\Filament\Resources\UserResource;
use App\Models\Student;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->plugins([
                FilamentSpatieRolesPermissionsPlugin::make(),
            ])
            ->databaseNotifications()
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder{
                return $builder->groups([
                    NavigationGroup::make()
                        ->items([
                            NavigationItem::make('Dashboard')
                            ->icon('heroicon-o-home')
                            ->isActiveWhen(fn ():bool => request()->routeIs('filament.admin.pages.dashboard'))
                            ->url(fn (): string => Dashboard::getUrl())
                        ]),
                    NavigationGroup::make('Data Akademik')
                        ->items([
                            ...StudentResource::getNavigationItems(),
                            ...TeacherResource::getNavigationItems(),
                            ...SubjectResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Source')
                        ->items([
                            ...CategoryNilaiResource::getNavigationItems(),
                            ...ClassroomResource::getNavigationItems(),
                            ...DepartementResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Setting')
                        ->items([
                            ...PeriodeResource::getNavigationItems(),
                            ...UserResource::getNavigationItems(),
                            NavigationItem::make('roles')
                            ->icon('heroicon-o-user-group')
                            ->isActiveWhen(fn ():bool => request()->routeIs([
                                'filament.admin.resources.roles.index',
                                'filament.admin.resources.roles.create',
                                'filament.admin.resources.roles.view',
                                'filament.admin.resources.roles.edit',
                            ]))
                            ->visible(fn(): bool => auth()->user()->can('role-permission'))
                            ->url( fn (): string => '/admin/roles'),
                            NavigationItem::make('Permission')
                            ->icon('heroicon-o-lock-closed')
                            ->isActiveWhen(fn ():bool => request()->routeIs([
                                'filament.admin.resources.permissions.index',
                                'filament.admin.resources.permissions.create',
                                'filament.admin.resources.permissions.view',
                                'filament.admin.resources.permissions.edit',
                            ]))
                            ->url( fn (): string => '/admin/permissions')
                            ->visible(fn(): bool => auth()->user()->can('role-permission'))
                        ]),
                    ]);
            });
    }

    public function boot(): void{
        Filament::serving(function(){
            Filament::registerUserMenuItems([
                UserMenuItem::make()
                ->label('Settings')
                ->url(PeriodeResource::getUrl())
                ->icon('heroicon-s-cog'),
            ]);
        });
    }
}
