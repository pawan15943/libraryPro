@extends('layouts.admin')
@section('content')

@php

use App\Models\PlanType;
use Carbon\Carbon;
$today = Carbon::today();
@endphp

<!-- Content Header (Page header) -->
<!-- Main row -->
<div class="row mb-4">
    <!-- Main Info -->
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table text-center datatable">
                <thead>
                    <tr>
                        <th style="width: 10%">Seat No.</th>
                        <th style="width: 20%">Name</th>
                        <th style="width: 10%">Plan Type</th>
                        <th style="width: 10%">Join On</th>
                        <th style="width: 10%">Start On</th>
                        <th style="width: 10%">Ends On</th>
                        <th style="width: 15%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($seats as $seat)
                    @php
                    // First, check if there are any customers with status 1 for the given seat
                    $usersForSeat =  App\Models\LearnerDetail::where('seat_id',$seat->id)->where('status',1)->get();
                    // If no learners with status 1 are found, check for learners with status 0
                    if ($usersForSeat->isEmpty()) {
                        $usersForSeat =  App\Models\LearnerDetail::where('seat_id',$seat->id)->where('status',0)->get();
             
                    }
                    @endphp
                    @if($usersForSeat->count() > 0)
                 
                    <tr>
                        <td rowspan="{{ $usersForSeat->count() }}">{{ $seat->seat_no }}</td>
                        @foreach($usersForSeat as $user)
                        @php
                            $learner=App\Models\Learner::where('id',$user->learner_id)->first();
                            $plantype = App\Models\PlanType::where('id', $user->plan_type_id)->first();
                            $endDate = Carbon::parse($user->plan_end_date);
                            $diffInDays = $today->diffInDays($endDate, false);
                        @endphp
                        @if (!$loop->first)
                    <tr>
                        @endif
                        <td class="uppercaseṢ">{{ $learner->name }}</td>
                        <td>{{ $plantype->name }}</td>
                        <td>{{ $user->join_date }}
                            @if ($diffInDays > 0)
                            <small class="text-success fs-10 d-block ">Expires in {{ $diffInDays }} days</small>
                            @elseif ($diffInDays < 0)
                            <small class="text-danger fs-10 d-block ">Available for book</small>
                            @else
                            <small class="text-warning fs-10 d-block ">Expires today</small>
                            @endif
                        </td>
                        <td>{{ $user->plan_start_date }}</td>
                        <td>{{ $user->plan_end_date }}</td>
                        @if ($loop->first)
                        <td rowspan="{{ $usersForSeat->count() }}">
                            <ul class="actionalbls">
                                <li>
                                    <a href="{{ url('seats/history', $seat->id) }}" title="View Seat Previous Booking "><i class="fa-solid fa-clock-rotate-left"></i></a>
                                </li>
                            </ul>
                        </td>
                        @endif
                        @if (!$loop->first)
                    </tr>
                    @endif
                    @endforeach
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@include('learner.script')
<!-- /.row (main row) -->
@endsection