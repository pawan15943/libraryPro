@extends('sitelayouts.layout')
@section('content')
<div class="container">
    <div class="card shadow-lg p-4">
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="{{ asset($library->library_logo) }}" alt="Library Logo" class="img-fluid rounded-circle" width="150">
            </div>
            <div class="col-md-8">
                <h2 class="text-primary">{{ $library->library_name }}</h2>
                <p><strong>Library Number:</strong> {{ $library->library_no }}</p>
                <p><strong>Type:</strong> {{ $library->subscription->name }}</p>
                <p><strong>Address:</strong> {{ $library->library_address }}, {{ $library->city->name }}, {{ $library->state->name }} - {{ $library->library_zip }}</p>
                <p><strong>Email:</strong> {{ $library->email }}</p>
                <p><strong>Phone:</strong> {{ $library->library_mobile }}</p>
                <p><strong>Google Map:</strong> {!! $library->google_map ? $library->google_map : 'Not Available' !!}</p>
            </div>
        </div>
        
        <hr>

        <h3 class="mt-4">Owner Details</h3>
        <p><strong>Name:</strong> {{ $library->library_owner }}</p>
        <p><strong>Email:</strong> {{ $library->library_owner_email }}</p>
        <p><strong>Contact:</strong> {{ $library->library_owner_contact }}</p>

        <hr>

        <h3 class="mt-4">Additional Information</h3>
        <p><strong>Account Status:</strong> {{ $library->status ? 'Active' : 'Inactive' }}</p>
        <p><strong>Paid Subscription:</strong> {{ $library->is_paid ? 'Yes' : 'No' }}</p>
        <p><strong>Profile Completed:</strong> {{ $library->is_profile ? 'Yes' : 'No' }}</p>
        <p><strong>Email Verified:</strong> {{ $library->email_verified_at ? 'Yes ('.$library->email_verified_at.')' : 'No' }}</p>

       
        <div class="col-lg-12 mt-4">
            <h4 class="mb-4">Library Features</h4>
            @php
                $selectedFeatures = $library->features ? json_decode($library->features, true) : [];
            @endphp
            <ul class="libraryFeatures">
                @foreach ($features as $feature)
                    @if (in_array($feature->id, $selectedFeatures ?? []))
                        <li>
                            <img src="{{ asset('public/'.$feature->image) }}" alt="Feature Image" width="50">
                            <span>{{ $feature->name }}</span>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        
        
    </div>
</div>
@endsection