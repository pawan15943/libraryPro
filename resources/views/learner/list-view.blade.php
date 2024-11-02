@extends('layouts.admin')
@section('content')

<!-- Content Header (Page header) -->

<div class="row mb-4 mt-4">
    <div class="col-lg-12 mb-4">
        <div class="records">
            <p class="mb-2">Total Seats : {{$total_seats}} | Available : {{$availble_seats}} | Booked : {{$booked_seats}}</p>

            <span class="text-success">Available to Book ({{$availble_seats}})</span> <span class="text-success">Active ({{$active_seat_count}})</span> <span class="text-danger">Expired ({{$expired_seat}})</span> <span class="text-danger">Full day ({{$fullday_count}})</span> <span class="text-danger">First Half ({{$firstHalfCount}})</span> <span class="text-danger">Second Half ({{$secondHalfCount}})</span> <span class="text-danger">Hourly 1 ({{$hourly1Count}})</span> <span class="text-danger">Hourly 2 ({{$hourly2Count}})</span> <span class="text-danger">Hourly 3 ({{$hourly3Count}})</span> <span class="text-danger">Hourly 4 ({{$hourly4Count}})</span>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="table-responsive ">
            <table class="table text-center datatable border-bottom">
                <thead>
                    <tr>
                        <th>Seat No.</th>
                        <th>Learner Info</th>
                        <th>Contact Info</th>
                        <th>Active Plan</th>
                        <th>Expired On</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>


            </table>
           

        </div>
    </div>
</div>



@include('learner.script')
@endsection