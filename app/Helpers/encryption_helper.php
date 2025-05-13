<?php

use App\Models\Branch;
use App\Models\Hour;
use App\Models\Learner;
use App\Models\LearnerDetail;
use App\Models\LearnerTransaction;
use Illuminate\Support\Facades\Log;
use App\Models\Library;
use App\Models\Subscription;
use App\Models\LibraryTransaction;
use App\Models\LibraryUser;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\PlanPrice;
use App\Models\Plan;
use App\Models\PlanType;
use Carbon\Carbon;

if (!function_exists('encryptData')) {
    function encryptData($data)
    {
        $key = "gingerth1nksaasT";
        $cipher = "AES-128-CBC";
        $iv_size = openssl_cipher_iv_length($cipher);
        $IV = substr(md5($key), 0, $iv_size);
        $encrypted = openssl_encrypt($data, $cipher, $key, 0, $IV);
        return str_replace(["+", "/"], [" ", "*"], $encrypted);
    }
}

if (!function_exists('decryptData')) {
    function decryptData($data)
    {
        $key = "gingerth1nksaasT";
        $cipher = "AES-128-CBC";
        $iv_size = openssl_cipher_iv_length($cipher);
        $IV = substr(md5($key), 0, $iv_size);
        $data = str_replace([" ", "*"], ["+", "/"], $data);
        $decrypted=openssl_decrypt($data, $cipher, $key, 0, $IV);
        \Log::info("Decryption Successful: " . $data . " → " . $decrypted);
        return $decrypted;
    }
}

if (!function_exists('getLibraryData')) {
    function getLibraryData()
    {
        $id = Session::get('selected_library_id');
        \Log::info("selected_library_id " . $id);
        if (!$id) {
            return null; // No library selected
        }

        $library = Library::find($id);
        if (!$library) {
            return null; // Invalid library ID
        }

        $plan = Subscription::where('id', $library->library_type)->with('permissions')->first();
        $library_transaction = LibraryTransaction::withoutGlobalScopes()->where('library_id', $library->id)
            ->where('is_paid', 1)
            ->orderBy('created_at', 'DESC')
            ->with('subscription')
            ->first();
        $library_all_transaction = LibraryTransaction::withoutGlobalScopes()->where('library_id', $library->id) ->with('subscription')->get();

        return (object) [
            'library' => $library,
            'plan' => $plan,
            'latest_transaction' => $library_transaction,
            'all_transactions' => $library_all_transaction,
        ];
    }
}

if(!function_exists('learnerTransaction') ){
    function learnerTransaction($id,$detail_id)
    {
       $transaction=LearnerTransaction::where('learner_id',$id)->where('learner_detail_id',$detail_id)->first();
       return  $transaction;
    }
}

if (!function_exists('getLibraryId')) {
    function getLibraryId()
    {
        $library_id = null;

        if (Auth::guard('library')->check()) {
            $library_id = Auth::guard('library')->user()->id;
        } elseif (Auth::guard('library_users')->check()) {
            $library_id = Auth::guard('library_users')->user()->library_id;
        }

        return $library_id;
    }
}

if (!function_exists('getCurrentBranch')){
    function getCurrentBranch(){
        $currentBranch=null;
        if (Auth::guard('library')->check()) {
           $data= Library::where('id',Auth::user()->id)->select('current_branch')->first();
            
        } elseif (Auth::guard('library_users')->check()) {
            $data= LibraryUser::where('id',Auth::user()->id)->select('current_branch')->first();
        }
        $currentBranch=$data->current_branch;
        return $currentBranch;
    }

}
if (!function_exists('getPlanPrice')) {
    function getPlanPrice($plan_id, $plan_type_id)
    {
        $libraryId = getLibraryId();
        $branchId = getCurrentBranch();

        $plan_price_all = PlanPrice::withoutGlobalScopes()
        ->leftJoin('plans', function ($join) {
            $join->on('plan_prices.plan_id', '=', 'plans.id')
                ->where('plans.library_id', getLibraryId());
        })
        ->where('plans.plan_id', 1)
        ->where('plans.type', 'MONTH')
        ->where('plan_prices.plan_type_id', $plan_type_id)
        ->where('plan_prices.library_id', getLibraryId())
        ->where('plan_prices.branch_id', getCurrentBranch())
        ->select('plan_prices.price')
        ->first();

    
        $plan=Plan::where('id',$plan_id)->first();
        if($plan->type=='MONTH'){
            $PlanpPrice= $plan_price_all->price * $plan->plan_id ;
        }elseif($plan->type=='YEAR'){
            $PlanpPrice= $plan_price_all->price * $plan->plan_id * 12 ;
        }elseif($plan->type=='WEEK'){
            $PlanpPrice= ($plan_price_all->price /4 ) * $plan->plan_id  ;
        }else{
            $PlanpPrice= ($plan_price_all->price /30 ) * $plan->plan_id  ;
        }

        return $PlanpPrice;
    }
}

