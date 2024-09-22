<?php

namespace App\Helpers;

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

            // Check if the route exists
            if (Route::has($routeName)) {
                try {
                    // Get current parameters
                    $parameters = Request::route()->parameters();

                    // If there are parameters, try to generate the URL with them
                    if (count($parameters) > 0) {
                        // Generate URL with the required parameters
                        $url = route($routeName, $parameters);
                    } else {
                        // Generate URL without parameters
                        $url = route($routeName);
                    }
                } catch (\Exception $e) {
                    // If there's an exception, do not generate the URL
                    $url = '#';
                }
            }

            // Push breadcrumb to the collection
            $breadcrumbs->push([
                'name' => ucfirst(str_replace('-', ' ', $route)), // Capitalize and format the name
                'url' => ($key === count($routes) - 1) ? '#' : $url // Last breadcrumb is not clickable
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

        // If no specific title is found, generate a generic one
        $titleParts = $breadcrumbs->pluck('name')->toArray();

        return implode(' ', $titleParts);
    }

    public static function generateLicenseKey(string $macAddress): string
    {
        return hash('sha256', $macAddress . '125352-ABXG56-H7Y5F5-45IJNN');
    }

}
