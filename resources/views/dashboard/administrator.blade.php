@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<!-- Dahsboard Count -->
<div class="row">
    <div class="col-lg-3">
        <div class="adminCounts">
            <h4>Total Registration</h4>
            <h1>{{$totalregistration}}</h1>
            <a href="{{route('library.count.view', ['type' => 'total'])}}">View Details</a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="adminCounts">
            <h4>Total Paid</h4>
            <h1>{{$paidregistration}}</h1>
            <a href="{{route('library.count.view', ['type' => 'paid_registration'])}}">View Details</a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="adminCounts">
            <h4>Total Unpaid</h4>
            <h1>{{$unpaidregistration}}</h1>
            <a href="{{route('library.count.view', ['type' => 'unpaid_registration'])}}">View Details</a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="adminCounts">
            <h4>Pending for Renew</h4>
            <h1>{{$renewCount}}</h1>
            <a href="{{route('library.count.view', ['type' => 'pending_renew'])}}">View Details</a>
        </div>
    </div>
</div>
<div class="row align-items-center mt-4">
    <div class="col-lg-3">
        <h4>Filter Dashboard Data</h4>
    </div>
    <div class="col-lg-3"></div>
    <div class="col-lg-3">
        <select id="datayaer" class="form-select form-control-sm">
            <option value="">Select Year</option>
        </select>
    </div>

    <div class="col-lg-3">
        <select id="dataFilter" class="form-select form-control-sm">
            <option value="">Select Month</option>
        </select>
    </div>

</div>

<div class="row mt-4">
    <div class="col-lg-3">
        <div class="adminCounts">
            <h4>Total Revenue (A + B + C)</h4>
            <h1>20,200</h1>
            <a href="{{route('library.count.view', ['type' => 'pending_renew'])}}">View Details</a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="adminCounts basic">
            <h4>Basic Plan Booked (A)</h4>
            <h1>{{$renewCount}}</h1>
            <a href="{{route('library.count.view', ['type' => 'pending_renew'])}}">View Details</a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="adminCounts standard">
            <h4>Standard Plan Booked (B)</h4>
            <h1>{{$renewCount}}</h1>
            <a href="{{route('library.count.view', ['type' => 'pending_renew'])}}">View Details</a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="adminCounts premium">
            <h4>Premium Plan Booked (C)</h4>
            <h1>{{$renewCount}}</h1>
            <a href="{{route('library.count.view', ['type' => 'pending_renew'])}}">View Details</a>
        </div>
    </div>
</div>
<div class="dashboard">


    <div class="row mt-4 mb-4">
        <div class="col-lg-6">
            <h4 class="mb-4">New Registrations</h4>
            <div class="table-responsive">
                <table class="table text-center datatable border-bottom">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Library Name</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>ABC Library</td>
                            <td>Basic Plan</td>
                            <td>Paid</td>
                            <td>
                                <ul class="actionalbls">
                                    <li>
                                        <a href=""><i class="fa fa-eye"></i></a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>ABC Library</td>
                            <td>Basic Plan</td>
                            <td>Paid</td>
                            <td>
                                <ul class="actionalbls">
                                    <li>
                                        <a href=""><i class="fa fa-eye"></i></a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>ABC Library</td>
                            <td>Basic Plan</td>
                            <td>Paid</td>
                            <td>
                                <ul class="actionalbls">
                                    <li>
                                        <a href=""><i class="fa fa-eye"></i></a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>ABC Library</td>
                            <td>Basic Plan</td>
                            <td>Paid</td>
                            <td>
                                <ul class="actionalbls">
                                    <li>
                                        <a href=""><i class="fa fa-eye"></i></a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>ABC Library</td>
                            <td>Basic Plan</td>
                            <td>Paid</td>
                            <td>
                                <ul class="actionalbls">
                                    <li>
                                        <a href=""><i class="fa fa-eye"></i></a>
                                    </li>
                                </ul>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-6">
            <h4 class="mb-4">Upcming Renewal</h4>
            <div class="table-responsive">
                <table class="table text-center datatable border-bottom">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Library Name</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>ABC Library</td>
                            <td>Basic Plan</td>
                            <td>Paid</td>
                            <td>
                                <ul class="actionalbls">
                                    <li>
                                        <a href=""><i class="fa fa-eye"></i></a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>ABC Library</td>
                            <td>Basic Plan</td>
                            <td>Paid</td>
                            <td>
                                <ul class="actionalbls">
                                    <li>
                                        <a href=""><i class="fa fa-eye"></i></a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>ABC Library</td>
                            <td>Basic Plan</td>
                            <td>Paid</td>
                            <td>
                                <ul class="actionalbls">
                                    <li>
                                        <a href=""><i class="fa fa-eye"></i></a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>ABC Library</td>
                            <td>Basic Plan</td>
                            <td>Paid</td>
                            <td>
                                <ul class="actionalbls">
                                    <li>
                                        <a href=""><i class="fa fa-eye"></i></a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>ABC Library</td>
                            <td>Basic Plan</td>
                            <td>Paid</td>
                            <td>
                                <ul class="actionalbls">
                                    <li>
                                        <a href=""><i class="fa fa-eye"></i></a>
                                    </li>
                                </ul>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection