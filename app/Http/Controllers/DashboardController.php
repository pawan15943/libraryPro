<?php

namespace App\Http\Controllers;

use App\Models\CustomerDetail;
use App\Models\Customers;
use App\Models\Library;
use App\Models\LibraryTransaction;
use App\Models\Student;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Carbon;
use App\Services\LibraryService;

class DashboardController extends Controller
{
    protected $libraryService;
    public function __construct(LibraryService $libraryService)
    {
        $this->libraryService = $libraryService;
    }
    public function index()
    {
       
        $user=Auth::user();
     
        if ($user->hasRole('superadmin')) {
           
     
            // for library booked seat
        
            return view('dashboard.administrator');
        }if ($user->hasRole('admin')) {
            dd("admin");
     
            // for library booked seat
        
            return view('dashboard.admin');
        }if ($user->hasRole('learner')) {
            
     
            // for library booked seat
        
            return view('dashboard.learner');
        }else{
           dd("no");
        }
       
    }

    public function libraryDashboard()
    {
        
        $user=Auth::user();
 
        if ($user->hasRole('admin')) {
            $value = LibraryTransaction::where('library_id', Auth::user()->id)
            ->where('status', 1)
            ->first();

            if ($value) {
                $today = Carbon::today();
                $endDate = Carbon::parse($value->end_date);
                $diffInDays = $today->diffInDays($endDate, false);
                    if ($diffInDays <= 0){
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


           
            $redirectUrl = $this->libraryService->checkLibraryStatus();
            if ($redirectUrl) {
                return redirect($redirectUrl);
            }else{
                return view('dashboard.admin',compact('diffInDays'));
            }
            
        }if ($user->hasRole('learner')) {
          
            return view('dashboard.learner');
        }else{
           dd("no");
        }
       
    }
}
