@php
$current_route = Route::currentRouteName();
$menu = App\Models\Menu::orderBy('order','ASC')->get();
$submenu = App\Models\SubMenu::orderBy('order','ASC')->get();
$i = 1;
@endphp
<div class="sidebar">
    {{-- <h4>Library <b>Pro</b></h4> --}}
    <ul class="list-unstyled ps-0 mt-4">
        <!-- Dashbsord -->
        @foreach($menu as $key => $value)
        @php
        $is_active_menu = false;
        @endphp
        @if($value->name=='Dashboard')
        <li class="mb-1 {{ $current_route == $value->url ? 'active' : '' }}"><a class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"
                href="{{ route($value->url) }}" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                <span><i class="fa-solid fa-dashboard me-2"></i> {{$value->name}}</span></a>
        </li>
        @else
        <li class="mb-1">
            <a class="btn btn-toggle d-inline-flex align-items-center rounded border-0 {{ $submenu->where('parent_id', $value->id)->pluck('url')->contains($current_route) ? 'collapsed' : '' }}"
                data-bs-toggle="collapse" data-bs-target="#manageLibrary_{{$value->id}}" aria-expanded="false">
                <span><i class="fa-solid fa-book-open me-2"></i> {{$value->name}}</span>
                <i class="fa-solid fa-angle-right"></i>
            </a>
            <div class="collapse" id="manageLibrary_{{$value->id}}" style="">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    @foreach($submenu as $subvalue)
                    @if($value->id == $subvalue->parent_id)
                    <li><a href="{{ route($subvalue->url) }}
                         {{ $current_route == $subvalue->url ? 'active' : '' }}"> {{$subvalue->name}}</a></li>
                    @php
                    if ($current_route == $subvalue->url) {
                    $is_active_menu = true;
                    }
                    @endphp
                    @endif
                    @endforeach
                </ul>
            </div>
        </li>

        @endif
        @php
        $i++;
        @endphp
        @endforeach

    </ul>
</div>