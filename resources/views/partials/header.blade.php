<div id="loader">
    <div class="spinner"></div>
</div>
<style>
        @php  if(!empty($primary_color)) @endphp
        :root {
            --c1: {{ $primary_color ? $primary_color : '#151F38'  }};
        }
        </style>

   <!-- Expiry Warning -->
<div class="modal" id="planExpiryModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            
            <div class="modal-body">
                <button type="button" class="btn-close align-self-right" data-bs-dismiss="modal" aria-label="Close"></button>
                <img src="{{ url('public/img/plan-expire.png') }}" alt="plan-expire" class="plan-expire img-fluid">
                @if($librarydiffInDays < 0)
                    <p class="text-danger text-center">Your library plan expired {{ abs($librarydiffInDays) }} days. Please consider renewing your plan!</p>
                @elseif($librarydiffInDays > 0)
                <p class="text-danger text-center">Your library plan will expire in {{ $librarydiffInDays }} days. Please consider renewing your plan!</p>
                @else
                <p class="text-danger text-center text-bold">Your library plan expires today. Please consider renewing your plan!</p>
                @endif

                <button type="button" class="btn btn-primary button m-auto w-100" data-bs-dismiss="modal" aria-label="Close">Renew your Subscription</button>
            </div>
        </div>
    </div>
</div>
<!-- Expiry Warning Ends-->

<div class="modal" tabindex="-1" id="todayrenew">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="planExpiryLabel">Renew Plan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <h4>Your library Renew today. Please consider renewing your plan!</h4>
            <button id="renewButton" type="button" class="btn btn-primary" onclick="renewPlan()">Configure Plan</button>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </div>
</div>
<!-- Modal Popup end for Configration -->


