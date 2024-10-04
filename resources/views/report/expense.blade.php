@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="row">
        <div class="col-lg-6">
            <h4>Current Month Revenue for {{ $month }} ({{ $year }})</h4>
        </div>
        <div class="col-lg-6">
            <h4><i class="fa fa-inr"></i> {{ number_format($library_revenue, 2) }}</h4>
        </div>
    </div>
    <form id="expense-form" action="{{ route('report.expense.store') }}" method="POST">
        @csrf
        <input type="hidden" name="year" value="{{ $year }}">
        <input type="hidden" name="month" value="{{ $month }}">

        @if($monthlyExpenses->isEmpty())
        <!-- No existing expenses, show empty form fields with plus button -->
        <div class="row mt-3">
            <div class="col-lg-6">
                <select class="form-control" name="expense_id[]">
                    <option value="">Select Expense</option>
                    @foreach($expenses as $expense)
                    <option value="{{ $expense->id }}">{{ $expense->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-5">
                <input type="text" placeholder="Amount" class="form-control" name="amount[]">
            </div>
            <div class="col-lg-1 d-inline">
                <button id="add_row" class="btn btn-primary button" type="button"><i class="fa fa-plus"></i></button>
            </div>
        </div>
        @else
        <!-- Populate form with existing expenses -->
        @foreach($monthlyExpenses as $key => $expense)
        <div class="row mt-3">
            <div class="col-lg-6">
                <select class="form-control" name="expense_id[]">
                    <option value="">Select Expense</option>
                    @foreach($expenses as $exp)
                    <option value="{{ $exp->id }}" {{ $exp->id == $expense->expense_id ? 'selected' : '' }}>
                        {{ $exp->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-5">
                <input type="text" placeholder="Amount" class="form-control" name="amount[]" value="{{ $expense->amount }}">
            </div>
            <div class="col-lg-1 d-inline">
                @if($key == count($monthlyExpenses) - 1)
                <!-- Plus button on the last row -->
                <button id="add_row" class="btn btn-primary" type="button"><i class="fa fa-plus"></i></button>
                @else
                <!-- Delete button on all other rows -->
                <button class="btn btn-danger delete-row" type="button"><i class="fa fa-trash text-white"></i></button>
                @endif
            </div>
        </div>
        @endforeach
        @endif

        <div class="row-container"></div>

        <div class="row mt-3">
            <div class="col-lg-3">
                <input type="submit" class="btn btn-primary button" value="Submit">
            </div>
        </div>
    </form>

    <div class="row mt-3">
        <div class="col-lg-12">
            <h4 class="mb-4 mt-4">Monthly Expense</h4>
            <div class="table-responsive tableRemove_scroll mt-2">
                <table class="table text-center datatable" id="datatable" style="display: table !important;">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Month</th>
                            <th>Expense Name</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $x=1;
                        @endphp
                        @foreach($revenue_expense as $expense)
                        @php
                        $dt = DateTime::createFromFormat('!m', $month);
                        @endphp
                        <tr>
                            <td>{{$x++}}</td>
                            <td>{{ $dt->format('F') }}, {{$year }} </td>
                            <td>{{ $expense->expense_name }}</td>
                            <td>{{ $expense->amount }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Function to add a new row
        $('#add_row').on('click', function() {
            var expenseOptions = `
            @foreach($expenses as $expense)
                <option value="{{ $expense->id }}">{{ $expense->name }}</option>
            @endforeach
        `;

            var newRow = `
        <div class="row mt-3">
            <div class="col-lg-6">
                <select class="form-control" name="expense_id[]">
                    <option value="">Select Expense</option>
                    ${expenseOptions}
                </select>
            </div>
            <div class="col-lg-5">
                <input type="text" placeholder="Amount" class="form-control" name="amount[]">
            </div>
            <div class="col-lg-1 d-inline">
                <button class="btn btn-danger delete-row" type="button"><i class="fa fa-trash text-white"></i></button>
            </div>
        </div>
        `;
            // Append the new row to the row-container
            $('.row-container').append(newRow);
        });

        // Function to delete a row
        $(document).on('click', '.delete-row', function() {
            $(this).closest('.row').remove();
        });
    });
</script>
@endsection