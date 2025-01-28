@extends('layouts.admin')
@section('content')
@php
use Carbon\Carbon;
$current_route = Route::currentRouteName();
@endphp

<!-- Bootstrap Toggle CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="records">
            <p class="mb-2 text-dark"><b>Total Seats : {{$total_seats}} | Available Seats : {{$availble_seats}} | Booked Seats: {{$booked_seats}}</b></p>
            <span class="text-success">Total Available Slots ({{$availble_seats}})</span> <span class="text-success">Total Booked Slots ({{$active_seat_count}})</span> <span class="text-danger">Total Expired Slots({{$expired_seat}})</span> <span class="text-danger">Extended Slots({{$extended_seats}})</span> <span class="text-danger">Full day ({{$fullday_count}})</span> <span class="text-danger">FH: First Half ({{$firstHalfCount}})</span> <span class="text-danger">SH: Second Half ({{$secondHalfCount}})</span> <span class="text-danger">H1: Hourly Slot 1 ({{$hourly1Count}})</span> <span class="text-danger">H2: Hourly Slot 2 ({{$hourly2Count}})</span> <span class="text-danger">H3: Hourly Slot 3 ({{$hourly3Count}})</span> <span class="text-danger">H4 : Hourly Slot 4 ({{$hourly4Count}})</span>
        </div>
    </div>

    <div class="row mb-4 mt-4">
        <div class="col-lg-12">
            <div class="filter-box">
                <h4 class="mb-3">Filter Box</h4>

                <form action="{{ route('attendance') }}" method="GET">
                    <div class="row g-4">
                        <!-- Filter By Plan -->
                        <div class="col-lg-4">
                            <label for="">Date</label>
                            <input type="date" name="date" value="{{ request('date') }}" id="date">

                        </div>
                        <div class="col-lg-2">
                            <button class="btn btn-primary button">
                                <i class="fa fa-search"></i> Search Records
                            </button>
                        </div>

                    </div>


                </form>
            </div>
        </div>

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
                            <td><span class="uppercase truncate" data-bs-toggle="tooltip" data-bs-title="{{$value->name}}" data-bs-placement="bottom">{{$value->name}}</span>
                                <br> <small>{{$value->dob}}</small>
                            </td>
                            <td><span class="truncate" data-bs-toggle="tooltip" data-bs-title="{{$value->email }}" data-bs-placement="bottom">
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
                                    @elseif ($diffInDays < 0 && $diffExtendDay==0) <small class="text-warning fs-10 d-block">Plan Expires today</small>
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
                                <div class="form-check form-switch text-center">
                                    <input class="form-check-input toggle" type="checkbox" id="myToggle" data-learner="{{$value->id}}">
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>


                </table>

            </div>
        </div>
    </div>



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            // Add event listener for the toggle
            $('.toggle').on('change', function () {
                // Determine attendance value
                var attendance = $(this).is(':checked') ? 1 : 2;

                // Get learner_id and date
                var learner_id = $(this).data('learner');
                var date = $('#date').val();

                // Validate date before making the AJAX request
                if (!date) {
                    alert('Please select a date!');
                    return;
                }

                // Send AJAX request
                if(learner_id && attendance && date){

                
                    $.ajax({
                        url: '{{ route('update.attendance') }}',
                        method: 'POST',
                        data: {
                            learner_id: learner_id,
                            attendance: attendance,
                            date: date,
                            _token: '{{ csrf_token() }}' 
                        },
                        success: function (response) {
                            if (response.success) {
                                alert(response.message);
                            } else {
                                alert('Error updating attendance');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error:', error);
                            alert('Something went wrong. Please try again.');
                        }
                    });
                }
            });
        });
    </script>

    @endsection
