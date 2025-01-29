@extends('layouts.admin')
@section('content')


<div class="card">
    <div class="row">

        <div class="form-group">
            <label for="category_name">Select Month</label>
            <select name="request_name" class="form-select">
                <option value="">Select Month</option>
            </select>

        </div>

        <div class="col-lg-3 mt-4">
            <button type="submit" class="btn btn-primary button">Search</button>
        </div>
        
    </div>

</div>
<h4 class="py-4">My Attendance : FULL DAY (06:00 AM to 10:00 PM)</h4>
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table text-center datatable dataTable" id="datatable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Attandance</th>
                        <th>In Time</th>
                        <th>Out Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>29 Jan 2025</td>
                        <td>Present</td>
                        <td>07:10:00 AM</td>
                        <td>09:00:00 AM</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection