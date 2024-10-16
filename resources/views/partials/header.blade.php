<div id="loader">
  <div class="spinner"></div>
</div>
<div class="header">
    <div class="d-flex">
        <div class="conatent flex">
            <i class="fa fa-bars mr-2" id="sidebar"></i>
            @if(isset($librarydiffInDays) && Auth::guard('library')->check() && !$is_renew && $isProfile)
                
            <small class="text-danger ml-2"> <i class="fa fa-clock"></i>
                @if($librarydiffInDays > 0)
                Plan expires in {{$librarydiffInDays}} days
                @elseif($librarydiffInDays < 0)
                    Plan expired {{ abs($librarydiffInDays) }} days ago
                @else
                    Plan expires today
                @endif
                
                
            </small>
          
                @if(($librarydiffInDays <= 5 &&  !$is_renew && $isProfile))
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
                        @if($librarydiffInDays < 0)
                        <h4>Your library plan expired {{ abs($librarydiffInDays) }} days. Please consider renewing your plan!</h4>
                        @elseif($librarydiffInDays > 0)
                        <h4>Your library plan will expire in {{ $librarydiffInDays }} days. Please consider renewing your plan!</h4>
                        @else
                        <h4>Your library plan expires today. Please consider renewing your plan!</h4>
                        @endif

                       
                    </div>
                </div>
            </div>
        </div>
         <!-- Modal Popup end for Expiry Warning -->
        <!-- Modal Popup for Configration -->
        <div class="modal fade" id="todayrenew" tabindex="-1" aria-labelledby="planExpiryLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="planExpiryLabel">Renew Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4>Your library Renew today. Please consider renewing your plan!</h4>
                        <button id="renewButton" type="button" class="btn btn-primary" onclick="renewPlan()">Renew Plan</button>
                    
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Popup end for Configration -->

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
        @if($today_renew)
        <script>
            window.onload = function() {
                setTimeout(function() {
                    var modal = new bootstrap.Modal(document.getElementById('todayrenew'));
                    modal.show();
                }, 1000); 
            };

            // Function to call renewConfigration via AJAX
            function renewPlan() {
                // Disable the button to avoid multiple clicks
                document.getElementById('renewButton').disabled = true;

                // Call the renew configuration function via AJAX
                $.ajax({
                    url: "{{ route('renew.configration') }}",
                    type: 'GET', // Change this to 'POST' if using POST method
                    success: function(response) {
                        // Show success message
                        alert("Plan successfully renewed!");

                        // Optionally close the modal after success
                        var modal = bootstrap.Modal.getInstance(document.getElementById('todayrenew'));
                        modal.hide();
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error("Error renewing plan:", error);
                        alert("Failed to renew the plan. Please try again later.");
                    },
                    complete: function() {
                        // Re-enable the button
                        document.getElementById('renewButton').disabled = false;
                    }
                });
            }
        </script>
        @endif



    </div>
</div>