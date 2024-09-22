@extends('layouts.admin')

@section('content')

<!-- Breadcrumb -->
<div class="row">
    <div class="d-flex bradcrumb">
        <h4>Add Library Plan</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Library Plan</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Add Library Plan -->
<div class="card">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Create Subscription -->
    <h4 class="mb-3">Create New Subscription</h4>
    <form action="{{ route('subscriptions.store') }}" class="validateForm" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-4">
                <label for="subscription">Subscription Name <span>*</span></label>
                <input type="text" class="form-control char-only" name="name">
            </div>
            <div class="col-lg-4">
                <label for="subscription">Monthly Plan Price <span>*</span></label>
                <input type="text" class="form-control digit-only" name="monthly_fees">
            </div>
            <div class="col-lg-4">
                <label for="subscription">Yearly Plan Price</label>
                <input type="text" class="form-control digit-only" name="yearly_fees">
            </div>
            <div class="col-lg-3">
                <button type="submit" class="btn btn-primary button">Create Subscription</button>
            </div>
        </div>
    </form>
</div>

<!-- Assign Permissions -->
<div class="card mt-4">
    <h4 class="mb-3">Set Planwise Permission </h4>
    <form action="{{ route('subscriptions.assignPermissions') }}" class="validateForm" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-12">
                <label for="subscription">Select Subscription Plan:</label>
                <select name="subscription_id" class="form-select" name="plan" id="subscription-select">
                    <option value="">Select Plan</option>
                    @foreach ($subscriptions as $subscription)
                    <option value="{{ $subscription->id }}">{{ $subscription->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-12">
                <div class="buttons form-group">
                    <label for="select-all" class="permission">
                        <input type="checkbox" name="terms" id="select-all" class="form-check-input no-validate"> Grant All Permission

                    </label>
                    <div class="error-msg"></div>
                </div>
            </div>

            @foreach ($permissions as $permission)
            <div class="col-lg-3">
                <label class="permission">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                        {{ isset($selectedSubscription) && $selectedSubscription->permissions->contains($permission->id) ? 'checked' : '' }}>
                    {{ $permission->name }}
                </label>

            </div>
            @endforeach
        </div>
        <div class="row mt-3">
            <div class="col-lg-3">
                <button type="submit" class="btn btn-primary button">Assign / Update Permissions</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Select or Deselect All Checkboxes
        $('#select-all').on('click', function() {
            // Check if #select-all is checked or not
            var isChecked = $(this).prop('checked');

            // Set all checkboxes to match the state of #select-all
            $('input[type="checkbox"]').prop('checked', isChecked);
        });

        // Fetch Permissions for Selected Subscription
        $('#subscription-select').on('change', function() {
            let subscriptionId = $(this).val();


            if (subscriptionId) {

                let url = '{{ route("subscriptions.permissions", ":id") }}';
                url = url.replace(':id', subscriptionId);

                $.ajax({
                    url: url, // Use the dynamically generated URL
                    type: 'GET',
                    success: function(data) {
                        console.log(data);
                        // Uncheck all checkboxes first
                        $('input[type="checkbox"]').prop('checked', false);

                        // Loop through the permissions and check the ones that are assigned
                        $('input[type="checkbox"]').each(function() {
                            if (data.permissions.includes($(this).val())) {
                                $(this).prop('checked', true);
                            }
                        });
                    },
                    error: function() {
                        console.error('Failed to fetch permissions');
                    }
                });
            } else {
                // If no subscription is selected, uncheck all checkboxes
                $('input[type="checkbox"]').prop('checked', false);
            }
        });
    });
</script>
@endsection