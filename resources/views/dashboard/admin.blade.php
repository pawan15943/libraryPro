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


    <div class="row align-items-center ">
        <div class="col-lg-6">
            <h1>Welcome to <span>LibraryPro</span>
                Where <span class="typing-text">Great Minds Gather!</span></h1>
        </div>
        <div class="col-lg-6">
            <div class="active-plan-box">
                <div class="top-content">
                    <h4>{{$plan->name}}<span><a href="">Upgrade Plan</a></span></h4>
                    <label for="">Active</label>
                </div>
                <div class="d-flex">
                    <ul class="plann-info">
                        <li>Total Seat : <a href="">{{$total_seats}}</a> </li>
                        <li>Plan Features : <a href="">{{$features_count}}</a> </li>
                        <li>Plan Price : <a href="">{{$check->amount}}</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="dashboard-Header">
                <img src="{{url('public/img/bg-library-welcome.png')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
    </div>
    <!-- Library Main Counts -->
    <div class="row my-4">
        <div class="col-lg-4">
            <div class="main-count cardbg-1">
                <span>Total Seats</span>
                <h2>{{$total_seats}}</h2>
                <small>Added on {{date('d-m-Y')}}</small>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="main-count cardbg-2">
                <span>Booked Seats</span>
                <h2>{{$booked_seats}}</h2>
               <small>Added on {{date('d-m-Y')}}</small>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="main-count cardbg-2">
                <span>Avaialble Seats</span>
                <h2>{{$availble_seats}}</h2>
               <small>Added on {{date('d-m-Y')}}</small>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
    </div>

    <h4 class="my-4">Monthly Revenues</h4>
    <div class="row">
        <div class="col-lg-12">
            <ul class="revenue-box scroll-x">
                @foreach($revenues  as $revenue)
                @php
                  $monthName = Carbon\Carbon::createFromDate($revenue['year'], $revenue['month'])->format('F');
                    $expense = $expenses->firstWhere('year', $revenue->year) && $expenses->firstWhere('month', $revenue->month);
                    $total_expense = $expense ? $expense->total_expense : 0;
                    $net_profit = $revenue->total_revenue - $total_expense;
                @endphp
                <li>
                    <div class="d-flex">
                        <h4>{{ $monthName }} {{ $revenue->year }} Revenue</h4>
                        <span class="toggleButton" data-box="{{ $loop->index + 1 }}"><i class="fa fa-eye-slash"></i></span>
                    </div>
                    <div class="d-flex mt-10">
                        <div class="value">
                            <small>Total Revenue</small>
                            <h4 class="totalRevenue" data-box="{{ $loop->index + 1 }}">{{ $revenue->total_revenue }}</h4>
                        </div>
                        <div class="value">
                            <small>Total Expense</small>
                            <h4 class="totalExpense" data-box="{{ $loop->index + 1 }}">{{ $total_expense }}</h4>
                        </div>
                        <div class="value">
                            <small>Net Profit</small>
                            <h4 class="netProfit" data-box="{{ $loop->index + 1 }}">{{ $net_profit }}</h4>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>


    <!-- Library Other Counts -->
    <h4 class="my-4">Library Other Highlights</h4>
    <div class="row g-4">
        <div class="col-lg-2">
            <div class="booking-count bg-3">
                <h6>Total Bookings</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-3">
                <h6>Online Paid</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-3">
                <h6>Online Paid</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-3">
                <h6>Offline Paid</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Expired in 5 Days</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-2">
                <h6>Expired Seats</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-4">
                <h6>Extended Seats</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-4">
                <h6>Swap Seats</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-4">
                <h6>Upgrade Seats</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-4">
                <h6>Reactive Seats</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-4">
                <h6>WhatsApp Sended</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-4">
                <h6>Email Sended</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
    </div>

    <h4 class="my-4">Plan Wise Count</h4>
    <div class="row g-4">
        <div class="col-lg-2">
            <div class="booking-count bg-4">
                <h6>Full Day</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-4">
                <h6>First Half</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-4">
                <h6>Second Half</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-4">
                <h6>Hourly Slot 1</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-4">
                <h6>Hourly Slot 2</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-4">
                <h6>Hourly Slot 3</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-4">
                <h6>Hourly Slot 4</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-3">
                <h6>Total Booked Seats</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-3">
                <h6>Available Seats</h6>
                <div class="d-flex">
                    <h4>80</h4>

                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
    </div>


    <!-- Available Seats -->
    <div class="row mt-5">
        <div class="col-lg-4">

            <!-- Show 10 availble Seats -->
            <div class="seat-statistics ">
                <h4 class="mb-4 text-center">Avaialble Seats</h4>
                <ul class="contents">
                    
                    @foreach($available_seats as $key => $value)
                    <li>
                        <div class="d-flex">
                            <img src="{{url('public/img/available.png')}}" alt="library" class="img-fluid rounded">
                            <div class="seat-content">
                                <h6>Seat No. {{$value}}</h6>
                                <small>Available</small>
                            </div>
                            <a href="" class="book">Book</a>
                        </div>
                    </li>
                    @endforeach
                   
                </ul>
                <a href="" class="view-full-info">View All Available Seats</a>
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
                                <small>{{$value->plan_type->name}}</small>
                            </div>
                            <div class="seat-status">
                                <p>Expired in 2 Days</p>
                                <small><a href="{{ route('learner.payment', [$seat->id]) }}">Renew Plan</a></small>
                            </div>

                            <ul class="d-flex inner">
                                <li><a href="https://wa.me/{{ $seat->mobile }}"><i class="fab fa-whatsapp"></i></a></li>
                                <li><a href="mailto:{{ $seat->email }}"><i class="fa fa-envelope"></i></a></li>
                            </ul>
                        </div>
                    </li>
                    @endforeach
                  
                </ul>
                <a href="" class="view-full-info">View All Availble Seats</a>
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
                                <small>{{ $seat->plan_type->name}}</small>
                            </div>
                            <div class="seat-status">
                                <p>Expired in {{ \Carbon\Carbon::now()->diffInDays($seat->plan_end_date) }} Days</p>
                                <small><a href="{{ route('learner.payment', [$seat->id]) }}">Renew Plan</a></small>
                            </div>

                            <ul class="d-flex inner">
                                <li><a href="https://wa.me/{{ $seat->mobile }}"><i class="fab fa-whatsapp"></i></a></li>
                                <li><a href="mailto:{{ $seat->email }}"><i class="fa fa-envelope"></i></a></li>
                            </ul>
                        </div>
                    </li>
                    @endforeach
                </ul>

                <a href="" class="view-full-info">View All Availble Seats</a>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <h4 class="my-4">Monthly Analytics (Rvenue, Expanse & Profit)</h4>
    <div class="row">
        <div class="col-lg-8">
            <canvas id="barChart"></canvas>
        </div>
    </div>
