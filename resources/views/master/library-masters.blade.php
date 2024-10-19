@extends('layouts.admin')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<!-- Main content -->

<!-- Breadcrumb -->
@if($iscomp==false && !$is_expire)
<div class="row">
    <div class="col-lg-12">
        <div class="steps">
            <ul>

                <li>
                    <a href="{{ ($checkSub) ? '#' : route('subscriptions.choosePlan')  }}">Choose Plan</a>
                </li>
                <li>
                    <a href="{{ ($ispaid) ? '#'  : route('subscriptions.payment') }}">Make Payment</a>
                </li>
                <li>
                    <a href="{{ ($ispaid ) ? route('profile') : '#' }}">Update Profile</a>
                </li>
                <li class="active">
                    <a href="{{ ($checkSub && $ispaid && $isProfile) ? route('library.master') : '#' }}">Configure Library</a>
                </li>
            </ul>

        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-12">
        <h2 class="text-center typing-text">You’re all set! Let’s get things ready for you!</h2>
    </div>
</div>
@endif

<div id="success-message" class="alert alert-success" style="display:none;"></div>
<div id="error-message" class="alert alert-danger" style="display:none;"></div>
@if(session('successCount'))
<div class="alert alert-success">
    {{ session('successCount') }} records imported successfully.
</div>
@endif
<!-- Masters -->

