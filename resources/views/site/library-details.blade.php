@extends('sitelayouts.layout')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<section class="libraryDetailedHeader">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="m-0">{{ $library->library_name }} <span>Libraro Verified</span></h1>
                <h5>{{ $library->library_address }}-{{ $library->library_zip }}</h5>
                <h5>{{ $library->city->city_name }}, {{ $library->state->state_name }}</h5>

                <ul class="controls">
                    <li>
                        <a href="">
                            <i class="fa fa-envelope"></i>
                            <span>Enquire Now</span>
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="fa fa-edit"></i>
                            <span>Write a Review</span>
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp</span>
                        </a>
                    </li>

                </ul>
            </div>


        </div>
    </div>
</section>

<section class="libraryDetials">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Library Description -->
                <h4 class="mt-0">Library Description </h4>
                <p class="m-0">Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe ipsam quod eligendi magnam amet cum quisquam fuga! Ab, eaque reprehenderit eligendi aliquam quisquam odio nisi molestias accusantium quod ipsa consequatur.</p>

                <div class="row mt-4">
                    <div class="col-lg-12">
                        <ul class="library-anmities">
                            <li>
                                <span>Library Type</span>
                                <p>Public</p>
                            </li>
                            <li>
                                <span>Library Capacity</span>
                                <p>{{$total_seat}} Seats</p>
                            </li>
                            <li>
                                <span>Lockers</span>
                                <p>30</p>
                            </li>
                            <li>
                                <span>Operations Hourse</span>
                                <p>{{ \Carbon\Carbon::parse($operating->start_time)->format('h:i A') }} to {{ \Carbon\Carbon::parse($operating->end_time)->format('h:i A') }}</p>

                            </li>
                            <li>
                                <span>Operating Days</span>
                                <p>Monday, Tuesday, Wednesday, Thursday, Friday, Saturday</p>
                            </li>
                        </ul>
                    </div>
                </div>


                <!-- Features -->
                <h4 class="mt-5">Library Features</h4>
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

                <!-- Library Plans -->
                <h4 class="mt-5">Our Library Packages</h4>
                <div class="row">
                    @foreach($our_package as $key => $value)  
                        <div class="col-lg-4">
                            <div class="plans-box">
                                <h4>{{$value->plan_type_name}}</h4>
                                <ul>
                                    <li>
                                        <span>Plan Type</span>
                                        <p>{{$value->plan_id == 12 ? 'Yearly' : 'Monthly'}}</p>
                                    </li>
                                    <li>
                                        <span>Validity</span>
                                        <p>{{$value->plan_name}}</p>
                                    </li>
                                    <li>
                                        <span>Plan Price</span>
                                        <p>{{$value->price}} INR</p>
                                    </li>
                                    <li>
                                        <span>Duration</span>
                                        <p>{{$value->slot_hours}} Hours</p>
                                    </li>
                                    <li class="w-100">
                                        
                                        <p>{{$value->start_time}} - {{$value->end_time}}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endforeach
                  
                </div>

                <!-- Write a review -->
                <h4 class="mt-5">Write a Review</h4>
                
                <form  method="POST" id="reviewForm">
                        @csrf
                    <div class="row g-4 justify-content-center">
                        <div class="col-lg-6">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" class="form-control char-only" placeholder="Enter Your Name" >
                        </div>
                        <div class="col-lg-6">
                            <label for="rating">Provide Rating</label>
                            <select id="rating" class="form-control form-select" name="rating" >
                                <option value="">Select Rating</option>
                                <option value="5">5 Star</option>
                                <option value="4">4 Star</option>
                                <option value="3">3 Star</option>
                                <option value="2">2 Star</option>
                                <option value="1">1 Star</option>
                            </select>
                        </div>
                        <div class="col-lg-12">
                            <label for="review">Write a Review</label>
                            <textarea id="review" class="form-control" name="comments"></textarea>
                        </div>
                        <div class="col-lg-12">
                            <input type="submit" class="nav-link button" value="Add My Review">
                        </div>
                    </div>
                </form>

                <!-- Write a review -->
                @if($learnerFeedback->isNotEmpty())
               
                <h4 class="mt-5">Learners Reviews</h4>
                <div class="row g-4 justify-content-center">
                    @foreach($learnerFeedback as $key => $value)
                    <div class="col-lg-6">
                        <div class="review-box">
                            <img src="{{url('public/img/comma.png')}}" alt="comma" class="comma">
                            <p>{{$value->comments}}</p>
                            <div class="d-flex mt-4">
                                <img src="" alt="">
                                <div class="leaner-info">
                                    <h4 class="m-0">{{$value->learner->name}}</h4>
                                    <span>Library Learner</span>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endforeach
              
                </div>
                @else
                <div class="row g-4 justify-content-center">
                </div>
                @endif
            </div>

            <!-- Library Image -->
            <div class="col-lg-4">
                <div class="library-images">
                    <div class="main-image">
                        <img id="mainImage" src="{{url('public/img/library-image.jpg')}}" alt="libraryImage">
                    </div>
                    <ul class="thumb">
                        <li><img class="thumb-img" src="{{url('public/img/library-image.jpg')}}" alt="libraryImage"></li>
                        <li><img class="thumb-img" src="{{url('public/img/02.jpg')}}" alt="libraryImage"></li>
                        <li><img class="thumb-img" src="{{url('public/img/library-image.jpg')}}" alt="libraryImage"></li>
                        <li><img class="thumb-img" src="{{url('public/img/library-image.jpg')}}" alt="libraryImage"></li>
                    </ul>
                </div>

                <!-- location on Map -->
                @if(!empty($library->google_map))
                    
              
                
                <h4>Location On Map</h4>
                <div class="location">
                    <!-- Paste Iframe Code -->
                    
                    <iframe src="{{$library->google_map}}" width="100%" class="rounded" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<section></section>

