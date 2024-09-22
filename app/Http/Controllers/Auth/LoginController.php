<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Show Admin (Library Owner) Login Form
    public function showAdminLoginForm()
    {
        return view('auth.login_admin');
    }

    // Show Learner Login Form
    public function showLearnerLoginForm()
    {
        return view('auth.login_learner');
    }

    // Handle Login
    public function login(Request $request)
    {
        
        // // Validate login input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'user_type' => 'required', // Ensure user type is selected
        ]);

        // Retrieve email and password from request
        $credentials = $request->only('email', 'password');
        
        // Check the user type and attempt login with the appropriate guard
        switch ($request->input('user_type')) {
            
            case 'superadmin':
                if (Auth::guard('web')->attempt($credentials)) {
               
                    return redirect()->intended(route('home'));
                }
                break;

            case 'admin':

                if (Auth::guard('library')->attempt($credentials)) {
                    $user = Auth::guard('library')->user();
                    
                    // Check if the user's email is verified
                    if (is_null($user->email_verified_at)) {
                        // Logout the user since email is not verified
                        Auth::guard('library')->logout();
            
                        // Redirect back with an error message
                        return redirect()->back()->withErrors(['email' => 'Your email address is not verified. Please verify your email to proceed.']);
                    }
                    
                    if ($user && !$user->hasRole('admin', 'library')) {
                        $user->assignRole('admin');
                    }
            
                    return redirect()->intended(route('library.home'));
                }
                
                break;
                

            case 'learner':
                if (Auth::guard('learner')->attempt($credentials, $request->filled('remember'))) {
                    $user = Auth::guard('learner')->user();
                        if (!$user->hasRole('learner')) {
                            $user->assignRole('learner');
                        }
                    // return redirect()->intended('/learner/dashboard');
                    return redirect()->intended(route('home'));
                }
                break;

            default:
                return back()->withErrors(['message' => 'Invalid user type selected.']);
        }

        // If login attempt fails
        return back()->withErrors(['message' => 'Login failed. Check your credentials and try again.']);
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        $user->touch(); 
    }

    
}
