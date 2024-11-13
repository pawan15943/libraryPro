@extends('layouts.admin')
@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<form action="{{ url('change-password') }}" method="POST">
    @csrf
    <input type="password" name="current_password" class="form-control  @error('current_password') is-invalid @enderror" placeholder="Current Password" required>
    @error('current_password')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
    <input type="password" name="new_password" class="form-control  @error('new_password') is-invalid @enderror" placeholder="New Password" required>
    @error('new_password')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
    <input type="password" name="new_password_confirmation"  placeholder="Confirm New Password" required>
    <button type="submit">Change Password</button>
</form>


@endsection