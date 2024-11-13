@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<!-- Content -->
<form action="{{ route('library.profile.update') }}" class="validateForm" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <label for="">Owner Name <span>*</span></label>
                        <input type="text" class="form-control " name="name" value="">
                        
                    </div>
                    <div class="col-lg-6">
                        <label for="">Email <span>*</span></label>
                        <input type="email" class="form-control " name="email" value="">
                        
                    </div>

                    <div class="col-lg-6">
                        <label for="">Mobile Number <span>*</span></label>
                        <input type="text" class="form-control " name="mobile" value="">
                    </div>

                    <div class="col-lg-6">
                        <label for="">City / Location <span>*</span></label>
                        <input type="text" class="form-control " name="city" value="">
                    </div>
                    <div class="col-lg-6">
                        <label for="">Feedback Type <span>*</span></label>
                        <select name="" class="form-select" id="">
                            <option value="">Select</option>
                            <option value="">Product</option>
                            <option value="">Service</option>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label for="">Rating <span>*</span></label>
                        <select name="" class="form-select" id="">
                            <option value="">Select</option>
                            <option value="">1 Star</option>
                            <option value="">2 Star</option>
                            <option value="">3 Star</option>
                            <option value="">4 Star</option>
                            <option value="">5 Star</option>
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <label for="">Feedback Description <span>*</span></label>
                        <textarea name="" id="" class="form-control" placeholder="Feedback Description" style="height:100px !important;"></textarea>
                    </div>
                    <div class="col-lg-6">
                        <label for="">Attachment (If Needed) <span>*</span></label>
                        <input type="file" class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <label for="">Would You Recommend Us? <span>*</span></label>
                        <select name="" class="form-select" id="">
                            <option value="">Select</option>
                            <option value="">Yes</option>
                            <option value="">No</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <input type="submit" value="Submit Feedback" class="btn btn-primary button">
                    </div>
                </div>

            </div>
        </div>

    </div>
</form>
</div>
@include('library.script')
@endsection