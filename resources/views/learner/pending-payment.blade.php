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
<input id="plan_type_id" type="hidden" name="plan_type_id" >

<div class="row g-4">
    <div class="col-lg-9 order-2 order-md-1">
        <div class="actions">
            <div class="upper-box">
                <div class="d-flex">
                    <h4 class="mb-3">Leraners Info</h4>
                    <a href="javascript:void(0);" class="go-back"
                        onclick="window.history.back();">Go
                        Back <i class="fa-solid fa-backward pl-2"></i></a>
                </div>
                <div class="row g-4">
                    <div class="col-lg-6 col-6">
                        <label for="">Seat Owner Name <span>*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror char-only" placeholder="Full Name" name="name" id="name" value="{{ old('name', $customer->learner->name) }}" readonly>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 col-6">
                        <label for="">DOB <span>*</span></label>
                        <input type="date" class="form-control @error('dob') is-invalid @enderror" placeholder="DOB" name="dob" id="dob" value="{{ old('dob', $customer->learner->dob) }}" readonly>
                        @error('dob')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 col-6">
                        <label for="">Mobile Number <span>*</span></label>
                        <input type="text" class="form-control @error('mobile') is-invalid @enderror digit-only" maxlength="10" minlength="10" placeholder="Mobile Number" name="mobile" id="mobile" value="{{ old('mobile', $customer->learner->mobile) }}" readonly>
                        @error('mobile')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 col-6">
                        <label for="">Email Id <span>*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Id" name="email" id="email" value="{{ old('email', $customer->learner->email) }}" readonly>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                </div>
            </div>
           
            <form action="{{route('learner.pending.payment.store')}}" method="POST" enctype="multipart/form-data"   class="payment_page">
                @csrf
                @method('POST')
                <div class="action-box">
                    <h4 class="mb-4">Actionables 
                        <div class="info-container">  
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
                    <p class="text-danger">Note : Here we are displaying the active plan Payment information that has been completed.</p>
                  
                    <div class="col-lg-6 col-6">
                        <label for="">Pending Payment </label>
                        
                        <select  id="transaction_id" class="form-control @error('transaction_id') is-invalid @enderror" name="transaction_id" >
                            <option value="">Select Payment</option>
                            @foreach($pendingPayment as $key => $value)
                            <option value="{{ $key }}" >{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-4">
                        <div class="col-lg-4 col-6">
                            <label for="">Plan <span>*</span></label>
                            <input type="text" class="form-control" id="plan_name" readonly>
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="">Plan Type <span>*</span></label>
                            <input type="text" class="form-control" id="plan_type_name" readonly >
                           
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="">Plan Price <span>*</span></label>
                            <input type="text" class="form-control " id="plan_price" readonly>
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="">Start Date <span>*</span></label>
                            <input type="date" class="form-control " name="start_date" id="plan_start_date" value="{{ old('start_date' ) }}" readonly>
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="">End Date <span>*</span></label>
                            <input type="date" class="form-control " name="end_date" id="plan_end_date" value="{{ old('end_date' ) }}" readonly>
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="">Transaction Date <span>*</span></label>
                            <input type="date" class="form-control @error('paid_date') is-invalid @enderror" placeholder="Transaction Date" name="paid_date" id="paid_date" value="">
                            @error('paid_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        {{-- <div class="col-lg-6 col-6">
                            <label for="">Payment Mode</label>
                           
                            <select name="payment_mode" id="payment_mode" class="form-select @error('payment_mode') is-invalid @enderror">
                                <option value="">Select Payment Mode</option>
                                <option value="1" {{ $customer->payment_mode == 1 ? 'selected' : '' }}>Online</option>
                                <option value="2" {{ $customer->payment_mode == 2 ? 'selected' : '' }}>Offline</option>
                                <option value="3" {{ $customer->payment_mode == 3 ? 'selected' : '' }}>Pay Later</option>
                            </select>
                            @error('payment_mode')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                           
                            
                        </div> --}}
                        
                      
                    </div>
                    {{-- <div class="row mt-3">
                        @if($customer->payment_mode==3)

                        <div class="col-lg-6 col-6">
                            <label for="">Transaction Number <span>*</span></label>
                            <input type="text" class="form-control @error('transaction_id') is-invalid @enderror digit-only" placeholder="Transaction Number" name="transaction_id" id="transaction_id" value="{{ old('transaction_id') }}">
                            @error('transaction_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        @endif

                        <div class="col-lg-6 col-12">
                            <label for="">Upload Payment Proof </label>
                            <input type="file" class="form-control @error('transaction_image') is-invalid @enderror " placeholder="Transaction Number" name="transaction_image" id="transaction_image" value="{{ old('transaction_image') }}">
                            @error('transaction_image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div> --}}

                    <div class="row mt-3">
                        <div class="col-lg-3">
                            <input type="submit" class="btn btn-warning btn-block button" value="Make Payment">
                        </div>
                    </div>
                    

                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-3 order-1 order-md-2">
        <div class="seat--info">
            @php 
            $class='';  
           
                if($diffInDays <= 5 && $diffExtendDay>0){
                    $class='extedned';
                }elseif($diffInDays < 0 ){
                    $class='expired';
                }
            @endphp
            <span class="d-block">Seat No : {{ $customer->learner->seat_no}}</span>
            <img src="{{ asset($customer->planType->image) }}" alt="Seat" class="seat py-3 {{$class}}">
            <p>{{ $customer->plan->name}}</p>
            <button>Booked for <b>{{ $customer->planType->name}}</b></button>
           
            @if ($diffInDays > 0)
                <span class="text-success">Plan Expires in {{ $diffInDays }} days</span>
            @elseif ($diffInDays < 0 && $diffExtendDay>0)
                <span class="text-danger fs-10 d-block">Extend Days are Active Now & Remaining Days are {{ abs($diffExtendDay) }} days.</span>
            @elseif ($diffInDays < 0 && $diffExtendDay==0)
                <span class="text-warning fs-10 d-block">Plan Expires today</span>
            @else
                <span class="text-danger fs-10 d-block">Plan Expired {{ abs($diffInDays) }} days ago</span>
            @endif
        </div>
    </div>
</div>
<script>
 
document.addEventListener('DOMContentLoaded', function() {
  
    const formId = document.querySelector('form.payment_page').id;
    
    handleFormChanges(formId, {{$customer->learner->id}});
});


</script>


@include('learner.script')

@endsection