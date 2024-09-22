@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Breadcrumb -->
<div class="row">
    <div class="d-flex bradcrumb">
        <h4>Complate Profile</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Complate Profile</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="steps">
            <ul>
                <li ><a href="{{route('subscriptions.choosePlan')}}">Choose Plan</a></li>
                <li><a href="{{route('subscriptions.payment')}}">Make Payment</a></li>
                <li class="active"><a href="{{route('profile')}}">Update Profile</a></li>
                <li ><a href="{{route('library.master')}}">Configure Library</a></li>
            </ul>
          
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <h2 class="text-center">A few details to make it yours!</h2>
    </div>
</div>
<!-- Content -->
<div class="card  mb-4 mt-4">
    

    <form action="{{ route('library.profile.update') }}" class="validateForm" method="POST" enctype="multipart/form-data">
        @csrf
        <h6>Library Info :</h6>
        <div class="row g-4">
            <div class="col-lg-9">
                <label for="">Library Full Name <span>*</span></label>
                <input type="text" class="form-control @error('library_name') is-invalid @enderror" name="library_name"
                    value="{{ old('library_name', $library->library_name ?? '') }}" placeholder="Enter library name">
                @error('library_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="col-lg-6">
                <label for="">Library Contact No (WhatsApp No.) <span>*</span></label>
                <input type="text" class="form-control digit-only @error('library_mobile') is-invalid @enderror" name="library_mobile" maxlength="10"
                    value="{{ old('library_mobile', $library->mobile ?? '') }}">
                @error('library_mobile')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="col-lg-6">
                <label for="">Library Mail Id <span>*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email', $library->email ?? '') }}">
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="col-lg-6">
                <label for="">Library Address <span>*</span></label>
                <textarea class="form-control @error('library_address') is-invalid @enderror" name="library_address"
                    style="height:auto !important; ">{{ old('library_address', $library->address ?? '') }}</textarea>
                @error('library_address')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="col-lg-6">
                <label for="">Library ZIP Code <span>*</span></label>
                <input type="text" class="form-control digit-only @error('library_zip') is-invalid @enderror" name="library_zip" maxlength="6"
                    value="{{ old('library_zip', $library->zip ?? '') }}">
                @error('library_zip')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <h6 class="mt-5">Library Owner Info :</h6>
        <div class="row g-4">

            <div class="col-lg-12">
                <label for="">Owner Name <span>*</span></label>
                <input type="text" class="form-control char-only" name="library_owner" value="{{ old('library_owner', $library->owner_name ?? '') }}">
            </div>

            <div class="col-lg-4">
                <label for="">State <span>*</span></label>
                <select name="state_id" id="stateid" class="form-select @error('state_id') is-invalid @enderror">
                    <option value="">Select State</option>
                    @foreach($states as $value)
                    <option value="{{ $value->id }}" {{ old('state_id', $library->state_id ?? '') == $value->id ? 'selected' : '' }}>
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

            <div class="col-lg-4">
                <label for="">City <span>*</span></label>
                <select name="city_id" id="cityid" class="form-select @error('city_id') is-invalid @enderror">
                    <option value="">City</option>
                </select>
                @error('city_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="col-lg-4">
                <label for="">Upload Logo Image <span>*</span></label>
                <input type="file" class="form-control" name="library_logo">
                @error('library_logo')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="col-lg-3">
                <button type="submit" value="Login" placeholder="Email Id" class="btn btn-primary button">Update Profile</button>
            </div>
        </div>
    </form>
</div>
@include('library.script')
@endsection