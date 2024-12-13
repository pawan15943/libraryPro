@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<!-- Dahsboard Count -->
<div class="row">
    <div class="col-lg-3">
        <div class="adminCounts">
            <h4>Total Registration</h4>
            <h1>{{$totalregistration}}</h1>
            <a href="{{route('library.list.view', ['type' => 'total'])}}">View Details</a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="adminCounts">
            <h4>Total Paid</h4>
            <h1>{{$paidregistration}}</h1>
            <a href="{{route('library.list.view', ['type' => 'paid_registration'])}}">View Details</a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="adminCounts">
            <h4>Total Unpaid</h4>
            <h1>{{$unpaidregistration}}</h1>
            <a href="{{route('library.list.view', ['type' => 'unpaid_registration'])}}">View Details</a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="adminCounts">
            <h4>Pending for Renew</h4>
            <h1>{{$renewCount}}</h1>
            <a href="{{route('library.list.view', ['type' => 'pending_renew'])}}">View Details</a>
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
        <div class="col-lg-6">
            <h4 class="mb-4">Upcming ReNewal</h4>
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