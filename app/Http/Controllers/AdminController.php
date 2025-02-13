<?php

namespace App\Http\Controllers;

use App\Models\DemoRequest;
use App\Models\Inquiry;
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
}
