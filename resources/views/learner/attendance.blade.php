@extends('layouts.admin')
@section('content')
@php
use Carbon\Carbon;
$current_route = Route::currentRouteName();
@endphp

<!-- Bootstrap Toggle CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

    

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
                           
                            <th>In time</th>
                            <th>Out time</th>
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
                                    <input class="form-check-input toggle" type="checkbox" id="myToggle{{$value->learner_id}}" data-learner="{{$value->learner_id}}" {{ $value->attendance == 1 ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <div class="form-check form-switch justify-content-center">
                                    <input class="form-check-input outToggle" type="checkbox" id="outToggle{{$value->learner_id}}" data-learner="{{$value->learner_id}}">
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



    <script>
        $(document).ready(function () {
            // Add event listener for the toggle
            $('.toggle').on('change', function () {
                let learner_id = $(this).data("learner"); // Get the learner ID of the clicked toggle
              
                let attendance = $(this).prop("checked") ? 1 : 0; // Get the new attendance value (1 or 0)
                let currentToggle = $(this);

                var date = $('#date').val();
                alert(learner_id);
                // Validate date before making the AJAX request
                if (!date) {
                    alert('Please select a date!');
                    return;
                }
                var time='in';
                updateAttendance(learner_id,attendance,date,time);
                
            });

            $('.outToggle').on('change', function () {
                console.log("out");
                var attendance =  1;

                // Get learner_id and date
                var learner_id = $(this).data('learner');
                var date = $('#date').val();

                // Validate date before making the AJAX request
                if (!date) {
                   
                    alert('Please select a date!');
                    return;
                }
                var time='out';
                updateAttendance(learner_id,attendance,date,time);
                
            });


            function updateAttendance(learner_id,attendance,date,time){
                // Send AJAX request
                if(learner_id && attendance && date){

                                
                            $.ajax({
                                url: '{{ route('update.attendance') }}',
                                method: 'POST',
                                data: {
                                    learner_id: learner_id,
                                    attendance: attendance,
                                    date: date,
                                    time: time,
                                    _token: '{{ csrf_token() }}' 
                                },
                                success: function (response) {
                                    if (response.success) {
                                        console.log(response.message);
                                       
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
            }

            $("form").on("submit", function () {
            $(".toggle, .outToggle").prop("checked", false).trigger("change");
        });
        });
    </script>

    @endsection
