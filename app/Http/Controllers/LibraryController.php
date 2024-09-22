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
use Auth;
use DB;

class LibraryController extends Controller
{
    
    public function index(){
        $libraries=Library::get();
        return view('library.index',compact('libraries'));
    }
    public function create(){
        $states=State::where('is_active',1)->get();
        return view('library.create',compact('states'));
    }

    protected function libraryValidation(Request $request)
    {
        $rules = [
            'library_name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255',
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
                // Send email verification notification
                $otp = Str::random(6); // Generate a 6-digit OTP
                $library->email_otp = $otp;
                $library->save();
                 // Send verification email with the OTP
                $this->sendVerificationEmail($library);
                session(['library_email' => $library->email]);

                // Redirect to the verification page with a success message
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

        // Set the library_id based on request or authenticated user
        if ($request->library_id) {
            $library_id = $request->library_id;
        } elseif (Auth::check()) {  // Check if user is authenticated
            $library_id = Auth::user()->id;
        } else {
            return redirect()->back()->with('error', 'Library id not provided.');
        }

        // Ensure $library_id is set correctly
        if (!$library_id) {
            return redirect()->back()->with('error', 'Library id is missing.');
        }

        // Insert the transaction and get the last inserted ID
        $transactionId = LibraryTransaction::insertGetId([
            'library_id' => $library_id,  // Use the resolved library_id here
            'amount'     => $request->price,
            'month'      => $month,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Update the library's subscription type
        Library::where('id', $library_id)->update([
            'library_type' => $request->subscription_id,
        ]);

        // Pass the last inserted transaction ID to the view
        return view('library.payment', [
            'transactionId' => $transactionId
        ]);
    }


    public function paymentStore(Request $request)
    {
        
        $transaction = DB::table('library_transactions')->where('id', $request->transaction_id)->first();

        // Check if the transaction exists
        if ($transaction) {
            // Update the transaction details
            DB::table('library_transactions')->where('id', $request->transaction_id)->update([
                'start_date' => now()->format('Y-m-d'),
                'transaction_date' => now()->format('Y-m-d'),
                'is_paid' => 1,
            ]);

            // Update the corresponding library's `is_paid` status
            Library::where('id', $transaction->library_id)->update([
                'is_paid' => 1,
            ]);

            return redirect()->route('profile')->with('success', 'Payment successfully processed.');
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
        // Validate the request data
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
        ]);

        // Handle the logo upload if a new file is provided
        if ($request->hasFile('library_logo')) {
            $library_logo = $request->file('library_logo');
            $library_logoNewName = "library_logo_" . time() . '.' . $library_logo->getClientOriginalExtension();
            $library_logo->move(public_path('uploads'), $library_logoNewName);
            $validated['library_logo'] = 'uploads/' . $library_logoNewName;
        }

        // Find the library based on the authenticated user's ID
        $library = Library::where('id', auth()->user()->id)->first();
       
        // Update the library record with validated data
        $library->update($validated);

        return redirect()->route('library.master')->with('success', 'Profile updated successfully!');
    }

}