if(!function_exists('getLockerPrice')){
    function getLockerPrice(?int $planId = null){
        $branchId = getCurrentBranch();
        if($branchId && $planId){
            $plan = Plan::find($planId);
           
            $branch=Branch::where('id',$branchId)->select('locker_amount')->first();
            if($plan->type=='YEAR'){
                $locker_amount=$branch->locker_amount * 12 * $plan->plan_id;
            }elseif($plan->type=='WEEK'){
                $locker_amount=($branch->locker_amount/30 * 7) * $plan->plan_id;
            }elseif($plan->type=='DAY'){
                $locker_amount=($branch->locker_amount/30) * $plan->plan_id;
            }elseif($plan->type=='MONTH'){
               
                $locker_amount=($branch->locker_amount) * $plan->plan_id;
                
            }else{
                $locker_amount=0;
            }
            
        }else if($branchId && !$planId){
            $branch=Branch::where('id',$branchId)->select('locker_amount')->first();
            $locker_amount=$branch->locker_amount;
        }else{
            $locker_amount=0;
        }
        
      
        return $locker_amount;
    }
}
if(!function_exists('getExtendDays')){
    function getExtendDays(){
        $branchId = getCurrentBranch();
        if($branchId){
            $branch=Branch::where('id',$branchId)->select('extend_days')->first();
            $extend_days=$branch->extend_days;
        }else{
            $extend_days=0;
        }
        
        return $extend_days ?? 0;
    }
}

if (!function_exists('getPlanStatusDetails')) {
        // $today = Carbon::today();
        // $endDate = Carbon::parse($user->plan_end_date);
        // $diffInDays = $today->diffInDays($endDate, false);
        // $inextendDate = $endDate->copy()->addDays($extendDay);
        // $diffExtendDay= $today->diffInDays($inextendDate, false);
        // $class='';
        // if($diffInDays < 0 && $diffExtendDay>0){
        //     $class='extedned';
        // }
        // if($diffInDays <=5 && $diffInDays>=0){
        //     $class='expired';
        // }
    function getPlanStatusDetails($plan_end_date) {
        $extendDay = getExtendDays(); // assume integer
        $today = Carbon::today();
        $endDate = Carbon::parse($plan_end_date);
        
        $diffInDays = $today->diffInDays($endDate, false);
        $inextendDate = $endDate->copy()->addDays($extendDay);
        $diffExtendDay = $today->diffInDays($inextendDate, false);

        // Default status & class
        $status = 'active';
        $class = '';

        if ($diffInDays < 0 && $diffExtendDay > 0) {
            $status = 'extended';
            $class = 'extended';
        } elseif ($diffInDays <= 5 && $diffInDays >= 0) {
            $status = 'expiring';
            $class = 'expired';
        } elseif ($diffExtendDay < 0) {
            $status = 'expired';
            $class = 'expired';
        }

        return [
            'status' => $status,
            'class' => $class,
            'diff_in_days' => $diffInDays,
            'diff_extend_day' => $diffExtendDay,
            'extend_days' => $extendDay
        ];
    }
}

