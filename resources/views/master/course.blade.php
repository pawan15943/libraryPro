@extends('layouts.admin')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="row">
    <!-- Page Main Content -->
    <div class="col-lg-12">
        <div id="success-message" class="alert alert-success" style="display:none;"></div>
        <div id="error-message" class="alert alert-danger" style="display:none;"></div>
        <div class="card card-default">
        <div class="card card-default">

            <!-- Add Course Fields -->
            <div class="card-body">
                <form id="submit">
                    @csrf
                    <div class="row g-4">
                        <input type="hidden" name="id" value="" id="course_id">
                        <div class="col-lg-3">
                            <label for="class_name"> Course Type<sup class="text-danger">*</sup></label>
                            <select id="course_type" name="course_type" class="form-control @error('course_type') is-invalid @enderror">
                                @error('course_type')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                                <option value="">Select Course Type</option>
                                @foreach ($course_type as $key => $value)
                                <option value="{{$value}}">{{$key}}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="class_name"> Course Name<sup class="text-danger">*</sup></label>
                            <input type="text" id="course_name" name="course_name" value="{{ old('course_name') }}" class="form-control char-only @error('course_name') is-invalid @enderror" >
                            @error('course_name')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="col-lg-3">
                            <label for="class_name"> Course Fees<sup class="text-danger">*</sup></label>
                            <input type="text" id="course_fees" name="course_fees" value="{{ old('course_fees') }}" class="form-control digit-only @error('course_fees') is-invalid @enderror" >
                        </div>

                        <div class="col-lg-3">
                            <label for="class_name"> Course Duration<sup class="text-danger">*</sup></label>
                            <input type="text" id="duration" name="duration" value="{{ old('duration') }}" class="form-control digit-only @error('duration') is-invalid @enderror" >
                        </div>

                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-primary btn-block" id="submit_id">Add Course</button>
                        </div>
                    </div>

                </form>
            </div>
            <!-- end -->

            <!-- All Course Fields -->
            <div class="card-body p-0">
                <h4 class="px-3 py-2">All Courses List</h4>
                <div class="table-responsive tableRemove_scroll mt-2">
                    <table class="table table-hover dataTable m-0" id="datatable" style="display:table !important">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Course Name</th>
                                <th>Course Fees </th>
                                <th>Duration</th>
                                <th>Course Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                            $x=1;
                            @endphp
                            @foreach($courses as $key => $course)
                            <tr>
                                <td>{{$x++}}</td>
                                <td>{{$course->course_name}}</td>

                                <td>{{$course->course_fees}}</td>

                                <td>{{$course->duration}}</td>
                                <td><span class="status-column">
                                    {{ $course->trashed() ? 'Inactive' : 'Active' }}
                                </span></td>

                                <td>
                                    <ul class="actionables">
                                        <li><a href="javascript:void(0)" type="button" class="course_edit" data-id="{{$course->id}}"><i class="fa fa-edit"></i></a></li>
                                        <li> <a href="javascript:void(0)" type="button" class="delete" data-id="{{$course->id}}"><i class="fa fa-trash"></i></a></li>
                                        <li> <a href="#" class="active-deactive" data-id="{{$course->id}}" data-table="Course" title="Active/Deactive">
                                            @if($course->deleted_at)
                                            <i class="fas fa-check"></i>
                                            @else
                                            <i class="fas fa-ban"></i>
                                            @endif</a></li>
                                    </ul>



                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
            <!-- end -->

        </div>
    </div>
</div>

@include('master.script')
<script>
    $(document.body).on('submit', '#submit', function(event) {

        event.preventDefault();

        var formData = new FormData(this);
        var course_name = $('#course_name').val();
        var course_fees = $('#course_fees').val();
        var duration = $('#duration').val();
        var course_id = $('#course_id').val();

        var errors = {};
        if (!course_name) {
            errors.course_name = 'Course Name is required.';
        }
        if (!course_fees) {
            errors.course_fees = 'Course Fees is required.';
        }
        if (!duration) {
            errors.duration = 'Duration is required.';
        }

        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: '{{ route('course.store') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            dataType: 'json',
            success: function(response) {

                if (response.success) {
                        $("#success-message").text(response.message).show();
                        $("#error-message").hide();
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                } else if (response.errors) {
                        $(".is-invalid").removeClass("is-invalid");
                        $(".invalid-feedback").remove();

                        $.each(response.errors, function(key, value) {
                            var element = $("[name='" + key + "']"); // Target element by name attribute
                          
                            element.addClass("is-invalid");
                            element.after('<span class="invalid-feedback" role="alert">' + value + '</span>');
                        });
                } else {
                        $("#error-message").text(response.message).show();
                        $("#success-message").hide();
                }
            }
        });
    });

    $(document.body).on('click', '.course_edit', function() {
        var course_id = $(this).data('id');
        $('#submit_id').text('');
        $('#submit_id').append('Edit Course');
        $.ajax({
            url: '{{ route('course.edit') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: 'GET',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": course_id,

            },

            dataType: 'json',
            success: function(response) {

                $('#course_name').val(response.course.course_name);

                $('#course_fees').val(response.course.course_fees);
                $('#course_type').val(response.course.course_type);

                $('#duration').val(response.course.duration);

                $('#course_id').val(response.course.id);

            }
        });

    });

    $('.delete').click(function(e) {
        if (!confirm('Are you sure you want to delete this Course?')) {
            e.preventDefault();
        }
        var course_id = $(this).data('id');
        $.ajax({
            url: '{{ route('course.destroy') }}',
            type: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": course_id,

            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success'
                    }).then(function() {

                        location.reload();

                    });
                } else {
                    // Handle other cases if needed
                }
            },
            error: function(error) {
                // Handle errors if the AJAX request fails
            }
        });

    });
</script>

@endsection