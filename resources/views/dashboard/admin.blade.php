@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
<!-- New Design Dahsbard Library -->
<div class="support-container">
    <div class="support-icon" onclick="toggleSupportCard()">
        <i class="fa-solid fa-phone-volume"></i>
    </div>
    <div class="support-card" id="supportCard">

        <p><strong><i class="fa-solid fa-phone-volume"></i> Contact Us:</strong></p>
        <p>Phone: 123-456-7890</p>
        <p>Email: support@library.com</p>
    </div>
</div>
<div class="dashboard">
    <div class="row">
        <div class="col-lg-12">
            <div class="greeting-container">
                <i id="greeting-icon" class="fas fa-sun greeting-icon"></i>
                <h2 id="greeting-message" class="typing-text">Good Morning! Library Owner</h2>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-9">
            <div class="dashboard-Header">
                <img src="{{url('public/img/bg-library-welcome.png')}}" alt="library" class="img-fluid rounded">
                <h1>Welcome to <span>LibraryPro</span><br>
                    Let’s Make Your <span class="typing-text"> Library the Place to Be! 📚🌟</span></h1>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="active-plan-box 
            @switch($plan->name)
                @case('Basic Plan')
                    basic
                    @break
                @case('Standard Plan')
                    standard
                    @break
                @case('Premium Plan')
                    premium
                    @break
            @endswitch">
                <div class="top-content">
                    <h4>{{$plan->name}}
                        @if(($librarydiffInDays <= 5 && !$is_renew && $isProfile))
                            <span><a href="{{ route('subscriptions.choosePlan') }}">Upgrade Plan</a></span>
                            @endif
                    </h4>
                    <label for="">Active </label>
                </div>
                <div class="d-flex">
                    <ul class="plann-info">
                        <li>Total Seat : <a href="{{route('seats')}}">{{$total_seats}}</a> </li>
                        <li>Plan Features : <br>{{$features_count}} <a href="{{route('library.myplan')}}" class="d-inline">View All Features</a> </li>
                        <li>Plan Price : <a href="{{route('library.transaction')}}">{{$check->amount}} ({{ $check->month == '12' ? 'Yearly' : 'Monthly' }})</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Library Main Counts -->
    <div class="row  g-4 my-4">
        <div class="col-lg-3">
            <div class="main-count cardbg-1">
                <span>Total Seats</span>
                <h2>{{$total_seats}}</h2>
                <small>As Today {{date('d-m-Y')}}</small>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-3">
            <div class="main-count cardbg-2">
                <span>Booked Seats</span>
                <h2>{{$booked_seats}}</h2>
                <a href="" class="text-white text-decoration-none">View All <i class="fa fa-long-arrow-right ms-2"></i></a>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-3">
            <div class="main-count cardbg-2">
                <span>Avaialble Seats</span>
                <h2>{{$availble_seats}}</h2>
                <a href="" class="text-white text-decoration-none">View All <i class="fa fa-long-arrow-right ms-2"></i></a>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-3">
            <div class="main-count cardbg-4">
                <span>Expired Seats</span>
                <h2>{{$availble_seats}}</h2>
                <a href="" class="text-white text-decoration-none">View All <i class="fa fa-long-arrow-right ms-2"></i></a>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Library Revenue -->
    <h4 class="my-4">Monthly Revenues</h4>
    <div class="row g-4">
        <div class="col-lg-12">
            <ul class="revenue-box scroll-x">
                @foreach($revenues as $revenue)

                @php

                $monthName = Carbon\Carbon::createFromDate($revenue['year'], $revenue['month'])->format('F');
                $expense = $expenses->first(function($item) use ($revenue) {
                return $item->year == $revenue['year'] && $item->month == $revenue['month'];
                });
                $total_expense = $expense ? $expense->total_expense : 0;
                $net_profit = $revenue->total_revenue - $total_expense;

                @endphp
                <li style="background: #fff url('{{ asset('public/img/revenue.png') }}');background-size: contain; background-position: center;">
                    <div class="d-flex">
                        <h4>{{ $monthName }}, {{ $revenue->year }}</h4>
                        <span class="toggleButton" data-box="{{ $loop->index + 1 }}"><i class="fa fa-eye-slash"></i></span>
                    </div>
                    <div class="d-flex mt-10">
                        <div class="value">
                            <small>Total Revenue</small>
                            <h4 class="totalRevenue" data-box="{{ $loop->index + 1 }}">{{ $revenue->total_revenue }}</h4>
                        </div>
                        <div class="value">
                            <small>Total Expense</small>
                            <h4 class="totalExpense text-danger" data-box="{{ $loop->index + 1 }}">{{ $total_expense }}</h4>
                        </div>
                        <div class="value">
                            <small>Net Profit</small>
                            <h4 class="netProfit text-success" data-box="{{ $loop->index + 1 }}">{{ $net_profit }}</h4>
                        </div>
                    </div>
                </li>
                @endforeach

            </ul>
        </div>
    </div>
    <!-- End -->


    <!-- Library Other Counts -->
    <div class="row g-4 mb-3 align-items-center">
        <div class="col-lg-9">
            <h4 class="my-4">Library Other Highlights</h4>
        </div>
        <div class="col-lg-3">
            <select id="dataFilter" class="form-select form-control-sm">
                <option value="all">All</option>
                <option value="monthly">Monthly</option>
                <option value="weekly">Weekly</option>
                <option value="today">Today</option>
            </select>
        </div>
    </div>


    <div class="row g-4">
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-3">
                <h6>Total Bookings</h6>
                <div class="d-flex">
                    <h4 id="totalBookings">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-3">
                <h6>Online Paid</h6>
                <div class="d-flex">
                    <h4 id="onlinePaid">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-3">
                <h6>Offline Paid</h6>
                <div class="d-flex">
                    <h4 id="offlinePaid">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-3">
                <h6>Other Paid</h6>
                <div class="d-flex">
                    <h4 id="otherPaid">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-1">
                <h6>Expired in 5 Days</h6>
                <div class="d-flex">
                    <h4 id="expiredInFive">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-2">
                <h6>Expired Seats</h6>
                <div class="d-flex">
                    <h4 id="expiredSeats">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-4">
                <h6>Extended Seats</h6>
                <div class="d-flex">
                    <h4 id="extended_seats">80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-4">
                <h6>Swap Seats</h6>
                <div class="d-flex">
                    <h4 id="swap_seat">80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-4">
                <h6>Upgrade Seats</h6>
                <div class="d-flex">
                    <h4 id="learnerUpgrade">80</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-4">
                <h6>Reactive Seats</h6>
                <div class="d-flex">
                    <h4 id="reactive">80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        {{-- <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-4">
                <h6>WhatsApp Sended</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
    </div>
</div>
<div class="col-lg-2 col-md-3 col-sm-6 col-6">
    <div class="booking-count bg-4">
        <h6>Email Sended</h6>
        <div class="d-flex">
            <h4>80</h4>

        </div>
        <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
    </div>
</div> --}}

