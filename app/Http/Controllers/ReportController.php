<?php

namespace App\Http\Controllers;

use App\Models\CustomerDetail;
use App\Models\Customers;
use App\Models\Expense;
use Illuminate\Http\Request;
use DB;

class ReportController extends Controller
{
    
    public function masterExpense(){
        $expenses=Expense::get();
        return view('master.expense',compact('expenses'));
    }
    public function masterExpenseEdit($id)
    {
        $expenses=Expense::get();
        $expense=Expense::find($id);
        return view('master.expense',compact('expense','expenses'));
    }
    public function masterExpenseStore(Request $request, $id = null)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Check if an ID is provided for updating an existing expense
        if ($id) {
            // Find the existing expense by ID
            $expense = Expense::findOrFail($id);

            // Update the existing expense with the validated data
            $expense->update($validatedData);
            return redirect()->route('expense')->with('success', 'Expense updated successfully!');
        } else {
            // Create a new expense with the validated data
            Expense::create($validatedData);
            return redirect()->route('expense')->with('success', 'Expense created successfully!');
        }
    }
    public function masterExpenseDestroy($id)
    {
        // Find the expense by its ID
        $expense = Expense::findOrFail($id);

        // Delete the expense
        $expense->delete();

        // Redirect back to the expense list with a success message
        return redirect()->route('expense')->with('success', 'Expense deleted successfully!');
    }

    public function monthlyReport()
    {
        // Fetch monthly revenues
        $monthlyRevenues =CustomerDetail::selectRaw('YEAR(join_date) as year, MONTH(join_date) as month, SUM(plan_price_id) as total_revenue')
            ->groupBy('year', 'month')
            ->get();

        // Initialize an array to hold the final report data
        $reportData = [];

        foreach ($monthlyRevenues as $monthlyRevenue) {
            // Fetch corresponding monthly expenses with MIN(id)
            $monthlyExpenses = DB::table('monthly_expense')
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
        $monthlyExpenses = DB::table('monthly_expense')
                         ->where('year', $year)
                         ->where('month', $month)
                         ->get();
      
        $library_revenue=  CustomerDetail::whereMonth('join_date', date('m'))
            ->whereYear('join_date', date('Y'))
            ->sum('plan_price_id');
        $expenses=Expense::get();
        $revenue_expense = DB::table('monthly_expense')
        ->join('expenses', 'monthly_expense.expense_id', '=', 'expenses.id')
        ->select('monthly_expense.*', 'expenses.name as expense_name')
        ->get();
    
       
        return view('report.expense',compact('library_revenue','expenses','monthlyExpenses','year', 'month','revenue_expense'));
    }
    public function monthlyExpenseStore(Request $request, $id = null)
    {
        // Validate the incoming request data
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
        $existingExpenseIds = DB::table('monthly_expense')
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
        // Loop through remaining each expense and save/update them in the database
        foreach ($validatedData['expense_id'] as $index => $expenseId) {
            $amount = $validatedData['amount'][$index];
    
            // Insert or update the monthly expenses
            DB::table('monthly_expense')->updateOrInsert(
                [
                    'year' => $year,
                    'month' => $month,
                    'expense_id' => $expenseId,
                ],
                [
                    'amount' => $amount,
                    'updated_at' => now(), // Set the current timestamp for updates
                    'created_at' => now(), // Set the timestamp for inserts
                ]
            );
        }
    
        // Redirect back with a success message
        return redirect()->route('report.monthly')->with('success', 'Expenses recorded successfully!');
    }
    


    


}
