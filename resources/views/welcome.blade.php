<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <style>
        * {
            box-sizing: border-box;
            transition: all .5s;
        }

        @import url('https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Outfit:wght@100..900&family=Philosopher:ital,wght@0,400;0,700;1,400;1,700&display=swap');

        body {
            overflow: auto;
        }

        :root {
            --gredient-4: linear-gradient(2deg, #3F51B5, #081350);
            --gredient-5: linear-gradient(3deg, #673AB7, #370099);
            --gredient-6: linear-gradient(0deg, #c3a81d, #ab8d15);

        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: "Outfit", 'sans-sarif';
            font-weight: 700;
            margin: 0;
        }

        p,
        th,
        th,
        span,
        small,
        li,
        a,
        input,
        select,
        textarea {
            font-family: "Mulish", 'sans-sarif';
            font-weight: 600;
            margin: 0;
            color: var(--c9);
            font-size: 1rem;
        }

        header {
            position: sticky;
            top: 0;
            box-shadow: 1px 0 5px #00000045;
            background: #fff;
            z-index: 100;
        }

        header ul.navbar-nav.ms-auto.mb-lg-0>li+li {
            margin-left: 2rem;
        }

        header a.nav-link {
            font-size: 1rem;
            font-weight: 500;
            font-family: 'Outfit', 'sans-sarif';
        }

        .head-text-1 {
            color: #34939F !important;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        a.nav-link.button {
            height: auto ! IMPORTANT;
            background: #18225f;
            padding: .5rem 1.5rem ! IMPORTANT;
            border-radius: .5rem;
            color: #fff;
            box-shadow: 1px 4px 0 #ababab;
        }

        a.nav-link.button:hover {
            background: #038b9c;
            box-shadow: 1px 1px 0 #ababab;

        }

        .head-text-2 {
            color: #18225F !important;
            font-weight: 700 !important;
            font-size: 2.3rem;
            margin-bottom: 1rem;
        }

        .head-text-3 {
            color: #585858 !important;
            font-weight: 600 !important;
            font-size: 1.2rem;
            margin-bottom: 2rem;
            font-family: 'outfit', 'sans-sarif';
        }


        .cta {
            padding: .8rem 3.5rem;
            background: #18225f;
            border-radius: .5rem;
            box-shadow: 1px 4px 0 #ababab;
            text-decoration: none;
            color: #fff;
            font-size: 1.2rem;
        }

        a.cta:hover {
            background: #038b9c;
            box-shadow: 1px 1px 0 #ababab;

        }

        .heading h2 {
            color: #293ba4 !important;
            font-weight: 600 !important;
            font-size: 2.2rem;
            line-height: 2.2rem;
            margin-bottom: 1rem;
        }

        .heading span {
            color: #34939F !important;
            font-weight: 600;
            font-size: 1rem;
        }

        .logo {
            width: 180px;
            padding: .5rem 0;
        }

        .hero_Section,
        .product-benefits {
            padding: 3.5rem 0;
            background: linear-gradient(0deg, #cffff4, #fff5e9);
        }

        .hero_Section ul {
            display: flex;
            column-gap: 3.5rem;
            justify-content: right;
            list-style: none;
        }

        .hero_Section ul li a {
            text-decoration: none;
        }

        .hero_Section ul li a.active {
            font-weight: 700;
        }

        .product-features-box {
            background: #369a8e;
            padding: 1.5rem;
            border-radius: 1.5rem;
            min-height: 500px;
            margin: 0 .5rem;
            position: relative;
            overflow: hidden;

        }

        .product-features-box img {
            position: absolute;
            right: -3px;
            bottom: -3px;
            width: 312px !important;
        }


        #features .owl-item:nth-child(odd) .product-features-box {
            background: #bc770d;
        }

        .product-features {
            padding: 3.5rem 0;
        }

        .plan-features .d-flex {
            display: flex !important;
            align-items: center;
            gap: 1rem;
            font-weight: 400;
            font-family: 'Mulish';
            font-size: .8rem ! IMPORTANT;
        }

        .product-benefits {
            padding: 3.5rem 0;
            background: linear-gradient(45deg, #00BCD4, #046571);
        }

        .product-benefits .heading span {
            color: #0ae4ff !important;
            font-weight: 600;
            font-size: 1rem;
        }

        .product-benefits .heading h2 {
            color: #fff !important;
        }


        .basic {
            background: var(--gredient-4) !important;
        }


        .standard {
            background: var(--gredient-5) !important;
        }


        .premium {
            background: var(--gredient-6) !important;
        }

        .benefits {
            background: #fff;
            border: 1px solid #e7e7e7;
            padding: 2rem;
            border-radius: 1.5rem;
            /* box-shadow: 1px 0px 20px #00000026; */
            height: 100%;
            transition: all .5s;
        }

        .benefits h4 {
            font-weight: 600;
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            color: #068291;
        }

        .benefits span {
            font-size: 1rem;
            font-weight: 600;
            color: #212121;
        }

        .customer-feedback {
            padding: 3.5rem 0;
            background: #fffbe5;
        }

        .feedback-box {
            background: #fff;
            padding: 1.5rem;
            border-radius: 1.5rem;
            height: 100%;
            box-shadow: 1px 0 5px #00000024;
            margin: 1rem .3rem;
        }

        .customer-details span {
            font-size: .9rem;
            display: block;
            line-height: 18px;
        }

        .customer-info h4 {
            font-size: 1.2rem;
            color: #05acc1;
            font-weight: 400;
        }

        .icon {
            width: 40px;
            margin-bottom: 1.5rem;
        }

        .heading {
            margin-bottom: 3.5rem;
        }

        .product-features-box h4 {
            color: #fff !important;
            font-size: 1.2rem;
            font-weight: 600;
            line-height: 27px;
        }

        .customer-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .customer-info ul {
            list-style: none;
            padding: 0;
            display: flex;
            gap: .5rem;
            flex: 1;
            justify-content: end;
        }

        .customer-info h4 {
            font-size: 1.2rem;
        }

        .customer-info .profile {
            width: 45px !important;
        }

        .message {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 3rem;
            color: #616161;
            font-family: 'Mulish', 'sans-sarif';
        }

        .benefits:hover {
            box-shadow: 1px 0 5px #00000021;
            transform: scale(1.02);
            transition: .5s all;
        }

        .benefits:hover img {
            transform: rotate(15deg);
            transition: all .5s ease-in-out;
        }

        .benefits img {
            transition: all .5s;
        }

        .benefits .contents {
            height: auto !important;
        }

        .col-lg-4.payment-mode {
            text-align: center;
        }

        .col-lg-4.payment-mode select {
            height: 55px;
        }

        .plan-box h1,
        .plan-box h4 {
            text-align: center;
        }

        .plan-box h4 {
            margin-bottom: 2rem;
            font-weight: 400;
        }

        button.btn.btn-primary {
            background: linear-gradient(45deg, #1b2458, #232d6a);
            border: none;
            width: 100%;
            margin-top: 1rem;
            height: 45px;
            font-family: 'Outfit', 'sans-sarif';
            font-weight: 600;
            transition: all .5s;
        }

        button.btn.btn-primary:hover {
            background: linear-gradient(45deg, #00BCD4, #00BCD4);
            transition: all .5s;
        }

        .our-plan {
            padding: 3.5rem 0;
        }

        .col-lg-4:nth-child(1) .plan-box,
        .active-plan-box.basic,
        .basic {
            background: var(--gredient-4) !important;
        }

        .col-lg-4:nth-child(2) .plan-box,
        .active-plan-box.standard,
        .standard {
            background: var(--gredient-5) !important;
        }

        .col-lg-4:nth-child(3) .plan-box,
        .active-plan-box.premium,
        .premium {
            background: var(--gredient-6) !important;
        }

        .plan-box {
            border-radius: 1rem;
        }

        .plan-box h1 {
            padding-top: 2rem !important;
        }

        .plan-box * {
            color: #fff;
        }

        .plan-box ul.plan-features {
            list-style: none;
            padding: 0;
            margin: 0;
            height: 500px;
            overflow: auto;
        }

        .plan-box ul li {
            padding: .6rem 1rem;
            font-size: .9rem;
            color: #3b3b3b;
        }

        .plan-box ul li+li {
            border-top: 1px dotted #c9c9c9;
        }

        /* For modern browsers */
        ::-webkit-scrollbar {
            width: 5px;
            /* Width of the scrollbar */
            height: 5px;
            /* Height of the horizontal scrollbar */
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f121;
            /* Background of the scrollbar track */
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #028b9d, #028b9d);
            /* Gradient for the scrollbar thumb */
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #028b9d;
            /* Darker shade on hover */
        }

        .comma {
            width: 60px ! IMPORTANT;
            display: block;
            margin-bottom: 1.5rem;
        }


        /* Inquiry */
        .inquiry {
            padding: 3.5rem 0;

        }

        .inquiry .form-control:not(textarea),
        .button {
            height: 50px !important;
        }


        .main-box {
            position: relative;
            z-index: 1;
        }

        .main-box .support {
            position: absolute;
            background: #fff;
            padding: 1.5rem;
            border-radius: 1.5rem;
            box-shadow: 1px 0 23px #00000045;
            left: 2%;
            width: 228px;
            bottom: 10%;
        }

        .main-box>img {
            padding-left: 5rem;
        }

        footer {
            background: #113140;
            padding-top: 2.5rem;
        }

        footer h4 {
            color: #fff !IMPORTANT;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
            font-weight: 400;
        }

        footer ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        footer ul li+li {
            margin-top: 1rem;
        }

        footer ul li a {
            color: #c7c7c7;
            text-decoration: none;
        }

        ul.social {
            display: flex;
            margin: 0 !IMPORTANT;
            padding: 0 !IMPORTANT;
            gap: 1rem;
        }

        ul.social li {
            margin: 0;
        }

        ul.social li a .fab {
            font-size: 1.6rem;
        }

        .accordion-button:not(.collapsed) {
            background-color: #ffedb9 !important;
            font-weight: 700;
        }

        button.accordion-button.collapsed {
            font-weight: 700;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container">
                <a class="navbar-brand" href="#"><img src="{{ asset('public/img/libraro.svg') }}" alt="logo" class="logo"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Pricing</a>
                        </li>
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li> -->

                        <li class="nav-item">
                            <a class="nav-link ">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ">Contact Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link button">Add Your Library</a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
    </header>

    <!-- Section 1 -->
    <section class="hero_Section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h4 class="head-text-1">Revolutionize Your Library with the Best Library Management Software</h4>
                    <h2 class="head-text-2">Effortlessly Manage Your Library – From Bookings to Reporting, All in One Place!</h2>
                    <p class="head-text-3">Simplify library management with our feature-rich, user-friendly library software. Perfect for schools, colleges, and public libraries.</p>
                    <a href="" class="cta">Sign Up Now!</a>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('public/img/head.png') }}" alt="ok" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Section 2 -->
    <section class="product-features">
        <div class="container">
            <div class="heading">
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
    <section class="product-benefits">
        <div class="container">
            <div class="heading mb-5">
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

    <div class="our-plan">
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


            <div class="row mt-4 justify-content-center mb-4">
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
    <section class="py-5">
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
                            <p>Call : +91-8114479678</p>
                            <p>Mail : libraro@nbcc.com</p>
                        </div>
                        <img src="{{ asset('public/img/direcotry/support.png') }}" alt="support" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3">
                    <img src="{{ asset('public/img/libraro-white.svg') }}" alt="logo" class="logo">
                </div>
                <div class="col-lg-3">
                    <h4>IMPORTANT LINKS</h4>
                    <ul>
                        <li><a href="">HOME</a></li>
                        <li><a href="">ABOUT</a></li>
                        <li><a href="">FAQ</a></li>
                        <li><a href="">BLOG</a></li>
                        <li><a href="">CONTACT</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h4>CONTACT US</h4>
                    <ul>
                        <li><a href="">PRIVACY POLICY</a></li>
                        <li><a href="">TERMS OF USE</a></li>
                        <li><a href="">REFUND POLICY</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h4>IMPORTANT LINKS</h4>
                    <ul class="social">
                        <li><a href=""><i class="fab fa-facebook"></i></a></li>
                        <li><a href=""><i class="fab fa-instagram"></i></a></li>
                        <li><a href=""><i class="fab fa-linkedin"></i></a></li>
                        <li><a href=""><i class="fab fa-youtube"></i></a></li>
                        <li><a href=""><i class="fab fa-twitter"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-12">
                    <p class="py-1 text-center text-white m-0">@2025, All rights reserved</p>
                </div>
            </div>
        </div>


    </footer>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
        $('#feedback').owlCarousel({
            loop: true,
            nav: true,
            dots: true,
            margin: 20,
            navText: ['<i class="las la-angle-left arrow-left"></i>', '<i class="las la-angle-right arrow-right"></i>'],
            pagination: true,
            autoplay: true,
            autoPlaySpeed: 2000,
            smartSpeed: 2000,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1,
                    nav: false,
                },
                768: {
                    items: 2,
                    nav: false,
                },
                992: {
                    items: 3,
                },
                1200: {
                    items: 3,
                },
                1920: {
                    items: 4,
                }
            }
        });
    </script>
    <script>
        $('#features').owlCarousel({
            loop: true,
            nav: true,
            dots: true,
            margin: 20,
            navText: ['<i class="las la-angle-left arrow-left"></i>', '<i class="las la-angle-right arrow-right"></i>'],
            pagination: true,
            autoplay: true,
            autoPlaySpeed: 2000,
            smartSpeed: 2000,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1,
                    nav: false,
                },
                768: {
                    items: 2,
                    nav: false,
                },
                992: {
                    items: 3,
                },
                1200: {
                    items: 3,
                },
                1920: {
                    items: 4,
                }
            }
        });
    </script>
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

            function subscription_price(plan_mode) {
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

</body>

</html>