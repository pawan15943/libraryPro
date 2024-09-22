@extends('layouts.admin')

@section('content')

<!-- Breadcrumb -->
<div class="row">
    <div class="d-flex bradcrumb">
        <h4>Add Library Plan</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Library Plan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="row">
        <form action="{{ route('library.store') }}" class="validateForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <!-- Library Name -->
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Library Name<sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control char-only @error('library_name') is-invalid @enderror" name="library_name"
                            value="{{ old('library_name') }}">
                        @error('library_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <!-- Library Email -->
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Library Email Id</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <!-- Library Mobile -->
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Library Contact No.</label>
                        <input type="text" class="form-control digit-only @error('library_mobile') is-invalid @enderror" name="library_mobile" maxlength="10"
                            value="{{ old('library_mobile') }}">
                        @error('library_mobile')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Library Owner Name<sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control @error('library_owner') is-invalid @enderror" name="library_owner"
                            value="{{ old('library_owner') }}" >
                        @error('library_owner')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>


                <!-- State -->
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>State</label>
                        <select name="state_id" id="stateid" class="form-control @error('state_id') is-invalid @enderror">
                            <option value="">Select State</option>
                            @foreach($states as $value)
                            <option value="{{ $value->id }}" {{ old('state_id') == $value->id ? 'selected' : '' }}>
                                {{ $value->state_name }}
                            </option>
                            @endforeach
                        </select>
                        @error('state_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <!-- City -->
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>City</label>
                        <select name="city_id" id="cityid" class="form-control char-only @error('city_id') is-invalid @enderror">
                            <option value="">Select City</option>
                        </select>
                        @error('city_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <!-- Address -->
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Address</label>
                        <textarea class="form-control h-auto @error('library_address') is-invalid @enderror" name="library_address"
                            >{{ old('library_address') }}</textarea>
                        @error('library_address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <!-- ZIP Code -->
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>ZIP Code</label>
                        <input type="text" class="form-control digit-only @error('library_zip') is-invalid @enderror" name="library_zip" maxlength="6"
                            value="{{ old('library_zip') }}">
                        @error('library_zip')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <!-- Library Type -->
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Library Type</label>
                        <input type="text" class="form-control @error('library_type') is-invalid @enderror" name="library_type"
                            value="{{ old('library_type') }}">
                        @error('library_type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <!-- Profile Image -->
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Library Logo</label>
                        <input type="file" class="form-control @error('library_logo') is-invalid @enderror" name="library_logo" accept="image/*">
                        @error('library_logo')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="">Status</option>
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-block button">
                        {{ isset($library) ? 'Update Library' : 'Add Library' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


@include('library.script')

@endsection