@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="error-box">
                <img src="" alt="">
                <img src="{{ asset('public/img/404.png') }}" alt="404" class="img-fluid">
                <h4 class="text-center"><a href="{{route('/')}}" class="btn btn-primary button">Go to Dashbaord >></a></h4>
            </div>
        </div>
    </div>
</div>
@endsection