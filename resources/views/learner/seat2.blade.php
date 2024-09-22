@extends('layouts.admin')
@section('content')

<!-- Main content -->

@php
use App\Models\Customers;
$fullDayCount = 0;
$halfDayFirstHalfCount = 0;
$halfDaySecondHalfCount = 0;
$hourlyCount = 0;
@endphp
<div id="success-message" class="alert alert-success" style="display:none;"></div>
<div id="error-message" class="alert alert-danger" style="display:none;"></div>
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="seat-booking">
            @foreach($seats as $seat)
                <div class="seat_no">
                    @php
                        $usersForSeat =Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->leftJoin('plan_types','customer_detail.plan_type_id','=','plan_types.id')->where('customers.seat_no', $seat->id)->select('customers.id','customers.seat_no','customer_detail.plan_type_id','plan_types.day_type_id','plan_types.image')->where('customers.status',1)->where('customer_detail.status',1)->get();
                        $remainingHours = $total_hour - $seat->total_hours;
                        $seatCount = 0;
                        $halfday = 1;
                        $hourly = 1;

                        // Determine seatCount based on remaining hours and availability
                        if ($remainingHours ==($total_hour-($total_hour/4)) && $seat->is_available == 4) {
                        $seatCount = 3;
                        } elseif ($remainingHours == ($total_hour-(2*$total_hour/4)) && $seat->is_available == 4) {
                        $seatCount = 2;
                        } elseif ($remainingHours == ($total_hour-(3*$total_hour/4)) && $seat->is_available == 4) {
                        $seatCount = 1;
                        } elseif ($remainingHours == ($total_hour/2) && $seat->is_available != 4) {
                        $seatCount = 1;
                        } elseif ($remainingHours == 0 && $seat->is_available != 4) {
                        $seatCount = 0;
                        }

                    @endphp

                    @if($usersForSeat->count() > 0)
                        @foreach($usersForSeat as $user)
                        @if($user->day_type_id == 1)
                        <div class="seat second_popup" id="{{ $user->id }}">
                            <div class="number">{{ $user->seat_no }}</div>
                            <img src="{{ asset($user->image) }}" alt="seat" class="seat_svg">
                        </div>
                        @elseif($user->day_type_id == 2)
                        <div class="seat hourly_wise second_popup" id="{{ $user->id }}">
                            <div class="number">{{ $halfday++ }}</div>
                            <img src="{{ asset($user->image) }}" alt="seat" class="seat_svg">
                        </div>
                        @elseif($user->day_type_id == 3)
                        <div class="seat hourly_wise second_popup" id="{{ $user->id }}">
                            <div class="number">{{ $halfday++ }}</div>
                            <img src="{{ asset($user->image) }}" alt="seat" class="seat_svg">
                        </div>
                        @elseif(in_array($user->day_type_id, [4, 5, 6, 7]))
                        <div class="seat hourly_wise second_popup" id="{{ $user->id }}">
                            <div class="number">{{ $hourly++ }}</div>
                            <img src="{{ asset($user->image) }}" alt="seat" class="seat_svg">
                        </div>
                        @endif
                        @endforeach

                        @php
                        // Adjust seatCount if one half-day and one hourly booking exist
                        $halfDayBookings = $usersForSeat->where('day_type_id', 2)->count() + $usersForSeat->where('day_type_id', 3)->count();
                        $hourlyBookings = $usersForSeat->whereIn('day_type_id', [4, 5, 6, 7])->count();

                        if ($halfDayBookings == 1 && $hourlyBookings == 1) {
                        $seatCount = 1;
                        }elseif($remainingHours != 0 && $hourlyBookings >0){
                        $seatCount = 4-$hourlyBookings;
                        }elseif($remainingHours != 0 && $halfDayBookings>0){
                        $seatCount = 2-$halfDayBookings;
                        }

                        @endphp

                        @for ($i = 0; $i < $seatCount; $i++)
                            <div class="seat hourly_wise first_popup" id="{{ $seat->id }}">
                            <div class="number">{{ $hourly++ }}</div>
                            <img src="{{ asset('public/img/available.svg') }}" alt="seat" class="seat_svg">
                        </div>
                        @endfor
                    @else
                    <div class="seat first_popup" id="{{ $seat->id }}">
                        <div class="number">{{ $seat->seat_no }}</div>
                        <img src="{{ asset('public/img/available.svg') }}" alt="seat" class="seat_svg">
                    </div>
                    @endif
                </div>
            @endforeach
        </div>



                
    <div class="d-flex color_lable">
        @php
        $plan_types=App\Models\PlanType::whereIn('day_type_id',[1,2,3,4])->get();
        @endphp
        @foreach($plan_types as $key => $value)
        <div>
            <img src="{{ asset($value->image) }}" alt="seat" class="seat_svg">
            {{$value->name}}
            @if($value->day_type_id==1)

            ({{$count_fullday}})
            @elseif($value->day_type_id==2)

            ({{$count_firstH}})
            @elseif($value->day_type_id==3)

            ({{$count_secondH}})
            @elseif($value->day_type_id==4)
            ({{$count_hourly}})

            @endif
        </div>

        @endforeach
        <div class="not-available">
            <img src="{{ asset('public/img/available.svg') }}" alt="seat" class="seat_svg">
            Available ({{$available}})
        </div>

    </div>

