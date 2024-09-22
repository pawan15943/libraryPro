<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LearnerController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('administrator/login', [LoginController::class, 'showLoginForm'])->name('login.administrator');
Route::get('library/login', [LoginController::class, 'showAdminLoginForm'])->name('login.library');
Route::get('learner/login', [LoginController::class, 'showLearnerLoginForm'])->name('login.learner');
Route::post('login/store', [LoginController::class, 'login'])->name('login.store');
Route::get('library/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Auth routes
Auth::routes(['register' => false, 'login' => false,'verify' => false]);
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->withoutMiddleware('auth');
Route::group(['prefix' => 'library'], function () {
  Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request.library');
  Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email.library');
  Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.library');
  Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update.library');
});


Route::get('/email/verify', function () {
  return view('auth.verify');
})->name('verification.notice');
Route::post('/verify-otp', [LibraryController::class, 'verifyOtp'])->name('verify.otp');
Route::get('library/choose-plan-price', [LibraryController::class, 'getSubscriptionPrice'])->name('subscriptions.getSubscriptionPrice');
Route::get('cityGetStateWise', [MasterController::class, 'stateWiseCity'])->name('cityGetStateWise');
Route::get('library/create', [LibraryController::class, 'create'])->name('library.create');
Route::post('library/store', [LibraryController::class, 'store'])->name('library.store');
// Routes for library users with 'auth:library' guard
Route::middleware(['auth:library', 'verified'])->group(function () {
  Route::get('/library/library-master', [MasterController::class, 'masterPlan'])->name('library.master');
 
  Route::get('/library/home', [DashboardController::class, 'libraryDashboard'])->name('library.home'); // Library user home
  Route::get('library/choose-plan', [LibraryController::class, 'choosePlan'])->name('subscriptions.choosePlan');
  Route::post('/subscriptions/payment-add', [LibraryController::class, 'paymentProcess'])->name('subscriptions.payment');
  Route::get('library/subscriptions/payment-add', [LibraryController::class, 'paymentProcess'])->name('subscriptions.payment');
  Route::post('/library/payment-store', [LibraryController::class, 'paymentStore'])->name('library.payment.store');

  Route::get('/library/profile', [LibraryController::class, 'profile'])->name('profile');
  Route::post('/library/profile/update', [LibraryController::class, 'updateProfile'])->name('library.profile.update');
 
  Route::post('library/master/store', [MasterController::class, 'storemaster'])->name('master.store');
  Route::get('library/master/edit', [MasterController::class, 'masterEdit'])->name('master.edit');
  Route::post('library/seats', [MasterController::class, 'seatsStore'])->name('seats.store');
  Route::post('library/extend-day', [MasterController::class, 'extendDay'])->name('extendDay.store');
  
  Route::delete('/activeDeactive/{id}/toggle', [MasterController::class, 'activeDeactive'])->name('activeDeactive');

 
   //**LEARNER**//

  //  Route::get('seats/list', [LearnerController::class, 'index'])->name('seats');
  Route::get('/library/learner', [LearnerController::class, 'index'])->name('seats');
  Route::post('learner/store', [LearnerController::class, 'learnerStore'])->name('learners.store');

   Route::get('learner/list', [LearnerController::class, 'learnerList'])->name('learners');
   Route::get('learner/history/list', [LearnerController::class, 'learnerList'])->name('learnerHistory');
   Route::get('learner/show/{id?}', [LearnerController::class, 'getUser'])->name('learners.show');
   Route::get('learner/edit/{id?}', [LearnerController::class, 'getUser'])->name('learners.edit');
   Route::put('user/update/{id?}', [LearnerController::class, 'userUpdate'])->name('learners.update');
   // Route::post('user/update/', [LearnerController::class, 'userUpdate'])->name('learners.update');
   Route::get('user/swap/{id?}', [LearnerController::class, 'getSwapUser'])->name('learners.swap');
   Route::put('learners/swap-seat', [LearnerController::class, 'swapSeat'])->name('learners.swap-seat');
   Route::get('learner/upgrade/{id?}', [LearnerController::class, 'getLearner'])->name('learners.upgrade');
   Route::post('user/close', [LearnerController::class, 'userclose'])->name('learners.close');
   Route::delete('/learners/{Customers}', [LearnerController::class, 'destroy'])->name('learners.destroy');
   Route::get('seat/history/list', [LearnerController::class, 'seatHistory'])->name('seats.history');
   Route::get('seats/history/{id?}', [LearnerController::class, 'history'])->name('seats.history.show');
   Route::get('learner/reactive/{id?}', [LearnerController::class, 'reactiveUser'])->name('learners.reactive');
   Route::put('learner/reactive/{id?}', [LearnerController::class, 'reactiveLearner'])->name('learner.reactive.store');
 //condition base route
   Route::post('learner/renew/', [LearnerController::class, 'learnerRenew'])->name('learners.renew');
   Route::get('getSeatStatus', [LearnerController::class, 'getSeatStatus'])->name('getSeatStatus');
   Route::get('getPlanType', [LearnerController::class, 'getPlanType'])->name('gettypePlanwise');
   Route::get('getPlanTypeSeatWise', [LearnerController::class, 'getPlanTypeSeatWise'])->name('gettypeSeatwise');
   Route::get('getPrice', [LearnerController::class, 'getPrice'])->name('getPricePlanwise');
   Route::get('getPricePlanwiseUpgrade', [LearnerController::class, 'getPricePlanwiseUpgrade'])->name('getPricePlanwiseUpgrade');
});
// Routes for superadmin and admin users
Route::middleware(['auth:web'])->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home'); // Admin or superadmin home
    Route::get('library/payment/{id}', [LibraryController::class, 'addPayment'])->name('library.payment');
    Route::middleware(['role:superadmin|admin'])->group(function () {

        Route::get('subscriptions-permissions', [MasterController::class, 'index'])->name('subscriptions.permissions');
        Route::post('subscriptions/store', [MasterController::class, 'storeSubscription'])->name('subscriptions.store');
        Route::post('subscriptions/assign-permissions', [MasterController::class, 'assignPermissionsToSubscription'])->name('subscriptions.assignPermissions');
        Route::get('/subscriptions/{id}/permissions', [MasterController::class, 'getPermissions'])->name('subscriptions.getPermissions');

        Route::get('library', [LibraryController::class, 'index'])->name('library');
    });
});






