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
use App\Models\Learner;
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

            $available_seats=$this->learnerService->getAvailableSeatsPlantype();
            
           
            $extend_days_data = Hour::where('library_id', Auth::user()->id)->first();
            $extend_day = $extend_days_data ? $extend_days_data->extend_days : 0;
            $fiveDaysbetween = $today->copy()->addDays(5);
            $renewSeats = $this->getLearnersByLibrary()
            ->whereBetween('learner_detail.plan_end_date', [$today->format('Y-m-d'), $fiveDaysbetween->format('Y-m-d')]) // Filter between today and 5 days in the future
            ->where('learner_detail.status', 1) // Only include active learners
            ->with('planType') // Eager load related planType
            ->get();
            $extend_sets = $this->getLearnersByLibrary()
            ->where('learner_detail.is_paid', 1) // Only paid learners
            ->where('learner_detail.status', 1)  // Only active learners
            ->where('learner_detail.plan_end_date', '<', $today->format('Y-m-d')) // plan_end_date is before today
            ->whereRaw("DATE_ADD(learner_detail.plan_end_date, INTERVAL ? DAY) >= CURDATE()", [$extend_day]) // Extended date is today or in the future
            ->with('planType') // Eager load related planType
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
           
            $startOfYear = Carbon::now()->startOfYear();
            $endOfYear = Carbon::now()->endOfYear();
            $revenues = LearnerDetail::whereBetween('join_date', [$startOfYear, $endOfYear])
            ->where('is_paid', 1)
            ->selectRaw('YEAR(join_date) as year, MONTH(join_date) as month, SUM(plan_price_id) as total_revenue')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->keyBy('month');
         

            // Fetch expenses for the last three months
            $expenses = DB::table('monthly_expense')
                ->where('library_id', Auth::user()->id)
                ->whereBetween(DB::raw('DATE(CONCAT(year, "-", month, "-01"))'), [$startOfYear, $endOfYear])
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
                return view('dashboard.admin',compact('plans','available_seats','renewSeats','revenues','expenses','plan','features_count','check','extend_sets','bookingcount','bookinglabels'));
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
        
        // Library other highlights
        
        $extend_days_data = Hour::where('library_id', Auth::user()->id)->first();
        $extend_day = $extend_days_data ? $extend_days_data->extend_days : 0;
        $fiveDaysBefore = Carbon::now()->addDays(-5)->format('Y-m-d'); // Add this line for the expired logic
        $expired_in_five = $this->getLearnersByLibrary()->whereDate('learner_detail.plan_end_date', '=', $fiveDaysBefore)->count();
       

        $extended_seats = $this->getLearnersByLibrary()
        ->where('learner_detail.is_paid',1)
        ->where('learner_detail.status',1)
        ->where('learner_detail.plan_end_date', '<', date('Y-m-d'))
        ->whereRaw("DATE_ADD(learner_detail.plan_end_date, INTERVAL ? DAY) >= CURDATE()", [$extend_day])
        ->count();       
        $total_seats=Seat::count();
            
            
            // Check if date range is provided and filter by it
         if ($request->filled('date_range')) {
                // Split date range into start and end dates
                [$startDate, $endDate] = explode(' to ', $request->date_range);
            
             $total_booking = LearnerDetail::where('is_paid', 1)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('plan_start_date', [$startDate, $endDate])
                        ->orWhereBetween('plan_end_date', [$startDate, $endDate]);
                })
                ->count();
            $active_booking=LearnerDetail::where('is_paid', 1)->where('status',1)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('plan_start_date', [$startDate, $endDate])
                    ->orWhereBetween('plan_end_date', [$startDate, $endDate]);
            })
            ->count();
            $online_paid = LearnerDetail::where('is_paid', 1)
                ->where('payment_mode', 1)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('plan_start_date', [$startDate, $endDate])
                        ->orWhereBetween('plan_end_date', [$startDate, $endDate]);
                })
                ->count();
        
            $offline_paid = LearnerDetail::where('is_paid', 1)
                ->where('payment_mode', 2)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('plan_start_date', [$startDate, $endDate])
                        ->orWhereBetween('plan_end_date', [$startDate, $endDate]);
                })
                ->count();
        
            $other_paid = LearnerDetail::where('is_paid', 1)
                ->where('payment_mode', 3)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('plan_start_date', [$startDate, $endDate])
                        ->orWhereBetween('plan_end_date', [$startDate, $endDate]);
                })
                ->count();
            $swap_seat=DB::table('learner_operations_log')->where('library_id', Auth::user()->id)->where('operation','=','swapseat')->whereBetween('created_at', [$startDate, $endDate])->count();
            $learnerUpgrade=DB::table('learner_operations_log')->where('library_id', Auth::user()->id)->where('operation','=','learnerUpgrade')->whereBetween('created_at', [$startDate, $endDate])->count();
            $reactive = DB::table('learner_operations_log')
            ->where('library_id', Auth::user()->id)
            ->where('operation', '=', 'reactive')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('learner_id', 'created_at')
            ->count();
        
            $expired_seats = LearnerDetail::where('status', 0)
                ->where('is_paid', 1)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('plan_start_date', [$startDate, $endDate])
                        ->orWhereBetween('plan_end_date', [$startDate, $endDate]);
                })
                ->count();
        
            // Similar adjustments for plan-wise booking and revenue, with conditions grouped properly
            $plan_wise_booking = LearnerDetail::where('is_paid', 1)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('plan_start_date', [$startDate, $endDate])
                        ->orWhereBetween('plan_end_date', [$startDate, $endDate]);
                })
                ->groupBy('plan_type_id')
                ->selectRaw('COUNT(id) as booking, plan_type_id')
                ->with('planType')
                ->get();
        
            $planTypeWiseRevenue = LearnerDetail::where('is_paid', 1)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('plan_start_date', [$startDate, $endDate])
                        ->orWhereBetween('plan_end_date', [$startDate, $endDate]);
                })
                ->groupBy('plan_type_id')
                ->selectRaw('SUM(plan_price_id) as revenue, plan_type_id')
                ->with('planType')
                ->get();
            $booked_seats=LearnerDetail::where('is_paid', 1)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('plan_start_date', [$startDate, $endDate])
                    ->orWhereBetween('plan_end_date', [$startDate, $endDate]);
            }) ->groupBy('seat_id')->count(); 
            $close_seat =DB::table('learner_operations_log')->where('library_id', Auth::user()->id)->where('operation','=','closeSeat')->whereBetween('created_at', [$startDate, $endDate])->count();

        } else {
            // Filter by year and/or month if date range is not provided
            $query = LearnerDetail::where('is_paid', 1);

            if ($request->filled('year') && !$request->filled('month')) {
                // If only the year is provided
                $query->where(function ($query) use ($request) {
                    $query->whereYear('plan_start_date', $request->year)
                        ->orWhereYear('plan_end_date', $request->year);
                });
            } elseif ($request->filled('year') && $request->filled('month')) {
                // If both year and month are provided
                $query->where(function ($query) use ($request) {
                    $query->whereYear('plan_start_date', $request->year)
                        ->whereMonth('plan_start_date', $request->month)
                        ->orWhere(function ($query) use ($request) {
                            $query->whereYear('plan_end_date', $request->year)
                                    ->whereMonth('plan_end_date', $request->month);
                        });
                });
            }

            $total_booking = $query->count();
            $active_booking=$query->where('status',1)->count();
            
        
            $online_paid = LearnerDetail::where('is_paid', 1)
            ->when($request->filled('year') && !$request->filled('month'), function ($query) use ($request) {
                // Filter by year only
                return $query->where(function ($query) use ($request) {
                    $query->whereYear('learner_detail.plan_start_date', $request->year)
                        ->orWhereYear('learner_detail.plan_end_date', $request->year);
                });
            })
            ->when($request->filled('year') && $request->filled('month'), function ($query) use ($request) {
                // Filter by both year and month
                return $query->where(function ($query) use ($request) {
                    $query->whereYear('learner_detail.plan_start_date', $request->year)
                        ->whereMonth('learner_detail.plan_start_date', $request->month)
                        ->orWhere(function ($query) use ($request) {
                            $query->whereYear('learner_detail.plan_end_date', $request->year)
                                    ->whereMonth('learner_detail.plan_end_date', $request->month);
                        });
                });
            })
            ->where('learner_detail.payment_mode', 1)
            ->count();

            $offline_paid = LearnerDetail::where('is_paid', 1)
                ->when($request->filled('year') && !$request->filled('month'), function ($query) use ($request) {
                    return $query->where(function ($query) use ($request) {
                        $query->whereYear('learner_detail.plan_start_date', $request->year)
                            ->orWhereYear('learner_detail.plan_end_date', $request->year);
                    });
                })
                ->when($request->filled('year') && $request->filled('month'), function ($query) use ($request) {
                    return $query->where(function ($query) use ($request) {
                        $query->whereYear('learner_detail.plan_start_date', $request->year)
                            ->whereMonth('learner_detail.plan_start_date', $request->month)
                            ->orWhere(function ($query) use ($request) {
                                $query->whereYear('learner_detail.plan_end_date', $request->year)
                                        ->whereMonth('learner_detail.plan_end_date', $request->month);
                            });
                    });
                })
                ->where('learner_detail.payment_mode', 2)
                ->count();

            $other_paid = LearnerDetail::where('is_paid', 1)
                ->when($request->filled('year') && !$request->filled('month'), function ($query) use ($request) {
                    return $query->where(function ($query) use ($request) {
                        $query->whereYear('learner_detail.plan_start_date', $request->year)
                            ->orWhereYear('learner_detail.plan_end_date', $request->year);
                    });
                })
                ->when($request->filled('year') && $request->filled('month'), function ($query) use ($request) {
                    return $query->where(function ($query) use ($request) {
                        $query->whereYear('learner_detail.plan_start_date', $request->year)
                            ->whereMonth('learner_detail.plan_start_date', $request->month)
                            ->orWhere(function ($query) use ($request) {
                                $query->whereYear('learner_detail.plan_end_date', $request->year)
                                        ->whereMonth('learner_detail.plan_end_date', $request->month);
                            });
                    });
                })
                ->where('learner_detail.payment_mode', 3)
                ->count();

            $swap_seat = DB::table('learner_operations_log')
            ->where('library_id', Auth::user()->id)
            ->where('operation', 'swapseat')
            ->when($request->filled('year') && !$request->filled('month'), function ($query) use ($request) {
                return $query->whereYear('created_at', $request->year);
            })
            ->when($request->filled('year') && $request->filled('month'), function ($query) use ($request) {
                return $query->whereYear('created_at', $request->year)
                             ->whereMonth('created_at', $request->month);
            })
            ->count();
    
            $learnerUpgrade = DB::table('learner_operations_log')
                ->where('library_id', Auth::user()->id)
                ->where('operation', 'learnerUpgrade')
                ->when($request->filled('year') && !$request->filled('month'), function ($query) use ($request) {
                    return $query->whereYear('created_at', $request->year);
                })
                ->when($request->filled('year') && $request->filled('month'), function ($query) use ($request) {
                    return $query->whereYear('created_at', $request->year)
                                ->whereMonth('created_at', $request->month);
                })
                ->count();
        
            $reactive = DB::table('learner_operations_log')
                ->where('library_id', Auth::user()->id)
                ->where('operation', 'reactive')
                ->when($request->filled('year') && !$request->filled('month'), function ($query) use ($request) {
                    return $query->whereYear('created_at', $request->year);
                })
                ->when($request->filled('year') && $request->filled('month'), function ($query) use ($request) {
                    return $query->whereYear('created_at', $request->year)
                                ->whereMonth('created_at', $request->month);
                })
                ->groupBy('learner_id', 'created_at')
                ->count();
            $close_seat = DB::table('learner_operations_log')
            ->where('library_id', Auth::user()->id)
            ->where('operation', 'closeSeat')
            ->when($request->filled('year') && !$request->filled('month'), function ($query) use ($request) {
                return $query->whereYear('created_at', $request->year);
            })
            ->when($request->filled('year') && $request->filled('month'), function ($query) use ($request) {
                return $query->whereYear('created_at', $request->year)
                                ->whereMonth('created_at', $request->month);
            })
            ->count();
            $expired_query = LearnerDetail::where('status', 0)->where('is_paid', 1);
            
            if ($request->filled('year') && !$request->filled('month')) {
                // If only the year is provided
                $expired_query->where(function ($expired_query) use ($request) {
                    $expired_query->whereYear('plan_end_date', $request->year);
                       
                });
            } elseif ($request->filled('year') && $request->filled('month')) {
                // If both year and month are provided
                $expired_query->where(function ($expired_query) use ($request) {
                    $expired_query->whereYear('plan_end_date', $request->year)
                        ->whereMonth('plan_end_date', $request->month);
                       
                });
            }
            $expired_seats=$expired_query->count();
            // // For graph
            $plan_wise_booking = LearnerDetail::where('is_paid', 1)
            ->when($request->filled('year') && !$request->filled('month'), function ($query) use ($request) {
                // Filter by year only
                return $query->where(function ($query) use ($request) {
                    $query->whereYear('learner_detail.plan_start_date', $request->year)
                        ->orWhereYear('learner_detail.plan_end_date', $request->year);
                });
            })
            ->when($request->filled('year') && $request->filled('month'), function ($query) use ($request) {
               
                return $query->where(function ($query) use ($request) {
                    $query->whereYear('learner_detail.plan_start_date', $request->year)
                        ->whereMonth('learner_detail.plan_start_date', $request->month)
                        ->orWhere(function ($query) use ($request) {
                            $query->whereYear('learner_detail.plan_end_date', $request->year)
                                    ->whereMonth('learner_detail.plan_end_date', $request->month);
                        });
                });
            })
            
            ->groupBy('plan_type_id')
            ->selectRaw('COUNT(id) as booking, plan_type_id')
            ->with('planType') 
            ->get();

              //plantype wise revenue
            $planTypeWiseRevenue = LearnerDetail::where('is_paid', 1)
            ->when($request->filled('year') && !$request->filled('month'), function ($query) use ($request) {
                
                return $query->where(function ($query) use ($request) {
                    $query->whereYear('learner_detail.plan_start_date', $request->year)
                        ->orWhereYear('learner_detail.plan_end_date', $request->year);
                });
            })
            ->when($request->filled('year') && $request->filled('month'), function ($query) use ($request) {
                // Filter by both year and month
                return $query->where(function ($query) use ($request) {
                    $query->whereYear('learner_detail.plan_start_date', $request->year)
                        ->whereMonth('learner_detail.plan_start_date', $request->month)
                        ->orWhere(function ($query) use ($request) {
                            $query->whereYear('learner_detail.plan_end_date', $request->year)
                                    ->whereMonth('learner_detail.plan_end_date', $request->month);
                        });
                });
            })
            
            ->groupBy('plan_type_id')
            ->selectRaw('SUM(plan_price_id) as revenue, plan_type_id')
            ->with('planType')
            ->get();

            // first div
            $booked_seats_query = LearnerDetail::where('is_paid', 1)->where('status', 1);
           
            
            if ($request->filled('year') && !$request->filled('month')) {
                // Only the year is provided
                $year = $request->year;
                $booked_seats_query->where(function($query) use ($year) {
                    $query->whereYear('plan_start_date', '<=', $year)
                        ->whereYear('plan_end_date', '>=', $year);
                });
            } elseif ($request->filled('year') && $request->filled('month')) {
                // Both year and month are provided
                $year = $request->year;
                $month = $request->month;
                $startOfMonth = "$year-$month-01";
                $endOfMonth = date("Y-m-t", strtotime($startOfMonth)); // Last day of the month
            
                $booked_seats_query->where(function($query) use ($startOfMonth, $endOfMonth) {
                    $query->where('plan_start_date', '<=', $endOfMonth)
                        ->where('plan_end_date', '>=', $startOfMonth);
                });
            }
            
            // Get the count of unique seat_id
            $booked_seats = $booked_seats_query->distinct('seat_id')->count('seat_id');
        

        }
       
        
        // Prepare data for response for graph
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
      

        // Prepare labels and data for revenue
        $revenueLabels = $planTypeWiseRevenue->pluck('planType.name')->toArray();
        $revenueData = $planTypeWiseRevenue->pluck('revenue')->toArray();

       

       
        
        $availble_seats=$total_seats-$booked_seats; 

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
                'active_booking' => $active_booking,
                //first div
                'total_seat' => $total_seats,
                'booked_seat' => $booked_seats,
                'available_seat'=>$availble_seats,
                'close_seat'=>$close_seat
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

    public function listView(Request $request)
    {
     
        $type = $request->get('type');
        $year = $request->get('year');
        $month = $request->get('month');
        $dateRange = $request->get('date_range');
        $seats = [];
        $extend_days=Hour::select('extend_days')->first();
        if($extend_days){
            $extendDay=$extend_days->extend_days;
        }else{
            $extendDay=0;
        }
        switch ($type) {
            case 'booked':
                $bookedSeats = Seat::where('is_available', '!=', 1)->where('total_hours', '!=', 0)->get();
                foreach ($bookedSeats as $bookedSeat) {
                 
                    $learnerData = $this->getAllLearnersByLibrary()
                                        ->where('learners.seat_no', $bookedSeat->seat_no)
                                        ->where('learners.status', 1)
                                        ->get();
                 
                    foreach ($learnerData as $learner) {
                        $seats[] = ['learner' => $learner];
                    }
                }
                break;
        
            case 'expired':
                $learnerData = Learner::where('library_id', auth()->user()->id) 
                    ->whereHas('learnerDetails', function ($query) {
                        $query->whereDate('plan_end_date', '<', now());
                    })
                    ->with([
                        'learnerDetails' => function($query) {
                            $query->with(['seat', 'plan', 'planType']);
                        }
                    ])
                    
                    ->get();
            
                foreach ($learnerData as $learner) {
                    $seats[] = ['learner' => $learner];
                }
                break;
                
            default:
                
                $allSeats = Seat::all();
                foreach ($allSeats as $seat) {
                    $learnerData = $this->getAllLearnersByLibrary()
                                        ->where('learners.seat_no', $seat->seat_no)
                                        ->get();
                    foreach ($learnerData as $learner) {
                        $seats[] = ['learner' => $learner ];
                    }
                }
                break;
        }

        return view('learner.list-view', compact('extendDay','seats', 'type'));
    }

    public function viewSeats(Request $request)
    {
        $type = $request->get('type');
        $year = $request->get('year');
        $month = $request->get('month');
        $dateRange = $request->get('date_range');
        
        if ($request->filled('date_range')) {
            // Split date range into start and end dates
            [$startDate, $endDate] = explode(' to ', $request->date_range);
            
            // Set up a base query for LearnerDetail
            $query = LearnerDetail::with(['plan', 'planType', 'seat', 'learner']) // Eager load relationships
                ->where('is_paid', 1)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('plan_start_date', [$startDate, $endDate])
                          ->orWhereBetween('plan_end_date', [$startDate, $endDate]);
                });
        
            // Apply filtering based on `$type`
            switch ($type) {
                case 'total_booking':
                    $result = $query->get();
                    break;
        
                case 'active_booking':
                    $result = $query->where('status', 1)->get();
                    break;
        
                case 'online_paid':
                    $result = $query->where('payment_mode', 1)->get();
                    break;
        
                case 'offline_paid':
                    $result = $query->where('payment_mode', 2)->get();
                    break;
        
                case 'other_paid':
                    $result = $query->where('payment_mode', 3)->get();
                    break;
        
                case 'swap_seat':
                    $result = DB::table('learner_operations_log')
                        ->where('library_id', Auth::user()->id)
                        ->where('operation', 'swapseat')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->get();
                    break;
        
                case 'learnerUpgrade':
                    $result = DB::table('learner_operations_log')
                        ->where('library_id', Auth::user()->id)
                        ->where('operation', 'learnerUpgrade')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->get();
                    break;
        
                case 'expired_seats':
                    $result = LearnerDetail::with(['plan', 'planType', 'seat', 'learner'])
                        ->where('status', 0)
                        ->where('is_paid', 1)
                        ->where(function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('plan_start_date', [$startDate, $endDate])
                                  ->orWhereBetween('plan_end_date', [$startDate, $endDate]);
                        })
                        ->get();
                    break;
        
                default:
                    $result = $query->get(); // Fallback if no specific `$type` is provided
                    break;
            }
        
        } else {
            $query = LearnerDetail::with(['plan', 'planType', 'seat', 'learner'])
            ->where('is_paid', 1);
        
        if ($request->filled('year') && !$request->filled('month')) {
            // Grouping conditions for clarity and correctness
            $query->where(function ($query) use ($request) {
                $query->whereYear('plan_start_date', $request->year)
                      ->orWhereYear('plan_end_date', $request->year);
            });
        } elseif ($request->filled('year') && $request->filled('month')) {
            // Grouping conditions for start date and end date
            $query->where(function ($query) use ($request) {
                $query->whereYear('plan_start_date', $request->year)
                      ->whereMonth('plan_start_date', $request->month)
                      ->orWhere(function ($query) use ($request) {
                          $query->whereYear('plan_end_date', $request->year)
                                ->whereMonth('plan_end_date', $request->month);
                      });
            });
        }
        
        // Execute the query and dump the results
        
        
           
            // Apply `$type` filter
            switch ($type) {
                case 'total_booking':
                    $result = $query->get();
                    break;
        
                case 'active_booking':
                    $result = $query->where('status', 1)->get();
                    break;
        
                case 'online_paid':
                    $result = $query->where('payment_mode', 1)->get();
                    break;
        
                // Continue adding cases for each `$type`...
        
                default:
                    $result = $query->get();
                    break;
            }
        }
        $extend_days=Hour::select('extend_days')->first();
        if($extend_days){
            $extendDay=$extend_days->extend_days;
        }else{
            $extendDay=0;
        }
       
        return view('learner.list-view', compact('result', 'type','extendDay'));
        
    }

    
    
    

}
