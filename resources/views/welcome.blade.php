<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Outfit:wght@100..900&family=Philosopher:ital,wght@0,400;0,700;1,400;1,700&display=swap');

        body {
            overflow: auto;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: "Outfit", serif;
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
            font-family: "Mulish", serif;
            font-weight: 600;
            margin: 0;
            color: var(--c9);
            font-size: 1rem;
        }

        .head-text-1 {
            color: #34939F !important;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .head-text-2 {
            color: #18225F !important;
            font-weight: 600 !important;
            font-size: 3rem;
            line-height: 3rem;
            margin-bottom: 1rem;
        }

        .head-text-3 {
            color: #000 !important;
            font-weight: 600 !important;
            font-size: 1.8rem;
            margin-bottom: 2rem;
        }

        .cta {
            padding: .5rem 1.5rem;
            background: #34939F;
            border-radius: 1.5rem;
            box-shadow: 1px 4px 0 #ababab;
            text-decoration: none;
            color: #fff;
        }

        .heading h2 {
            color: #18225F !important;
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
            padding: 1rem 0;
        }

        .hero_Section,
        .product-benefits {
            padding: 1.5rem 0;
            background: linear-gradient(0deg, #FFFFFF, #E4FBFF);
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
            background: #113140;
            padding: 1.5rem;
            border-radius: 1.5rem;
            min-height: 500px;
            margin: 0 .5rem;
        }

        #features .owl-item:nth-child(odd) .product-features-box {
            background: #00BCD4;
        }

        .product-features {
            padding: 3.5rem 0;
        }


        .product-benefits {
            padding: 3.5rem 0;
        }

        .benefits {
            background: #fff;
            /* border: 1px solid #e7e7e7; */
            padding: 2rem;
            border-radius: 1.5rem;
            box-shadow: 1px 0px 20px #00000026;
        }

        .benefits h4 {
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .benefits span {
            font-size: 1rem;
            font-weight: 600;
        }

        .customer-feedback {
            padding: 3.5rem 0;
            background: #eff1ff;
        }

        .feedback-box {
            background: #fff;
            padding: 1.5rem;
            border-radius: 1.5rem;
        }

        .icon {
            width: 60px;
            margin-bottom: 1.5rem;
        }

        .heading {
            margin-bottom: 3.5rem;
        }

        .product-features-box h4 {
            color: #fff !important;
            font-size: 1.2rem;
            font-weight: 400;
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
            font-weight: 700;
            margin-bottom: 7rem;
        }

        .benefits:hover {
            box-shadow: 1px 0 25px #00000021;
            transform: scale(1.05);
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
        }

        .our-plan {
            padding: 3.5rem 0;
            background: #f5f5f5;
        }

        .col-lg-3:nth-child(1) .plan-box,
        .active-plan-box.basic,
        .basic {
            background: var(--gredient-4) !important;
        }

        .col-lg-3:nth-child(2) .plan-box,
        .active-plan-box.standard,
        .standard {
            background: var(--gredient-5) !important;
        }

        .col-lg-3:nth-child(3) .plan-box,
        .active-plan-box.premium,
        .premium {
            background: var(--gredient-6) !important;
        }

        .plan-box {
            padding: 1rem;
            border: 1px solid #c9c9c9;
            border-radius: 1rem;

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
    </style>
</head>

<body>


    <!-- Section 1 -->
    <section class="hero_Section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <img src="{{ asset('public/img/libraro.png') }}" alt="logo" class="logo">
                </div>
                <div class="col-lg-6">
                    <ul class="">
                        <li class="">
                            <a class="active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="">
                            <a class="" href="#">Features</a>
                        </li>
                        <li class="">
                            <a class="" href="#">Pricing</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h4 class="head-text-1">Transform Your <br>
                        Library into a Smart Space</h4>
                    <h2 class="head-text-2">Simplify Booking, <br>
                        Maximize Productivity!</h2>
                    <h3 class="head-text-3">Simplify. Manage. Grow. Succeed.</h3>
                    <a href="" class="cta">Sign Up Now!</a>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('public/img/dashbaord.png') }}" alt="ok" class="img-fluid">
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
                                <img src="" alt="">
                            </div>
                        </div>
                        <div class="item">
                            <div class="product-features-box">
                                <h4>Engage with Our Seat Mapping Feature: Expired and Extended Highlights</h4>
                                <img src="" alt="">
                            </div>
                        </div>
                        <div class="item">
                            <div class="product-features-box">
                                <h4>Efficient & Seamless
                                    Reporting that make you Hasselfree</h4>
                                <img src="" alt="">
                            </div>
                        </div>
                        <div class="item">
                            <div class="product-features-box">
                                <h4>Create Custom Plan as per learner Requirement</h4>
                                <img src="" alt="">
                            </div>
                        </div>
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
            </div>
        </div>
    </section>

    <div class="our-plan">
        <div class="container">
            <!-- Dynamic 3 -->
            <div class="row g-4 justify-content-center">
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
                                <div class="message">Efficient & Seamless
                                    Seat Booking System with History</div>
                                <div class="customer-info">
                                    <img src="{{ asset('public/img/user2.png') }}" alt="user" class="profile">
                                    <div class="customer-details">
                                        <h4>ICC Library, KOTA</h4>
                                        <span>Working form 5 years</span>
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
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Qus 1: What is the purpose of this portal?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <strong>Answer</strong> This portal allows library owners to manage their libraries
                                    more efficiently by offering seat reservations, user management, and detailed
                                    reporting. It provides a seamless experience for learners to book seats and access
                                    library services online.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Qus 2: How do library owners manage their library using the portal?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <strong>Answer</strong> Library owners can log in to their admin dashboard to
                                    configure seat availability, track bookings, view user analytics, and manage library
                                    policies. The portal also offers tools to customize booking rules and timings.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Qus 3:What features does the portal offer for learners?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <strong>This is the third item's accordion body.</strong> It is hidden by default,
                                    until the collapse plugin adds the appropriate classes that we use to style each
                                    element. These classes control the overall appearance, as well as the showing and
                                    hiding via CSS transitions. You can modify any of this with custom CSS or overriding
                                    our default variables. It's also worth noting that just about any HTML can go within
                                    the <code>.accordion-body</code>, though the transition does limit overflow.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Support -->


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