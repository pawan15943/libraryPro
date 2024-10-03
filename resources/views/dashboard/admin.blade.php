@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

<div class="row">
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
            <h4>Expired in 5 Days</h4>
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