@extends('layouts.admin')
@section('content')

<!-- Content Header (Page header) -->
@php
      use Carbon\Carbon;
      $current_route = Route::currentRouteName();
@endphp


<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-bordered text-center" id="datatable">
                <thead>
                    <tr>
                        <th>Seat No.</th>
                        <th>Learner Info</th>
                        <th>Contact Info</th>
                        <th>Active Plan</th>
                        <th>Expired On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
                @if($current_route=='learners')
                <tbody>
                    @foreach($learners as $key => $value)
                        @php
                            $today = Carbon::today();
                            $endDate = Carbon::parse($value->plan_end_date);
                            $diffInDays = $today->diffInDays($endDate, false);
                        @endphp
                        
                    <tr>
                        <td>{{$value->seat_no}}<br>
                            <small>{{$value->plan_type_name}}</small>
                        </td>
                        <td>{{$value->name}}
                            <br> 
                            <small>{{$value->dob}}</small>
                        </td>
                        <td>{{$value->email }} <br>
                            <small> +91-{{$value->mobile}}</small>
                        <td>{{$value->plan_name}}<br>
                            <small>{{$value->plan_start_date}}</small>
                        </td>
                        <td> {{$value->plan_end_date}}<br>
                            @if ($diffInDays > 0)
                            <small class="text-success fs-10 d-block">Expires in {{ $diffInDays }} days</small>
                            @elseif ($diffInDays < 0)
                                <small class="text-danger fs-10 d-block">Expired {{ abs($diffInDays) }} days ago</small>
                            @else
                                <small class="text-warning fs-10 d-block">Expires today</small>
                            @endif
                          
                        </td>
                        <td>
                            <ul class="actionables">
                                <!-- View Seat Info -->
                                <li><a href="{{route('learners.show',$value->id)}}" title="View Seat Booking Full Details"><i class="fas fa-eye"></i></a></li>

                                <!-- Edit Seat Info -->
                                <li><a href="{{route('learners.edit',$value->id)}}" title="Edit Seat Booking Details"><i class="fas fa-edit"></i></a></li>

                                <!-- Swap Seat-->
                                <li><a href="{{route('learners.swap',$value->id)}}" title="Swap Seat "><i class="fa-solid fa-arrow-right-arrow-left"></i></a></li>

                                <!-- Swap Seat-->
                                <li><a href="{{route('learners.upgrade',$value->id)}}" title="Upgrade Plan"><i class="fa fa-arrow-up-short-wide"></i></a></li>

                                <!-- Close Seat -->
                                <li><a href="javascript:void(0);" class="link-close-plan" data-id="{{ $value->id }}" title="Close"><i class="fas fa-times"></i></a></li>

                                <!-- Deletr Seat -->
                                <li><a href="#" data-id="{{$value->id}}" title="Delete Lerners" class="delete-customer"><i class="fas fa-trash"></i></a></li>
                            </ul>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
                @endif
                @if($current_route=='learnerHistory')
                <tbody>
                    
                    @foreach($learnerHistory as $key => $value)
                        @php
                            $today = Carbon::today();
                            $endDate = Carbon::parse($value->plan_end_date);
                            $diffInDays = $today->diffInDays($endDate, false);
                        @endphp

                            <tr>
                                <td>{{$value->seat_no}}<br>
                                    <small>{{$value->plan_type_name}}</small>
                                </td>
                                <td>{{$value->name}}
                                    <br> 
                                    <small>{{$value->dob}}</small>
                                </td>
                                <td>{{$value->email }} <br>
                                    <small> +91-{{$value->mobile}}</small>
                                <td>{{$value->plan_name}}<br>
                                    <small>{{$value->plan_start_date}}</small>
                                </td>
                                <td> {{$value->plan_end_date}}<br>
                                    @if ($diffInDays > 0)
                                    <small class="text-success fs-10 d-block">Expires in {{ $diffInDays }} days</small>
                                    @elseif ($diffInDays < 0)
                                        <small class="text-danger fs-10 d-block">Expired {{ abs($diffInDays) }} days ago</small>
                                    @else
                                        <small class="text-warning fs-10 d-block">Expires today</small>
                                    @endif
                                
                                </td>
                                <td>
                                    <ul class="actionables">
                                        <li><a href="{{route('learners.reactive',$value->id)}}" title="Reactivate Learner"><i class="fa-solid fa-arrows-rotate"></i></a></li>
                                    </ul>
                                </td>

                            </tr>
                    
                    @endforeach
                </tbody>
                @endif
            </table>


        </div>
    </div>
</div>
           

<!-- /.content -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
 
    $(document).on('click', '.delete-customer', function() {
    var id = $(this).data('id');
    var url = '{{ route('learners.destroy', ':id') }}';
    url = url.replace(':id', id);

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        'User has been deleted.',
                        'success'
                    ).then(() => {
                        location.reload(); // Optionally, you can refresh the page
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'Error!',
                        'An error occurred while deleting the student.',
                        'error'
                    );
                }
            });
        }
    });
    });

</script>
<script>
    function confirmSwap(customerId) {
        const form = document.getElementById(`swap-seat-form-${customerId}`);
        const oldSeat = document.getElementById(`old-seat-${customerId}`).value;
        const newSeatSelect = document.getElementById(`new-seat-${customerId}`);
        const newSeat = newSeatSelect.options[newSeatSelect.selectedIndex].text;

        // Confirm message with old seat and new seat details
        const confirmation = confirm(`Are you sure you want to swap from seat ${oldSeat} to seat ${newSeat}?`);

        if (confirmation) {
            form.submit();
        } else {
            // Reset the dropdown to prevent accidental changes
            newSeatSelect.value = '';
        }
    }
</script>
<script>
    $(document).on('click', '.link-close-plan', function() {
        const customer_id = this.getAttribute('data-id');
        var url = '{{ route('learners.close') }}'; // Adjust the route as necessary

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, close it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',  // Use POST or PATCH for this type of operation
                    data: {
                        _token: '{{ csrf_token() }}',
                        customer_id: customer_id
                    },
                    success: function(response) {
                        Swal.fire(
                            'Closed!',
                            'The user plan has been closed.',
                            'success'
                        ).then(() => {
                            location.reload();  // Optionally reload the page after closing the plan
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(
                            'Error!',
                            'An error occurred while closing the plan.',
                            'error'
                        );
                    }
                });
            }
        });
    });
</script>
<script>
    let table = new DataTable('#datatable');
</script>
@endsection