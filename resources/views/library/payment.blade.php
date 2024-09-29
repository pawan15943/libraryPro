@extends('layouts.admin')

@section('content')


<!-- Breadcrumb -->

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

<div class="row justify-content-center mb-4 mt-3">
    <div class="col-lg-9">
        <h4 class="text-center mb-4">Overview Payment</h4>
        <div class="payment-detaile">
            <div class="paymentinfo">
                <div class="plan-info">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <span>Plan Name</span>
                            <h4>Basic Plan</h4>
                        </div>
                        <div class="col-lg-6">
                            <span>Plan Type</span>
                            <h4>MONTHLY</h4>
                        </div>
                        <div class="col-lg-6">
                            <span>Plan Price</span>
                            <h4>99</h4>
                        </div>
                        <div class="col-lg-6">
                            <span>Payment Date</span>
                            <h4>20-04-2024</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="payment-information">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <span>Payment Method</span>
                        <h4>Phone Pay</h4>
                    </div>
                    <div class="col-lg-6">
                        <span>Amount Paid</span>
                        <h4>99</h4>
                    </div>
                    <div class="col-lg-6">
                        <span>Payment Status</span>
                        <h4>Success</h4>
                    </div>
                    <div class="col-lg-6">
                        <span>Transaction Id</span>
                        <h4>522154892212541522</h4>
                    </div>
                    <div class="col-lg-6">
                        <span>Receipt Id</span>
                        <h4>215214</h4>
                    </div>
                    <div class="col-lg-6">
                        <span>Print Receipt</span>
                        <h4><a href="">Download</a></h4>
                    </div>
                </div>
            </div>


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