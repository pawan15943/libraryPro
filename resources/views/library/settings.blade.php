@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<!-- Content -->
<form action="{{ route('library.profile.update') }}" class="validateForm" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <h4 class="mb-4">Library Portal Settings</h4>
                <div class="row g-4">
                    <div class="col-lg-12">
                        <label for="">Library Fav Icon <span>*</span></label>
                        <input type="file" class="form-control @error('library_favicon') is-invalid @enderror" name="library_favicon"
                            value="{{ old('library_favicon', $library->library_favicon ?? '') }}">
                        @error('library_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-12">
                        <label for="">Library Title <span>*</span></label>
                        <input type="text" class="form-control @error('library_title') is-invalid @enderror" name="library_title"
                            value="{{ old('library_title', $library->library_title ?? '') }}">
                        @error('library_title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="col-lg-12">
                        <label for="">Library Meta Description <span>*</span></label>
                        <textarea name="" id="" class="form-control" style="height: 100px !important;"></textarea>
                        @error('library_meta_description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="col-lg-4">
                        <label for="">Library Primary Color <span>*</span></label>
                        <input type="color" class="form-control" >
                        @error('library_address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-4">
                        <label for="">Library Primary Color <span>*</span></label>
                        <select name="" class="form-select" id="">
                            <option value="">Select Language</option>
                            <option value="">English</option>
                            <option value="">Hindi</option>
                        </select>
                        @error('library_address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="col-lg-4">
                        <input type="submit" value="Save Settings" class="btn btn-primary button">
                    </div>
                </div>

            </div>
        </div>


</form>
</div>
@include('library.script')
@endsection