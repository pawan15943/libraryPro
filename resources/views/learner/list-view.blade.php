@extends('layouts.admin')
@section('content')

<!-- Content Header (Page header) -->
@php
    use Carbon\Carbon;
    use App\Helpers\HelperService; 
@endphp
<div class="row mb-4">
    <div class="col-lg-12">
        <b class="d-block pb-3">Active Bookings for November: [{{$result->count()}}]</b>
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
                    @if($data->operation_date)
                        @php
                            $learner=App\Models\Learner::where('id',$data->learner_id)->first();
                            $learner_detail=App\Models\LearnerDetail::where('id',$data->learner_detail_id)->with(['plan','planType'])->first();
                           
                            $operation = DB::table('learner_operations_log')->where('learner_id',$data->learner_id)->where('learner_detail_id',$data->learner_detail_id)->where('operation',$data->operation)->whereDate('created_at',$data->operation_date)->first();
                            $operationDetails = HelperService::getOperationDetails($operation);
                           
                        @endphp
                         <tr>
                            <td>{{ $learner->seat_no }}<br>{{$operationDetails['field']}}{{$operationDetails['old']}}-{{$operationDetails['new']}}</td> <!-- Seat No -->
                           
                            
                            <td><span class="uppercase truncate" data-bs-toggle="tooltip"
                                data-bs-title="{{$learner->name}}" data-bs-placement="bottom">{{$learner->name}}</span>
                            <br> <small>{{$learner->dob}}</small>
                            </td>
                           
                            <td><span class="truncate" data-bs-toggle="tooltip"
                                data-bs-title="{{ $learner->email }}" data-bs-placement="bottom"><i
                                    class="fa-solid fa-times text-danger"></i></i>
                                {{ $learner->email }}</span> <br>
                                <small> +91-{{$learner->mobile}}</small>
                            </td>
                            <td>
                                {{ $learner_detail->plan_start_date ?? 'N/A' }}<br>
                                    <small>{{ $learner_detail->plan->name ?? 'N/A' }}</small>
                            </td>
                            <td>
                                {{ $learner_detail->plan_end_date ?? 'N/A' }}<br>
                                    <small>{{ $learner_detail->planType->name ?? 'N/A' }}</small> 
                            </td>
                            <td>
                                @php
                                        
                                        $today = Carbon::today();
                                        if($learner_detail->plan_end_date){
                                            $endDate =$learner_detail->plan_end_date;
                                        }
                                        $endDate = Carbon::parse($endDate);
                                        $diffInDays = $today->diffInDays($endDate, false);
                                        $inextendDate = $endDate->copy()->addDays($extendDay); 
                                        $diffExtendDay= $today->diffInDays($inextendDate, false);
                                    @endphp
                                    
                                    @if ($learner_detail->status == 1)
                                        Active <small>{{$learner_detail->is_paid==1 ? 'Paid' : 'Unpaid'}}</small><br>{{$operationDetails['operation_type']}}
                                    @else
                                        Inactive <small>{{$learner_detail->is_paid==1 ? 'Paid' : 'Unpaid'}}</small><br>{{$operationDetails['operation_type']}}
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
                    @elseif($data->learner)
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
                                @elseif ($diffInDays <= 0 && $diffExtendDay > 0 && $data->status == 1)
                                    <small class="text-danger fs-10 d-block">Extend Days are Active Now & Remaining Days are {{ abs($diffExtendDay) }} days.</small>
                                @elseif ($diffInDays < 0 && $diffExtendDay == 0)
                                    <small class="text-warning fs-10 d-block">Plan Expires today</small>
                                @else
                                    <small class="text-danger fs-10 d-block">Plan Expired {{ abs($diffInDays) }} days ago</small>
                                @endif
                        </td>
                        
                        
                    </tr>
                    @else
                    <tr>
                        <td>{{ $data->seat_no }}</td> <!-- Seat No -->
                        
                        <td><span class="uppercase truncate" data-bs-toggle="tooltip"
                            data-bs-title="{{$data->name}}" data-bs-placement="bottom">{{$data->name}}</span>
                        <br> <small>{{$data->dob}}</small>
                        </td>
                       
                        <td><span class="truncate" data-bs-toggle="tooltip"
                            data-bs-title="{{ $data->email }}" data-bs-placement="bottom"><i
                                class="fa-solid fa-times text-danger"></i></i>
                            {{ $data->email }}</span> <br>
                            <small> +91-{{$data->mobile}}</small>
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
                                
                                @if ($data->status == 1)
                                    Active
                                @else
                                    Inactive
                                @endif
                                <br>
                                @if ($diffInDays > 0)
                                    <small class="text-success">Plan Expires in {{ $diffInDays }} days</small>
                                @elseif ($diffInDays <= 0 && $diffExtendDay > 0 && $data->status == 1)
                                    <small class="text-danger fs-10 d-block">Extend Days are Active Now & Remaining Days are {{ abs($diffExtendDay) }} days.</small>
                                @elseif ($diffInDays < 0 && $diffExtendDay == 0)
                                    <small class="text-warning fs-10 d-block">Plan Expires today</small>
                                @else
                                    <small class="text-danger fs-10 d-block">Plan Expired {{ abs($diffInDays) }} days ago</small>
                                @endif
                        </td>
                        
                        
                    </tr>
                    @endif
                    
                    @endforeach
                </tbody>
            </table>
          
        </div>
    </div>
</div>



@include('learner.script')
@endsection