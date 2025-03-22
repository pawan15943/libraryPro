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
        <p class="info-message">
            <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
            <b>Important :</b> The Seat History page displays a comprehensive list of all library seats, along with seat-specific booking details in a single view. If you need information about library seats, this section provides helpful details to guide you.
        </p>
        <div class="table-responsive">
            <table class="table text-center datatable" >
                <thead>
                    <tr>
                        <th style="width: 10%">Seat No.</th>
                        <th style="width: 20%">Seat Owner Name</th>
                        <th style="width: 20%">Contact Info</th>
                        <th style="width: 10%">Plan Info</th>
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
                    $usersForSeat = App\Models\LearnerDetail::where('seat_id',$seat->id)->where('status',1)->get();
                    // If no learners with status 1 are found, check for learners with status 0
                    if ($usersForSeat->isEmpty()) {
                    $usersForSeat = App\Models\LearnerDetail::where('seat_id',$seat->id)->where('status',0)->get();

                    }
                    @endphp
                    @if($usersForSeat->count() > 0)

                    <tr>
                        <td rowspan="{{ $usersForSeat->count() }}">{{ $seat->seat_no }}</td>
                        @foreach($usersForSeat as $user)
                        @php
                        $learner=App\Models\Learner::where('id',$user->learner_id)->first();
                        $plan = App\Models\Plan::where('id', $user->plan_id)->first();
                        $plantype = App\Models\PlanType::where('id', $user->plan_type_id)->first();
                        $endDate = Carbon::parse($user->plan_end_date);
                        $diffInDays = $today->diffInDays($endDate, false);
                        $inextendDate = $endDate->copy()->addDays($extendDay); // Preserving the original $endDate
                        $diffExtendDay= $today->diffInDays($inextendDate, false);
                        @endphp
                        @if (!$loop->first)
                    <tr>
                        @endif
                        <td><span class="uppercase">{{ $learner->name }}</span><br><small>{{ $learner->dob }}</small></td>
                        <td><span class="truncate" data-bs-toggle="tooltip"
                                data-bs-title="{{decryptData($learner->email) }}" data-bs-placement="bottom"><i
                                    class="fa-solid fa-times text-danger"></i></i>
                                    {{decryptData($learner->email) }}</span> <br>
                            <small> +91-{{decryptData($learner->mobile)}}</small>
                        </td>
                        <td>{{ $plantype->name }}<br><small>{{ $plan->name }}</small></td>

                        <td>{{ $user->join_date }}
                            @if(isset($user->is_paid) && $user->is_paid==1)
                            <small class="fs-10 d-block ">Paid</small>
                            @else
                            <small class="fs-10 d-block ">Unpaid</small>
                            @endif
                        </td>
                        <td>{{ $user->plan_start_date }}</td>
                        <td>{{ $user->plan_end_date }}<br>
                         
                            @if ($diffInDays > 0)
                                <small class="text-success">Plan Expires in {{ $diffInDays }} days</small>
                            @elseif ($diffInDays <= 0 && $diffExtendDay>0)
                                <small class="text-danger fs-10 d-block">Extension active! {{ abs($diffExtendDay) }} days left.</small>
                            @elseif ($diffInDays < 0 && $diffExtendDay==0)
                                <small class="text-warning fs-10 d-block">Plan Expires today</small>
                            @else
                                <small class="text-danger fs-10 d-block">Plan Expired {{ abs($diffInDays) }} days ago</small>
                            @endif
                        </td>
                        @if ($loop->first)
                        <td rowspan="{{ $usersForSeat->count()  }}">

                            <ul class="actionalbls">
                                <li>
                                    <a href="{{ url('seats/history', $seat->id) }}" title="View Seat Previous Booking " class="disabled"><i class="fa-solid fa-clock-rotate-left"></i></a>
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