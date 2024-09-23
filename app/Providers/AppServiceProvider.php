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
            'subscriptions.choosePlan' => [
                'Dashboard' => route('home'),
                'Choose Plan' => route('subscriptions.choosePlan')
            ],
            'subscriptions.payment' => [
                'Dashboard' => route('home'),
                'Make Payment' => route('subscriptions.payment')
            ],
            'library.master' => [
                'Dashboard' => route('home'),
                'Configure Library' => route('library.master')
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
            'subscriptions.choosePlan' => 'Choose Plan',
            'subscriptions.payment' => 'Make Payment',
            'c' => 'Configure Library',
        ];

        // Return title or format from the route name
        return $titles[$routeName] ?? ucfirst(str_replace('.', ' ', $routeName));
    }
}
