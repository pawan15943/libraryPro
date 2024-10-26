<?php

namespace App\Http\Controllers;

use App\Models\CustomerDetail;
use App\Models\Customers;
use App\Models\Hour;
use App\Models\LearnerDetail;
use App\Models\Library;
use App\Models\LibraryTransaction;
use App\Models\Seat;
use App\Models\Student;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Carbon;
use App\Services\LibraryService;
use App\Services\LearnerService;
use App\Traits\LearnerQueryTrait;
use App\Http\Middleware\LoadMenus;
use App\Models\PlanType;
use App\Models\Subscription;
use Log;

class DashboardController extends Controller
{
    use LearnerQueryTrait;
    protected $libraryService;
    protected $learnerService;


    public function __construct(LibraryService $libraryService, LearnerService $learnerService)
    {
        
        $this->libraryService = $libraryService;
        $this->learnerService = $learnerService;
    }
    public function index()
    {
       
        $user=Auth::user();
     
        if ($user->hasRole('superadmin')) {
           
        
            return view('dashboard.administrator');
        }if ($user->hasRole('admin')) {
           
            return view('dashboard.admin');
        }if ($user->hasRole('learner')) {
           
        
            return view('dashboard.learner');
        }
       
    }

    public function libraryDashboard(Request $request)
    {
        $user=Auth::user();
       
        if ($user->hasRole('admin')) {
            //load menus status function call for status update
            $middleware = app(LoadMenus::class);
            $middleware->statusInactive();
            $value = LibraryTransaction::where('library_id', Auth::user()->id)
            ->where('status', 1)
            ->first();
            $today = Carbon::today();
            if ($value) {
               
                $endDate = Carbon::parse($value->end_date);
                $diffInDays = $today->diffInDays($endDate, false);
                    if ($diffInDays < 0){
                        $library = Library::where('id', Auth::user()->id)->first();
                        if ($library) {
                            $library->is_paid = 0;
                            $library->save(); 
                        }
                    }
                 
                    if ($diffInDays == -5) {
                    // Update the transaction status to inactive
                    $value->status = 0;
                    $value->save();

                    $library = Library::where('id', Auth::user()->id)->first();
                    if ($library) {
                    $library->status = 0;
                    $library->save(); 
                    }
                }
            }

            
            $availble_seats=Seat::where('total_hours','==',0)->count(); 
          
            $booked_seats=Seat::where('is_available','!=',1)->where('total_hours','!=',0)->count();
            $total_seats=Seat::count();
          
            // redirect check library  
            $iscomp = Library::where('id', Auth::user()->id)->where('status', 1)->exists();
            $redirectUrl = $this->libraryService->checkLibraryStatus();
            $check = LibraryTransaction::where('library_id',  Auth::user()->id)->where('is_paid',1)->orderBy('id','desc')->first();
            $is_expire=false;
            if ($check) {
                $today = Carbon::today();
                $endDate = Carbon::parse($check->end_date);
                $librarydiffInDays = $today->diffInDays($endDate, false);
                if($librarydiffInDays <= 0){
                    $is_expire=true;
                }
                
            }

            $available_seats=$this->learnerService->getAvailableSeats();
            
           
            $extend_days_data = Hour::where('library_id', Auth::user()->id)->first();
            $extend_day = $extend_days_data ? $extend_days_data->extend_days : 0;
            $fiveDaysbetween = $today->copy()->addDays(5);
            $renewSeats = $this->getLearnersByLibrary()
            ->where('learner_detail.plan_end_date', '>=', $fiveDaysbetween->format('Y-m-d')) // plan_end_date is today - 5 days
            ->where('learner_detail.status',1)
            ->with('planType')
            ->get();
          
            $threeMonthsAgo = $today->copy()->subMonths(2)->startOfMonth(); // Start of 3 months ago
            $endOfLastMonth = $today->copy()->subMonth()->endOfMonth(); // End of last month
           

            // Fetch revenue for the last three months
           

            $data = Library::where('id', Auth::user()->id)
            ->with('subscription.permissions')  // Fetch associated subscription and permissions
            ->first();

            $plan=Subscription::where('id',$data->library_type)->first();
            if($plan){
                $features_count=DB::table('subscription_permission')->where('subscription_id',$plan->id)->count();

            }else{
                $features_count=0;
            }
            $extend_sets=$this->getLearnersByLibrary()
            ->where('learner_detail.is_paid',1)
            ->where('learner_detail.status',1)
            ->where('learner_detail.plan_end_date', '<', $today->format('Y-m-d'))
            ->whereRaw("DATE_ADD(learner_detail.plan_end_date, INTERVAL ? DAY) > CURDATE()", [$extend_day])
            ->with('planType')
            ->get();
            
            $revenues = LearnerDetail::whereBetween('join_date', [$threeMonthsAgo, $endOfLastMonth])
            ->where('is_paid', 1)
            ->selectRaw('YEAR(join_date) as year, MONTH(join_date) as month, SUM(plan_price_id) as total_revenue')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

            // Fetch expenses for the last three months
            $expenses = DB::table('monthly_expense')
                ->where('library_id', Auth::user()->id)
                ->whereBetween(DB::raw('DATE(CONCAT(year, "-", month, "-01"))'), [$threeMonthsAgo, $endOfLastMonth])
                ->selectRaw('year, month, SUM(amount) as total_expense')
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();
                
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $startDate = $startDate->format('Y-m-d');
                $endDate = $endDate->format('Y-m-d');
                $plans = $this->learnerService->getPlans();
            
          
           

            $plan_wise_booking=LearnerDetail::whereBetween('join_date', [$startDate, $endDate])
            ->where('is_paid', 1) ->groupBy('plan_type_id')->selectRaw('COUNT(id) as booking, plan_type_id')->get();
            $bookinglabels = $plan_wise_booking->map(function ($booking) {
                return $booking->planType->name; 
            })->toArray(); 
            $bookingcount = $plan_wise_booking->pluck('booking')->toArray(); 
          
            if($is_expire){
                return redirect()->route('library.myplan');
            }elseif($iscomp){
                return view('dashboard.admin',compact('availble_seats','plans','booked_seats','total_seats','available_seats','renewSeats','revenues','expenses','plan','features_count','check','extend_sets','bookingcount','bookinglabels'));
            }else{
                return redirect($redirectUrl);
            }
           
            
        }if ($user->hasRole('learner')) {
          
            return view('dashboard.learner');
        }
       
    }


