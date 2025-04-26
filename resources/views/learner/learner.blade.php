@extends('layouts.admin')
@section('content')

<!-- Content Header (Page header) -->
@php
use Carbon\Carbon;
$current_route = Route::currentRouteName();
@endphp
<style>
    /* Pagination container styling */
    .pagination-container nav {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
    }

    /* Styling for the pagination links */
    .pagination-container a,
    .pagination-container span {
        text-decoration: none;
        padding: 8px 12px;
        margin: 0 4px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        color: #151f38;
        transition: background-color 0.3s, color 0.3s;
        height: 41px ! IMPORTANT;
        display: inline-flex;
        border-radius: 2rem;
        justify-content: center;
        align-items: center;
    }

    /* Hover effect for pagination links */
    .pagination-container a:hover {
        background-color: #007bff;
        /* Blue background on hover */
        color: white;
        /* White text on hover */
    }

    /* Disabled state styling */
    .pagination-container span.cursor-not-allowed {
        background-color: #ffffff;
        color: #000000;
        cursor: not-allowed;
        height: 41px !important;
        display: inline-block;
        font-size: 1rem;
        border-radius: 3rem;
    }

    /* Active page styling */
    .pagination-container .bg-blue-500 {
        background-color: #151f38;
        color: white;
        font-weight: bold;
        border-color: #151f38;
    }

    .pagination-container a:hover {
        background-color: #e1e9ff ! important;
        color: #000000;
    }

    /* Adjusting spacing for the Previous and Next links */
    .pagination-container .flex {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Styling for the Previous and Next buttons */
    .pagination-container .pagination-container a:hover,
    .pagination-container .pagination-container .bg-blue-500 {
        text-decoration: none;
        color: white;
        /* Make sure the text color stays white on hover */
    }
</style>
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

<div class="modal fade" id="noseatAllotmentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="success-message" class="alert alert-success" style="display:none;"></div>

        <div class="modal-content">
            <div id="error-message" class="alert alert-danger" style="display:none;"></div>
            <div id="validation-error-message" class="alert alert-danger" style="display:none;"></div>
            <div class="modal-header">
                <h1 class="modal-title px-2 fs-5" id="seat_no_head">Booking Form</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="seatAllotmentForm">
                    <div class="detailes">
                        <input type="hidden" name="seat_id" value="" id="seat_id">
                        <input type="hidden" name="general_seat" value="yes" id="general_seat">
                        <input type="hidden" class="form-control char-only" name="seat_no" value="" id="seat_no"
                            autocomplete="off">

                        <div class="row g-4">
                            <div class="col-lg-6">
                                <label for="">Full Name <span>*</span></label>
                                <input type="text" class="form-control char-only" name="name" id="name">
                            </div>
                            <div class="col-lg-6">
                                <label for="">DOB <span>*</span></label>
                                <input type="date" class="form-control" name="dob" id="dob">
                            </div>
                            <div class="col-lg-6">
                                <label for="">Mobile Number <span>*</span></label>
                                <input type="text" class="form-control digit-only" maxlength="10" minlength="10" name="mobile" id="mobile">
                            </div>
                            <div class="col-lg-6">
                                <label for="">Email Id <span>*</span></label>
                                <input type="text" class="form-control" name="email" id="email">
                            </div>

                            <div class="col-lg-4">
                                <label for="">Select Plan <span>*</span></label>
                                <select name="plan_id" id="plan_id" class="form-select" name="plan_id">
                                    <option value="">Select Plan</option>
                                    @foreach($plans as $key => $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <label for="">Plan Type <span>*</span></label>
                                <select id="plan_type_id" class="form-select" name="plan_type_id">
                                    <option value="">Select Plan Type</option>
                                    @foreach($plan_types as $key => $value)
                                    @can('has-permission',$value->name)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endcan
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-lg-4">
                                <label for="">Plan Starts On <span>*</span></label>
                                <input type="date" class="form-control" placeholder="Plan Starts On" name="plan_start_date" id="plan_start_date">
                            </div>
                            <div class="col-lg-4">
                                <label for="">Plan Price <span>*</span></label>
                                <input type="text" id="plan_price_id" class="form-control" name="plan_price_id" placeholder="Example : 00 Rs" readonly>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" value="" id="toggleFieldCheckbox" name="toggleFieldCheckbox">
                                    <label class="form-check-label" for="toggleFieldCheckbox">
                                        Locker
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-4" id="extraFieldContainer" style="display: none;">
                                <label for="locker_amount">Locker Amount</label>
                                <input type="text" class="form-control digit-only" name="locker_amount" id="locker_amount" placeholder="Enter Locker Amount">
                            </div>
                            <div class="col-lg-4" id="extraFieldContainer" >
                                <label for="discount_amount">Discount Amount</label>
                                <input type="text" class="form-control digit-only" name="discount_amount" id="discount_amount" placeholder="Enter Discount Amount">
                            </div>
                            <div class="col-lg-4">
                                <label for="">Paid Amount (INR)<span>*</span></label>
                                <input id="paid_amount" class="form-control digit-only" name="paid_amount" placeholder="Example : 00 Rs">
                                <span id="pending_amt" class="text-danger"></span>
                            </div>

                            <div class="col-lg-4">
                                <label for="">Choose Due Date<span>*</span></label>
                                <input type="date" class="form-control" placeholder="Plan Starts On" name="due_date" id="due_date" readonly>
                            </div>
                         
                            
                            
                            <div class="col-lg-4">
                                <label for="">Payment Mode <span>*</span></label>
                                <select name="payment_mode" id="payment_mode" class="form-select">
                                    <option value="">Select Payment Mode</option>
                                    <option value="1">Online</option>
                                    <option value="2">Offline</option>
                                    <option value="3">Pay Later</option>
                                </select>
                            </div>

                        </div>
                        <h4 class="py-4 m-0">Other Important Info
                            <i id="toggleIcon" class="fa fa-plus" style="cursor: pointer;"></i>
                        </h4>
                        <div id="idProofFields" style="display: none;">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <label for="">Id Proof Received </label>
                                    <select name="" id="id_proof_name" class="form-select" name="id_proof_name">
                                        <option value="">Select Id Proof</option>
                                        <option value="1">Aadhar</option>
                                        <option value="2">Driving License</option>
                                        <option value="3">Other</option>
                                    </select>
                                    <span class="text-danger">Uploading ID proof is optional do it later.</span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="id_proof_file">Upload Scan Copy of Proof</label>
                                    <input type="file" class="form-control" name="id_proof_file" id="id_proof_file"
                                        autocomplete="off">

                                    <a href="javascript:;" id="viewButton" style="display: none;">
                                        <i class="fa fa-eye"></i> View Uploaded File
                                    </a>
                                    <div id="filePopup" class="file-popup" style="display: none;">
                                        <img src="" id="imagePreview" style="display: none;" alt="Selected Image">
                                        <iframe id="pdfPreview" style="display: none;" frameborder="0"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row mt-2">
                            <div class="col-lg-4">
                                <input type="submit" class="btn btn-primary btn-block button" id="submit"
                                    value="Book Library Seat Now" autocomplete="off">
                            </div>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 text-end">
        @can('has-permission', 'General Seat Booked')
        @if(Auth::user()->library_seat_type!='numbered')
      
        <a href="javascript:;" data-bs-toggle="modal" class="btn btn-primary noseat_popup" data-bs-target="#noseatAllotmentModal" ><i class="fa-solid fa-check-circle available "></i>Seat Book</a>
        @endif
        @endcan
        @can('has-permission', 'Export Library Seats')
        <a href="{{ route('learners.export-csv') }}" class="btn btn-primary export"><i class="fa-solid fa-file-export"></i> Export All Data in CSV</a>
        @endcan
        @can('has-permission', 'Import Library Seats')
        <a href="{{ route('library.upload.form') }}" class="btn btn-primary export bg-4"><i class="fa-solid fa-file-import"></i> Import Learners Data to Portal</a>
        @endcan
    </div>
    @can('has-permission', 'Filter')
    <div class="col-lg-12">
        <div class="filter-box">
            <h4 class="mb-3">Filter Box</h4>

            <form action="{{ route('learners') }}" method="GET">
                <div class="row g-4">
                    <!-- Filter By Plan -->
                    <div class="col-lg-2">
                        <label for="plan_id">Filter By Plan</label>
                        <select name="plan_id" id="plan_id" class="form-select">
                            <option value="">Choose Plan</option>
                            @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ request()->get('plan_id') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter By Payment Status -->
                    <div class="col-lg-2">
                        <label for="is_paid">Filter By Payment Status</label>
                        <select name="is_paid" id="is_paid" class="form-select">
                            <option value="">Choose Payment Status</option>
                            <option value="1" {{ request()->get('is_paid') == '1' ? 'selected' : '' }}>Paid</option>
                            <option value="0" {{ request()->get('is_paid') == '0' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </div>


                    <!-- Filter By Active/Expired Status -->
                    <div class="col-lg-2">
                        <label for="status">Filter By Active / Expired</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Choose Status</option>
                            <option value="active" {{ request()->get('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ request()->get('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>
                    <!-- Filter By Active/Expired Status -->
                    @if(Auth::user()->library_seat_type != 'general')
                   
                    <div class="col-lg-2">
                        <label for="status">Seat No.</label>
                        <select name="seat_no" id="seat_no" class="form-select">
                            <option value="">Seat No</option>

                            @foreach($seats as $seat)
                            <option value="{{ $seat->id }}" {{ request()->get('seat_no') == $seat->id ? 'selected' : '' }}>
                                {{ $seat->seat_no }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <!-- Search By Name, Mobile & Email -->
                    <div class="col-lg-4">
                        <label for="search">Search By</label>
                        <input type="text" class="form-control" name="search" placeholder="Enter Name, Mobile or Email"
                            value="{{ request()->get('search') }}">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-2">
                        <button class="btn btn-primary button">
                            <i class="fa fa-search"></i> Search Records
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endcan
</div>

<div class="row mb-4 mt-4">
    <div class="col-lg-12 mb-4">
        <div class="records">
            @if(Auth::user()->library_seat_type != 'general')
            <p class="mb-2 text-dark"><b>Total Seats : {{$total_seats}} | Available Seats : {{$availble_seats}} | Booked Seats: {{$booked_seats}}</b></p>
            @endif
            <span class="text-success">Total Available Slots ({{$availble_seats}})</span> <span class="text-success">Total Booked Slots ({{$active_seat_count}})</span> <span class="text-danger">Total Expired Slots({{$expired_seat}})</span> <span class="text-danger">Extended Slots({{$extended_seats}})</span> <span class="text-danger">Full day ({{$fullday_count}})</span> <span class="text-danger">FH: First Half ({{$firstHalfCount}})</span> <span class="text-danger">SH: Second Half ({{$secondHalfCount}})</span> <span class="text-danger">H1: Hourly Slot 1 ({{$hourly1Count}})</span> <span class="text-danger">H2: Hourly Slot 2 ({{$hourly2Count}})</span> <span class="text-danger">H3: Hourly Slot 3 ({{$hourly3Count}})</span> <span class="text-danger">H4 : Hourly Slot 4 ({{$hourly4Count}})</span>
        </div>
    </div>
    {{-- <p>Total 10 out of 61 Records 1-10</p> --}}
    <div class="col-lg-12">
        <div class="table-responsive ">
            <table class="table text-center datatable border-bottom" id="datatable">
                <thead>
                    <tr>
                        <th>Seat No.</th>
                        <th>Learner Info</th>
                        <th>Contact Info</th>
                        <th>Active Plan</th>
                        <th>Expired On</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                 
                    $user = Auth::user();
                    $permissions = $user->subscription ? $user->subscription->permissions : null;
                    @endphp
                    @foreach($learners as $key => $value)
                    @php
                    $today = Carbon::today();
                    $endDate = Carbon::parse($value->plan_end_date);
                    $diffInDays = $today->diffInDays($endDate, false);
                    $inextendDate = $endDate->copy()->addDays($extendDay); // Preserving the original $endDate
                    $diffExtendDay= $today->diffInDays($inextendDate, false);
                   
                    @endphp
                    <tr>
                        <td>{{$value->seat_no ? $value->seat_no : ucfirst(Auth::user()->library_seat_type)}}<br>
                            <small>{{$value->plan_type_name}}</small>
                        </td>
                        <td><span class="uppercase truncate" data-bs-toggle="tooltip"
                                data-bs-title="{{$value->name}}" data-bs-placement="bottom">{{$value->name}}</span>
                            <br> <small>{{$value->dob}}</small>
                        </td>
                        <td><span class="truncate" data-bs-toggle="tooltip"
                                data-bs-title="{{$value->email }}" data-bs-placement="bottom">
                                {{$value->email }}</span> <br>
                            <small> +91-{{$value->mobile}}</small>
                        </td>
                        <td>{{$value->plan_start_date}}<br>
                            <small>{{$value->plan_name}}</small>
                        </td>
                        <td>{{$value->plan_end_date}}<br>

                            @if ($diffInDays > 0)
                            <small class="text-success">Plan Expires in {{ $diffInDays }} days</small>
                            @elseif ($diffInDays <= 0 && $diffExtendDay>0)
                                <small class="text-danger fs-10 d-block">Extension active! {{ abs($diffExtendDay) }} days left.</small>
                                @elseif ($diffInDays < 0 && $diffExtendDay==0)
                                    <small class="text-warning fs-10 d-block">Plan Expires today</small>
                                    @else
                                    <small class="text-danger fs-10 d-block">Plan Expired {{ abs($diffInDays) }} days ago</small>
                                    @endif
                        </td>
                        <td>
                            @if($value->status==1)
                            <button class="active-status">Active</button>
                            @else
                            <button class="active-status">InActive</button>
                            @endif
                            @if(!empty(learnerTransaction($value->id,$value->learner_detail_id)->pending_amount) && learnerTransaction($value->id,$value->learner_detail_id)->pending_amount==0)
                                <span class="text-success d-block">Fully Paid</span>
                            @elseif(empty(learnerTransaction($value->id,$value->learner_detail_id)->pending_amount))
                                <span></span>
                            @else
                            <a href="{{ route('learner.pending.payment', ['id' => $value->id]) }}" class="text-danger d-block">
                                Pending : {{ learnerTransaction($value->id, $value->learner_detail_id)->pending_amount ?? '' }}
                            </a>
                                                        
                            @endif

                        </td>
                        <td>

                            <ul class="actionalbls">
                                <!-- View Seat Info -->
                                @can('has-permission', 'View Seat')
                                <li><a href="{{route('learners.show',$value->id)}}" title="View Seat Booking Full Details"><i class="fas fa-eye"></i></a></li>
                                @endcan

                                <!-- Edit Seat Info -->
                                @if($diffExtendDay>0)

                                    @can('has-permission', 'Edit Seat')
                                    <li><a href="{{route('learners.edit',$value->id)}}" title="Edit Seat Booking Details"><i class="fas fa-edit"></i></a></li>
                                    @endcan
                                    
                                    {{-- <li><a href="{{route('learner.expire',$value->id)}}" title="Custom Seat Expire"><i class="fas fa-calendar"></i></a></li> --}}

                                    <!-- Make payment -->
                                    @can('has-permission','Renew Seat')
                                    <li><a href="{{route('learner.payment',$value->learner_detail_id)}}" title="Payment Lerners" class="payment-learner"><i class="fas fa-credit-card"></i></a></li>

                                    @endcan

                                    <!-- Swap Seat-->

                                    @can('has-permission', 'Swap Seat')
                                    @if(Auth::user()->library_seat_type != 'general')
                                    <li><a href="{{route('learners.swap',$value->id)}}" title="Swap Seat "><i class="fa-solid fa-arrow-right-arrow-left"></i></a></li>
                                    @endif
                                    @endcan

                                
                                    @can('has-permission', 'Change Plan')
                                    <li><a href="{{route('learner.change.plan',$value->id)}}" title="Change Plan"><i class="fa fa-arrow-up-short-wide"></i></a></li>
                                    @endcan
                                    <!---ID Card generate-->
                                    <li>
                                        <form action="{{ route('generateIdCard') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" id="custId" name="detail_id" value="{{ $value->learner_detail_id }}">
                                            <input type="hidden" name="learner_id" value="{{ $value->id }}">
                                            <button type="submit"><i class="fa fa-print"></i></button>
                                        </form>
                                    </li>
                                    <!-- upgrade Seat-->
                                    @if($diffInDays <= 0 && $diffExtendDay>0 && $diffExtendDay>5)

                                        @can('has-permission', 'Upgrade Seat Plan')
                                        <li><a href="{{route('learners.upgrade.renew',$value->id)}}" title="Upgrade Plan"><i class="fa fa-arrow-up-short-wide"></i></a></li>
                                        @endcan

                                    @endif
                                    <!-- Close Seat -->

                                    @can('has-permission', 'Close Seat')
                                    <li><a href="javascript:void(0);" class="link-close-plan" data-id="{{ $value->id }}" title="Close" data-plan_end_date="{{$value->plan_end_date}}"><i class="fas fa-times"></i></a></li>
                                    @endcan
                                @endif
                                    <!-- Deletr Seat -->
                                    @can('has-permission', 'Delete Seat')
                                    <li><a href="#" data-id="{{$value->id}}" title="Delete Lerners" class="delete-customer"><i class="fas fa-trash"></i></a></li>
                                    @endcan
                                    @can('has-permission', 'Reactive Seat')
                                    @if($value->status==0)
                                    <li><a href="{{route('learners.reactive',$value->id)}}" title="Reactivate Learner"><i class="fa-solid fa-arrows-rotate"></i></a></li>
                                    @endif
                                    @endcan
                                    @if($diffExtendDay>0)
                                    <!-- Sent Mail -->

                                    @can('has-permission', 'WhatsApp Notification')
                                    <li><a href="https://web.whatsapp.com/send?phone=91{{$value->mobile}}&text=Hey!%20🌟%0A%0AJust%20a%20friendly%20reminder:%20Your%20library%20seat%20plan%20will%20expire%20in%205%20days!%20📚✨%0A%0ADon%E2%80%99t%20miss%20out%20on%20the%20chance%20to%20keep%20enjoying%20your%20favorite%20books%20and%20resources.%20Plus,%20renewing%20now%20means%20you%20can%20unlock%20exciting%20rewards!%20🎁" target="_blank" data-id="11"
                                            onclick="incrementMessageCount({{ $value->id }}, 'whatsapp')"
                                            class="whatsapp" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-original-title="Send WhatsApp Reminder"><i class="fa-brands fa-whatsapp"></i></a></li>

                                    @endcan
                                    <!-- Sent Mail -->
                                    @can('has-permission', 'Email Notification')
                                    <li><a href="mailto:{{$value->email }}?subject=Library Seat Renewal Reminder&body=Hey!%20🌟%0D%0A%0D%0AJust%20a%20friendly%20reminder:%20Your%20library%20seat%20plan%20will%20expire%20in%205%20days!%20📚✨%0D%0A%0D%0ADon%E2%80%99t%20miss%20out%20on%20the%20chance%20to%20keep%20enjoying%20your%20favorite%20books%20and%20resources.%20Plus,%20renewing%20now%20means%20you%20can%20unlock%20exciting%20rewards!%20🎁" target="_blank" data-id="11"
                                            onclick="incrementMessageCount({{ $value->id }}, 'email')"
                                            class="message" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-original-title="Send Email Reminders"><i class="fas fa-envelope"></i></a></li>
                                    @endcan
                                    @endif

                            </ul>
                        </td>
                    </tr>
                    @endforeach

                </tbody>


            </table>
            <!-- Add pagination links -->
            {{-- <div class="d-flex justify-content-center">
                <div class="pagination-container">
                    {{ $learners->links('vendor.pagination.default') }}
            </div>
            </div> --}}

        </div>
    </div>
</div>


<!-- /.content -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    $(document).ready(function() {
        let table = new DataTable('#datatable', {
            searching: false ,// This option hides the search bar
            ordering: false
        });
        var url = window.location.href;

        // Check if there are any URL parameters
        if (url.includes('?')) {
            // Redirect to the URL without parameters
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
</script>

@include('learner.script')
@endsection