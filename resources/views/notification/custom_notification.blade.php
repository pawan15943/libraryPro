@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<!-- Content -->
<form action="{{ route('notifications.send') }}" method="POST">
    @csrf
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="row g-4">
                    <!-- Title Input -->
                    <div class="col-lg-6">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <!-- Description Input -->
                    <div class="col-lg-6">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>

                    <!-- Link Input -->
                    <div class="col-lg-6">
                        <label for="link">Link</label>
                        <input type="url" class="form-control" id="link" name="link" >
                    </div>

                    <!-- Image URL Input -->
                    <div class="col-lg-6">
                        <label for="image">Image URL</label>
                        <input type="url" class="form-control" id="image" name="image">
                    </div>

                    <!-- Guard Selection -->
                    <div class="col-lg-6">
                        <label for="guard">Select Type</label>
                        <select class="form-control" id="guard" name="guard" required>
                            <option value="">Select</option>
                            <option value="web">Administrator</option>
                            <option value="library">Library</option>
                            <option value="learner">Learner</option>
                        </select>
                    </div>
                   
                </div>
                <div class="col-lg-6 mt-2">
                    <button type="submit" class="btn btn-primary">Send Notification</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Submit Button -->
    
</form>
</div>
@include('library.script')
@endsection