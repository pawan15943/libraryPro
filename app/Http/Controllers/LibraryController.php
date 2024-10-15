<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLibraryRequest;
use App\Models\City;
use App\Models\Library;
use App\Models\LibraryTransaction;
use App\Models\State;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Services\LibraryService;
use Auth;
use DB;
use Carbon\Carbon;

class LibraryController extends Controller
{
    protected $libraryService;
    public function __construct(LibraryService $libraryService)
    {
        $this->libraryService = $libraryService;
    }
    
    public function index(Request $request)
    {
        $query = Library::leftJoin('library_transactions', 'libraries.id', '=', 'library_transactions.library_id')
            ->where('library_transactions.is_paid', 1)
            ->select('libraries.id', 'libraries.library_type', 'libraries.status', 'libraries.library_name', 'libraries.library_mobile', 'libraries.email', DB::raw('MAX(library_transactions.id) as latest_transaction_id'))
            ->groupBy('libraries.id', 'libraries.library_type', 'libraries.status', 'libraries.library_name', 'libraries.library_mobile', 'libraries.email');
    
        // Filter by Plan
        if ($request->filled('plan_id')) {
            $query->where('libraries.library_type', $request->plan_id);
        }
    
        // Filter by Payment Status
        if ($request->filled('is_paid')) {
            $query->where('is_paid', $request->is_paid);
        }
    
        // Filter by Active/Expired
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('libraries.status', 1);
            } elseif ($request->status == 'expired') {
                $query->where('libraries.status', 0);
            }
        }
    
        // Search by Name, Mobile, or Email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('libraries.library_name', 'LIKE', "%{$search}%")
                  ->orWhere('libraries.library_mobile', 'LIKE', "%{$search}%")
                  ->orWhere('libraries.email', 'LIKE', "%{$search}%");
            });
        }
    
        $libraries = $query->get();
        $plans = Subscription::get();
    
        return view('library.index', compact('libraries', 'plans'));
    }
    
  

    public function create(){
        $states=State::where('is_active',1)->get();
        return view('library.create',compact('states'));
    }

    protected function libraryValidation(Request $request)
    {
        $rules = [
            'library_name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:libraries,email',
            'library_mobile' => 'required|digits:10',
            'state_id'       => 'nullable|exists:states,id',
            'city_id'        => 'nullable|exists:cities,id',
            'library_address'=> 'nullable|string|max:500',
            'library_zip'    => 'nullable|digits:6',
            'library_type'   => 'nullable|string|max:255',
            'library_owner'  => 'nullable|string|max:255',
            'library_logo'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password'       => 'required|string|min:8',
            'terms'          => 'accepted',
        ];
        

        return Validator::make($request->all(), $rules);
    }

    public function store(Request $request)
    {
       
        // Validate the request
        $validatedData = $this->libraryValidation($request);
        
        if ($validatedData->fails()) {
            return redirect()->back()->withErrors($validatedData)->withInput();
        }

        $validated = $validatedData->validated();
        unset($validated['terms']);

        if ($request->hasFile('library_logo')) {
            $library_logo = $request->file('library_logo');
            $library_logoNewName = "library_logo_" . time() . '.' . $library_logo->getClientOriginalExtension();
            $library_logo->move(public_path('uploads'), $library_logoNewName);
            $validated['library_logo'] = 'uploads/' . $library_logoNewName;
        }

        $validated['password'] = bcrypt($validated['password']);
      
        try {
            $library = Library::create($validated);

            if ($library) {
               
                $otp = Str::random(6); 
                $library->email_otp = $otp;
                $library->save();
                
                $this->sendVerificationEmail($library);
                session(['library_email' => $library->email]);

                return redirect()->route('verification.notice')
                    ->with('message', 'Please verify your email to continue.');
            } else {
                return response()->json(['error' => 'Library creation failed.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function sendVerificationEmail($library)
    {
        // Prepare the data to send to the email view
        $data = [
            'name' => $library->library_name,
            'email' => $library->email,
            'otp' => $library->email_otp,
        ];

        Mail::send('email.verify-email', $data, function($message) use ($data) {
            $message->to($data['email'], $data['name'])->subject('Verify Your Email Address');
        });
    }

    public function verifyOtp(Request $request)
    {
      
        // Validate the input
        $request->validate([
            'email' => 'required|email',
            'email_otp' => 'required',
        ]);

        // Find the library by email
        $library = Library::where('email', $request->email)->first();
     

        if (!$library) {
            return redirect()->back()->withErrors(['email' => 'Library not found']);
        }
        
        // Check if the OTP matches
        if ($library->email_otp == $request->email_otp) {
            // Mark email as verified
            $library->email_verified_at = now();
            $library->save();

             
            // Log the user in (assuming you're using Laravel's built-in auth)
            Auth::guard('library')->login($library);
            
            // Now that the user is logged in, you can access their role
            $user = Auth::guard('library')->user();
            if ($user && !$user->hasRole('admin', 'library')) {
                // Assign the 'admin' role to the user under the 'library' guard
                $user->assignRole('admin');
            }

            // Redirect to dashboard or wherever you want
            return redirect()->route('library.home')->with('success', 'Email verified and logged in successfully.');
        } else {
            return redirect()->back()->withErrors(['email_otp' => 'Invalid OTP. Please try again.']);
        }
    }

    public function sidebarRedirect(){
        $redirectUrl = $this->libraryService->checkLibraryStatus();
       
            if ($redirectUrl) {
                return redirect($redirectUrl);
            }
    }
    public function choosePlan()
    {
        
        $subscriptions = Subscription::with('permissions')->get();
      
        return view('library.plan', compact('subscriptions'));
    }



    public function getSubscriptionPrice(Request $request)
    {
        
        if($request->plan_mode==1){
            $subscription_prices = Subscription::with('permissions')->select('monthly_fees as fees','id')->get();
        }elseif($request->plan_mode==2){
            $subscription_prices = Subscription::with('permissions')->select('yearly_fees as fees','id')->get();

        }
        
        return response()->json([
            'subscription_prices' => $subscription_prices,
            
        ]);
    }

    public function paymentProcess(Request $request)
    {
        $month = ($request->plan_mode == 2) ? 12 : 1;

        if ($request->library_id) {
            $library_id = $request->library_id;
        } elseif (Auth::check()) { 
            $library_id = Auth::user()->id;
        } else {
            return redirect()->back()->with('error', 'Library ID not provided.');
        }

        
        if (!$library_id) {
            return redirect()->back()->with('error', 'Library ID is missing.');
        }

        $today = date('Y-m-d');
        $existingTransaction = LibraryTransaction::where('library_id', $library_id)
            ->where(function($query) use ($today) {
                $query->where('is_paid', 0)
                    ->where(function($subQuery) use ($today) {
                        $subQuery->whereNull('end_date')
                                ->orWhere('end_date', '>=', $today);
                    });
            })
            ->exists();
        $gst_discount = DB::table('gst_discount')->first(); 

        if ($gst_discount) {
            $gst = $gst_discount->gst ?? 0;       
            $discount = $gst_discount->discount ?? 0; 
        } else {
            $gst = 0;
            $discount = 0;
        }
        $amount=$request->price;
        //First Apply Discount, Then GST
        $discount_amount=$amount*($discount/100);
        $price_after_discount=$amount-$discount_amount;
        $gst_amount=$price_after_discount*($gst/100);
        $final_price=$price_after_discount+$gst_amount;
       
           
        if (isset($request->subscription_id) && !is_null($request->subscription_id)) {
           
            Library::where('id', $library_id)->update([
                'library_type' => $request->subscription_id,
            ]);
        
            $transactionId = null;
        
            if ($existingTransaction) {
                
                LibraryTransaction::where('library_id', $library_id)
                    ->where(function($query) use ($today) {
                        $query->where('is_paid', 0)
                              ->where(function($subQuery) use ($today) {
                                  $subQuery->whereNull('end_date')
                                           ->orWhere('end_date', '>=', $today);
                              });
                    })
                    ->update([
                        'amount'       => $amount,
                        'paid_amount'  => $final_price,
                        'month'        => $month,
                        'subscription' => $request->subscription_id,
                        'gst'          => $gst,
                        'discount'     => $discount,
                    ]);
                
                // Get the last updated ID
                $transactionId = LibraryTransaction::where('library_id', $library_id)
                    ->where('is_paid', 0)
                    ->where(function($query) use ($today) {
                        $query->whereNull('end_date')->orWhere('end_date', '>=', $today);
                    })
                    ->latest('id')
                    ->value('id');
            } else {
               
                $transaction = LibraryTransaction::create([
                    'library_id'   => $library_id,
                    'amount'       => $amount,
                    'paid_amount'  => $final_price,
                    'month'        => $month,
                    'subscription' => $request->subscription_id,
                    'gst'          => $gst,
                    'discount'     => $discount,
                ]);
                $transactionId = $transaction->id;
            }
        
            
        } else {
            return redirect()->back()->with('error', 'No valid subscription selected.');
        }
        
     

        // Retrieve the most recent transaction
        $data = Library::where('id', $library_id)
        ->with('subscription.permissions')  
        ->first();
        $plan = Subscription::where('id', $data->library_type)->first();
       
        $month = LibraryTransaction::where('id', $transactionId)
            ->orderBy('id', 'desc')
            ->first();
      $all_transaction = LibraryTransaction::where('library_id', $library_id)
            ->where('is_paid', 1)
            ->with(['subscription', 'subscription.permissions'])
            ->get();
          
        return view('library.payment', [
            'transactionId' => $transactionId,
            'month'         => $month,
            'plan'          => $plan,
            'data'          => $data,
            'all_transaction' => $all_transaction,
            'discount_amount'  =>$discount_amount,
            'gst_amount'  =>$gst_amount,
        ]);
    }


    public function paymentStore(Request $request)
    {
       
        $this->validate($request, [
            'payment_method' => 'required',
           
        ]);
        $library_transaction_id = LibraryTransaction::where('id', $request->library_transaction_id)->first();

        if($request->payment_method=='2'){
           
            LibraryTransaction::where('id', $request->library_transaction_id)->update([
               
                'transaction_id' => $request->transaction_id ??  Str::random(8),
               
            ]);
        }
        // elseif($request->payment_method=='1'){

        // }
        

        if ($library_transaction_id) {
            
            $duration = $library_transaction_id->month ?? 0;

            if (LibraryTransaction::where('library_id', $library_transaction_id->library_id)->where('status', 1)->exists()) {
                $library_tra = LibraryTransaction::where('library_id', $library_transaction_id->library_id)
                                                 ->where('status', 1)
                                                 ->orderBy('id', 'desc')
                                                 ->first();
            
                $start_date = Carbon::parse($library_tra->end_date)->addDay(1);
                $endDate = $start_date->copy()->addMonths($duration);
                $status = 0;
            } else {
                $start_date = now(); 
                $endDate = $start_date->copy()->addMonths($duration);
                $status = 1;
            }
            
           
            // Update the transaction details
            LibraryTransaction::where('id', $request->library_transaction_id)->update([
                'start_date' => $start_date->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'transaction_date' => now()->format('Y-m-d'),
                'payment_mode'=>$request->payment_method,
                'is_paid' => 1,
                'status' => $status,
            ]);

            // Update the corresponding library's `is_paid` status
            Library::where('id', $library_transaction_id->library_id)->update([
                'is_paid' => 1,
               
            ]);
            $isProfile = Library::where('id', $library_transaction_id->library_id)->where('is_profile', 1)->exists();
            if($isProfile){
                
                return redirect()->route('library.home')->with('success', 'Payment successfully processed.');
            }else{
                return redirect()->route('profile')->with('success', 'Payment successfully processed.');
            }
           
           
        }
        return redirect()->back()->with('error', 'Transaction not found.');
    }


    public function profile()
    {
        
        $library = Library::where('id', auth()->user()->id)->first();  
        
        $states=State::where('is_active',1)->get();
        $citis=City::where('is_active',1)->get();
        
        return view('library.profile', compact('library', 'states','citis'));
    }

    public function updateProfile(Request $request)
    {
        
        $validated = $request->validate([
            'library_name' => 'required|string|max:255',
            'library_mobile' => 'required|string|max:10',
            'email' => 'required|email',
            'library_address' => 'required|string',
            'library_zip' => 'required|string|max:6',
            'library_owner' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'library_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'library_owner_email' => 'required|email',
            'library_owner_contact' => 'required|string|max:10',
        ]);

      
        if ($request->hasFile('library_logo')) {
            $library_logo = $request->file('library_logo');
            $library_logoNewName = "library_logo_" . time() . '.' . $library_logo->getClientOriginalExtension();
            $library_logo->move(public_path('uploads'), $library_logoNewName);
            $validated['library_logo'] = 'uploads/' . $library_logoNewName;
        }

      
        $library = Library::where('id', auth()->user()->id)->first();
       
        $update=$library->update($validated);
        if($update){
            $library->update([
                'is_profile' => 1
            ]);
        }
        

        return redirect()->route('library.master')->with('success', 'Profile updated successfully!');
    }

    public function transaction(){
        $data = Library::where('id', Auth::user()->id)
        ->with('subscription.permissions')  // Fetch associated subscription and permissions
        ->first();
        $plan=Subscription::where('id',$data->library_type)->first();
        $transaction=LibraryTransaction::where('library_id',Auth::user()->id)->where('is_paid',1)->get();
        return view('library.transaction',compact('transaction','plan','data'));
    }
    public function myplan(){
        $data = Library::where('id', Auth::user()->id)
        ->with('subscription.permissions')  // Fetch associated subscription and permissions
        ->first();
        $month=LibraryTransaction::where('library_id',Auth::user()->id)->where('is_paid',1)->get();
       
        $plan=Subscription::where('id',$data->library_type)->first();
       
        return view('library.my-plan',compact('data','month','plan'));
    }

    // from superadmin side
    public function showLibrary($id){
        $library=Library::findOrFail($id);
        $plan=Subscription::where('id',$library->library_type)->with('permissions')->first();
        $library_transaction=LibraryTransaction::where('library_id',$library->library_id)->where('is_paid',1)->orderBy('DESC')->first();
        $library_all_transaction=LibraryTransaction::where('library_id',$library->library_id)->get();
        
        return view('library.view',compact('library','plan','library_transaction','library_all_transaction'));
    }

   
    


}
