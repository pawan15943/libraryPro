@extends('layouts.admin')

@section('content')


<!-- Breadcrumb -->


@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row justify-content-center mb-4 mt-4">
    <div class="col-lg-3">
        <form action="{{ route('csv.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="csv_file">
            <button type="submit">Upload</button>
        </form>
    </div>
</div>




@endsection