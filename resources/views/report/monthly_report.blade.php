@extends('layouts.admin')
@section('content')
<div class="row">
    <!-- Page Main Content -->
    <div class="col-lg-12">
        <!-- General Information -->
        <div class="card card-default">

            <!-- Add Plan -->
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-12">
                        
                        <div class="table-responsive tableRemove_scroll mt-2">
                            <table class="table dataTable border-0 m-0" id="datatable" style="display: table !important;">
                                <thead>
                                    <tr>
                                        <th>Month Name(year)</th>
                                        <th>Revenue</th>
                                        <th>total Expense</th>
                                        <th>Profit</th>
                                        <th>Expense ADD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reportData as $key => $value)
                                        <tr>
                                            <td><i class="fa fa-calendar"></i> {{ $value['month'] }} ({{ $value['year'] }})</td>
                                            <td><i class="fa fa-inr"></i> {{ number_format($value['total_revenue'], 2) }}</td>
                                            <td><i class="fa fa-inr"></i> {{ number_format($value['total_expenses'], 2) }}</td>
                                            <td><i class="fa fa-inr"></i> {{ number_format($value['total_revenue'] - $value['total_expenses'], 2) }}</td>
                                            <td>
                                                <ul class="actionables">
                                                    <li>
                                                        <a href="{{ route('report.expense', ['year' => $value['year'], 'month' => $value['month']]) }}" title="Add Expense">
                                                            <i class="fas fa-plus"></i>
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
        </div>
    </div>
</div>


@endsection
