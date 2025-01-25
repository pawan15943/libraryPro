@extends('layouts.admin')

@section('title', 'Learner Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
<div class="row">
    <div class="col-lg-9">
        <div class="actions">
            @foreach($learners as $key => $learner)
            <div class="upper-box">
                <div class="d-flex">
                    <h4 class="mb-3">{{$library_name->library_name}}</h4>
                    <a href="javascript:void(0);" class="go-back"
                        onclick="window.history.back();">Go
                        Back <i class="fa-solid fa-backward pl-2"></i></a>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4">
                        <span>Plan Name</span>
                        <h5 class="uppercase">{{ $learner->plan_name }}</h5>
                    </div>
                    <div class="col-lg-4">
                        <span>Plan Type</span>
                        <h5>{{ $learner->plan_type_name }}</h5>
                    </div>
                    <div class="col-lg-4">
                        <span>Seat No. </span>
                        <h5>{{ $learner->seat_no }}</h5>
                    </div>
                    <div class="col-lg-4">
                        <span>Start Date</span>
                        <h5>{{ $learner->plan_start_date }}</h5>
                    </div>
                    <div class="col-lg-4">
                        <span>End Date</span>
                        <h5>{{ $learner->plan_end_date }}</h5>
                    </div>
                    <div class="col-lg-4">
                        <span>Status</span>
                        @if($learner->status==1)
                        <h5>Active</h5>
                        @else
                        <h5>Deactive</h5>
                        @endif
                       
                    </div>
                </div>
            </div>
            @endforeach
        </div>
       
    </div>
    <div class="col-lg-3">
        @foreach($learners as $key => $learner)
        @if($learner->status==1)
       
        <div class="seat--info">
          
            <span class="d-block ">Seat No : {{ Auth::user()->seat_no}}</span>
            <img src="{{ asset('public/img/available.png') }}" alt="Seat" class="seat py-3 ">
            <p>{{ $learner->plan_name}}</p>
            <button class="mb-3"> Booked for <b>{{$learner->plan_type_name}}</b></button>
           
        </div>  
             
        @else
            
        @endif 
        @endforeach
      
        {{-- @if($learner_request->isNotEmpty())
        <div class="request-logs mt-4">
            <h5>Learners Request</h5>
            <ul class="request_list">
                @foreach($learner_request as $key => $value)
                <li>
                    <div class="d-flex">
                        <div class="icon"></div>
                        <div class="detials">
                            <p class="m-0"><i class="fa-solid fa-arrow-turn-down"></i> Request Name
                                : {{$value->request_name}}</p>
                            <span class="description">Message Send by <b>[Seat Owner]</b> on
                                {{$value->request_date}}</span>
                            <span class="timestamp"><i class="fa-solid fa-calendar"></i> {{$value->created_at}}</span>
                            <small class="status"> <b>Status : </b>
                                @if($value->request_status==0)
                                <span class=" text-danger d-inline">Pending</span>
                                @else
                                <span class=" text-success d-inline">Resolved (By Admin)</span>
                                @endif

                            </small>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
        --}}

    </div>
</div>

@endsection