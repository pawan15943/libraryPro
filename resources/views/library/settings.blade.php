@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<!-- Content -->
<form action="{{ route('library.profile.update') }}" class="validateForm" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row mb-4">
        <div class="col-lg-9">
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
                    <div class="col-lg-6">
                        <label for="">Library Title <span>*</span></label>
                        <input type="text" class="form-control @error('library_title') is-invalid @enderror" name="library_title"
                            value="{{ old('library_title', $library->library_title ?? '') }}">
                        @error('library_title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="col-lg-6">
                        <label for="">Library Meta Description <span>*</span></label>
                        <textarea type="text" placeholder="Enter Library Meta Description" class="form-control digit-only @error('library_meta_description') is-invalid @enderror" name="library_mobile" maxlength="10"
                            value="">{{ old('library_meta_description', $library->library_meta_description ?? '') }}</textarea>
                        @error('library_meta_description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>



                    <div class="col-lg-12">
                        <label for="">Library Primary Color <span>*</span></label>
                        <textarea rows="5" class="form-control @error('library_address') is-invalid @enderror" name="library_address"
                            style="height:auto !important; ">{{ old('library_address', $library->library_address ?? '') }}</textarea>
                        @error('library_address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

            </div>
        </div>


</form>
</div>
@include('library.script')
@endsection