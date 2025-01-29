@extends('layouts.admin')
@section('content')
@php
use Carbon\Carbon;
$current_route = Route::currentRouteName();
@endphp

<!-- Bootstrap Toggle CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<div class="row">
    

    <div class="row mb-4 ">
        <div class="col-lg-12">
            <div class="filter-box">
                <h4 class="mb-3">Filter Box</h4>

                <form action="{{ route('attendance') }}" method="GET">
                    <div class="row g-4">
                        <!-- Filter By Plan -->
                        <div class="col-lg-4">
                            
                            <input type="date" class="form-control" name="date" value="{{ request('date') ?: date('Y-m-d') }}" id="date">

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

        
    </div>
    <div class="row mb-4">
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
                           
                            <th>Mark Attendance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($learners)
    

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
                                <div class="form-check form-switch justify-content-center">
                                    <input class="form-check-input toggle" type="checkbox" id="myToggle" data-learner="{{$value->id}}" {{ $value->attendance == 1 ? 'checked' : '' }}>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                     
    
                        @endif
                    </tbody>


                </table>

            </div>
        </div>
    </div>
    </div>


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
