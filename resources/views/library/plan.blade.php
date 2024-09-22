@extends('layouts.admin')

@section('content')

<!-- Breadcrumb -->
<div class="row">
    <div class="d-flex bradcrumb">
        <h4>Choose Plan</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Choose Plan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="steps">
            <ul>
                <li class="active">
                    <a href="{{ $isEmailVeri ? route('subscriptions.choosePlan') : '#' }}">Choose Plan</a>
                </li>
                <li>
                    <a href="{{ $checkSub ? route('subscriptions.payment') : '#' }}">Make Payment</a>
                </li>
                <li >
                    <a href="{{ $ispaid ? route('profile') : '#' }}">Update Profile</a>
                </li>
                <li>
                    <a href="{{ $isProfile ? route('library.master') : '#' }}">Configure Library</a>
                </li>
            </ul>
            
            
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <h2 class="text-center">Pick the plan that fits you best!</h2>
    </div>
</div>


<div class="row g-4 justify-content-center mt-1">
    <div class="col-lg-4 payment-mode">
        <label for="" class="m-auto d-block">Select Plan Mode <span>*</span></label>
        <select name="plan_mode" id="plan_mode" class="form-select">
            <option value="1">MONTHLY</option>
            <option value="2">YEARLY</option>
        </select>
    </div>
</div>


<div class="row mt-4 justify-content-center mb-4">
    @foreach($subscriptions as $subscription)
    <div class="col-lg-3">
        <div class="plan-box">
            <h1 id="subscription_fees_{{$subscription->id}}"></h1> 
            <h4>{{$subscription->name}}</h4>
            <ul class="plan-features">
                @foreach($subscription->permissions as $permission)
                <li>
                    <div class="d-flex">
                        <i class="fa-solid fa-check"></i>
                        {{$permission->name}}
                    </div>
                </li>
                @endforeach
            </ul>
            <span class="showmore">Show More </span>
            <form id="payment-form" action="{{route('subscriptions.payment')}}" method="POST" >
                @csrf
                <input type="hidden" name="library_id" value="{{Auth::user()->id}}">
                <input type="hidden" name="subscription_id" id="subscription_id" value="{{$subscription->id}}">
                <input type="hidden" name="plan_mode" id="plan_mode_{{$subscription->id}}">
                <input type="hidden" name="price" id="price_{{$subscription->id}}">
                <button type="submit" class="btn btn-primary button ">Buy Now</button>
            </form>
        </div>
    </div>

    @endforeach
</div>



<!-- jQuery and AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '.showmore', function() {
        var $planFeatures = $(this).closest('.plan-box').find('.plan-features');
        
        // Toggle the overflow-auto class
        $planFeatures.toggleClass('overflow-auto');

        // Change the button text between "Show More" and "Show Less"
        if ($planFeatures.hasClass('overflow-auto')) {
            $(this).text('Show Less');
        } else {
            $(this).text('Show More');
        }
    });



    $(document).ready(function() {
        
        var plan_mode = $('#plan_mode').find(":selected").val();
        subscription_price(plan_mode);
        
        $('#plan_mode').on('change', function() {
            var plan_mode = $(this).val();
            subscription_price(plan_mode);
           
        });
        function subscription_price(plan_mode){
            if (plan_mode) {
                $.ajax({
                    url: '{{ route('subscriptions.getSubscriptionPrice') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: 'GET',
                    data: {
                        "plan_mode": plan_mode
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Loop through each subscription price and dynamically update the HTML
                        response.subscription_prices.forEach(function(subscription) {
                            $('#subscription_fees_' + subscription.id).text(subscription.fees); 
                            $('#plan_mode_' + subscription.id).val(plan_mode)
                            $('#price_' + subscription.id).val(subscription.fees)
                        });
                    },
                    error: function(xhr) {
                        toastr.error('An error occurred. Please try again.');
                    }
                });
            }
        }

       
       
       
    });
</script>
@endsection
