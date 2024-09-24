<?php

namespace App\Helpers;

use App\Models\Hour;
use App\Models\Library;
use App\Models\LibraryTransaction;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\PlanType;
use App\Models\Seat;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;



class HelperService
{
    protected static $titleMap = [
        'dashboard' => 'Dashboard',
        'student.index' => 'Student List',
        'student.create' => 'Create Student',
        'student.edit' => 'Edit Student',
        // Add more route-specific titles here
    ];

    public static function generateBreadcrumbs()
    {
        $breadcrumbs = collect();
        $currentRouteName = Route::currentRouteName();
        $routes = explode('.', $currentRouteName);

        foreach ($routes as $key => $route) {
            $routeName = implode('.', array_slice($routes, 0, $key + 1));
            if ($route === 'student') {
                $routeName = 'student.index'; // Map "student" to the "student.index" route
            }
            $url = '#'; // Default to not clickable for the last breadcrumb

            if (Route::has($routeName)) {
                try {
                    $parameters = Request::route()->parameters();
                    $url = count($parameters) > 0 ? route($routeName, $parameters) : route($routeName);
                } catch (\Exception $e) {
                    $url = '#';
                }
            }

            $breadcrumbs->push([
                'name' => ucfirst(str_replace('-', ' ', $route)),
                'url' => ($key === count($routes) - 1) ? '#' : $url
            ]);
        }

        return $breadcrumbs;
    }

    public static function generateTitle()
    {
        $breadcrumbs = self::generateBreadcrumbs();
        $routeName = Route::currentRouteName();

        if (array_key_exists($routeName, self::$titleMap)) {
            return self::$titleMap[$routeName];
        }

        $titleParts = $breadcrumbs->pluck('name')->toArray();
        return implode(' ', $titleParts);
    }

    public static function generateLicenseKey(string $macAddress): string
    {
        return hash('sha256', $macAddress . '125352-ABXG56-H7Y5F5-45IJNN');
    }

   
}




