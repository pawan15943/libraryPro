<?php

namespace App\Http\Controllers;

use App\Models\Hour;
use App\Models\Learner;
use App\Models\LearnerDetail;
use App\Models\LearnerTransaction;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\PlanType;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use App\Services\LearnerService;
use Exception;
use App\Traits\LearnerQueryTrait;
use Illuminate\Support\Facades\Auth;
use Log;

class LearnerController extends Controller
{
    use LearnerQueryTrait;
    protected $learnerService;

    public function __construct(LearnerService $learnerService)
    {
        $this->learnerService = $learnerService;
    }
   
    protected function validateCustomer(Request $request, array $additionalRules = [])
    {
        $baseRules = [
            'seat_no' => 'required|integer',
            'email' => 'required|email',
            'name' => 'required',
            'id_proof_file' => 'nullable|file|mimes:jpg,png,jpeg,webp|max:200',
            'mobile' => 'required|digits:10',
            'dob' => 'required|date',
            'pin_code' => 'nullable|digits:6',
            'plan_id' => 'required',
            'plan_type_id' => 'required',
            'plan_price_id' => 'required',
            
        ];

        
        $rules = array_merge($baseRules, $additionalRules);

        return Validator::make($request->all(), $rules);
    }
    protected function dataUpdate(){
       
        $seats = Seat::get();
 
        foreach($seats as $seat){
            $total_hourse=Learner::where('library_id',Auth::user()->id)->where('status', 1)->where('seat_no',$seat->seat_no)->sum('hours');
           
            $updateseat=Seat::where('library_id',Auth::user()->id)->where('id', $seat->id)->update(['total_hours' => $total_hourse]);
        
        }
    
       $userUpdates = Learner::where('library_id',Auth::user()->id)->where('status', 1)->get();
  
       foreach ($userUpdates as $userUpdate) {
           $today = date('Y-m-d'); 
           $customerdatas=LearnerDetail::where('learner_id',$userUpdate->id)->where('status',1)->get();
           $extend_days_data = Hour::where('library_id', Auth::user()->id)->first();
           $extend_day = $extend_days_data ? $extend_days_data->extend_days : 0;
           foreach($customerdatas as $customerdata){
                $planEndDateWithExtension = Carbon::parse($customerdata->plan_end_date)->addDays($extend_day);
                if ($planEndDateWithExtension->lte($today)) {
                    $userUpdate->update(['status' => 0]);
                    $customerdata->update(['status' => 0]);
                }else{
                    $userUpdate->update(['status' => 1]);
                    LearnerDetail::where('learner_id', $userUpdate->learner_id)->where('status',0)->where('plan_start_date','<=',$today)->where('plan_end_date','>',$today)->update(['status' => 1]);
                }
           }
           
       }

       //seat table update
        $userS = $this->getLearnersByLibrary()->where('learners.status', 0)->leftJoin('plan_types', 'learner_detail.plan_type_id', '=', 'plan_types.id')->select('learners.*','plan_types.day_type_id')->get();
      
        foreach ($userS as $user) {
        
            $seatNo = $user->seat_no;
            $seat = Seat::where('library_id', auth()->user()->id)->where('seat_no', $seatNo)->first();
            
            $available = 1; 
            
            if ($seat->is_available == 5) {
                $available = 1;
            } elseif ($seat->is_available == 4 && ($user->day_type_id == 4 || $user->day_type_id==5 || $user->day_type_id==6 || $user->day_type_id==7)) {
                $available = 1;
            } elseif ($seat->is_available == 3 && $user->day_type_id == 3) {
                $available = 1;
            } elseif ($seat->is_available == 2 && $user->day_type_id == 2) {
                $available = 1;
            } elseif ($seat->is_available == 2 && $user->day_type_id == 3) {
                $available = 2;
            } elseif ($seat->is_available == 3 && $user->day_type_id == 2) {
                $available = 3;
            }elseif ($seat->is_available == 4 && $user->day_type_id == 3) {
                    $available = 4;
            } else {
                $available = 1;
            }
            
            Seat::where('library_id', auth()->user()->id)->where('seat_no', $seatNo)->update(['is_available' => $available]);
        }

        foreach($seats as $seat){
            Seat::where('library_id', auth()->user()->id)->where('id',$seat->id)->where('total_hours',0)->where('is_available','!=',1)->update(['is_available' => 1]);
   
        }
    }

    protected function seat_availablity(Request $request){
        
        $plan_type_id=$request->plan_type_id;
        $seat_id=$request->seat_id;
      
        if(!$seat_id){
            $learnerData=Learner::where('id',$request->user_id)->first();
            $library_id=$learnerData->library_id;
            $seat=Seat::where('library_id',$library_id)->where('seat_no',$request->seat_no)->first();
            $seat_id=$seat->id;
        }
        
     
       $this->seat_availablity_update($seat_id,$plan_type_id);
        
    }
    protected function seat_availablity_update($seat_id,$plan_type_id){
        $seat = Seat::where('id',$seat_id)->first();
                 
        $available=5;
        $day_type_id=PlanType::where('id',$plan_type_id)->select('day_type_id')->first();
        
        if( $seat->is_available == 1 && $day_type_id->day_type_id==1 ){
           
            $available = 5;
        }elseif($seat->is_available == 1 && $day_type_id->day_type_id==2 ){
           
            $available = 2;
        }elseif($seat->is_available == 1 && $day_type_id->day_type_id==3 ){
           
            $available = 3;
        }elseif($seat->is_available == 1 && ($day_type_id->day_type_id==4 || $day_type_id->day_type_id==5 ||$day_type_id->day_type_id==6 || $day_type_id->day_type_id==7) ){
           
            $available = 4;
           
        }elseif($seat->is_available == 2 && $day_type_id->day_type_id==3){
           
            $available = 5;
        }elseif($seat->is_available == 2 && ($day_type_id->day_type_id==6 || $day_type_id->day_type_id==7)){
           
            $available = 4;
        }elseif($seat->is_available == 3 && ($day_type_id->day_type_id==4 || $day_type_id->day_type_id==5)){
           
            $available = 4;
        }elseif($seat->is_available == 3 && $day_type_id->day_type_id==2){
           
            $available = 5;
        }elseif($seat->is_available == 4 && ($day_type_id->day_type_id==2|| $day_type_id->day_type_id==3||$day_type_id->day_type_id==4 || $day_type_id->day_type_id==5 || $day_type_id->day_type_id==6 || $day_type_id->day_type_id==5)){
            $available = 4;
            
        }
        
        // Update seat availability
        $update=Seat::where('id',$seat_id)->update(['is_available' => $available]);
        
    }
    
    
    public function index(){
        $first_record = Hour::first();
        $total_hour = $first_record ? $first_record->hour : null;

        $seats = Seat::get();
        $this->dataUpdate();
        $users = $this->getLearnersByLibrary()->where('learners.status', 1)->where('learner_detail.library_id',auth()->user()->id);
      
        $plans=Plan::get();
        $plan_types=PlanType::get();
        $count_fullday=$this->getLearnersByLibrary()->leftJoin('plan_types', 'learner_detail.plan_type_id', '=', 'plan_types.id')->where('learner_detail.library_id',auth()->user()->id)->where('plan_types.day_type_id',1)->where('learners.status',1)->count();
        $count_firstH=$this->getLearnersByLibrary()->leftJoin('plan_types', 'learner_detail.plan_type_id', '=', 'plan_types.id')->where('learner_detail.library_id',auth()->user()->id)->where('plan_types.day_type_id',2)->where('learners.status',1)->count();
        $count_secondH=$this->getLearnersByLibrary()->leftJoin('plan_types', 'learner_detail.plan_type_id', '=', 'plan_types.id')->where('learner_detail.library_id',auth()->user()->id)->where('plan_types.day_type_id',3)->where('learners.status',1)->count();
        $count_hourly=$this->getLearnersByLibrary()->leftJoin('plan_types', 'learner_detail.plan_type_id', '=', 'plan_types.id')->where('learner_detail.library_id',auth()->user()->id)->whereIn('plan_types.day_type_id',[4,5,6,7])->where('learners.status',1)->count();
        $available=Seat::where('total_hours',0)->count();
        $not_available=Seat::where('is_available',0)->count();
        return view('learner.seat', compact('seats', 'users','plans','plan_types','count_fullday','count_firstH','count_secondH','available','not_available','total_hour','count_hourly'));
    }
    //learner store
    public function learnerStore(Request $request){
        $additionalRules = [
            'payment_mode' => 'required',
            'plan_start_date' => 'required|date',
        ];
    
        $validator = $this->validateCustomer($request, $additionalRules);
       
        if($this->getLearnersByLibrary()->where('seat_no',$request->seat_no)->where('plan_type_id',$request->plan_type_id)->where('learners.status',1)->count()>0){
           
            return response()->json([
                'error' => true,
                'message' => 'This Plan Type Seat already booked'
            ], 422);
            die;
        }
      
        if ($validator->fails()) {
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
            die;
        }

    
        if($request->hasFile('id_proof_file'))
        {
            $this->validate($request,['id_proof_file' => 'mimes:webp,png,jpg,jpeg|max:200']);
            $id_proof_file = $request->id_proof_file;
            $id_proof_fileNewName = "id_proof_file".time().$id_proof_file->getClientOriginalName();
            $id_proof_file->move('public/uploade/',$id_proof_fileNewName);
            $id_proof_file = 'public/uploade/'.$id_proof_fileNewName;
        }else{
            $id_proof_file=null;
        }
        $first_record = Hour::first();
       
        $total_hour = $first_record ? $first_record->hour : null;

        if(PlanType::where('id',$request->plan_type_id)->count()>0){
            
            $hours=PlanType::where('id',$request->plan_type_id)->value('slot_hours');
        }
        if(($this->getLearnersByLibrary()->where('seat_no',$request->seat_no)->where('learners.status',1)->sum('hours') + $hours)>$total_hour){
           
            return response()->json([
                'error' => true,
                'message' => 'You can not select this plan type'
            ], 422);
            die;
        }
        
        $plan_id = $request->input('plan_id');
        $months=Plan::where('id',$plan_id)->value('plan_id');
        $duration = $months ?? 0;

        $start_date = Carbon::parse($request->input('plan_start_date'));
        $endDate = $start_date->copy()->addMonths($duration);
        if($request->payment_mode==1 || $request->payment_mode==2){
            $is_paid=1;
        }else{
            $is_paid=0; 
        }
        
     
        $customer = Learner::create([
            'seat_no' => $request->input('seat_no'),
            'name' => $request->input('name'),
            'mobile' => $request->input('mobile'),
            'email' => $request->input('email'),
            'dob' => $request->input('dob'),
            'id_proof_name' => $request->input('id_proof_name'),
            'id_proof_file' => $id_proof_file,
            'hours' => $hours,
            'payment_mode' => $request->input('payment_mode'),
            'library_id'=>Auth::user()->id,
            'password'=> bcrypt($request->mobile)
        ]);

        $learner_detail=LearnerDetail::create([
            'learner_id' => $customer->id, 
            'plan_id' => $plan_id,
            'plan_type_id' => $request->input('plan_type_id'),
            'plan_price_id' => $request->input('plan_price_id'),
            'plan_start_date' => $start_date->format('Y-m-d'),
            'plan_end_date' => $endDate->format('Y-m-d'),
            'join_date' => date('Y-m-d'),
            'hour' => $hours,
            'seat_id' => $request->seat_id,
            'library_id'=>Auth::user()->id,
            'is_paid' => $is_paid,
        ]);

        if($request->payment_mode==1 || $request->payment_mode==2){
            LearnerTransaction::create([
                'learner_id' =>$customer->id, 
                'library_id' => Auth::user()->id,
                'learner_deatail_id' => $learner_detail->id,
                'total_amount' => $request->input('plan_price_id'),
                'paid_amount' => $request->input('plan_price_id'),
                'pending_amount' => 0,
                'paid_date' => date('Y-m-d'),
                'is_paid' => 1
            ]);
        }

        $this->seat_availablity($request);
        $this->dataUpdate();
       
        return response()->json([
            'success' => true,
            'message' => 'Learner created successfully!',
        ], 201);
    }


    public function getPlanType(Request $request){
        $seatId = $request->seat_no_id;
       
        $customer_plan=$this->getLearnersByLibrary()->where('seat_no', $seatId)->where('learners.id',$request->user_id)
        ->pluck('learner_detail.plan_type_id');
       
        // Step 1: Retrieve the plan_type_ids from learners for the given seat
        $filteredPlanTypes=PlanType::where('id',$customer_plan)->pluck('name', 'id');

        $planTypesRemovals = $this->getLearnersByLibrary()->where('seat_no', $seatId)
            ->pluck('plan_type_id')
            ->toArray();
    
        // Step 2: Retrieve all plan_types as an associative array
        $planTypes = PlanType::pluck('name', 'id');
      
        
      
        // Step 3: Filter out the plan_types that match the retrieved plan_type_ids
        if(!$planTypesRemovals){
                $filteredPlanTypes = $planTypes->reject(function ($name, $id) use ($planTypesRemovals) {
                    return in_array($id, $planTypesRemovals);
                });
            }

        $selectedPlan=$this->getLearnersByLibrary()->where('seat_no', $seatId)->where('learners.id',$request->user_id)
        ->pluck('plan_id');
        $selectedPlanName=Plan::where('id',$selectedPlan)->pluck('name','id');
        // Return the filtered plan types as JSON
        return response()->json([$filteredPlanTypes,$selectedPlanName]);
    }
    public function getPrice(Request $request){
        if($request->plan_type_id && $request->plan_id){
            $planId=$request->plan_type_id;
            $PlanpPrice=PlanPrice::where('plan_type_id',$planId)->where('plan_id',$request->plan_id)->pluck('price','id');
            
            return response()->json($PlanpPrice);
        }
    }
    public function getPricePlanwiseUpgrade(Request $request){
        if($request->update_plan_type_id && $request->update_plan_id){
           
            $planId=$request->update_plan_type_id;
            $PlanpPrice=PlanPrice::where('plan_type_id',$planId)->where('plan_id',$request->update_plan_id)->pluck('price','id');
           
            return response()->json($PlanpPrice);
        }
    }
    
