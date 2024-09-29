@extends('layouts.admin')
@section('content')

<!-- Content Header (Page header) -->
@php
      use Carbon\Carbon;
      $current_route = Route::currentRouteName();
@endphp

<div class="row">
    <div class="col-lg-12">
        <div class="filter-box">
            <h4 class="mb-3">Filter Box</h4>
           
            <form action="{{ route('learners') }}" method="GET">
                <div class="row">
                    <div class="col-lg-3">
                        <label for="">Filter By Plan</label>
                        <select name="plan_id" id="plan_id" class="form-select">
                            <option value="">Choose Plan</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="">Filter By Payment Status</label>
                        <select name="is_paid" id="is_paid" class="form-select">
                            <option value="">Choose Payment Status</option>
                            <option value="1">Paid</option>
                            <option value="0">Unpaid</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="">Filter By Active / Expired</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Choose Status</option>
                            <option value="active">Active</option>
                            <option value="expired">Expired</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="">Search By Name, Mobile &amp; Email</label>
                        <input type="text" class="form-control" name="search" placeholder="Enter Name, Mobile or Email">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-2">
                        <button class="btn btn-primary button"><i class="fa fa-search"></i> Search Records</button>
                    </div>
                </div>
            </form>
        </div>
        
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive mt-4">
            <table class="table text-center datatable" id="datatable">
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
                            <small class="text-success fs-10 d-block">Expires in {{ $diffInDays }} days</small>
                            @elseif ($diffInDays < 0)
                                <small class="text-danger fs-10 d-block">Expired {{ abs($diffInDays) }} days ago</small>
                            @else
                                <small class="text-warning fs-10 d-block">Expires today</small>
                            @endif
                        </td>
                        <td>
                            <button class="active-status">Active</button>
                        </td>
                        <td>
                            <ul class="actionalbls d-none">
                                <!-- View Seat Info -->
                                <li><a href="#" data-bs-toggle="tooltip"
                                        data-bs-title="View Seat Details"
                                        data-bs-placement="bottom"><i class="fas fa-eye"></i></a>
                                </li>

                                <!-- Edit Seat Info -->
                                <li><a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        title="" data-bs-title="Edit Seat Info"><i
                                            class="fas fa-edit"></i></a></li>

                                <!-- Swap Seat-->
                                <li><a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        title="" data-original-title="Swap Seat "><i
                                            class="fa-solid fa-arrow-right-arrow-left"></i></a></li>

                                <!-- Swap Seat-->
                                <li><a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        title="" data-original-title="Upgrade Plan"><i
                                            class="fa fa-arrow-up-short-wide"></i></a></li>
                                <!-- Reactive Seat-->
                                <li><a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        title="" data-original-title="Upgrade Plan"><i
                                            class="fa fa-refresh"></i></a></li>

                                <!-- Close Seat -->
                                <li><a href="#;" class="link-close-plan" data-id="11"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom" title=""
                                        data-original-title="Close Seat"><i
                                            class="fas fa-times"></i></a></li>

                                <!-- Deletr Seat -->
                                <li><a href="#" data-id="11" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" title="" class="delete-customer"
                                        data-original-title="Delete Lerners"><i
                                            class="fas fa-trash"></i></a></li>
                                <!-- Sent Mail -->
                                <li><a href="#" data-id="11" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" title="" class="delete-customer"
                                        data-original-title="Delete Lerners"><i
                                            class="fas fa-envelope"></i></a></li>
                                <!-- Sent Mail -->
                                <li><a href="#" data-id="11" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" title="" class="delete-customer"
                                        data-original-title="Delete Lerners"><i
                                            class="fa-brands fa-whatsapp"></i></a></li>
                            </ul>
                            <ul class="actionalbls">
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
                                 <!-- Sent Mail -->
                                 <li><a href="#" data-id="11" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" class="delete-customer" data-original-title="Delete Lerners"><i class="fas fa-envelope"></i></a></li>
                                <!-- Sent Mail -->
                                <li><a href="#" data-id="11" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" class="delete-customer" data-original-title="Delete Lerners"><i class="fa-brands fa-whatsapp"></i></a></li>
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
                            <small class="text-success fs-10 d-block">Expires in {{ $diffInDays }} days</small>
                            @elseif ($diffInDays < 0)
                                <small class="text-danger fs-10 d-block">Expired {{ abs($diffInDays) }} days ago</small>
                            @else
                                <small class="text-warning fs-10 d-block">Expires today</small>
                            @endif
                        </td>
                        <td>
                            <button class="active-status">Active</button>
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
        const learner_id = this.getAttribute('data-id');
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
                        learner_id: learner_id
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