@extends('layouts.admin')

@section('content')


<!-- Breadcrumb -->

<div class="row justify-content-center mb-4 mt-3">
    <div class="col-lg-9">
        <h4 class="text-center mb-4">Overview Payment</h4>
        <div class="payment-detaile">
          
        @foreach($transaction as $key => $value)
        <div class="payment-information">
            <div class="row g-4">
                <div class="col-lg-6">
                    <span>Payment Method</span>
                    <h4>{{$value->payment_mode ? $value->payment_mode : 'NA'}}</h4>
                </div>
                <div class="col-lg-6">
                    <span>Amount Paid</span>
                    <h4>{{$value->paid_amount}}</h4>
                </div>
                <div class="col-lg-6">
                    <span>Payment Status</span>
                    @if($value->is_paid==1)
                    <h4>Success</h4>
                    @else
                    <h4>Unsuccess</h4>
                    @endif
                   
                </div>
                <div class="col-lg-6">
                    <span>Transaction Id</span>
                    <h4>{{$value->transaction_id ? $value->transaction_id : 'NA'}}</h4>
                </div>
                <div class="col-lg-6">
                    <span>Tran Date</span>
                    <h4>{{$value->transaction_date}}</h4>
                </div>
                <div class="col-lg-6">
                    <span>Print Receipt</span>
                    <h4><a href="">Download</a></h4>
                </div>
            </div>
        </div>
        @endforeach
            


        </div>
       
    </div>
</div>



@endsection