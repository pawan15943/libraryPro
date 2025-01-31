<?php

namespace App\Http\Middleware;

use App\Models\Hour;
use App\Models\Learner;
use App\Models\LearnerDetail;
use App\Models\Library;
use App\Models\LibrarySetting;
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
use Illuminate\Support\Facades\Route;

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
            $menus = Menu::where('status', 1)->where(function ($query) {
                $query->where('guard', 'web')
                    ->orWhereNull('guard');
            })->with('children')->orderBy('order')->get();
        } elseif (Auth::guard('library')->check()) {


            $user = Auth::guard('library')->user();
            $menus = Menu::where('status', 1)->where(function ($query) {
                $query->where('guard', 'library')
                    ->orWhereNull('guard');
            })->with('children')->orderBy('order')->get();
        } elseif (Auth::guard('learner')->check()) {
            $user = Auth::guard('learner')->user();
            $menus = Menu::where('status', 1)->where(function ($query) {
                $query->where('guard', 'learner')
                    ->orWhereNull('guard');
            })->with('children')->orderBy('order')->get();
        }

        view()->share('menus', $menus);

        if (Auth::check()) {
            //learner remainig days count

            $leraner=LearnerDetail::withoutGlobalScopes()->where('learner_id',Auth::user()->id)->where('learner_detail.status',1)->leftJoin('plans','learner_detail.plan_id','=','plans.id')->leftJoin('plan_types','learner_detail.plan_type_id','=','plan_types.id')->select('learner_detail.*','plan_types.name as plan_type_name','plans.name as plan_name','plan_types.start_time','plan_types.end_time')->first();
            $learner_current_library_extend=Hour::withoutGlobalScopes()->where('library_id',Auth::user()->library_id)->first();
            if($leraner && $learner_current_library_extend){
                $today = Carbon::today();
                $endDate = Carbon::parse($leraner->plan_end_date);
                $diffInDays = $today->diffInDays($endDate, false);
                $inextendDate = $endDate->copy()->addDays($learner_current_library_extend->extend_days); // Preserving the original $endDate
                $diffExtendDay = $today->diffInDays($inextendDate, false);
            }else{
                $diffExtendDay=0;
            }
            $learner_is_renew=LearnerDetail::withoutGlobalScopes()->where('learner_id',Auth::user()->id) ->where('status', 0)
            ->where('plan_start_date', '>=', date('Y-m-d'))
            ->exists();
            //learner remainig days count
            $isEmailVeri = Library::where('id', Auth::user()->id)->whereNotNull('email_verified_at')->exists();
            $checkSub = LibraryTransaction::where('library_id', Auth::user()->id)->where('status', 1)->exists();
            $ispaid = Library::where('id', Auth::user()->id)->where('is_paid', 1)->exists();
            $iscomp = Library::where('id', Auth::user()->id)->where('status', 1)->exists();
            $isProfile = Library::where('id', Auth::user()->id)->where('is_profile', 1)->exists();

            $diffInDays = 0; // Initialize the variable to a default value
            $today = date('Y-m-d');

            $value = LibraryTransaction::where('library_id',  Auth::user()->id)->where('is_paid', 1)->orderBy('id', 'desc')->first();
            $is_renew_comp = LibraryTransaction::where('library_id', Auth::user()->id)
                ->where('is_paid', 1)
                ->where('status', 1)
                ->where('start_date', '>=', date('Y-m-d'))->exists();
            $is_renew = LibraryTransaction::where('library_id', Auth::user()->id)
                ->where('is_paid', 1)
                ->where('status', 0)
                ->where('start_date', '>=', date('Y-m-d'))
                ->exists();
            $is_expire = false;
            if ($value) {
                $today = Carbon::today();
                $endDate = Carbon::parse($value->end_date);
                $librarydiffInDays = $today->diffInDays($endDate, false);
                if ($librarydiffInDays <= 5) {
                    $is_expire = true;
                }
            } else {
                $librarydiffInDays = 0;
            }

            if ($is_renew) {
                $is_renew_val = LibraryTransaction::where('library_id', Auth::user()->id)
                    ->where('is_paid', 1)
                    ->where('status', 0)
                    ->where('start_date', '>', date('Y-m-d'))->first();
                $today = Carbon::today();
                if ($is_renew_val) {
                    $start_date = Carbon::parse($is_renew_val->start_date);
                    $upcomingdiffInDays = $today->diffInDays($start_date);
                } else {
                    $upcomingdiffInDays = null;
                }
            } else {

                $upcomingdiffInDays = null;
            }


            $this->statusInactive();
            $this->updateLibraryStatus();
            $this->dataUpdate();
            $today_renew = LibraryTransaction::where('library_id', Auth::user()->id)
                ->where('is_paid', 1)
                ->where('status', 0)
                ->where('start_date', '<=', $today)
                ->where('end_date', '>', date('Y-m-d'))
                ->exists();

            $learnerExtendText = 'Extend Days are Active Now & Remaining Days are';

            $total_seats = Seat::count();
            // $availble_seats=Seat::where('total_hours','!=',16)->count(); 

            $booked_seats = Seat::where('total_hours', '!=', 0)->count();
            $availble_seats = $total_seats - $booked_seats;
            $active_seat_count =  Learner::where('library_id', Auth::user()->id)->where('status', 1)
                ->distinct()
                ->count();
            $extend_days_data = Hour::where('library_id', Auth::user()->id)->first();
            $extend_day = $extend_days_data ? $extend_days_data->extend_days : 0;
            $extended_seats = LearnerDetail::where('learner_detail.is_paid', 1)
                ->where('learner_detail.status', 1)
                ->where('learner_detail.plan_end_date', '<', date('Y-m-d'))
                ->whereRaw("DATE_ADD(learner_detail.plan_end_date, INTERVAL ? DAY) >= CURDATE()", [$extend_day])
                ->count();
            $expired_seat = Learner::where('library_id', Auth::user()->id)->where('status', 0)->count();
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
            $extend_days = Hour::select('extend_days')->first();
            if ($extend_days) {
                $extendDay = $extend_days->extend_days;
            } else {
                $extendDay = 0;
            }

            $library_setting = LibrarySetting::where('library_id', Auth::user()->id)->first();

            if ($library_setting) {
                $primary_color = $library_setting->library_primary_color;
            } else {
                $primary_color = null;  
            }

            View::share('primary_color', $primary_color);
            View::share('checkSub', $checkSub);
            View::share('checkSub', $checkSub);
            View::share('ispaid', $ispaid);
            View::share('isProfile', $isProfile);
            View::share('isEmailVeri', $isEmailVeri);
            View::share('iscomp', $iscomp);
            View::share('librarydiffInDays', $librarydiffInDays);
            View::share('is_renew', $is_renew);
            View::share('is_renew_comp', $is_renew_comp);
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
            View::share('extended_seats', $extended_seats);
            View::share('extendDay', $extendDay);
            View::share('diffExtendDay', $diffExtendDay);
            View::share('learner_is_renew', $learner_is_renew);
        }
        if (auth()->check() && Auth::guard('library')->check()) {
            $user = Auth::user();
            $request->attributes->set('library_name', $user->library_name);
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
        $is_active = LibraryTransaction::where('library_id', Auth::user()->id)->where('is_paid', 1)->where('end_date', '>', $today->format('Y-m-d'))->exists();
        if ($hourexist > 0 && $extendexist > 0 && $seatExist > 0 && $plan > 0 && $plantype >= 3 && $planPrice >= 3 && $is_active) {
            $id = Auth::user()->id;
            $library = Library::findOrFail($id);

            if ($library->status != 1) {
                $library->status = 1;
                $library->save();
            }
        }
    }

    public function statusInactive()
    {
        $userId = Auth::user()->id;
        $today = Carbon::today();
        $yesterday = $today->subDay();
        $statuscheck = LibraryTransaction::where('library_id',  Auth::user()->id)->where('is_paid', 1)->where('end_date', '<=', $yesterday->format('Y-m-d'))->exists();
        $is_renew = LibraryTransaction::where('library_id', Auth::user()->id)->where('is_paid', 1)->where('end_date', '>', $today->format('Y-m-d'))->exists();
        if ($statuscheck && ($is_renew == false)) {
            Library::where('id', $userId)
                ->where('status', 1)
                ->update(['status' => 0, 'is_paid' => 0]);

            // Mark the expired transaction status as inactive
            LibraryTransaction::where('library_id', $userId)
                ->where('is_paid', 1)
                ->where('status', 1)
                ->whereDate('end_date', '=', $yesterday->format('Y-m-d'))
                ->orWhere('end_date', '<', $today->format('Y-m-d'))
                ->update(['status' => 0]);
        }
    }
    public function dataUpdate()
    {

        $seats = Seat::get();

        foreach ($seats as $seat) {
            $total_hourse = Learner::where('library_id', Auth::user()->id)->where('status', 1)->where('seat_no', $seat->seat_no)->sum('hours');

            $updateseat = Seat::where('library_id', Auth::user()->id)->where('id', $seat->id)->update(['total_hours' => $total_hourse]);
        }

        $userUpdates = Learner::where('library_id', Auth::user()->id)->where('status', 1)->get();

        foreach ($userUpdates as $userUpdate) {
            $today = date('Y-m-d');
            $customerdatas = LearnerDetail::where('learner_id', $userUpdate->id)->where('status', 1)->get();

            $extend_days_data = Hour::where('library_id', Auth::user()->id)->first();
            $extend_day = $extend_days_data ? $extend_days_data->extend_days : 0;
            foreach ($customerdatas as $customerdata) {
                $planEndDateWithExtension = Carbon::parse($customerdata->plan_end_date)->addDays($extend_day);

                $current_date = Carbon::today();
                $hasFuturePlan = LearnerDetail::where('learner_id', $userUpdate->id)
                    ->where('plan_end_date', '>', $current_date->copy()->addDays(5))->where('status', 0)
                    ->exists();
                $hasPastPlan = LearnerDetail::where('learner_id', $userUpdate->id)
                    ->where('plan_end_date', '<', $current_date->copy()->addDays(5))
                    ->exists();


                $isRenewed = $hasFuturePlan && $hasPastPlan;
                if ($planEndDateWithExtension->lte($today)) {
                    $userUpdate->update(['status' => 0]);
                    $customerdata->update(['status' => 0]);
                } elseif ($isRenewed) {
                    LearnerDetail::where('learner_id', $userUpdate->id)->where('plan_start_date', '<=', $today)->where('plan_end_date', '>', $current_date->copy()->addDays(5))->update(['status' => 1]);
                    LearnerDetail::where('learner_id', $userUpdate->id)->where('plan_end_date', '<', $today)->update(['status' => 0]);
                } else {
                    $userUpdate->update(['status' => 1]);
                    LearnerDetail::where('learner_id', $userUpdate->learner_id)->where('status', 0)->where('plan_start_date', '<=', $today)->where('plan_end_date', '>', $today)->update(['status' => 1]);
                }
            }
        }

        //seat table update
        $userS = Learner::leftJoin('learner_detail', 'learner_detail.learner_id', '=', 'learners.id')
            ->where('learners.library_id', auth()->user()->id)->where('learners.status', 0)->leftJoin('plan_types', 'learner_detail.plan_type_id', '=', 'plan_types.id')->select('learners.*', 'plan_types.day_type_id')->get();

        foreach ($userS as $user) {

            $seatNo = $user->seat_no;
            $seat = Seat::where('library_id', auth()->user()->id)->where('seat_no', $seatNo)->first();

            $available = 1;

            if ($seat->is_available == 5) {
                $available = 1;
            } elseif ($seat->is_available == 4 && ($user->day_type_id == 4 || $user->day_type_id == 5 || $user->day_type_id == 6 || $user->day_type_id == 7)) {
                $available = 1;
            } elseif ($seat->is_available == 3 && $user->day_type_id == 3) {
                $available = 1;
            } elseif ($seat->is_available == 2 && $user->day_type_id == 2) {
                $available = 1;
            } elseif ($seat->is_available == 2 && $user->day_type_id == 3) {
                $available = 2;
            } elseif ($seat->is_available == 3 && $user->day_type_id == 2) {
                $available = 3;
            } elseif ($seat->is_available == 4 && $user->day_type_id == 3) {
                $available = 4;
            } else {
                $available = 1;
            }

            Seat::where('library_id', auth()->user()->id)->where('seat_no', $seatNo)->update(['is_available' => $available]);
        }

        foreach ($seats as $seat) {
            Seat::where('library_id', auth()->user()->id)->where('id', $seat->id)->where('total_hours', 0)->where('is_available', '!=', 1)->update(['is_available' => 1]);
        }
    }
}
