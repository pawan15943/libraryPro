@extends('layouts.admin')
@section('content')




<div class="row">
    <div class="col-lg-4">
        <div class="myId">
            <h5>Shree Shyam Library</h5>
           wq <hr>
            <!-- Photo -->
            <img src="{{url('public/img/user.png')}}" alt="User" class="id-profile">
            <!-- Personal Info -->
            <div class="learner-info mt-4">
                <h4>Pawan Rathore</h4>
                <h6>Library Id</h6>
            </div>
            <hr>
            <!-- Plan Info -->
            <div class="learner-plan-info mt-4">
                <p class="m-0">FULLDAY (Monthly)</p>
                <p class="m-0">Shift [06:00 AM to 10:00 PM]</p>
                <p class="m-0">End Date : 25 Jan 2025</p>
            </div>
            <hr>
            <h4 class="mt-4">Seat No : 10</h4>
        </div>
    </div>
</div>


@endsection