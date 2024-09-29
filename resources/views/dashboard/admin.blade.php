@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
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
        <div class="dashibox">
            <h4>Available Seats</h4>
            <ul>
                <li>
                    <div class="d-flex">
                        <img src="{{ asset('public/img/available.png') }}" alt="">
                        <div class="booking">
                            <p>Seat No. 2</p>
                            <a href="">Book Now</a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="d-flex">
                        <img src="{{ asset('public/img/available.png') }}" alt="">
                        <div class="booking">
                            <p>Seat No. 2</p>
                            <a href="">Book Now</a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="d-flex">
                        <img src="{{ asset('public/img/available.png') }}" alt="">
                        <div class="booking">
                            <p>Seat No. 2</p>
                            <a href="">Book Now</a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="d-flex">
                        <img src="{{ asset('public/img/available.png') }}" alt="">
                        <div class="booking">
                            <p>Seat No. 2</p>
                            <a href="">Book Now</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="dashibox">
            <h4>Expired in 5 Days</h4>
            <span>Show Expired in 5 Days Seats & Extended Seats </span>
            <ul>
                <li>
                    <div class="d-flex">
                        <img src="{{ asset('public/img/available.png') }}" alt="">
                        <div class="booking">
                            <p>Seat No. 2</p>
                            <a href="">Renew Plan</a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="d-flex">
                        <img src="{{ asset('public/img/available.png') }}" alt="">
                        <div class="booking">
                            <p>Seat No. 2</p>
                            <a href="">Renew Plan</a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="d-flex">
                        <img src="{{ asset('public/img/available.png') }}" alt="">
                        <div class="booking">
                            <p>Seat No. 2</p>
                            <a href="">Renew Plan</a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="d-flex">
                        <img src="{{ asset('public/img/available.png') }}" alt="">
                        <div class="booking">
                            <p>Seat No. 2</p>
                            <a href="">Renew Plan</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

</div>

@endsection