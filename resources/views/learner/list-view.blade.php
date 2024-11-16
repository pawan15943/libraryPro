@extends('layouts.admin')
@section('content')

<!-- Content Header (Page header) -->
@php
    use Carbon\Carbon;
@endphp
<div class="row mb-4">
    <div class="col-lg-12">
        <b class="d-block pb-3">Active Bookings for November: [Count]</b>
        <div class="table-responsive">
            <table class="table text-center datatable border-bottom" id="datatable">
                <thead>
                    <tr>
                        <th>Seat No.</th>
                        <th>Learner Info</th>
                        <th>Contact Info</th>
                        <th>Active Plan</th>
                        <th>Expired On</th>
                        <th>Status</th>
                       
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($result as $data)
                    <tr>
                        <td>{{ $data->learner->seat_no }}</td> <!-- Seat No -->
                        
                        <td><span class="uppercase truncate" data-bs-toggle="tooltip"
                            data-bs-title="{{$data->learner->name}}" data-bs-placement="bottom">{{$data->learner->name}}</span>
                        <br> <small>{{$data->dob}}</small>
                        </td>
                       
                        <td><span class="truncate" data-bs-toggle="tooltip"
                            data-bs-title="{{ $data->learner->email }}" data-bs-placement="bottom"><i
                                class="fa-solid fa-times text-danger"></i></i>
                            {{ $data->learner->email }}</span> <br>
                            <small> +91-{{$data->learner->mobile}}</small>
                        </td>
                        <td>
                            {{ $data->plan_start_date ?? 'N/A' }}<br>
                                <small>{{ $data->plan->name ?? 'N/A' }}</small>
                        </td>
                        <td>
                            {{ $data->plan_end_date ?? 'N/A' }}<br>
                                <small>{{ $data->planType->name ?? 'N/A' }}</small> 
                        </td>
                        <td>
                            @php
                                    
                                    $today = Carbon::today();
                                    if($data->plan_end_date){
                                        $endDate =$data->plan_end_date;
                                    }elseif($data->learner->plan_end_date){
                                        $endDate =$data->learner->plan_end_date;
                                    }
                                    $endDate = Carbon::parse($endDate);
                                    $diffInDays = $today->diffInDays($endDate, false);
                                    $inextendDate = $endDate->copy()->addDays($extendDay); 
                                    $diffExtendDay= $today->diffInDays($inextendDate, false);
                                @endphp
                                
                                @if ($data->status == 1 || $data->learner->status == 1)
                                    Active
                                @else
                                    Inactive
                                @endif
                                <br>
                                @if ($diffInDays > 0)
                                    <small class="text-success">Plan Expires in {{ $diffInDays }} days</small>
                                @elseif ($diffInDays <= 0 && $diffExtendDay > 0)
                                    <small class="text-danger fs-10 d-block">Extend Days are Active Now & Remaining Days are {{ abs($diffExtendDay) }} days.</small>
                                @elseif ($diffInDays < 0 && $diffExtendDay == 0)
                                    <small class="text-warning fs-10 d-block">Plan Expires today</small>
                                @else
                                    <small class="text-danger fs-10 d-block">Plan Expired {{ abs($diffInDays) }} days ago</small>
                                @endif
                        </td>
                        
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
          
        </div>
    </div>
</div>



@include('learner.script')
@endsection