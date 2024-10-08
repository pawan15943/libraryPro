<div id="loader">
  <div class="spinner"></div>
</div>
<div class="header">
    <div class="d-flex">
        <div class="conatent flex">
            <i class="fa fa-bars mr-2" id="sidebar"></i>
            @if(Auth::guard('library')->check())
                
            <small class="text-danger ml-2"> <i class="fa fa-clock"></i> Plan Expired in {{$diffInDays}} Days</small>
            @if($diffInDays <= 5 && $diffInDays >= 0)
            <script>
               
                window.onload = function() {
                    setTimeout(function() {
                        alert('Your library plan will expire in {{ $diffInDays }} days. Please consider renewing your plan!');
                    }, 1000); 
                };
            </script>
            
            <a href="{{ route('library.home') }}" type="button" class="btn btn-primary button">Renew Plan</a>
            @endif
            @endif
            
        </div>
        <div class="profile">
            <div class="dropdown">
                <a class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{Auth::user()->name}}{{Auth::user()->library_name}}
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <img src="{{ url('public/img/user.png') }}" alt="profile" class="LibraryProfile">
                    </li>
                    <!-- Change Password -->
                    <li>
                        <a class="dropdown-item" href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                            Change Password
                        </a>
                    </li>
                    <!-- Logout -->
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>