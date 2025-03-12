@extends('sitelayouts.layout')
@section('content')
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>



<!-- Section 1 -->
<section class="hero_Section_directory" style="background: url('{{ asset('/public/img/direcotry/bg-head.png') }}') no-repeat; background-size:cover;">
    <div class="container">
        <div class="row h-100 align-items-center">
            <div class="col-lg-12">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-9">
                        <h1>Find the Best Libraries Near You in India</h1>

                        <div class="search">
                            <select name="" id="city-item" class="form-select">
                                <option value="">Select City</option>
                                <option value="1">KOTA</option>
                               
                                <option value="2">BUNDI</option>
                            </select>
                            <!-- <select name="" id="" class="form-select">
                                <option value="">Select Price Range</option>
                                <option value="">200 to 400</option>
                            </select> -->
                            <div class="search-container" >
                                <input type="text" id="library-search" class="form-control" placeholder="Search Libraries near you">

                                <i class="fa fa-arrow-right"></i>
                                <ul id="suggestions" class="list-group mt-2"></ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Library -->
<section class="popular py-5">
    <div class="container">
        <div class="heading mb-5">
            <h2>Featured & Popular Libraries</h2>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="owl-carousel" id="library-list">
                </div>
            </div>

        </div>
    </div>
</section>

<section class="google-map pt-5">
    <h2 class="mb-4 text-center">Explore Our Libraries Across India – Interactive Map!</h2>
    <div id="map" style="width: 100%; height: 550px;"></div>
</section>


<section class="important-counts">
    <div class="container">
        <h2 class="mb-5">Important Factors <br>That Make Us the Right Choice</h2>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6 col-6">
                <h1>{{$library_count}}+</h1>
                <p>Library Enrolled</p>
            </div>
            <div class="col-lg-3 col-md-6 col-6">
                <h1>{{$learner_count}}+</h1>
                <p>Learner Enrolled</p>
            </div>
            <div class="col-lg-3 col-md-6 col-6">
                <h1>{{$city_count}}+</h1>
                <p>Total Cities
            </div>
            <div class="col-lg-3 col-md-6 col-6">
                <h1>{{$feedback_count}}+</h1>
                <p>Customers trusts</p>
            </div>
        </div>
    </div>
</section>

