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
            
            <!-- Add Course Type Fields -->
            <div class="card-body">
                <form id="submit">
                    @csrf
                    <div class="row g-4">
                        <input type="hidden" name="id" value="" id="course_type_id">

                        <div class="col-lg-6 mt-2">
                            <label for="name"> Course Type Name<sup class="text-danger">*</sup></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control char-only @error('name') is-invalid @enderror">

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        

                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-primary btn-block" id="submit_id">Add Course Type</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- end -->
        
            <!-- Add Course Type Fields -->
            <div class="card-body p-0">
                <h4 class="px-3 py-2">All Course Types List</h4>
                <div class="table-responsive tableRemove_scroll mt-2">
                    <table class="table table-hover dataTable m-0" id="datatable" style="display:table !important">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Course Type Name</th>
                                <th>Active</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        @php
                        $x=1;
                        @endphp
                        <tbody>
                            @foreach($course_type as $key => $value)
                            <tr>
                                <td>{{$x++}}</td>
                                <td>{{$value->name}}</td>
                                <td><span class="status-column">
                                    {{ $value->trashed() ? 'Inactive' : 'Active' }}
                                </span></td>
                                <td>
                                    <ul class="actionables">
                                        <li><a href="javascript:void(0)" type="button" class="course_type_edit" data-id="{{$value->id}}"><i class="fas fa-edit"></i></a></li>
                                        <li><a href="javascript:void(0)" type="button" class="delete" data-id="{{$value->id}}"><i class="fas fa-trash"></i></a></li>
                                        <li><a href="#" class="active-deactive" data-id="{{ $value->id }}" data-table="CourseType" title="Active/Deactive">
                                            @if($value->deleted_at)
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
<script type="text/javascript">
    $(document).ready(function() {

        $(document.body).on('submit', '#submit', function(event) {
            event.preventDefault();

            var name = $('#name').val();

            var course_type_id = $('#course_type_id').val();


            $.ajax({
                url: '{{ route('courseType.store') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    name: name,
                    id: course_type_id,
                },

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

        $(document.body).on('click', '.course_type_edit', function() {
            var id = $(this).data('id');
            $('#submit_id').text('');
            $('#submit_id').append('Edit Course Type');
            $.ajax({
                url: '{{ route('courseType.edit') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: 'GET',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id,

                },

                dataType: 'json',
                success: function(response) {

                    $('#name').val(response.grade.name);
                    $('#course_type_id').val(response.grade.id);


                }
            });

        });

        $('.delete').click(function(e) {
            if (!confirm('Are you sure you want to delete this Course?')) {
                e.preventDefault();
            }
            var class_id = $(this).data('id');
            $.ajax({
                url: '{{ route('class.destroy') }}',
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": class_id,

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
    });
</script>

@endsection