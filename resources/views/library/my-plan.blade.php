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
           
            <div class="paymentinfo basic <?php if ($plan->name == 'Basic')?>">
                <div class="plan-info">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <span>Subscription Name</span>
                            <h4>{{$plan ? $plan->name : $month->plan}}</h4>
                        </div>
                        <div class="col-lg-6">
                            <span>Subscription Type</span>
                            @if($month->month==1)
                            <h4>{{ 'MONTHLY'}}</h4>
                            @else
                            <h4>{{ $month->month}} Months</h4>
                            @endif

                        </div>
                        <div class="col-lg-6">
                            <span>Subscription Price</span>
                            <h4>{{ $month->amount }}</h4>
                        </div>
                        <div class="col-lg-6">
                            <span>Subscription Start Date</span>
                            <h4>{{ $month->start_date}}</h4>
                        </div>
                        @if($month->end_date)  
                        <div class="col-lg-6">
                            <span>Subscription End Date</span>
                            <h4>{{ $month->end_date}}</h4>
                        </div>
                        @endif
                        <div class="col-lg-6">
                            <span> Status :</span>
                            @if($month->status ==1)
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
           
        </div>
    </div>
</div>



@endsection