if(!function_exists('getSeatType')){
    function getSeatType(){
        $branchId = getCurrentBranch();
        if($branchId){
            $branch=Branch::where('id',$branchId)->select('seat_type')->first();
            $seat_type=$branch->seat_type;
        }else{
            $seat_type=null;
        }
        
        return $seat_type ;
    }
}
if(!function_exists('countWithoutSeatNo')){
    function countWithoutSeatNo(){
        $branchId = getCurrentBranch();
        if($branchId){
            $count=Learner::where('branch_id',$branchId)->whereNull('seat_no')->count();
        }
        
        return $count ?? 0 ;
    }
}
if(!function_exists('getUserStatusDetails')){
    function getUserStatusDetails($plan_end_date) {
        $extendDay = getExtendDays(); // assume this returns an integer like 3 or 7
        $today = Carbon::today();
        $endDate = Carbon::parse($plan_end_date);

        $diffInDays = $today->diffInDays($endDate, false); // negative if expired
        $inextendDate = $endDate->copy()->addDays($extendDay);
        $diffExtendDay = $today->diffInDays($inextendDate, false); // negative if beyond extension

        if ($diffInDays > 0) {
            return '<small class="text-success">Plan Expires in ' . $diffInDays . ' days</small>';
        }  elseif ($diffInDays < 0 && $diffExtendDay > 0) {
            // <span class="text-danger fs-10 d-block">{{$learnerExtendText}} {{ abs($customer->diffExtendDay) }} days.</span>
            return '<small class="text-danger fs-10 d-block">Extension active! ' . abs($diffExtendDay) . ' days left.</small>';
        } elseif(($diffInDays < 0 && $diffExtendDay==0)){
            return ' <span class="text-warning fs-10 d-block">Plan Expires today</span>';
        }elseif ($diffInDays == 0) {
            return '<small class="text-warning fs-10 d-block">Plan Expires today</small>';
        } else {
            return '<small class="text-danger fs-10 d-block">Plan Expired ' . abs($diffInDays) . ' days ago</small>';
        }
    }

}

if(!function_exists('myLearner')){
    function myLearner($learner_id){
        $learner=Learner::where('id',$learner_id)->first();
        return $learner ? $learner : null ;
    }
}

if(!function_exists('myPlan')){
    function myPlan($plan_id){
        $plan=Plan::where('id', $plan_id)->first();
        return $plan ? $plan : null ;
    }
}

if(!function_exists('myPlanType')){
    function myPlanType($plan_id){
        $plan=PlanType::where('id', $plan_id)->first();
        return $plan ? $plan : null ;
    }
}

if(!function_exists('countBranch')){
    function countBranch(){
       $count=Branch::where('library_id',getLibraryId())->count();
       return $count ;
    }
}

if(!function_exists('getUserStatusWithSpan')){
    function getUserStatusWithSpan($plan_end_date) {
        $extendDay = getExtendDays(); 
        $today = Carbon::today();
        $endDate = Carbon::parse($plan_end_date);

        $diffInDays = $today->diffInDays($endDate, false); 
        $inextendDate = $endDate->copy()->addDays($extendDay);
        $diffExtendDay = $today->diffInDays($inextendDate, false); 

        if ($diffInDays > 0) {
            return '<span class="text-success">Plan Expires in ' . $diffInDays . ' days</span>';
        }  elseif ($diffInDays < 0 && $diffExtendDay > 0) {
            return '<span class="text-danger fs-10 d-block">Extension active! ' . abs($diffExtendDay) . ' days left.</span>';
        } elseif(($diffInDays < 0 && $diffExtendDay==0)){
            return ' <span class="text-warning fs-10 d-block">Plan Expires today</span>';
        }elseif ($diffInDays == 0) {
            return '<span class="text-warning fs-10 d-block">Plan Expires today</span>';
        } else {
            return '<span class="text-danger fs-10 d-block">Plan Expired ' . abs($diffInDays) . ' days ago</span>';
        }
    }

}
if (!function_exists('getUnavailableSeatCount')) {
    function getUnavailableSeatCount() {
        $totalHour = Hour::value('hour');

        return LearnerDetail::select('seat_no', DB::raw('SUM(hour) as used_hours'))
            ->whereNotNull('seat_no')
            ->groupBy('seat_no')
            ->having('used_hours', '>=', $totalHour)
            ->count();
    }
}

if (!function_exists('getAvailableSeatCount')) {
    function getAvailableSeatCount() {
        $totalHour = Hour::value('hour');
        $totalSeats = Hour::value('seats') ?? 0;

        $unavailable = getUnavailableSeatCount();

        return $totalSeats - $unavailable;
    }
}

if (!function_exists('seatRemainingHour')) {
    function seatRemainingHour($seat) {
        $totalHour = Hour::value('hour');

        // If total hour is not set, return 0
        if (!$totalHour) {
            return 0;
        }

        $usedHours = LearnerDetail::where('seat_no', $seat)
            ->sum('hour');

        return max(0, $totalHour - $usedHours); // Ensure no negative value
    }
}

if(!function_exists('currentTransaction')){
    function currentTransaction($learner_detail){
           $data= LearnerTransaction::where('learner_detail_id',$learner_detail)->first();
           return $data ?? null ;
    }
}











