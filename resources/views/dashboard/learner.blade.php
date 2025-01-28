@extends('layouts.admin')

@section('title', 'Learner Dashboard')

@section('content')

<div class="dashboard learner">
    <!-- Welcome Message -->
    <div class="row">
        <div class="col-lg-12">
            <div class="greeting-container">
                <i id="greeting-icon" class="fas fa-sun greeting-icon"></i>
                <h2 id="greeting-message" class="typing-text">Good Morning! Student Name!</h2>
            </div>
        </div>
    </div>

    <!-- Library Info -->
    <div class="row g-4">
        <div class="col-lg-9">
            <div class="dashboard-Header">
                <img src="{{url('public/img/bg-library-welcome.png')}}" alt="library" class="img-fluid rounded">
                <h1>Welcome to <span>Libraro</span><br>
                    Let’s Make Your <span class="typing-text"> Library the Place to Be! 📚🌟</span></h1>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="active-plan-box bg-dark">
                <h4>Seat No. : 25</h4>
                <label for="">
                    <!-- Show this Button When Plan Expire -->
                    <!-- <a href="{{ route('subscriptions.choosePlan') }}" class="text-danger">Upgrade Plan</a> -->
                    Active
                </label>
                <div class="d-flex">
                    <ul class="plann-info">
                        <li>My Plan : Full Day (Monthly)</li>
                        <li>Plan End Date :</li>
                        <li>Plan Start Date :</li>
                        <li>Plan Price :</li>
                        <li>Payment Status : Paid</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <!-- Library Features -->

    <div class="row">
        <div class="col-lg-12">
            <h4 class="py-4">Features List</h4>
            <ul class="features">
                <li>
                    <!-- Feature Icon -->
                    <img src="" alt="Feature Icon">
                    <p>Features Name</p>
                </li>
                <li>
                    <!-- Feature Icon -->
                    <img src="" alt="Feature Icon">
                    <p>Features Name</p>
                </li>
                <li>
                    <!-- Feature Icon -->
                    <img src="" alt="Feature Icon">
                    <p>Features Name</p>
                </li>
                <li>
                    <!-- Feature Icon -->
                    <img src="" alt="Feature Icon">
                    <p>Features Name</p>
                </li>
                <li>
                    <!-- Feature Icon -->
                    <img src="" alt="Feature Icon">
                    <p>Features Name</p>
                </li>
                <li>
                    <!-- Feature Icon -->
                    <img src="" alt="Feature Icon">
                    <p>Features Name</p>
                </li>
                <li>
                    <!-- Feature Icon -->
                    <img src="" alt="Feature Icon">
                    <p>Features Name</p>
                </li>
                <li>
                    <!-- Feature Icon -->
                    <img src="" alt="Feature Icon">
                    <p>Features Name</p>
                </li>
                <li>
                    <!-- Feature Icon -->
                    <img src="" alt="Feature Icon">
                    <p>Features Name</p>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <h4 class="py-4">Available Books List</h4>
            <ul class="features mb-4">
                <li>
                    <!-- Book Icon -->
                    <img src="" alt="Book Icon">
                    <p>Book Name</p>
                </li>
                <li>
                    <!-- Book Icon -->
                    <img src="" alt="Book Icon">
                    <p>Book Name</p>
                </li>
                <li>
                    <!-- Book Icon -->
                    <img src="" alt="Book Icon">
                    <p>Book Name</p>
                </li>
                <li>
                    <!-- Book Icon -->
                    <img src="" alt="Book Icon">
                    <p>Book Name</p>
                </li>
                <li>
                    <!-- Book Icon -->
                    <img src="" alt="Book Icon">
                    <p>Book Name</p>
                </li>
                <li>
                    <!-- Book Icon -->
                    <img src="" alt="Book Icon">
                    <p>Book Name</p>
                </li>
                <li>
                    <!-- Book Icon -->
                    <img src="" alt="Book Icon">
                    <p>Book Name</p>
                </li>
                <li>
                    <!-- Book Icon -->
                    <img src="" alt="Book Icon">
                    <p>Book Name</p>
                </li>
                <li>
                    <!-- Book Icon -->
                    <img src="" alt="Book Icon">
                    <p>Book Name</p>
                </li>
            </ul>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <h4 class="py-4">Govt. Exam Information <span class="badge badge-danger">Beta (Coming Soon!)</span></h4>
            <ul class="features mb-4">
                <li>
                    <!-- Book Icon -->
                    <img src="" alt="Book Icon">
                    <p>Exam Name</p>
                </li>
                <li>
                    <!-- Book Icon -->
                    <img src="" alt="Book Icon">
                    <p>Exam Name</p>
                </li>
                <li>
                    <!-- Book Icon -->
                    <img src="" alt="Book Icon">
                    <p>Exam Name</p>
                </li>
                <li>
                    <!-- Book Icon -->
                    <img src="" alt="Book Icon">
                    <p>Exam Name</p>
                </li>
                
            </ul>
        </div>
    </div>
</div>

@endsection