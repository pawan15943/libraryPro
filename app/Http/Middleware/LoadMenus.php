<?php

namespace App\Http\Middleware;

use App\Models\Hour;
use App\Models\Learner;
use App\Models\LearnerDetail;
use App\Models\Library;
use App\Models\LibraryTransaction;
use App\Models\Menu;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\PlanType;
use App\Models\Seat;
use Closure;
use Illuminate\Support\Facades\View;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use Illuminate\Support\Carbon;
class LoadMenus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
     public function handle($request, Closure $next)
     {
        $checkSub = false;
        $ispaid = false;
        $iscomp = false;
        $isProfile = false;
        $isEmailVeri = false;

        $menus = collect();

        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $menus = Menu::where('status',1)->where(function ($query) {
                $query->where('guard', 'web')
                      ->orWhereNull('guard'); 
            })->with('children')->orderBy('order')->get();
        } elseif (Auth::guard('library')->check()) {
          

            $user = Auth::guard('library')->user();
            $menus = Menu::where('status',1)->where(function ($query) {
                $query->where('guard', 'library')
                      ->orWhereNull('guard'); 
            })->with('children')->orderBy('order')->get();

           
            
        } elseif (Auth::guard('learner')->check()) {
            $user = Auth::guard('learner')->user();
            $menus = Menu::where('status',1)->where(function ($query) {
                $query->where('guard', 'learner')
                      ->orWhereNull('guard'); 
            })->with('children')->orderBy('order')->get();
        }

        view()->share('menus', $menus);

        if (Auth::check()) {
            $isEmailVeri = Library::where('id', Auth::user()->id)->whereNotNull('email_verified_at')->exists();
            $checkSub = LibraryTransaction::where('library_id', Auth::user()->id)->where('status', 1)->exists();
            $ispaid = Library::where('id', Auth::user()->id)->where('is_paid', 1)->exists();
            $iscomp = Library::where('id', Auth::user()->id)->where('status', 1)->exists();
            $isProfile = Library::where('id', Auth::user()->id)->where('is_profile', 1)->exists();
        
            $diffInDays = 0; // Initialize the variable to a default value
            $today = date('Y-m-d');
           
        $value = LibraryTransaction::where('library_id',  Auth::user()->id)->where('is_paid',1)->orderBy('id','desc')->first();
        
        $is_renew=LibraryTransaction::where('library_id',Auth::user()->id) 
                ->where('is_paid', 1)
                ->where('status', 0)
                ->where('start_date','>=', date('Y-m-d'))
                ->exists();
        $is_expire=false;
        if ($value) {
            $today = Carbon::today();
            $endDate = Carbon::parse($value->end_date);
            $librarydiffInDays = $today->diffInDays($endDate, false);
            if($librarydiffInDays <= 5){
                $is_expire=true;
            }
            
        }else{
            $librarydiffInDays=0;
        }

        if($is_renew){
            $is_renew_val=LibraryTransaction::where('library_id',Auth::user()->id) 
                ->where('is_paid', 1)
                ->where('status', 0)
                ->where('start_date','>', date('Y-m-d'))->first();
            $today = Carbon::today();
            if($is_renew_val){
                $start_date = Carbon::parse($is_renew_val->start_date);
                $upcomingdiffInDays = $today->diffInDays($start_date);
            }else{
                $upcomingdiffInDays = null; 
            }
           
        }else {
           
            $upcomingdiffInDays = null; 
        }
            
        
            $this->statusInactive();
            $this->updateLibraryStatus();
            $today_renew = LibraryTransaction::where('library_id', Auth::user()->id)
            ->where('is_paid', 1)
            ->where('status', 0)
            ->where('start_date', '<=', $today)
            ->where('end_date','>', date('Y-m-d'))
            ->exists();

            $learnerExtendText='Extend Days are Active Now & Remaining Days are';

            $total_seats=Seat::count();
            $availble_seats=Seat::where('total_hours','==',0)->count(); 
          
            $booked_seats=Seat::where('is_available','!=',1)->where('total_hours','!=',0)->count();
            $active_seat_count =  Learner::where('library_id',Auth::user()->id)->where('status', 1) 
            ->distinct() 
            ->count('seat_no');
            $expired_seat=Learner::where('library_id',Auth::user()->id)->where('status',0)->count();
            // Initialize an array to hold the counts for each plan type
                $counts = [];

                // Define the mapping of day_type_id to their respective count variable names
                $dayTypeIds = [
                    1 => 'fullDayCount',
                    2 => 'firstHalfCount',
                    3 => 'secondHalfCount',
                    4 => 'hourly1Count',
                    5 => 'hourly2Count',
                    6 => 'hourly3Count',
                    7 => 'hourly4Count',
                ];

                foreach ($dayTypeIds as $dayTypeId => $countName) {
                    // Fetch PlanType, first checking for non-trashed, then trashed if not found
                    $planType = PlanType::where('day_type_id', $dayTypeId)
                        ->withoutTrashed()
                        ->first();

                    if (!$planType) {
                        $planType = PlanType::where('day_type_id', $dayTypeId)
                            ->withTrashed()
                            ->first();
                    }
                    $planTypeId = $planType->id ?? 0;
                    $counts[$countName] = LearnerDetail::where('status', 1)
                        ->where('plan_type_id', $planTypeId)
                        ->count();
                }

                // Now you can access the counts for each type
                $fullday_count = $counts['fullDayCount'];
                $firstHalfCount = $counts['firstHalfCount'];
                $secondHalfCount = $counts['secondHalfCount'];
                $hourly1Count = $counts['hourly1Count'];
                $hourly2Count = $counts['hourly2Count'];
                $hourly3Count = $counts['hourly3Count'];
                $hourly4Count = $counts['hourly4Count'];

            
            View::share('checkSub', $checkSub);
            View::share('ispaid', $ispaid);
            View::share('isProfile', $isProfile);
            View::share('isEmailVeri', $isEmailVeri);
            View::share('iscomp', $iscomp);
            View::share('librarydiffInDays', $librarydiffInDays); 
            View::share('is_renew', $is_renew); 
            View::share('is_expire', $is_expire); 
            View::share('today_renew', $today_renew); 
            View::share('upcomingdiffInDays', $upcomingdiffInDays); 
            View::share('learnerExtendText', $learnerExtendText); 
            View::share('total_seats', $total_seats); 
            View::share('active_seat_count', $active_seat_count); 
            View::share('expired_seat', $expired_seat); 
            View::share('availble_seats', $availble_seats); 
            View::share('booked_seats', $booked_seats); 
            View::share('fullday_count', $fullday_count); 
            View::share('firstHalfCount', $firstHalfCount); 
            View::share('secondHalfCount', $secondHalfCount); 
            View::share('hourly1Count', $hourly1Count); 
            View::share('hourly2Count', $hourly2Count); 
            View::share('hourly3Count', $hourly3Count); 
            View::share('hourly4Count', $hourly4Count); 
          
            
        }
        

     
         return $next($request);
     }

     public function updateLibraryStatus()
    {
        $today = Carbon::today();
        $hourexist = Hour::count();
        $extendexist = Hour::whereNotNull('extend_days')->count();
        $seatExist = Seat::count();
        $plan = Plan::count();
        $plantype = PlanType::where('library_id', auth()->user()->id)
                            ->where(function ($query) {
                                $query->where('day_type_id', 1)
                                    ->orWhere('day_type_id', 2)
                                    ->orWhere('day_type_id', 3);
                            })
                            ->count();
        $planPrice = PlanPrice::count();
        $is_active=LibraryTransaction::where('library_id',Auth::user()->id)->where('is_paid',1) ->where('end_date', '>', $today->format('Y-m-d'))->exists();
        if ($hourexist > 0 && $extendexist > 0 && $seatExist > 0 && $plan > 0 && $plantype >= 3 && $planPrice >= 3 && $is_active) {
            $id = Auth::user()->id;
            $library = Library::findOrFail($id);

            if ($library->status != 1) {
                $library->status = 1;
                $library->save();
            }
        }
    

    }

    public function statusInactive(){
        $userId = Auth::user()->id;
        $today = Carbon::today();
        $yesterday = $today->subDay();
        $statuscheck=LibraryTransaction::where('library_id',  Auth::user()->id)->where('is_paid',1)->where('end_date', '<=', $yesterday->format('Y-m-d'))->exists();
        $is_renew=LibraryTransaction::where('library_id',Auth::user()->id)->where('is_paid',1) ->where('end_date', '>', $today->format('Y-m-d'))->exists();
        if($statuscheck && ($is_renew==false)){
            Library::where('id', $userId)
           ->where('status', 1)
           ->update(['status' => 0,'is_paid'=>0]);

            // Mark the expired transaction status as inactive
            LibraryTransaction::where('library_id', $userId)
                            ->where('is_paid', 1)
                            ->where('status', 1)
                            ->whereDate('end_date', '=', $yesterday->format('Y-m-d'))
                            ->orWhere('end_date','<',$today->format('Y-m-d'))
                            ->update(['status' => 0]);

        }
    }

    


     
}
