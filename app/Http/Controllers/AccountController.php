<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class AccountController extends Controller
{
    public function index(){
        $datas = Student::select('students.*','states.state_name','cities.city_name','grades.class_name','courses.course_name','course_types.name as course_type')
        ->leftJoin('states', 'students.state_id', '=', 'states.id')
        ->leftJoin('cities', 'students.city_id', '=', 'cities.id')
        ->leftJoin('grades', 'students.grade_id', '=', 'grades.id')
        ->leftJoin('courses', 'students.course_id', '=', 'courses.id')
        ->leftJoin('course_types', 'courses.course_type', '=', 'course_types.id')
        ->orderBy('students.created_at', 'DESC')->get();
        
       
        return view('student.payment-list',compact('datas'));
    }

    public function savePayment(Request $request){
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'paid_amount' => 'required|numeric|min:0',
            'paid_date' => 'required',
        ]);

        $studentId = $request->student_id;
        $totalAmount= $request->total_amount;
        $paidAmount = $request->paid_amount;
        $paidDate = $request->paid_date;

        // Calculate the total amount
      

        $previousTransactions = DB::table('transactions')
            ->where('student_id', $studentId)
            ->get();

        $totalPaidAmount = $previousTransactions->sum('paid_amount');
        $pendingAmount = $totalAmount - $totalPaidAmount - $paidAmount;

        if ($paidAmount > ($request->pending_amount)) {
            return redirect()->back()->with('error','Amount exceeds the pending amount');
            die;
        }
       
        // Insert the new transaction
        DB::table('transactions')->insert([
            'student_id' => $studentId,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'pending_amount' => $pendingAmount,
            'paid_date' => $paidDate,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $lastTransactions = DB::table('transactions')
            ->where('student_id', $studentId)
            ->orderBy('id','DESC')->first();
        if($lastTransactions->pending_amount==0){
            Student::where('id',$studentId)->update(['is_paid'=>1]);
        }

        return redirect()->back()->with('success', 'Payment successful');
    }

    public function addPayment($id){
        $student = Student::findOrFail($id); 
        $course=Course::where('id', $student->course_id)->select('course_fees')->first();
        $total_fees=$course->course_fees;
        $transaction=Transaction::where('student_id',$student->id)->orderBy('id','DESC')->first();
        if($transaction){
            $pending_amount=$transaction->pending_amount;
        }else{
            $pending_amount=$total_fees;
        }
       
        $transaction_list=Transaction::where('student_id',$student->id)->orderBy('id','ASC')->get();
        return view('student.add-payment', compact('student','total_fees','pending_amount','transaction_list'));
    }

    
}
