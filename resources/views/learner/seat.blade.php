@extends('layouts.admin')
@section('content')
@php
use App\Models\Learner;
$fullDayCount = 0;
$halfDayFirstHalfCount = 0;
$halfDaySecondHalfCount = 0;
$hourlyCount = 0;

@endphp

<style>
    .seat {
        opacity: 0;
        transform: translateY(50px);
        transition: transform 0.5s ease, opacity 0.5s ease;
    }
</style>

<div class="row mb-4">
    <div class="col-lg-12">
        <div class="seat-booking">

            @foreach($seats as $seat)
            <div class="seat">
                @php
                $usersForSeat =Learner::leftJoin('learner_detail','learner_detail.learner_id','=','learners.id')->leftJoin('plan_types','learner_detail.plan_type_id','=','plan_types.id')->where('learners.library_id',auth()->user()->id)->where('learners.seat_no', $seat->seat_no)->select('learners.id','learners.seat_no','learner_detail.plan_type_id','plan_types.day_type_id','plan_types.image')->where('learners.status',1)->where('learner_detail.status',1)->get();
                $remainingHours = $total_hour - $seat->total_hours;
                $seatCount = 0;
                $halfday = 1;
                $hourly = 1;
                $x=1;
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
                @php

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
                <ul>
                    @foreach($usersForSeat as $user)

                    @if($user->day_type_id == 1)
                    <li><a href="javascript:;" data-bs-toggle="modal" class="second_popup" data-seat_no="{{ $seat->seat_no }}"
                            data-bs-target="#seatAllotmentModal2" data-userid="{{ $user->id }}"><i
                                class="fa-solid fa-check-circle booked"></i></a></li>

                    @elseif($user->day_type_id == 2)

                    <li><a href="javascript:;" data-bs-toggle="modal" class="second_popup" data-seat_no="{{ $seat->seat_no }}"
                            data-bs-target="#seatAllotmentModal2" data-userid="{{ $user->id }}"><i
                                class="fa-solid fa-check-circle booked"></i></a></li>


                    @elseif($user->day_type_id == 3)
                    <li><a href="javascript:;" data-bs-toggle="modal" class="second_popup" data-seat_no="{{ $seat->seat_no }}"
                            data-bs-target="#seatAllotmentModal2" data-userid="{{ $user->id }}"><i
                                class="fa-solid fa-check-circle booked"></i></a></li>

                    @elseif(in_array($user->day_type_id, [4, 5, 6, 7]))
                    <li><a href="javascript:;" data-bs-toggle="modal" class="second_popup" data-seat_no="{{ $seat->seat_no }}"
                            data-bs-target="#seatAllotmentModal2" data-userid="{{ $user->id }}"><i
                                class="fa-solid fa-check-circle booked"></i></a></li>
                    @endif


                    @endforeach
                    @for ($i = 0; $i < $seatCount; $i++)


                        <li><a href="javascript:;" data-bs-toggle="modal" class="first_popup"
                            data-bs-target="#seatAllotmentModal" data-id="{{ $seat->id }}" data-seat_no="{{ $seat->seat_no }}"><i
                                class="fa-solid fa-check-circle available"></i></a></li>

                        @endfor
                </ul>

                @foreach($usersForSeat as $user)

                @if($user->day_type_id == 1)

                <small class="text-dark d-inline">Fullday</small>

                @elseif($user->day_type_id == 2)

                <small class="text-dark d-inline">FH</small>

                @elseif($user->day_type_id == 3)

                <small class="text-dark d-inline">SH</small>
                @elseif($user->day_type_id == 4)

                <small class="text-dark d-inline">H1</small>
                @elseif($user->day_type_id == 5)

                <small class="text-dark d-inline">H2</small>
                @elseif($user->day_type_id == 6)

                <small class="text-dark d-inline">H3</small>

                @elseif($user->day_type_id == 7)

                <small class="text-dark d-inline">H4</small>

                @endif
                @endforeach

                <img src="{{ asset($user->image) }}" class="booked" alt="book">
                <small class="text-dark">Seat No.{{ $seat->seat_no }}</small>

                @else
                <ul>

                    <li><a href="javascript:;" data-bs-toggle="modal" class="first_popup"
                            data-bs-target="#seatAllotmentModal" data-id="{{ $seat->id }}" data-seat_no="{{ $seat->seat_no }}"><i
                                class="fa-solid fa-check-circle available "></i></a></li>
                </ul>
                <small class="text-dark">Available </small>
                <img src="{{ asset('public/img/available.png') }}" alt="book">
                <small class="text-dark">Seat No. {{ $seat->seat_no }}</small>


                @endif
            </div>
            @endforeach


        </div>
    </div>