<!-- Customer's Feedback -->
<section class="customer-feedback">

    <div class="container">
        <div class="heading mb-5">
            <span>Customer's Feedback</span>
            <h2>What Our <br>
                Happy Customers Say’s</h2>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="owl-carousel" id="clientsFeedback">
                    @if(!($happy_customers->isEmpty()))
                    <div class="item">
                        <div class="feedback-box">
                            <img src="{{url('public/img/comma.png')}}" alt="comma" class="comma">

                            <div class="message">As the <b>Founder & Director</b>, I created Libraro to simplify library operations with automation, seamless bookings, and powerful analytics. It's the all-in-one solution for modern libraries!</div>
                            <div class="customer-info">
                                <img src="{{ asset('public/img/pawan-profile.jpg') }}" alt="user" class="profile rounded-circle">
                                <div class="customer-details">
                                    <h4>Pawan Rathore</h4>
                                    <span>Founder: Libraro</span>
                                </div>
                                <ul class="customer-ratings">
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="feedback-box">
                            <img src="{{url('public/img/comma.png')}}" alt="comma" class="comma">

                            <div class="message">As the Developer of Libraro, I built this platform to streamline library operations with automation, intuitive booking, and advanced analytics. Designed for efficiency, it's the ultimate tool for modern libraries!</div>
                            <div class="customer-info">
                                <img src="{{ asset('public/img/user2.png') }}" alt="user" class="profile">
                                <div class="customer-details">
                                    <h4>Heena Kaushar</h4>
                                    <span>Developer: Libraro </span>
                                </div>
                                <ul class="customer-ratings">
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="feedback-box">
                            <img src="{{url('public/img/comma.png')}}" alt="comma" class="comma">

                            <div class="message">We’ve been using Library Manager for over a year now, and it has exceeded all our expectations. The analytics and reporting features provide valuable insights. It’s an all-in-one solution for modern library management!</div>
                            <div class="customer-info">
                                <img src="{{ asset('public/img/user2.png') }}" alt="user" class="profile">
                                <div class="customer-details">
                                    <h4>Sandeep Rathor</h4>
                                    <span>Libraro Manager</span>
                                </div>
                                <ul class="customer-ratings">
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @foreach($happy_customers as $key => $value)
                    <div class="item">
                        <div class="feedback-box">
                            <img src="{{url('public/img/comma.png')}}" alt="comma" class="comma">
                            <div class="message">{{$value->description ?? ''}}</div>
                            <div class="customer-info">
                                <img src="{{ asset('public/img/pawan.png') }}" alt="user" class="profile">
                                <div class="customer-details">
                                    <h4>{{$value->library_owner ?? ''}}</h4>
                                    <span>{{$value->library_name ?? ''}}</span>
                                </div>
                                <ul class="customer-ratings">
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @else
                    <div class="item">
                        <div class="feedback-box">
                            <img src="{{url('public/img/comma.png')}}" alt="comma" class="comma">

                            <div class="message">As the <b>Founder & Director</b>, I created Libraro to simplify library operations with automation, seamless bookings, and powerful analytics. It's the all-in-one solution for modern libraries!</div>
                            <div class="customer-info">
                                <img src="{{ asset('public/img/pawan-profile.jpg') }}" alt="user" class="profile rounded-circle">
                                <div class="customer-details">
                                    <h4>Pawan Rathore</h4>
                                    <span>Founder: Libraro</span>
                                </div>
                                <ul class="customer-ratings">
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="feedback-box">
                            <img src="{{url('public/img/comma.png')}}" alt="comma" class="comma">

                            <div class="message">As the Developer of Libraro, I built this platform to streamline library operations with automation, intuitive booking, and advanced analytics. Designed for efficiency, it's the ultimate tool for modern libraries!</div>
                            <div class="customer-info">
                                <img src="{{ asset('public/img/user2.png') }}" alt="user" class="profile">
                                <div class="customer-details">
                                    <h4>Heena Kaushar</h4>
                                    <span>Developer: Libraro </span>
                                </div>
                                <ul class="customer-ratings">
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="feedback-box">
                            <img src="{{url('public/img/comma.png')}}" alt="comma" class="comma">

                            <div class="message">We’ve been using Library Manager for over a year now, and it has exceeded all our expectations. The analytics and reporting features provide valuable insights. It’s an all-in-one solution for modern library management!</div>
                            <div class="customer-info">
                                <img src="{{ asset('public/img/user2.png') }}" alt="user" class="profile">
                                <div class="customer-details">
                                    <h4>Sandeep Rathor</h4>
                                    <span>Libraro Manager</span>
                                </div>
                                <ul class="customer-ratings">
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                    <li><img src="{{ asset('public/img/star.png') }}" alt="star"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Frequently Asked Questions -->
<section class="py-5" id="faqy">
    <div class="container">
        <h2 class="text-center mb-5">Frequently Asked Questions</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq_01" aria-expanded="true" aria-controls="faq_01">
                                Qus 1: What is Libraro, and how does it work?
                            </button>
                        </h2>
                        <div id="faq_01" class="accordion-collapse collapse show"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>Answer</strong> Libraro is a comprehensive library management portal designed to simplify and automate library operations. It allows you to manage books, track issued and returned items, and generate reports efficiently, all through a user-friendly interface.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq_02" aria-expanded="false" aria-controls="faq_02">
                                Qus 2: Who can use Libraro?
                            </button>
                        </h2>
                        <div id="faq_02" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>Answer</strong> Libraro is suitable for schools, colleges, universities, public libraries, and private libraries looking for a modern solution to streamline their library management processes.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq_03" aria-expanded="false" aria-controls="faq_03">
                                Qus 3: Is Libraro compatible with different devices?
                            </button>
                        </h2>
                        <div id="faq_03" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>Answer</strong> Yes, Libraro is accessible on desktops, laptops, tablets, and smartphones, ensuring convenience for library staff and users anytime, anywhere.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq_04" aria-expanded="false" aria-controls="faq_04">
                                Qus 4: Can I import my existing library data into Libraro?
                            </button>
                        </h2>
                        <div id="faq_04" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>Answer</strong> Absolutely! Libraro allows you to import existing data in bulk using easy-to-use templates, making the transition seamless for your library.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq_05" aria-expanded="false" aria-controls="faq_05">
                                Qus 5: Does Libraro support multiple users and roles?
                            </button>
                        </h2>
                        <div id="faq_05" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>Answer</strong> Yes, Libraro supports multiple user roles, such as administrators, librarians, and members. Each role has customized permissions to ensure smooth and secure operations.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq_06" aria-expanded="false" aria-controls="faq_06">
                                Qus 6: Is my library data secure with Libraro?
                            </button>
                        </h2>
                        <div id="faq_06" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>Answer</strong> Security is our top priority. Libraro uses advanced encryption and data protection measures to ensure your library's data is safe and accessible only to authorized users.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq_07" aria-expanded="false" aria-controls="faq_07">
                                Qus 7: How do I get support if I face any issues?
                            </button>
                        </h2>
                        <div id="faq_07" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>Answer</strong> We provide dedicated customer support via email, phone, and chat to assist you with any technical or operational queries. You can also access our online help documentation for quick solutions.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Support -->
