@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-lg-9">
        <div class="actions">
            <div class="upper-box">
                <div class="d-flex">
                    <h4 class="mb-3">Leraners Info</h4>
                    <a href="javascript:void(0);" class="go-back"
                        onclick="window.history.back();">Go
                        Back <i class="fa-solid fa-backward pl-2"></i></a>
                </div>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <span>Name</span>
                        <h5 class="uppercase">{{ Auth::user()->name }}</h5>
                    </div>
                    <div class="col-lg-6">
                        <span>Date Of Birth </span>
                        <h5>{{ Auth::user()->dob }}</h5>
                    </div>
                    <div class="col-lg-6">
                        <span>Mobile Number</span>
                        <h5>+91-{{ Auth::user()->mobile }}</h5>
                    </div>
                    <div class="col-lg-6">
                        <span>Email Id</span>
                        <h5>{{ Auth::user()->email }}</h5>
                    </div>
                    <div class="col-lg-6">
                        <span>Address</span>
                        <h5>{{ Auth::user()->address }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('learner.script')
@endsection