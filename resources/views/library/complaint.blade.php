@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<div class="row justify-content-center mb-4">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table text-center datatable" id="datatable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Learner Name</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Response</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                  
                    @foreach($data as $key => $value)
                  
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$value->learner_name}}</td>
                       
                        <td> {{$value->title}}</td>
                        <td> {{$value->description}}</td>
                        <td>
                            @if($value->status==1)
                            <span class="text-success">Resolved</span>
                            @elseif($value->status==2)
                            <span class="text-danger">Reject</span>
                            @elseif($value->status==3)
                            <span class="text-danger">Clarification</span>
                            @else
                            <span class="text-info">Pending</span>
                            @endif

                           
                        </td>
                        <td>{{$value->response}}</td>
                      
                        <td>
                            <ul class="actionalbls">
                                <li>
                                    <select id="statusDropdown" class="form-control" data-row-id="{{$value->id}}">
                                        <option value="">Select Status</option>
                                        <option value="1">Resolved</option>
                                        <option value="2">Reject</option>
                                        <option value="3">Clarification</option>
                                    </select>
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
<div id="clarificationModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h4>Provide Remarks</h4>
        <textarea id="remarkText" rows="4" cols="50"></textarea>
        <br>
        <button id="submitStatus" class="btn btn-primary">Submit</button>
    </div>
</div>
@include('library.script')
@endsection