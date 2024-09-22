<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\PlanType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
       
        $plan=Plan::all();
        return view('master.plan',compact('plan'));
    }
    public function craetePlanName()
    {
        $plans=Plan::withTrashed()->get();
      
        return view('master.plan',compact('plans'));
    }
    public function planNameedit(Plan $plan)
    {
        $plans=Plan::withTrashed()->get();
        return view('master.plan', compact('plan','plans'));
    }
    public function store(Request $request)
    {
       
        $request->validate([
            'plan_id' => 'required',
        ]);

        if(Plan::where('plan_id',$request->plan_id)->count()>0){
            return redirect()->back()->with('error', 'Plan Already Exists.');
            die();
        }

        $name = $request->plan_id . ' MONTHS';
        
       
        Plan::create([
            'plan_id' => $request->plan_id,
            'name' => $name,
        ]);
        return redirect()->back()->with('success', 'Plan created successfully.');
    }
    public function updateplanType(Request $request, Plan $plan)
    {
        $request->validate([
            'plan_id' => 'required|integer',
        ]);
        
        if(Plan::where('plan_id',$request->plan_id)->count()>0){
            return redirect()->back()->with('error', 'Plan Already Exists.');
            die();
        }
        // Determine the plan name based on the plan_id
        $name = $request->plan_id . ' MONTHS';

        // Update the plan with the new data
        $plan->update([
            'plan_id' => $request->plan_id,
            'name' => $name,
        ]);

        return redirect()->route('plan')->with('success', 'Plan updated successfully.');
    }

    public function planTypeList(){
        $plan=PlanType::all();
        return view('master.index',compact('plan'));
    }
    public function craeteplanType()
    {
        $plan_types=PlanType::withTrashed()->get();
        $plans=Plan::all();
        return view('master.planType', compact('plans','plan_types'));
    }
    public function planTypedit(PlanType $planType)
    {
        $plan_types=PlanType::withTrashed()->get();
        $plans=Plan::all();
        return view('master.planType', compact('planType','plans','plan_types'));
    }
    public function planTypestore(Request $request)
    {
       
        $request->validate([
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'slot_hours' => 'required|regex:/^\d+$/', // Custom validation rule to ensure integer value
        ]);
        $data=$request->all();
        if ($request->image_colour == 'orange') {
            $data['image'] = 'public/img/first-half.svg';
        } elseif ($request->image_colour == 'light_orange') {
            $data['image'] = 'public/img/second-half.svg';
        } else {
            $data['image'] = 'public/img/full-day.svg';
        }
        $first_record = DB::table('hour')->first();
        $total_hour = $first_record ? $first_record->hour : null;
       
        if($total_hour && $data['slot_hours']==$total_hour){
            $day_type=1;
        }elseif($total_hour && $data['slot_hours']==$total_hour/2){
            
            if($data['start_time']>'14:00'){
                $day_type=2;
            }else{
                $day_type=3;
            }
        }elseif($total_hour && $data['slot_hours']==$total_hour/4){
            if($data['timming']=='Morning1'){
                $day_type=4;
            }elseif($data['timming']=='Morning2'){
                $day_type=5;
            }elseif($data['timming']=='Evening1'){
                $day_type=6;
            }elseif($data['timming']=='Evening2'){
                $day_type=7;
            }else{
                $request->validate([
                    'timming' => 'required',
                ]);
            }
            if(PlanType::where('day_type_id',$day_type)->count()>0){
                return redirect()->route('planType')->with('error', 'Timining is already selected');
                die;
            }

            
        }else{
            return redirect()->route('planType')->with('error', 'Plan Type is full');
            die;
        }
        
        PlanType::create([
            'name' => $data['name'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'slot_hours' => $data['slot_hours'],
            'image' => $data['image'],
            'day_type_id'=>$day_type
        ]);
        return redirect()->route('planType')->with('success', 'Plan Type created successfully.');
    }
    public function planTypeupdate(Request $request, PlanType $planType)
    {
        $request->validate([
            'name' => 'required',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'slot_hours' => 'required|regex:/^\d+$/',
        ]);
        
        $data = $request->all();
        
        if ($request->image_colour == 'orange') {
            $data['image'] = 'public/img/first-half.svg';
        } elseif ($request->image_colour == 'light_orange') {
            $data['image'] = 'public/img/second-half.svg';
        }elseif ($request->image_colour == 'blue') {
            $data['image'] = 'public/img/hourly.svg';
        } else {
            $data['image'] = 'public/img/full-day.svg';
        }
        $first_record = DB::table('hour')->first();
        $total_hour = $first_record ? $first_record->hour : null;
       
        if($total_hour && $data['slot_hours']==$total_hour){
            $day_type=1;
        }elseif($total_hour && $data['slot_hours']==$total_hour/2){
            
            if($data['start_time']>'14:00'){
                $day_type=2;
            }else{
                $day_type=3;
            }
        }elseif($total_hour && $data['slot_hours']==$total_hour/4){
           
            if($data['timming']=='Morning1'){
                $day_type=4;
            }elseif($data['timming']=='Morning2'){
                $day_type=5;
            }elseif($data['timming']=='Evening1'){
                $day_type=6;
            }elseif($data['timming']=='Evening2'){
                $day_type=7;
            }else{
                $request->validate([
                    'timming' => 'required',
                ]);
            }
            if(PlanType::where('day_type_id',$day_type)->count()>0){
                return redirect()->route('planType')->with('error', 'Timining is already selected');
                die;
            }
            
        }else{
            return redirect()->route('planType')->with('error', 'Plan Type is full');
            die;
        }
        // Ensure $planType is defined and is the correct instance
        $planType->update([
            'name' => $data['name'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'slot_hours' => $data['slot_hours'],
            'image' => $data['image'],
            'day_type_id'=>$day_type
        ]);
        
        return redirect()->route('planType')->with('success', 'Plan Type updated successfully.');
    }
    // public function planPriceList(){
    //     $planPrice=PlanPrice::leftJoin('plan_types','plan_prices.plan_type_id','=','plan_types.id')->leftJoin('plans','plan_prices.plan_id','=','plans.id')->select('plans.name as plan_name','plan_types.name as plan_type','plan_prices.*')->get();
      
    //     return view('plan.index',compact('planPrice'));
    // }
    public function craeteplanPrice()
    {
        $plans=Plan::all();
        $plantypes=PlanType::all();
        $planPrice_list=PlanPrice::leftJoin('plan_types','plan_prices.plan_type_id','=','plan_types.id')->leftJoin('plans','plan_prices.plan_id','=','plans.id')->select('plans.name as plan_name','plan_types.name as plan_type','plan_prices.*')->withTrashed()->get();

        return view('master.planPrice', compact('plans','plantypes','planPrice_list'));
    }
    public function planPricedit(PlanPrice $planPrice)
    {
        $plans=Plan::all();
        $plantypes=PlanType::all();
        $planPrice_list=PlanPrice::leftJoin('plan_types','plan_prices.plan_type_id','=','plan_types.id')->leftJoin('plans','plan_prices.plan_id','=','plans.id')->select('plans.name as plan_name','plan_types.name as plan_type','plan_prices.*')->withTrashed()->get();

        return view('master.planPrice', compact('planPrice','plans','plantypes','planPrice_list'));
    }
    public function planPricestore(Request $request)
    {
        
        $request->validate([
            'plan_id' => 'required',
            'plan_type_id' => 'required',
            'price' => 'required',
        ]);
        if($request->plan_id){
            PlanPrice::create($request->all());
        }else{
            $plans=Plan::all();
            foreach($plans as $plan){
                $plan_id=$plan->id;
                $plan_type_id=$request->plan_type_id;
                $price=($plan->plan_id) * ($request->price);
                PlanPrice::create([
                    'plan_id'=>$plan_id,
                    'plan_type_id'=>$plan_type_id,
                    'price'=>$price,
                ]);
            }
        }
        
     
       
        return redirect()->route('planPrice')->with('success', 'Plan Price created successfully.');
    }
    public function planPriceupdate(Request $request, PlanPrice $planPrice)
    {
        $request->validate([
            'plan_id' => 'required',
            'plan_type_id' => 'required',
            'price' => 'required',
        ]);
        $planPrice->update($request->all());
        return redirect()->route('planPrice')->with('success', 'Plan Price updated successfully.');
    }

    public function getPlanType(Request $request){
        $seatId = $request->seat_no_id;
       
        $customer_plan=Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->where('seat_no', $seatId)->where('customers.id',$request->user_id)
        ->pluck('customer_detail.plan_type_id');
       
        // Step 1: Retrieve the plan_type_ids from Customers for the given seat
        $filteredPlanTypes=PlanType::where('id',$customer_plan)->pluck('name', 'id');

        $planTypesRemovals = Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->where('seat_no', $seatId)
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

        $selectedPlan=Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')->where('seat_no', $seatId)->where('customers.id',$request->user_id)
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
        $bookings = Customers::leftJoin('customer_detail','customer_detail.customer_id','=','customers.id')
            ->join('plan_types', 'customer_detail.plan_type_id', '=', 'plan_types.id')
            ->where('seat_no', $seatId)
            ->where('customers.status', 1)
            ->where('customer_detail.status', 1)
            ->get(['customer_detail.plan_type_id', 'plan_types.start_time', 'plan_types.end_time', 'plan_types.slot_hours']);
     
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
        $first_record = DB::table('hour')->first();
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
    
    public function hourCreate()
    {
        $hourse=DB::table('hour')->get();
        return view('master.hour',compact('hourse'));
    }
    
    public function hourUpdate(Request $request, $id = null)
    {
        // Validation
        $request->validate([
            'hourse' => 'required|integer',
        ]);
    
        if ($id) {
            // Find the hour record by ID using the query builder
            $hour = DB::table('hour')->where('id', $id)->first();
            if (!$hour) {
                return redirect()->back()->with('error', 'Hour record not found');
            }
    
            // Update the record
            DB::table('hour')->where('id', $id)->update([
                'hour' => $request->input('hourse'),
                
            ]);
    
            // Redirect with a success message
            return redirect()->route('hour')->with('success', 'Hour updated successfully');
        } else {
            // Insert a new record
            DB::table('hour')->insert([
                'hour' => $request->input('hourse'),
                
            ]);
    
            // Redirect with a success message
            return redirect()->route('hour')->with('success', 'Hour created successfully');
        }
    }
    
    public function hourEdit($id)
    {
        // Retrieve the specific hour record by ID
        $hourse=DB::table('hour')->get();
        $hour = DB::table('hour')->find($id);
    
        // Check if the hour record exists
        if (!$hour) {
            return redirect()->back()->with('error', 'Hour record not found');
        }
    
        // Pass the hour data to the view
        return view('master.hour', compact('hour','hourse'));
    }
    

}