</div>
<!-- End -->

<div class="row g-4 d-none">
    <div class="col-lg-6">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="dashibox">
                    <span>Total Seats</span>
                    <h4>{{$total_seats}}</h4>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="dashibox">
                    <span>Booked Seats</span>
                    <h4>{{$booked_seats}}</h4>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="dashibox">
                    <span>Available Seats</span>
                    <h4>{{$availble_seats}}</h4>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="dashibox">
                    <span>Revenue</span>
                   
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="dashibox h-my ">
            <h4>Available Seats</h4>
            <ul class="contents">
                @foreach($available_seats as $key => $value)
                <li>
                    <div class="d-flex">
                        <img src="{{ asset('public/img/available.png') }}" alt="">
                        <div class="booking">
                            <p>Seat No. {{$value}}</p>
                            <a href="">Book Now</a>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="dashibox h-my">
            <h4>Upcoming Expirations</h4>
            <span>Show Expired in 5 Days Seats & Extended Seats </span>
            <ul>
                @foreach($renewSeats as $key => $value)
                <li>
                    <div class="d-flex">
                        <img src="{{ asset('public/img/available.png') }}" alt="">
                        <div class="booking">
                            <p>Seat No. {{$value->seat_no}}</p>
                            <a href="">Renew Plan</a>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="dashibox h-my">
            <h4>Recent Transactions</h4>
            <ul>
                @foreach($renewSeats as $key => $value)
                <li>
                    <div class="d-flex">
                        <img src="{{ asset('public/img/available.png') }}" alt="">
                        <div class="booking">
                            <p>Seat No. {{$value->seat_no}}</p>
                            <a href="">Renew Plan</a>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="dashibox h-my">
            <h4>Learner Registrations</h4>
            <ul>
                @foreach($renewSeats as $key => $value)
                <li>
                    <div class="d-flex">
                        <img src="{{ asset('public/img/available.png') }}" alt="">
                        <div class="booking">
                            <p>Seat No. {{$value->seat_no}}</p>
                            <a href="">Renew Plan</a>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<div class="row g-4 mt-2 d-none">
    <div class="col-lg-3">
        <div class="dashibox">
            <span>Today Bookings</span>
            <h4>{{$total_seats}}</h4>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="dashibox">
            <span>Extended Seats</span>
            <h4>{{$total_seats}}</h4>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="dashibox">
            <span>Paid Online</span>
            <h4>{{$total_seats}}</h4>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="dashibox">
            <span>Paid Offline</span>
            <h4>{{$total_seats}}</h4>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="dashibox">
            <span>Overdue Seats</span>
            <h4>{{$total_seats}}</h4>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="dashibox">
            <span>Expired in 5 Days</span>
            <h4>{{$total_seats}}</h4>
        </div>
    </div>

</div>
<div class="row d-none">
    <div class="col-lg-12">
        <h4 class="py-4">Plan Statistics (Show Plan wise) Graph</h4>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

<script>
    (function($) {
        $(window).on("load", function() {
            $(".contents").mCustomScrollbar();
        });
    })(jQuery);

    // (function($) {
    //     $(window).on("load", function() {
    //         $(".scroll-x").mCustomScrollbar({
    //             axis: "x", // Enable horizontal scrolling
    //             theme: "dark", // Optional theme for the scrollbar
    //             advanced: {
    //                 autoExpandHorizontalScroll: true // Automatically expand scrollbar for large content
    //             },
    //             scrollInertia: 200 // Optional: Set scroll speed
    //         });
    //     });
    // })(jQuery);
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
@endsection