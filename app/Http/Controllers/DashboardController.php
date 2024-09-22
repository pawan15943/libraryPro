<?php

namespace App\Http\Controllers;

use App\Models\CustomerDetail;
use App\Models\Customers;
use App\Models\Library;
use App\Models\Student;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
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
       
        if ($user->hasRole('superadmin')) {
           
     
            // for library booked seat
        
            return view('dashboard.administrator');
        }if ($user->hasRole('admin')) {
          
            if (Library::where('id', $user->id)->whereNull('library_type')->exists()) {
                return redirect()->route('subscriptions.choosePlan');
            }
            if (Library::where('id', $user->id)->where('is_paid',1)->whereNull('library_no')->exists()) {
                return redirect()->route('library.master');
            }
            
          
            // for library booked seat
        
            return view('dashboard.admin');
        }if ($user->hasRole('learner')) {
            
     
            // for library booked seat
        
            return view('dashboard.learner');
        }else{
           dd("no");
        }
       
    }
}
