@extends('layouts.admin')
@section('content')

<!-- Content Header (Page header) -->
@php
      use Carbon\Carbon;
      $current_route = Route::currentRouteName();
@endphp

<!-- Main row -->
<div class="row ">
    <!-- Main Info -->
    <div class="col-lg-12 ">
        <!-- Add Document -->
        <div class="card card-default main_card_content">
            <!-- /.card-header -->
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-12">
                         <div class="table-responsive tableRemove_scroll mt-2">
                            <table class="table table-hover data-table" id="datatable">
                                <thead>
                                    <tr class="text-center">
                                        <th style="width: 20% ">Seat</th>
                                        <th style="width: 20% ">Name</th>
                                        <th style="width: 20% ">Mobile</th>
                                        <th style="width: 20% ">Email</th>
                                        <th style="width: 20% ">Plan</th>
                                        <th style="width: 20% ">Starts On</th>
                                        <th style="width: 20% ">Ends On</th>
                                        <th style="width: 20% ">Action</th>
                                    </tr>
                                </thead>
                                
                                @if($current_route=='learners')
                                <tbody>
                                    @foreach($customers as $key => $value)
                                    @php
                                        $today = Carbon::today();
                                        $endDate = Carbon::parse($value->plan_end_date);
                                        $diffInDays = $today->diffInDays($endDate, false);
                                    @endphp
                                    <tr class="text-center">
                                        <td>{{$value->seat_no}}</td>
                                        <td style="width: 20%;"> <span class="truncate uppercase">{{$value->name}}</span></td>
                                        <td> +91-{{$value->mobile}}</td>
                                        <td title="{{$value->email }}"><span class="badge badge-success truncate"> {{$value->email }}</span></td>
                                        <td style="width: 15%;"> {{$value->plan_type_name}}<br>
                                        <small class="text-info">{{$value->plan_name}}</small></td>
                                        <td style="width: 10%;"> {{$value->plan_start_date}}</td>
                                        <td style="width: 13%;"> {{$value->plan_end_date}}
                                            @if ($diffInDays > 0)
                                            <small class="text-success fs-10 d-block">Expires in {{ $diffInDays }} days</small>
                                            @elseif ($diffInDays < 0)
                                                <small class="text-danger fs-10 d-block">Expired {{ abs($diffInDays) }} days ago</small>
                                            @else
                                                <small class="text-warning fs-10 d-block">Expires today</small>
                                            @endif
                                           
                                        </td>
                                        
                                        <td style="width: 20%;">
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
                                    <tr class="text-center">
                                        <td>{{$value->seat_no}}</td>
                                        <td style="width: 20%;"> <span class="truncate uppercase">{{$value->name}}</span></td>
                                        <td> +91-{{$value->mobile}}</td>
                                        <td title="{{$value->email }}"><span class="badge badge-success truncate"> {{$value->email }}</span></td>
                                        <td style="width: 15%;"> {{$value->plan_type_name}}<br>
                                        <small class="text-info">{{$value->plan_name}}</small></td>
                                        <td style="width: 10%;"> {{$value->plan_start_date}}</td>
                                        <td style="width: 13%;"> {{$value->plan_end_date}}
                                            @if ($diffInDays > 0)
                                            <small class="text-success fs-10 d-block">Expires in {{ $diffInDays }} days</small>
                                            @elseif ($diffInDays < 0)
                                                <small class="text-danger fs-10 d-block">Expired {{ abs($diffInDays) }} days ago</small>
                                            @else
                                                <small class="text-warning fs-10 d-block">Expires today</small>
                                            @endif
                                           
                                        </td>
                                        <td style="width: 20%;">
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
            </div>
            <!-- /.card-body -->
        </div>

    </div>

</div>
<!-- /.row (main row) -->

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

@endsection