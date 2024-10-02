@extends('layouts.admin')

@section('content')


<!-- Breadcrumb -->
@if($iscomp==false)
<div class="row">
    <div class="col-lg-12">
        <div class="steps">
            <ul>
                <li >
                    <a href="{{ ($checkSub) ? '#' : route('subscriptions.choosePlan')  }}">Pick Your Perfect Plan</a>
                </li>
                <li class="active">
                    <a href="{{ ($ispaid) ? '#'  : route('subscriptions.payment') }}">Make Payment</a>
                </li>
                <li >
                    <a href="{{ ($ispaid ) ? route('profile') : '#' }}">Update Profile</a>
                </li>
                <li >
                    <a href="{{ ($checkSub && $ispaid && $isProfile) ? route('library.master') : '#' }}">Configure Library</a>
                </li>
            </ul>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <h2 class="text-center">Secure your plan in just one step!</h2>
    </div>
</div>
@endif
<div class="row justify-content-center mb-4 mt-3">
    <div class="col-lg-9">
        <h4 class="text-center mb-4"> Payment Overview</h4>
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
            <div class="payment-information">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <span>Payment Method</span>
                        <h4>{{  $value->payment_mode}}</h4>
                    </div>
                    <div class="col-lg-6">
                        <span>Amount Paid</span>
                        <h4>{{  $value->amount}}</h4>
                    </div>
                    <div class="col-lg-6">
                        <span>Payment Status</span>
                        @if($value->is_paid ==1)
                        <span class=" text-success d-inline">Success</span> 
                        @else
                        <span class=" text-danger d-inline">Pending</span> 
                        @endif 
                    </div>
                    <div class="col-lg-6">
                        <span>Transaction Id</span>
                        <h4>{{  $value->transaction_id}}</h4>
                    </div>
                    <div class="col-lg-6">
                        <span>Payment Date</span>
                        <h4>{{  $value->transaction_date}}</h4>
                    </div>
                   
                </div>
            </div>
            @endforeach
           


        </div>
        <div class="row justify-content-center mt-3">
            <div class="col-lg-3">
                <form action="{{route('library.payment.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="transaction_id" value="{{$transactionId}}">
                    <button type="submit" class="btn btn-primary btn-block button"> Make a Payment </button>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection