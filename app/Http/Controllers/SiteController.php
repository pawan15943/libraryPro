<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Feedback;
use App\Models\Learner;
use App\Models\Library;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function aboutUs(){
        return view('site.about-us');
    }
    public function blog(){
        return view('site.blog');
    }
    public function contactUs(){
        return view('site.contact-us');
    }
    public function privacyPolicy(){
        return view('site.privacy-policy');
    }
    public function termAndCondition(){
        return view('site.term-and-condition');
    }
    public function refundPolicy(){
        return view('site.refund-policy');
    }
    public function home(){
        $subscriptions = Subscription::with('permissions')->get();
        return view('welcome',compact('subscriptions'));
    }
    public function searchLibrary(){
        $cities = City::pluck('city_name', 'id');
        $topLibraries = Library::take(5)->get();
        $library_count=Library::count();
        $learner_count=Learner::count();
        $city_count=City::count();
        $feedback_count=Feedback::count();
        $happy_customer=Feedback::leftJoin('libraries','feedback.library_id','=','libraries.id')->leftJoin('cities','cities.id','libraries.city_id')->where('feedback.rating','>',4)->select('libraries.library_address','libraries.created_at','feedback.*','cities.city_name')->get();
       
        return view('library-directory' ,compact('cities','topLibraries','learner_count','library_count','city_count','happy_customer','feedback_count'));
    }
}