<section class="inquiry" id="demo">


    <div class="container">
        <div class="row g-4 align-items-center">
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            <div class="col-lg-6">
                <h2 class="mb-4">Would you like to <br><span>Schedule a free Demo?</span></h2>
                <form class="me-3" id="demoRequest">
                    @csrf
                    <input type="hidden" name="databasemodel" value="DemoRequest">
                    <div class="form-box">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="full_name">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror char-only" placeholder="Enter your Name" autocomplete="off" id="full_name">

                            </div>
                            <div class="col-lg-12">
                                <label for="mobile_number">Mobile Number <span class="text-danger">*</span></label>
                                <input type="text" name="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror digit-only" placeholder="Enter Mobile Number" minlength="8" maxlength="10" autocomplete="off" id="mobile_number">
                                @error('mobile_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <label for="email">Email Id <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter Email Address" autocomplete="off" id="email">

                            </div>
                            <div class="col-lg-12">
                                <label for="preferred_date">Preferred Date <span class="text-danger">*</span></label>
                                <input type="date" name="preferred_date" class="form-control @error('preferred_date') is-invalid @enderror" id="preferred_date">

                            </div>
                            <div class="col-lg-12">
                                <label for="timeSlot">Preferred Time (Optional)</label>
                                <select class="form-select no-validate" id="timeSlot" name="preferred_time">
                                    <option value="">Select Time Slot</option>
                                    <option value="7:00 AM - 7:30 AM">7:00 AM - 7:30 AM</option>
                                    <option value="7:30 AM - 8:00 AM">7:30 AM - 8:00 AM</option>
                                    <option value="8:00 AM - 8:30 AM">8:00 AM - 8:30 AM</option>
                                    <option value="8:30 AM - 9:00 AM">8:30 AM - 9:00 AM</option>
                                    <option value="9:00 AM - 9:30 AM">9:00 AM - 9:30 AM</option>
                                    <option value="9:30 AM - 10:00 AM">9:30 AM - 10:00 AM</option>
                                    <option value="10:00 AM - 10:30 AM">10:00 AM - 10:30 AM</option>
                                    <option value="10:30 AM - 11:00 AM">10:30 AM - 11:00 AM</option>
                                    <option value="11:00 AM - 11:30 AM">11:00 AM - 11:30 AM</option>
                                    <option value="11:30 AM - 12:00 PM">11:30 AM - 12:00 PM</option>
                                    <option value="12:00 PM - 12:30 PM">12:00 PM - 12:30 PM</option>
                                    <option value="12:30 PM - 1:00 PM">12:30 PM - 1:00 PM</option>
                                    <option value="1:00 PM - 1:30 PM">1:00 PM - 1:30 PM</option>
                                    <option value="1:30 PM - 2:00 PM">1:30 PM - 2:00 PM</option>
                                    <option value="2:00 PM - 2:30 PM">2:00 PM - 2:30 PM</option>
                                    <option value="2:30 PM - 3:00 PM">2:30 PM - 3:00 PM</option>
                                    <option value="3:00 PM - 3:30 PM">3:00 PM - 3:30 PM</option>
                                    <option value="3:30 PM - 4:00 PM">3:30 PM - 4:00 PM</option>
                                    <option value="4:00 PM - 4:30 PM">4:00 PM - 4:30 PM</option>
                                    <option value="4:30 PM - 5:00 PM">4:30 PM - 5:00 PM</option>
                                    <option value="5:00 PM - 5:30 PM">5:00 PM - 5:30 PM</option>
                                    <option value="5:30 PM - 6:00 PM">5:30 PM - 6:00 PM</option>
                                    <option value="6:00 PM - 6:30 PM">6:00 PM - 6:30 PM</option>
                                    <option value="6:30 PM - 7:00 PM">6:30 PM - 7:00 PM</option>
                                    <option value="7:00 PM - 7:30 PM">7:00 PM - 7:30 PM</option>
                                </select>
                                <small class="text-danger">*We will call you at your preferred time based on our availability.</small>
                            </div>
                            <div class="col-lg-12 form-group">
                                <input type="checkbox" class="me-2 form-check-input " name="terms" id="terms">
                                <label class="form-check-label" for="terms">
                                    I agree to the Libraro <a href="#">Terms and Conditions.</a><sup class="text-danger">*</sup>
                                </label>
                                <div class="error-msg"></div>
                            </div>
                            <div class="col-lg-4">
                                <button class="btn btn-primary" type="submit">Book My Slot</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="col-lg-6">
                <div class="main-box">
                    <div class="support">
                        <img src="{{ asset('public/img/direcotry/call.png') }}" alt="call">
                        <h4>We Are Here
                            to Assist you</h4>
                        <p class="m-0">Call : <a href="tel:91-8114479678">91-8114479678</a></p>
                        <p>Mail : <a href="mailto:info@libraro.in">info@libraro.in</a></p>
                    </div>
                    <img src="{{ asset('public/img/contact.png') }}" alt="support" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#demoRequest').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: '{{ route("demo-request") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    console.log(response);

                    if (response.status === 'success') {
                        toastr.success(response.message);

                        // Clear error messages and reset form
                        $(".is-invalid").removeClass("is-invalid");
                        $(".invalid-feedback").remove();

                        // Optionally, reset the form after success
                        $('#demoRequest')[0].reset();
                        $("#error-message").hide();
                    } else {
                        $("#error-message").text(response.message).show();
                        $("#success-message").hide();
                    }
                },
                error: function(xhr) {
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
                        console.error('AJAX Error:', xhr.responseText);
                        alert('There was an error processing the request. Please try again.');
                    }
                }
            });
        });
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        var map = L.map('map').setView([20.5937, 78.9629], 5); // Default center: India

        // Load OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Fetch library locations from Laravel route
        fetch("{{ route('getLibrariesLocations') }}")
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) return; // If no libraries, do nothing

                var bounds = L.latLngBounds(); // Define a bounding box for fitting markers

                data.forEach(library => {
                    var marker = L.marker([library.latitude, library.longitude])
                        .addTo(map)
                        .bindPopup(`<strong>${library.library_name}</strong><br>${library.library_address}`);

                    bounds.extend(marker.getLatLng()); // Expand bounds to include this location
                });

                // Auto-fit map to show all markers dynamically
                map.fitBounds(bounds, {
                    padding: [50, 50]
                });
            })
            .catch(error => console.error("Error loading map data:", error));
    });
</script>


@endsection