@if($iscomp)
<div class="row g-4 mb-4">
    <!-- Add Operating Hours -->
    <div class="col-lg-4">
        <div class="master-box">
            <div class="d-flex">
                <h4>Add Operating Hours
                    <div class="info-container">
                        <i class="fa-solid fa-circle-info info-icon"></i>
                        <div class="info-card">
                            <h3 class="info-title">Upgrade Seat</h3>
                            <p class="info-details">In a seat upgrade, the learner selects a higher plan, checks seat availability, and pays the difference. The system allocates the upgraded seat, adjusts the remaining time from the current plan, and closes the old reservation. The learner then uses the upgraded seat for the new duration.</p>
                        </div>
                    </div>
                </h4>

                @if($hours->isEmpty())
                <i class="fa fa-plus-circle toggle-button"></i>
                @endif

            </div>
            <div class="master-form mt-3">
                <div class="form-fields">
                    <form id="operating_hour" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="library_id" value="{{Auth::user()->id}}">
                        <input type="hidden" name="databasemodel" value="Hour">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="">Operating Hours <span>*</span></label>
                                <select class="form-select @error('hour') is-invalid @enderror" name="hour" id="hour">
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
                                <button class="btn btn-primary button" id="saveHourBtn"><i
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
                                <li><a href="#" class="active-deactive" data-id="{{ $value->id }}" data-table="Hour" title="Active/Deactive">
                                        @if($value->deleted_at)
                                        <i class="fas fa-cross"></i>
                                        @else
                                        <i class="fa fa-check"></i>
                                        @endif</a></li>
                                <li><a href="javascript:void(0)" type="button" class="hour_edit" data-id="{{$value->id}}" data-table="Hour"><i class="fa fa-edit"></i></a></li>

                            </ul>
                        </div>
                    </li>
                    @endforeach

                </ul>
            </div>
        </div>
    </div>

    <!-- Add Library Seats -->
    <div class="col-lg-4">
        <div class="master-box">
            <div class="d-flex">
                <h4>Add Library Seats
                    <div class="info-container">
                        <i class="fa-solid fa-circle-info info-icon"></i>
                        <div class="info-card">
                            <h3 class="info-title">Upgrade Seat</h3>
                            <p class="info-details">In a seat upgrade, the learner selects a higher plan, checks seat availability, and pays the difference. The system allocates the upgraded seat, adjusts the remaining time from the current plan, and closes the old reservation. The learner then uses the upgraded seat for the new duration.</p>
                        </div>
                    </div>
                </h4>
                @if($total_seat==0)
                <i class="fa fa-plus-circle toggle-button"></i>
                @endif
            </div>
            <div class="master-form mt-3">
                <div class="form-fields">

                    <form id="library_seat" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="library_id" value="{{Auth::user()->id}}">
                        <input type="hidden" name="databasemodel" value="Seat">

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
                    @if($total_seat>0)
                    <li>
                        <div class="d-flex">
                            <h4>{{($total_seat)}}</h4>
                            <ul>
                                <li><a href=""><i class="fa fa-check"></i></a></li>
                                <li><a href="javascript:void(0)" type="button" class="seat_edit" data-id="{{Auth::user()->id}}" data-table="Seat"><i class="fa fa-edit"></i></a></li>


                            </ul>
                        </div>
                    </li>
                    @endif

                </ul>
            </div>
        </div>
    </div>

    <!-- Add Extend Days -->
    <div class="col-lg-4">
        <div class="master-box">
            <div class="d-flex">
                <h4>Add Extend Days
                    <div class="info-container">
                        <i class="fa-solid fa-circle-info info-icon"></i>
                        <div class="info-card">
                            <h3 class="info-title">Upgrade Seat</h3>
                            <p class="info-details">In a seat upgrade, the learner selects a higher plan, checks seat availability, and pays the difference. The system allocates the upgraded seat, adjusts the remaining time from the current plan, and closes the old reservation. The learner then uses the upgraded seat for the new duration.</p>
                        </div>
                    </div>
                </h4>
                @if($is_extendday==false)
                <i class="fa fa-plus-circle toggle-button"></i>
                @endif
            </div>
            <div class="master-form mt-3">
                <div class="form-fields">
                    <form id="extend_hour" class="validateForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="library_id" value="{{Auth::user()->id}}">
                        <input type="hidden" name="databasemodel" value="Hour">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="">Extend Days <span>*</span></label>
                                <input type="text" class="form-control digit-only @error('extend_days') is-invalid @enderror" name="extend_days">
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
                    @if($is_extendday==true)
                    @foreach($hours as $key => $value)
                    <li>
                        <div class="d-flex">
                            <h4>{{$value->extend_days}} Days</h4>
                            <ul>
                                <li><a href="#">
                                        @if($value->deleted_at)
                                        <i class="fas fa-cross"></i>
                                        @else
                                        <i class="fa fa-check"></i>
                                        @endif</a></li>
                                <li><a href="javascript:void(0)" type="button" class="extend_day_edit" data-id="{{$value->id}}" data-table="Hour"><i class="fa fa-edit"></i></a></li>
                                <li><a href="#" class="active-deactive" data-id="{{ $value->id }}" data-table="Hour" title="Delete"><i class="fa fa-trash"></i></a></li>
                            </ul>
                        </div>
                    </li>
                    @endforeach
                    @endif

                </ul>
            </div>
        </div>
    </div>

    <!-- Add Plan -->
    <div class="col-lg-4">
        <div class="master-box">
            <div class="d-flex">
                <h4>Add plan
                    <div class="info-container">
                        <i class="fa-solid fa-circle-info info-icon"></i>
                        <div class="info-card">
                            <h3 class="info-title">Upgrade Seat</h3>
                            <p class="info-details">In a seat upgrade, the learner selects a higher plan, checks seat availability, and pays the difference. The system allocates the upgraded seat, adjusts the remaining time from the current plan, and closes the old reservation. The learner then uses the upgraded seat for the new duration.</p>
                        </div>
                    </div>
                </h4>
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
                                <select class="form-select @error('plan_id') is-invalid @enderror" name="plan_id" id="plan_id">
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
                <ul id="plan-list">
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
                                <li><a href="javascript:void(0)" type="button" class="plan_edit" data-id="{{$value->id}}" data-table="Plan"><i class="fa fa-edit"></i></a></li>
                                <li><a href="#" class="delete" data-id="{{ $value->id }}" data-table="Plan" title="Delete"><i class="fa fa-trash"></i></a></li>

                            </ul>
                        </div>
                    </li>
                    @endforeach

                </ul>
            </div>
        </div>
    </div>

    <!-- Add Plan Type -->
    <div class="col-lg-4">
        <div class="master-box">
            <div class="d-flex">
                <h4>Add Plan Type
                    <div class="info-container">
                        <i class="fa-solid fa-circle-info info-icon"></i>
                        <div class="info-card">
                            <h3 class="info-title">Upgrade Seat</h3>
                            <p class="info-details">In a seat upgrade, the learner selects a higher plan, checks seat availability, and pays the difference. The system allocates the upgraded seat, adjusts the remaining time from the current plan, and closes the old reservation. The learner then uses the upgraded seat for the new duration.</p>
                        </div>
                    </div>
                </h4>
                <i class="fa fa-plus-circle toggle-button"></i>
            </div>
            <div class="master-form mt-3">
                <div class="form-fields">
                    <form id="planTypeForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="library_id" value="{{Auth::user()->id}}">
                        <input type="hidden" name="databasemodel" value="PlanType">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="">Plan Type Name <span>*</span></label>
                                <select class="form-select @error('day_type_id') is-invalid @enderror" name="day_type_id" id="plantype_name">
                                    <option value="">Select Plan Type</option>
                                    <option value="1" {{ old('day_type_id', isset($planType) ? $planType->day_type_id : '') == 1 ? 'selected' : '' }}>Full Day</option>
                                    <option value="2" {{ old('day_type_id', isset($planType) ? $planType->day_type_id : '') == 2 ? 'selected' : '' }}>First Half</option>
                                    <option value="3" {{ old('day_type_id', isset($planType) ? $planType->day_type_id : '') == 3 ? 'selected' : '' }}>Second Half</option>
                                    <option value="4" {{ old('day_type_id', isset($planType) ? $planType->day_type_id : '') == 4 ? 'selected' : '' }}>Hourly Slot 1</option>
                                    <option value="5" {{ old('day_type_id', isset($planType) ? $planType->day_type_id : '') == 5 ? 'selected' : '' }}>Hourly Slot 2</option>
                                    <option value="6" {{ old('day_type_id', isset($planType) ? $planType->day_type_id : '') == 6 ? 'selected' : '' }}>Hourly Slot 3</option>
                                    <option value="7" {{ old('day_type_id', isset($planType) ? $planType->day_type_id : '') == 7 ? 'selected' : '' }}>Hourly Slot 4</option>
                                </select>
                                {{-- <input type="text" name="name" id="plantype_name" class="form-control char-only @error('name') is-invalid @enderror" placeholder="Enter Plan Type" value="{{ old('name', isset($planType) ? $planType->name : '') }}"> --}}
                                @error('day_type_id')
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
                                <select name="image" id="seat_color" class="form-select no-validate">
                                    <option value="">Select Color</option>
                                    <option value="orange">Orange</option>
                                    <option value="light_orange">Light Orange</option>
                                    <option value="green">Green</option>
                                    <option value="blue">Blue</option>
                                </select>
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
                                <li><a href="javascript:void(0)" type="button" class="plantype_edit" data-id="{{$value->id}}" data-table="PlanType"><i class="fa fa-edit"></i></a></li>
                                <li><a href="#" class="delete" data-id="{{ $value->id }}" data-table="PlanType" title="Delete"><i class="fa fa-trash"></i></a></li>

                            </ul>
                        </div>
                    </li>
                    @endforeach
                </ul>

            </div>
        </div>
    </div>

    <!-- Add Plan price -->
    <div class="col-lg-4">
        <div class="master-box">
            <div class="d-flex">
                <h4>Add Plan price
                    <div class="info-container">
                        <i class="fa-solid fa-circle-info info-icon"></i>
                        <div class="info-card">
                            <h3 class="info-title">Upgrade Seat</h3>
                            <p class="info-details">In a seat upgrade, the learner selects a higher plan, checks seat availability, and pays the difference. The system allocates the upgraded seat, adjusts the remaining time from the current plan, and closes the old reservation. The learner then uses the upgraded seat for the new duration.</p>
                        </div>
                    </div>
                </h4>
                <i class="fa fa-plus-circle toggle-button"></i>
            </div>
            <div class="master-form mt-3">
                <div class="form-fields">
                    <form id="planPriceForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="library_id" value="{{Auth::user()->id}}">
                        <input type="hidden" name="databasemodel" value="PlanPrice">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="">Plan Name <span>*</span></label>
                                <select name="plan_id" id="price_plan_id" class="form-select @error('plan_id') is-invalid @enderror event">
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
                                <select name="plan_type_id" id="plan_type_id" class="form-select @error('plan_type_id') is-invalid @enderror event">
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
                <ul id="planPrice-list">
                    @foreach($planprice as $key => $value)
                    <li>
                        <div class="d-flex">
                            <h4>{{$value->price}}</h4>
                            <h4>{{ $value->plan->name ?? 'N/A' }}</h4>
                            <h4>{{ $value->planType->name ?? 'N/A' }}</h4>
                            <ul>
                                <li><a href="#" class="active-deactive" data-id="{{ $value->id }}" data-table="PlanPrice" title="Active/Deactive">
                                        @if($value->deleted_at)
                                        <i class="fas fa-cross"></i>
                                        @else
                                        <i class="fa fa-check"></i>
                                        @endif</a></li>
                                <li><a href="javascript:void(0)" type="button" class="planPrice_edit" data-id="{{$value->id}}" data-table="PlanPrice"><i class="fa fa-edit"></i></a></li>
                                <li><a href="#" class="delete" data-id="{{ $value->id }}" data-table="PlanPrice" title="Delete"><i class="fa fa-trash"></i></a></li>

                            </ul>
                        </div>
                    </li>
                    @endforeach

                </ul>
            </div>
        </div>
    </div>

    <!-- Add Expense -->
    <div class="col-lg-4">
        <div class="master-box">
            <div class="d-flex">
                <h4>Add Expense
                    <div class="info-container">
                        <i class="fa-solid fa-circle-info info-icon"></i>
                        <div class="info-card">
                            <h3 class="info-title">Upgrade Seat</h3>
                            <p class="info-details">In a seat upgrade, the learner selects a higher plan, checks seat availability, and pays the difference. The system allocates the upgraded seat, adjusts the remaining time from the current plan, and closes the old reservation. The learner then uses the upgraded seat for the new duration.</p>
                        </div>
                    </div>
                </h4>
                <i class="fa fa-plus-circle toggle-button"></i>
            </div>
            <div class="master-form mt-3">
                <div class="form-fields">

                    <form id="library_expense" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="library_id" value="{{Auth::user()->id}}">
                        <input type="hidden" name="databasemodel" value="Expense">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="class_name"> Expense Name<sup class="text-danger">*</sup> </label>
                                <input type="text" name="name" value="" class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary button"><i
                                        class="fa fa-plus"></i>
                                    Add Expense</button>
                            </div>
                        </div>
                    </form>
                </div>
                <ul>
                    @foreach($expenses as $key => $value)
                    <li>
                        <div class="d-flex">
                            <h4>{{$value->name}}</h4>

                            <ul>
                                <li><a href="#" class="delete" data-id="{{ $value->id }}" data-table="Expense" title="Active/Deactive">
                                        @if($value->deleted_at)
                                        <i class="fas fa-cross"></i>
                                        @else
                                        <i class="fa fa-check"></i>
                                        @endif</a></li>
                                <li><a href="javascript:void(0)" type="button" class="expense_edit" data-table="Expense" data-id="{{$value->id}}"><i class="fa fa-edit"></i></a></li>
                                <li><a href="#" class="active-deactive" data-id="{{ $value->id }}" data-table="Expense" title="Delete"><i class="fa fa-trash"></i></a></li>
                            </ul>
                        </div>
                    </li>

                    @endforeach

                </ul>
            </div>
        </div>
    </div>

</div> 
@else

<div class="row justify-content-center mb-4 mt-4">
    <div class="col-lg-4">
        <div class="import-data">
            <form action="{{ route('library.csv.upload') }}" method="POST" enctype="multipart/form-data" id="importForm">               
                 @csrf
                <input type="hidden" name="library_id" value=" {{isset($library_id) ? $library_id : ''}}"> 
                <input type="hidden" name="library_import" value="library_master"> 
                <div class="row g-4">
                    <div class="col-lg-12">
                        <label for="">Select File</label>
                        <input type="file" class="form-control @error('csv_file') is-invalid @enderror" name="csv_file">
                        @error('csv_file')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                      
                        <a href="{{ asset('public/sample/master.csv') }}"><small>Download Sample library master CSV File</small></a>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary button">Import Data</button>
                        
                    </div>
                </div>

            </form>
           

        </div>
    </div>
    <div id="progress-container" style="display:none;">
        <progress id="progress-bar" value="0" max="100" style="width: 100%;"></progress>
        <span id="progress-text">0%</span>
    </div>
</div>


{{-- Display Invalid Records --}}
<div id="invalid-records-section">
    @if(session('invalidRecords') && count(session('invalidRecords')) > 0)
        <p class="text-danger">Some records could not be imported:</p>
        <div  class="table table-responsive">
            <table class="table text-center data-table">
                <thead>
                    <tr>
                        <th>Operating Hour</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Seat</th>
                        <th>Full day price</th>
                        <th>Error Message</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (session('invalidRecords') as $record)
                    <tr>
                        <td>{{ $record['Operating_hour'] ?? 'N/A' }}</td>
                        <td>{{ $record['start_time'] ?? 'N/A' }}</td>
                        <td>{{ $record['end_time'] ?? 'N/A' }}</td>
                        <td>{{ $record['total_seat'] ?? 'N/A' }}</td>
                        <td>{{ $record['fullday_price'] ?? 'N/A' }}</td>
                        <td class="text-danger">{{ $record['error'] ?? 'No error provided' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button class="btn btn-danger btn-sm mt-2" id="clearInvalidRecordsButton">Clear Invalid Records</button>
        </div>
    


        {{-- Trigger CSV Download Automatically --}}
        @if(session('autoExportCsv'))
            <script type="text/javascript">
                let exportrecordurl = "{{ Auth::guard('library')->check() ? route('library.export.invalid.records') : route('web.export.invalid.records') }}";
                    window.onload = function() {
                        setTimeout(function() {
                            window.location.href = exportrecordurl; // Trigger the export CSV route
                        }, 1000); // Delay to ensure the page fully loads before triggering
                    };
            </script>

        @endif
    @endif

    <script>
        document.getElementById('clearInvalidRecordsButton').addEventListener('click', function() {
            // Hide the invalid records section
            document.getElementById('invalid-records-section').style.display = 'none';
            let clearSessionRoute = "{{ Auth::guard('library')->check() ? route('library.clear.session') : route('web.clear.session') }}";
            // Send AJAX request to clear session
            fetch(clearSessionRoute, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log('Session cleared successfully.');
                } else {
                    console.log('Failed to clear session.');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</div>
@endif


<!-- Button -->
@if($iscomp)
<div class="row justify-content-center mb-4">
    <div class="col-lg-4">
        {{-- @if($seat_button) --}}
        <a href="{{route('seats')}}" type="button" class="btn btn-primary button main">Take me to My Dashboard </a>
        {{-- @endif --}}
    </div>
</div>
@endif


<!-- /.content -->
@include('master.script')
@endsection