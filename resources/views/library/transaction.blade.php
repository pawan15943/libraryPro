@extends('layouts.admin')

@section('content')


<!-- Breadcrumb -->

<div class="row justify-content-center mb-4">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table text-center datatable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Plan Name</th>
                        <th>Price</th>
                        <th>Payment Method</th>
                        <th>Paid On</th>
                        <th>Trxn Id</th>
                        <th>Trxn Date</th>
                        <th>Trxn Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($transaction as $key => $value)
                    <tr>
                        <td>1</td>
                        <td>{{$value->paid_amount}}</td>
                        <td>{{$value->paid_amount}}</td>
                        <td>{{$value->payment_mode ? $value->payment_mode : 'NA'}}</td>
                        <td>{{$value->paid_amount}}</td>
                        <td>{{$value->transaction_id ? $value->transaction_id : 'NA'}}</td>
                        <td>{{$value->transaction_date}}</td>
                        <td>@if($value->is_paid==1)
                            <span>Success</span>
                            @else
                            <span>Unsuccess</span>
                            @endif
                        </td>
                        <td>
                            <ul class="actionalbls">
                                <li><a href=""><i class="fa fa-print"></i></a></li>
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