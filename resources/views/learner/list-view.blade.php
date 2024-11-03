@extends('layouts.admin')
@section('content')

<!-- Content Header (Page header) -->
@php
    use Carbon\Carbon;
@endphp
<div class="row mb-4 mt-4">
    
    <div class="col-lg-12">
        <div class="table-responsive ">
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
                    @foreach ($seats as $data)
                        <tr>
                            <td>{{ $data['learner']->seat_no }}</td> <!-- Seat No -->
                            
                            <td><span class="uppercase truncate" data-bs-toggle="tooltip"
                                data-bs-title="{{$data['learner']->name}}" data-bs-placement="bottom">{{$data['learner']->name}}</span>
                            <br> <small>{{$data['learner']->dob}}</small>
                            </td>
                           
                            <td><span class="truncate" data-bs-toggle="tooltip"
                                data-bs-title="{{ $data['learner']->email }}" data-bs-placement="bottom"><i
                                    class="fa-solid fa-times text-danger"></i></i>
                                {{ $data['learner']->email }}</span> <br>
                                <small> +91-{{$data['learner']->mobile}}</small>
                            </td>
                            <td>
                                @if ($data['learner']->learnerDetails->isNotEmpty())
                                    @php
                                        $detail = $data['learner']->learnerDetails->first();
                                        $today = Carbon::today();
                                        $endDate = Carbon::parse($detail->plan_end_date);
                                        $diffInDays = $today->diffInDays($endDate, false);
                                        $inextendDate = $endDate->copy()->addDays($extendDay); 
                                        $diffExtendDay= $today->diffInDays($inextendDate, false);
                                    @endphp
                            
                                    {{ $detail->plan_start_date ?? 'N/A' }}<br>
                                    <small>{{ $detail->plan->name ?? 'N/A' }}</small>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if ($data['learner']->learnerDetails->isNotEmpty())
                                    @php
                                        $detail = $data['learner']->learnerDetails->first();
                                        $endDate = Carbon::parse($detail->plan_end_date);
                                    @endphp
                                    {{ $detail->plan_end_date ?? 'N/A' }}<br>
                                    <small>{{ $detail->planType->name ?? 'N/A' }}</small> 
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if ($data['learner']->learnerDetails->isNotEmpty())
                                    @php
                                        $detail = $data['learner']->learnerDetails->first();
                                        $today = Carbon::today();
                                        $endDate = Carbon::parse($detail->plan_end_date);
                                        $diffInDays = $today->diffInDays($endDate, false);
                                        $inextendDate = $endDate->copy()->addDays($extendDay); 
                                        $diffExtendDay= $today->diffInDays($inextendDate, false);
                                    @endphp
                            
                                    {{ $data['learner']->status == 1 ? 'Active' : 'Inactive' }}
                                    @if ($diffInDays > 0)
                                        <small class="text-success">Plan Expires in {{ $diffInDays }} days</small>
                                    @elseif ($diffInDays <= 0 && $diffExtendDay > 0)
                                        <small class="text-danger fs-10 d-block">Extend Days are Active Now & Remaining Days are {{ abs($diffExtendDay) }} days.</small>
                                    @elseif ($diffInDays < 0 && $diffExtendDay == 0)
                                        <small class="text-warning fs-10 d-block">Plan Expires today</small>
                                    @else
                                        <small class="text-danger fs-10 d-block">Plan Expired {{ abs($diffInDays) }} days ago</small>
                                    @endif
                                @else
                                    N/A
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