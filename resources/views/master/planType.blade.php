@extends('layouts.admin')
@section('content')

<!-- Main content -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Custom CSS -->

<div class="row">
    <!-- Page Main Content -->
    <div class="col-lg-12" id="planType">
        <!-- General Information -->
        <div class="card card-default">

                <!-- Add Plan Type -->
                <div class="card-body">
                    <form action="{{ isset($planType) ? route('planType.update', $planType->id) : route('planType.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($planType))
                        @method('PUT')
                        @endif
                        <div class="row g-4">
                            <div class="col-lg-3">
                                <label>Plan Type<sup class="text-danger">*</sup></label>
                                <input type="text" name="name" class="form-control char-only @error('name') is-invalid @enderror" placeholder="Enter Plan Type" value="{{ old('name', isset($planType) ? $planType->name : '') }}">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label>Plan Start Time<sup class="text-danger">*</sup></label>

                                <input type="text" id="start_time" class="form-control @error('start_time') is-invalid @enderror" name="start_time" value="{{ old('start_time', isset($planType) ? $planType->start_time : '') }}" placeholder="Select start time">
                                @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label>Plan End Time<sup class="text-danger">*</sup></label>
                                <input type="text" id="end_time" class="form-control @error('end_time') is-invalid @enderror" name="end_time" value="{{ old('end_time', isset($planType) ? $planType->end_time : '') }}" placeholder="Select end time">
                                @error('end_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label>Slot Duration</label>
                                <input type="text" id="slot_hours" class="form-control @error('slot_hours') is-invalid @enderror" name="slot_hours" readonly value="{{ old('slot_hours', isset($planType) ? $planType->slot_hours : '') }}" placeholder="Slot duration">
                                @error('slot_hours')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label>Select Seat Color <sup class="text-danger">*</sup></label>
                                <div class="d-flex">
                                    <label for="orange">
                                        <input type="radio" name="image_colour" value="orange" id="orange">
                                        Orange
                                    </label>
                                    <label for="light_orange">
                                        <input type="radio" name="image_colour" value="light_orange" id="light_orange">
                                        Light Orange</label>
                                    <label for="green">
                                        <input type="radio" name="image_colour" value="green" id="green">
                                        Green</label>
                                    <label for="blue">
                                        <input type="radio" name="image_colour" value="blue" id="blue">
                                        Blue</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <select class="form-control @error('timming') is-invalid @enderror" name="timming">
                                    <option value="">Select time</option>
                                    <option value="Morning1" >Morning1</option>
                                    <option value="Morning2" >Morning2</option>
                                    <option value="Evening1" >Evening1</option>
                                    <option value="Evening2" >Evening2</option>
                                </select>
                                @error('timming')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <input type="submit" class="btn btn-primary btn-block" value="{{isset($planType) ? 'Edit Plan Type' : 'Add Plan Type'}}">
                            </div>
                        </div>
                    </form>
                </div>
                <!-- end -->
                
                <!-- All Plan Type List -->
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <h4 class="px-3">All Plans Type List</h4>
                            <div class="table-responsive tableRemove_scroll mt-2">
                                <table class="table table-hover dataTable m-0" id="datatable" style="display:table !important">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Plan Type</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Hours</th>
                                            <th>Seat Colour</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $x=1;
                                        @endphp
                                        @foreach($plan_types as $key => $value)
                                        <tr>
                                            <td> {{$x++}}</td>
                                            <td> {{$value->name}}</td>
                                            <td> {{$value->start_time}}</td>
                                            <td> {{$value->end_time}}</td>
                                            <td> {{$value->slot_hours}}</td>
                                            <td style="width: 20%">
                                                <img src="{{ asset($value->image) }}" alt="{{ $value->name }} image" class="w-65">
                                            </td>
                                            <td class="status-column">{{ $value->trashed() ? 'Inactive' : 'Active' }}</td>
                                            <td>
                                            <ul class="actionables">
                                                <li>
                                                <a href="{{route('planType.edit', $value->id)}}" title="Edit PlanType"><i class="fas fa-edit"></i></a>
                                                </li>
                                                <li>
                                                <a href="#" class="active-deactive" data-id="{{ $value->id }}" data-table="PlanType" title="Active/Deactive">
                                                @if($value->trashed())
                                                <i class="fas fa-check"></i>
                                                @else
                                                <i class="fas fa-ban"></i>
                                                @endif
                                                </a>
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
                <!-- end -->
        </div>
    </div>
</div>

<!-- /.content -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        flatpickr("#start_time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            onChange: function(selectedDates, dateStr, instance) {
                calculateSlotHours();
            }
        });

        flatpickr("#end_time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            onChange: function(selectedDates, dateStr, instance) {
                calculateSlotHours();
            }
        });

        function calculateSlotHours() {
            var startTime = $('#start_time').val();
            var endTime = $('#end_time').val();

            if (startTime && endTime) {
                var start = new Date("1970-01-01T" + startTime + ":00Z");
                var end = new Date("1970-01-01T" + endTime + ":00Z");
                var diffInMinutes = (end - start) / 1000 / 60;

                if (diffInMinutes < 0) {
                    diffInMinutes += 24 * 60; // Adjust for crossing midnight
                }

                var hours = Math.floor(diffInMinutes / 60);
                var minutes = diffInMinutes % 60;

                $('#slot_hours').val(hours);
            }
        }
    });
</script>
<script>
    flatpickr("#start_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K", // Include minutes to format correctly
        time_24hr: false, // Use 12-hour format with AM/PM
        minuteIncrement: 60, // Disable minute selection by making it increment by 60
        defaultHour: 12, // Set a default hour if needed
        onValueUpdate: function(selectedDates, dateStr, instance) {
            let hours = instance.formatDate(selectedDates[0], "h");
            let minutes = "00";
            let ampm = instance.formatDate(selectedDates[0], "K");

            // Add leading zero to hours if necessary
            if (hours.length < 2) {
                hours = '0' + hours;
            }

            // Construct the final formatted time
            let formattedTime = `${hours}:${minutes} ${ampm}`;
            instance.input.value = formattedTime;
        }
    });

</script>

@include('master.script')
@endsection