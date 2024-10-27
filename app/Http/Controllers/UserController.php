<?php

namespace App\Http\Controllers;

use App\Models\CustomerDetail;

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


class UserController extends Controller
{   
    protected $learnerService;

    public function __construct(LearnerService $learnerService)
    {
        $this->learnerService = $learnerService;
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
    
            return $query->firstOrFail();
        } else {
            // Fetch multiple customer records
            return $query->get();
        }
    }
    
    // public function userUpdate(Request $request, $id = null)
    // {
        
    //     $validator = $this->validateCustomer($request, $id);
       
    //     if($request->input('user_id')){
    //         $user_id=$request->input('user_id');

    //             // Check if the validation fails
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'errors' => $validator->errors()
    //             ], 422);
    //         }
    //     }else{
    //         $user_id=$id;
    //         if ($validator->fails()) {
    //             return redirect()->back()->withErrors($validator)->withInput();
    //         }
    //     }
       
    //     // Find the customer by user_id
    //     $customer = Customers::findOrFail($user_id);
    //     if (!$customer) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Customer not found.'
    //         ], 404);
    //     }
      
    //         // Fetch existing bookings for the same seat
    //     $existingBookings = Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->where('seat_no', $customer->seat_no)
    //         ->where('customers.id', '!=', $customer->id) // Exclude the current booking
    //         ->get();
    //     // Determine hours based on plan_type_id
        
    //     $planType = PlanType::find($request->plan_type_id);
    //     $startTime = $planType->start_time;
    //     $endTime = $planType->end_time;
    //     $hours = $planType->slot_hours;

    //     // Check for overlaps with existing bookings
    //     foreach ($existingBookings as $booking) {
    //         $bookingPlanType = PlanType::find($booking->plan_type_id);
            
    //         if ($bookingPlanType) {
    //             $bookingStartTime = $bookingPlanType->start_time;
    //             $bookingEndTime = $bookingPlanType->end_time;

    //             // Check if the new plan type's time slot overlaps with an existing booking
    //             if (
    //                 ($startTime < $bookingEndTime && $endTime > $bookingStartTime) ||
    //                 ($endTime > $bookingStartTime && $startTime < $bookingEndTime)
    //             ) {
    //                 return redirect()->back()->with('error', 'The selected plan type overlaps with an existing booking.');
    //             }
    //         }
    //     }

    //     // Total available hours check
    //     $first_record = DB::table('hour')->first();
    //     $total_hour = $first_record ? $first_record->hour : 0;

    //     if ($total_hour === 0) {
    //         return redirect()->back()->with('error', 'Total available hours not set.');
    //     }

    //     // Calculate total hours booked on this seat
    //     $total_cust_hour = Customers::where('seat_no', $customer->seat_no)->sum('hours');

    //     // Check if the selected plan type exceeds available hours
    //     if ($hours > ($total_hour - ($total_cust_hour - $customer->hours))) {
    //         return redirect()->back()->with('error', 'You cannot select this plan type as it exceeds the available hours.');
    //     } else {
    //         $plan_type = $request->plan_type_id;
    //     }
       
    //     // Calculate new plan_end_date by adding duration to the current plan_end_date
    //     $months=Plan::where('id',$request->plan_id)->value('plan_id');
    //     $duration = $months ?? 0;
    //     $currentEndDate = Carbon::parse($customer->plan_end_date);
    //     $start_date = Carbon::parse($request->input('plan_start_date'));
    //     if($request->input('plan_end_date')){
    //         $newEndDate= Carbon::parse($request->input('plan_end_date'));
    //     }elseif($request->input('plan_start_date')){
    //         $start_date = Carbon::parse($request->input('plan_start_date'));
    //         $newEndDate = $start_date->copy()->addMonths($duration);
    //     }else{
           
    //         $newEndDate = $currentEndDate->addMonths($duration);
    //     }
       
    //     // Handle the file upload
    //     if($request->hasFile('id_proof_file'))
    //     {
    //         $this->validate($request,['id_proof_file' => 'mimes:webp,png,jpg,jpeg|max:200']);
    //         $id_proof_file = $request->id_proof_file;
    //         $id_proof_fileNewName = "id_proof_file".time().$id_proof_file->getClientOriginalName();
    //         $id_proof_file->move('public/uploade/',$id_proof_fileNewName);
    //         $id_proof_file = 'public/uploade/'.$id_proof_fileNewName;
    //     }else{
    //         $id_proof_file=null;
    //     }
    //     // Update customer details
    //     $customer->seat_no = $request->input('seat_no');
       
    //     if( $request->input('name')){
    //         $customer->name = $request->input('name');
    //     }
    //     if( $request->input('mobile')){
    //         $customer->mobile = $request->input('mobile');
    //     }
    //     if($request->input('email')){
    //         $customer->email = $request->input('email');
    //     }
    //     if($request->input('payment_mode')){
    //         $customer->payment_mode = $request->input('payment_mode');
    //     }
    //     if($request->input('id_proof_name')){
    //         $customer->id_proof_name = $request->input('id_proof_name');
    //     }
       
    //     $customer->hours = $hours;
    //     $customer->id_proof_file = $id_proof_file;
       
    //     $customer->save();
      
    //     // some field in customer deatl table so Update the customer_detail table
    //     $customerDetail = CustomerDetail::where('customer_id', $customer->id)->first();
    //     if ($customerDetail) {
    //         if($request->input('plan_start_date')){
    //             $customerDetail->plan_start_date =$start_date;
    //         }
    //         $customerDetail->plan_id = $request->input('plan_id');
    //         $customerDetail->plan_type_id = $plan_type;
    //         $customerDetail->plan_price_id = $request->input('plan_price_id');
    //         $customerDetail->plan_end_date = $newEndDate->toDateString();
    //         $customerDetail->save();
    //     }
    //     // Update seat availability
    //     $this->seat_availablity($request);

    //     $this->dataUpdate();
    //     if($request->input('user_id')){
    //         // Return a JSON response
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Learner updated successfully!',
    //         ], 200);
    //     }else{
    //         return redirect('learners/list')->with('success', 'User Update successfully.');
    //     }
       
    // }
    
    
    
   
   
    
   
    
    
   

    

    

   
    
   
    
   
   
    // public function customerUpdate(Request $request, $id = null){
       
       
    //     if($request->input('user_id')){
    //         $user_id=$request->input('user_id');

    //     }else{
    //         $user_id=$id;
    //     }
       
    //     // Find the customer by user_id
    //     $customer = Customers::findOrFail($user_id);
       
    //     if (!$customer) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Customer not found.'
    //         ], 404);
    //     }
    //      // Handle the file upload
    //      if($request->hasFile('id_proof_file'))
    //      {
    //          $this->validate($request,['id_proof_file' => 'mimes:webp,png,jpg,jpeg|max:200']);
    //          $id_proof_file = $request->id_proof_file;
    //          $id_proof_fileNewName = "id_proof_file".time().$id_proof_file->getClientOriginalName();
    //          $id_proof_file->move('public/uploade/',$id_proof_fileNewName);
    //          $id_proof_file = 'public/uploade/'.$id_proof_fileNewName;
    //      }else{
    //          $id_proof_file=null;
    //      }

       
    //     $customer->name = $request->input('name');
      
        
    //     $customer->mobile = $request->input('mobile');
      
        
    //     $customer->email = $request->input('email');
      
       
    //     $customer->payment_mode = $request->input('payment_mode');
       
    //     $customer->id_proof_name = $request->input('id_proof_name');
      
    //     $customer->id_proof_file = $id_proof_file;
       
    //     $customer->save();
    //     return redirect('learners/list')->with('success', 'User Update successfully.');
    // }
   
    // customer update and upgrade plan function
    
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
    


   
}
