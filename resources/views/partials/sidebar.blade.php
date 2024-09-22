@php
$current_route = Route::currentRouteName();
$menus = App\Models\Menu::orderBy('order','ASC')->get();
$submenu = App\Models\SubMenu::orderBy('order','ASC')->get();
$i = 1;
@endphp
<div class="sidebar">
    {{-- <h4>Library <b>Pro</b></h4> --}}

    <ul class="menu">
        @foreach($menus as $menu)
            <li>
                <a href="{{ route($menu->url) ?? '#' }}">
                    <i class="{{ $menu->icon }}"></i> {{ $menu->name }}
                </a>
                @if($menu->children->count())
                    <ul class="submenu">
                        @foreach($menu->children as $submenu)
                            <li><a href="{{ route($submenu->url) }}">{{ $submenu->name }}</a></li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>

</div>