</div>
<div class="modal fade" id="seatAllotmentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="success-message" class="alert alert-success" style="display:none;"></div>

        <div class="modal-content">
            <div id="error-message" class="alert alert-danger" style="display:none;"></div>
            <div id="validation-error-message" class="alert alert-danger" style="display:none;"></div>
            <div class="modal-header">
                <h1 class="modal-title px-2 fs-5" id="seat_no_head"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="seatAllotmentForm">
                    <div class="detailes">
                        <input type="hidden" name="seat_id" value="" id="seat_id">
                        <input type="hidden" class="form-control char-only" name="seat_no" value="" id="seat_no"
                            autocomplete="off">
                        
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
                        
                            <div class="col-lg-4">
                                <label for="">Select Plan <span>*</span></label>
                                <select name="plan_id" id="plan_id" class="form-select" name="plan_id">
                                    <option value="">Select Plan</option>
                                    @foreach($plans as $key => $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="">Plan Type <span>*</span></label>
                                <select id="plan_type_id" class="form-select" name="plan_type_id">
                                    <option value="">Select Plan Type</option>

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
                                <input type="date" class="form-control" placeholder="Plan Starts On" name="plan_end_date" id="plan_end_date" disabled>
                            </div>


                            <div class="col-lg-4">
                                <label for="">Payment Mode <span>*</span></label>
                                <select name="payment_mode" id="payment_mode" class="form-select">
                                    <option value="">Select Payment Mode</option>
                                    <option value="1">Online</option>
                                    <option value="2">Offline</option>
                                    <option value="3">Pay Later</option>
                                </select>
                            </div>
                        </div>
                        <h4 class="py-4 m-0">Other Important Info 
                            <i id="toggleIcon" class="fa fa-plus" style="cursor: pointer;"></i>
                        </h4>
                        <div id="idProofFields" style="display: none;">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <label for="">Id Proof Received </label>
                                    <select name="" id="id_proof_name" class="form-select" name="id_proof_name">
                                        <option value="">Select Id Proof</option>
                                        <option value="1">Aadhar</option>
                                        <option value="2">Driving License</option>
                                        <option value="3">Other</option>
                                    </select>
                                    <span class="text-danger">Uploading ID proof is optional do it later.</span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="id_proof_file">Upload Scan Copy of Proof</label>
                                    <input type="file" class="form-control" name="id_proof_file" id="id_proof_file"
                                        autocomplete="off">

                                    <a href="javascript:;" id="viewButton" style="display: none;">
                                        <i class="fa fa-eye"></i> View Uploaded File
                                    </a>
                                    <div id="filePopup" class="file-popup" style="display: none;">
                                        <img src="" id="imagePreview" style="display: none;" alt="Selected Image">
                                        <iframe id="pdfPreview" style="display: none;" frameborder="0"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row mt-2">
                            <div class="col-lg-4">
                                <input type="submit" class="btn btn-primary btn-block button" id="submit"
                                    value="Book Library Seat Now" autocomplete="off">
                            </div>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="seatAllotmentModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="seat_details_info">Book Seat</h1>
                <span id="seat_name" style="display: none;"></span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="actions">
                            <div class="upper-box">
                                <h4 class="mb-4">Leraners Info</h4>
                                <div class="row g-4">
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
                                <div class="row g-4">
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
                                    <div>
                                        <h5 id="extendday" class="text-center"></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <input type="hidden" value="" id="user_id">
                                <a id="upgrade" class="btn btn-primary btn-block mt-2 button" style="height : auto;">Renew Library Membership</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
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
                            <div class="col-lg-6">
                                <label for="">Payment Mode <span>*</span></label>
                                <select name="payment_mode" id="payment_mode" class="form-select">
                                    <option value="">Select Payment Mode</option>
                                    <option value="1">Online</option>
                                    <option value="2">Offline</option>
                                    <option value="3">Pay Later</option>
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <span class="text-info">Your upcoming plan starts after your current plan expires.</span>
                            </div>
                            <div class="col-lg-4 mt-1">

                                <input type="hidden" class="form-control char-only" name="seat_no" value="" id="update_seat_no">
                                <input type="hidden" class="form-control char-only" name="user_id" value="" id="update_user_id">
                                <input type="submit" class="btn btn-primary btn-block button" id="submit" value="Renew Membership Now">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('learner.script')
<script>
   $(document).ready(function() {
    // Check if the animation has already been run in the current session
    if (!sessionStorage.getItem('seatsAnimated')) {
        // Animate each seat one by one
        $('.seat').each(function(index) {
            $(this).delay(index * 200).queue(function(next) {
                $(this).css({
                    'opacity': '1',
                    'transform': 'translateY(0)'
                });
                next(); // Move to the next item in the queue
            });
        });

        // After all animations complete, set the sessionStorage flag
        setTimeout(function() {
            sessionStorage.setItem('seatsAnimated', 'true');
        }, $('.seat').length * 200 + 500); // Wait for all seats to animate
    } else {
        // If the animation has already run, make all seats visible immediately
        $('.seat').css({
            'opacity': '1',
            'transform': 'translateY(0)',
            'transition': 'none' // Disable the transition so they don't animate again
        });
    }
});

</script>

<script>
    document.getElementById('plan_start_date').addEventListener('change', function () {
        const startDate = new Date(this.value);
        if (startDate) {
            // Add 30 days to the start date
            const endDate = new Date(startDate);
            endDate.setDate(endDate.getDate() + 30);

            // Format the date to yyyy-mm-dd for the input field
            const formattedDate = endDate.toISOString().split('T')[0];

            // Set the calculated end date in the input field
            document.getElementById('plan_end_date').value = formattedDate;
        }
    });
</script>
@endsection