</div>
<!-- End -->
<h4 class="my-4">Plan Wise Count</h4>
<!-- Plan Wise Booking Counts -->
<div class="row g-4 planwisecount">
    <div class="col-lg-2 col-md-3 col-sm-6 col-6">
        <div class="booking-count bg-4">
            <h6>Full Day</h6>
            <div class="d-flex">
                <h4>80</h4>
            </div>
            <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6 col-6">
        <div class="booking-count bg-4">
            <h6>First Half</h6>
            <div class="d-flex">
                <h4>80</h4>
            </div>
            <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6 col-6">
        <div class="booking-count bg-4">
            <h6>Second Half</h6>
            <div class="d-flex">
                <h4>80</h4>
            </div>
            <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6 col-6">
        <div class="booking-count bg-4">
            <h6>Hourly Slot 1</h6>
            <div class="d-flex">
                <h4>80</h4>
            </div>
            <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6 col-6">
        <div class="booking-count bg-4">
            <h6>Hourly Slot 2</h6>
            <div class="d-flex">
                <h4>80</h4>
            </div>
            <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6 col-6">
        <div class="booking-count bg-4">
            <h6>Hourly Slot 3</h6>
            <div class="d-flex">
                <h4>80</h4>
            </div>
            <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6 col-6">
        <div class="booking-count bg-4">
            <h6>Hourly Slot 4</h6>
            <div class="d-flex">
                <h4>80</h4>
            </div>
            <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6 col-6">
        <div class="booking-count bg-3">
            <h6>Total Booked Seats</h6>
            <div class="d-flex">
                <h4>80</h4>
            </div>
            <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6 col-6">
        <div class="booking-count bg-3">
            <h6>Available Seats</h6>
            <div class="d-flex">
                <h4>80</h4>
            </div>
            <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
        </div>
    </div>

