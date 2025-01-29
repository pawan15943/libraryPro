@extends('layouts.admin')
@section('content')


<!-- Learners Blog -->
<div class="row">
    @foreach($data as $key => $value)
        <div class="col-lg-4">
            <div class="blog-box">
                <img src="{{ $value->header_image }}" alt="blog-title" class="blog-thumb">
                <h4>{{ $value->page_title }}</h4>
                <a href="">Read Full Article >></a>
            </div>
        </div>
    @endforeach
</div>

@endsection