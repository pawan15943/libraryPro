@extends('layouts.admin')
@section('content')

@php
use Carbon\Carbon;
$today = Carbon::today();
$endDate = Carbon::parse($customer->plan_end_date);
$diffInDays = $today->diffInDays($endDate, false);
@endphp

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
<input id="plan_type_id" type="hidden" name="plan_type_id" value="{{$customer->plan_type_id }}">

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
                    <div class="col-lg-6">
                        <label for="">Plan  <span>*</span></label>
                        <input type="text" class="form-control" value="{{ $customer->plan_name }}" readonly>
                    </div>
                    <div class="col-lg-6">
                        <label for="">Plan Type <span>*</span></label>
                        <input type="text" class="form-control" value="{{ $customer->plan_type_name }}" readonly>
                    </div>
                    <div class="col-lg-6">
                        <label for="">Payment Mode</label>
                        <input type="text" class="form-control" 
                        value="{{ $customer->payment_mode == 1 ? 'Online' : ($customer->payment_mode == 2 ? 'Offline' : 'Pay Later') }}" 
                        readonly>
                    </div>
                    <div class="col-lg-6">
                        <label for="">Payment Status</label>
                        <input type="text" class="form-control"  
                        value="{{ $customer->is_paid == 1 ? 'Paid' : 'Unpaid' }}" 
                        readonly>
                    </div>
                  
                </div>
            </div>
            <form action="{{route('learner.payment.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="action-box">
                    <h4 class="mb-4">Actionables <div class="info-container">
                            <i class="fa-solid fa-circle-info info-icon"></i>
                            <div class="info-card">
                                <h3 class="info-title">Payment</h3>
                                <p class="info-details">Learners can request to change their current
                                    seat to another available seat. If the requested seat is
                                    available, the learner’s current seat will be swapped with the
                                    new one.</p>
                            </div>
                        </div>
                    </h4>
                    <input id="user_id" type="hidden" name="learner_id" value="{{ $customer->id}}">
                    <input id="library_id" type="hidden" name="library_id" value="{{ $customer->library_id}}">
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="">Plan Price <span>*</span></label>
                            <input type="text" class="form-control " name="paid_amount" id="paid_amount" value="{{ old('paid_amount', $customer->plan_price_id ) }}" readonly>

                        </div>
                        <div class="col-lg-6">
                            <label for="">Transaction Date <span>*</span></label>
                            <input type="date" class="form-control @error('paid_date') is-invalid @enderror" placeholder="Transaction Date" name="paid_date" id="paid_date" value="{{ old('paid_date', $customer->paid_date) }}">
                            @error('paid_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        @if($customer->payment_mode==3)
                            
                        <div class="col-lg-6">
                            <label for="">Transaction Number <span>*</span></label>
                            <input type="text" class="form-control @error('transaction_id') is-invalid @enderror digit-only" placeholder="Transaction Number" name="transaction_id" id="transaction_id" value="{{ old('transaction_id') }}">
                            @error('transaction_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                            
                        @endif
                        
                        <div class="col-lg-6">
                            <label for="">Upload Payment Proof <span>*</span></label>
                            <input type="file" class="form-control @error('transaction_image') is-invalid @enderror " placeholder="Transaction Number" name="transaction_image" id="transaction_image" value="{{ old('transaction_image') }}">
                            @error('transaction_image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-3">
                            <input type="submit" class="btn btn-primary btn-block button" value="Payment">
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



@include('learner.script')

@endsection