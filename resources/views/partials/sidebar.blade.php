@php
$current_route = Route::currentRouteName();
@endphp

<div class="sidebar scroll">
    <h4><b>Libraro</b></h4> 
        <ul class="list-unstyled ps-0 mt-4">
        @foreach($menus as $menu)
            {{-- Check if it's a parent menu and the guard matches --}}
            @if(is_null($menu->parent_id) && ($menu->guard === null || Auth::guard($menu->guard)->check()))
                {{-- Parent menu logic --}}
                <li class="mb-1 {{ $current_route == $menu->url ? 'active' : '' }}">
                    <a class="btn btn-toggle d-inline-flex align-items-center rounded border-0 {{ $menu->children->count() ? '' : 'flex-start' }}" 
                       href="{{ route($menu->url) ?? '#' }}" 
                       data-bs-toggle="{{ $menu->children->count() ? 'collapse' : '' }}" 
                       data-bs-target="#menu_{{ $menu->id }}" 
                       aria-expanded="false">
                        <i class="{{ $menu->icon }} me-2"></i> {{ $menu->name }}
                        @if($menu->children->count())
                            <i class="fa-solid fa-angle-right ms-auto"></i>
                        @endif
                    </a>
                    
                    {{-- If menu has children (submenu) --}}
                    @if($menu->children->count())
                        <div class="collapse" id="menu_{{ $menu->id }}">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                @foreach($menu->children as $submenu)
                                    {{-- Check guard for submenu --}}
                                    @if($submenu->guard === null || Auth::guard($submenu->guard)->check())
                                            
                                        @if($submenu->guard !== 'library')
                                        <li>
                                            <a href="{{ route($submenu->url) }}" 
                                            class="{{ $current_route == $submenu->url ? 'active' : '' }}">
                                                {{ $submenu->name }}
                                            </a>
                                        </li>
                                        {{-- Show submenu for library guard only if conditions are met --}}
                                        @elseif(Auth::guard('library')->check() && $checkSub && $ispaid && $isProfile && $iscomp)
                                            <li>
                                                <a href="{{ route($submenu->url) }}" 
                                                class="{{ $current_route == $submenu->url ? 'active' : '' }}">
                                                    {{ $submenu->name }}
                                                </a>
                                            </li>
                                        @endif
                                       
                                        
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </li>
            @endif
        @endforeach
    </ul>
</div>
