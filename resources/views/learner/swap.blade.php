@extends('layouts.admin')
@section('content')

@php
      use Carbon\Carbon;
@endphp

<div class="row">
    <!-- Page Main Content -->
    <div class="col-lg-12">
        <!-- Add City Fields -->
        <div class="">
            <!-- Add City Fields -->
            <div class="card-body p-0">
                @php
                    $today = Carbon::today();
                    $endDate = Carbon::parse($customer->plan_end_date);
                    $diffInDays = $today->diffInDays($endDate, false);
                @endphp
               
                    <input id="plan_type_id" type="hidden" name="plan_type_id" value="{{$customer->plan_type_id }}">
                    
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="actions">
                                <div class="upper-box">
                                    <h4 class="mb-3">Leraners Info</h4>
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
                                <form action="{{ route('learners.swap-seat', $customer->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                <div class="action-box">
                                    <h4 class="mb-4">Actionables</h4>
                                    <input id="user_id" type="hidden" name="customer_id" value="{{ $customer->id}}">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="">Current Seat No. <span>*</span></label>
                                            <input  class="form-control"   value="{{ $customer->seat_no }} - {{ $customer->plan_type_name }}" readonly>
                                           
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="">Select Seat<span>*</span></label>
                                            <select name="seat_no" id="new_seat_id" class="form-control form-control-sm h-auto">
                                                <option>Select Seat</option>
                                                @foreach($available_seat as $id => $seat_no)
                                                <option value="{{ $id }}"> {{ $seat_no }}</option>
                                                @endforeach
                                            </select>
                                            @error('seat_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-6">
                                        <span>Current Seat Status</span>
                                        <span id="swap_status"></span>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-3">
                                            <input type="submit" class="btn btn-primary btn-block" id="swapsubmit" value="Swap Seat">
                                        </div>
                                    </div>
                                    
                                </div>
                                </form>
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

               


        </div>
    </div>
</div>
</div>
<script>
    document.getElementById("swapsubmit").disabled = true;
</script>
@include('library.script')

@endsection