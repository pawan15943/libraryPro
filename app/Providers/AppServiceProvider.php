<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
   
    public function boot()
    {
        View::composer('*', function ($view) {
            $routeName = Route::currentRouteName();
            // Define breadcrumb and page title logic based on the route
            $breadcrumb = $this->getBreadcrumb($routeName);
            $pageTitle = $this->getPageTitle($routeName);

            $view->with(compact('breadcrumb', 'pageTitle'));
        });
    }

    private function getBreadcrumb($routeName)
    {

        $breadcrumbs = [
            'home' => ['Dashboard' => route('home')],
            'profile' => [
                'Dashboard' => route('home'),
                'Complete Profile' => route('profile')
            ],
        ];

        return $breadcrumbs[$routeName] ?? [];
    }

    private function getPageTitle($routeName)
    {
        // Simple logic to convert route name to page title
        $titles = [
            'home' => 'Dashboard',
            'profile' => 'Complete Profile',
            // Add more route-to-title mappings as needed
        ];

        // Return title or format from the route name
        return $titles[$routeName] ?? ucfirst(str_replace('.', ' ', $routeName));
    }
}
