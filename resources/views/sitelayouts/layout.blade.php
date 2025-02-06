<!DOCTYPE html>
<html lang="en">

<head>
    @php
        $current_route = Route::currentRouteName();
        $page=App\Models\Page::where('route',$current_route)->first();
        
    @endphp
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('public/img/favicon.ico') }}" type="image/x-icon">
   
    <title>{{$page->meta_title}}</title>
    
    <meta type="description" value="{{$page->meta_description}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <link rel="stylesheet" href="{{ asset('public/css/home-style.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Libraru Schema -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "SoftwareApplication",
            "name": "Libraro",
            "url": "https://www.libraro.in",
            "applicationCategory": "Library Management Software",
            "operatingSystem": "Windows, Web-based",
            "description": "Libraro is a powerful library management software to simplify cataloging, membership, and book tracking for schools, colleges, and public libraries.",
            "offers": {
                "@type": "Offer",
                "price": "Contact for pricing",
                "priceCurrency": "INR"
            },
            "author": {
                "@type": "Organization",
                "name": "Techito"
            },
            "softwareVersion": "1.0",
            "aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "4.8",
                "reviewCount": "256"
            }
        }
    </script>
</head>


<body>

    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
    <div id="loader">
        <dotlottie-player src="https://lottie.host/db22cec8-bed8-4ce9-8993-e2c88bff2231/qJmiiH5Orw.lottie" background="transparent" speed="1" style="width: 150px; height: 150px" loop autoplay></dotlottie-player>
    </div>


    @include('sitelayouts.header')
    @yield('content')
    @include('sitelayouts.footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
    <script src="{{ url('public/js/main-validation.js') }}"></script>

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
            $(".buy-now-btn").attr("data-plan_mode", plan_mode);
            subscription_price(plan_mode);

            $('#plan_mode').on('change', function() {
                var plan_mode = $(this).val();
                $(".buy-now-btn").attr("data-plan_mode", plan_mode);
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
                                let modeText = plan_mode == 1 ? " (Monthly)" : plan_mode == 2 ? " (Yearly)" : "";
                                $('#subscription_fees_' + subscription.id).text(subscription.fees + modeText);
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
    <script>
        $(window).on('load', function() {
            $('#loader').fadeOut('slow', function() {
                $(this).remove(); // Remove the loader from the DOM after fading out
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".buy-now-btn").click(function() {
                var planId = $(this).data("id");
                var planMode = $("#plan_mode").val(); // Get the selected plan mode from dropdown

                $.ajax({
                    url: "{{ route('store.selected.plan') }}", // Route to store in session
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        plan_id: planId,
                        plan_mode: planMode
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            window.location.href = "{{ route('register') }}"; // Redirect to registration page
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>