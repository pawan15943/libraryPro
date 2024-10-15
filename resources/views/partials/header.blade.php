<div id="loader">
  <div class="spinner"></div>
</div>
<div class="header">
    <div class="d-flex">
        <div class="conatent flex">
            <i class="fa fa-bars mr-2" id="sidebar"></i>
            @if(isset($librarydiffInDays) && Auth::guard('library')->check() && !$is_renew && $isProfile)
                
            <small class="text-danger ml-2"> <i class="fa fa-clock"></i> Plan Expired in {{$librarydiffInDays}} Days</small>
            @if($librarydiffInDays <= 5 && $librarydiffInDays >= 0 && $isProfile)
            <script>
                window.onload = function() {
                    setTimeout(function() {
                        var modal = new bootstrap.Modal(document.getElementById('planExpiryModal'));
                        modal.show();
                    }, 1000); 
                };
            </script>
            
            <a href="{{ route('subscriptions.choosePlan') }}" type="button" class="btn btn-primary button">Renew Plan</a>
            @endif
            @endif
           
        </div>
        <!-- Modal Popup for Expiry Warning -->
        <div class="modal fade" id="planExpiryModal" tabindex="-1" aria-labelledby="planExpiryLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="planExpiryLabel">Plan Expiry Warning</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4>Your library plan will expire in {{ $librarydiffInDays }} days. Please consider renewing your plan!</h4>
                    </div>
                </div>
            </div>
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