<?php
// app/Http/Middleware/LoadMenus.php
namespace App\Http\Middleware;

use Closure;
use App\Models\Menu;

class LoadMenus
{
    public function handle($request, Closure $next)
    {
        // Fetch menus based on user role, if needed
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        view()->share('menus', $menus);

        return $next($request);
    }
}
