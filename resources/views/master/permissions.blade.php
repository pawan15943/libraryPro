@extends('layouts.admin')
@section('content')


<div class="card mb-4">
    <div class="row">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('permissions.storeOrUpdate', $permission->id ?? null) }}" method="POST">
            @csrf
            @method('put')
            <div class="row">
                <div class="col-lg-4">
                    <label for="name">Permission Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $permission->name ?? old('name') }}" required>
                </div>
                <div class="col-lg-4">
                    <label for="guard_name">Select Guard</label>
                    <select name="guard_name" id="guard_name" required class="form-control">
                        <option value="web" {{ (isset($permission) && $permission->guard_name == 'web') ? 'selected' : '' }}>Web</option>
                        <option value="library" {{ (isset($permission) && $permission->guard_name == 'library') ? 'selected' : '' }}>Library</option>
                        <option value="learner" {{ (isset($permission) && $permission->guard_name == 'learner') ? 'selected' : '' }}>Learner</option>

                    </select>
                </div>
                <div class="col-lg-4">
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
            <table class="table text-center datatable">
                <tr>
                    <th>Permission Name</th>
                    <th>Action</th>
                </tr>
                @foreach($permissions as $key => $value)
                <tr>
                    <td>
                        {{$value->name}}
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



@include('master.script')

@endsection