    public function learnerDashboard(){
        $user=Auth::user();
        if ($user->hasRole('learner')) {
          
            return view('dashboard.learner');
        }
    }
    public function getData(Request $request)
    {
        // Fetch data based on the selected filter monthly, today, weekly
        $filter = $request->filter ?? 'monthly'; 

        switch ($filter) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'monthly':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            default:
                $startDate = Carbon::today();
                $endDate = Carbon::today();
        }
        
        Log::info("start date: $startDate, end date: $endDate");

        // // For graph
    
        $plan_wise_booking = LearnerDetail::whereBetween('join_date', [$startDate, $endDate])
            ->where('is_paid', 1)
            ->groupBy('plan_type_id')
            ->selectRaw('COUNT(id) as booking, plan_type_id')
            ->with('planType') // Assuming planType is a relationship
            ->get();

        // Prepare data for response
        $data = [];
        foreach ($plan_wise_booking as $booking) {
            $data[] = [
                'plan_type_id' => $booking->plan_type_id,
                'booking' => $booking->booking,
                'plan_type_name' => $booking->planType ? $booking->planType->name : 'Unknown' // Get the plan type name
            ];
        }

    
        $bookinglabels = $plan_wise_booking->map(function ($booking) {
            return $booking->planType->name; 
        })->toArray(); 
        
        $bookingcount = $plan_wise_booking->pluck('booking')->toArray(); 
        //plantype wise revenue
        $planTypeWiseRevenue = LearnerDetail::whereBetween('join_date', [$startDate, $endDate])
        ->where('is_paid', 1)
        ->groupBy('plan_type_id')
        ->selectRaw('SUM(plan_price_id) as revenue, plan_type_id')
        ->with('planType') // Assuming planType is a relationship
        ->get();

    // Prepare labels and data for revenue
    $revenueLabels = $planTypeWiseRevenue->pluck('planType.name')->toArray();
    $revenueData = $planTypeWiseRevenue->pluck('revenue')->toArray();

        // Library other highlights
        $total_booking = LearnerDetail::where('status', 1)
            ->where('is_paid', 1)
            ->whereBetween('join_date', [$startDate, $endDate])
            ->count();
        
        $online_paid = $this->getLearnersByLibrary()->whereBetween('learner_detail.join_date', [$startDate, $endDate])
            ->where('learners.payment_mode', 1)
            ->count();
        
        $offline_paid = $this->getLearnersByLibrary()->whereBetween('learner_detail.join_date', [$startDate, $endDate])
            ->where('learners.payment_mode', 2)
            ->count();
        
        $other_paid = $this->getLearnersByLibrary()->whereBetween('learner_detail.join_date', [$startDate, $endDate])
            ->where('learners.payment_mode', 3)
            ->count();
        $extend_days_data = Hour::where('library_id', Auth::user()->id)->first();
        $extend_day = $extend_days_data ? $extend_days_data->extend_days : 0;
        $fiveDaysBefore = Carbon::now()->addDays(-5)->format('Y-m-d'); // Add this line for the expired logic
        $expired_in_five = $this->getLearnersByLibrary()->whereDate('learner_detail.plan_end_date', '=', $fiveDaysBefore)->count();
        $expired_seats = $this->getLearnersByLibrary()->whereDate('learner_detail.plan_end_date', '<', now())->count();
        $extended_seats = $this->getLearnersByLibrary()
        ->where('learner_detail.is_paid',1)
        ->where('learner_detail.status',1)
        ->whereRaw("DATE_ADD(learner_detail.plan_end_date, INTERVAL ? DAY) > CURDATE()", [$extend_day])
        ->count();       
        $swap_seat=DB::table('learner_operations_log')->where('library_id', Auth::user()->id)->where('operation','=','swapseat')->count();
        $learnerUpgrade=DB::table('learner_operations_log')->where('library_id', Auth::user()->id)->where('operation','=','learnerUpgrade')->count();
        $reactive=DB::table('learner_operations_log')->where('library_id', Auth::user()->id)->where('operation','=','reactive')->count();
        
        return response()->json([
            'highlights' => [
               
                'swap_seat' => $swap_seat,
                'learnerUpgrade' => $learnerUpgrade,
                'reactive' => $reactive,
                'extended_seats' => $extended_seats,
                'total_booking' => $total_booking,
                'online_paid' => $online_paid,
                'offline_paid' => $offline_paid,
                'other_paid' => $other_paid,
                'expired_in_five' => $expired_in_five,
                'expired_seats' => $expired_seats,
            ],
        
            'plan_wise_booking' => $data,
            'planTypeWiseRevenue' => [
                'labels' => $revenueLabels,
                'data' => $revenueData,
            ],
            'planTypeWiseCount' => [
                'labels' => $bookinglabels,
                'data' => $bookingcount,
            ],
        ]);
    }
}
