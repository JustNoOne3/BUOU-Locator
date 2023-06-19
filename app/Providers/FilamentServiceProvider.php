<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Foundation\Vite;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\ServiceProvider;
use App\Filament\Resources\UserResource;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::serving(function() {
            Filament::registerTheme(
                app(Vite::class)('resources/css/filament.css'),
            );
            if(auth()->user()){
                if(auth()->user()->is_admin == 1 && auth()->user()->hasAnyRole(['admin', 'developer'])) {
                    Filament::registerUserMenuItems([
                        UserMenuItem::make()
                            ->label('Manage Users')
                            ->url(UserResource::getUrl())
                            ->icon('heroicon-s-users')
                    ]);
               }
            }
        });
    }
}
