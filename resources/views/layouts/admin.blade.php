<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('public/css/style.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.css" />
   

</head>

<body>

    <div class="library-dashbaord">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <div class="content-area">
            <!-- Header -->
            @include('partials.header')


            <!-- Begin Page Content -->
            <div class="content">
                <div class="container-fluid">
                    @include('partials.breadcrumbs')
                    @yield('content')
                    <script>
                        // Session expiration popup logic here
                        const sessionLifetime = {{config('session.lifetime')}}* 60; // Convert to seconds
                        const warningTime = sessionLifetime - 60; // Show popup 1 minute before expiration

                        setTimeout(function() {
                            Swal.fire({
                                title: 'Session Expiring Soon',
                                text: 'Your session will expire in 1 minute. Please save your work or stay active.',
                                icon: 'warning',
                                confirmButtonText: 'Stay Logged In'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload(); // Refresh to reset session
                                }
                            });
                        }, warningTime * 1000);
                    </script>
                </div>
            </div>


            <!-- Footer  -->
            @include('partials.footer')
        </div>


    </div>
   


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="{{ url('public/js/main-scripts.js') }}"></script>
    <script src="{{ url('public/js/main-validation.js') }}"></script>
   
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>

    <script>
        $(document).ready(function() {
            // Attach event listeners for collapse events once
            $('[data-bs-toggle="collapse"]').each(function() {
                var $btn = $(this);
                var $icon = $btn.find('i.fa-angle-right');
                var targetCollapse = $btn.data('bs-target');

                $(targetCollapse).on('show.bs.collapse', function() {
                    $icon.addClass('rotate');
                });

                $(targetCollapse).on('hide.bs.collapse', function() {
                    $icon.removeClass('rotate');
                });
            });

            // Fix for initial state
            $('[data-bs-toggle="collapse"]').each(function() {
                var $btn = $(this);
                var $icon = $btn.find('i.fa-angle-right');
                var targetCollapse = $btn.data('bs-target');

                if ($(targetCollapse).hasClass('show')) {
                    $icon.addClass('rotate');
                } else {
                    $icon.removeClass('rotate');
                }
            });
        });
    </script>


    <!-- jQuery -->
    <script>
        $(document).ready(function() {
            $('#toggleIcon').click(function() {
                $('#idProofFields').slideToggle();

                if ($('#idProofFields').is(':visible')) {
                    $('#toggleIcon').removeClass('fa-plus').addClass('fa-minus');
                } else {
                    $('#toggleIcon').removeClass('fa-minus').addClass('fa-plus');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.info-icon').on('click', function() {
                $(this).next('.info-card').toggle();
            });
        });

        $(document).ready(function() {
            $('#sidebar').on('click', function() {
                $('.sidebar').toggleClass('w-120');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('selectstart', function(e) {
                if (!$(e.target).is('input, select, textarea')) {
                    e.preventDefault();
                }
            });

            $(document).on('mousedown', function(e) {
                if (!$(e.target).is('input, select, textarea')) {
                    e.preventDefault();
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Function to show a popup
            function showOfflinePopup() {
                Swal.fire({
                    title: 'No Internet Connection',
                    text: 'Your internet connection is lost. Please check your connection.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }

            // Check if already offline on page load
            if (!navigator.onLine) {
                showOfflinePopup();
            }

            // Listen for offline and online events
            window.addEventListener('offline', function() {
                showOfflinePopup();
            });

            window.addEventListener('online', function() {
                Swal.fire({
                    title: 'Back Online',
                    text: 'Your internet connection has been restored.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });
        });

        $(document).ready(function() {
            function addClassOnResize() {
                if ($(window).width() <= 480) {
                    $('.sidebar').addClass('w-120');
                } else {
                    $('.sidebar').removeClass('w-120');
                }
            }

            // Run the function on window resize
            $(window).resize(function() {
                addClassOnResize();
            });

            // Initial check when the page loads
            addClassOnResize();
        });
    </script>



</body>

</html>