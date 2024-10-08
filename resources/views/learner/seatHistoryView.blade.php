@extends('layouts.admin')
@section('content')
@php
use App\Models\Learner;
use App\Models\PlanType;
use Carbon\Carbon;
$today = Carbon::today();
@endphp


<div class="row">
    <div class="col-lg-12">
        <!-- <h4>Customer History for Seat {{ $seat->seat_no }}</h4> -->

        @if($learners->isEmpty())
        <p class="not-found">No customer history found for this seat.</p>
        @else
        <div class="table-responsive mt-2">
            <table class="table text-center data-table" id="datatable" style="display:table !important;">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Plan</th>
                        <th>Plan Type</th>
                        <th>Starts On</th>
                        <th>Ends On</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($learners as $key => $value)
                    @php
                    $today = Carbon::today();
                    $endDate = Carbon::parse($value->plan_end_date);
                    $diffInDays = $today->diffInDays($endDate, false);
                    @endphp
                    <tr>
                        <td><span class="uppercase truncate">{{$value->name}}</span></td>
                        <td> {{$value->mobile}}</td>
                        <td> {{$value->email }}</td>
                        <td> {{$value->plan_name}}</td>
                        <td> {{$value->plan_type_name}}</td>
                        <td> {{$value->plan_start_date}}</td>
                        <td> {{$value->plan_end_date}}<br>
                            @if ($diffInDays > 0)
                            <small class="text-success fs-10">Expires in {{ $diffInDays }} days</small>
                            @elseif ($diffInDays < 0)
                                <small class="text-danger fs-10">Expired {{ abs($diffInDays) }} days ago</small>
                                @else
                                <small class="text-warning fs-10">Expires today</small>
                                @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        </div>
        @endif
    </div>
</div>
           

@endsection