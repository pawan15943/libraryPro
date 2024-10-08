
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="{{ asset('public/css/style.css') }}" rel="stylesheet">
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
            <div class="middle" style="ba">
                <h5>Welcome Back, </h5>
                <h2>Library Learner</h2>
                @if(session('success'))
                <div class="alert alert-success mb-0 mt-1">
                    {{ session('success') }}
                </div>
                @elseif($errors->has('error'))
                    <div class="alert alert-danger mb-0 mt-1">
                        {{ $errors->first('error') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <input type="hidden" name="user_type" value="learner">
                    <div class="row g-3 mt-1">
                        <div class="col-lg-12">
                            <label for="">Email Address</label>
                            <input id="email"  type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-12">
                            <label for="">Password</label>
                            <input id="password"  type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                       
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary button">Login Now <i class="bi bi-arrow-right"></i></button>
                        </div>
                        
                    </div>
                </form>
            </div>
            <div class="bottom"></div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
</body>

</html>

