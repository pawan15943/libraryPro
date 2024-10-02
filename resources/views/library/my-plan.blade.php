@extends('layouts.admin')

@section('content')


<!-- Breadcrumb -->

<div class="row justify-content-center mb-4 mt-3">
    <div class="col-lg-9">
        <h4 class="text-center mb-4"> My Plan</h4>
        <div class="payment-detaile">
            @foreach($month as $key => $value)
            <div class="paymentinfo">
                <div class="plan-info">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <span>Plan Name</span>
                            <h4>{{$plan ? $plan->name : $value->plan}}</h4>
                        </div>
                        <div class="col-lg-6">
                            <span>Plan Type</span>
                            @if($value->month==1)
                            <h4>{{ 'MONTHLY'}}</h4>
                            @else
                            <h4>{{  $value->month}} Months</h4>
                            @endif
                          
                        </div>
                        <div class="col-lg-6">
                            <span>Plan Price</span>
                            <h4>{{  $value->amount}}</h4>
                        </div>
                        <div class="col-lg-6">
                            <span>Start Date</span>
                            <h4>{{  $value->start_date}}</h4>
                        </div>
                        <small class="status"> <b>Status : </b>
                            @if($value->status ==1)
                            <span class=" text-success d-inline">Active</span> 
                            @else
                            <span class=" text-danger d-inline">Inactive</span> 
                            @endif 
                           
                        </small>
                    </div>
                   
                </div>
               
            </div>
            <div>
                <ul class="plan-features">
                    @if($data->subscription->permissions->isNotEmpty())
                    @foreach($data->subscription->permissions as $permission)
                        <li>{{ $permission->name }}</li> 
                        @endforeach
                    @else
                        <li>No permissions available</li>
                    @endif
                </ul>
                </div>
            @endforeach
         

        </div>
       
    </div>
</div>



@endsection