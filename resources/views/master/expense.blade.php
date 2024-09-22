@extends('layouts.admin')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="row">
    <!-- Page Main Content -->
    <div class="col-lg-12">

        <div class="card card-default">

            <!-- Add Menus Fields -->
            <div class="card-body">
                <form id="submit" action="{{ isset($expense) && $expense->id ? route('expense.update', $expense->id) : route('expense.store') }}" method="post">
                    @csrf
                    @if(isset($expense) && $expense->id)
                    @method('PUT')
                    @endif

                    @if(session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                    @endif

                    <div class="row g-4">
                        @if(isset($expense) && $expense->id)
                        <input type="hidden" name="id" value="{{ $expense->id }}" id="expense_id">
                        @endif

                        <div class="col-lg-6">
                            <label for="class_name"> Expense Name<sup class="text-danger">*</sup> </label>
                            <input type="text" id="name" name="name" value="{{ old('name', isset($expense) && $expense->name ? $expense->name : '') }}" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-3 mt-4">
                            <button type="submit" class="btn btn-primary btn-block">{{ isset($menu) && $menu->id ? 'Update Expanse' : 'Add Expanse' }}</button>
                        </div>
                    </div>

                </form>
            </div>
            <!-- end -->

            <!-- Add Menus List -->
            <div class="card-body p-0">
                <h4 class="px-3 py-2">All Expenses List</h4>
                <div class="table-responsive tableRemove_scroll mt-2">
                    <table class="table table-hover dataTable m-0" id="datatable" style="display:table !important">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Menu Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $x = 1;
                            @endphp
                            @foreach($expenses as $key => $value)
                            <tr>
                                <td>{{ $x++ }}</td>
                                <td>{{ $value->name }}</td>
                                <td><span class="status-column">
                                        {{ $value->trashed() ? 'Inactive' : 'Active' }}
                                    </span></td>
                                <td style="width: 20%">
                                    <ul class="actionables">
                                        <li><a href="{{ route('expense.edit', $value->id) }}" class="btn tooltips btn-default p-2 btn-sm rounded mr-2" title="Edit Expense"><i class="fas fa-edit"></i></a></li>
                                        <li> <a href="#" class="active-deactive" data-id="{{ $value->id }}" data-table="Expense" title="Active/Deactive">

                                                @if($value->trashed())
                                                <i class="fas fa-check"></i>
                                                @else
                                                <i class="fas fa-ban"></i>
                                                @endif
                                            </a></li>
                                        <li>
                                            <form method="POST" action="{{ route('expense.destroy', $value->id) }}" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="border: none; background: none; cursor: pointer;" onclick="return confirm('Are you sure you want to delete this expense?');">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </li>


                                    </ul>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end -->

        </div>
    </div>
</div>
@include('master.script')
@endsection