</div>
<!-- End -->

<!-- Dahboard Charts -->
<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card">
            <h5 class="mb-3">Planwise Revenue</h4>
            <canvas id="revenueChart" style="max-height:340px;"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <h5 class="mb-3">Planwise Booking</h4>
            <canvas id="bookingCountChart"></canvas>
        </div>
    </div>
</div>
<!-- End -->


<!-- Available Seats -->

<div class="row g-4 mt-2 mb-4">
    <div class="col-lg-4">

        <!-- Show 10 availble Seats -->
        <div class="seat-statistics ">
            <h4 class="mb-3 text-center">Avaialble Seats</h4>
            <ul class="contents">

                @foreach($available_seats as $key => $value)
                <li>
                    <div class="d-flex">
                        <img src="{{url('public/img/available.png')}}" alt="library" class="img-fluid rounded">
                        <div class="seat-content">
                            <h6>Seat No. {{$value}}</h6>
                            <small>Available</small>
                        </div>

                        <a href="javascript:;" data-bs-toggle="modal" class="first_popup book"
                            data-bs-target="#seatAllotmentModal" data-id="{{$key}}" data-seat_no="{{$value}}">Book</a>
                    </div>
                </li>
                @endforeach
                <li class="record-not-found">
                    <img src="{{ asset('public/img/record-not-found.png') }}" class="no-record"" alt=" record-not-found">
                    <span>No Seats Available to Book</span>
                </li>
            </ul>
            <a href="{{route('seats')}}" class="view-full-info">View All Available Seats</a>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="seat-statistics">
            <h4 class="mb-3 text-center">Seat About to Expire</h4>
            <ul class="contents">
                @foreach($renewSeats as $key => $value)
                <li>
                    <div class="d-flex">
                        <img src="{{url('public/img/booked.png')}}" alt="library" class="img-fluid rounded">
                        <div class="seat-content">
                            <h6>Seat No. {{$value->seat_no}}</h6>
                            <small>{{$value->planType->name}}</small>
                        </div>
                        <div class="seat-status">
                            <p>Expired in {{ \Carbon\Carbon::now()->diffInDays($value->plan_end_date) }} Days</p>
                            <small><a class="renew_extend" data-seat_no="{{$value->seat_no}}" data-user="{{$value ->learner_id}}" data-end_date="{{$value->plan_end_date}}">Renew Plan</a></small>
                        </div>

                        <ul class="d-flex inner">
                            <li><a href="https://wa.me/{{ $value->mobile }}"><i class="fab fa-whatsapp"></i></a></li>
                            <li><a href="mailto:{{ $value->email }}"><i class="fa fa-envelope"></i></a></li>
                        </ul>
                    </div>
                </li>
                @endforeach
                <li class="record-not-found">
                    <img src="{{ asset('public/img/record-not-found.png') }}" class="no-record"" alt=" record-not-found">
                    <span>No Expired Seats Available.</span>
                </li>
            </ul>
            <a href="{{route('learners')}}" class="view-full-info opacity-0">View All Availble Seats</a>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="seat-statistics">
            <h4 class="mb-3 text-center">Extend Seats</h4>
            <ul class="contents">
                @foreach($extend_sets as $seat)
                <li>
                    <div class="d-flex">
                        <img src="{{url('public/img/booked.png')}}" alt="library" class="img-fluid rounded extedned">
                        <div class="seat-content">
                            <h6>Seat No. {{ $seat->seat_no }}</h6>
                            <small>{{ $seat->planType->name}}</small>
                        </div>
                        <div class="seat-status">
                            <p>Expired in {{ \Carbon\Carbon::now()->diffInDays($seat->plan_end_date) }} Days</p>
                            <small><a class="renew_extend" data-seat_id="{{$seat->seat_id}}" data-user="{{$seat->learner_id}}" data-end_date="{{$seat->plan_end_date}}">Renew Plan</a></small>
                        </div>

                        <ul class="d-flex inner">
                            <li><a href="https://wa.me/{{ $seat->mobile }}"><i class="fab fa-whatsapp"></i></a></li>
                            <li><a href="mailto:{{ $seat->email }}"><i class="fa fa-envelope"></i></a></li>
                        </ul>
                    </div>
                </li>
                @endforeach
                <li class="record-not-found">
                    <img src="{{ asset('public/img/record-not-found.png') }}" class="no-record"" alt=" record-not-found">
                    <span>No Extended Seats Available.</span>
                </li>

            </ul>

            <a href="{{route('learners')}}" class="view-full-info ">View All Availble Seats</a>
        </div>
    </div>
