@extends('sitelayouts.layout')
@section('content')

    <!-- Section 1 -->
    <section class="hero_Section">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6 order-2 order-md-1 text-center text-md-start">
                    <h4 class="head-text-1">Revolutionize Your Library with the Best Library Management Software</h4>
                    <h2 class="head-text-2">Effortlessly Manage Your Library – From Bookings to Reporting, All in One Place!</h2>
                    <p class="head-text-3">Simplify library management with our feature-rich, user-friendly library software. Perfect for schools, colleges, and public libraries.</p>
                    <a href="{{route('register')}}" class="cta">Sign Up Now!</a>
                </div>
                <div class="col-lg-6 order-1 order-md-2 mb-4 mb-md-0">
                    <img src="{{ asset('public/img/head.png') }}" alt="ok" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Section 2 -->
    <section class="product-features">
        <div class="container">
            <div class="heading text-center text-md-start">
                <span>Features of Product</span>
                <h2>Transform Your <br>
                    Library into a Smart Space </h2>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="owl-carousel" id="features">

                        <div class="item">
                            <div class="product-features-box">
                                <h4>Interactive Dashboard with <br>
                                    Complete Seat Tracking</h4>
                                <img src="{{ asset('public/img/01.png') }}" alt="01">
                            </div>
                        </div>
                        <div class="item">
                            <div class="product-features-box">
                                <h4>Engage with Our Seat Mapping Feature: Expired and Extended Highlights</h4>
                                <img src="{{ asset('public/img/02.png') }}" alt="02">
                            </div>
                        </div>
                        <div class="item">
                            <div class="product-features-box">
                                <h4>Efficient & Seamless
                                    Reporting that make you Hasselfree</h4>
                                <img src="{{ asset('public/img/03.png') }}" alt="03">
                            </div>
                        </div>
                        <!-- <div class="item">
                            <div class="product-features-box">
                                <h4>Create Custom Plan as per learner Requirement</h4>
                                <img src="" alt="">
                            </div>
                        </div> -->
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Section 3 -->
    <section class="product-benefits" id="features">
        <div class="container">
            <div class="heading mb-5 text-center text-md-start">
                <span>Benefits of Product</span>
                <h2>Library<br>
                    Features we offers</h2>
            </div>
            <div class="row g-4">
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/swap.png') }}" alt="Swap Seat" class="icon">
                        <h4>Seamless <br>
                            Seat Swapping</h4>
                        <span>Effortlessly switch seats for the perfect spot every time—boost comfort and productivity
                            with just a click!</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/upgrade.png') }}" alt="Swap Seat" class="icon">
                        <h4>Easy <br>
                            Plan Upgrades</h4>
                        <span>Instantly upgrade your plan for more access and perks—unlock the best library experience
                            anytime!</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/reactivate.png') }}" alt="Swap Seat" class="icon">
                        <h4>Reactivate <br>
                            Seat Access</h4>
                        <span>Quickly reactivate your seat and jump back into your favorite study spot—no waiting, just
                            instant access!</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/close.png') }}" alt="Swap Seat" class="icon">
                        <h4>Close <br>
                            Seat Option</h4>
                        <span>Quickly reactivate your seat and jump back into your favorite study spot—no waiting, just
                            instant access!</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/cancel.png') }}" alt="Swap Seat" class="icon">
                        <h4>Cancel <br>
                            Seat Booking</h4>
                        <span>Cancel your seat booking anytime with ease—flexibility for your changing schedule!</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Delete <br>
                            Seat Booking</h4>
                        <span>Easily delete seat bookings you no longer need—maintain a clear and up-to-date booking
                            history!</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Effortless Communication</h4>
                        <span>Send updates, notifications, or reminders to students instantly via WhatsApp or email directly from the portal.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Flexible Membership Plans</h4>
                        <span>Modify learner plans seamlessly to fit their changing needs.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Quick Library Reset</h4>
                        <span>Mistakes happen! Reset your library settings with just one click to start fresh.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Simple Data Import</h4>
                        <span>Import existing learner data effortlessly in a single step to get started without delays.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Export Data with Ease</h4>
                        <span>Keep complete control by exporting your library data whenever needed.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Comprehensive Reporting</h4>
                        <span>Get detailed, actionable insights to optimize your library's performance.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Interactive Dashboard</h4>
                        <span>Access everything at a glance with our intuitive and user-friendly dashboard.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Extend Seat Usage</h4>
                        <span>Extend seat access days for learners with just a few clicks.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Automated Weekly Backups</h4>
                        <span>Rest easy knowing your library’s data is backed up automatically every week.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Maintain Full Seat History</h4>
                        <span>Keep a detailed record of every seat and its usage history.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Simplified User Interface</h4>
                        <span>Enjoy the easiest and most intuitive UI that requires no learning curve.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Free Directory Listing</h4>
                        <span>Get a complimentary listing in our library directory to boost visibility.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Learner Login Portal</h4>
                        <span>Provide learners with a secure login to access all library features effortlessly.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Track Recent Activities</h4>
                        <span>Monitor all recent activities in your library to stay updated.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Quick Action on Expired Seats</h4>
                        <span>View a dedicated listing of expiring and expired seats to take timely actions.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Flexible Setup Options</h4>
                        <span>Set up your library for half-day, full-day, or hourly operations—all in one step.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Expense and Revenue Management</h4>
                        <span>Manage your library’s expenses and calculate monthly revenue seamlessly.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>Online Training Videos</h4>
                        <span>Access training videos anytime to make the most of the software’s features.</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="benefits">
                        <img src="{{ asset('public/img/icons/delete.png') }}" alt="Swap Seat" class="icon">
                        <h4>24/7 Support</h4>
                        <span>Enjoy round-the-clock assistance to address any challenges you face.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="our-plan" id="pricing">
        <div class="container">
            <!-- Dynamic 3 -->
            <h2 class="text-center mb-4">Our Pricing</h2>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-4 payment-mode">
                    <select name="plan_mode" id="plan_mode" class="form-select">
                        <option value="1">MONTHLY</option>
                        <option value="2">YEARLY</option>
                    </select>
                </div>
            </div>


            <div class="row mt-4 g-4 justify-content-center mb-4">
                @foreach($subscriptions as $subscription)
                <div class="col-lg-4">
                    <div class="plan-box">
                        <h1 id="subscription_fees_{{$subscription->id}}"></h1>
                        <h4>{{$subscription->name}}</h4>
                        <ul class="plan-features contents">
                            @foreach($subscription->permissions as $permission)
                            <li>
                                <div class="d-flex">
                                    <i class="fa-solid fa-check"></i>
                                    {{$permission->name}}
                                </div>
                            </li>
                            @endforeach
                        </ul>

                        <div class="p-3">
                            <button class="btn btn-primary">Buy Now</button>
                        </div>
                    </div>
                </div>

                @endforeach
            </div>
            <!-- Dynamic 3 -->
        </div>

    </div>

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
                    <div class="owl-carousel" id="feedback">
                        <div class="item">
                            <div class="feedback-box">
                                <img src="{{url('public/img/comma.png')}}" alt="comma" class="comma">
                                <div class="message">As a librarian, I was constantly struggling to keep track of books and overdue records. With Library Manager, everything is automated, and managing our collection is now a breeze. Excellent product and great support team!</div>
                                <div class="customer-info">
                                    <img src="{{ asset('public/img/user2.png') }}" alt="user" class="profile">
                                    <div class="customer-details">
                                        <h4>Priya Mehta</h4>
                                        <span>Senior Librarian</span>
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
                                        <h4>Amit Kumar</h4>
                                        <span>Library In-Charge</span>
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
                                        <h4>Amit Kumar</h4>
                                        <span>Library In-Charge</span>
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
                                    data-bs-target="#faq_01" aria-expanded="true" aria-controls="collapseOne">
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
                                    data-bs-target="#faq_02" aria-expanded="false" aria-controls="collapseTwo">
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
                                    data-bs-target="#faq_03" aria-expanded="false" aria-controls="collapseThree">
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
                                    data-bs-target="#faq_04" aria-expanded="false" aria-controls="collapseThree">
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
                                    data-bs-target="#faq_05" aria-expanded="false" aria-controls="collapseThree">
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
                                    data-bs-target="#faq_06" aria-expanded="false" aria-controls="collapseThree">
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
                                    data-bs-target="#faq_07" aria-expanded="false" aria-controls="collapseThree">
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
    <section class="inquiry">


        <div class="container">
            <div class="row g-4 align-items-center">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                <div class="col-lg-6">
                    <h2 class="mb-4">Have Any Query ? <br>
                        Request a Callback</h2>
                    <form action="{{ route('submit.inquiry') }}" method="POST">
                        @csrf
                        <div class="form-box">
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <label for="name">Full Name</label>
                                    <input type="text"
                                        name="full_name"
                                        class="form-control @error('full_name') is-invalid @enderror char-only"
                                        placeholder="Enter your Name"
                                        value="{{ old('full_name') }}">
                                    @error('full_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-lg-12">
                                    <label for="mobile_number">Mobile Number</label>
                                    <input type="text"
                                        name="mobile_number"
                                        class="form-control @error('mobile_number') is-invalid @enderror digit-only"
                                        placeholder="Enter Mobile Number"
                                        value="{{ old('mobile_number') }}">
                                    @error('mobile_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-lg-12">
                                    <label for="email">Email Id</label>
                                    <input type="email"
                                        name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Enter Email Address"
                                        value="{{ old('email') }}">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-lg-12">
                                    <label for="message">Message</label>
                                    <textarea rows="5"
                                        name="message"
                                        class="form-control @error('message') is-invalid @enderror"
                                        placeholder="Enter Message">{{ old('message') }}</textarea>
                                    @error('message')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-lg-12">
                                    <button class="btn btn-primary button" type="submit">Submit Details</button>
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
                            <p>Mail : <a href="mailto:info@librarymanager.in">info@librarymanager.in</a></p>
                        </div>
                        <img src="{{ asset('public/img/direcotry/support.png') }}" alt="support" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection
