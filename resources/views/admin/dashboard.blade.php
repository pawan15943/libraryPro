@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
<div class="row">
    <div class="col-lg-9">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="dashibox">
                    <div class="animated-square"></div>
                    <h4>Total Enrollments</h4>
                    <div class="d-flex">
                        <h2 class="counter" data-count="{{$total_enrollment}}">0</h2>
                        <a class="wave-button" href="{{route('student.index')}}"><i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dashibox">
                    <div class="animated-square"></div>
                    <h4>Course Completed</h4>
                    <div class="d-flex">
                        <h2 class="counter" data-count="{{$course_complete}}">0</h2>
                        <a class="wave-button" href="{{route('student.index')}}"><i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dashibox">
                    <div class="animated-square"></div>
                    <h4>Course Revenue</h4>
                    <div class="d-flex">
                        <h2 class="counter" data-count="{{$monthly_revenue}}">0</h2>
                        <a class="wave-button" href="{{route('admin.accounts')}}"><i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dashibox">
                    <div class="animated-square"></div>
                    <h4>Booked Seats </h4>
                    <div class="d-flex">
                        <h2 class="counter" data-count="{{$booked_seats}}">0</h2>
                        <a class="wave-button" href="{{route('learners')}}"><i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dashibox">
                    <div class="animated-square"></div>
                    <h4>Available Seats</h4>
                    <div class="d-flex">
                        <h2 class="counter" data-count="{{$availble_seats}}">0</h2>
                        <a class="wave-button" href="{{route('seats')}}" class="water-drop-button"><i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dashibox">
                    <div class="animated-square"></div>

                    <h4>Library Revenue</h4>
                    <div class="d-flex">
                        <h2 class="counter" data-count="{{$library_revenue}}">0</h2>
                        <a class="wave-button" href="{{route('learners')}}"><i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="course-list">
            <h4>Course Wise Enrollments</h4>
            <ul>
                @foreach ($count_course_wise as $course)
                <li>
                    <div class="d-flex">
                        <h5>{{$course->course_name }}</h5>
                        <div class="count">{{ $course->student_count }}</div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="row mt-4 g-4">
    <div class="col-lg-12">
        <div class="course-list">
            <h4>Monthly Student Enrollments</h4>
            <canvas id="studentEnrollmentChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="course-list">
            <h4>Monthly Seat Booked</h4>
            <ul>
                @foreach ($planwise_count as $count)
                <li>
                    <div class="d-flex">
                        <h5>{{$count->name }}</h5>
                        <div class="count">{{ $count->customer_count }}</div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="course-list">
            <h4>Monthly Seat Bookings</h4>
            <canvas id="libraryBookedChart" height="120"></canvas>
        </div>
    </div>

</div>
<!-- Content Row -->


<script>
    $(document).ready(function() {
        $('.counter').each(function() {
            var $this = $(this);
            var countTo = $this.data('count');
            $({
                countNum: $this.text()
            }).animate({
                countNum: countTo
            }, {
                duration: 3000, // Duration of the animation in milliseconds
                easing: 'swing', // Easing function
                step: function() {
                    $this.text(Math.floor(this.countNum));
                },
                complete: function() {
                    $this.text(this.countNum);
                }
            });
        });
    });
</script>
<script>
    const ctx = document.getElementById('studentEnrollmentChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(54, 162, 235, 0.8)'); // Light Blue
    gradient.addColorStop(1, 'rgba(54, 162, 235, 0.3)'); // Lighter Blue

    const studentEnrollmentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels_stu), // Months
            datasets: [{
                label: 'Student Enrollments',
                data: @json($data_stu), // Enrollment counts
                backgroundColor: gradient,
                borderColor: 'rgba(54, 162, 235, 1)', // Blue Border
                borderWidth: 0,
                borderRadius: 15, // Rounded Edges
                barThickness: 20, // Bar Width
                borderSkipped: false,
            }]
        },
        options: {
            animation: {
                duration: 2000, // Animation duration
                easing: 'easeInOutQuart' // Animation easing
            },

            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: false // Remove y-axis grid lines
                    },
                    ticks: {
                        display: false // Hide y-axis labels
                    },
                    border: {
                        display: false // Hide y-axis border line
                    }
                },
                x: {
                    grid: {
                        display: false // Remove x-axis grid lines
                    },
                    
                    border: {
                        display: false // Hide y-axis border line
                    }
                }
            },
            plugins: {
                legend: {
                    display: true, // Show legend
                    labels: {
                        boxWidth: 0, // Remove the box
                        padding: 10, // Add padding
                        color: 'rgba(0, 0, 0, 0.7)' // Adjust label color
                    }
                },
                datalabels: {
                    color: 'rgba(0, 0, 0, 0.7)', // Color of the data labels
                    display: true, // Display data labels
                    anchor: 'end', // Position of the data labels
                    align: 'top', // Align data labels to the top
                    offset: 4, // Offset from the bar
                    font: {
                        weight: 'bold', // Font weight
                        size: 12 // Font size
                    }
                }
            }
        },
        plugins: [ChartDataLabels] // Include the data labels plugin
    });
</script>
<script>
    const ctx1 = document.getElementById('libraryBookedChart').getContext('2d');

    // Create gradient for the libraryBookedChart
    const gradientLibrary = ctx1.createLinearGradient(0, 0, 0, 400);
    gradientLibrary.addColorStop(0, 'rgba(75, 192, 192, 0.8)'); // Darker Teal
    gradientLibrary.addColorStop(1, 'rgba(75, 192, 192, 0.3)'); // Lighter Teal

    const libraryBookedChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: @json($labels_cust), // Months
            datasets: [{
                label: 'Learners',
                data: @json($data_cust), // Enrollment counts
                backgroundColor: gradientLibrary, // Apply gradient
                borderColor: 'rgba(75, 192, 192, 1)', // Teal Border
                borderWidth: 0,
                borderRadius: 15, // Rounded Edges
                barThickness: 20, // Bar Width
                borderSkipped: false
            }]
        },
        options: {
            animation: {
                duration: 2000, // Animation duration
                easing: 'easeInOutQuart' // Animation easing
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: false // Remove y-axis grid lines
                    },
                    ticks: {
                        display: false // Hide y-axis labels
                    },
                    border: {
                        display: false // Hide y-axis border line
                    }
                },
                x: {
                    grid: {
                        display: false // Remove x-axis grid lines
                    },
                    border: {
                        display: false // Hide y-axis border line
                    }
                }
            },
            plugins: {
                legend: {
                    display: true, // Show legend
                    labels: {
                        boxWidth: 0, // Remove the box
                        padding: 10, // Add padding
                        color: 'rgba(0, 0, 0, 0.7)' // Adjust label color
                    }
                },
                datalabels: {
                    color: 'rgba(0, 0, 0, 0.7)', // Color of the data labels
                    display: true, // Display data labels
                    anchor: 'end', // Position of the data labels
                    align: 'top', // Align data labels to the top
                    offset: 4, // Offset from the bar
                    font: {
                        weight: 'bold', // Font weight
                        size: 12 // Font size
                    }
                }
            }
        },
        plugins: [ChartDataLabels] // Include the data labels plugin
    });
</script>

@endsection