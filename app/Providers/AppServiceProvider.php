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
            'learners.upgrade.renew' => [
                'Dashboard' => route('library.home'),
                'Learners List' => route('learners'),
                'Upgrade Seat' => route('learners.upgrade.renew', $parameters)
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
            'pending.payment.report' => [
                'Dashboard' => route('library.home'),
                'Payment Pending Report' => route('pending.payment.report')
            ],
            'learner.report' => [
                'Dashboard' => route('library.home'),
                'All Learners Report' => route('learner.report')
            ],
            'upcoming.payment.report' => [
                'Dashboard' => route('library.home'),
                'Upcoming Payment Report' => route('upcoming.payment.report')
            ],
            'expired.learner.report' => [
                'Dashboard' => route('library.home'),
                'Expired Learners Report' => route('expired.learner.report')
            ],
            'library.settings' => [
                'Dashboard' => route('library.home'),
                'Library Setting' => route('library.settings')
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
            'learners.list.view' => [
                'Dashboard' => route('library.home'),
                'Library Counts Details' => route('learners.list.view'),
            ],
            'library.feedback' => [
                'Dashboard' => route('library.home'),
                'Library Feedback' => route('library.feedback'),
            ],
            'library.video-training' => [
                'Dashboard' => route('library.home'),
                'Video Tutorials' => route('library.video-training'),
            ],
            'library.upload.form' => [
                'Dashboard' => route('library.home'),
                'Learners List' => route('learners'),
                'Import Learners' => route('library.upload.form'),
            ],

            'report.expense' => [
                'Dashboard' => route('library.home'),
                'Monthly Revenue Reports' => route('report.monthly'),
                'Manage Expense' => route('report.expense', [
                    'year' => request()->year ?? now()->year, // Default to current year if not found
                    'month' => request()->month ?? now()->month, // Default to current month if not found
                ]),
            ],

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
            'learners.upgrade.renew' => 'Upgrade Seat',
            'seats.history' => 'Seat Booking History',
            'seats.history.show' => 'Detailed Seat History',
            'library.myplan' => 'My Plan',
            'library.transaction' => 'My Payment Transactions',
            'report.monthly' => 'Monthly Revenue Report',
            'pending.payment.report' => 'Payment Pending Report',
            'learner.report' => 'All Learners Report',
            'upcoming.payment.report' => 'Upcoming Payment Report',
            'expired.learner.report' => 'Expired Learners Report',
            'library.master' => 'Configure Library',
            'learners.reactive' => 'Reactive Learner',
            'learnerHistory' => 'Learner History',
            'learner.payment' => 'Make Payment',
            'learners.list.view' => 'Library Counts Details',
            'library.settings' => 'Library Setting',
            'library.upload.form' => 'Import Learners',
            'report.expense' => 'Manage Expanse',
            'library.feedback' => 'Library Feedback',
            'library.video-training' => 'Video Tutorials',
        ];

        return $titles[$routeName] ?? ucfirst(str_replace('.', ' ', $routeName));
    }
}
