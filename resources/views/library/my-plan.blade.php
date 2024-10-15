@extends('layouts.admin')

@section('content')


<!-- Breadcrumb -->
@if(isset($librarydiffInDays) && $librarydiffInDays <= 5)
<div class="row justify-content-center mb-4 mt-4">
    <div class="col-lg-3">
        <a href="{{route('subscriptions.choosePlan')}}" type="button" class="btn btn-primary button main">Renew Plan Now</a>
    </div>
</div>
@endif
<div class="row justify-content-center mb-4">
    <div class="col-lg-6">
        <div class="payment-detaile">
            @foreach($month as $key => $value)
            <div class="paymentinfo">
                <div class="plan-info">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <span>Subscription Name</span>
                            <h4>{{$plan ? $plan->name : $value->plan}}</h4>
                        </div>
                        <div class="col-lg-6">
                            <span>Subscription Type</span>
                            @if($value->month==1)
                            <h4>{{ 'MONTHLY'}}</h4>
                            @else
                            <h4>{{ $value->month}} Months</h4>
                            @endif

                        </div>
                        <div class="col-lg-6">
                            <span>Subscription Price</span>
                            <h4>{{ $value->amount }}</h4>
                        </div>
                        <div class="col-lg-6">
                            <span>Subscription Start Date</span>
                            <h4>{{ $value->start_date}}</h4>
                        </div>
                        @if($value->end_date)  
                        <div class="col-lg-6">
                            <span>Subscription End Date</span>
                            <h4>{{ $value->end_date}}</h4>
                        </div>
                        @endif
                        <div class="col-lg-6">
                            <span> Status :</span>
                            @if($value->status ==1)
                            <h4 class="text-success">Active</h4>
                            @else
                            <h4 class="text-danger">Inactive</h4>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <span>Next Due Date</span>
                            <h4></h4>
                        </div>
                    </div>

                </div>

            </div>
            <div>
                <ul class="plan-features">
                    @if($data->subscription->permissions->isNotEmpty())
                    @foreach($data->subscription->permissions as $permission)
                    <li><i class="fa-solid fa-check text-success me-2"></i> {{ $permission->name }}</li>
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