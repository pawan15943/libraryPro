@extends('layouts.admin')
@section('content')

@php
$current_route = Route::currentRouteName();
use Carbon\Carbon;
$today = Carbon::today();
$endDate = Carbon::parse($customer->plan_end_date);
$diffInDays = $today->diffInDays($endDate, false);
@endphp

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<!-- View Customer Information -->
<div class="row">
    <div class="col-lg-9">
        <div class="actions">
            <div class="upper-box">
                <div class="d-flex">
                    <h4 class="mb-3">Leraners Info</h4>
                    <a href="javascript:void(0);" class="go-back"
                    onclick="window.history.back();">Go
                    Back <i class="fa-solid fa-backward pl-2"></i></a>
                    </div>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <span>Seat Owner Name</span>
                        <h5 class="uppercase">{{ $customer->name }}</h5>
                    </div>
                    <div class="col-lg-6">
                        <span>Date Of Birth </span>
                        <h5>{{ $customer->dob }}</h5>
                    </div>
                    <div class="col-lg-6">
                        <span>Mobile Number</span>
                        <h5>+91-{{ $customer->mobile }}</h5>
                    </div>
                    <div class="col-lg-6">
                        <span>Email Id</span>
                        <h5>{{ $customer->email }}</h5>
                    </div>
                </div>
            </div>
            <div class="action-box">
                <h4>Seat Plan Info</h4>
                <div class="row g-4">
                    <div class="col-lg-4">
                        <span>Plan</span>
                        <h5>{{ $customer->plan_name }}</h5>
                    </div>
                    <div class="col-lg-4">
                        <span>Plan Type</span>
                        <h5>{{ $customer->plan_type_name }}</h5>
                    </div>
                    <div class="col-lg-4">
                        <span>Plan Price</span>
                        <h5>{{ $customer->plan_price_id }}</h5>
                    </div>
                    <div class="col-lg-4">
                        <span>Seat Booked On</span>
                        <h5>{{ $customer->join_date }}</h5>
                    </div>
                    <div class="col-lg-4">
                        <span>Plan Starts On</span>
                        <h5>{{ $customer->plan_start_date }}</h5>
                    </div>
                    <div class="col-lg-4">
                        <span>Plan Ends On</span>
                        
                        <h5>{{ $customer->plan_end_date }}</h5>
                    </div>
                    <div class="col-lg-4">
                        <span>Seat Timings</span>
                        <h5>{{$customer->hours}} Hours ({{ $customer->start_time }} to {{ $customer->end_time }})</h5>
                    </div>
                    <div class="col-lg-4">
                        <span>Plan Expired In</span>
                        <h5><span class="text-success">Plan Expires in {{$diffInDays}} Days</span></h5>
                    </div>
                    <div class="col-lg-4">
                        <span>Current Plan Status</span>
                        <h5>
                            @if($customer->status==1)
                            <span class="text-success">Active</span>
                            @else
                            <span class="text-danger">InActive</span>
                            @endif
                        </h5>
                    </div>
                    
                   
                </div>
                <h4 class="mt-4"> Seat Other Info :</h4>
                <div class="row g-4">
                   
                    <div class="col-lg-4">
                        <span>Id Proof</span>
                        <h5>
                            @if($customer->id_proof_name==1)
                            Aadhar
                            @elseif($customer->id_proof_name==2)
                            Driving License
                            @else
                            Other
                            @endif
                            @if($customer->id_proof_file)
                            <img src="{{ asset($customer->id_proof_file) }}" width="150" height="150">
                            @else
                            <img src="">

                            @endif
                        </h5>
                    </div>
                    <div class="col-lg-4">
                        <span>Id Proof Document</span>
                        <h5>
                            NA
                        </h5>
                    </div>
                    <div class="col-lg-4">
                        <span>Seat Created At</span>
                        <h5>{{ $customer->created_at }}</h5>
                    </div>
                    <div class="col-lg-4">
                        <span>Seat Modified At</span>
                        <h5>{{ $customer->updated_at }}</h5>
                    </div>
                    <div class="col-lg-4">
                        <span>Seat Deleted At</span>
                        <h5> {{ $customer->deleted_at ? $customer->deleted_at : 'NA'}}</h5>
                    </div>
                </div>
                <h4 class="mt-4"> Seat Payment Info :</h4>
                <div class="row g-4">
                    <div class="col-lg-4">
                        <span>Payment Mode</span>
                        @if($customer->payment_mode == 1)
                        <h5>{{ 'Online' }}</h5>
                        @elseif($customer->payment_mode == 2)
                        <h5>{{ 'Offline' }}</h5>
                        @else
                        <h5>{{ 'Pay Later' }}</h5>
                        
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <span>Payment Status</span>
                        <h5>
                            @if(isset($transaction->is_paid) && $transaction->is_paid==1)
                            <span class="text-success">Paid</span>
                            @else
                            <span class="text-danger">Pending</span>
                            @endif
                             
                           
                        </h5>
                    </div>
                    <div class="col-lg-4">
                        <span>Transaction Id</span>
                        @if(isset($transaction->transaction_id) && $transaction->transaction_id)
                        <h5>{{$transaction->transaction_id}}</h5>
                        @else
                        <h5>NA</h5>
                        @endif
                        
                    </div>
                    <div class="col-lg-4">
                        <span>Payment Date</span>
                        @if(isset($transaction->paid_date) && $transaction->paid_date)
                        <h5>{{$transaction->paid_date}}</h5>
                        @else
                        <h5>NA</h5>
                        @endif
                    </div>
                </div>
                <h4 class="mt-4"> Plan Re-New History :</h4>
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th>Plan </th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Amount</th>
                                        <th>Payment Mode</th>
                                        <th>Paid On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($renew_detail as $key => $value)
                                    @php
                                        $transactionRenew=App\Models\LearnerTransaction::where('learner_deatail_id',$value->id)->where('is_paid',1)->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            {{$value->plan->name}} <br> 
                                            <small class="text-success">{{$value->planType->name}}</small>
                                        </td>
                                        <td>{{$value->plan_start_date}}</td>
                                        <td>{{$value->plan_end_date}}</td>
                                        <td>{{$transactionRenew->total_amount ?? 'NA'}}</td>
                                       
                                            @if($value->payment_mode == 1)
                                            <td>{{ 'Online' }}</td>
                                            @elseif($value->payment_mode == 2)
                                            <td>{{ 'Offline' }}</td>
                                            @else
                                            <td>{{ 'Pay Later' }}</td>
                                            
                                            @endif
                                        <td>{{$transactionRenew->paid_date ?? 'NA'}}</td>
                                       
                                        <td>
                                            <ul class="actionalbls" style="width: 90px;">
                                                <li><a href="javascript:;" data-toggle="modal"
                                                        data-target="#bookingDetailsModal"
                                                        title="View Transaction Details"><i
                                                            class="fa-solid fa-eye"></i></a></li>
                                                            
                                            @can('has-permission', 'Receipt Generation')
                                                <li>
                        
                                                <form action="{{ route('fee.generateReceipt') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden"  name="id" value="{{ $transactionRenew->id ?? 'NA'}}">
                                                    <input type="hidden"  name="type" value="learner">
                
                                                    <button type="submit">
                                                        <i class="fa fa-print"></i> 
                                                    </button>
                                                </form>
                                                
                                                </li>
                                            @endcan  
                                            </ul>
                                        </td>
                                    </tr>
                                    @endforeach
                                    

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
               
                <h4 class="mt-4"> Seat Previous Booking History</h4>
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th>Owner Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Plan</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($seat_history as $key => $value)
                                    <tr>
                                        <td>{{$value->name}}</td>
                                        <td>+91-{{$value->library_mobile}}</td>
                                        <td>{{$value->email}}</td>
                                        <td>{{ $value->plan->name ?? 'NA' }} <br>

                                            <small class="text-success">
                                                {{$value->planType->name}}</small></td>
                                        <td>{{$value->plan_start_date}}</td>
                                        <td>{{$value->plan_end_date}}</td>
                                        <td>
                                            <ul class="actionalbls" style="width: 90px;">
                                                <li><a href="javascript:;" data-toggle="modal"
                                                        data-target="#bookingDetailsModal"
                                                        title="View Transaction Details"><i
                                                            class="fa-solid fa-eye"></i></a></li>
                                                <li><a href="" title="Print Receipt"><i
                                                            class="fa-solid fa-print"></i></a></li>
                                                <li><a href="" title="Download Receipt"><i
                                                            class="fa-solid fa-download"></i></a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    @endforeach
                                   

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="seat--info">
            <span class="d-block">Seat No : {{ $customer->seat_no}}</span>
            <img src="{{ asset($customer->image) }}" alt="Seat" class="seat py-3">
            <p>{{ $customer->plan_name}}</p>
            <button>Booked for <b>{{ $customer->plan_type_name}}</b></button>
            <span class="text-success">Plan Expires in {{$diffInDays}} Days</span>
        </div>
        <div class="request-logs mt-4">
            <h5>Learners Request</h5>
            <ul class="request_list">
                <li>
                    <div class="d-flex">
                        <div class="icon"></div>
                        <div class="detials">
                            <p class="m-0"><i class="fa-solid fa-arrow-turn-down"></i> Request Name
                                : Swap Seat</p>
                            <span class="description">Message Send by <b>[Seat Owner]</b> on
                                10-05-2024.</span>
                            <span class="timestamp"><i class="fa-solid fa-calendar"></i> 29-05-2024
                                10:15:21 AM</span>
                            <small class="status"> <b>Status : </b> <span
                                    class=" text-danger d-inline">Pending</span> </small>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="d-flex">
                        <div class="icon"></div>
                        <div class="detials">
                            <p class="m-0"><i class="fa-solid fa-arrow-turn-up"></i> Request Name :
                                Upgrade Plan</p>
                            <span class="description">Seat is Swapped by <b>[Seat Owner]</b> on
                                10-05-2024.</span>
                            <span class="timestamp"><i class="fa-solid fa-calendar"></i> 29-05-2024
                                10:15:21 AM</span>
                            <small class="status"> <b>Status : </b> <span
                                    class=" text-success d-inline">Resolved (By Admin)</span>
                            </small>

                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>


       
@include('learner.script')
@endsection