@extends('layouts.admin')
@section('content')


<div class="card mb-4">
    <div class="row">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <h4 class="mb-4">Add Permissions Category</h4>
        <form action="{{ route('permission-categories.storeOrUpdate', $category->id ?? null) }}" method="POST">
            @csrf
            @method('put')

            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" name="name" id="category_name" class="form-control" value="{{ $category->name ?? old('name') }}" required>
            </div>
            <div class="col-lg-3 mt-4">
                <button type="submit" class="btn btn-primary button">{{ isset($category) ? 'Update Category' : 'Add Category' }}</button>
            </div>
        </form>
    </div>
    <h4 class="py-4">Add Portal Permisions</h4>
    <div class="row">
        <form action="{{ route('permissions.storeOrUpdate', $permission->id ?? null) }}" method="POST">
            @csrf
            @method('put')
            <div class="row g-4">
                <div class="col-lg-3">
                    <label for="category">Select Permission Category</label>
                    <select name="permission_category_id" id="category" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ (isset($permission) && $permission->permission_category_id == $category->id) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3">
                    <label for="name">Permission Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $permission->name ?? old('name') }}" required id="permission">
                </div>
                <div class="col-lg-3">
                    <label for="name">Permission Slug</label>
                    <input type="text" name="slug" readonly class="form-control" id="slug" value="{{ $permission->name ?? old('name') }}" required>
                </div>
                <div class="col-lg-3">
                    <label for="guard_name">Select Guard</label>
                    <select name="guard_name" id="guard_name" required class="form-select">
                        <option value="web" {{ (isset($permission) && $permission->guard_name == 'web') ? 'selected' : '' }}>Web</option>
                        <option value="library" {{ (isset($permission) && $permission->guard_name == 'library') ? 'selected' : '' }}>Library</option>
                        <option value="learner" {{ (isset($permission) && $permission->guard_name == 'learner') ? 'selected' : '' }}>Learner</option>

                    </select>
                </div>
                <div class="col-lg-12">
                    <label for="description">Description (optional)</label>
                    <input type="text" name="description" class="form-control" value="{{ $permission->description ?? old('description') }}">
                </div>
            </div>

            <div class="col-lg-3 mt-4">
                <button type="submit" class="btn btn-primary button">{{ $permission ? 'Update Permission' : 'Add Permission' }}</button>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table text-center datatable dataTable" id="datatable">
                <tr>
                    <th>S.No.</th>

                    <th>Permission Name</th>
                    <th>Permission Category</th>
                    <th>About Permision</th>
                    <th>Action</th>
                </tr>
                @php
                $x=1;
                @endphp
                @foreach($permissions as $key => $value)
                <tr>
                    <td>{{$x++}}</td>


                    <td>
                        {{$value->name}}
                        <small>{{$value->slug}}</small>
                    </td>
                    <td>
                        {{ $value->permission_category_id ? \App\Models\PermissionCategory::find($value->permission_category_id)->name : 'No Category' }}
                    </td>
                    <td class="w-25">
                        <code>{{$value->description}}</code>
                    </td>
                    <td>
                        <ul class="actionalbls">
                            <li><a href="{{ route('permissions.storeOrUpdate', $value->id) }}"><i class="fa fa-edit"></i></a></li>
                            <li>
                                <form action="{{ route('permissions.delete', $value->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <i class="fa fa-trash"></i>
                                </form>
                            </li>
                        </ul>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#permission').on('keyup', function() {
            var permissionName = $(this).val();
            var slug = permissionName
                .toLowerCase() // Convert to lowercase
                .replace(/ /g, '-') // Replace spaces with dashes
                .replace(/[^\w-]+/g, ''); // Remove special characters

            $('#slug').val(slug); // Set the slug value
        });
    });
</script>
@include('master.script')

@endsection