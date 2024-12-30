@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div>
    <!-- Content -->
    <form action="{{ route('library.settings.store') }}" class="validateForm" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <label for="">Library Fav Icon <span>*</span></label>
                            <input type="file" class="form-control @error('library_favicon') is-invalid @enderror" name="library_favicon"
                                value="{{ old('library_favicon', $library->library_favicon ?? '') }}">
                            @error('library_favicon')
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
                            <textarea name="library_meta_description" class="form-control" style="height: 100px !important;">{{ old('library_meta_description', $library->library_meta_description ?? '') }}</textarea>
                            @error('library_meta_description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-lg-6">
                            <label for="">Library Primary Color <span>*</span></label>
                            <input type="color" name="library_primary_color" class="form-control" value="{{ old('library_primary_color', $library->library_primary_color ?? '') }}">
                            @error('library_primary_color')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label for="">Library Language <span>*</span></label>
                            <select name="library_language" class="form-select" id="">
                                <option value="">Select Language</option>
                                <option value="English" {{ old('library_language', $library->library_language ?? '') == 'English' ? 'selected' : '' }}>English</option>
                                <option value="Hindi" {{ old('library_language', $library->library_language ?? '') == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                            </select>
                            @error('library_language')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>


                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-2">
                            <input type="submit" value="Save Settings" class="btn btn-primary button">
                        </div>
                    </div>

                </div>
            </div>
    </form>

</div>
</div>
@include('library.script')
@endsection