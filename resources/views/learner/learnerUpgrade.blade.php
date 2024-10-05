@extends('layouts.admin')
@section('content')
@php
use Carbon\Carbon;
$current_route = Route::currentRouteName();
$today = Carbon::today();
$endDate = Carbon::parse($customer->plan_end_date);
$diffInDays = $today->diffInDays($endDate, false);

if (Route::currentRouteName() == 'learners.upgrade') {
$displayNone = 'style="display: none;"';

$readonlyStyle = 'pointer-events: none; background-color: #e9ecef;';

} else {
$displayNone = '';

$readonlyStyle = '';
}
@endphp



<form action="{{ route('learners.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-9">
            <div class="actions">
                <div class="upper-box">
                    <div class="d-flex">
                        <h4 class="mb-3">Leraners Info</h4>
                        <a href="javascript:void(0);" class="go-back"
                            onclick="window.history.back();">Go
                            Back <i class="fa-solid fa-backward pl-2"></i></a>
                    </div>
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <label for="">Seat Owner Name <span>*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror char-only" placeholder="Full Name" name="name" id="name" value="{{ old('name', $customer->name) }}" readonly>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label for="">DOB <span>*</span></label>
                            <input type="date" class="form-control @error('dob') is-invalid @enderror" placeholder="DOB" name="dob" id="dob" value="{{ old('dob', $customer->dob) }}" readonly>
                            @error('dob')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label for="">Mobile Number <span>*</span></label>
                            <input type="text" class="form-control @error('mobile') is-invalid @enderror digit-only" maxlength="10" minlength="10" placeholder="Mobile Number" name="mobile" id="mobile" value="{{ old('mobile', $customer->mobile) }}" readonly>
                            @error('mobile')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label for="">Email Id <span>*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Id" name="email" id="email" value="{{ old('email', $customer->email) }}" readonly>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="action-box">
                    <h4 class="mb-4">Actionables
                        <div class="info-container">
                            <i class="fa-solid fa-circle-info info-icon"></i>
                            <div class="info-card">
                                <h3 class="info-title">Upgrade Seat</h3>
                                <p class="info-details">In a seat upgrade, the learner selects a higher plan, checks seat availability, and pays the difference. The system allocates the upgraded seat, adjusts the remaining time from the current plan, and closes the old reservation. The learner then uses the upgraded seat for the new duration.</p>
                            </div>
                        </div>
                    </h4>
                    <div class="row g-4">
                        <input id="edit_seat" type="hidden" name="seat_no" value="{{ old('seat_no', $customer->seat_no) }}">
                        <input type="hidden" name="user_id" value="{{ old('user_id', $customer->id) }}">

                        <div class="col-lg-4">
                            <label for=""> Plan <span>*</span></label>
                            <select id="plan_id" class="form-control @error('plan_id') is-invalid @enderror" name="plan_id" style="{{ $readonlyStyle }}">
                                <option value="">Select Plan</option>
                                @foreach($plans as $key => $value)
                                <option value="{{ $value->id }}" {{ old('plan_id', $customer->plan_id) == $value->id ? 'selected' : '' }}>{{ $value->name }}</option>
                                @endforeach
                            </select>
                            @error('plan_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-4">
                            <label for="">Plan Type <span>*</span></label>
                            <select id="plan_type_id2" class="form-control @error('plan_type_id') is-invalid @enderror" name="plan_type_id">
                                <option value="">Select Plan Type</option>
                                @foreach($planTypes as $planType)
                                <option value="{{ $planType->id }}"
                                    {{ old('plan_type_id', $customer->plan_type_id) == $planType->id ? 'selected' : '' }}>
                                    {{ $planType->name }}
                                </option>
                                @endforeach


                            </select>
                            @error('plan_type_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-4">
                            <label for="">Plan Price <span>*</span></label>
                            <input id="plan_price_id" class="form-control @error('plan_price_id') is-invalid @enderror" name="plan_price_id" value="{{ old('plan_price_id', $customer->plan_price_id) }}" readonly>
                            @error('plan_price_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-4">
                            <label for="">Plan Starts On <span>*</span></label>
                            <input type="date" class="form-control @error('plan_start_date') is-invalid @enderror" placeholder="Plan Starts On" name="plan_start_date" id="plan_start_date" value="{{ old('plan_start_date', $customer->plan_start_date) }}" readonly disabled>
                            @error('plan_start_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-lg-4">
                            <label for="">Plan End On <span>*</span></label>
                            <input type="date" class="form-control @error('plan_end_date') is-invalid @enderror" placeholder="Plan Starts On" name="plan_end_date" id="plan_end_date" value="{{ old('plan_end_date', $customer->plan_end_date) }}" readonly>
                            @error('plan_end_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-3">
                            <input type="submit" class="btn btn-primary btn-block button" id="submit" value="Update Seat Info">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="seat--info">
                <span class="d-block">Seat No : {{ $customer->seat_no}}</span>
                <img src="{{ asset($customer->image) }}" alt="Seat" class="seat py-3">
                <p>{{ $customer->plan_name}}</p>
                <button>Booked for <b>{{ $customer->plan_type_name}}</b></button>
                <span class="text-success">Plan Expires in {{$diffInDays}} Days</span>
            </div>
        </div>
    </div>

</form>



@include('learner.script')
@endsection