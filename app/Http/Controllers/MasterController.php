<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Expense;
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
        $permissions = Permission::where('guard_name','library')->get();
        $users = User::all();

        return view('master.subscriptionPermission', compact('subscriptions', 'permissions', 'users'));
    }
    
    public function managePermissions($permissionId = null)
    {
        
        $subscriptions = Subscription::with('permissions')->get();
        $permissions =  Permission::get(); 
        $permission = $permissionId ? Permission::find($permissionId) : null;
        return view('master.permissions', compact('subscriptions', 'permission','permissions'));
    }

    
    public function storeOrUpdatePermission(Request $request, $permissionId = null)
    {
       
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
           
        ]);

        
        if ($permissionId) {
          
            $permission = Permission::findOrFail($permissionId);
            $permission->update($request->only('name', 'description', 'guard_name'));
            $message = 'Permission updated successfully.';
        } else {
           
            $permission = Permission::create($request->only('name', 'description', 'guard_name'));
          
            $message = 'Permission added successfully.';
        }

        return redirect()->route('permissions') ->with('success', $message);
    }


  
    public function deletePermission($permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        $permission->delete();

        return redirect()->route('permissions')
            ->with('success', 'Permission deleted successfully.');
    }
    public function deleteSubscriptionPermission(Request $request, $permissionId)
    {
        $subscriptionId = $request->subscription_id;
    
      
        DB::table('subscription_permission')
            ->where('subscription_id', $subscriptionId)
            ->where('permission_id', $permissionId)
            ->delete();
    
        return redirect()->back()->with('success', 'Permission successfully deleted from the subscription.');
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
        
        $request->validate([
            'subscription_id' => 'required',
            'permissions' => 'array', 
        ]);

        $subscription = Subscription::find($request->subscription_id);

        if (!$subscription) {
            return redirect()->back()->withErrors('Subscription not found');
        }

        $subscription->permissions()->sync($request->permissions);
          // $subscription = Subscription::findOrFail($request->subscription_id);
            // $subscription->permissions()->attach($permission->id);
        return redirect()->back()->with('success', 'Permissions assigned/updated successfully.');
    }

    public function masterPlan(Request $request){
        $plans=Plan::where('library_id',auth()->user()->id)->withTrashed()->get();
        $hours=Hour::withTrashed()->get();
        $plantype=PlanType::withTrashed()->where('library_id',auth()->user()->id)->get();
        $plantypes=PlanType::where('library_id',auth()->user()->id)->get();
        $planprice=PlanPrice::withTrashed()->with(['plan', 'planType'])->get();
        $total_seat=Seat::where('library_id',auth()->user()->id)->count();
        $seat_button=Library::where('id',Auth::user()->id)->where('status',1)->exists();
       $expenses=Expense::get();
       $is_extendday=Hour::whereNotNull('extend_days')->exists();
        return view('master.library-masters',compact('total_seat','plans','hours','plantype','planprice','plantypes','seat_button','expenses','is_extendday'));
    }
    
    public function storemaster(Request $request, $id = null)
    {
       
        $this->validationfunction($request);
        $modelClass = 'App\\Models\\' . $request->databasemodel;
        $table=$request->databasetable;
        $data=$request->all();
        $plan_type_name=null;
        
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
    
           if($request->day_type_id==1){
            $plan_type_name='Full Day';
           }elseif($request->day_type_id==2){
            $plan_type_name='First Half';
           }elseif($request->day_type_id==3){
            $plan_type_name='Second Half';
           }elseif($request->day_type_id==4){
            $plan_type_name='Hourly Slot 1';
           }elseif($request->day_type_id==5){
            $plan_type_name='Hourly Slot 2';
           }elseif($request->day_type_id==6){
            $plan_type_name='Hourly Slot 3';
           }elseif($request->day_type_id==7){
            $plan_type_name='Hourly Slot 4';
           }

            
            $data['name'] = $plan_type_name;
           
           
        }
        $this->conditionFunction($request,$plan_type_name);
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
            // elseif($request->databasetable){
            //     if (empty($data['id'])) {
            //         $modelInstance=DB::table($table)->insert($data);
            //     }else {
            //         $modelInstance=DB::table($table)->where('id', $data['id'])->update($data);
            //     }
            // }
            
    
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
    
    public function seatsStore(Request $request) {
        $totalSeats = $request->input('total_seats');
    
        if (!$totalSeats || $totalSeats <= 0) {
            return response()->json([
                'error' => true,
                'message' => 'Invalid number of seats'
            ], 400);
        }
    
        $lastSeatNo = Seat::where('library_id', $request->library_id)
                          ->orderBy('seat_no', 'desc')
                          ->value('seat_no');
    
        $startSeatNo = $lastSeatNo ? $lastSeatNo + 1 : 1;
    
        $seats = [];
    
        for ($i = 0; $i < $totalSeats; $i++) {
            $seats[] = [
                'seat_no' => $startSeatNo + $i,
                'library_id' => $request->library_id,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    
        // Use insert() for batch insertion
        Seat::insert($seats);
    
        return response()->json([
            'success' => true, 
            'message' => 'Seat(s) added/updated successfully',
        ]);
    }
    
    public function masterEdit(Request $request){
        $id=$request->id;
        try {
           if($request->modeltable=='Seat'){
                $data=Seat::count();
                
            }else{
                $modelClass = 'App\\Models\\' . $request->modeltable;
                $data=$modelClass::findOrFail($id);
            }

            return response()->json([$request->modeltable => $data]);
           
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
        
    }

    public function activeDeactive(Request $request, $id)
    {
        $modelClass = 'App\\Models\\' . $request->dataTable;

        if (!class_exists($modelClass)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid model'], 400);
        }
       
        if ($request->dataTable == 'Hour') {
            $hour = Hour::find($id);
            if ($hour) {
                $hour->update(['extend_days' => null]);
                return response()->json(['status' => 'success', 'message' => 'Hour successfully updated', 'data_status' => 'updated']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Hour not found'], 404);
            }
        } else {
            $data = $modelClass::withTrashed()->find($id);

            if ($data) {
                if ($data->trashed()) {
                    $data->restore();
                    $status = 'activated';
                } else {
                    $data->delete();
                    $status = 'deactivated';
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Data successfully ' . $status,
                    'data_status' => $status
                ]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Data not found'], 404);
            }
        }
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
            $check_to_id = $request->day_type_id;
        }elseif ($request->databasemodel == 'Expense') {
            $check_from_id = 'name';
            $check_to_id = $request->name;
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Something went wrong',
            ]);
        }

        
        if ($request->databasemodel == 'Plan' || $request->databasemodel == 'PlanType'  ) {
           
            $query = $modelClass::where($check_from_id, $check_to_id)
                ->where('library_id', $request->library_id);

        }elseif($request->databasemodel == 'PlanPrice'){
            $query = $modelClass::where('plan_id', $request->plan_id)->where('plan_type_id',$request->plan_type_id)
            ->where('library_id', $request->library_id);
            
        }elseif($request->databasetable=='hour'){
            $query =DB::table('hour')->where('library_id', $request->library_id);
        }else{
            $query = $modelClass::where($check_from_id, $check_to_id)
            ->where('library_id', $request->library_id);

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
                'day_type_id' => 'required',
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
        if ($request->databasemodel == 'Expense'){
            $request->validate([
                'name' => 'required',
            ]);
        }
        
    }

   
    


    
}
