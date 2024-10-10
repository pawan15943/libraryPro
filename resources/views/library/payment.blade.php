@extends('layouts.admin')

@section('content')


<!-- Breadcrumb -->
@if($iscomp==false)
<div class="row">
    <div class="col-lg-12">
        <div class="steps">
            <ul>
                <li>
                    <a href="{{ ($checkSub) ? '#' : route('subscriptions.choosePlan')  }}">Pick Your Perfect Plan</a>
                </li>
                <li class="active">
                    <a href="{{ ($ispaid) ? '#'  : route('subscriptions.payment') }}">Make Payment</a>
                </li>
                <li>
                    <a href="{{ ($ispaid ) ? route('profile') : '#' }}">Update Profile</a>
                </li>
                <li>
                    <a href="{{ ($checkSub && $ispaid && $isProfile) ? route('library.master') : '#' }}">Configure Library</a>
                </li>
            </ul>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <h2 class="text-center mb-4">Secure your plan in just one step!</h2>
    </div>
</div>
@endif
<div class="row justify-content-center mb-4">
    <div class="col-lg-7">
        <div class="payment-detaile ">

            @foreach($month as $key => $value)
            <!-- .basic | .standard | .premium  use these classes conditionllly-->
            <div class="paymentinfo basic">
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
                            <h4>{{ $value->amount}}</h4>
                        </div>
                        <div class="col-lg-6">
                            <span>Subscription Start Date</span>
                            <h4>{{ $value->start_date}}</h4>
                        </div>
                        <div class="col-lg-6">
                            <span>Subscription End Date</span>
                            <h4>{{ $value->start_date}}</h4>
                        </div>
                        <div class="col-lg-6">
                            <span>Subscription Duration</span>
                            <h4>{{ $value->start_date}}</h4>
                        </div>

                    </div>

                </div>
            </div>



            <div class="payment-information">
                <h4 class="mb-3">Plan Features</h4>
                <!-- <div class="row g-4">
                    <div class="col-lg-6">
                        <span>Payment Method</span>
                        <h4>{{ $value->payment_mode}}</h4>
                    </div>
                    <div class="col-lg-6">
                        <span>Amount Paid</span>
                        <h4>{{ $value->amount}}</h4>
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
                        <h4>{{ $value->transaction_id}}</h4>
                    </div>
                    <div class="col-lg-6">
                        <span>Payment Date</span>
                        <h4>{{ $value->transaction_date}}</h4>
                    </div>

                </div>
            </div> -->

            </div>
            @endforeach
        </div>

    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="col-lg-12">
                <h4 class="text-center mb-4">Order Summery</h4>
                <div class="row g-4">
                    <div class="col-lg-8">
                        Amount Paid
                    </div>
                    <div class="col-lg-4 text-end">
                        <i class="fa fa-inr"></i> 1000
                    </div>

                    <div class="col-lg-8">
                        12% GST
                    </div>
                    <div class="col-lg-4 text-end">
                        <i class="fa fa-inr"></i> 0
                    </div>

                    <div class="col-lg-8">
                        % Discount (<a href="">Offer & Discounts</a>)
                    </div>
                    <div class="col-lg-4 text-end">
                        <i class="fa fa-inr"></i> 0
                    </div>

                    <div class="col-lg-8">
                        <b>Total Amount to Pay</b>
                    </div>
                    <div class="col-lg-4 text-end">
                        <b><i class="fa fa-inr"></i> 1000</b>
                    </div>

                </div>

            </div>
        </div>
        <div class="dicount-cupon mt-4">
            Discount Cupon Code
        </div>
        <div class="card mt-4">
            <h4 class="mb-3 text-center">Transaction Summery</h4>
            <div class="row g-4">
                <div class="col-lg-6">
                    <span>Transaction Date</span>
                    <input type="date" class="form-control">
                </div>
                <div class="col-lg-6">
                    <span>Transaction Id</span>
                    <input type="number" class="form-control" value="genrate rsndome string">
                </div>
                <div class="col-lg-12">
                    <span>Payment Method</span>
                    <select name="" id="" class="form-select">
                        <option value="">Select Mode</option>
                        <option value="">Online</option>
                        <option value="">Offline</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-3">
            <div class="col-lg-12">
                <form action="{{route('library.payment.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="transaction_id" value="{{$transactionId}}">
                    <button type="submit" class="btn btn-primary btn-block button"> Make Payment </button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row mb-4">
    <h4 class="py-4">Transaction Detials</h4>
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table text-center data-table">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Subscription Name</th>
                        <th>Subscription Amt</th>
                        <th>Discount</th>
                        <th>Paid Amt</th>
                        <th>Transaction Id</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Basic Plan</td>
                        <td>1000</td>
                        <td>70%</td>
                        <td>300</td>
                        <td>45615848612315</td>
                        <td>
                            <ul class="actionalbls">
                                <li>

                                    <form action="{{ route('fee.generateReceipt') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" id="custId" name="id" value="{{ $value->id }}">
                                        <input type="hidden" name="type" value="library">

                                        <button type="submit">
                                            <i class="fa fa-print"></i>
                                        </button>
                                    </form>

                                </li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection