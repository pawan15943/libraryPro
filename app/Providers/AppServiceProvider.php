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
            // Administrator Links
            'home' => ['Dashboard' => route('home')],

            // Library Links
            'library.home' => ['Dashboard' => route('library.home')],
            'profile' => [
                'Dashboard' => route('home'),
                'Library Profile' => route('profile')
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
            'seats' => [
                'Dashboard' => route('library.home'),
                'Seat Assignment' => route('seats')
            ],
            'learners' => [
                'Dashboard' => route('library.home'),
                'Learners List' => route('learners')
            ],
            'learners.show' => [
                'Dashboard' => route('library.home'),
                'Learners List' => route('learners'),
                'Booking Info' => route('learners.show')
            ],
            'learners.edit' => [
                'Dashboard' => route('library.home'),
                'Learners List' => route('learners'),
                'Edit Seat Booking Info' => route('learners.edit')
            ],
            'learners.swap' => [
                'Dashboard' => route('library.home'),
                'Learners List' => route('learners'),
                'Swap Seat' => route('learners.swap')
            ],
            'learners.upgrade' => [
                'Dashboard' => route('library.home'),
                'Learners List' => route('learners'),
                'Upgrade Seat' => route('learners.upgrade')
            ],
            'seats.history' => [
                'Dashboard' => route('library.home'),
                'Seat Booking History' => route('seats.history')
            ],
            'library.myplan' => [
                'Dashboard' => route('library.home'),
                'My Plan' => route('library.myplan')
            ],
            'library.transaction' => [
                'Dashboard' => route('library.home'),
                'My Payment Transactions' => route('library.transaction')
            ],
            'report.monthly' => [
                'Dashboard' => route('library.home'),
                'Monthly Revenue Report' => route('report.monthly')
            ],
            // 'report.expense' => [
            //     'Dashboard' => route('library.home'),
            //     'Monthly Revenue Report' => route('report.monthly'),
            //     'Manage Monthly Exapanse' => route('report.expense')
            // ],
        ];

        return $breadcrumbs[$routeName] ?? [];
    }

    private function getPageTitle($routeName)
    {
        // Simple logic to convert route name to page title
        $titles = [
            // Administrator Portal
            'home' => 'Dashboard',

            // Library Portal
            'library.home' => 'Library Dashboard',
            'profile' => 'Library Profile',
            'subscriptions.choosePlan' => 'Choose Plan',
            'subscriptions.payment' => 'Make Payment',
            'seats' => 'Seat Assignment',
            'learners' => 'Learners List',
            'learners.show' => 'Booking Info',
            'learners.edit' => 'Edit Seat Booking Info',
            'learners.swap' => 'Swap Seat',
            'learners.upgrade' => 'Upgrade Seat',
            'seats.history' => 'Seat Booking History',
            'library.myplan' => 'My Plan',
            'library.transaction' => 'My Payment Transactions',
            'report.monthly' => 'Monthly Revenue Report',
            'c' => 'Configure Library',
        ];

        return $titles[$routeName] ?? ucfirst(str_replace('.', ' ', $routeName));
    }
}
