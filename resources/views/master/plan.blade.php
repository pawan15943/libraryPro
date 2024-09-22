@extends('layouts.admin')
@section('content')

<!-- Main content -->

<div class="row">
    <!-- Page Main Content -->
    <div class="col-lg-12">
        <!-- Genral Information -->
        <div class="card card-default">

            <!-- Add Plan -->
            <div class="card-body">
                <form action="{{ isset($plan) ? route('plan.update', $plan->id) : route('plan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($plan))
                    @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-lg-6">
                            <label> Plan Name<sup class="text-danger">*</sup></label>
                            <select class="form-control @error('plan_id') is-invalid @enderror" name="plan_id">
                                <option value="">Select Plan</option>
                                <option value="1" {{ old('plan_id', isset($plan) ? $plan->plan_id : '') == 1 ? 'selected' : '' }}>1 MONTH</option>
                                <option value="3" {{ old('plan_id', isset($plan) ? $plan->plan_id : '') == 3 ? 'selected' : '' }}>3 MONTHS</option>
                                <option value="6" {{ old('plan_id', isset($plan) ? $plan->plan_id : '') == 6 ? 'selected' : '' }}>6 MONTHS</option>
                                <option value="9" {{ old('plan_id', isset($plan) ? $plan->plan_id : '') == 9 ? 'selected' : '' }}>9 MONTHS</option>
                                <option value="12" {{ old('plan_id', isset($plan) ? $plan->plan_id : '') == 12 ? 'selected' : '' }}>12 MONTHS</option>
                            </select>
                            @error('plan_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3">
                            <label></label>
                            <input type="submit" class="btn btn-primary btn-block" value="{{isset($plan) ? 'Edit Plan' :'Add Plan'}}">
                        </div>
                    </div>

                </form>
            </div>
            <!-- end -->

            <!-- All Plans List -->
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="px-3">All Plans List</h4>
                        <div class="table-responsive tableRemove_scroll mt-2">
                            <table class="table dataTable border-0 m-0" id="datatable" style="display:table !important">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Plan Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $x = 1;
                                    @endphp
                                    @foreach($plans as $key => $value)
                                    <tr>
                                        <td>{{$x++}}</td>
                                        <td class="w-75">{{$value->name}}</td>
                                        <td><span class="status-column">
                                                {{ $value->trashed() ? 'Inactive' : 'Active' }}
                                            </span></td>
                                        <td>
                                        <ul class="actionables">
                                            <li>
                                            <a href="{{route('plan.edit', $value->id)}}" title="Edit Plan"><i class="fas fa-edit"></i></a></li>
                                            <li><a href="#" class="active-deactive" data-id="{{ $value->id }}" data-table="Plan" title="Active/Deactive">
                                            @if($value->trashed())
                                                <i class="fas fa-check"></i>
                                            @else
                                            <i class="fas fa-ban"></i>
                                            @endif</a>
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
            <!-- end -->
        </div>

    </div>

</div>



<!-- /.content -->
@include('master.script')
@endsection