<div class="container">
    <div class="card shadow-lg p-4">
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="{{ asset($library->library_logo) }}" alt="Library Logo" class="img-fluid rounded-circle" width="150">
            </div>
            <div class="col-md-8">
                <h2 class="text-primary"></h2>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function() {
        // Set the first thumbnail as the default main image
        var firstImage = $(".thumb-img").first().attr("src");
        $("#mainImage").attr("src", firstImage);

        // On thumbnail click, change main image
        $(".thumb-img").click(function() {
            var imgSrc = $(this).attr("src");
            $("#mainImage").attr("src", imgSrc);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#reviewForm").on('submit', function (e) {
            e.preventDefault(); 
    
            var formData = new FormData(this);
            formData.append('_token', '{{ csrf_token() }}');
            $.ajax({
                url: '{{ route('submit.review') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    
                    
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        
                        // Clear error messages and reset form
                        $(".is-invalid").removeClass("is-invalid");
                        $(".invalid-feedback").remove();
                        
                        // Optionally, reset the form after success
                        $('#reviewForm')[0].reset(); 
                        $("#error-message").hide();
                    } else {
                        $("#error-message").text(response.message).show();
                        $("#success-message").hide();
                    }
                },
                error: function (xhr) {
                    var response = xhr.responseJSON;
                    
                    if (xhr.status === 422 && response.errors) { // Validation error check
                        $(".is-invalid").removeClass("is-invalid");
                        $(".invalid-feedback").remove();

                        $.each(response.errors, function(key, value) {
                            var element = $("[name='" + key + "']");
                            element.addClass("is-invalid");
                            element.after('<span class="invalid-feedback" role="alert">' + value[0] + '</span>');
                        });
                    } else {
                        console.log('AJAX Error: ', xhr.responseText);
                        alert('There was an error processing the request. Please try again.');
                    }
                }
            });
        });
    });
    </script>
@endsection