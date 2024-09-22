<?php

namespace App\Http\Middleware;

use App\Models\Library;
use App\Models\LibraryTransaction;
use App\Models\Menu;
use Closure;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class LoadMenus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
     public function handle($request, Closure $next)
     {
        $checkSub = false;
        $ispaid = false;
        $iscomp = false;
        $isProfile = false;
        $isEmailVeri = false;
         // Fetch menus based on user role, if needed
         $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
         view()->share('menus', $menus);
     
         if (Auth::check()) {
         $checkSub = LibraryTransaction::where('library_id', Auth::user()->id)->exists();
         $ispaid = Library::where('id', Auth::user()->library_id)->where('is_paid', 1)->exists();
         $iscomp = Library::where('id', Auth::user()->library_id)->where('status', 1)->exists();
         $isProfile = Library::where('id', Auth::user()->library_id)->where('is_profile', 1)->exists();
         $isEmailVeri = Library::where('id', Auth::user()->library_id)->whereNotNull('email_verified_at')->exists();
         }
         // Share the variables with all views
         View::share('checkSub', $checkSub);
         View::share('ispaid', $ispaid);
         View::share('isProfile', $isProfile);
         View::share('isEmailVeri', $isEmailVeri);
     
         return $next($request);
     }
     
}
