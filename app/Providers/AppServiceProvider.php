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

    private function getBreadcrumb($routeName, $parameters = [])
    {
        // Ensure $parameters is always an array
        $parameters = is_array($parameters) ? $parameters : [];

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
                'Booking Info' => route('learners.show', $parameters)
            ],
            'learners.edit' => [
                'Dashboard' => route('library.home'),
                'Learners List' => route('learners'),
                'Edit Seat Booking Info' => route('learners.edit', $parameters)
            ],
            'learners.swap' => [
                'Dashboard' => route('library.home'),
                'Learners List' => route('learners'),
                'Swap Seat' => route('learners.swap', $parameters)
            ],
            'learners.upgrade' => [
                'Dashboard' => route('library.home'),
                'Learners List' => route('learners'),
                'Upgrade Seat' => route('learners.upgrade', $parameters)
            ],
            'seats.history' => [
                'Dashboard' => route('library.home'),
                'Seat Booking History' => route('seats.history')
            ],
            'seats.history.show' => [
                'Dashboard' => route('library.home'),
                'Seat Booking History' => route('seats.history'),
                'Detailed History' => route('seats.history.show', $parameters)
            ],
            'learners.reactive' => [
                'Dashboard' => route('library.home'),
                'Learners List' => route('learners'),
                'Reactive Learner' => route('learners.reactive', $parameters)
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
            'learnerHistory' => [
                'Dashboard' => route('library.home'),
                'Learner History' => route('learnerHistory'),
            ],
            'learner.payment' => [
                'Dashboard' => route('library.home'),
                'Learners List' => route('learners'),
                'Make Payment' => route('learner.payment', $parameters),
            ],
            // 'report.expense' => [
            //     'Dashboard' => route('library.home'),
            //     'Monthly Revenue Report' => route('report.monthly'),
            //     'Manage Monthly Expense' => route('report.expense', ['year' => $parameters['year'] ?? null, 'month' => $parameters['month'] ?? null]),
            // ],
        ];

        return $breadcrumbs[$routeName] ?? [];
    }


    private function getPageTitle($routeName, $parameters = [])
    {
        // Ensure $parameters is always an array (not used here but for consistency)
        $parameters = is_array($parameters) ? $parameters : [];

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
            'seats.history.show' => 'Detailed Seat History',
            'library.myplan' => 'My Plan',
            'library.transaction' => 'My Payment Transactions',
            'report.monthly' => 'Monthly Revenue Report',
            'library.master' => 'Configure Library',
            'learners.reactive' => 'Reactive Learner',
            'learnerHistory' => 'Learner History',
            'learner.payment' => 'Make Payment',
        ];

        return $titles[$routeName] ?? ucfirst(str_replace('.', ' ', $routeName));
    }
}
