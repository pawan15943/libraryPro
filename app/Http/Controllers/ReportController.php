<?php

namespace App\Http\Controllers;

use App\Models\CustomerDetail;
use App\Models\Customers;
use App\Models\Expense;
use App\Models\Hour;
use App\Models\Learner;
use App\Models\LearnerDetail;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Services\LearnerService;
use Carbon\Carbon;
use App\Traits\LearnerQueryTrait;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    use LearnerQueryTrait;
    protected $learnerService;
    public function __construct(LearnerService $learnerService)
    {
        $this->learnerService = $learnerService;
    }
    public function monthlyReport()
    {
        $monthlyRevenues = LearnerDetail::withoutGlobalScopes()
    ->leftJoin('plans', 'plans.id', '=', 'learner_detail.plan_id')
    ->where('learner_detail.is_paid', 1)
    ->where('learner_detail.library_id', Auth::user()->id)
    ->selectRaw('
        YEAR(join_date) as year,
        MONTH(join_date) as month,
        SUM(plan_price_id) as total_revenue,
        SUM(plan_price_id / plans.plan_id) as monthly_revenue
    ')
        ->groupBy('year', 'month')
        ->get();

    // Initialize an array to hold the final report data
    $reportData = [];

    foreach ($monthlyRevenues as $monthlyRevenue) {
        // Fetch corresponding monthly expenses with MIN(id)
        $monthlyExpenses = DB::table('monthly_expense')->where('library_id', Auth::user()->id)
            ->selectRaw('MIN(id) as expense_id, year, month, SUM(amount) as total_expenses')
            ->where('year', $monthlyRevenue->year)
            ->where('month', $monthlyRevenue->month)
            ->groupBy('year', 'month')
            ->first();

        // Prepare the report data
        $reportData[] = [
            'year' => $monthlyRevenue->year,
            'month' => $monthlyRevenue->month,
            'total_revenue' => $monthlyRevenue->total_revenue,
            'id' => $monthlyExpenses->expense_id ?? null, 
            'total_expenses' => $monthlyExpenses->total_expenses ?? 0, 
            'monthly_revenue' => $monthlyRevenue->monthly_revenue, 
            
        ];
    }


        return view('report.monthly_report', ['reportData' => $reportData]);
    }


    public function monthlyExpenseCreate($year, $month)
    {
        $monthlyExpenses = DB::table('monthly_expense')->leftJoin('expenses','monthly_expense.expense_id','=','expenses.id')->where('monthly_expense.library_id', Auth::user()->id)
            ->where('monthly_expense.year', $year)
            ->where('monthly_expense.month', $month)
            ->get();
       
        $library_revenue =  LearnerDetail::whereMonth('join_date', date('m'))
            ->whereYear('join_date', date('Y'))
            ->sum('plan_price_id');
        $expenses = Expense::get();
        $revenue_expense = DB::table('monthly_expense')
            ->join('expenses', 'monthly_expense.expense_id', '=', 'expenses.id')
            ->where('monthly_expense.library_id', Auth::id()) // Optimized to use Auth::id()
            ->where('expenses.library_id', Auth::id())
            ->select('monthly_expense.*', 'expenses.name as expense_name')
            ->get();

        return view('report.expense', compact('library_revenue', 'expenses', 'monthlyExpenses', 'year', 'month', 'revenue_expense'));
    }
    public function monthlyExpenseStore(Request $request, $id = null)
    {
        $validatedData = $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer',
            'expense_id' => 'required|array|min:1', // Ensure at least one expense ID is provided
            'expense_id.*' => 'required|integer|exists:expenses,id', // Validate each expense ID element
            'amount' => 'required|array|min:1', // Ensure at least one amount is provided
            'amount.*' => 'required|numeric|min:0', // Validate each amount element
        ]);

        $year = $validatedData['year'];
        $month = $validatedData['month'];
        // delete request id's
        $existingExpenseIds = DB::table('monthly_expense')->where('library_id', Auth::user()->id)
            ->where('year', $year)
            ->where('month', $month)
            ->pluck('expense_id')
            ->toArray();

        $expenseIdsToDelete = array_diff($existingExpenseIds, $validatedData['expense_id']);
        if (!empty($expenseIdsToDelete)) {
            DB::table('monthly_expense')
                ->where('year', $year)
                ->where('month', $month)
                ->whereIn('expense_id', $expenseIdsToDelete)
                ->delete();
        }
        foreach ($validatedData['expense_id'] as $index => $expenseId) {
            $amount = $validatedData['amount'][$index];

            DB::table('monthly_expense')->updateOrInsert(
                [
                    'library_id' => Auth::user()->id, // Include library_id
                    'year' => $year,
                    'month' => $month,
                    'expense_id' => $expenseId,
                ],
                [
                    'amount' => $amount,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        return redirect()->route('report.monthly')->with('success', 'Expenses recorded successfully!');
    }

    public function pendingPayment(Request $request){
        $plans = $this->learnerService->getPlans();
        $plan_type =$this->learnerService->getPlanTypes();
        $uniqueDates=LearnerDetail::selectRaw('YEAR(plan_start_date) as year, MONTH(plan_start_date) as month')
            ->distinct()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'asc')
            ->get();
        $dynamicyears = $uniqueDates->pluck('year')->unique();
        $dynamicmonths = $uniqueDates->pluck('month')->unique();
        $filters = [
            'year' => $request->get('year'),
            'month' => $request->get('month'),
            'plan_id' => $request->get('plan_id'),
            'plan_type'  => $request->get('plan_type'),
            'search'  => $request->get('search'),
        ];
        $today = Carbon::now()->format('Y-m-d');
        $fiveDaysBefore = Carbon::now()->subDays(5)->format('Y-m-d');
       
        $query  = LearnerDetail::with(['seat', 'plan', 'planType','learner'])->whereBetween('plan_end_date', [$fiveDaysBefore,$today]);
      
       
        $learners = $this->fetchlearnerData( $filters,$query);
    
        return view('report.pending_payment', compact('plans', 'plan_type', 'dynamicyears', 'dynamicmonths', 'learners'));

    }

   
    
    public function learnerReport(Request $request){
     

        $filters = [
            'year' => $request->get('year'),
            'month' => $request->get('month'),
            'is_paid' => $request->get('is_paid'),
            'status'  => $request->get('status'),
            'search'  => $request->get('search'),
        ];
       
        $query = LearnerDetail::with(['seat', 'plan', 'planType','learner']);
         
        $learners = $this->fetchlearnerData( $filters,$query);
       // Get the unique years and month
       $minStartDate =LearnerDetail::min('plan_start_date');
       $maxEndDate =LearnerDetail::max('plan_end_date');
   
       $start = Carbon::parse($minStartDate)->startOfMonth();
       $end = Carbon::parse($maxEndDate)->startOfMonth();
   
       $months = [];
       while ($start <= $end) {
           $year = $start->year;
           $month = $start->format('F');
           $months[$year][$start->month] = $month;
           $start->addMonth();
       }
        return view('report.learner_report',compact('learners', 'months'));
    }

    public function upcomingPayment(){
        $today = Carbon::now()->format('Y-m-d');
        $fiveDaysLater = Carbon::now()->addDays(5)->format('Y-m-d');
       
        $learners = $this->getAllLearnersByLibrary()
            ->whereHas('learnerDetails', function($query) use ($today, $fiveDaysLater) {
                $query->whereBetween('plan_end_date', [$today, $fiveDaysLater]);
            })->get();

        return view('report.upcoming_payment',compact('learners'));
    }

    public function expiredLearner(Request $request){
       
        $uniqueDates=LearnerDetail::selectRaw('YEAR(plan_start_date) as year, MONTH(plan_start_date) as month')
            ->distinct()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'asc')
            ->get();
        $dynamicyears = $uniqueDates->pluck('year')->unique();
        $dynamicmonths = $uniqueDates->pluck('month')->unique();
        $filters = [
            'expiredyear' => $request->get('expiredyear'),
            'expiredmonth' => $request->get('expiredmonth'),
        ];
        $query = LearnerDetail::with(['seat', 'plan', 'planType','learner'])->where('status', 0)
        ->whereHas('learner', function($query) {
            $query->where('status', 0);
        });
       
        $learners = $this->fetchlearnerData( $filters,$query);
  
        return view('report.expired_learner', compact('dynamicyears', 'dynamicmonths', 'learners'));

    }

    public function fetchlearnerData( $filters,$query){
      
        Log::info('Filters applied:', $filters);
        if (!empty($filters)) {
            $year = $filters['year'] ?? date('Y');
            $month = $filters['month'] ?? null;

            if (!empty($filters['plan_id'])) {
                    $query->where('plan_id', $filters['plan_id']);
            }
            if (!empty($filters['plan_type'])) {
                Log::info('Filter applied: plan type');
               
                $query->where('plan_type_id', $filters['plan_type']);
            
            }

            if (!empty($filters['expiredyear'])) {
                Log::info('Filter applied: expiredyear ');
                $year = $filters['expiredyear'];
                $query->whereYear('plan_end_date', $year);
               
            }
        
            if (!empty($filters['expiredmonth']) && !empty($filters['expiredyear'])) {
                Log::info('Filter applied: expiredyear and expiredmonth');
                $year = $filters['expiredyear'];
                $month = $filters['expiredmonth'];
               
                $query->whereYear('plan_end_date', $year)->whereMonth('plan_end_date', $month);
               
            }
           
            if (isset($filters['is_paid'])) {
                Log::info('Filter applied: unpaid');
               
                $query->where('is_paid', $filters['is_paid']);
               
            }

                // Apply the year filter if provided
            if (!empty($filters['year'])) {
                Log::info('Filter applied: year');
                $year = $filters['year'];
                
                // Adjust query to cover plan dates within the given year
                $query->whereYear('plan_start_date', '<=', $year)
                ->whereYear('plan_end_date', '>=', $year);
            }
        
            // Apply the month filter if provided (year should be set either by filter or default)
            if (!empty($filters['month'])) {
               
               
                Log::info('Filter applied: year and month',['year' => $year,'month' => $month,]);
                $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
                $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();
                $query->where('plan_start_date', '<=', $endOfMonth)->where('plan_end_date', '>=', $startOfMonth);
             
            }

                // Apply the status filter if provided
            if (isset($filters['status'])) {
                $status = $filters['status'];
                
                // If status = 0 (expired), filter based on the year and/or month, if provided
                if ($status == 0 && ($filters['year'] || $filters['month'])) {
                    
                    Log::info('Filter applied: expired status with year and/or month', ['year' => $year,'month' => $month,'status' => $status,]);
                    
                    $query->where('learner_detail.status', $status)
                    ->whereYear('learner_detail.plan_end_date', $year);
                
                    if ($month) {
                        Log::info('in month');
                        $query->whereMonth('learner_detail.plan_end_date', $month);
                    }
                } else {
                    // Apply regular status filter if not expired with specific year/month
                    Log::info('not expired with specific year/month');
                   
                        $query->where('status', $status);
                   
                }
            }

            // Search by Name, Mobile, or Email
           if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('mobile', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('email', 'LIKE', '%' . $searchTerm . '%');
            });
            }
           
        }
        // \DB::enableQueryLog();
        // $learners = $query->get();
        // dd(\DB::getQueryLog());
       
        return $query->get();
    }

  

    

    
}
