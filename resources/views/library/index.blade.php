@extends('layouts.admin')
@section('content')

<!-- Breadcrumb -->
<div class="row">
    <div class="d-flex bradcrumb">
        <h4>Library List</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Library List</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="filter-box bg-white">
            <h4 class="mb-3">Filter Library By</h4>
            <form action="">
                <div class="row">
                    <div class="col-lg-3">
                        <label for="">Filter By Plan</label>
                        <select name="" id="" class="form-select">
                            <option value="">Choose Plan</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="">Filter By Payment Status</label>
                        <select name="" id="" class="form-select">
                            <option value="">Choose Payment Status</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="">Filter By Active / Expired</label>
                        <select name="" id="" class="form-select">
                            <option value="">Choose Payment Status</option>
                            <option value="">Active</option>
                            <option value="">Expired</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="">Search By Name, Mobile &amp; Email</label>
                        <input type="text" class="form-control" name="filter">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-2">
                        <button class="btn btn-primary button"><i class="fa fa-search"></i> Search
                            Records</button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="heading-list">
            <h4 class="">Library List </h4>
            <a href="{{route('library.create')}}" class="btn btn-primary button w-15"><i class="fa-solid fa-plus"></i> Add Library</a>
        </div>
        <div class="table-responsive mt-4">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Library Name</th>
                        <th>Contact Info</th>
                        <th>Plan</th>
                        <th>Starts On</th>
                        <th>Expired On</th>
                        <th style="width:30%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($libraries as $key => $value)


                    <tr>
                        <td>1</td>
                        <td><span class="uppercase truncate d-block m-auto" data-bs-toggle="tooltip" data-bs-title="Pawan Kumar Rathore" data-bs-placement="bottom"> {{$value->library_name}}</span>
                            <small>210202152</small>
                        </td>
                        <td><span class="truncate d-block m-auto" data-bs-toggle="tooltip" data-bs-title="{{$value->email}}" data-bs-placement="bottom"><i class="fa-solid fa-times text-danger"></i>
                                {{$value->email}}</span>
                            <small>+91-{{$value->library_mobile}}</small>
                        </td>
                        <td>BASIC PLAN<br>
                            <small>MONTHLY</small>
                        </td>
                        <td><span class="d-block m-auto">
                                30-04-2024
                            </span>
                            <small class="text-success">Active</small>
                        </td>
                        <td>30-04-2024<br>
                            <small class="text-success">20 Days Pending</small>
                        </td>

                        <td>
                            <ul class="actionalbls">
                                <!-- View Library Info -->
                                <li><a href="#" data-bs-toggle="tooltip" data-bs-title="View Library Details" data-bs-placement="bottom"><i class="fas fa-eye"></i></a>
                                </li>

                                <!-- Edit Library Info -->
                                <li><a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-title="Edit Library Info"><i class="fas fa-edit"></i></a></li>

                                <!-- Upgrde Plan-->
                                <li><a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-original-title="Upgrade Plan"><i class="fa fa-arrow-up-short-wide"></i></a></li>

                                <!-- Close Seat -->
                                <li><a href="#;" class="link-close-plan" data-id="11" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-original-title="Close Seat"><i class="fas fa-times"></i></a></li>

                                <!-- Deletr Seat -->
                                <li><a href="#" data-id="11" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" class="delete-customer" data-original-title="Delete Lerners"><i class="fas fa-trash"></i></a></li>

                                <!-- Make Payment -->
                                <li><a href="{{ route('library.payment', $value->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" class="delete-customer" data-original-title="Make Payment"> <i class="fas fa-credit-card"></i> </a></li>

                                <!-- Sent Mail -->
                                <li><a href="#" data-id="11" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" class="delete-customer" data-original-title="Delete Lerners"><i class="fas fa-envelope"></i></a></li>
                                <!-- Sent Mail -->
                                <li><a href="#" data-id="11" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" class="delete-customer" data-original-title="Delete Lerners"><i class="fa-brands fa-whatsapp"></i></a></li>
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection