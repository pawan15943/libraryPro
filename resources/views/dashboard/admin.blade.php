@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
<!-- New Design Dahsbard Library -->
<div class="dashboard">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <h1>Welcome to <span>LibraryPro</span>
                Where <span>Great Minds Gather!</span></h1>
        </div>
        <div class="col-lg-6">
            <div class="active-plan-box">
                <div class="top-content">
                    <h4>BASIC PLAN <span><a href="">Upgrade Plan</a></span></h4>
                    <label for="">Active</label>
                </div>
                <div class="d-flex">
                    <ul class="plann-info">
                        <li>Total Seat : <a href="">50</a> </li>
                        <li>Plan Features : <a href="">50</a> </li>
                        <li>Plan Price : <a href="">50</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Library Main Counts -->
    <div class="row my-4">
        <div class="col-lg-4">
            <div class="main-count cardbg-1">
                <span>Total Seats</span>
                <h2>50</h2>
                <small>Added on 25-10-2024</small>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="main-count cardbg-2">
                <span>Booked Seats</span>
                <h2>50</h2>
                <small>Added on 25-10-2024</small>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="main-count cardbg-2">
                <span>Avaialble Seats</span>
                <h2>50</h2>
                <small>Added on 25-10-2024</small>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
    </div>

    <h4 class="my-4">Monthly Revenues</h4>
    <div class="row">
        <div class="col-lg-12">
            <ul class="revenue-box">
                <li>
                    <div class="d-flex">
                        <h4>April Month Revenue</h4>
                        <span><i class="fa fa-eye"></i></span>
                    </div>
                    <div class="d-flex mt-10">
                        <div class="value">
                            <small>Total Revenue</small>
                            <h4>12020</h4>
                        </div>
                        <div class="value">
                            <small>Total Expanse</small>
                            <h4>12020</h4>
                        </div>
                        <div class="value">
                            <small>Net Profit</small>
                            <h4>12020</h4>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- Library Other Counts -->
    <h4 class="my-4">Library Other Highlights</h4>
    <div class="row g-4">
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Total Bookings</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Online Paid</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Online Paid</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Offline Paid</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Expired in 5 Days</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Expired Seats</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Extended Seats</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Swap Seats</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Upgrade Seats</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Reactive Seats</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>WhatsApp Sended</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Email Sended</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
    </div>

    <h4 class="my-4">Plan Wise Count</h4>
    <div class="row g-4">
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Full Day</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>First Half</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Second Half</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Hourly Slot 1</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Hourly Slot 2</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Hourly Slot 3</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Hourly Slot 4</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Total Booked Seats</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="booking-count bg-1">
                <h6>Available Seats</h6>
                <div class="d-flex">
                    <h4>80</h4>
                    <span><i class="fa fa-circle-check"></i></span>
                </div>
                <img src="{{url('public/img/seat.svg')}}" alt="library" class="img-fluid rounded">
            </div>
        </div>
    </div>


    <!-- Available Seats -->
    <div class="row">
        <div class="col-lg-4">
            <h4 class="my-4">Avaialble Seats</h4>
            <!-- Show 10 availble Seats -->
            <ul>
                <li>
                    <div class="d-flex">
                        <img src="" alt="">
                        <div class="seat-content">
                            <h6>Seat No. 10</h6>
                            <small>Available</small>
                        </div>
                        <a href="">Book</a>
                    </div>
                </li>
                <li>
                    <div class="d-flex">
                        <img src="" alt="">
                        <div class="seat-content">
                            <h6>Seat No. 10</h6>
                            <small>Available</small>
                        </div>
                        <a href="">Book</a>
                    </div>
                </li>
                <li>
                    <div class="d-flex">
                        <img src="" alt="">
                        <div class="seat-content">
                            <h6>Seat No. 10</h6>
                            <small>Available</small>
                        </div>
                        <a href="">Book</a>
                    </div>
                </li>
                <li>
                    <div class="d-flex">
                        <img src="" alt="">
                        <div class="seat-content">
                            <h6>Seat No. 10</h6>
                            <small>Available</small>
                        </div>
                        <a href="">Book</a>
                    </div>
                </li>
                <li>
                    <div class="d-flex">
                        <img src="" alt="">
                        <div class="seat-content">
                            <h6>Seat No. 10</h6>
                            <small>Available</small>
                        </div>
                        <a href="">Book</a>
                    </div>
                </li>
                <li>
                    <div class="d-flex">
                        <img src="" alt="">
                        <div class="seat-content">
                            <h6>Seat No. 10</h6>
                            <small>Available</small>
                        </div>
                        <a href="">Book</a>
                    </div>
                </li>
                <li>
                    <div class="d-flex">
                        <img src="" alt="">
                        <div class="seat-content">
                            <h6>Seat No. 10</h6>
                            <small>Available</small>
                        </div>
                        <a href="">Book</a>
                    </div>
                </li>
            </ul>
            <a href="">View All Availble Seats</a>
        </div>

        <div class="col-lg-4">
            <h4>Seats About to Expire</h4>
            <ul>
                <li>
                    <div class="d-flex">
                        <img src="" alt="">
                        <div class="seat-content">
                            <h6>Seat No. 10</h6>
                            <small>First Half</small>
                        </div>
                        <div class="seat-status">
                            <p>Expired in 2 Days</p>
                            <small><a href="">Renew Plan</a></small>
                        </div>

                        <ul>
                            <li><a href=""><i class="fa fa-whatsapp"></i></a></li>
                            <li><a href=""><i class="fa fa-envelope"></i></a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <div class="d-flex">
                        <img src="" alt="">
                        <div class="seat-content">
                            <h6>Seat No. 10</h6>
                            <small>First Half</small>
                        </div>
                        <div class="seat-status">
                            <p>Expired in 2 Days</p>
                            <small><a href="">Renew Plan</a></small>
                        </div>

                        <ul>
                            <li><a href=""><i class="fa fa-whatsapp"></i></a></li>
                            <li><a href=""><i class="fa fa-envelope"></i></a></li>
                        </ul>
                    </div>
                </li>
            </ul>
            <a href="">View All Availble Seats</a>
        </div>
        <div class="col-lg-4">
            <h4>Extended Seats </h4>
            <ul>
                <li>
                    <div class="d-flex">
                        <img src="" alt="">
                        <div class="seat-content">
                            <h6>Seat No. 10</h6>
                            <small>First Half</small>
                        </div>
                        <div class="seat-status">
                            <p>Expired in 2 Days</p>
                            <small><a href="">Renew Plan</a></small>
                        </div>

                        <ul>
                            <li><a href=""><i class="fa fa-whatsapp"></i></a></li>
                            <li><a href=""><i class="fa fa-envelope"></i></a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <div class="d-flex">
                        <img src="" alt="">
                        <div class="seat-content">
                            <h6>Seat No. 10</h6>
                            <small>First Half</small>
                        </div>
                        <div class="seat-status">
                            <p>Expired in 2 Days</p>
                            <small><a href="">Renew Plan</a></small>
                        </div>

                        <ul>
                            <li><a href=""><i class="fa fa-whatsapp"></i></a></li>
                            <li><a href=""><i class="fa fa-envelope"></i></a></li>
                        </ul>
                    </div>
                </li>
            </ul>
            <a href="">View All Availble Seats</a>
        </div>
    </div>

    <!-- Charts -->
    <h4>Monthly Analytics (Rvenue, Expanse & Profit)</h4>
    <div class="row">
        <div class="col-lg-8">

        </div>
    </div>
</div>
<!-- End -->

<div class="row g-4">
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
                    <h4>{{$library_revenue}}</h4>
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
<div class="row g-4 mt-2">
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
<div class="row">
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
</script>
@endsection