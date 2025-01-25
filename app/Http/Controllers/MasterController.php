<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Expense;
use App\Models\Feature;
use App\Models\Feedback;
use App\Models\Hour;
use App\Models\Inquiry;
use App\Models\Learner;
use App\Models\Library;
use App\Models\PermissionCategory;
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

    public function showPlanwisePermission($id){
       
        // $subscriptions = Subscription::all();
        $subscriptions = Subscription::where('id',$id)->get();
        $permissions = Permission::where('guard_name','library')->get();
        $users = User::all();
        return view('master.showPlanwisePermissions', compact('subscriptions', 'permissions', 'users'));
    }
    public function subscriptionMaster(){
        $subscriptions = Subscription::withTrashed()->get();
        $permissions = Permission::where('guard_name','library')->get();
        $subscription=null;
        $users = User::all();
        return view('master.subscriptionMaster', compact('subscriptions', 'permissions', 'users','subscription'));
    }
    public function storeSubscription(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'monthly_fees' => 'required|numeric|min:0',
            'yearly_fees' => 'nullable|numeric|min:0',
        ]);
        Subscription::create($request->all());
        return redirect()->back()->with('success', 'Subscription created successfully');
    }
    public function subscriptionMasterUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'monthly_fees' => 'required|numeric|min:0',
            'yearly_fees' => 'nullable|numeric|min:0',
        ]);

        $subscription = Subscription::findOrFail($id);
        $subscription->update($validated);

        return redirect()->route('subscription.master')->with('success', 'Subscription updated successfully.');
    }
    public function subscriptionMasterEdit($id = null){
        $subscription = $id ? Subscription::withTrashed()->find($id) : null;
        $subscriptions = Subscription::withTrashed()->get();
        $permissions = Permission::where('guard_name','library')->get();
       
        $users = User::all();
        return view('master.subscriptionMaster', compact('subscriptions', 'permissions', 'users','subscription'));
    }
    public function deactiveSubscription($id)
    {
        
        try {
            DB::transaction(function () use ($id) {
                
                Subscription::where('id', $id)->delete();
            });
    
           
            return response()->json(['success' => 'Subscriptions successfully.']);
        } catch (\Exception $e) {
           
            return response()->json(['error' => 'An error occurred while deleting the customer: ' . $e->getMessage()], 500);
        }
    
        return response()->json(['success' => 'Learner deleted successfully.']);
    }
    public function managePermissions($permissionId = null,$categoryId = null)
    {
        
        $subscriptions = Subscription::with('permissions')->get();
        $permissions =  Permission::get(); 
        $permission = $permissionId ? Permission::find($permissionId) : null;
        $category = $categoryId ? PermissionCategory::find($categoryId) : null;
        $categories = PermissionCategory::all();
        return view('master.permissions', compact('subscriptions', 'permission','permissions','categories','category'));
    }
    public function storeOrUpdateCategory(Request $request, $categoryId = null)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            
        ]);

        PermissionCategory::updateOrCreate(['id' => $categoryId], $data);

        return redirect()->route('permissions')->with('success', 'Permission Category saved successfully.');
    }

    public function storeOrUpdatePermission(Request $request, $permissionId = null)
    {
       
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
           'permission_category_id' => 'required|exists:permission_categories,id',
        ]);

        
        if ($permissionId) {
          
            $permission = Permission::findOrFail($permissionId);
            $permission->update($request->only('name', 'description', 'guard_name', 'permission_category_id'));
            $message = 'Permission updated successfully.';
        } else {
           
            $permission = Permission::create($request->only('name', 'description', 'guard_name', 'permission_category_id'));
          
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
       $notleaner=Learner::where('id',Auth::user()->id)->count();
        return view('master.library-masters',compact('total_seat','plans','hours','plantype','planprice','plantypes','seat_button','expenses','is_extendday','notleaner'));
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
        $user=Auth::user();
        if ($user->can('Extend Seat')) {
            $extend_day = $request->extend_days;
        } else {
            $extend_day = 0;
        }
        
        
        if(DB::table('hour')->where('library_id', $request->library_id)){

            $hourData=DB::table('hour')->where('library_id', $request->library_id)->update([
                'extend_days'=>$extend_day
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
        $libraryData = Library::where('id', Auth::user()->id)->first();

        if ($libraryData) {
            $seatLimit = ($libraryData->library_type == 1) ? 50 : (($libraryData->library_type == 2) ? 75 : null);
        
            if ($seatLimit !== null && $totalSeats > $seatLimit) {
                return response()->json([
                    'error' => true,
                    'message' => 'Invalid number of seats'
                ], 400);  
            }
        }
        if (!$totalSeats || $totalSeats <= 0) {
            return response()->json([
                'error' => true,
                'message' => 'Invalid number of seats'
            ], 400);
        }
    
        $lastSeatNo = Seat::orderBy('seat_no', 'desc')->value('seat_no');
     
        $startSeatNo = $lastSeatNo ? $lastSeatNo + 1 : 1;
     
        $currentSeatCount = Seat::withoutGlobalScopes()->where('library_id', $request->library_id)->count();
        $seatsToAdd = $totalSeats - $currentSeatCount;
        if ($seatsToAdd > 0) {
         
        $seats = [];
    
        for ($i = 0; $i < $seatsToAdd; $i++) {
            
            $seats[] = [
                'seat_no' => $startSeatNo + $i,
                'library_id' => $request->library_id,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
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

    public function featureCreate(Request $request, $id = null){
        $feature = null;
        $features=Feature::get();
        return view('master.features',compact('features','feature'));
    }
    public function featureEdit($id)
    {
        $feature = Feature::findOrFail($id);
        $features = Feature::all();
        return view('master.features', compact('feature', 'features'));
    }

    public function destroy($id)
    {
        $feature = Feature::findOrFail($id);
        $feature->delete();

        return redirect()->route('feature.create')->with('success', 'Feature deleted successfully!');
    }
   
    public function storeFeature(Request $request, $id = null)
    {
       
        $rules = [
            'name' => 'required|string|max:255',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    
        $request->validate($rules);
    
        $data = $request->only('name');

        if ($request->hasFile('image') ) {
           
           $image = $request->file('image');
            $imageName = "icon" . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/icon/'), $imageName);
            $data['image'] = 'uploads/icon/' . $imageName;
            if ($id) {
                $feature = Feature::findOrFail($id);
                if ($feature->image && file_exists(public_path($feature->image))) {
                    unlink(public_path($feature->image));
                }
            }
        } elseif ($id) {
            $feature = Feature::findOrFail($id);
            $data['image'] = $feature->image;
        }

        if ($id) {
            $feature = Feature::findOrFail($id);
            $feature->update($data);
            $message = 'Feature updated successfully!';
        } else {
          
            Feature::create($data);
            $message = 'Feature added successfully!';
        }

        return redirect()->route('feature.create')->with('success', $message);
    }

  

    public function getLibraries(Request $request)
    {
        $query = $request->input('query'); 
        $suggestion = $request->input('suggestion'); 
        $city = $request->input('city'); 
     
        // If a suggestion is selected (library name or location or city)
        if ($suggestion) {
         
            // Search libraries based on the selected suggestion (name, location, or city)
            $libraries = Library::where('library_name', 'like', '%' . $suggestion . '%')
                                ->orWhere('library_address', 'like', '%' . $suggestion . '%')
                               
                                ->get();
        } elseif ($query) {
            
            // Search libraries based on the query input (name, location, or city)
            $libraries = Library::where('library_name', 'like', '%' . $query . '%')
                                ->orWhere('library_address', 'like', '%' . $query . '%')
                               
                                ->get();
        } elseif($city){
            $libraries = Library::where('city_id', '=', $city)
            
            ->get();
        }else {
            // Return top 5 libraries by default (e.g., by rating or popularity)
            $libraries = Library::take(5)->get();
        }
       
        return response()->json($libraries);
    }
    public function Inquerystore(Request $request)
    {
        $data=$request->validate([
            'full_name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ]);
    
       
        Inquiry::create($data);

        return back()->with('success', 'Inquiry submitted successfully!');
    }


    

    
}
