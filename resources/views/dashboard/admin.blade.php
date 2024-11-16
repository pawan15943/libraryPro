@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
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
                <h1>Welcome to <span>Libraro</span><br>
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
                        <li>Plan Features : <a href="{{route('library.myplan')}}">{{$features_count}}</a> </li>
                        <li>Plan Price :
                            <a href="{{route('library.transaction')}}">{{$check->amount}}
                                @if($check->month==12)
                                (Yearly)
                                @else
                                (Monthly)
                                @endif

                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @php
    $currentYear = date('Y');
    $currentMonth = date('m');
    @endphp
    <div class="row align-items-center mt-4">
        <div class="col-lg-3">
            <h4>Filter Dashboard Data</h4>
        </div>
        <div class="col-lg-3"></div>
        <div class="col-lg-3">
            <select id="datayaer" class="form-select form-control-sm">
                @foreach($dynamicyears as $year)
                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                    {{ $year }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3">
            <select id="dataFilter" class="form-select form-control-sm">
                <option value="">Select Month</option>
                @foreach($dynamicmonths as $month)
                <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}"
                    {{ str_pad($month, 2, '0', STR_PAD_LEFT) == $currentMonth ? 'selected' : '' }}>
                    {{ DateTime::createFromFormat('!m', $month)->format('M') }}
                </option>
                @endforeach
            </select>
        </div>


        {{-- <div class="col-lg-6">
            <label for="dateRange" class="form-label">Select Date Range:</label>
            <input type="text" id="dateRange" class="form-control form-control-sm" placeholder="YYYY-MM-DD to YYYY-MM-DD">
        </div> --}}

    </div>

    <!-- Library Main Counts -->
    <div class="row  g-4 mt-1 mb-4">
        <div class="col-lg-3">
            <div class="main-count cardbg-1">
                <span>Total Seats</span>
                <h2 id="total_seat">0</h2>
                <small>As Today {{date('d-m-Y')}}</small>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-3">
            <div class="main-count cardbg-2">
                <span>Booked Seats</span>
                <h2 id="booked_seat">0</h2>
                <a href="{{ route('learners.list.view', ['type' => 'total_booking']) }}" class="text-white text-decoration-none">View All <i class="fa fa-long-arrow-right ms-2"></i></a>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-3">
            <div class="main-count cardbg-2">
                <span>Avaialble Seats</span>
                <h2 id="available_seat">0</h2>

                <a href="{{route('seats')}}" class="text-white text-decoration-none">View All <i class="fa fa-long-arrow-right ms-2"></i></a>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-3">
            <div class="main-count cardbg-4">
                <span>Expired Seats</span>
                <h2 id="expired_seat">0</h2>

                <a href="{{route('learners.list.view', ['type' => 'expired_seats'])}}" class="text-white text-decoration-none">View All <i class="fa fa-long-arrow-right ms-2"></i></a>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Library Revenue -->
    <h4 class="my-4">Monthly Revenues</h4>
    <div class="row g-4">
        <div class="col-lg-9">
            <div class="v-content">

                <ul class="revenue-box scroll-x " id="monthlyData">
                    {{-- @foreach($revenues as $revenue)

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
                            <small>Monthly Revenue</small>
                            <h4 class="totalRevenue" data-box=""></h4>
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
                    @endforeach --}}

                </ul>
            </div>

        </div>
    </div>
    <!-- End -->


    <!-- Library Other Counts -->
    <div class="row g-4 align-items-center">
        <div class="col-lg-9">
            <h4 class="my-4">Total Slots Booked Till Today</h4>
        </div>
    </div>


    <div class="row g-4">
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-3">
                <h6>Total Slots Bookings</h6>
                <div class="d-flex">
                    <h4 id="totalBookings">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="{{ route('learners.list.view', ['type' => 'total_booking']) }}" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-4">
                <h6>Active Slots</h6>
                <div class="d-flex">
                    <h4 id="active_booking">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="{{ route('learners.list.view', ['type' => 'active_booking']) }}" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-1">
                <h6>Expired Slots</h6>
                <div class="d-flex">
                    <h4 id="expiredSeats">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="{{ route('learners.list.view', ['type' => 'expired_seats']) }}" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
    </div>
    <h4 class="pt-4">Current Month Slots Booked</h4>
    <div class="col-lg-12 pb-4">
        <p class="text-danger m-0 mt-1">Note : Expired and Extended seat counts are always based on the Past and Current Month, as the system operates on a monthly subscription model.</p>
    </div>
    <div class="row g-4">
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-3">
                <h6>This Month Total</h6>
                <div class="d-flex">
                    <h4 id="thismonth_total_book">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="{{ route('learners.list.view', ['type' => 'thisbooking_slot']) }}" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-4">
                <h6>This Month Booked</h6>
                <div class="d-flex">
                    <h4 id="month_total_active_book">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="{{ route('learners.list.view', ['type' => 'booing_slot']) }}" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-1">
                <h6>This Month Expired</h6>
                <div class="d-flex">
                    <h4 id="month_all_expired">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="{{ route('learners.list.view', ['type' => 'expire_booking_slot']) }}" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-1">
                <h6>Expired in 5 Days</h6>
                <div class="d-flex">
                    <h4 id="expiredInFive">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="{{ route('learners.list.view', ['type' => 'expired_in_five']) }}" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-4">
                <h6>Extended Seats</h6>
                <div class="d-flex">
                    <h4 id="extended_seats">0</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="{{ route('learners.list.view', ['type' => 'extended_seat']) }}" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-3">
                <h6>Online Paid</h6>
                <div class="d-flex">
                    <h4 id="onlinePaid">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="{{ route('learners.list.view', ['type' => 'online_paid']) }}" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-3">
                <h6>Offline Paid</h6>
                <div class="d-flex">
                    <h4 id="offlinePaid">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="{{ route('learners.list.view', ['type' => 'offline_paid']) }}" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-3">
                <h6>Pay Later</h6>
                <div class="d-flex">
                    <h4 id="otherPaid">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="{{ route('learners.list.view', ['type' => 'other_paid']) }}" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
       
        
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-3">
                <h6>Swap Seats</h6>
                <div class="d-flex">
                    <h4 id="swap_seat">0</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="{{ route('learners.list.view', ['type' => 'swap_seat']) }}" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-3">
                <h6>Upgrade Seats</h6>
                <div class="d-flex">
                    <h4 id="learnerUpgrade">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="{{ route('learners.list.view', ['type' => 'learnerUpgrade']) }}" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-3">
                <h6>Reactive Seats</h6>
                <div class="d-flex">
                    <h4 id="reactive">0</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="{{ route('learners.list.view', ['type' => 'reactive_seat']) }}" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-3">
                <h6>Renew Seats</h6>
                <div class="d-flex">
                    <h4 id="renew_seat">0</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="{{ route('learners.list.view', ['type' => 'renew_seat']) }}" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-3">
                <h6>Close Seats</h6>
                <div class="d-flex">
                    <h4 id="close_seat">0</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="{{ route('learners.list.view', ['type' => 'close_seat']) }}" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
            <div class="booking-count bg-3">
                <h6>Delete Seats</h6>
                <div class="d-flex">
                    <h4 id="delete_seat">0</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
                <a href="{{ route('learners.list.view', ['type' => 'delete_seat']) }}" class="viewall">View All <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6 d-none">
            <div class="booking-count bg-4">
                <h6>WhatsApp Sended</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6 d-none">
            <div class="booking-count bg-4">
                <h6>Email Sended</h6>
                <div class="d-flex">
                    <h4>80</h4>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div> 
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

                @foreach($available_seats as $seat)
                <li>
                    <div class="d-flex">
                        <img src="{{ url('public/img/available.png') }}" alt="library" class="img-fluid rounded">
                        <div class="seat-content">
                            <h6>Seat No. {{ $seat['seat_no'] }}</h6>
                            @if(count($seat['available_plan_types']) > 3)
                            <small>Available</small>
                            @else
                            @foreach($seat['available_plan_types'] as $planType)
                            @if($planType['name']=='First Half')
                            <small>FH </small>
                            @elseif($planType['name']=='Second Half')
                            <small>SH </small>
                            @elseif($planType['name']=='Hourly Slot 1')
                            <small>H1 </small>
                            @elseif($planType['name']=='Hourly Slot 2')
                            <small>H2 </small>
                            @elseif($planType['name']=='Hourly Slot 3')
                            <small>H3 </small>
                            @elseif($planType['name']=='Hourly Slot 4')
                            <small>H4 </small>
                            @else
                            <small>{{ $planType['name'] }}</small>
                            @endif

                            @endforeach
                            @endif

                        </div>
                        <a href="javascript:;" data-bs-toggle="modal" class="first_popup book"
                            data-bs-target="#seatAllotmentModal" data-id="{{ $seat['seat_id'] }}" data-seat_no="{{ $seat['seat_no'] }}">Book</a>
                    </div>
                </li>
                @endforeach

            </ul>
            <a href="{{route('seats')}}" class="view-full-info">View All Available Seats</a>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="seat-statistics">
            <h4 class="mb-3 text-center">Seat About to Expire</h4>
            <ul class="contents">
                @if(!$renewSeats->isEmpty())

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
                @else
                <li class="record-not-found">
                    <img src="{{ asset('public/img/record-not-found.png') }}" class="no-record"" alt=" record-not-found">
                    <span>No Expired Seats Available.</span>
                </li>
                @endif
            </ul>
            <a href="{{route('learners')}}" class="view-full-info">View All Availble Seats</a>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="seat-statistics">
            <h4 class="mb-3 text-center">Extend Seats</h4>
            <ul class="contents">
                @if(!$extend_sets->isEmpty())
                
                @foreach($extend_sets as $seat)
                <li>
                    <div class="d-flex" >
                        <img src="{{url('public/img/booked.png')}}" alt="library" class="img-fluid rounded extedned">
                        <div class="seat-content">
                            <h6>Seat No. {{ $seat->seat_no }}</h6>
                            <small>{{ $seat->planType->name}}</small>
                        </div>
                        <div class="seat-status">
                            <p>Expired in {{ \Carbon\Carbon::now()->diffInDays($seat->plan_end_date) }} Days</p>
                            <small><a class="renew_extend" data-seat_no="{{$seat->seat_no}}" data-seat_id="{{$seat->seat_id}}" data-user="{{$seat->learner_id}}" data-end_date="{{$seat->plan_end_date}}">Renew Plan</a></small>
                        </div>

                        <ul class="d-flex inner">
                            <li><a href="https://wa.me/{{ $seat->mobile }}"><i class="fab fa-whatsapp"></i></a></li>
                            <li><a href="mailto:{{ $seat->email }}"><i class="fa fa-envelope"></i></a></li>
                        </ul>
                    </div>
                </li>
                @endforeach
                @else
                <li class="record-not-found">
                    <img src="{{ asset('public/img/record-not-found.png') }}" class="no-record"" alt=" record-not-found">
                    <span>No Extended Seats Available.</span>
                </li>
                @endif
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
                    <input type="hidden" id="hidden_plan">
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
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#dateRange", {
            mode: "range",
            dateFormat: "Y-m-d",
            maxDate: new Date().fp_incr(365), // Set the maximum date to one year from now
        });
    });
