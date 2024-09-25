<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Hour;
use App\Models\Library;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\PlanType;
use App\Models\Seat;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Exception;
use DB;
use Yajra\DataTables\Facades\DataTables;
use Auth;

class MasterController extends Controller
{
    public function stateWiseCity(Request $request)
    {

        if ($request->state_id) {
            $stateId = $request->state_id;
            $city = City::where('state_id', $stateId)->pluck('city_name', 'id');

            return response()->json($city);
        }
    }
    public function index()
    {
        $subscriptions = Subscription::all();
        $permissions = Permission::all();
        $users = User::all();

        return view('master.subscriptionPermission', compact('subscriptions', 'permissions', 'users'));
    }
    
    public function getPermissions($id)
    {
        $subscription = Subscription::with('permissions')->find($id);

        if (!$subscription) {
            return response()->json(['error' => 'Subscription not found'], 404);
        }

        $permissions = $subscription->permissions->pluck('id')->toArray();
  
        return response()->json(['permissions' => $permissions]);
    }


    public function storeSubscription(Request $request)
    {
        Subscription::create($request->all());
        return redirect()->back()->with('success', 'Subscription created successfully');
    }

    public function assignPermissionsToSubscription(Request $request)
    {
        // Validate the input
        $request->validate([
            'subscription_id' => 'required',
            'permissions' => 'array', // Ensure it's an array
        ]);

        // Find the subscription by ID
        $subscription = Subscription::find($request->subscription_id);

        if (!$subscription) {
            return redirect()->back()->withErrors('Subscription not found');
        }

        // Sync the selected permissions (removes unchecked ones, adds new ones)
        $subscription->permissions()->sync($request->permissions);

        return redirect()->back()->with('success', 'Permissions assigned/updated successfully.');
    }

    public function masterPlan(Request $request){
        $plans=Plan::where('library_id',auth()->user()->id)->withTrashed()->get();
        $hours=DB::table('hour')->where('library_id',auth()->user()->id)->get();
        $plantype=PlanType::withTrashed()->where('library_id',auth()->user()->id)->get();
        $plantypes=PlanType::where('library_id',auth()->user()->id)->get();
        $planprice=PlanPrice::withTrashed()->where('library_id',auth()->user()->id)->get();
        $total_seat=Seat::where('library_id',auth()->user()->id)->count();
        $seat_button=Library::where('id',Auth::user()->id)->where('status',1)->exists();
       
        return view('master.library-masters',compact('total_seat','plans','hours','plantype','planprice','plantypes','seat_button'));
    }
    
