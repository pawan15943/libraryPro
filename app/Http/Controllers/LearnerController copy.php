<?php

namespace App\Http\Controllers;
use App\Models\CustomerDetail;
use App\Models\Customers;
use App\Models\Plan;
use App\Models\PlanType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use App\Services\LearnerService;
use Exception;

class LearnerController extends Controller
{
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

        // if ($id) {
        //     $validator = Validator::make($request->all(), $rules);

        //     $rules['name'] = 'required';
        //     $rules['dob'] = 'required';
        //     $rules['mobile'] = 'required';
        //     $rules['email'] = 'required|email|' . Rule::unique('customers')->ignore($id);
        //     $rules['payment_mode'] = 'required';

        //     // Conditionally require 'user_id' if $id is null
        //     $validator->sometimes('user_id', 'required|integer', function ($input) use ($id) {
        //         return !$id;
        //     });

        //     return $validator;
        // }

        // For create, return the validator with basic rules
        $rules = array_merge($baseRules, $additionalRules);

        return Validator::make($request->all(), $rules);
    }
    public function seatCreate(){
        return view('learner.create');
    }
    public function seatStore(Request $request)
    {
        $totalSeats = $request->input('total_seats');
    
        if (!$totalSeats || $totalSeats <= 0) {
            return redirect()->back()->with('error', 'Invalid number of seats');
            die;
        }
    
        // Retrieve the last seat number from the seats table
        $lastSeatNo = DB::table('seats')->orderBy('seat_no', 'desc')->value('seat_no');
    
        // If there are no seats yet, start from 1; otherwise, start from the next number
        $startSeatNo = $lastSeatNo ? $lastSeatNo + 1 : 1;
    
        $seats = [];
    
        for ($i = 0; $i < $totalSeats; $i++) {
            $seats[] = [
                'seat_no' => $startSeatNo + $i,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    
        DB::table('seats')->insert($seats);
    
        return redirect()->route('seats')->with('success', 'Data created successfully.');
    }

    public function index(){
        $first_record = DB::table('hour')->first();
        $total_hour = $first_record ? $first_record->hour : null;

        $seats = DB::table('seats')->get();
        $this->dataUpdate();
        $users = Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->where('customers.status', 1);
      
        $plans=Plan::get();
        $plan_types=PlanType::get();
        $count_fullday=Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->leftJoin('plan_types', 'customer_detail.plan_type_id', '=', 'plan_types.id')->where('plan_types.day_type_id',1)->where('customers.status',1)->count();
        $count_firstH=Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->leftJoin('plan_types', 'customer_detail.plan_type_id', '=', 'plan_types.id')->where('plan_types.day_type_id',2)->where('customers.status',1)->count();
        $count_secondH=Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->leftJoin('plan_types', 'customer_detail.plan_type_id', '=', 'plan_types.id')->where('plan_types.day_type_id',3)->where('customers.status',1)->count();
        $count_hourly=Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->leftJoin('plan_types', 'customer_detail.plan_type_id', '=', 'plan_types.id')->whereIn('plan_types.day_type_id',[4,5,6,7])->where('customers.status',1)->count();
        $available=DB::table('seats')->where('total_hours',0)->count();
        $not_available=DB::table('seats')->where('is_available',0)->count();
        return view('learner.seat', compact('seats', 'users','plans','plan_types','count_fullday','count_firstH','count_secondH','available','not_available','total_hour','count_hourly'));
    }
    public function fetchCustomerData($customerId = null, $isRenew = false, $status = 1, $detailStatus = 1)
    {
        $query = Customers::leftJoin('customer_detail', 'customer_detail.customer_id', '=', 'customers.id')
            ->leftJoin('seats', 'customers.seat_no', '=', 'seats.id')
            ->leftJoin('plans', 'customer_detail.plan_id', '=', 'plans.id')
            ->leftJoin('plan_types', 'customer_detail.plan_type_id', '=', 'plan_types.id')
            ->where('customers.status', $status)
            ->where('customer_detail.status', $detailStatus)
            ->select(
                'plan_types.name as plan_type_name',
                'plans.name as plan_name',
                'seats.seat_no',
                'customers.*',
                'plan_types.start_time',
                'plan_types.end_time',
                'customer_detail.customer_id','customer_detail.plan_start_date','customer_detail.plan_end_date','customer_detail.plan_type_id','customer_detail.plan_id','customer_detail.plan_price_id','customer_detail.status',
                'plan_types.image'
            );
    
        if ($customerId) {
            // Fetch a single customer record
            $query->where('customers.id', $customerId);
            
            if ($isRenew) {
                $query->selectRaw('customer_detail.customer_id, customer_detail.plan_start_date, customer_detail.join_date, customer_detail.plan_end_date, customer_detail.plan_type_id, customer_detail.plan_id, customer_detail.plan_price_id, customer_detail.status, 1 as is_renew');
            } else {
                $query->selectRaw('customer_detail.customer_id, customer_detail.plan_start_date, customer_detail.join_date, customer_detail.plan_end_date, customer_detail.plan_type_id, customer_detail.plan_id, customer_detail.plan_price_id, customer_detail.status, 0 as is_renew');
            }
    
            $customer = $query->firstOrFail();
        
            // Format start_time and end_time to 12-hour with AM/PM
            if ($customer) {
                $customer->start_time = Carbon::parse($customer->start_time)->format('g:i A');
                $customer->end_time = Carbon::parse($customer->end_time)->format('g:i A');
            }
            
            return $customer;
        } else {
            // Fetch multiple customer records
            return $query->get();
        }
    }

    public function learnerList(){
       
        $customers =$this->fetchCustomerData(null, null, $status=1, $detailStatus=1);
        
      
        $learnerHistory = $this->fetchCustomerData(null, null, $status=0, $detailStatus=0);
        return view('learner.customer', compact('customers','learnerHistory'));
        
    }
    public function getUser(Request $request, $id = null)
    {
       
        $customerId = $request->id ?? $id;
        $is_renew = $this->learnerService->getRenewalStatus($customerId);
        
        $plans = $this->learnerService->getPlans();
        $planTypes = $this->learnerService->getPlanTypes();
        $available_seat = $this->learnerService->getAvailableSeats();
       
        $customer = $this->fetchCustomerData($customerId, $is_renew, $status=1, $detailStatus=1);
        
        // Check the request type or the presence of a parameter to determine the response
        if ($request->expectsJson() || $request->has('id')) {
            return response()->json($customer);
        } else {
            return view('learner.learnerEdit',compact('customer', 'plans', 'planTypes','available_seat'));
           
        }
    }
    public function getSwapUser($id){
       
        $customerId=$id;
        $firstRecord = DB::table('hour')->first();
        $totalHour = $firstRecord ? $firstRecord->hour : null;
        
        $available_seat = DB::table('seats')
            ->where('total_hours', '!=', $totalHour)
            ->pluck('seat_no', 'id');
        
        $customer = $this->fetchCustomerData($customerId, false, $status=1, $detailStatus=1);
        return view('learner.swap', compact('customer','available_seat'));
    }
    public function getLearner(Request $request, $id = null){
        // Determine the customer ID to use
        $customerId = $request->id ?? $id;
        $is_renew = $this->learnerService->getRenewalStatus($customerId);
        
        $plans = $this->learnerService->getPlans();
        $planTypes = $this->learnerService->getPlanTypes();
        $available_seat = $this->learnerService->getAvailableSeats();
        // Query to get customer details
        $customer = $this->fetchCustomerData($customerId, $is_renew, $status=1, $detailStatus=1);
        
           
       return view('learner.customerShow', compact('customer', 'plans', 'planTypes','available_seat'));
    }
    public function seatHistory(){
        $seats = DB::table('seats')->get();
        $customers_seats=Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->get();
    
        return view('learner.seatHistory' ,compact('customers_seats','seats'));
    }
    public function history($id)
    {
        $customers = Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->leftJoin('plans', 'customer_detail.plan_id', '=', 'plans.id')
        ->leftJoin('plan_types', 'customer_detail.plan_type_id', '=', 'plan_types.id')
        ->where('customers.seat_no',$id)
        ->where('customers.status',0)
            ->select(
                'plan_types.name as plan_type_name',
                'plans.name as plan_name',
                'customers.*',
                'customer_detail.customer_id','customer_detail.plan_start_date','customer_detail.plan_end_date','customer_detail.plan_type_id','customer_detail.plan_id','customer_detail.plan_price_id','customer_detail.status',
                'plan_types.start_time',
                'plan_types.end_time',
            )
        ->get();
        $seat=DB::table('seats')->where('id',$id)->first('seat_no');
        return view('learner.seatHistoryView', compact('customers','seat'));
    }
    
    public function reactiveUser(Request $request, $id = null){
        // Determine the customer ID to use
        $customerId = $request->id ?? $id;
        $is_renew = $this->learnerService->getRenewalStatus($customerId);
        
        $plans = $this->learnerService->getPlans();
        $planTypes = $this->learnerService->getPlanTypes();
        $available_seat = $this->learnerService->getAvailableSeats();
        // Query to get customer details
        $customer = $this->fetchCustomerData($customerId, $is_renew, $status=0, $detailStatus=0);
       
        // Check the request type or the presence of a parameter to determine the response
        if ($request->expectsJson() || $request->has('id')) {
            return response()->json($customer);
        } else {
            return view('learner.learnerEdit', compact('customer', 'plans', 'planTypes','available_seat'));
        }
    }
    //learner Edit and Upgrade
    public function userUpdate(Request $request, $id = null)
    {
        $validator = $this->validateCustomer($request);

        // If validation fails, return error response
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
        

        $customer = Customers::findOrFail($user_id);
        
         // Fetch existing bookings for the same seat
        $existingBookings = Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->where('seat_no', $customer->seat_no)
            ->where('customers.id', '!=', $customer->id) // Exclude the current booking
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

                // Check if the new plan type's time slot overlaps with an existing booking
                if (
                    ($startTime < $bookingEndTime && $endTime > $bookingStartTime) ||
                    ($endTime > $bookingStartTime && $startTime < $bookingEndTime)
                ) {
                    return redirect()->back()->with('error', 'The selected plan type overlaps with an existing booking.');
                }
            }
        }

             // Total available hours check
        $first_record = DB::table('hour')->first();
        $total_hour = $first_record ? $first_record->hour : 0;

        if ($total_hour === 0) {
            return redirect()->back()->with('error', 'Total available hours not set.');
        }

        // Calculate total hours booked on this seat
        $total_cust_hour = Customers::where('seat_no', $customer->seat_no)->sum('hours');

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
       
             // some field in customer deatl table so Update the customer_detail table
        $customerDetail = CustomerDetail::where('customer_id', $customer->id)->first();
        if ($customerDetail) {
            if($request->input('plan_start_date')){
                $customerDetail->plan_start_date =$start_date;
            }
            $customerDetail->plan_id = $request->input('plan_id');
            $customerDetail->plan_type_id = $plan_type;
            $customerDetail->plan_price_id = $request->input('plan_price_id');
            $customerDetail->plan_end_date = $newEndDate->toDateString();
            $customerDetail->save();
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
    public function swapSeat(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {

               
                $customer = Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->where('customers.id',$request->customer_id)->select('customers.id as id','customers.*','customer_detail.plan_type_id')->first();
                $newSeatId = $request->seat_no;
              
                $first_record = DB::table('hour')->first();
                $total_hour = $first_record ? $first_record->hour : null;
               // plan type check
              
               $total_cust_hour=Customers::where('seat_no',$newSeatId)->sum('hours');
               $new_seat_remainig=$total_hour-$total_cust_hour;
                // Fetch the current seat's total hours
                $hourCheck = DB::table('seats')->where('seat_no', $newSeatId)->select('total_hours')->first();

              
                if (($hourCheck->total_hours > 0) && ($customer->hours > $new_seat_remainig)) {
                    throw new Exception('Not available according to your hours.');
                } elseif (Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->where('seat_no', $newSeatId)
                    ->where('plan_type_id', $customer->plan_type_id)
                    ->where('customers.status', 1)
                    ->where('customer_detail.status', 1)
                    ->count() > 0) {
                    throw new Exception('The new seat is not available for your plan type.');
                } else {
                 
                    // Update seat availability for the old seat
                    $this->seat_availablity_update($customer->seat_no, $customer->plan_type_id);
                    $old_total_hour = DB::table('seats')->where('seat_no', $customer->seat_no)->value('total_hours');
    
                    // Adjust old seat's total hours
                    $remaining = $old_total_hour - $customer->hours;
                    DB::table('seats')->where('seat_no', $customer->seat_no)->update(['total_hours' => $remaining]);
                   
                    // Update the customer's seat
                    $customer->seat_no = $newSeatId;
                    $customer->save();
    
                    // Update seat availability for the new seat
                    $new_total_hour = DB::table('seats')->where('seat_no', $newSeatId)->value('total_hours');
                    $this->seat_availablity_update($newSeatId, $customer->plan_type_id);
    
                    // Adjust new seat's total hours
                    $total_remain = $new_total_hour + $customer->hours;
                    DB::table('seats')->where('seat_no', $newSeatId)->update(['total_hours' => $total_remain]);
                }
            });
    
            // If everything works fine, return success message
            return redirect()->route('learners')->with('success', 'Seat swapped successfully!');

        } catch (Exception $e) {
            // If any error occurs, roll back the transaction and return an error message
            DB::rollBack();
    
            // Return with an error message containing the exception details
            return redirect()->back()->with('error', 'Seat swap failed: ' . $e->getMessage());
        }
    }
    public function reactiveLearner(Request $request, $id)
    {
       
        $rules = [
            'seat_no' => 'required|integer',
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
        DB::beginTransaction(); // Start transaction

        try {
            $customer = Customers::findOrFail($request->user_id);

            // Fetch existing bookings for the same seat
            $existingBookings = Customers::where('seat_no', '=', $request->seat_no)
                ->leftJoin('customer_detail', 'customer_detail.customer_id', '=', 'customers.id')
                ->where('customers.status', 1)
                ->where('customer_detail.status', 1)
                ->select('customer_detail.plan_type_id')
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

                    // Check if the new plan type's time slot overlaps with an existing booking
                    if (
                        ($startTime < $bookingEndTime && $endTime > $bookingStartTime) ||
                        ($endTime > $bookingStartTime && $startTime < $bookingEndTime)
                    ) {
                        return redirect()->back()->with('error', 'The selected plan type and seat overlaps with an existing booking.');
                    }
                }
            }

            // Total available hours check
            $first_record = DB::table('hour')->first();
            $total_hour = $first_record ? $first_record->hour : 0;

            if ($total_hour === 0) {
                return redirect()->back()->with('error', 'Total available hours not set.');
            }

            // Calculate total hours booked on this seat
            $total_cust_hour = Customers::where('seat_no', $request->seat_no)->sum('hours');

            // Check if the selected plan type exceeds available hours
            if ($hours > ($total_hour - $total_cust_hour)) {
                return redirect()->back()->with('error', 'You cannot select this plan type as it exceeds the available hours.');
            }

            // Calculate plan duration
            $months = Plan::where('id', $request->plan_id)->value('plan_id');
            $duration = $months ?? 0;
            $start_date = Carbon::parse($request->input('plan_start_date'));
            $endDate = $start_date->copy()->addMonths($duration);

            // Update customer information
            $customer->seat_no = $request->seat_no;
            $customer->hours = $hours;
            $customer->status = 1;
            if (!$customer->save()) {
                throw new \Exception('Failed to update customer');
            }

            // Create customer detail
            CustomerDetail::create([
                'customer_id' => $customer->id,
                'plan_id' => $request->plan_id,
                'plan_type_id' => $request->input('plan_type_id'),
                'plan_price_id' => $request->input('plan_price_id'),
                'plan_start_date' => $start_date->format('Y-m-d'),
                'plan_end_date' => $endDate->format('Y-m-d'),
                'join_date' => date('Y-m-d'),
            ]);
            $total_hourse=Customers::where('status', 1)->where('seat_no',$request->seat_no)->sum('hours');
           
            $updateseat=DB::table('seats')->where('seat_no', $request->seat_no)->update(['total_hours' => $total_hourse]);

            // Update seat availability and other data
            $this->seat_availablity($request);
        

            DB::commit(); // Commit transaction

            return redirect('learner/list')->with('success', 'User Update successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    //learner store
    public function learnerStore(Request $request){
        $additionalRules = [
            'payment_mode' => 'required',
            'plan_start_date' => 'required|date',
        ];
    
        $validator = $this->validateCustomer($request, $additionalRules);
        
        if(Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->where('seat_no',$request->seat_no)->where('plan_type_id',$request->plan_type_id)->where('customers.status',1)->count()>0){
           
            return response()->json([
                'error' => true,
                'message' => 'This Plan Type Seat already booked'
            ], 422);
            die;
        }
        // Check if the validation fails
        if ($validator->fails()) {
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
            die;
        }

        // Handle the file upload
    
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
        $first_record = DB::table('hour')->first();
        $total_hour = $first_record ? $first_record->hour : null;

        if(PlanType::where('id',$request->plan_type_id)->count()>0){
            
            $hours=PlanType::where('id',$request->plan_type_id)->value('slot_hours');
        }
        if((Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->where('seat_no',$request->seat_no)->where('customers.status',1)->sum('hours') + $hours)>$total_hour){
           
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
       
     // Create a new customer using mass assignment
        $customer = Customers::create([
            'seat_no' => $request->input('seat_no'),
            'name' => $request->input('name'),
            'mobile' => $request->input('mobile'),
            'email' => $request->input('email'),
            'dob' => $request->input('dob'),
            'id_proof_name' => $request->input('id_proof_name'),
            'id_proof_file' => $id_proof_file, // Store the file path
            'hours' => $hours,
            'payment_mode' => $request->input('payment_mode'),
        ]);

        // Create a new customer detail using mass assignment
        CustomerDetail::create([
            'customer_id' => $customer->id, // Set the customer_id to the newly created customer's ID
            'plan_id' => $plan_id,
            'plan_type_id' => $request->input('plan_type_id'),
            'plan_price_id' => $request->input('plan_price_id'),
            'plan_start_date' => $start_date->format('Y-m-d'),
            'plan_end_date' => $endDate->format('Y-m-d'),
            'join_date' => date('Y-m-d'),
        ]);

        // Update seat availability
      
        $this->seat_availablity($request);
        $this->dataUpdate();
        // Return a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Learner created successfully!',
        ], 201);
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
        $customer = Customers::findOrFail($request->user_id);
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.'
            ], 404);
        }
       
        $months=Plan::where('id',$request->plan_id)->value('plan_id');
        $duration = $months ?? 0;
        $customer_detail=CustomerDetail::where('customer_id',$customer->id)->where('status',1)->first();
    

        if (!$customer_detail) {
           return response()->json([
               'success' => false,
               'message' => 'Learner detail not found or inactive.'
           ], 404);
       }
   
       // Calculate the start date as the next day after the current plan's end date
       $start_date = Carbon::parse($customer_detail->plan_end_date)->addDay();
   
       // Calculate the new end date by adding the plan duration to the start date
       $endDate = $start_date->copy()->addMonths($duration);
       
       CustomerDetail::create([
           'customer_id' => $customer->id, // Set the customer_id to the newly created customer's ID
           'plan_id' => $request->input('plan_id'),
           'plan_type_id' => $request->input('plan_type_id'),
           'plan_price_id' => $request->input('plan_price_id'),
           'plan_start_date' => $start_date->format('Y-m-d'),
           'plan_end_date' => $endDate->format('Y-m-d'),
           'join_date' => date('Y-m-d'),
           'status'=>0
       ]);

       return response()->json([
           'success' => true,
           'message' => 'Learner updated successfully!',
       ], 200);
     
    }
    public function getSeatStatus(Request $request)
    {
        // Count the bookings for the new seat
        $count = Customers::leftJoin('customer_detail', 'customer_detail.customer_id', '=', 'customers.id')
            ->where('seat_no', $request->new_seat_id)
            ->where('customers.status', 1)
            ->where('customer_detail.status', 1)
            ->where('customer_detail.plan_type_id', $request->plan_type_id)
            ->count();

        // Fetch the customer details
        $customer = Customers::where('id', $request->user_id)
            ->where('status', 1)
            ->first();

        // Get the total available hours from the hour table
        $first_record = DB::table('hour')->first();
        $total_hour = $first_record ? $first_record->hour : null;

        // Calculate the total hours already booked for the new seat
        $total_cust_hour = Customers::where('seat_no', $request->new_seat_id)->sum('hours');
        $new_seat_remaining = $total_hour - $total_cust_hour;

        // Fetch the current seat's total hours
        $hourCheck = DB::table('seats')->where('seat_no', $request->new_seat_id)->select('total_hours')->first();

        // Retrieve all bookings for the current seat
        $bookings = Customers::leftJoin('customer_detail', 'customer_detail.customer_id', '=', 'customers.id')
            ->join('plan_types', 'customer_detail.plan_type_id', '=', 'plan_types.id')
            ->where('seat_no', $request->new_seat_id)
            ->where('customers.status', 1)
            ->where('customer_detail.status', 1)
            ->get(['customer_detail.plan_type_id', 'plan_types.start_time', 'plan_types.end_time', 'plan_types.slot_hours']);

        // Retrieve the plan type details for the customer
        $planType = PlanType::where('id', $request->plan_type_id)->first();

        $status_array = [];
    
        // Determine conflicts based on plan_type_id and hours
        foreach ($bookings as $booking) {

            if ($booking->start_time < $planType->end_time && $booking->end_time > $planType->start_time) {
                $status_array[] = 0;
            } else {
                $status_array[] = 1;
            }
        }

        // Determine the status
        if ($hourCheck->total_hours > 0 && $customer->hours > $new_seat_remaining) {
            $status = 0;
        } elseif ($count == 1) {
            $status = 0;
        } elseif (in_array(0, $status_array)) {
            $status = 0;
        } elseif ($count == 0) {
            $status = 1;
        } else {
            $status = 1; // Default status if no other conditions are met
        }

        return response()->json($status);
    }

    protected function dataUpdate(){
       
        // Fetch seat data and update custor and seat table
        $seats = DB::table('seats')->get();
 
        foreach($seats as $seat){
            $total_hourse=Customers::where('status', 1)->where('seat_no',$seat->id)->sum('hours');
           
            $updateseat=DB::table('seats')->where('id', $seat->id)->update(['total_hours' => $total_hourse]);
          
           
        }
    
       //Fetch user data and update seat table and customer table
       $userUpdates = Customers::where('status', 1)->get();
  
       foreach ($userUpdates as $userUpdate) {
           $today = date('Y-m-d'); // Use the correct date format for comparison
           $customerdatas=CustomerDetail::where('customer_id',$userUpdate->id)->where('status',1)->get();
           // Check if the user's plan has expired
           foreach($customerdatas as $customerdata){
                if ($customerdata->plan_end_date <= $today) {
                
                    // Update status in customers table
                    $userUpdate->update(['status' => 0]);

                    // Update status in customer_detail table for all related records
                    $customerdata->update(['status' => 0]);
                    
                    
                }else{
                
                    // Update status in customers table
                    $userUpdate->update(['status' => 1]);
                    // Update status in customer_detail table for all related records
                    DB::table('customer_detail')->where('customer_id', $userUpdate->customer_id)->where('status',0)->where('plan_start_date','<=',$today)->where('plan_end_date','>',$today)->update(['status' => 1]);
                }
           }
           
       }

       //seat table update
       $userS = Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->leftJoin('plan_types', 'customer_detail.plan_type_id', '=', 'plan_types.id')->where('customers.status', 0)->select('customers.*','plan_types.day_type_id')->get();
      
        foreach ($userS as $user) {
        
            // Update seats status
            $seatNo = $user->seat_no;
            $seat = DB::table('seats')->where('seat_no', $seatNo)->first();
            
            // update seat availability based on conditions
            $available = 1; // Default to available
            
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
            
            // Update seat availability
            DB::table('seats')->where('seat_no', $seatNo)->update(['is_available' => $available]);
        }


      // total_hours==0 and is_availability!=1 seat availability update
        foreach($seats as $seat){
            DB::table('seats')->where('id',$seat->id)->where('total_hours',0)->where('is_available','!=',1)->update(['is_available' => 1]);
   
        }
    }

    protected function seat_availablity(Request $request){

        $plan_type_id=$request->plan_type_id;
        $seat_no=$request->seat_no;
      
       $this->seat_availablity_update($seat_no,$plan_type_id);
        
    }
    protected function seat_availablity_update($seat_no,$plan_type_id){
        $seat = DB::table('seats')->where('seat_no',$seat_no)->first();
        //available(not booked)=1,not available=0, firstHBook= 2 secondHbook=3 hourly=4 , fullbooked=5
                    //plan type 1=fullday, 2=firstH, 3=secondH,4=hourly	
                    // Determine new seat availability based on conditions

                      
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
        $update=DB::table('seats')->where('seat_no',$seat_no)->update(['is_available' => $available]);
        
    }
    public function destroy($id)
    {
        
        try {
            DB::transaction(function () use ($id) {
                // Find the customer by ID
                $customer = Customers::findOrFail($id);
                
                // Delete related records from the customer_detail table
                DB::table('customer_detail')->where('customer_id', $customer->id)->delete();
                
                // Delete the customer
                $customer->delete();
            });
    
            // Return a JSON response if the operation was successful
            return response()->json(['success' => 'Learner and related details deleted successfully.']);
        } catch (\Exception $e) {
            // If there's an error, roll back the transaction and return an error response
            return response()->json(['error' => 'An error occurred while deleting the customer: ' . $e->getMessage()], 500);
        }
    
        return response()->json(['success' => 'Learner deleted successfully.']);
    }
    public function userclose(Request $request){
        $customer = Customers::findOrFail($request->customer_id);
        $today = date('Y-m-d');
        DB::table('customer_detail')->where('customer_id', $customer->id)->where('status',1)->update(['plan_end_date' => $today,'status'=>0]);
        $customer->status=0;
        $customer->save();
        return response()->json(['message' => 'Learner closed successfully.']);
    }

   
}
