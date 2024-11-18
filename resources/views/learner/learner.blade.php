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


<div class="row">
    <div class="col-lg-12 text-end">
        <a href="{{ route('learners.export-csv') }}" class="btn btn-primary export"><i class="fa-solid fa-file-export"></i> Export All Data in CSV</a>
        <a href="{{ route('library.upload.form') }}" class="btn btn-primary export bg-4"><i class="fa-solid fa-file-import"></i> Import Learners Data to Portal</a>
    </div>
    <div class="col-lg-12">
        <div class="filter-box">
            <h4 class="mb-3">Filter Box</h4>

            <form action="{{ route('learners') }}" method="GET">
                <div class="row">
                    <!-- Filter By Plan -->
                    <div class="col-lg-3">
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
                    <div class="col-lg-3">
                        <label for="is_paid">Filter By Payment Status</label>
                        <select name="is_paid" id="is_paid" class="form-select">
                            <option value="">Choose Payment Status</option>
                            <option value="1" {{ request()->get('is_paid') == '1' ? 'selected' : '' }}>Paid</option>
                            <option value="0" {{ request()->get('is_paid') == '0' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </div>

                    <!-- Filter By Active/Expired Status -->
                    <div class="col-lg-3">
                        <label for="status">Filter By Active / Expired</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Choose Status</option>
                            <option value="active" {{ request()->get('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ request()->get('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>

                    <!-- Search By Name, Mobile & Email -->
                    <div class="col-lg-3">
                        <label for="search">Search By Name, Mobile & Email</label>
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
</div>

<div class="row mb-4 mt-4">
    <div class="col-lg-12 mb-4">
        <div class="records">
            <p class="mb-2 text-dark"><b>Total Seats : {{$total_seats}} | Available Seats : {{$availble_seats}} | Booked Seats: {{$booked_seats}}</b></p>
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
                        <td>{{$value->seat_no}}<br>
                            <small>{{$value->plan_type_name}}</small>
                        </td>
                        <td><span class="uppercase truncate" data-bs-toggle="tooltip"
                                data-bs-title="{{$value->name}}" data-bs-placement="bottom">{{$value->name}}</span>
                            <br> <small>{{$value->dob}}</small>
                        </td>
                        <td><span class="truncate" data-bs-toggle="tooltip"
                                data-bs-title="{{$value->email }}" data-bs-placement="bottom"><i
                                    class="fa-solid fa-times text-danger"></i></i>
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

                                <li><a href="{{route('learner.expire',$value->id)}}" title="Custom Seat Expire"><i class="fas fa-calendar"></i></a></li>

                                <!-- Make payment -->
                                <li><a href="{{route('learner.payment',$value->learner_detail_id)}}" title="Payment Lerners" class="payment-learner"><i class="fas fa-credit-card"></i></a></li>

                                <!-- Swap Seat-->

                                @can('has-permission', 'Swap Seat')
                                <li><a href="{{route('learners.swap',$value->id)}}" title="Swap Seat "><i class="fa-solid fa-arrow-right-arrow-left"></i></a></li>
                                @endcan


                                <!-- upgrade Seat-->

                                @can('has-permission', 'Upgrade Seat Plan')
                                <li><a href="{{route('learners.upgrade',$value->id)}}" title="Upgrade Plan"><i class="fa fa-arrow-up-short-wide"></i></a></li>
                                @endcan

                                <!-- Close Seat -->

                                @can('has-permission', 'Close Seat')
                                <li><a href="javascript:void(0);" class="link-close-plan" data-id="{{ $value->id }}" title="Close" data-plan_end_date="{{$value->plan_end_date}}"><i class="fas fa-times"></i></a></li>
                                @endcan
                                @endif
                                <!-- Deletr Seat -->
                                @can('has-permission', 'Delete Seat')
                                <li><a href="#" data-id="{{$value->id}}" title="Delete Lerners" class="delete-customer"><i class="fas fa-trash"></i></a></li>
                                @endcan
                                @if($value->status==0)
                                <li><a href="{{route('learners.reactive',$value->id)}}" title="Reactivate Learner"><i class="fa-solid fa-arrows-rotate"></i></a></li>
                                @endif
                                @if($diffExtendDay>0)
                                <!-- Sent Mail -->
                                <li><a href="https://web.whatsapp.com/send?phone=91{{$value->mobile}}&text=Hey!%20🌟%0A%0AJust%20a%20friendly%20reminder:%20Your%20library%20seat%20plan%20will%20expire%20in%205%20days!%20📚✨%0A%0ADon%E2%80%99t%20miss%20out%20on%20the%20chance%20to%20keep%20enjoying%20your%20favorite%20books%20and%20resources.%20Plus,%20renewing%20now%20means%20you%20can%20unlock%20exciting%20rewards!%20🎁" target="_blank" data-id="11" 
                                    onclick="incrementMessageCount({{ $value->id }}, 'whatsapp')" 
                                    class="whatsapp" data-bs-toggle="tooltip" data-bs-placement="bottom" title=""  data-original-title="Send WhatsApp Reminder"><i class="fa-brands fa-whatsapp"></i></a></li>
                                <!-- Sent Mail -->
                                <li><a href="mailto:RECIPIENT_EMAIL?subject=Library Seat Renewal Reminder&body=Hey!%20🌟%0D%0A%0D%0AJust%20a%20friendly%20reminder:%20Your%20library%20seat%20plan%20will%20expire%20in%205%20days!%20📚✨%0D%0A%0D%0ADon%E2%80%99t%20miss%20out%20on%20the%20chance%20to%20keep%20enjoying%20your%20favorite%20books%20and%20resources.%20Plus,%20renewing%20now%20means%20you%20can%20unlock%20exciting%20rewards!%20🎁" target="_blank" data-id="11" 
                                    onclick="incrementMessageCount({{ $value->id }}, 'email')" 
                                    class="message"  data-bs-toggle="tooltip" data-bs-placement="bottom" title=""  data-original-title="Send Email Reminders"><i class="fas fa-envelope"></i></a></li>

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
            searching: false // This option hides the search bar
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