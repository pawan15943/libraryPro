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
                <form action="{{ isset($hour) ? route('hour.update', $hour->id) : route('hour.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($hour))
                    @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-lg-12">
                            <label> Total Operating Hours<sup class="text-danger">*</sup></label>
                            <select class="form-control @error('hourse') is-invalid @enderror" name="hourse">
                                <option value="">Select Plan</option>
                                <option value="16" {{ old('hourse', isset($hour) ? $hour->hour : '') == 16 ? 'selected' : '' }}>16</option>
                                <option value="14" {{ old('hourse', isset($hour) ? $hour->hour : '') == 14 ? 'selected' : '' }}>14</option>
                                <option value="12" {{ old('hourse', isset($hour) ? $hour->hour : '') == 12 ? 'selected' : '' }}>12</option>
                            </select>
                            @error('hourse')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <small class="text-danger font-weight-600"><i class="fa fa-info-circle"></i> Please note: You can only add your library operating hours once. Adding multiple entries may cause unexpected behavior or errors.</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3">
                            <label></label>
                            <input type="submit" class="btn btn-primary btn-block" value="{{isset($hour) ? 'Edit Operating Hours' :'Add Operating Hours'}}">
                        </div>
                    </div>

                </form>
            </div>
            <!-- end -->

            <!-- All Plans List -->
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="px-3">Library Operating Hours</h4>
                        <div class="table-responsive tableRemove_scroll mt-2">
                            <table class="table dataTable border-0 m-0" id="datatable" style="display:table !important">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Operating Hours</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $x = 1;
                                    @endphp
                                    @foreach($hourse as $key => $value)
                                    <tr>
                                        <td>{{$x++}}</td>
                                        <td class="w-75">{{$value->hour}}</td>

                                        <td>
                                            <ul class="actionables">
                                                <li><a href="{{route('hour.edit', $value->id)}}" title="Edit Hour"><i class="fas fa-edit"></i></a></li>
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