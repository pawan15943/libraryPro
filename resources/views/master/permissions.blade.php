@extends('layouts.admin')
@section('content')


<div class="row">
    <h2>Manage Permissions</h2>

    {{-- Display success message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Add/Edit Permission Form --}}
    <form action="{{ route('permissions.storeOrUpdate', $permission->id ?? null) }}" method="POST">
        @csrf
       @method('put')
        <div class="form-group">
            <label for="name">Permission Name</label>
            <input type="text" name="name" class="form-control" value="{{ $permission->name ?? old('name') }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description (optional)</label>
            <input type="text" name="description" class="form-control" value="{{ $permission->description ?? old('description') }}">
        </div>
        <div class="form-select">
            <label for="guard_name">Select Guard</label>
            <select name="guard_name" id="guard_name" required  class="form-control">
                <option value="web" {{ (isset($permission) && $permission->guard_name == 'web') ? 'selected' : '' }}>Web</option>
                <option value="library" {{ (isset($permission) && $permission->guard_name == 'library') ? 'selected' : '' }}>Library</option>
                <option value="learner" {{ (isset($permission) && $permission->guard_name == 'learner') ? 'selected' : '' }}>Learner</option>
               
            </select>
        </div>
        <div class="col-lg-3 m-4">
        <button type="submit" class="btn btn-primary button">{{ $permission ? 'Update Permission' : 'Add Permission' }}</button>
        </div>
    </form>

    <hr>
</div>
<div>
<table>
    @foreach($permissions as $key => $value)
    <tr>
        <td>
            {{$value->name}}
        </td>
        <td>
            <a href="{{ route('permissions.storeOrUpdate', $value->id) }}" class="btn btn-sm btn-warning">Edit</a>
            <td>
                                
                <form action="{{ route('permissions.delete', $value->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </td>
    </tr>
        
    @endforeach
</table>
    
</div>

  

@include('master.script')

@endsection