    public function getPlanTypeSeatWise(Request $request){
        $seatId = $request->seatId;
    
        // Step 1: Retrieve all bookings for the given seat
        $bookings = $this->getLearnersByLibrary()
            ->join('plan_types', 'learner_detail.plan_type_id', '=', 'plan_types.id')
            ->where('learner_detail.seat_id', $seatId)
            ->where('learners.status', 1)
            ->where('learner_detail.status', 1)
            ->get(['learner_detail.plan_type_id', 'plan_types.start_time', 'plan_types.end_time', 'plan_types.slot_hours']);
     
        // Step 2: Retrieve all plan types
        $planTypes = PlanType::all();
    
        // Step 3: Initialize an array to store the plan_type_ids to be removed
        $planTypesRemovals = [];
    
        // Step 4: Calculate total booked hours for the seat
        $totalBookedHours = $bookings->sum('slot_hours');
    
        // Step 5: Determine conflicts based on plan_type_id and hours
        foreach ($bookings as $booking) {
            foreach ($planTypes as $planType) {
                if ($booking->start_time < $planType->end_time && $booking->end_time > $planType->start_time) {
                    $planTypesRemovals[] = $planType->id;
                }
            }
        }
    
        // Remove duplicate entries in planTypesRemovals
        $planTypesRemovals = array_unique($planTypesRemovals);
       
        // If total booked hours >= 16, all plan types should be removed
        $first_record = Hour::first();
        $total_hour = $first_record ? $first_record->hour : null;
       
        if ($totalBookedHours >= $total_hour) {
            $planTypesRemovals = $planTypes->pluck('id')->toArray();
        }
       
        // Step 6: Filter out the plan_types that match the retrieved plan_type_ids
        $filteredPlanTypes = $planTypes->filter(function ($planType) use ($planTypesRemovals) {
            return !in_array($planType->id, $planTypesRemovals);
        })->map(function ($planType) {
            return ['id' => $planType->id, 'name' => $planType->name];
        })->values(); // Ensure the keys are reset to a continuous numerical index

    
        // Return the filtered plan types as JSON
        return response()->json($filteredPlanTypes);
    }

    
    public function fetchCustomerData($customerId = null, $isRenew = false, $status = 1, $detailStatus = 1, $filters = [])
    {
       
      
        $query = Learner::leftJoin('learner_detail', 'learner_detail.learner_id', '=', 'learners.id')
            ->leftJoin('seats', 'learner_detail.seat_id', '=', 'seats.id')
            ->leftJoin('plans', 'learner_detail.plan_id', '=', 'plans.id')
            ->leftJoin('plan_types', 'learner_detail.plan_type_id', '=', 'plan_types.id')
            ->where('learners.library_id', Auth::user()->id)
            ->where('learner_detail.library_id', Auth::user()->id)
            ->select(
                'plan_types.name as plan_type_name',
                'plans.name as plan_name',
                'seats.seat_no',
                'learners.*',
                'plan_types.start_time',
                'plan_types.end_time',
                'learner_detail.plan_start_date',
                'learner_detail.plan_end_date',
                'learner_detail.plan_type_id',
                'learner_detail.plan_id',
                'learner_detail.plan_price_id',
                'learner_detail.status as learner_detail_status',
                'plan_types.image',
                'learner_detail.is_paid'
            );
    
        // Apply dynamic filters if provided
        if (!empty($filters)) {
            // Filter by Plan ID
            if (!empty($filters['plan_id'])) {
                $query->where('learner_detail.plan_id', $filters['plan_id']);
            }
    
            // Filter by Payment Status
            if (!empty($filters['is_paid'])) {
                $query->where('learner_detail.is_paid', $filters['is_paid']);
            }
    
            // If a status filter is provided, apply it and skip the default status conditions
            if (!empty($filters['status'])) {
                if ($filters['status'] === 'active') {
                    // Only select active learners and details
                    $query->where('learners.status', 1)
                          ->where('learner_detail.status', 1);
                } elseif ($filters['status'] === 'expired') {
                    // Only select expired learners or details
                    $query->where(function ($q) {
                        $q->where('learner_detail.status', 0);
                    });
                }
            } else {
                // Apply default status conditions if no status filter is provided
                $query->where('learners.status', $status)
                      ->where('learner_detail.status', $detailStatus);
            }
    
            // Search by Name, Mobile, or Email
            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('learners.name', 'LIKE', "%{$search}%")
                      ->orWhere('learners.mobile', 'LIKE', "%{$search}%")
                      ->orWhere('learners.email', 'LIKE', "%{$search}%");
                });
            }
        } else {
            // Apply default status conditions if no filters are provided
            $query->where('learners.status', $status)
                  ->where('learner_detail.status', $detailStatus);
        }
    
        // If fetching a specific customer
        if ($customerId) {
     
          
            $query->where('learners.id', $customerId);
    
            // Handle renew cases
            if ($isRenew) {
                $query->selectRaw('learner_detail.learner_id, learner_detail.plan_start_date, learner_detail.join_date, learner_detail.plan_end_date, learner_detail.plan_type_id, learner_detail.plan_id, learner_detail.plan_price_id, learner_detail.status, 1 as is_renew');
            } else {
                $query->selectRaw('learner_detail.learner_id, learner_detail.plan_start_date, learner_detail.join_date, learner_detail.plan_end_date, learner_detail.plan_type_id, learner_detail.plan_id, learner_detail.plan_price_id, learner_detail.status, 0 as is_renew');
            }
    
            $customer = $query->firstOrFail();
    
            if ($customer) {
                // Format start and end time
                $customer->start_time = Carbon::parse($customer->start_time)->format('g:i A');
                $customer->end_time = Carbon::parse($customer->end_time)->format('g:i A');
            }
           
            return $customer;
        }
        
        
       
        return $query->paginate(perPage: 10);
    }
    

    public function learnerList(Request $request){
        $filters = [
            'plan_id' => $request->get('plan_id'),
            'is_paid' => $request->get('is_paid'),
            'status'  => $request->get('status'),
            'search'  => $request->get('search'),
        ];
    
        $learners = $this->fetchCustomerData(null, false, 1, 1, $filters);

       
        $plans = $this->learnerService->getPlans();
        return view('learner.learner', compact('learners','plans'));
        
    }
    public function learnerHistory(Request $request){
        $filters = [
            'plan_id' => $request->get('plan_id'),
            'is_paid' => $request->get('is_paid'),
            'status'  => $request->get('status'),
            'search'  => $request->get('search'),
        ];
    
       

        $learnerHistory = $this->fetchCustomerData(null, null, $status=0, $detailStatus=0,$filters);
        $plans = $this->learnerService->getPlans();
        return view('learner.learnerHistory', compact('learnerHistory','plans'));
        
    }
    //learner Edit and Upgrade
    public function userUpdate(Request $request, $id = null)
    {
        
        $validator = $this->validateCustomer($request);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        // Determine user_id based on $id or request input
        $user_id = $id ?: $request->input('user_id');
        

        $customer = Learner::findOrFail($user_id);
    
         // Fetch existing bookings for the same seat
        $existingBookings =$this->getLearnersByLibrary()->where('seat_no', $customer->seat_no)
            ->where('learners.id', '!=', $customer->id) // Exclude the current booking
            ->get();
        // Determine hours based on plan_type_id
        
        $planType = PlanType::find($request->plan_type_id);
        $startTime = $planType->start_time;
        $endTime = $planType->end_time;
        $hours = $planType->slot_hours;

        // Check for overlaps with existing bookings
        foreach ($existingBookings as $booking) {
            $bookingPlanType = PlanType::find($booking->plan_type_id);
            
            if ($bookingPlanType) {
                $bookingStartTime = $bookingPlanType->start_time;
                $bookingEndTime = $bookingPlanType->end_time;

                
                if (
                    ($startTime < $bookingEndTime && $endTime > $bookingStartTime) ||
                    ($endTime > $bookingStartTime && $startTime < $bookingEndTime)
                ) {
                    return redirect()->back()->with('error', 'The selected plan type overlaps with an existing booking.');
                }
            }
        }

            
        $first_record = Hour::first();
        $total_hour = $first_record ? $first_record->hour : 0;

        if ($total_hour === 0) {
            return redirect()->back()->with('error', 'Total available hours not set.');
        }

        // Calculate total hours booked on this seat
        $total_cust_hour = Learner::where('library_id',Auth::user()->id)->where('seat_no', $customer->seat_no)->sum('hours');

        // Check if the selected plan type exceeds available hours
        if ($hours > ($total_hour - ($total_cust_hour - $customer->hours))) {
            return redirect()->back()->with('error', 'You cannot select this plan type as it exceeds the available hours.');
        } else {
            $plan_type = $request->plan_type_id;
        }
       
        // Calculate new plan_end_date by adding duration to the current plan_end_date
        $months=Plan::where('id',$request->plan_id)->value('plan_id');
        $duration = $months ?? 0;
        $currentEndDate = Carbon::parse($customer->plan_end_date);
        $start_date = Carbon::parse($request->input('plan_start_date'));
        if($request->input('plan_end_date')){
            $newEndDate= Carbon::parse($request->input('plan_end_date'));
        }elseif($request->input('plan_start_date')){
            $start_date = Carbon::parse($request->input('plan_start_date'));
            $newEndDate = $start_date->copy()->addMonths($duration);
        }else{
           
            $newEndDate = $currentEndDate->addMonths($duration);
        }
        // Handle the file upload
        if ($request->hasFile('id_proof_file')) {
            $id_proof_file = $request->file('id_proof_file');
            $id_proof_fileNewName = "id_proof_file_" . time() . "_" . $id_proof_file->getClientOriginalName();
            
            // Store the file in the 'public/uploads' directory
            $id_proof_file->move(public_path('uploads'), $id_proof_fileNewName);
            $id_proof_filePath = 'uploads/' . $id_proof_fileNewName;

            // Set the path in the customer model
            $customer->id_proof_file = $id_proof_filePath;
        }

        // Update customer details only if the field is provided
        $customer->name = $request->input('name', $customer->name);
        $customer->mobile = $request->input('mobile', $customer->mobile);
        $customer->email = $request->input('email', $customer->email);
        $customer->dob = $request->input('dob', $customer->dob);
        $customer->payment_mode = $request->input('payment_mode', $customer->payment_mode);
        $customer->id_proof_name = $request->input('id_proof_name', $customer->id_proof_name);
        $customer->hours = $hours;
        // Save the customer details
        $customer->save();
       
             // some field in customer deatl table so Update the learner_detail table
        $LearnerDetail =LearnerDetail::where('learner_id', $customer->id)->first();
        if ($LearnerDetail) {
            if($request->input('plan_start_date')){
                $LearnerDetail->plan_start_date =$start_date;
            }
            $LearnerDetail->plan_id = $request->input('plan_id');
            $LearnerDetail->plan_type_id = $plan_type;
            $LearnerDetail->plan_price_id = $request->input('plan_price_id');
            $LearnerDetail->plan_end_date = $newEndDate->toDateString();
            $LearnerDetail->save();
        }
        // Update seat availability
        $this->seat_availablity($request);

        $this->dataUpdate();
        if($request->expectsJson()){
            // Return a JSON response
            return response()->json([
                'success' => true,
                'message' => 'Learner updated successfully!',
            ], 200);
        }else{
            return redirect()->route('learners')->with('success', 'Learner updated successfully.');
        }

       
       
    }
    public function getUser(Request $request, $id = null)
    {
       
        $customerId = $request->id ?? $id;
        $is_renew = $this->learnerService->getRenewalStatus($customerId);
        
        $plans = $this->learnerService->getPlans();
        $planTypes = $this->learnerService->getPlanTypes();
        $available_seat = $this->learnerService->getAvailableSeats();
       
        $customer = $this->fetchCustomerData($customerId, $is_renew, $status=1, $detailStatus=1);
        
        if ($request->expectsJson() || $request->has('id')) {
            return response()->json($customer);
        } else {
            return view('learner.learnerEdit',compact('customer', 'plans', 'planTypes','available_seat'));
           
        }
    }
    public function showLearner(Request $request, $id = null)
    {
       
        $customerId = $request->id ?? $id;
        $is_renew = $this->learnerService->getRenewalStatus($customerId);
        
        $plans = $this->learnerService->getPlans();
        $planTypes = $this->learnerService->getPlanTypes();
        $available_seat = $this->learnerService->getAvailableSeats();
       
        $customer = $this->fetchCustomerData($customerId, $is_renew, $status=1, $detailStatus=1);
       
        //renew History
        $renew_detail = LearnerDetail::where('learner_detail.learner_id', $customerId) 
        ->with(['plan', 'planType']) 
        ->get();

    

        //seat history
        $seat_history = $this->getAllLearnersByLibrary()
        ->where('learners.seat_no', $customer->seat_no)
        ->where('learners.id', '!=', $customerId) 
        ->with(['plan', 'planType'])
        ->get();

        $transaction = LearnerTransaction::where('learner_id', $customerId)
        ->orderBy('id', 'DESC') 
        ->first();
        $all_transactions=LearnerTransaction::where('learner_id',$customerId)->where('is_paid',1)->get();
        $extend_days=Hour::select('extend_days')->first();
        if($extend_days){
            $extendDay=$extend_days->extend_days;
        }else{
            $extendDay=0;
        }
        $today = Carbon::today();
        $endDate = Carbon::parse($customer->plan_end_date);
        $diffInDays = $today->diffInDays($endDate, false);
        $inextendDate = $endDate->copy()->addDays($extendDay); // Preserving the original $endDate
        $diffExtendDay= $today->diffInDays($inextendDate, false);
        $customer['diffExtendDay'] = $diffExtendDay;
        if ($request->expectsJson() || $request->has('id')) {
            return response()->json($customer);
        } else {
            return view('learner.learnerShow',compact('customer', 'plans', 'planTypes','available_seat','renew_detail','seat_history','transaction','all_transactions','extendDay'));
           
        }
    }
    //upgrade form view
    public function getLearner(Request $request, $id = null){
      
        $customerId = $request->id ?? $id;
        $is_renew = $this->learnerService->getRenewalStatus($customerId);
        
        $plans = $this->learnerService->getPlans();
        $planTypes = $this->learnerService->getPlanTypes();
        $available_seat = $this->learnerService->getAvailableSeats();
       
        $customer = $this->fetchCustomerData($customerId, $is_renew, $status=1, $detailStatus=1);
        
           
       return view('learner.learnerUpgrade', compact('customer', 'plans', 'planTypes','available_seat'));
    }
    public function getSwapUser($id){
       
        $customerId=$id;
        $firstRecord = Hour::first();
        $totalHour = $firstRecord ? $firstRecord->hour : null;
        
        $available_seat = Seat::where('total_hours', '!=', $totalHour)->pluck('seat_no', 'id');
        
        $customer = $this->fetchCustomerData($customerId, false, $status=1, $detailStatus=1);
        return view('learner.swap', compact('customer','available_seat'));
    }
   
    public function seatHistory(){
        $seats = Seat::get();
        $learners_seats= $this->getLearnersByLibrary()->get();
    
        return view('learner.seatHistory' ,compact('learners_seats','seats'));
    }
    public function history($id)
    {
        
        $learners =  $this->getAllLearnersByLibrary()
        ->where('learner_detail.seat_id',$id)
        ->where('learners.status',0)
            ->select(
                'plan_types.name as plan_type_name',
                'plans.name as plan_name',
                'learners.*',
                'learner_detail.learner_id','learner_detail.plan_start_date','learner_detail.plan_end_date','learner_detail.plan_type_id','learner_detail.plan_id','learner_detail.plan_price_id','learner_detail.status',
                'plan_types.start_time',
                'plan_types.end_time',
            )
        ->get();
       
        $seat=Seat::where('id',$id)->first('seat_no');
        return view('learner.seatHistoryView', compact('learners','seat'));
    }
    
    public function reactiveUser(Request $request, $id = null){
       
        $customerId = $request->id ?? $id;
        $is_renew = $this->learnerService->getRenewalStatus($customerId);
       
        $plans = $this->learnerService->getPlans();
        $planTypes = $this->learnerService->getPlanTypes();
        $available_seat = $this->learnerService->getAvailableSeats();
       
        $customer = $this->fetchCustomerData($customerId, false, $status=0, $detailStatus=0);
   
        if ($request->expectsJson() || $request->has('id')) {
           
            return response()->json($customer);
        } else {
           
            return view('learner.learnerEdit', compact('customer', 'plans', 'planTypes','available_seat'));
        }
    }
    
    public function swapSeat(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {

             
                $customer = $this->getLearnersByLibrary()->where('learners.id',$request->learner_id)->select('learners.id as id','learners.*','learner_detail.plan_type_id','learner_detail.seat_id')->first();
               
                $newSeatId = $request->seat_id;
          
                $first_record = Hour::first();
                $total_hour = $first_record ? $first_record->hour : null;
             
                $hourCheck = Seat::where('id', $newSeatId)->select('total_hours','seat_no')->first();
                $newSeatNo=$hourCheck->seat_no;
               
               $total_cust_hour=Learner::where('library_id',Auth::user()->id)->where('seat_no',$hourCheck->seat_no)->sum('hours');
               $new_seat_remainig=$total_hour-$total_cust_hour;
              
                if (($hourCheck->total_hours > 0) && ($customer->hours > $new_seat_remainig)) {
                    throw new Exception('Not available according to your hours.');
                } elseif ($this->getLearnersByLibrary()->where('learner_detail.seat_id', $newSeatId)
                    ->where('plan_type_id', $customer->plan_type_id)
                    ->where('learners.status', 1)
                    ->where('learner_detail.status', 1)
                    ->count() > 0) {
                    throw new Exception('The new seat is not available for your plan type.');
                } else {
                 
                    // Update seat availability for the old seat
                    $this->seat_availablity_update($customer->seat_id, $customer->plan_type_id);
                    $old_total_hour = Seat::where('id', $customer->seat_id)->value('total_hours');
    
                    // Adjust old seat's total hours
                    $remaining = $old_total_hour - $customer->hours;
                    Seat::where('id', $customer->seat_id)->update(['total_hours' => $remaining]);
                   
                    // Update the learner's seat_id and seat_no
                    $data=Learner::findOrFail($request->learner_id);
                    $data->seat_no = $newSeatNo;
                    $data->save();
                    $learner_detail=LearnerDetail::where('learner_id',$request->learner_id)->update([
                        'seat_id'=>$newSeatId,
                    ]);
    
                    // Update seat availability for the new seat
                    $new_total_hour = Seat::where('id', $newSeatId)->value('total_hours');
                    $this->seat_availablity_update($newSeatId, $customer->plan_type_id);
    
                    // Adjust new seat's total hours
                    $total_remain = $new_total_hour + $customer->hours;
                    Seat::where('id', $newSeatId)->update(['total_hours' => $total_remain]);
                }
            });
    
          
            return redirect()->route('learners')->with('success', 'Seat swapped successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Seat swap failed: ' . $e->getMessage());
        }
    }
    public function reactiveLearner(Request $request, $id)
    {
       
        $rules = [
            'seat_id' => 'required|integer',
            'plan_id' => 'required',
            'plan_type_id' => 'required',
            'plan_price_id' => 'required',
            'plan_start_date' => 'required',
            'payment_mode' => 'required',
            
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction(); 

        try {
            $customer = Learner::findOrFail($request->user_id);
            $seat_no=Seat::where('id',$request->seat_id)->value('seat_no');
            $existingBookings = $this->getLearnersByLibrary()
                ->where('seat_no', '=', $seat_no)
                ->where('learners.status', 1)
                ->where('learner_detail.status', 1)
                ->select('learner_detail.plan_type_id')
                ->get();

            $planType = PlanType::find($request->plan_type_id);
            $startTime = $planType->start_time;
            $endTime = $planType->end_time;
            $hours = $planType->slot_hours;

            foreach ($existingBookings as $booking) {
                $bookingPlanType = PlanType::find($booking->plan_type_id);

                if ($bookingPlanType) {
                    $bookingStartTime = $bookingPlanType->start_time;
                    $bookingEndTime = $bookingPlanType->end_time;

                    if (
                        ($startTime < $bookingEndTime && $endTime > $bookingStartTime) ||
                        ($endTime > $bookingStartTime && $startTime < $bookingEndTime)
                    ) {
                        return redirect()->back()->with('error', 'The selected plan type and seat overlaps with an existing booking.');
                    }
                }
            }

            $first_record = Hour::first();
            $total_hour = $first_record ? $first_record->hour : 0;

            if ($total_hour === 0) {
                return redirect()->back()->with('error', 'Total available hours not set.');
            }

            $total_cust_hour = Learner::where('library_id',Auth::user()->id)->where('seat_no', $seat_no)->where('status',1)->sum('hours');

            if ($hours > ($total_hour - $total_cust_hour)) {
                return redirect()->back()->with('error', 'You cannot select this plan type as it exceeds the available hours.');
            }

            $months = Plan::where('id', $request->plan_id)->value('plan_id');
            $duration = $months ?? 0;
            $start_date = Carbon::parse($request->input('plan_start_date'));
            $endDate = $start_date->copy()->addMonths($duration);

            $customer->seat_no = $seat_no;
            $customer->hours = $hours;
            $customer->status = 1;
            if (!$customer->save()) {
                throw new \Exception('Failed to update customer');
            }

            LearnerDetail::create([
                'library_id'=>$customer->library_id,
               'learner_id' => $customer->id, 
               'plan_id' => $request->input('plan_id'),
               'plan_type_id' => $request->input('plan_type_id'),
               'plan_price_id' => $request->input('plan_price_id'),
               'plan_start_date' => $start_date->format('Y-m-d'),
               'plan_end_date' => $endDate->format('Y-m-d'),
               'join_date' => date('Y-m-d'),
               'hour' =>$hours,
               'seat_id' =>$request->seat_id,
              
           ]);
            $total_hourse=Learner::where('library_id',Auth::user()->id)->where('status', 1)->where('seat_no',$seat_no)->sum('hours');
           
            $updateseat=Seat::where('seat_no', $seat_no)->update(['total_hours' => $total_hourse]);

            $this->seat_availablity($request);
            DB::commit();

            return redirect()->route('learnerHistory')->with('success', 'Learner updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
   
    public function learnerRenew(Request $request){
        $rules = [
            'seat_no' => 'required|integer',
            'plan_id' => 'required',
            'plan_type_id' => 'required',
            'plan_price_id' => 'required',
            'user_id' => 'required',
         
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // Find the customer by user_id
        $customer = Learner::findOrFail($request->user_id);
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Learner not found.'
            ], 404);
        }
       
        $months=Plan::where('id',$request->plan_id)->value('plan_id');
        $duration = $months ?? 0;
        $learner_detail=LearnerDetail::where('learner_id',$customer->id)->where('status',1)->first();
    

        if (!$learner_detail) {
           return response()->json([
               'success' => false,
               'message' => 'Learner detail not found or inactive.'
           ], 404);
       }
       $start_date = Carbon::parse($learner_detail->plan_end_date)->addDay();
   
       $endDate = $start_date->copy()->addMonths($duration);
       
      LearnerDetail::create([
            'library_id'=>$customer->library_id,
           'learner_id' => $customer->id, 
           'plan_id' => $request->input('plan_id'),
           'plan_type_id' => $request->input('plan_type_id'),
           'plan_price_id' => $request->input('plan_price_id'),
           'plan_start_date' => $start_date->format('Y-m-d'),
           'plan_end_date' => $endDate->format('Y-m-d'),
           'join_date' => date('Y-m-d'),
           'hour' =>$learner_detail->hour,
           'seat_id' =>$learner_detail->seat_id,
           'status'=>0
       ]);
      

       return response()->json([
           'success' => true,
           'message' => 'Learner updated successfully!',
       ], 200);
     
    }
    public function getSeatStatus(Request $request)
    {
        
       $seat= Seat::where('id', $request->new_seat_id)->first();
        $count = $this->getLearnersByLibrary()
            ->where('seat_no', $seat->seat_no)
            ->where('learners.status', 1)
            ->where('learner_detail.status', 1)
            ->where('learner_detail.plan_type_id', $request->plan_type_id)
            ->count();
            
        $customer = Learner::where('id', $request->user_id)
            ->where('status', 1)
            ->first();

        $first_record = Hour::first();
        $total_hour = $first_record ? $first_record->hour : null;
        
        $total_cust_hour = Learner::where('library_id',Auth::user()->id)->where('seat_no', $seat->seat_no)->sum('hours');
        $new_seat_remaining = $total_hour - $total_cust_hour;
      
        $hourCheck = Seat::where('id', $request->new_seat_id)->select('total_hours')->first();
       
        $bookings = $this->getLearnersByLibrary()
            ->join('plan_types', 'learner_detail.plan_type_id', '=', 'plan_types.id')
            ->where('seat_no', $seat->seat_no)
            ->where('learners.status', 1)
            ->where('learner_detail.status', 1)
            ->get(['learner_detail.plan_type_id', 'plan_types.start_time', 'plan_types.end_time', 'plan_types.slot_hours']);

        $planType = PlanType::where('id', $request->plan_type_id)->first();

        $status_array = [];
    
        foreach ($bookings as $booking) {

            if ($booking->start_time < $planType->end_time && $booking->end_time > $planType->start_time) {
                $status_array[] = 0;
            } else {
                $status_array[] = 1;
            }
        }

        if ($hourCheck->total_hours > 0 && $customer->hours > $new_seat_remaining) {
            $status = 0;
        } elseif ($count == 1) {
            $status = 0;
        } elseif (in_array(0, $status_array)) {
            $status = 0;
        } elseif ($count == 0) {
            $status = 1;
        } else {
            $status = 1; 
        }

        return response()->json($status);
    }

    
    public function destroy($id)
    {
        
        try {
            DB::transaction(function () use ($id) {
                
                $customer = Learner::findOrFail($id);
                
               
                LearnerDetail::where('learner_id', $customer->id)->delete();
              
                $customer->delete();
            });
    
           
            return response()->json(['success' => 'Learner and related details deleted successfully.']);
        } catch (\Exception $e) {
           
            return response()->json(['error' => 'An error occurred while deleting the customer: ' . $e->getMessage()], 500);
        }
    
        return response()->json(['success' => 'Learner deleted successfully.']);
    }
    public function userclose(Request $request){
        $customer = Learner::findOrFail($request->learner_id);
       
        $today = date('Y-m-d');
        LearnerDetail::where('learner_id', $customer->id)->where('status',1)->update(['plan_end_date' => $today,'status'=>0]);
        $customer->status=0;
        $customer->save();
        return response()->json(['message' => 'Learner closed successfully.']);
    }
    
    public function makePayment(Request $request){
        $customerId = $request->id;
        $customer = $this->fetchCustomerData($customerId, $isRenew = false, $status=1, $detailStatus=1);
        
        return view('learner.payment',compact('customer'));
    }

    public function paymentStore(Request $request)
    {
        $this->validate($request, [
            'learner_id' => 'required|exists:learners,id',
            'paid_amount' => 'required|numeric',
            'transaction_image' => 'nullable|mimes:webp,png,jpg,jpeg|max:200',
        ]);
        $data=$request->all();
        $total_amount = 0;
        $pending_amount = 0;
    
        $learnerDetail = LearnerDetail::where('learner_id', $request->learner_id)
            ->where('plan_price_id', $request->paid_amount)
            ->where('is_paid', 0)
            ->first();
    
        if ($learnerDetail) {
            $total_amount = $learnerDetail->plan_price_id;
            $pending_amount = $total_amount - $request->paid_amount;
        }
    
        if ($request->hasFile('transaction_image')) {
            $transaction_image = $request->file('transaction_image');
            $transaction_imageNewName = 'transaction_image_' . time() . '_' . $transaction_image->getClientOriginalName();
            $transaction_image->move(public_path('uploads'), $transaction_imageNewName);
            $data['transaction_image'] = 'uploads/' . $transaction_imageNewName;
        } else {
            $data['transaction_image'] = null;
        }
    
        $data['total_amount'] = $total_amount;
        $data['pending_amount'] = $pending_amount;
        $data['learner_deatail_id'] = $learnerDetail->id;
      
        try {
            $learner_transaction=LearnerTransaction::create($data);
            if($learner_transaction){
                LearnerDetail::where('learner_id',$request->learner_id)->where('plan_price_id',$request->paid_amount)->update([
                    'is_paid'=>1,
                ]);
            }

            return redirect()->route('learners')->with('success', 'Payment successfully recorded.');
        } catch (\Exception $e) {
            \Log::error('Payment Error: ' . $e->getMessage());
            return redirect()->route('learners')->withErrors(['error' => 'An error occurred while processing the payment.']);
        }
    }

    public function learnerExpire(Request $request, $id = null)
    {
       
        $customerId = $request->id ?? $id;
        $is_renew = $this->learnerService->getRenewalStatus($customerId);
        
        $plans = $this->learnerService->getPlans();
        $planTypes = $this->learnerService->getPlanTypes();
        $available_seat = $this->learnerService->getAvailableSeats();
       
        $customer = $this->fetchCustomerData($customerId, $is_renew, $status=1, $detailStatus=1);
        
        return view('learner.expire',compact('customer', 'plans', 'planTypes','available_seat'));
    }
    public function editLearnerExpire(Request $request){
        $user_id = $request->input('user_id');
        
        $customer = Learner::findOrFail($user_id);
        $start_date = Carbon::parse($request->input('plan_start_date'));
        if($request->input('plan_end_date')){
            $newEndDate= Carbon::parse($request->input('plan_end_date'));
        }

        $LearnerDetail =LearnerDetail::where('learner_id', $customer->id)->first();
        if($request->input('plan_start_date')){
            $LearnerDetail->plan_start_date =$start_date->toDateString();
        }
        
        $LearnerDetail->plan_end_date = $newEndDate->toDateString();
        $LearnerDetail->save();
        $LearnerDetail =LearnerDetail::where('learner_id', $customer->id)->first();
        $LearnerDetail->save();
        $this->seat_availablity($request);

        $this->dataUpdate();
        return redirect()->route('learners')->with('success', 'Learner updated successfully.');
    }
    
}