</script>

<script>
    (function($) {
        $(window).on("load", function() {
            $(".v-content").mCustomScrollbar({
                theme: "dark",
                scrollInertia: 300,
                axis: "x",
                autoHideScrollbar: false, // Keeps scrollbar visible
            });
        });
    })(jQuery);
</script>
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
    $(document).ready(function() {
        // Fetch initial data based on the default filter (month)
        var initialYear = $('#datayaer').val();
        var initialMonth = $('#dataFilter').val();
        fetchLibraryData(initialYear, initialMonth, null);
        updateAllViewLinks(initialYear, initialMonth, null);

        // Event listener for year filter
        $('#datayaer').on('change', function() {
            var selectedYear = $(this).val();
            var selectedMonth = $('#dataFilter').val();
            fetchLibraryData(selectedYear, selectedMonth, null);
            updateAllViewLinks(selectedYear, selectedMonth, null);
        });

        // Event listener for month filter
        $('#dataFilter').on('change', function() {
            var selectedYear = $('#datayaer').val();
            var selectedMonth = $(this).val();
            fetchLibraryData(selectedYear, selectedMonth, null);
            updateAllViewLinks(selectedYear, selectedMonth, null);
        });

        // Event listener for date range picker
        $('#dateRange').on('change', function() {
            var selectedYear = $('#datayaer').val();
            var selectedMonth = $('#dataFilter').val();
            var dateRange = $(this).val(); // Date range in the format "YYYY-MM-DD to YYYY-MM-DD"
            fetchLibraryData(selectedYear, selectedMonth, dateRange);
            updateAllViewLinks(selectedYear, selectedMonth, dateRange);
        });

        // Function to fetch data based on filters
        function fetchLibraryData(year, month, dateRange) {
            $.ajax({
                url: '{{ route("dashboard.data.get") }}',
                method: 'POST',
                data: {
                    year: year,
                    month: month,
                    date_range: dateRange,
                    _token: '{{ csrf_token() }}' // CSRF token for security
                },
                success: function(response) {
                    updateRevenue(response.revenu_expense);
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

                    // Render charts for Revenue and Booking Count
                    if (response.planTypeWiseRevenue && Array.isArray(response.planTypeWiseRevenue.labels) && Array.isArray(response.planTypeWiseRevenue.data)) {
                        renderRevenueChart(response.planTypeWiseRevenue.labels, response.planTypeWiseRevenue.data);
                    } else {
                        console.error('Invalid data format for planTypeWiseRevenue:', response.planTypeWiseRevenue);
                    }

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

        // Function to update highlights
        function updateHighlights(highlights) {
            console.log('highlights', highlights);

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
            $('#total_seat').text(highlights.total_seat);
            $('#booked_seat').text(highlights.booked_seat);
            $('#available_seat').text(highlights.available_seat);
            $('#expired_seat').text(highlights.expired_seats);
            $('#active_booking').text(highlights.active_booking);
            $('#close_seat').text(highlights.close_seat);
            $('#month_total_active_book').text(highlights.month_total_active_book);
            $('#month_all_expired').text(highlights.month_all_expired);
            $('#thismonth_total_book').text(highlights.thismonth_total_book);
            $('#renew_seat').text(highlights.renew_seat);
            $('#delete_seat').text(highlights.delete_seat);
        }

        function updateAllViewLinks(year, month, dateRange) {
            // Select all "View All" links and update them based on the filters
            $('.viewall').each(function() {
                var currentUrl = $(this).attr('href');

                // Construct the additional query parameters
                var queryParams = [];
                if (year) queryParams.push(`year=${year}`);
                if (month) queryParams.push(`month=${month}`);
                if (dateRange) queryParams.push(`date_range=${dateRange}`);

                // Append the query parameters to the existing URL
                var updatedUrl = currentUrl + (queryParams.length ? '&' + queryParams.join('&') : '');
                $(this).attr('href', updatedUrl);
            });
        }

        function updateRevenue(data) {

            $('#monthlyData').empty();

            // Loop through each item in the data array and create HTML for each month
            data.forEach(function(item) {
                let html = `
                        <li style="background: #fff url('{{ asset('public/img/revenue.png') }}'); background-size: contain; background-position: center;">
                            <div class="d-flex">
                                <h4>${item.month}, ${item.year}</h4> 
                                <span class="toggleButton" data-box=""><i class="fa fa-eye-slash"></i></span>
                            </div>
                            <div class="d-flex mt-10">
                                <div class="value">
                                    <small>Total Revenue</small>
                                    <h4 class="totalRevenue" data-box="1">${item.totalRevenue}</h4>
                                </div>
                                <div class="value">
                                    <small>Monthly Revenue</small>
                                    <h4 class="totalRevenue" data-box="2">${item.monthlyRevenue}</h4>
                                </div>
                                <div class="value">
                                    <small>Total Expense</small>
                                    <h4 class="totalExpense text-danger" data-box="3">${item.totalExpense}</h4>
                                </div>
                                <div class="value">
                                    <small>Net Profit</small>
                                    <h4 class="netProfit text-success" data-box="4">${item.netProfit}</h4>
                                </div>
                            </div>
                        </li>`;


                $('#monthlyData').append(html);
            });
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
                label: `Plan Type Wise Revenue (Total: ${totalCount})`, // Total revenue
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
                        display: false // Show y-axis labels
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
                        display: false // Hide x-axis border line
                    }
                }
            },
            plugins: {
                legend: {
                    display: true, // Show legend
                    labels: {
                        boxWidth: 15, // Legend box size
                        padding: 10, // Add padding
                        color: 'rgba(0, 0, 0, 0.7)' // Adjust label color
                    }
                },
                datalabels: {
                    color: 'rgba(0, 0, 0, 0.8)', // Label color
                    display: true, // Enable datalabels
                    anchor: 'end',
                    align: 'top',
                    font: {
                        size: 14,
                        weight: 'bold'
                    },
                    formatter: (value) => value // Show raw data value
                }
            }
        },
        plugins: [ChartDataLabels] // Register the datalabels plugin
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
            labels: labels.map((label, index) => `${label}: ${data[index]} bookings`), // Add counts to labels
            datasets: [{
                label: 'Plan Type Wise Booking Count',
                data: data,
                backgroundColor: [
                    '#001f3f', // Dark Navy for Full Day
                    '#85144b', // Maroon for First Half
                    '#FF4136', // Red for Second Half
                    '#3D9970', // Dark Green for Hourly 1
                    '#FF851B', // Orange for Hourly 2
                    '#0074D9', // Blue for Hourly 3
                    '#7FDBFF'  // Light Blue for Hourly 4
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
                    labels: {
                        color: '#000', // Legend text color
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const label = tooltipItem.label || '';
                            const value = tooltipItem.raw || 0;
                            return `${label}: ${value} bookings`;
                        }
                    }
                },
                datalabels: {
                    color: '#fff', // Label text color
                    display: true,
                    formatter: (value) => value, // Show count directly on the chart
                    font: {
                        size: 20,
                        weight: 'regular'
                    }
                }
            }
        },
        plugins: [ChartDataLabels] // Register ChartDataLabels plugin
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Initially hide the values (show as ****)
    $('.totalRevenue, .monthlyRevenue, .totalExpense, .netProfit').each(function() {
        $(this).data('originalValue', $(this).text()); // Store the original value in a data attribute
        $(this).text('****'); // Hide the value initially
    });

    // Toggle button logic
    $('.toggleButton').on('click', function() {
        alert('dsfdsf');
    });

    function toggleSupportCard() {
        $('#supportCard').toggle();
    }
</script>
@include('learner.script')


@endsection