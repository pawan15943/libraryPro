@extends('layouts.admin')
@section('content')


<div class="card mb-4">
    <div class="row">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <h4 class="mb-4">Add Features</h4>
        {{-- <form action="{{ route('permission-categories.storeOrUpdate', $category->id ?? null) }}" method="POST">
            @csrf
            @method('put')

            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" name="name" id="category_name" class="form-control" value="{{ $category->name ?? old('name') }}" required>
            </div>
            <div class="col-lg-3 mt-4">
                <button type="submit" class="btn btn-primary button">{{ isset($category) ? 'Update Category' : 'Add Category' }}</button>
            </div>
        </form> --}}
    </div>
   
</div>
<div class="row">
    <div class="col-lg-12">

        <div class="table-responsive">
            <table class="table text-center datatable dataTable" id="datatable">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Permission Name</th>
                        <th>Permission Category</th>
                        <th>About Permission</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                   
                </tbody>
            </table>
        </div>
   

    </div>
</div>
@include('master.script')

@endsection