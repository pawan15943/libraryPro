<?php

namespace App\Http\Controllers;

use App\Models\DemoRequest;
use App\Models\Inquiry;
use App\Models\Library;
use App\Models\LibraryTransaction;
use App\Models\Subscription;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function contactInqueryGet(){
        $data=Inquiry::get();
        return view('administrator.inquery-list',compact('data'));
    }
    public function demoRequestGet(){
        $data=DemoRequest::get();
        return view('administrator.demo-request-list',compact('data'));
    }

    public function libraryPayment($library_id){
            
          
            $data = Library::where('id', $library_id)
            ->with('subscription.permissions')  
            ->first();
            $plans=Subscription::pluck('name','id');
         
            // $plan = Subscription::where('id', $data->library_type)->first();
           
            // $month = LibraryTransaction::where('id', $transactionId)
            //     ->orderBy('id', 'desc')
            //     ->first();
            return view('administrator.library-payment',compact('plans','library_id'));
    }
}
