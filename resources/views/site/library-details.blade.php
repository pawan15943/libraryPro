@extends('sitelayouts.layout')
@section('content')
<section class="libraryDetailedHeader">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="m-0">{{ $library->library_name }} <span>Libraro Verified</span></h1>
                <h5>Near police Chowki Vinoa BHave Nagar - 324005</h5>
                <h5>KOTA, RAJASTHAN</h5>

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
                                <p>200 Seats</p>
                            </li>
                            <li>
                                <span>Lockers</span>
                                <p>30</p>
                            </li>
                            <li>
                                <span>Operations Hourse</span>
                                <p>09:30 PM to 23:00 PM</p>
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
                    <div class="col-lg-4">
                        <div class="plans-box">
                            <h4>Full Day</h4>
                            <ul>
                                <li>
                                    <span>Plan Type</span>
                                    <p>Monthly</p>
                                </li>
                                <li>
                                    <span>Validity</span>
                                    <p>1 Month</p>
                                </li>
                                <li>
                                    <span>Plan Price</span>
                                    <p>500 INR</p>
                                </li>
                                <li>
                                    <span>Duration</span>
                                    <p>8 Hours</p>
                                </li>
                                <li class="w-100">
                                    <a href="">Book Now</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="plans-box">
                            <h4>First Half</h4>
                            <ul>
                                <li>
                                    <span>Plan Type</span>
                                    <p>Full Day</p>
                                </li>
                                <li>
                                    <span>Validity</span>
                                    <p>1 Month</p>
                                </li>
                                <li>
                                    <span>Plan Price</span>
                                    <p>500 INR</p>
                                </li>
                                <li>
                                    <span>Duration</span>
                                    <p>8 Hours</p>
                                </li>
                                <li class="w-100">
                                    <a href="">Book Now</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="plans-box">
                            <h4>Second Half</h4>
                            <ul>
                                <li>
                                    <span>Plan Type</span>
                                    <p>Full Day</p>
                                </li>
                                <li>
                                    <span>Validity</span>
                                    <p>1 Month</p>
                                </li>
                                <li>
                                    <span>Plan Price</span>
                                    <p>500 INR</p>
                                </li>
                                <li>
                                    <span>Duration</span>
                                    <p>8 Hours</p>
                                </li>
                                <li class="w-100">
                                    <a href="">Book Now</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Write a review -->
                <h4 class="mt-5">Write a Review</h4>
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-6">
                        <label for="">Full Name</label>
                        <input type="text" class="form-control" placeholder="Enter Your name">
                    </div>
                    <div class="col-lg-6">
                        <label for="">Provide Rating</label>
                        <select name="" id="" class="form-control form-select">
                            <option value="">Select Rating</option>
                            <option value="">5 Star</option>
                            <option value="">4 Star</option>
                            <option value="">3 Star</option>
                            <option value="">2 Star</option>
                            <option value="">1 Star</option>
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <label for="">Write a Review</label>
                        <textarea name="" id="" class="form-control"></textarea>
                    </div>
                    <div class="col-lg-12">
                        <input type="submit" class="nav-link button" value="Add My Review">
                    </div>
                </div>

                <!-- Write a review -->
                <h4 class="mt-5">Learners Reviews</h4>
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-6">
                        <div class="review-box">
                            <img src="{{url('public/img/comma.png')}}" alt="comma" class="comma">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum iste aperiam aliquid saepe perspiciatis eius! Alias dolore, dicta nulla excepturi quia fugit quaerat, distinctio provident culpa minus consequatur rerum eos?</p>
                            <div class="d-flex mt-4">
                                <img src="" alt="">
                                <div class="leaner-info">
                                    <h4 class="m-0">Mukesh Aahuja</h4>
                                    <span>Library Learner</span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="review-box">
                            <img src="{{url('public/img/comma.png')}}" alt="comma" class="comma">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum iste aperiam aliquid saepe perspiciatis eius! Alias dolore, dicta nulla excepturi quia fugit quaerat, distinctio provident culpa minus consequatur rerum eos?</p>
                            <div class="d-flex mt-4">
                                <img src="" alt="">
                                <div class="leaner-info">
                                    <h4 class="m-0">Mukesh Aahuja</h4>
                                    <span>Library Learner</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

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
                <h4>Location On Map</h4>
                <div class="location">
                    <!-- Paste Iframe Code -->
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3612.6495305782005!2d75.8375642!3d25.1137224!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396f85c36f29576f%3A0x173a1efae7a53a41!2sNew%20Balaji%20Computer%20Classes%2C%20Kota!5e0!3m2!1sen!2sin!4v1739934919077!5m2!1sen!2sin" width="100%" class="rounded" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
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
@endsection