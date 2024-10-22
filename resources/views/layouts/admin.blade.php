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
                        const sessionLifetime = {{ config('session.lifetime') }} * 60;  // Convert to seconds
                        const warningTime = sessionLifetime - 60;  // Show popup 1 minute before expiration
                
                        setTimeout(function() {
                            Swal.fire({
                                title: 'Session Expiring Soon',
                                text: 'Your session will expire in 1 minute. Please save your work or stay active.',
                                icon: 'warning',
                                confirmButtonText: 'Stay Logged In'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();  // Refresh to reset session
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

    <script src="{{ url('public/js/main-scripts.js') }}"></script>
    <script src="{{ url('public/js/main-validation.js') }}"></script>
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">

    <!-- Include DataTables JS -->

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

    <script>
        $(document).ready(function() {
            // Initialize validation
            $(".validateForm").validate({
                errorPlacement: function(error, element) {
                    if (element.is(':checkbox') || element.is(':radio')) {
                        element.closest('.form-group').find('.error-msg').append(error);
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element) {
                    $(element).addClass("is-invalid");
                },
                unhighlight: function(element) {
                    $(element).removeClass("is-invalid");
                },
                ignore: ".no-validate", // Skip validation for fields with the 'no-validate' class
                submitHandler: function(form) {
                    // Prevent form submission if validation fails
                    if ($(form).valid()) {
                        alert("Form successfully submitted!");
                        form.submit(); // Submit form only if valid
                    }
                }
            });

            // Add custom validator method for file size
            $.validator.addMethod("filesize", function(value, element, param) {
                // Check if file is selected and its size
                if (element.files.length === 0) {
                    return false; // Return false if no file is selected
                }
                return this.optional(element) || (element.files[0].size <= param);
            }, "File size must be less than {0}.");

            // Add custom validator for file extension
            $.validator.addMethod("extension", function(value, element, param) {
                const allowedExtensions = param.split('|');
                const fileExtension = value.split('.').pop().toLowerCase();
                return this.optional(element) || allowedExtensions.includes(fileExtension);
            }, "Invalid file type. Only {0} are allowed.");

            $.validator.addMethod("regex", function(value, element, regexpr) {
                return regexpr.test(value); // Return true if the regex passes
            }, "Invalid format.");

            // Apply validation rules for each input type
            const fields = [{
                    type: 'input[type="text"]',
                    rules: {
                        required: true,
                        minlength: 2
                    },
                    messages: {
                        required: "This field is required.",
                        minlength: "Enter at least 2 characters."
                    }
                },
                {
                    type: 'input[type="email"]',
                    rules: {
                        required: true,
                        email: true,
                        regex: /^[^\s@]+@[^\s@]+\.[a-z]{2,}$/i // Correct regex for email validation
                    },
                    messages: {
                        required: "This field is required.",
                        email: "Enter a valid email address.",
                        regex: "Email must contain '@' and end with a valid domain (e.g., .com, .in)."
                    }
                },
                {
                    type: 'input[type="number"]',
                    rules: {
                        required: true,
                        number: true,
                        min: 1
                    },
                    messages: {
                        required: "This field is required.",
                        number: "Enter a valid number.",
                        min: "Value must be greater than 0."
                    }
                },
                {
                    type: 'select',
                    rules: {
                        required: true
                    },
                    messages: {
                        required: "Please select an option."
                    }
                },
                {
                    type: 'textarea',
                    rules: {
                        required: true,
                        minlength: 10
                    },
                    messages: {
                        required: "This field is required.",
                        minlength: "Enter at least 10 characters."
                    }
                },
                {
                    type: 'input[type="checkbox"]',
                    rules: {
                        required: true
                    },
                    messages: {
                        required: "You must agree to the terms."
                    }
                },
                {
                    type: 'input[type="radio"]',
                    rules: {
                        required: true
                    },
                    messages: {
                        required: "Please select an option."
                    }
                },
                {
                    type: 'input[type="date"]',
                    rules: {
                        required: true,
                        date: true
                    },
                    messages: {
                        required: "Please select a date.",
                        date: "Enter a valid date."
                    }
                },
                {
                    type: 'input[type="file"]',
                    rules: {
                        required: true,
                        extension: "jpg|jpeg", // Allowed file types
                        filesize: 5242880 // Max file size: 5MB
                    },
                    messages: {
                        required: "Please upload a file.",
                        extension: "Only JPG and JPEG files are allowed.", // Correct file types in message
                        filesize: "File size must be less than 5MB."
                    }
                },
                {
                    type: 'input[type="password"]',
                    rules: {
                        required: true,
                        minlength: 6,
                    },
                    messages: {
                        required: "Password is required.",
                        minlength: "Password must be at least 6 characters long."
                    }
                }
            ];

            // Apply rules and messages dynamically based on input types
            fields.forEach(field => {
                $(field.type).each(function() {
                    if (!$(this).hasClass('no-validate')) {
                        $(this).rules("add", {
                            ...field.rules,
                            messages: field.messages
                        });
                    }
                });
            });

            // Add validation rules for the password confirmation field if found
            if ($('.confirm-password').length > 0) {
                $('.confirm-password').rules('add', {
                    required: true,
                    matchPassword: true,
                    messages: {
                        required: "Please confirm your password.",
                        matchPassword: "Passwords do not match."
                    }
                });
            }

            // Restrictions for input fields without validation
            $(document).on('input', '.digit-only', function() {
                this.value = this.value.replace(/[^0-9.]/g, ''); // Restrict to numbers and decimal
            });

            $(document).on('input', '.char-only', function() {
                this.value = this.value.replace(/[^a-zA-Z ]/g, ''); // Restrict to alphabetic characters and allow single space
            });

            $(document).on('input', '.char-with-sps', function() {
                this.value = this.value.replace(/[^a-zA-Z!@#\$%\^\&*\)\(+=._-\s]/g, ''); // Allow letters, special symbols, and spaces
            });

            // Explicit validation check for file input on file change
            $('input[type="file"]').on('change', function() {
                $(this).valid(); // Force validation for file input on change
            });
        });
    </script>
    <!-- jQuery -->
    <script>
        $(document).ready(function() {
            $('#toggleIcon').click(function() {
                $('#idProofFields').slideToggle(); // Toggle visibility of the fields

                // Change icon based on visibility
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
                // Toggle the visibility of the corresponding .info-card
                $(this).next('.info-card').toggle();
            });
        });

        $(document).ready(function() {
            $('#sidebar').on('click', function() {
                $('.sidebar').toggleClass('w-120');
            });
        });

        // $(document).ready(function() {
        //     // Show loader on page load
        //     $("#loader").show();

        //     // Hide loader when the page is fully loaded
        //     $(window).on("load", function() {
        //         $("#loader").fadeOut("slow");
        //     });
        // });
        // window.history.pushState(null, null, window.location.href);
        // window.onpopstate = function () {
        //     window.history.pushState(null, null, window.location.href);
        //     // Optionally redirect to login or a specific page if needed
        //     window.location.href = '/login'; // Redirect to the login page
        // };
    </script>
</body>

</html>