    public function storemaster(Request $request, $id = null)
    {
        
        $this->validationfunction($request);
        $modelClass = 'App\\Models\\' . $request->databasemodel;
        $table=$request->databasetable;
        $data=$request->all();
        $day_type=null;
        
        if ($request->databasemodel == 'Plan'){
            $data['name']=$request->plan_id . ' MONTHS';
        }
        if ($request->databasemodel == 'PlanType'){
            $data = $request->except(['timming']);

            if ($request->image == 'orange') {
                $data['image'] = 'public/img/booked.png';
            } elseif ($request->image == 'light_orange') {
                $data['image'] = 'public/img/booked.png';
            } else {
                $data['image'] = 'public/img/booked.png';
            }
    
            $first_record = DB::table('hour')->where('library_id', $request->library_id)->first();
            $total_hour = $first_record ? $first_record->hour : null;
    
           
            if ($total_hour && $data['slot_hours'] == $total_hour) {
                $day_type = 1;
            } elseif ($total_hour && $data['slot_hours'] == $total_hour / 2) {
                if ($data['start_time'] > '14:00') {
                    $day_type = 2; 
                } else {
                    $day_type = 3; 
                }
            } elseif ($total_hour && $data['slot_hours'] == $total_hour / 4) {
             
                switch ($request->timming) {
                    case 'Morning1':
                        $day_type = 4;
                        break;
                    case 'Morning2':
                        $day_type = 5;
                        break;
                    case 'Evening1':
                        $day_type = 6;
                        break;
                    case 'Evening2':
                        $day_type = 7;
                        break;
                    default:
                        $request->validate([
                            'timming' => 'required', // Validate timing if needed
                        ]);
                        break;
                }
    
              
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Plan Type is full'
                ], 400);
            }

            
            $data['day_type_id'] = $day_type;
        }
        $this->conditionFunction($request,$day_type);
        try {
            unset($data['databasemodel']); 
            unset($data['databasetable']); 
            unset($data['_token']);
            if($request->databasemodel){
                if (is_null($data['id'])) {
               
                    $modelInstance = $modelClass::create($data);
                } else {
                  
                    $modelInstance = $modelClass::findOrFail($data['id']);
                    
                    $modelInstance->update($data);
                }
            }
            elseif($request->databasetable){
                if (empty($data['id'])) {
                    $modelInstance=DB::table($table)->insert($data);
                }else {
                    $modelInstance=DB::table($table)->where('id', $data['id'])->update($data);
                }
            }
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
    
            if ($hourexist > 0 && $extendexist > 0 && $seatExist > 0 && $plan > 0 && $plantype >= 3 && $planPrice > 0) {
                $id=Auth::user()->id;
                $library=Library::findOrFail($id);
                $library->status = 1; 
                $library->save();  
            }
    
            return response()->json([
                'success' => true, 
                'message' => 'Data Added/Updated successfully',
                'plan' => $modelInstance  
            ]);
        }  catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
        
    }

    public function extendDay(Request $request){
        $request->validate([
            'extend_days' => 'required',
        ]);

        $id = $request->id;
        if(DB::table('hour')->where('library_id', $request->library_id)){
            $hourData=DB::table('hour')->where('library_id', $request->library_id)->update([
                'extend_days'=>$request->extend_days
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Please Add Hour'
            ], 400);
        }
        
       
        return response()->json([
            'success' => true, 
            'message' => 'Extend Days Added/Updated successfully',
            'hour' => $hourData  
        ]);


    }
    
    public function seatsStore(Request $request){
        $totalSeats = $request->input('total_seats');
    
        if (!$totalSeats || $totalSeats <= 0) {
            return response()->json([
                'error' => true,
                'message' => 'Invalid number of seats'
            ], 400);
           
        }
    
        $lastSeatNo = Seat::where('library_id',$request->library_id)->orderBy('seat_no', 'desc')->value('seat_no');
    
        $startSeatNo = $lastSeatNo ? $lastSeatNo + 1 : 1;
    
        $seats = [];
    
        for ($i = 0; $i < $totalSeats; $i++) {
            $seats[] = [
                'seat_no' => $startSeatNo + $i,
                'library_id'=>$request->library_id,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Seat::create($seats);
       
        return response()->json([
            'success' => true, 
            'message' => 'Seat Added/Updated successfully',
              
        ]);
        
    }
    public function masterEdit(Request $request){
        $id=$request->id;
        try {
            if($request->modeltable=='Plan' || $request->modeltable=='PlanType' || $request->modeltable=='PlanPrice'){
                $modelClass = 'App\\Models\\' . $request->modeltable;
                $data=$modelClass::findOrFail($id);
            }elseif($request->modeltable=='hour' ){
                $data = DB::table('hour')->where('id', $id)->first();
            }elseif($request->modeltable=='seats'){
                $data=DB::table('seats')->where('library_id',$id)->count();
                
            }
            if($request->modeltable=='Plan'){
                return response()->json(['plan' => $data]);
            }elseif($request->modeltable=='PlanType'){
                return response()->json(['plantype' => $data]);
            }elseif($request->modeltable=='PlanPrice'){
                return response()->json(['planprice' => $data]);
            }elseif($request->modeltable=='hour'){
                return response()->json(['hour' => $data]);
            }elseif($request->modeltable=='seats'){
                return response()->json(['seats' => $data]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
        
    }

    public function activeDeactive(Request $request,$id)
    {
        
        $modelClass = 'App\\Models\\' . $request->dataTable; // Dynamically build the model class name

            if (!class_exists($modelClass)) {
                return response()->json(['status' => 'error', 'message' => 'Invalid model'], 400);
            }

            $data = $modelClass::withTrashed()->find($id);

            if ($data) {
              
                if ($data->trashed()) {
                    $data->restore();
                    $status = 'activated';
                } else {
                    $data->delete();
                    $status = 'deactivated';
                }
            
                return response()->json(['status' => 'success', 'message' => 'Data successfully ' . $status, 'data_status' => $status]);
            }else{
                
            } return response()->json(['status' => 'error', 'message' => 'Data not found'], 404);
        
    }

    protected function conditionFunction(Request $request, $day_type = null)
    {
        
        $modelClass = 'App\\Models\\' . $request->databasemodel;
        $check_from_id = null;
        $check_to_id = null;

        if ($request->databasemodel == 'Plan') {
            $check_from_id = 'plan_id';
            $check_to_id = $request->plan_id;
        } elseif ($request->databasemodel == 'PlanType') {
            $check_from_id = 'day_type_id';
            $check_to_id = $day_type;
        }

        
        if ($request->databasemodel == 'Plan' || $request->databasemodel == 'PlanType'  ) {
          
            $query = $modelClass::where($check_from_id, $check_to_id)
                ->where('library_id', $request->library_id);

        }elseif($request->databasemodel == 'PlanPrice'){
            $query = $modelClass::where('plan_id', $request->plan_id)->where('plan_type_id',$request->plan_type_id)
            ->where('library_id', $request->library_id);
            
        }elseif($request->databasetable=='hour'){
            $query =DB::table('hour')->where('library_id', $request->library_id);
        }

        if (!empty($request->id)) {
            $query->where('id', '!=', $request->id);
        }
        $existing = $query->count();

        if ($existing > 0) {
            throw new \Exception('Data already exists.');
        }
    }


    protected function validationfunction(Request $request){
        if ($request->databasemodel == 'Plan'){
            $request->validate([
                'plan_id' => 'required|integer',
            ]);
        }
       
        if($request->databasemodel == 'PlanType'){
            $request->validate([
                'name' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
                'slot_hours' => 'required', 
            ]);
        }
        if($request->databasemodel == 'PlanPrice'){
            $request->validate([
                'plan_id' => 'required',
                'plan_type_id' => 'required',
                'price' => 'required',
            ]);
        }
        if($request->databasetable == 'hour'){
            $request->validate([
                'hour' => 'required|integer',
            ]);
        }
        if($request->databasetable == 'seats'){
            $request->validate([
                'total_seats' => 'required|integer',
            ]);
            
        }
        
    }

   
    


    
}
