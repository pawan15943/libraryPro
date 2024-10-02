<?php

namespace App\Http\Controllers;

use App\Models\CustomerDetail;
use App\Models\Customers;
use App\Models\Expense;
use App\Models\LearnerDetail;
use Illuminate\Http\Request;
use DB;
use Auth;

class ReportController extends Controller
{
    
  

    public function monthlyReport()
    {
        // Fetch monthly revenues
        $monthlyRevenues =LearnerDetail::selectRaw('YEAR(join_date) as year, MONTH(join_date) as month, SUM(plan_price_id) as total_revenue')
            ->groupBy('year', 'month')
            ->get();

        // Initialize an array to hold the final report data
        $reportData = [];

        foreach ($monthlyRevenues as $monthlyRevenue) {
            // Fetch corresponding monthly expenses with MIN(id)
            $monthlyExpenses = DB::table('monthly_expense')->where('library_id',Auth::user()->id)
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
                'id' => $monthlyExpenses->expense_id ?? null, // Using null if no record is found
                'total_expenses' => $monthlyExpenses->total_expenses ?? 0, // Using 0 if no record is found
            ];
        }
       
        return view('report.monthly_report', ['reportData' => $reportData]);
    }


    public function monthlyExpenseCreate($year, $month){
        $monthlyExpenses = DB::table('monthly_expense')->where('library_id',Auth::user()->id)
                         ->where('year', $year)
                         ->where('month', $month)
                         ->get();
      
        $library_revenue=  LearnerDetail::whereMonth('join_date', date('m'))
            ->whereYear('join_date', date('Y'))
            ->sum('plan_price_id');
        $expenses=Expense::get();
        $revenue_expense = DB::table('monthly_expense')
        ->join('expenses', 'monthly_expense.expense_id', '=', 'expenses.id')
        ->where('monthly_expense.library_id', Auth::id()) // Optimized to use Auth::id()
        ->where('expenses.library_id', Auth::id())
        ->select('monthly_expense.*', 'expenses.name as expense_name')
        ->get();
    
        return view('report.expense',compact('library_revenue','expenses','monthlyExpenses','year', 'month','revenue_expense'));
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
        $existingExpenseIds = DB::table('monthly_expense')->where('library_id',Auth::user()->id)
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
    


    


}