</div>


<!-- Booking Popup -->
<div class="modal fade" id="seatAllotmentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="success-message" class="alert alert-success" style="display:none;"></div>
        <div class="modal-content">
            <div id="error-message" class="alert alert-danger" style="display:none;"></div>
            <div id="validation-error-message" class="alert alert-danger" style="display:none;"></div>
            <div class="modal-body ">
                <form id="seatAllotmentForm">
                    <div class="detailes">

                        <button type="button" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
                        <h3 id="seat_no_head"></h3>

                        <input type="hidden" class="form-control char-only" name="seat_no" value="" id="seat_no">
                        <h4 class="py-4 m-0">Leraners Information</h4>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <label for="">Full Name <span>*</span></label>
                                <input type="text" class="form-control char-only" name="name" id="name">
                            </div>
                            <div class="col-lg-6">
                                <label for="">DOB <span>*</span></label>
                                <input type="date" class="form-control" name="dob" id="dob">
                            </div>
                            <div class="col-lg-6">
                                <label for="">Mobile Number <span>*</span></label>
                                <input type="text" class="form-control digit-only" maxlength="10" minlength="10" name="mobile" id="mobile">
                            </div>
                            <div class="col-lg-6">
                                <label for="">Email Id <span>*</span></label>
                                <input type="text" class="form-control" name="email" id="email">
                            </div>
                        </div>
                        <h4 class="py-4 m-0">Plan Information</h4>
                        <div class="row g-4">
                            <div class="col-lg-4">
                                <label for="">Select Plan <span>*</span></label>
                                <select name="plan_id" id="plan_id" class="form-control" name="plan_id">
                                    <option value="">Select Plan</option>
                                    @foreach($plans as $key => $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="">Plan Type <span>*</span></label>
                                <select id="plan_type_id" class="form-control" name="plan_type_id">
                                    <option value="">Select Plan Type</option>
                                    {{-- @foreach($plan_types as $key => $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="">Plan Price <span>*</span></label>
                                <input id="plan_price_id" class="form-control" name="plan_price_id" placeholder="Example : 800 Rs" @readonly(true)>

                            </div>
                            <div class="col-lg-4">
                                <label for="">Plan Starts On <span>*</span></label>
                                <input type="date" class="form-control" placeholder="Plan Starts On" name="plan_start_date" id="plan_start_date">
                            </div>
                            <div class="col-lg-4">
                                <label for="">Plan Ends On <span></span></label>
                                <input type="date" class="form-control" placeholder="Plan Starts On" name="plan_end_date" id="plan_start_date" readonly>
                            </div>


                            <div class="col-lg-4">
                                <label for="">Payment Mode <span>*</span></label>
                                <select name="payment_mode" id="payment_mode" class="form-control">
                                    <option value="">Select Payment Mode</option>
                                    <option value="1">Online</option>
                                    <option value="2">Offline</option>
                                    <option value="3">Pay Later</option>
                                </select>
                            </div>
                        </div>
                        <h4 class="py-4 m-0">Id Proof Upload</h4>

                        <div class="row g-4">
                            <div class="col-lg-6">
                                <label for="">Id Proof Received </label>
                                <select name="" id="id_proof_name" class="form-control" name="id_proof_name">
                                    <option value="">Select Id Proof</option>
                                    <option value="1">Aadhar</option>
                                    <option value="2">Driving License</option>
                                    <option value="3">Other</option>
                                </select>
                                <span class="text-info">Uploading ID proof is optional; you can upload it later.</span>
                            </div>
                            <div class="col-lg-6">
                                <label for="">Upload Scan Copy of Proof </label>
                                <input type="file" class="form-control" name="id_proof_file" id="id_proof_file">

                                <a href="javascript:;" id="viewButton"><i class="fa fa-eye"></i> View Uploded File</a>
                                <div id="filePopup" class="file-popup">
                                    <img src="" id="imagePreview" style="display: none;" alt="Selected Image">
                                    <iframe id="pdfPreview" style="display: none;" frameborder="0"></iframe>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <input type="submit" class="btn btn-primary btn-block" id="submit" value="Book Library Seat Now">
                            </div>
                        </div>

                    </div>
            </div>

        </div>
        </form>
    </div>
</div>

<!-- Detail Popup -->
<div class="modal fade" id="seatAllotmentModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body m-0">
                <div class="detailes">
                    <button type="button" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
                    <h3 id="seat_details_info"></h3>
                    <span id="seat_name" style="display: none;"></span>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="actions">
                                <div class="upper-box mt-3">
                                    <h4>Leraners Info</h4>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <span>Seat Owner Name</span>
                                            <h5 id="owner" class="uppercase">NA</h5>
                                        </div>
                                        <div class="col-lg-6">
                                            <span>Date Of Birth </span>
                                            <h5 id="learner_dob">NA</h5>
                                        </div>
                                        <div class="col-lg-6">
                                            <span>Mobile Number</span>
                                            <h5 id="learner_mobile">NA</h5>
                                        </div>
                                        <div class="col-lg-6">
                                            <span>Email Id</span>
                                            <h5 id="learner_email">NA</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="action-box">
                                    <h4>Other Seat Info</h4>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <span>Plan</span>
                                            <h5 id="planName">NA</h5>
                                        </div>
                                        <div class="col-lg-4">
                                            <span>Plan Type</span>
                                            <h5 id="planTypeName">NA</h5>
                                        </div>
                                        <div class="col-lg-4">
                                            <span>Plan Price</span>
                                            <h5 id="price">NA</h5>
                                        </div>
                                        <div class="col-lg-4">
                                            <span>Seat Booked On</span>
                                            <h5 id="joinOn">NA</h5>
                                        </div>
                                        <div class="col-lg-4">
                                            <span>Plan Starts On</span>
                                            <h5 id="startOn">NA</h5>
                                        </div>
                                        <div class="col-lg-4">
                                            <span>Plan Ends On</span>
                                            <h5 id="endOn">NA</h5>
                                        </div>
                                        <div class="col-lg-4">
                                            <span>Payment Mode</span>
                                            <h5 id="paymentmode">NA</h5>
                                        </div>
                                        <div class="col-lg-4">
                                            <span>Id Proof</span>
                                            <h5 id="proof"><a class="">View Docuemnt</a></h5>
                                        </div>
                                        <div class="col-lg-4">
                                            <span>Seat Timings</span>
                                            <h5 id="planTiming">NA</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive d-none">
                                <table class="table table-bordered mb-0" Id="bookingDetails">
                                    <tr>
                                        <td class="w-50">Seat Owner Name</td>
                                        <td id="owner" class="uppercase"></td>
                                    </tr>
                                    <tr>
                                        <td>Plan Purchsed</td>
                                        <td id="planName"></td>
                                    </tr>
                                    <tr>
                                        <td>Plan Price & Current Status</td>
                                        <td id="price"></td>
                                    </tr>
                                    <tr>
                                        <td>Plan Type</td>
                                        <td id="planTypeName"></td>
                                    </tr>
                                    <tr>
                                        <td>Plan Timings</td>
                                        <td id="planTiming"></td>
                                    </tr>
                                    <tr>
                                        <td>Join On</td>
                                        <td id="joinOn"></td>
                                    </tr>
                                    <tr>
                                        <td>Plan Starts On</td>
                                        <td id="startOn"></td>
                                    </tr>
                                    <tr>
                                        <td>Plan Ends On (Pending Days)</td>
                                        <td id="endOn"></td>
                                    </tr>
                                    <tr>
                                        <td>Id Proof Name & Received Status</td>
                                        <td id="proof"></td>
                                    </tr>
                                </table>
                                <span class="text-info text-center"><b>Renew Library Membership</b> option available 5 days before your plan expires.</span>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <input type="hidden" value="" id="user_id">
                                    <a id="upgrade" class="btn btn-primary btn-block mt-2" style="height : auto;">Renew Library Membership</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Renew Plan -->
<div class="modal fade" id="seatAllotmentModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div id="success-message" class="alert alert-success" style="display:none;"></div>
    <div id="error-message" class="alert alert-danger" style="display:none;"></div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body m-0">
                <form id="upgradeForm">
                    <div class="detailes">
                        <button type="button" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
                        <h3 id="seat_number_upgrades"></h3>
                        <div class="row g-4 mt-1">
                            <div class="col-lg-6">
                                <label for="">Select Plan <span>*</span></label>
                                <select id="update_plan_id" class="form-control" name="plan_id">
                                    <option value="">Select Plan</option>
                                    @foreach($plans as $key => $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>

                                    @endforeach

                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="">Plan Type <span>*</span></label>
                                <select id="updated_plan_type_id" class="form-control" name="plan_type_id" @readonly(true)>

                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="">Plan Price <span>*</span></label>
                                <input id="updated_plan_price_id" class="form-control" placeholder="Plan Price" name="plan_price_id" @readonly(true)>

                            </div>
                            <div class="col-lg-6">
                                <label for="">Plan Ends On <span>*</span></label>
                                <input type="date" class="form-control" placeholder="Plan Ends On" id="update_plan_end_date" value="" readonly>
                            </div>
                            <div class="col-lg-12">
                                <span class="text-info">Your upcoming plan starts after your current plan expires.</span>
                            </div>
                            <div class="col-lg-4 mt-1">

                                <input type="hidden" class="form-control char-only" name="seat_no" value="" id="update_seat_no">
                                <input type="hidden" class="form-control char-only" name="user_id" value="" id="update_user_id">
                                <input type="submit" class="btn btn-primary btn-block" id="submit" value="Renew Membership Now">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('library.script')
@endsection