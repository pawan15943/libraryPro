<nav aria-label="breadcrumb">
    <ol class="breadcrumb">@php
    @endphp
        @foreach($breadcrumb as $name => $link)
            <li class="breadcrumb-item"><a href="{{ $link }}">{{ $name }}</a></li>
        @endforeach
    </ol>
</nav>
<title>{{ $pageTitle }}</title>
<h1>{{ $pageTitle }}</h1>