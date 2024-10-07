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
    <div class="col-lg-4">
        <div class="import-data">
            <form action="{{ route('csv.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4" >
                    <div class="col-lg-12">
                        <label for="">Select File</label>
                        <input type="file" class="form-control" name="csv_file">
                        <a href="{{ asset('sample/sample-file.csv') }}"><small>Download Sample CSV Fil</small></a>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary button">Import Data</button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>




@endsection