<div class="header">
    <div class="d-flex" style="gap:1rem">
        <div class="conatent flex" style="flex: 1;">
            <i class="fa fa-bars mr-2" id="sidebar"></i>
            @if(isset($upcomingdiffInDays) && Auth::guard('library')->check() && $is_renew && $isProfile)
            <small class="text-danger ml-2"> <i class="fa fa-clock"></i>
                @if($upcomingdiffInDays > 0)
                Upcoming Plan after {{$upcomingdiffInDays}} days
                @endif
            </small>
            @endif
          
            @if(isset($librarydiffInDays) && Auth::guard('library')->check() && !$is_renew && $isProfile)

           
            @if($librarydiffInDays > 0)
            <small class="text-success ml-2"> <i class="fa fa-clock"></i> Enjoy your plan for the next {{$librarydiffInDays}} days!</small>

            @elseif($librarydiffInDays < 0)
                <small class="text-danger ml-2"><i class="fa fa-clock"></i> Plan expired {{ abs($librarydiffInDays) }} days ago </small>

            @else
                <small class="text-danger ml-2"> <i class="fa fa-clock"></i> Plan expires today </small>
            @endif

                @if(($librarydiffInDays <= 5 && !$is_renew && $isProfile))
                    <script>
                    window.onload = function() {
                    setTimeout(function() {
                    var modal = new bootstrap.Modal(document.getElementById('planExpiryModal'));
                    modal.show();
                    }, 1000);
                    };
                    </script>

                    <!-- This Script show below popup only once -->
                    <!-- <script>
                        window.onload = function() {
                            if (!sessionStorage.getItem("planExpiryModalShown")) {
                                setTimeout(function() {
                                    var modal = new bootstrap.Modal(document.getElementById('planExpiryModal'));
                                    modal.show();
                                    sessionStorage.setItem("planExpiryModalShown", "true");
                                }, 1000);
                            }
                        };
                    </script> -->
                    <a href="{{ route('subscriptions.choosePlan') }}" type="button" class="btn btn-primary button">Renew your plan</a>
                    
                @endif
            @endif
               <!-- learner  -->      
           

            @if(isset($diffExtendDay) && Auth::guard('learner')->check() && !$learner_is_renew )
                   
              
                @if ($diffInDays < 0 && $diffExtendDay>0)
                <h5 class="text-danger fs-10 d-block ">Enjoy your plan in extend {{ abs($diffExtendDay) }} days.</h5>
                @elseif ($diffInDays < 0 && $diffExtendDay==0)
                <small class="text-danger ml-2"> <i class="fa fa-clock"></i> Plan expires today </small>
                @elseif($diffExtendDay > 0)
                <small class="text-success ml-2"> <i class="fa fa-clock"></i> Enjoy your plan for the next {{$diffExtendDay}} days!</small>
                @else
                <small class="text-danger ml-2"><i class="fa fa-clock"></i> Plan expired {{ abs($diffExtendDay) }} days ago </small>

                @endif
            @endif

        </div>

      
        <!-- old popup position -->
        
        
        <!--Notifications -->
        @if(isset(auth()->user()->unreadNotifications))
            
        
        <div class="notification">
            <div class="dropdown">
                @php
                $guard = null;
                if (Auth::guard('web')->check()) {
                $guard = 'web';
                } elseif (Auth::guard('library')->check()) {
                $guard = 'library';
                } elseif (Auth::guard('learner')->check()) {
                $guard = 'learner';
                }
               
                $unreadNotifications = auth()->user()->unreadNotifications->where('data.guard', $guard);
               
                @endphp
                <a class="dropdown-toggle uppercase" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    <!-- Counter - Alerts -->
                    <span class="badge badge-danger badge-counter">{{ $unreadNotifications->count() }}</span>
                </a>
                <ul class="dropdown-menu notificcation">
                    <li>
                        <!-- Dropdown - Alerts -->
                        <div class="dropdown-menu-1" aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">Notification Center</h6>

                            @forelse($unreadNotifications as $notification)
                            <a class="dropdown-item d-flex align-items-center" data-notification-id="{{ $notification->id }}" href="{{ $notification->data['link'] ?? '#' }}">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">{{ $notification->data['title'] ?? 'No Title' }}</div>
                                </div>
                            </a>
                            @empty
                            <a class="dropdown-item text-center small text-gray-500">No new notifications</a>
                            @endforelse
                            <a class="dropdown-item text-center small text-gray-500" href="{{route('list.notification')}}">Show All Alerts</a>
                        </div>
                    </li>

                </ul>
            </div>

        </div>
        @else
            
        @endif
        <div class="profile">
            <div class="dropdown">
                
                @if(Auth::user()->library_nam !="")
                <span class="icon">{{ strtoupper(substr(Auth::user()->library_name, 0, 2)) }}</span>
                @endif
                Welcome
                <a class="dropdown-toggle uppercase" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{Auth::user()->library_name}}
                {{Auth::user()->name}}
                </a>
                <ul class="dropdown-menu">

                    <li>
                        <img src="{{ url('public/img/user.png') }}" alt="profile" class="LibraryProfile">
                    </li>
                    @if(Auth::guard('library')->check())
                    <li>
                        <a class="dropdown-item text-center" href="javascript:;">
                            <small class="text-danger">Library Unique Id</small><br>
                            {{Auth::user()->library_no ?? ''}}</a>
                    </li> 
                      <!-- Change Password -->
                      <li>
                        <a class="dropdown-item" href="{{route('change.password')}}">
                            <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                            Change Library Password
                        </a>
                    </li>
                    @endif
                   
                  
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
                        location.reload();
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
   
    @if(!$learnerupdates->isEmpty() && Auth::guard('learner')->check())

   
    <div class="latest-notification">
        <b>Updates :</b>
        @foreach($learnerupdates as $key => $value)
        <marquee behavior="" direction="left" class="m-0" scrollamount="5">{{$value->message}}</marquee>
   
        @endforeach
        <button onclick="closeNotification()" class="close">&times;</button>
    </div>
          
    @endif
    @if(!$libraryupdates->isEmpty()  && Auth::guard('library')->check())
   
    <div class="latest-notification">
        <b>Updates :</b>
        @foreach($libraryupdates as $key => $value)
        <marquee behavior="" direction="left" class="m-0" scrollamount="5">{{$value->message}}</marquee>
   
        @endforeach
        <button onclick="closeNotification()" class="close">&times;</button>
    </div>
          
    @endif
    
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.dropdown-item').forEach(function(notificationItem) {
            notificationItem.addEventListener('click', function(e) {
                const notificationId = this.getAttribute('data-notification-id');

                if (notificationId) {
                    fetch('{{ route("notifications.markAsRead") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                notification_id: notificationId
                            })
                        }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Notification marked as read.');
                            }
                        }).catch(error => console.error('Error:', error));
                }
            });
        });
    });
</script>