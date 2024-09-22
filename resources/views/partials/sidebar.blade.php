@php
$current_route = Route::currentRouteName();
// $menus = App\Models\Menu::orderBy('order','ASC')->get();
// $submenu = App\Models\SubMenu::orderBy('order','ASC')->get();
// $i = 1;

@endphp
<div class="sidebar">
    {{-- <h4>Library <b>Pro</b></h4> --}}

    <ul>
        @foreach($menus as $menu)
            @if(is_null($menu->parent_id) && ($menu->guard === null || Auth::guard($menu->guard)->check()))  <!-- Check if it's a parent menu and the guard matches -->
                <li>
                    <a href="{{ route($menu->url) ?? '#' }}">
                        <i class="{{ $menu->icon }}"></i> {{ $menu->name }}
                    </a>
                    @if($menu->children->count())  <!-- Check if it has children -->
                        <ul class="submenu">
                            @foreach($menu->children as $submenu)
                                @if($submenu->guard === null || Auth::guard($submenu->guard)->check())  <!-- Check guard for submenu -->
                                    <li>
                                        <a href="{{ route($submenu->url) }}">{{ $submenu->name }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endif
        @endforeach
    </ul>
    
    
    

</div>