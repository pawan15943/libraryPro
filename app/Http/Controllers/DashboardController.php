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
use App\Models\LearnerOperationsLog;
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
            ->where('learner_detail.status', 1)
            ->whereNotExists(function ($query) use ($fiveDaysbetween) {
                $query->select(DB::raw(1))
                    ->from('learner_detail as ld')
                    ->whereColumn('ld.seat_id', 'learner_detail.seat_id')
                    ->where('ld.plan_end_date', '>', $fiveDaysbetween->format('Y-m-d'));
            })
            ->with('planType')
            ->get();
            $extend_sets = $this->getLearnersByLibrary()
            ->where('learner_detail.is_paid', 1) // Only paid learners
            ->where('learner_detail.status', 1)  // Only active learners
            ->where('learner_detail.plan_end_date', '<', $today->format('Y-m-d')) // plan_end_date is before today
            ->whereRaw("DATE_ADD(learner_detail.plan_end_date, INTERVAL ? DAY) >= CURDATE()", [$extend_day]) // Extended date is today or in the future
            ->whereNotExists(function ($query) use ($fiveDaysbetween) {
                $query->select(DB::raw(1))
                    ->from('learner_detail as ld')
                    ->whereColumn('ld.seat_id', 'learner_detail.seat_id')
                    ->where('ld.plan_end_date', '>', $fiveDaysbetween->format('Y-m-d'));
            })
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
            $uniqueDates=LearnerDetail::selectRaw('YEAR(plan_start_date) as year, MONTH(plan_start_date) as month')
            ->distinct()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'asc')
            ->get();
            $dynamicyears = $uniqueDates->pluck('year')->unique();
            $dynamicmonths = $uniqueDates->pluck('month')->unique();
            if($is_expire){
                return redirect()->route('library.myplan');
            }elseif($iscomp){
                return view('dashboard.admin',compact('plans','available_seats','renewSeats','plan','features_count','check','extend_sets','bookingcount','bookinglabels','dynamicyears','dynamicmonths'));
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
         $today = Carbon::now()->format('Y-m-d');
        $extend_days_data = Hour::where('library_id', Auth::user()->id)->first();
        $extend_day = $extend_days_data ? $extend_days_data->extend_days : 0;
       
        $fiveDaysLater = Carbon::now()->addDays(5)->format('Y-m-d');

        $expired_in_five  = $this->getAllLearnersByLibrary()
            ->whereHas('learnerDetails', function($query) use ($today, $fiveDaysLater) {
                $query->whereBetween('plan_end_date', [$today, $fiveDaysLater]);
            })->count();
       

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
            $renew = DB::table('learner_operations_log')
            ->where('library_id', Auth::user()->id)
            ->where('operation', '=', 'renewSeat')
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
            $delete_seat=DB::table('learner_operations_log')
            ->where('library_id', Auth::user()->id)
            ->where('operation', 'deleteSeat')
            ->whereBetween('created_at', [$startDate, $endDate])->count();
        } else {
            
                $query = LearnerDetail::where('is_paid', 1);
              
                if ($request->filled('year') && !$request->filled('month')) {
                    // If only the year is provided
                    $year = $request->year;
                    $query->where(function ($q) use ($year) {
                        $q->whereYear('plan_start_date', '<=', $year)
                        ->whereYear('plan_end_date', '>=', $year);
                    });
                } elseif ($request->filled('year') && $request->filled('month')) {
                    // If both year and month are provided
                    $year = $request->year;
                    $month = $request->month;
                    $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
                    $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();
                
                    $query->where(function ($q) use ($startOfMonth, $endOfMonth) {
                        $q->where('plan_start_date', '<=', $endOfMonth)
                        ->where('plan_end_date', '>=', $startOfMonth);
                    });
                }
                
                $total_booking = $query->count();
                $expired_query = LearnerDetail::where('is_paid', 1)->where('status',0);
            
                if ($request->filled('year') && !$request->filled('month')) {
                    $expired_query->where(function ($expired_query) use ($request) {
                        $expired_query->whereYear('plan_end_date', $request->year);
                            
                    });
                } elseif ($request->filled('year') && $request->filled('month')) {
                    
                    $expired_query->where(function ($expired_query) use ($request) {
                        $expired_query->whereYear('plan_end_date', $request->year)
                            ->whereMonth('plan_end_date', $request->month);
                            
                    });
                }
                $month_all_expired=$expired_query->count();
                
                $expired_seats=$month_all_expired;
                $active_booking=$total_booking-$month_all_expired;

                $month_total_active_booking = LearnerDetail::where('is_paid', 1);

                if ($request->filled('year') && !$request->filled('month')) {
                    $month_total_active_booking->whereYear('join_date', $request->year);
                } elseif ($request->filled('year') && $request->filled('month')) {
                    $month_total_active_booking->whereYear('join_date', $request->year)
                        ->whereMonth('join_date', $request->month);
                }
                
                $month_total_active_book = $month_total_active_booking->count();
                
                $thismonth_total_booking = LearnerDetail::query();
                
                if ($request->filled('year') && !$request->filled('month')) {
                    $thismonth_total_booking->where(function ($query) use ($request) {
                        $query->whereYear('join_date', $request->year)
                              ->orWhereYear('plan_end_date', $request->year);
                    });
                } elseif ($request->filled('year') && $request->filled('month')) {
                    $thismonth_total_booking->where(function ($query) use ($request) {
                        $query->where(function ($subQuery) use ($request) {
                            $subQuery->whereYear('join_date', $request->year)
                                     ->whereMonth('join_date', $request->month);
                        })
                        ->orWhere(function ($subQuery) use ($request) {
                            $subQuery->whereYear('plan_end_date', $request->year)
                                     ->whereMonth('plan_end_date', $request->month);
                        });
                    });
                }
                
                $thismonth_total_book = $thismonth_total_booking->count();
                
        
            // Base query with year and month filters applied for online,offline,paylater seats
            $data = LearnerDetail::where('is_paid', 1)
            ->when($request->filled('year') && !$request->filled('month'), function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    $query->whereYear('plan_start_date', $request->year)
                        ->orWhereYear('plan_end_date', $request->year);
                });
            })
            ->when($request->filled('year') && $request->filled('month'), function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    $query->whereYear('plan_start_date', $request->year)
                        ->whereMonth('plan_start_date', $request->month)
                        ->orWhere(function ($query) use ($request) {
                            $query->whereYear('plan_end_date', $request->year)
                                    ->whereMonth('plan_end_date', $request->month);
                        });
                });
            });

            // Clone the base query for each payment mode count
            $online_paid = (clone $data)->where('payment_mode', 1)->count();
            $offline_paid = (clone $data)->where('payment_mode', 2)->count();
            $other_paid = (clone $data)->where('payment_mode', 3)->count();


            // Define the base query for learner_operations_log with common filters applied
            $baseQuery = DB::table('learner_operations_log')
            ->where('library_id', Auth::user()->id)
            ->when($request->filled('year') && !$request->filled('month'), function ($query) use ($request) {
                return $query->whereYear('created_at', $request->year);
            })
            ->when($request->filled('year') && $request->filled('month'), function ($query) use ($request) {
                return $query->whereYear('created_at', $request->year)
                            ->whereMonth('created_at', $request->month);
            });

            // Clone the base query and apply specific filters for each operation
            $swap_seat = (clone $baseQuery)
            ->where('operation', 'swapseat')
            ->count();

            $learnerUpgrade = (clone $baseQuery)
            ->where('operation', 'learnerUpgrade')
            ->count();

            $reactive = (clone $baseQuery)
            ->where('operation', 'reactive')
            ->groupBy('learner_id', 'created_at')
            ->count();

            $renew = (clone $baseQuery)
            ->where('operation', 'renewSeat')
            ->groupBy('learner_id', 'created_at')
            ->count();

            $close_seat = (clone $baseQuery)
            ->where('operation', 'closeSeat')
            ->count();

            $delete_seat = (clone $baseQuery)
            ->where('operation', 'deleteSeat')
            ->count();

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
            $booked_seats_query = LearnerDetail::where('is_paid', 1);
           
            
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
            // revenue expense div
          
            
            $revenue_query=LearnerDetail::withoutGlobalScopes()
            ->leftJoin('plans', 'plans.id', '=', 'learner_detail.plan_id') // Join with plans table on plan_id
            ->where('learner_detail.is_paid', 1);
           
            if ($request->filled('year') && !$request->filled('month')) {
                
                $revenue_query->where(function ($revenue_query) use ($request) {
                    $revenue_query->whereYear('join_date', $request->year);
                    
                });
            } elseif ($request->filled('year') && $request->filled('month')) {
        
                $revenue_query->where(function ($revenue_query) use ($request) {
                    $revenue_query->whereYear('join_date', $request->year)
                        ->whereMonth('join_date', $request->month);
                    
                });
            }
            
            $revenues =$revenue_query->selectRaw('YEAR(join_date) as year, MONTH(join_date) as month, SUM(plan_price_id) as total_revenue, SUM(learner_detail.plan_price_id / plans.plan_id) as monthly_revenue')->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')->get()
            ->keyBy('month');
           
            $expense_query=DB::table('monthly_expense')
            ->where('library_id', Auth::user()->id);
            
            if ($request->filled('year') && !$request->filled('month')) {
                    
                    $expense_query->where(function ($expense_query) use ($request) {
                        $expense_query->whereYear('year', $request->year);
                        
                    });
                } elseif ($request->filled('year') && $request->filled('month')) {
            
                    $expense_query->where(function ($expense_query) use ($request) {
                        $expense_query->whereYear('year', $request->year)
                            ->whereMonth('month', $request->month);
                        
                    });
                }

                $expenses = $expense_query->selectRaw('year, month, SUM(amount) as total_expense')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'asc')
                    ->orderBy('month', 'asc')
                    ->get();

                $revenu_expense = [];
                foreach ($revenues as $month => $revenue) {
                    $monthName = Carbon::createFromDate($revenue['year'], $revenue['month'])->format('F');
                    $expense = $expenses->get($month);
                    $totalExpense = $expense ? $expense->total_expense : 0;
                    $totalRevenue = $revenue->total_revenue;
                    
                    $monthlyRevenue=number_format($revenue->monthly_revenue,2);
                    $monthlyRevenue = (float) str_replace(',', '', $monthlyRevenue);
                   
                    $netProfit = $monthlyRevenue - $totalExpense;
                 
                    $revenu_expense[] = [
                        'month' => $monthName,
                        'totalRevenue' => $totalRevenue,
                        'totalExpense' => $totalExpense,
                        'netProfit' => $netProfit,
                        'year'=>$request->year,
                        'monthlyRevenue' =>$monthlyRevenue,
                    ];
                }
           
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
               
                'delete_seat' => $delete_seat,
                'renew_seat' => $renew,
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
                'close_seat'=>$close_seat,
                'month_total_active_book'=>$month_total_active_book,
                'thismonth_total_book'=>$thismonth_total_book,
                'month_all_expired'=>$month_all_expired,
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

            'revenu_expense' => $revenu_expense,
        ]);
    }

    // public function listView(Request $request)
    // {
     
    //     $type = $request->get('type');
    //     $year = $request->get('year');
    //     $month = $request->get('month');
    //     $dateRange = $request->get('date_range');
    //     $seats = [];
    //     $extend_days=Hour::select('extend_days')->first();
    //     if($extend_days){
    //         $extendDay=$extend_days->extend_days;
    //     }else{
    //         $extendDay=0;
    //     }
    //     switch ($type) {
    //         case 'booked':
    //             $bookedSeats = Seat::where('is_available', '!=', 1)->where('total_hours', '!=', 0)->get();
    //             foreach ($bookedSeats as $bookedSeat) {
                 
    //                 $learnerData = $this->getAllLearnersByLibrary()
    //                                     ->where('learners.seat_no', $bookedSeat->seat_no)
    //                                     ->where('learners.status', 1)
    //                                     ->get();
                 
    //                 foreach ($learnerData as $learner) {
    //                     $seats[] = ['learner' => $learner];
    //                 }
    //             }
    //             break;
        
    //         case 'expired':
    //             $learnerData = Learner::where('library_id', auth()->user()->id) 
    //                 ->whereHas('learnerDetails', function ($query) {
    //                     $query->whereDate('plan_end_date', '<', now());
    //                 })
    //                 ->with([
    //                     'learnerDetails' => function($query) {
    //                         $query->with(['seat', 'plan', 'planType']);
    //                     }
    //                 ])
                    
    //                 ->get();
            
    //             foreach ($learnerData as $learner) {
    //                 $seats[] = ['learner' => $learner];
    //             }
    //             break;
                
    //         default:
                
    //             $allSeats = Seat::all();
    //             foreach ($allSeats as $seat) {
    //                 $learnerData = $this->getAllLearnersByLibrary()
    //                                     ->where('learners.seat_no', $seat->seat_no)
    //                                     ->get();
    //                 foreach ($learnerData as $learner) {
    //                     $seats[] = ['learner' => $learner ];
    //                 }
    //             }
    //             break;
    //     }

    //     return view('learner.list-view', compact('extendDay','seats', 'type'));
    // }

    public function viewSeats(Request $request)
    {
        $type = $request->get('type');
        $year = $request->get('year');
        $month = $request->get('month');
        $dateRange = $request->get('date_range');

        $extend_days_data = Hour::where('library_id', Auth::user()->id)->first();
        $extend_day = $extend_days_data ? $extend_days_data->extend_days : 0;
        
       
        $today = Carbon::now()->format('Y-m-d');
        $fiveDaysLater = Carbon::now()->addDays(5)->format('Y-m-d');
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
            $data = LearnerDetail::with(['plan', 'planType', 'seat', 'learner']) // Eager load relationships
            ->where('is_paid', 1)
            ->when($request->filled('year') && !$request->filled('month'), function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    $query->whereYear('plan_start_date', $request->year)
                        ->orWhereYear('plan_end_date', $request->year);
                });
            })
            ->when($request->filled('year') && $request->filled('month'), function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    $query->whereYear('plan_start_date', $request->year)
                        ->whereMonth('plan_start_date', $request->month)
                        ->orWhere(function ($query) use ($request) {
                            $query->whereYear('plan_end_date', $request->year)
                                    ->whereMonth('plan_end_date', $request->month);
                        });
                });
            });
            $baseQuery = LearnerOperationsLog::with(['learner.plan', 'learner.planType'])
            ->whereHas('learner', function ($query) {
                $query->where('library_id', Auth::user()->id);
            })
            ->when($request->filled('year') && !$request->filled('month'), function ($query) use ($request) {
                return $query->whereYear('created_at', $request->year);
            })
            ->when($request->filled('year') && $request->filled('month'), function ($query) use ($request) {
                return $query->whereYear('created_at', $request->year)
                            ->whereMonth('created_at', $request->month);
            });
            switch ($type) {
                case 'total_booking':
                    $query = LearnerDetail::with(['plan', 'planType', 'seat', 'learner']) // Eager load relationships
                    ->where('is_paid', 1);
              
                    if ($request->filled('year') && !$request->filled('month')) {
                        // If only the year is provided
                        $year = $request->year;
                        $query->where(function ($q) use ($year) {
                            $q->whereYear('plan_start_date', '<=', $year)
                            ->whereYear('plan_end_date', '>=', $year);
                        });
                    } elseif ($request->filled('year') && $request->filled('month')) {
                        // If both year and month are provided
                        $year = $request->year;
                        $month = $request->month;
                        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
                        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();
                    
                        $query->where(function ($q) use ($startOfMonth, $endOfMonth) {
                            $q->where('plan_start_date', '<=', $endOfMonth)
                            ->where('plan_end_date', '>=', $startOfMonth);
                        });
                    }
                    $result = $query->get();
                    break;

                case 'expire_booking_slot':
                    $expired_query = LearnerDetail::with(['plan', 'planType', 'seat', 'learner']) // Eager load relationships
                    ->where('is_paid', 1)->where('status',0);
            
                    if ($request->filled('year') && !$request->filled('month')) {
                        $expired_query->where(function ($expired_query) use ($request) {
                            $expired_query->whereYear('plan_end_date', $request->year);
                                
                        });
                    } elseif ($request->filled('year') && $request->filled('month')) {
                        
                        $expired_query->where(function ($expired_query) use ($request) {
                            $expired_query->whereYear('plan_end_date', $request->year)
                                ->whereMonth('plan_end_date', $request->month);
                                
                        });
                    }
                    $result = $expired_query->get();
                    break;
                case 'expired_seats':
                    $expired_query = LearnerDetail::with(['plan', 'planType', 'seat', 'learner']) // Eager load relationships
                    ->where('is_paid', 1);
            
                    if ($request->filled('year') && !$request->filled('month')) {
                        $expired_query->where(function ($expired_query) use ($request) {
                            $expired_query->whereYear('plan_end_date', $request->year);
                                
                        });
                    } elseif ($request->filled('year') && $request->filled('month')) {
                        
                        $expired_query->where(function ($expired_query) use ($request) {
                            $expired_query->whereYear('plan_end_date', $request->year)
                                ->whereMonth('plan_end_date', $request->month);
                                
                        });
                    }
                    $result = $expired_query->get();
                    break;
                case 'active_booking':
                    $total_booking = (clone $data)->get();
                    $expired_seats = (clone $data)
                        ->when($request->filled('year') && !$request->filled('month'), function ($query) use ($request) {
                            $query->whereYear('plan_end_date', $request->year);
                        })
                        ->when($request->filled('year') && $request->filled('month'), function ($query) use ($request) {
                            $query->whereYear('plan_end_date', $request->year)
                                  ->whereMonth('plan_end_date', $request->month);
                        })
                        ->get();
            
                    // Use the `diff` method to remove expired seats from total bookings
                    $active_booking = $total_booking->diff($expired_seats);
                    $result = $active_booking;
                    break;
                case 'booing_slot':
                    $month_total_active_booking =  LearnerDetail::with(['plan', 'planType', 'seat', 'learner']) 
                    ->where('is_paid', 1); 
                    if ($request->filled('year') && !$request->filled('month')) {
                
                        $month_total_active_booking->where(function ($month_total_active_booking) use ($request) {
                            $month_total_active_booking->whereYear('join_date', $request->year);
                            
                        });
                    } elseif ($request->filled('year') && $request->filled('month')) {
                
                        $month_total_active_booking->where(function ($month_total_active_booking) use ($request) {
                            $month_total_active_booking->whereYear('join_date', $request->year)
                                ->whereMonth('join_date', $request->month);
                            
                        });
                    }
                    $result =$month_total_active_booking->get();
                    break;
                case 'thisbooking_slot':
                    $thismonth_total_booking = LearnerDetail::with(['plan', 'planType', 'seat', 'learner']);

                    // Apply year and month filters based on join learner and expired learner
                    if ($request->filled('year') && !$request->filled('month')) {
                        $thismonth_total_booking->where(function ($query) use ($request) {
                            $query->whereYear('join_date', $request->year)
                                  ->orWhereYear('plan_end_date', $request->year);
                        });
                    } elseif ($request->filled('year') && $request->filled('month')) {
                        $thismonth_total_booking->where(function ($query) use ($request) {
                            $query->where(function ($subQuery) use ($request) {
                                $subQuery->whereYear('join_date', $request->year)
                                         ->whereMonth('join_date', $request->month);
                            })
                            ->orWhere(function ($subQuery) use ($request) {
                                $subQuery->whereYear('plan_end_date', $request->year)
                                         ->whereMonth('plan_end_date', $request->month);
                            });
                        });
                    }
                    
                    $result = $thismonth_total_booking->get();
                    
                    break;
                    
                case 'online_paid':
                    $result = (clone $data)->where('payment_mode', 1)->get();
                    break;

                case 'offline_paid':
                    $result = (clone $data)->where('payment_mode', 1)->get();
                    break;

                case 'other_paid':
                    $result = (clone $data)->where('payment_mode', 1)->get();
                    break;
                case 'expired_in_five':
                    $result = LearnerDetail::with(['plan', 'planType', 'seat', 'learner']) 
                    ->where('is_paid', 1)->whereBetween('plan_end_date', [$today, $fiveDaysLater])->get();
                    break;
                case 'extended_seat':
                    $result = LearnerDetail::with(['plan', 'planType', 'seat', 'learner']) 
                    ->where('is_paid', 1)->where('learner_detail.plan_end_date', '<', date('Y-m-d'))
                    ->whereRaw("DATE_ADD(learner_detail.plan_end_date, INTERVAL ? DAY) >= CURDATE()", [$extend_day])
                    ->get();
                    break;
                case 'swap_seat':
                    $result = (clone $baseQuery)->where('operation', 'swapseat')->get();
                    break;
                case 'learnerUpgrade':
                    $result = (clone $baseQuery)->where('operation', 'learnerUpgrade')->get();
                    break;
                case 'reactive_seat':
                    $result = (clone $baseQuery)->where('operation', 'reactive')->get();
                    break;
                case 'renew_seat':
                    $result = (clone $baseQuery)->where('operation', 'renewSeat')->get();
                    break;
                case 'close_seat':
                    $result = (clone $baseQuery)->where('operation', 'closeSeat')->get();
                    break;
                case 'delete_seat':
                    $result = (clone $baseQuery)->where('operation', 'deleteSeat')->get();
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
