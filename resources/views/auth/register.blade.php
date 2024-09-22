<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('public/css/style.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <div class="adminstrator-login">
        <div class="left">
            <h2>Empower Learning,<br>
                Shape Futures: <br>
                Your Library, Your Legacy.</h2>
        </div>
        <div class="right">
            <div class="top">
                <img src="" alt="">
            </div>
            <div class="middle">
                <h2>Register your Library</h2>
                <form action="{{ route('library.store') }}" method="POST" class="validateForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3 mt-1">
                        <div class="col-lg-12">
                            <label>Library Name<span>*</span></label>
                            <input type="text" class="form-control char-only @error('library_name') is-invalid @enderror" name="library_name" 
                                value="{{ old('library_name') }}">
                            @error('library_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            
                        </div>
                        <div class="col-lg-12">
                            <label for="">Library Email Id <span>*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" 
                                    value="{{ old('email') }}">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-12">
                            <label for="">Library Contact No. <span>*</span></label>
                            <input type="text" class="form-control digit-only @error('library_mobile') is-invalid @enderror" name="library_mobile" maxlength="10"
                                value="{{ old('library_mobile') }}" >
                            @error('library_mobile')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-12">
                            <label for="">Password <span>*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                        <div class="col-lg-12">
                            <label for="">Confirm Password <span>*</span></label>
                            <input type="password" class="form-control confirm-password @error('password_confirmation') is-invalid @enderror" name="password_confirmation">
                                @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                        <div class="col-lg-12 form-group">
                            <input type="checkbox" class="form-check-input @error('terms') is-invalid @enderror" name="terms" id="terms">
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#">Terms and Conditions</a><sup class="text-danger">*</sup>
                            </label>
                            <div class="error-msg"></div>
                            @error('terms')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        
                        <div class="col-lg-12">
                            <button type="submit" value="Login" class="btn btn-primary button">Register Now  <i class="bi bi-arrow-right"></i></button>
                        </div>
                        <div class="col-lg-12 text-center">
                            <p >If Already Register ? <a href="{{route('login.library')}}" class="links d-inline">Login Now</a></p>
                        </div>
                        
                    </div>
                </form>
            </div>
            <div class="bottom"></div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>

    <script src="{{ url('public/js/main-scripts.js') }}"></script>
    <script src="{{ url('public/js/main-validation.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('input').attr('autocomplete', 'off');
        });
    </script>
</body>

</html>