</div>


<!-- Charts -->

</div>
<!-- End -->
<!-- seat book poup -->
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
<div class="modal fade" id="seatAllotmentModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div id="success-message" class="alert alert-success" style="display:none;"></div>
    <div id="error-message" class="alert alert-danger" style="display:none;"></div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title px-2 fs-5" id="seat_number_upgrades">Re-New Lerners Plan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-0">
                <form id="upgradeForm" class="m-0">
                    <div class="">
                        <div class="row g-4 m-0">
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
                                <span class="text-info text-center">Your upcoming plan starts after your current plan expires.</span>
                            </div>
                            <div class="col-lg-5 mt-3">

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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

<script>
    (function($) {
        $(window).on("load", function() {
            $(".contents").mCustomScrollbar({
                theme: "dark",
                scrollInertia: 300,
                axis: "y",
                autoHideScrollbar: false, // Keeps scrollbar visible
            });
        });
    })(jQuery);
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initially hide the values (show as ****)
        document.querySelectorAll('.totalRevenue, .totalExpense, .netProfit').forEach(function(item) {
            item.dataset.originalValue = item.innerText; // Store the original value in a data attribute
            item.innerText = '****'; // Hide the value initially
        });

        // Toggle button logic
        document.querySelectorAll('.toggleButton').forEach(function(button) {
            button.addEventListener('click', function() {
                const boxId = this.getAttribute('data-box');
                const revenue = document.querySelector(`.totalRevenue[data-box="${boxId}"]`);
                const expense = document.querySelector(`.totalExpense[data-box="${boxId}"]`);
                const profit = document.querySelector(`.netProfit[data-box="${boxId}"]`);
                const icon = this.querySelector('i');

                // Check current state and toggle visibility
                if (revenue.innerText === '****') {
                    // Show original values
                    revenue.innerText = revenue.dataset.originalValue;
                    expense.innerText = expense.dataset.originalValue;
                    profit.innerText = profit.dataset.originalValue;
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    // Hide values
                    revenue.innerText = '****';
                    expense.innerText = '****';
                    profit.innerText = '****';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });
        });
    });

    function toggleSupportCard() {
        var card = document.getElementById('supportCard');
        card.style.display = card.style.display === 'block' ? 'none' : 'block';
    }
</script>
<script>
    $(document).ready(function() {
        var initialFilter = $('#dataFilter').val();
        fetchLibraryData(initialFilter);

        // Fetch data when the filter is changed
        $('#dataFilter').on('change', function() {
            var selectedFilter = $(this).val();
            fetchLibraryData(selectedFilter);
        });

        function fetchLibraryData(filter) {
            $.ajax({
                url: '{{ route("dashboard.data.get") }}',
                method: 'POST',
                data: {
                    filter: filter, // Use the filter parameter passed to the function
                    _token: '{{ csrf_token() }}' // CSRF token for security
                },
                success: function(response) {

                    updateHighlights(response.highlights);
                    console.log('Full response:', response);

                    var planWiseBookings = response.plan_wise_booking;
                    $('.row.g-4.planwisecount').empty(); // Clear existing data

                    planWiseBookings.forEach(function(booking) {
                        var html = `
                            <div class="col-lg-2">
                                <div class="booking-count bg-4">
                                    <h6>${booking.plan_type_name}</h6>
                                    <div class="d-flex">
                                        <h4>${booking.booking}</h4>
                                    </div>
                                    <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                                </div>
                            </div>`;
                        $('.row.g-4.planwisecount').append(html);
                    });
                    // Ensure labels and data for Revenue Chart are properly fetched
                    if (response.planTypeWiseRevenue && Array.isArray(response.planTypeWiseRevenue.labels) && Array.isArray(response.planTypeWiseRevenue.data)) {
                        renderRevenueChart(response.planTypeWiseRevenue.labels, response.planTypeWiseRevenue.data);
                    } else {
                        console.error('Invalid data format for planTypeWiseRevenue:', response.planTypeWiseRevenue);
                    }

                    // Ensure labels and data for Booking Count Chart are properly fetched
                    if (response.planTypeWiseCount && Array.isArray(response.planTypeWiseCount.labels) && Array.isArray(response.planTypeWiseCount.data)) {
                        renderBookingCountChart(response.planTypeWiseCount.labels, response.planTypeWiseCount.data);
                    } else {
                        console.error('Invalid data format for planTypeWiseCount:', response.planTypeWiseCount);
                    }




                },
                error: function(xhr) {
                    console.error(xhr);
                }
            });
        }

        function updateHighlights(highlights) {

            $('#totalBookings').text(highlights.total_booking);
            $('#onlinePaid').text(highlights.online_paid);
            $('#offlinePaid').text(highlights.offline_paid);
            $('#otherPaid').text(highlights.other_paid);
            $('#expiredInFive').text(highlights.expired_in_five);
            $('#expiredSeats').text(highlights.expired_seats);
            $('#extended_seats').text(highlights.extended_seats);
            $('#swap_seat').text(highlights.swap_seat);
            $('#learnerUpgrade').text(highlights.learnerUpgrade);
            $('#reactive').text(highlights.reactive);
        }

    });
