@extends('layouts.admin')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<!-- Main content -->

<!-- Breadcrumb -->
<div class="row">
    <div class="d-flex bradcrumb">
        <h4>Master Create</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Master Create</li>
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
                <li ><a href="{{route('profile')}}">Update Profile</a></li>
                <li class="active"><a href="{{route('library.master')}}">Configure Library</a></li>
            </ul>
           
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h2 class="text-center">You’re all set! Let’s get things ready for you!</h2>
    </div>
</div>

<div id="success-message" class="alert alert-success" style="display:none;"></div>
<div id="error-message" class="alert alert-danger" style="display:none;"></div>
<!-- Masters -->
<div class="row g-4 mb-4 mt-2">
    <div class="col-lg-4">
        <div class="master-box">
            <div class="d-flex">
                <h4>Add Operating Hours</h4>
                <i class="fa fa-plus-circle toggle-button"></i>
            </div>
            <div class="master-form mt-3">
                <div class="form-fields">
                    <form id="operating_hour"  enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="" >
                        <input type="hidden" name="library_id" value="{{Auth::user()->id}}" >
                        <input type="hidden" name="databasetable" value="hour">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="">Operating Hours <span>*</span></label>
                                <select class="form-control @error('hour') is-invalid @enderror" name="hour" id="hour">
                                    <option value="">Select Hour</option>
                                    <option value="16" {{ old('hour', isset($hour) ? $hour->hour : '') == 16 ? 'selected' : '' }}>16</option>
                                    <option value="14" {{ old('hour', isset($hour) ? $hour->hour : '') == 14 ? 'selected' : '' }}>14</option>
                                    <option value="12" {{ old('hour', isset($hour) ? $hour->hour : '') == 12 ? 'selected' : '' }}>12</option>
                                </select>
                                @error('hour')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <button  class="btn btn-primary button" id="saveHourBtn"><i
                                        class="fa fa-plus"></i>
                                    Add Hours</button>
                            </div>
                        </div>
                    </form>
                </div>
                <ul id="hour-list">
                    @foreach($hours as $key => $value)
                    <li>
                        <div class="d-flex">
                            <h4>{{$value->hour}} hours</h4>
                            <ul>
                                <li><a href="#" class="active-deactive" data-id="{{ $value->id }}" data-table="hour" title="Active/Deactive">
                                    @if($value->deleted_at)
                                    <i class="fas fa-cross"></i>
                                    @else
                                    <i class="fa fa-check"></i>
                                    @endif</a></li>
                                <li><a href="javascript:void(0)" type="button" class="hour_edit" data-id="{{$value->id}}"><i class="fa fa-edit"></i></a></li>
                                <li><a href="#" ><i class="fa fa-trash"></i></a></li>
                            </ul>
                        </div>
                    </li>
                    @endforeach
                    
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="master-box">
            <div class="d-flex">
                <h4>Add Library Seats</h4>
                <i class="fa fa-plus-circle toggle-button"></i>
            </div>
            <div class="master-form mt-3">
                <div class="form-fields">
                    
                    <form id="library_seat"  enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="" >
                        <input type="hidden" name="library_id" value="{{Auth::user()->id}}" >
                        <input type="hidden" name="databasetable" value="seats" >
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="">Library Seats <span>*</span></label>
                                <input type="text" name="total_seats" class="form-control @error('total_seats') is-invalid @enderror" id="" placeholder="Enter Seats No.">
                                @error('total_seats')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary button"><i
                                        class="fa fa-plus"></i>
                                    Add Seats</button>
                            </div>
                        </div>
                    </form>
                </div>
                <ul>
                    <li>
                        <div class="d-flex">
                            <h4>{{$total_seat}}</h4>
                            <ul>
                                <li><a href=""><i class="fa fa-check"></i></a></li>
                                <li><a href="javascript:void(0)" type="button" class="seat_edit" data-id="{{Auth::user()->id}}"><i class="fa fa-edit"></i></a></li>
                               
                                <li><a href=""><i class="fa fa-trash"></i></a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="master-box">
            <div class="d-flex">
                <h4>Add Extend Days</h4>
                <i class="fa fa-plus-circle toggle-button"></i>
            </div>
            <div class="master-form mt-3">
                <div class="form-fields">
                    <form id="extend_hour" class="validateForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="" >
                        <input type="hidden" name="library_id" value="{{Auth::user()->id}}" >
                        
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="">Extend Days <span>*</span></label>
                                <input type="text" class="form-control @error('extend_days') is-invalid @enderror" name="extend_days">
                                @error('extend_days')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary button"><i
                                        class="fa fa-plus"></i>
                                    Add Day</button>
                            </div>
                        </div>
                    </form>
                </div>
                <ul id="extend-day-list">
                    @foreach($hours as $key => $value)
                    <li>
                        <div class="d-flex">
                            <h4>{{$value->extend_days}} Days</h4>
                            <ul>
                                <li><a href="#" class="delete" data-id="{{ $value->id }}" data-table="hour" title="Active/Deactive">
                                    @if($value->deleted_at)
                                    <i class="fas fa-cross"></i>
                                    @else
                                    <i class="fa fa-check"></i>
                                    @endif</a></li>
                                <li><a href="javascript:void(0)" type="button" class="extend_day_edit" data-id="{{$value->id}}"><i class="fa fa-edit"></i></a></li>
                                <li><a href="#" class="active-deactive" data-id="{{ $value->id }}" data-table="hour" title="Delete"><i class="fa fa-trash"></i></a></li>
                            </ul>
                        </div>
                    </li>
                    @endforeach
                    
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="master-box">
            <div class="d-flex">
                <h4>Add Plan</h4>
                <i class="fa fa-plus-circle toggle-button"></i>
            </div>
            <div class="master-form mt-3">
                <div class="form-fields">
                    <form id="planForm" enctype="multipart/form-data">
                        @csrf
                        @if (isset($plan))
                        @method('PUT')
                        @endif
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="library_id" value="{{Auth::user()->id}}">
                        <input type="hidden" name="databasemodel" value="Plan">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="">Plan Name <span>*</span></label>
                                <select class="form-control @error('plan_id') is-invalid @enderror" name="plan_id" id="plan_id">
                                    <option value="">Select Plan</option>
                                    <option value="1" {{ old('plan_id', isset($plan) ? $plan->plan_id : '') == 1 ? 'selected' : '' }}>1 MONTH</option>
                                    <option value="3" {{ old('plan_id', isset($plan) ? $plan->plan_id : '') == 3 ? 'selected' : '' }}>3 MONTHS</option>
                                    <option value="6" {{ old('plan_id', isset($plan) ? $plan->plan_id : '') == 6 ? 'selected' : '' }}>6 MONTHS</option>
                                    <option value="9" {{ old('plan_id', isset($plan) ? $plan->plan_id : '') == 9 ? 'selected' : '' }}>9 MONTHS</option>
                                    <option value="12" {{ old('plan_id', isset($plan) ? $plan->plan_id : '') == 12 ? 'selected' : '' }}>12 MONTHS</option>
                                </select>
                                @error('plan_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary button" id="savePlanBtn"><i class="fa fa-plus"></i>
                                    @if(isset($plan)) Edit Plan @else Add Plan @endif
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
                <ul  id="plan-list">
                    @foreach($plans as $key => $value)
                    <li>
                        <div class="d-flex">
                            <h4>{{$value->name}}</h4>
                            <ul>
                                <li><a href="#" class="active-deactive" data-id="{{ $value->id }}" data-table="Plan" title="Active/Deactive">
                                        @if($value->deleted_at)
                                        <i class="fas fa-cross"></i>
                                        @else
                                        <i class="fa fa-check"></i>
                                        @endif</a></li>
                                <li><a href="javascript:void(0)" type="button" class="plan_edit" data-id="{{$value->id}}"><i class="fa fa-edit"></i></a></li>
                                <li><a href="#" class="delete" data-id="{{ $value->id }}" data-table="Plan" title="Delete"><i class="fa fa-trash"></i></a></li>

                            </ul>
                        </div>
                    </li>
                    @endforeach

                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="master-box">
            <div class="d-flex">
                <h4>Add Plan Type</h4>
                <i class="fa fa-plus-circle toggle-button"></i>
            </div>
            <div class="master-form mt-3">
                <div class="form-fields">
                    <form id="planTypeForm" enctype="multipart/form-data" class="validateForm">
                        @csrf
                        <input type="hidden" name="id" value="" >
                        <input type="hidden" name="library_id" value="{{Auth::user()->id}}" >
                        <input type="hidden" name="databasemodel" value="PlanType">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="">Plan Type Name <span>*</span></label>
                                <input type="text" name="name" id="plantype_name" class="form-control char-only @error('name') is-invalid @enderror" placeholder="Enter Plan Type" value="{{ old('name', isset($planType) ? $planType->name : '') }}">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="">Start Time <span>*</span></label>
                                <input type="text" id="start_time" class="form-control @error('start_time') is-invalid @enderror" name="start_time" value="{{ old('start_time', isset($planType) ? $planType->start_time : '') }}" placeholder="Select start time">
                                @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="">End Time <span>*</span></label>
                                <input type="text" id="end_time" class="form-control @error('end_time') is-invalid @enderror" name="end_time" value="{{ old('end_time', isset($planType) ? $planType->end_time : '') }}" placeholder="Select end time">
                                @error('end_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="">Slot Duration <span>*</span></label>
                                <input type="text" id="slot_hours" class="form-control @error('slot_hours') is-invalid @enderror no-validate" name="slot_hours" readonly value="{{ old('slot_hours', isset($planType) ? $planType->slot_hours : '') }}" placeholder="Slot duration">
                                @error('slot_hours')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="seat_color">Select Seat Color <span>*</span></label>
                                <select name="image" id="seat_color" class="form-control no-validate">
                                    <option value="">Select Color</option>
                                    <option value="orange">Orange</option>
                                    <option value="light_orange">Light Orange</option>
                                    <option value="green">Green</option>
                                    <option value="blue">Blue</option>
                                </select>
                            </div>
                            
                            <div class="col-lg-6">
                                <label for="">Day Type </label>
                                <select class="form-control @error('timming') is-invalid @enderror no-validate" name="timming">
                                    <option value="">Select time</option>
                                    <option value="Morning1">Morning1</option>
                                    <option value="Morning2">Morning2</option>
                                    <option value="Evening1">Evening1</option>
                                    <option value="Evening2">Evening2</option>
                                </select>
                                @error('timming')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" id="savePlanTypeBtn" class="btn btn-primary button"><i
                                        class="fa fa-plus"></i>
                                    Add Plan Type</button>
                            </div>
                        </div>
                    </form>
                </div>
                <ul id="plantype-list">
                    @foreach($plantype as $key => $value)
                    <li>
                        <div class="d-flex">
                            <h4>{{$value->name}}</h4>
                            <ul>
                                <li><a href="#" class="active-deactive" data-id="{{ $value->id }}" data-table="PlanType" title="Active/Deactive">
                                    @if($value->deleted_at)
                                    <i class="fas fa-cross"></i>
                                    @else
                                    <i class="fa fa-check"></i>
                                    @endif</a></li>
                                <li><a href="javascript:void(0)" type="button" class="plantype_edit" data-id="{{$value->id}}"><i class="fa fa-edit"></i></a></li>
                                <li><a href="#" class="delete" data-id="{{ $value->id }}" data-table="PlanType" title="Delete"><i class="fa fa-trash"></i></a></li>

                            </ul>
                        </div>
                    </li>
                    @endforeach
                </ul>
               
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="master-box">
            <div class="d-flex">
                <h4>Add Plan price</h4>
                <i class="fa fa-plus-circle toggle-button"></i>
            </div>
            <div class="master-form mt-3">
                <div class="form-fields">
                    <form id="planPriceForm" enctype="multipart/form-data" >
                        @csrf
                        <input type="hidden" name="id" value="" >
                        <input type="hidden" name="library_id" value="{{Auth::user()->id}}" >
                        <input type="hidden" name="databasemodel" value="PlanPrice">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="">Plan Name <span>*</span></label>
                                <select name="plan_id" id="price_plan_id" class="form-control @error('plan_id') is-invalid @enderror event">
                                    <option value="">Select Plan</option>
                                    @foreach ($plans as $value)
                                    <option value="{{ $value->id }}" {{ isset($planPrice) && $planPrice->plan_id == $value->id ? 'selected' : '' }}>
                                        {{ $value->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('plan_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label> Plan Type<sup class="text-danger">*</sup></label>
                                <select name="plan_type_id" id="plan_type_id" class="form-control @error('plan_type_id') is-invalid @enderror event">
                                    <option value="">Select Plan Type</option>
                                    @foreach($plantypes as $planType)
                                    <option value="{{ $planType->id }}" {{ isset($planPrice) && $planPrice->plan_type_id == $planType->id ? 'selected' : '' }}>
                                        {{ $planType->name }}
                                    </option>
                                    @endforeach
                                 
                                </select>
                                @error('plan_type_id')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="">Plan Price <span>*</span></label>
                                <input type="text" name="price" class="form-control digit-only @error('price') is-invalid @enderror" id="price" placeholder="Enter Price" value="{{ old('price', isset($planPrice) ? $planPrice->price : '') }}">
                            @error('price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary button"><i
                                        class="fa fa-plus"></i>
                                    Add Plan Price</button>
                            </div>
                        </div>
                    </form>
                </div>
                <ul  id="planPrice-list">
                    @foreach($planprice as $key => $value)
                    <li>
                        <div class="d-flex">
                            <h4>{{$value->price}}</h4>
                            <ul>
                                <li><a href="#" class="active-deactive" data-id="{{ $value->id }}" data-table="PlanPrice" title="Active/Deactive">
                                    @if($value->deleted_at)
                                    <i class="fas fa-cross"></i>
                                    @else
                                    <i class="fa fa-check"></i>
                                    @endif</a></li>
                                <li><a href="javascript:void(0)" type="button" class="planPrice_edit" data-id="{{$value->id}}"><i class="fa fa-edit"></i></a></li>
                                <li><a href="#" class="delete" data-id="{{ $value->id }}" data-table="PlanPrice" title="Delete"><i class="fa fa-trash"></i></a></li>

                            </ul>
                        </div>
                    </li>
                    @endforeach
                   
                </ul>
            </div>
        </div>
    </div>
</div>


<!-- /.content -->
@include('master.script')
@endsection