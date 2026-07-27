<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('admin', function (Request $request) {
            $userKey = optional($request->user())->id ?: $request->ip();

            return Limit::perMinute(120)->by('admin:'.$userKey);
        });

        Blade::if('role', function ($role) {
            $user = Auth::user();
            return $user && $user->role && $user->role->name === $role;
        });

        Blade::if('hasperm', function ($permission) {
            $user = Auth::user();
            return $user && $user->hasPermission($permission);
        });

        // Public menu view composer
        View::composer('layouts.public', function ($view) {
            // Check if menus table exists to avoid errors during migrations
            if (\Illuminate\Support\Facades\Schema::hasTable('menus')) {
                $headerMenus = \App\Models\Menu::with('children')
                    ->where('parent_id', null)
                    ->where('posisi', 'header')
                    ->where('status', 'active')
                    ->orderBy('urutan')
                    ->get();

                $footerMenus = \App\Models\Menu::with('children')
                    ->where('parent_id', null)
                    ->where('posisi', 'footer')
                    ->where('status', 'active')
                    ->orderBy('urutan')
                    ->get();
            } else {
                $headerMenus = collect();
                $footerMenus = collect();
            }

            $view->with(compact('headerMenus', 'footerMenus'));
        });
    }
}