</script>
<script>
    function renderRevenueChart(labels, data) {
        if (Chart.getChart("revenueChart")) {
            Chart.getChart("revenueChart").destroy();
        }

        var ctx = document.getElementById('revenueChart').getContext('2d');

        // Create gradient
        var gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, '#001f3f'); // Navy
        gradient.addColorStop(1, '#0a284b'); // Dark Navy

        var totalCount = data.reduce((a, b) => a + b, 0); // Calculate the total count

        var revenueChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: `Plan Type Wise Revenue (Total: ${totalCount})`, // Displaying total count in legend
                    data: data,
                    backgroundColor: gradient,
                    borderColor: 'rgba(54, 162, 235, 1)', // Blue Border
                    borderWidth: 0,
                    borderRadius: 15, // Rounded Edges
                    barThickness: 30, // Bar Width
                    borderSkipped: false,
                }]
            },
            options: {
                animation: {
                    duration: 2000, // Animation duration
                    easing: 'easeInOutQuart' // Animation easing
                },

                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: false // Remove y-axis grid lines
                        },
                        ticks: {
                            display: false // Hide y-axis labels
                        },
                        border: {
                            display: false // Hide y-axis border line
                        }
                    },
                    x: {
                        grid: {
                            display: false // Remove x-axis grid lines
                        },

                        border: {
                            display: false // Hide y-axis border line
                        }
                    }
                },
            },
            plugins: {
                legend: {
                    display: true, // Show legend
                    labels: {
                        boxWidth: 0, // Remove the box
                        padding: 10, // Add padding
                        color: 'rgba(0, 0, 0, 0.7)' // Adjust label color
                    }
                },
                datalabels: {
                    color: 'rgba(0, 0, 0, 0.7)',
                    display: true,
                    anchor: 'end',
                    align: 'top',
                    offset: 4,
                    font: {
                        weight: 'bold', // Font weight
                        size: 12 // Font size
                    }
                }
            }
        });
    }


    function renderBookingCountChart(labels, data) {
        if (Chart.getChart("bookingCountChart")) {
            Chart.getChart("bookingCountChart").destroy();
        }
        var ctx1 = document.getElementById('bookingCountChart').getContext('2d');
        var bookingCountChart = new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Plan Type Wise Booking Count',
                    data: data,
                    backgroundColor: [
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 206, 86, 0.6)'
                    ],
                    borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' bookings';
                            }
                        }
                    }
                }
            }
        });
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const greetingMessage = document.getElementById('greeting-message');
        const greetingIcon = document.getElementById('greeting-icon');

        const currentHour = new Date().getHours();
        let greetingText = "Good Morning! Library Owner";
        let iconClass = "fas fa-sun"; // Morning icon

        if (currentHour >= 12 && currentHour < 17) {
            greetingText = "Good Afternoon! Library Owner";
            iconClass = "fas fa-cloud-sun"; // Afternoon icon
        } else if (currentHour >= 17 && currentHour < 20) {
            greetingText = "Good Evening! Library Owner";
            iconClass = "fas fa-cloud-moon"; // Evening icon
        } else if (currentHour >= 20 || currentHour < 5) {
            greetingText = "Good Night! Library Owner";
            iconClass = "fas fa-moon"; // Night icon
        }

        greetingMessage.textContent = greetingText;
        greetingIcon.className = `${iconClass} greeting-icon`;
    });
</script>
@include('learner.script')


@endsection