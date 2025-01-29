@extends('layouts.admin')
@section('content')


<div class="card">
    <div class="row g-4">

        <h4>Add Complaint</h4>

        <div class="col-lg-12">
            <input type="text" class="form-control" placeholder="Enter Title">
        </div>

        <div class="col-lg-12">
            <textarea name="" class="form-control" id="" placeholder="Enter your description"></textarea>
        </div>

        <div class="col-lg-12">
            <input type="file" name="" class="form-control" id="">
            <div class="screenshot"></div>
        </div>

        <div class="col-lg-3 mt-4">
            <button type="submit" class="btn btn-primary button">Submit</button>
        </div>
    </div>
</div>
<h4 class="py-4">Complaints List</h4>
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table text-center datatable dataTable" id="datatable">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Library Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($learner_request ))
                    @foreach($learner_request as $index => $value)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $value->request_name }}</td>
                        <td>{{$value->request_date}}</td>
                        <td>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="4">No Data Found</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection