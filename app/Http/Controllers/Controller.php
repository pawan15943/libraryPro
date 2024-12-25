<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Hour;
use App\Models\Learner;
use App\Models\LearnerDetail;
use App\Models\LearnerTransaction;
use App\Models\Library;
use App\Models\LibraryTransaction;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\PlanType;
use App\Models\Seat;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Throwable;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Middleware\LoadMenus;
use App\Models\Expense;
use App\Models\Subscription;
use App\Traits\LearnerQueryTrait;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    use LearnerQueryTrait;
    public function generateReceipt(Request $request)
    {
        if($request->type=='library'){
         
            $data = LibraryTransaction::where('id', $request->id)->first();
            $user = Library::where('id', $data->library_id)->where('status', 1)->first();
            $transactionDate=$data->transaction_date;
            $paymentMode=$data->payment_mode;
            $total_amount=$data->amount;
            $month=$data->month;
            $start_date=$data->start_date;
            $end_date=$data->end_date;
            $name=$user->library_owner;
            $subscription=Subscription::where('id',$user->library_type)->value('name');
        }
        if($request->type=='learner'){
            $learnerDeatail = LearnerDetail::where('id', $request->id)
            ->with(['plan', 'planType'])
            ->first();
           
            $data = LearnerTransaction::where('learner_detail_id', $learnerDeatail->id)->where('is_paid',1)->first();
        
            $user = Learner::where('id', $data->learner_id)->first();
          
            $transactionDate=$data->paid_date;
            $paymentMode='Offline';
            $total_amount=$data->total_amount;
           
        
            if ($learnerDeatail) {
                $month = $learnerDeatail->plan ? $learnerDeatail->plan->plan_id : null; // Check if 
                $start_date = $learnerDeatail->plan_start_date;
                $end_date = $learnerDeatail->plan_end_date;
                $subscription=$learnerDeatail->plantype ? $learnerDeatail->plantype->name : null;
            } else {
            
                $month = null;
                $start_date = null;
                $end_date = null;
                $subscription=null;
            }
            $name=$user->name;
           
           
        }
       
        
        $send_data = [
            'subscription' =>$subscription ?? 'NA',
            'name' => $name ?? 'NA',
            'email' => $user->email ?? 'NA',
            'transactiondate' => $transactionDate ?? 'NA',
            'paid_amount' => $data->paid_amount ?? 'NA',
            'payment_mode' => $paymentMode ?? 'NA',
            'invoice_ref_no' => $data->transaction_id ?? 'NA',
            'total_amount' => $total_amount ?? 'NA',
            'start_date' => $start_date ?? 'NA',
            'end_date' => $end_date ?? 'NA',
            'monthly_amount' => $total_amount ?? 'NA',
            'month' => $month ?? 'NA',
            'currency' => 'Rs.',
        ];
        

        // Generate the PDF without saving it on the server
        $pdf = PDF::loadView('recieptPdf', $send_data);

        return $pdf->download(time() . '_receipt.pdf');
    }

    
    public function showUploadForm($id = null)
    {
        $library_id=$id;
        // if (Auth::check('guard') == 'library') {
        //     dd("1");
        //     // Handle library specific logic
        // } elseif (Auth::check('guard') == 'web') {
        //     dd("2");
        //     // Handle web specific logic
        // }
        // dd("3");
        return view('library.csv', compact('library_id'));
    }

    // protected function validateAndInsert($data, &$successRecords, &$invalidRecords)
    // {
    //     // Validate data
    //     $validator = Validator::make($data, [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email',
    //     ]);

    //     if ($validator->fails()) {
    //         $invalidRecords[] = array_merge($data, ['error' => 'Validation failed']);
    //         return;
    //     }

    //     $dob = $this->parseDate(trim($data['dob']));
    //     $start_date = $this->parseDate(trim($data['start_date']));

    //     if (!$start_date) {
    //         $invalidRecords[] = array_merge($data, ['error' => 'Start date not found']);
    //         return;
    //     }

    //     if (!$dob) {
    //         $invalidRecords[] = array_merge($data, ['error' => 'Invalid date of birth format']);
    //         return;
    //     }

    //     $plan = Plan::where('plan_id', trim($data['plan']))->first();
    //     $planType = PlanType::where('name', 'LIKE', trim($data['plan_type']))->first();
    //     $planPrice = PlanPrice::where('price', 'LIKE', trim($data['plan_price']))->first();

    //     if (!$plan || !$planType || !$planPrice) {
    //         $invalidRecords[] = array_merge($data, ['error' => 'Plan, Plan type, or Plan price not found']);
    //         return;
    //     }

    //     $seat = Seat::where('seat_no', trim($data['seat_no']))->first();
    //     $payment_mode = $this->getPaymentMode(trim($data['payment_mode']));
    //     $hours = $planType->slot_hours;
    //     $duration = trim($data['plan']) ?? 0;
    //     $joinDate = isset($data['join_date']) ? $this->parseDate(trim($data['join_date'])) : $start_date;
    //     if (isset($data['end_date'])) {
    //         $endDate = $this->parseDate(trim($data['end_date']));
    //     } else {
    //         $endDate = Carbon::parse($start_date)->addMonths($duration)->format('Y-m-d');
    //     }

    //     $learner = Learner::create([
    //         'library_id' => Auth::user()->id,
    //         'name' => trim($data['name']),
    //         'email' => trim($data['email']),
    //         'password' => bcrypt(trim($data['mobile'])),
    //         'mobile' => trim($data['mobile']),
    //         'dob' => $dob,
    //         'hours' => trim($hours),
    //         'seat_no' => trim($data['seat_no']),
    //         'payment_mode' => $payment_mode,
    //         'address' => trim($data['address']),
    //     ]);
    
    //     LearnerDetail::create([
    //         'learner_id' => $learner->id,
    //         'plan_id' => $plan->id,
    //         'plan_type_id' => $planType->id,
    //         'plan_price_id' => $planPrice->id,
    //         'plan_start_date' => $start_date,
    //         'plan_end_date' => $endDate,
    //         'join_date' => $joinDate,
    //         'hour' => $hours,
    //         'seat_id' => $seat->id,
    //         'library_id' => Auth::user()->id,
    //     ]);

    //     $pending_amount = $planPrice->price - trim($data['paid_amount']);
    //     $paid_date = isset($data['paid_date']) ? $this->parseDate(trim($data['paid_date'])) : $start_date;

    //     // Create related LearnerTransaction record
    //     LearnerTransaction::create([
    //         'learner_id' => $learner->id,
    //         'library_id' => Auth::user()->id,
    //         'total_amount' => $planPrice->price,
    //         'paid_amount' => trim($data['paid_amount']),
    //         'pending_amount' => $pending_amount,
    //         'paid_date' => $paid_date,
    //     ]);

    //     $successRecords[] = $data;
    // }

    public function uploadCsv(Request $request)
    {
       
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt,xlsx,xls',
        ]);
        

        // Get the file and its real path
        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        $csvData = [];
        $header = null;

        // Open the file and parse the CSV
        if (($handle = fopen($path, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $row = array_map('trim', $row);

                if (!$header) {
                    $header = $row; // Set first row as header
                } else {
                    if (count($header) == count($row)) {
                        $csvData[] = array_combine($header, $row);
                    } else {
                        Log::error('CSV row does not match header format: ', $row);
                        return redirect()->back()->withErrors('CSV data does not match header format.');
                    }
                }
            }
            fclose($handle);
        }

        // Invalid and success records
        $invalidRecords = [];
        $successRecords = [];
        if($request->library_id){
            $library_id=$request->library_id;
        }elseif($request->library_import=='library_master'){
            $library_id=Auth::user()->id;
        }else{
            $library_id=null; 
        }
        
     

        DB::transaction(function () use ($csvData, &$invalidRecords, &$successRecords,$library_id) {
            foreach ($csvData as $record) {
                try {
                    $this->validateAndInsert($record, $successRecords, $invalidRecords);
                   
                    
                } catch (Throwable $e) {
                    Log::error('Error inserting record: ' . $e->getMessage(), $record);
                    $record['error_message'] = $e->getMessage();
                    $invalidRecords[] = $record;
                }
            }
        });

        if (!empty($invalidRecords)) {
        
            session(['invalidRecords' => $invalidRecords]); 

            return redirect()->route('library.upload.form')->with([
                'successCount' => count($successRecords),
                'autoExportCsv' => true, // This triggers the CSV download
            ]);
        }
        
        return redirect()->back()->with('successCount', count($successRecords));
       
    }
    
    public function uploadmastercsv(Request $request){
        
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt,xlsx,xls',
        ]);

        // Get the file and its real path
        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        $csvData = [];
        $header = null;

        // Open the file and parse the CSV
        if (($handle = fopen($path, 'r')) !== false) {
            
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $row = array_map('trim', $row);
              
                if (!$header) {
                  
                    $header = $row; // Set first row as header
                } else {
                   
                    if (count($header) == count($row)) {
                        $csvData[] = array_combine($header, $row);
                    } else {
                        Log::error('CSV row does not match header format: ', $row);
                        return redirect()->back()->withErrors('CSV data does not match header format.');
                    }
                }
            }
            fclose($handle);
        }

        // Invalid and success records
        $invalidRecords = [];
        $successRecords = [];
        if($request->library_id){
            $library_id=$request->library_id;
        }elseif($request->library_import=='library_master'){
            $library_id=Auth::user()->id;
        }else{
            $library_id=null; 
        }
       
        DB::transaction(function () use ($csvData, &$invalidRecords, &$successRecords,$library_id) {
            foreach ($csvData as $record) {
                try {
                    
                    $this->validateMasterInsert($record, $successRecords, $invalidRecords,$library_id);
                   
                    
                } catch (Throwable $e) {
                    Log::error('Error inserting record: ' . $e->getMessage(), $record);
                    $record['error_message'] = $e->getMessage();
                    $invalidRecords[] = $record;
                }
            }
        });

        if (!empty($invalidRecords)) {
        
            session(['invalidRecords' => $invalidRecords]); 

            return redirect()->back()->with([
                'successCount' => count($successRecords),
                'autoExportCsv' => true, // This triggers the CSV download
            ]);
        }
        
        
        $middleware = app(LoadMenus::class);
        $middleware->updateLibraryStatus();
        return redirect()->back()->with('successCount', count($successRecords));
    }

    protected function validateAndInsert($data, &$successRecords, &$invalidRecords)
    {
    
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'plan' => 'required|int',
        ]);

        if ($validator->fails()) {
            $invalidRecords[] = array_merge($data, ['error' => 'Validation failed']);
            return;
        }
        $user = Auth::user();

        $dob = $this->parseDate(trim($data['dob']));
        $start_date = $this->parseDate(trim($data['start_date']));

        if (!$start_date) {
            $invalidRecords[] = array_merge($data, ['error' => 'Start date not found']);
            return;
        }

        if (!$dob) {
            $invalidRecords[] = array_merge($data, ['error' => 'Invalid date of birth format']);
            return;
        }
      

        $plan = Plan::where('plan_id', trim($data['plan']))->first();
        $planType = PlanType::where('name', '=', trim($data['plan_type']))->first();
        $planPrice = PlanPrice::where('price', 'LIKE', trim($data['plan_price']))->first();
        if ((!$user->can('has-permission', 'Full Day') && $planType->day_type_id==1) || (!$user->can('has-permission', 'First Half') && $planType->day_type_id==2) || (!$user->can('has-permission', 'Second Half') && $planType->day_type_id==3) || (!$user->can('has-permission', 'Hourly Slot 1') && $planType->day_type_id==4)|| (!$user->can('has-permission', 'Hourly Slot 2') && $planType->day_type_id==5)|| (!$user->can('has-permission', 'Hourly Slot 3') && $planType->day_type_id==6)|| (!$user->can('has-permission', 'Hourly Slot 4') && $planType->day_type_id==7)){
            $invalidRecords[] = array_merge($data, ['error' => $planType->name.'Plan type has no permissions']);
            return;
        }
        if (!$plan ) {
            $invalidRecords[] = array_merge($data, ['error' => 'Plan  not found']);
            return;
        }
        if (!$planType ) {
            $invalidRecords[] = array_merge($data, ['error' => 'Plan type  not found']);
            return;
        }
        if ( !$planPrice) {
            $invalidRecords[] = array_merge($data, ['error' => ' Plan price not found']);
            return;
        }

        $seat = Seat::where('seat_no', trim($data['seat_no']))->first();
        if(!$seat){
            $invalidRecords[] = array_merge($data, ['error' => 'This Seat No. not exists']);
            return;
        }
       
        $payment_mode = $this->getPaymentMode(trim($data['payment_mode']));
        $hours = $planType->slot_hours;
        $duration = trim($data['plan']) ?? 0;
        $joinDate = isset($data['join_date']) ? $this->parseDate(trim($data['join_date'])) : $start_date;
        // Here we manage end date how it calculated.
        $endDate = Carbon::parse($start_date)->addMonths($duration)->format('Y-m-d');
        

        $pending_amount = $planPrice->price - trim($data['paid_amount']);
        $paid_date = isset($data['paid_date']) ? $this->parseDate(trim($data['paid_date'])) : $start_date;

        $extend_days=Hour::select('extend_days')->first();
        $extendDay = $extend_days ? $extend_days->extend_days : 0;
       
        $inextendDate = Carbon::parse($endDate)->addDays($extendDay);
        $status = $inextendDate > Carbon::today() ? 1 : 0;

        $is_paid = $pending_amount <= 0 ? 1 : 0;
        if ($status == 1) {
            \Log::info('Learner for updated', [ 'status1' => $status]);
            // Check if the learner already exists with active status
            $alreadyLearner = Learner::where('library_id', Auth::user()->id)
                ->where('email', trim($data['email']))
                ->where('status', 1)
                ->exists();
            $exist_check = Learner::where('library_id', Auth::user()->id)
            ->where('email', trim($data['email']))
            ->where('status', 0)
            ->exists();

            if ($alreadyLearner) {
              
                $invalidRecords[] = array_merge($data, ['error' => 'This data already exists']);
                return;
            } else {
               
                // Check if seat is already occupied
                if (Learner::where('library_id', Auth::user()->id)
                    ->where('seat_no', trim($data['seat_no']))
                    ->where('status', 1)
                    ->exists()) {
                        \Log::info('Learner occupide', [ 'status1' => $status]);
                    $first_record = Hour::first();
                    $total_hour = $first_record ? $first_record->hour : null;
                    $hours = PlanType::where('id', $planType->id)->value('slot_hours');
                    $day_type_id=PlanType::where('id', $planType->id)->value('day_type_id');


                    $exists_data=Learner::leftJoin('learner_detail', 'learner_detail.learner_id', '=', 'learners.id')
                    ->where('learners.library_id', auth()->user()->id)
                    ->where('learners.seat_no', trim($data['seat_no']))
                    ->where('learners.status', 1)
                    ->where('learner_detail.status', 1)->with('planType')->get();

                    $planTypeSame=Learner::leftJoin('learner_detail', 'learner_detail.learner_id', '=', 'learners.id')
                    ->where('learners.library_id', auth()->user()->id)
                    ->where('learners.seat_no', trim($data['seat_no']))
                    ->where('learners.status', 1)
                    ->where('learner_detail.status', 1)->where('learner_detail.plan_type_id',$planType->id)->count();
                    if($planTypeSame > 0){
                        $invalidRecords[] = array_merge($data, ['error' => 'Your plan type already booked 1']);
                        return; 
                    }

                    // Day Type 1 : FD | 2 : FH | 3 : SH | 4 : H1 | 5: H2 | 6 : H3 | 7 : H4
                    // Here we check if FH is booked then H1 & H2 is Not booked and so on.
                    foreach($exists_data as $data_get){
                        if(($day_type_id==2 && ($data_get->planType->day_type_id==4 || $data_get->planType->day_type_id==5))
                         || ($day_type_id==3 && ($data_get->planType->day_type_id==6 || $data_get->planType->day_type_id==7)) 
                         || (($day_type_id==4 || $day_type_id==5) && ($data_get->planType->day_type_id==2)) 
                         || (($day_type_id==6 || $day_type_id==7) && ($data_get->planType->day_type_id==3))
                         || (($day_type_id==1 ) && ($data_get->planType->day_type_id==1 || $data_get->planType->day_type_id==2 || $data_get->planType->day_type_id==3 || $data_get->planType->day_type_id==4 ||$data_get->planType->day_type_id==5 ||$data_get->planType->day_type_id==6 ||$data_get->planType->day_type_id==7)))
                        {
                            $invalidRecords[] = array_merge($data, ['error' => 'Your plan type already booked 2']);
                            return; 
                        }
                    }
                    // Check if total hours exceed allowed hours
                    if ((Learner::where('seat_no', trim($data['seat_no']))
                         ->where('library_id', Auth::user()->id)
                        ->where('learners.status', 1)
                        ->sum('hours') + $hours) > $total_hour) {
                            \Log::info('plan type exceed');
                        $invalidRecords[] = array_merge($data, ['error' => 'Your plan type exceeds the library total hours']);
                        return;
                    }else {
                        // Create new learner and associated records
                        if (empty($data['name']) || empty($data['email']) || empty($data['mobile']) || empty($hours) || empty($seat) || empty($start_date) || empty($planPrice->price)) {
                            $invalidRecords[] = array_merge($data, ['error' => 'Missing essential data for creating learner']);
                            return;
                        }
                       
                        \Log::info('last else in Learner craete', [ 'status2' => $status]);
                        $learner = $this->createLearner($data, $hours, $dob, $payment_mode, $status, $plan, $planType, $seat, $start_date, $endDate, $joinDate, $is_paid, $planPrice, $pending_amount, $paid_date);
                    }
                } elseif($exist_check){
                    \Log::info('for renew data create learner detail and update learner DB', [ 'status1' => $status]);
                    $learnerData = Learner::where('library_id', Auth::user()->id)
                    ->where('email', trim($data['email']))
                    ->where('status', 0)
                    ->first();
                   
                    $this->createLearnerDetail($learnerData->id, $plan,$status, $planType, $seat, $data, $start_date, $endDate, $joinDate, $hours, $is_paid, $planPrice, $pending_amount, $paid_date,$payment_mode);
                    \Log::info('Learner detail created', [
                        'learner_id' => $learnerData->id,
                        'plan' => $plan,
                        'status' => $status,
                        'plan_type' => $planType,
                        'seat' => $seat,
                    ]);
                   
                }else {
                    \Log::info('seat is not occupied Learner create', [ 'status1' => $status]);
                    // If seat is not occupied, directly create learner
                    $learner = $this->createLearner($data, $hours, $dob, $payment_mode, $status, $plan, $planType, $seat, $start_date, $endDate, $joinDate, $is_paid, $planPrice, $pending_amount, $paid_date);
                }
            }
        } else {
            \Log::info('When Status : 0 Previously Paid Seat info : Leaner', [ 'status0' => $status]);
            // Handling non-active status (status != 1)
            $exist_check = Learner::where('library_id', Auth::user()->id)
                ->where('email', trim($data['email']))
                ->exists();
        
            if (Learner::where('library_id', Auth::user()->id)
                ->where('email', trim($data['email']))
                ->where('status', 1)
                ->exists()) {
                \Log::info('You are already active');
                $invalidRecords[] = array_merge($data, ['error' => 'You are already active']);
                return;
            } elseif ($exist_check) {
                // Check if learner exists and update data
                $already_data = LearnerDetail::where('plan_start_date', $start_date)->exists();
                $learnerData = Learner::where('library_id', Auth::user()->id)
                    ->where('email', trim($data['email']))
                    ->first();
                if ($already_data) {
                
                    // Update existing learner and learner detail
                    $this->updateLearner($learnerData, $data, $dob, $hours, $payment_mode, $status, $plan, $planType, $seat, $start_date, $endDate, $joinDate, $is_paid);
                }
                if ($learnerData) {
                    \Log::info('Check if learner detaill exists with status 0 then update the details');
                    // Update existing learner and learner detail
                    $this->createLearnerDetail($learnerData->id, $plan,$status, $planType, $seat, $data, $start_date, $endDate, $joinDate, $hours, $is_paid, $planPrice, $pending_amount, $paid_date,$payment_mode);
                } 
            } else {
                if (empty($data['name']) || empty($data['email']) || empty($data['mobile']) || empty($hours) || empty($seat) || empty($start_date) || empty($planPrice->price)) {
                    $invalidRecords[] = array_merge($data, ['error' => 'Missing essential data for creating learner']);
                    return;
                }
                \Log::info('Insert New Learner Info if Learner is not exists in DB Previously');
                // Create a new learner if they dont exist
                $learner = $this->createLearner($data, $hours, $dob, $payment_mode, $status, $plan, $planType, $seat, $start_date, $endDate, $joinDate, $is_paid, $planPrice, $pending_amount, $paid_date);
            }
        }
   
      
        $successRecords[] = $data;
    }

    public function exportCsv()
    {
        try {
            // Retrieve invalid records from session
            $invalidRecords = session('invalidRecords', []);

            // Check if there are invalid records
            if (empty($invalidRecords)) {
                return redirect()->back()->with('error', 'No invalid records found for export.');
            }

            // Set headers for CSV
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="invalid_records.csv"',
            ];

            // Callback for streaming the CSV
            $callback = function () use ($invalidRecords) {
                $file = fopen('php://output', 'w');

                // Check if file opens successfully
                if ($file === false) {
                    throw new \Exception('Unable to open the file for writing.');
                }

                // Set CSV headers
                $headerRow = array_keys(reset($invalidRecords));
                fputcsv($file, $headerRow);

                // Write each invalid record to the CSV
                foreach ($invalidRecords as $record) {
                    fputcsv($file, $record);
                }

                fclose($file);
            };

            return new StreamedResponse($callback, 200, $headers);

        } catch (\Exception $e) {
            // Catch and handle export-related errors
            return redirect()->back()->with('error', 'Failed to export CSV: ' . $e->getMessage());
        }
    }

    function createLearner($data, $hours, $dob, $payment_mode, $status, $plan, $planType, $seat, $start_date, $endDate, $joinDate, $is_paid, $planPrice, $pending_amount, $paid_date) {
   
        DB::beginTransaction();
    
        try {
            // Create learner entry
            $learner = Learner::create([
                'library_id' => Auth::user()->id,
                'name' => trim($data['name']),
                'email' => trim($data['email']),
                'password' => bcrypt(trim($data['mobile'])),
                'mobile' => trim($data['mobile']),
                'dob' => $dob,
                'hours' => trim($hours),
                'seat_no' => trim($data['seat_no']),
                
                'address' => trim($data['address']),
                'status' => $status,
            ]);
    
            // Create learner detail entry
            $learner_detail = LearnerDetail::create([
                'learner_id' => $learner->id,
                'plan_id' => $plan->id,
                'plan_type_id' => $planType->id,
                'plan_price_id' => trim($data['plan_price']),
                'plan_start_date' => $start_date,
                'plan_end_date' => $endDate,
                'join_date' => $joinDate,
                'hour' => $hours,
                'seat_id' => $seat->id,
                'library_id' => Auth::user()->id,
                'payment_mode' => $payment_mode,
                'is_paid' => $is_paid,
                'status' => $status,
            ]);
    
            // Create learner transaction entry
            LearnerTransaction::create([
                'learner_id' => $learner->id,
                'library_id' => Auth::user()->id,
                'learner_detail_id' => $learner_detail->id,  // Corrected column name
                'total_amount' => $planPrice->price,
                'paid_amount' => trim($data['paid_amount']),
                'pending_amount' => $pending_amount,
                'paid_date' => $paid_date,
                'is_paid' => 1,
            ]);
    
            // Commit the transaction if all inserts succeed
            DB::commit();
    
            // Update seat availability and learner data
            $this->seat_availablity_update_now($seat->id, $planType->id);
            $this->dataUpdateNow($learner->id);
    
        } catch (\Exception $e) {
            // Rollback transaction on failure
            DB::rollBack();
    
            // Log the error
            \Log::error('Error in createLearnerDetail: ' . $e->getMessage());
    
            // Re-throw the exception to handle it further up
            throw $e;
        }
    }
    

    function createLearnerDetail($learner_id, $plan, $status, $planType, $seat, $data, $start_date, $endDate, $joinDate, $hours, $is_paid, $planPrice, $pending_amount, $paid_date,$payment_mode)
    {
        
        DB::beginTransaction();

        try {
             // update learner  entry
            Learner::where('id', $learner_id)->update([
                'mobile' => trim($data['mobile']),
                'hours' => trim($hours),
                'seat_no' => trim($data['seat_no']),
                'address' => trim($data['address']),
                'status' => $status,
            ]);
            // Create learner detail entry
            $learner_detail = LearnerDetail::create([
                'learner_id' => $learner_id,
                'plan_id' => $plan->id,
                'plan_type_id' => $planType->id,
                'plan_price_id' => trim($data['plan_price']),
                'plan_start_date' => $start_date,
                'plan_end_date' => $endDate,
                'join_date' => $joinDate,
                'hour' => $hours,
                'seat_id' => $seat->id,
                'library_id' => Auth::user()->id,
                'payment_mode' => $payment_mode,
                'is_paid' => $is_paid,
                'status' => $status,
            ]);

            // Create learner transaction entry
            LearnerTransaction::create([
                'learner_id' => $learner_id,
                'library_id' => Auth::user()->id,
                'learner_detail_id' => $learner_detail->id,
                'total_amount' => $planPrice->price,
                'paid_amount' => trim($data['paid_amount']),
                'pending_amount' => $pending_amount,
                'paid_date' => $paid_date,
                'is_paid' => 1,
            ]);

        
            DB::commit();

            $this->seat_availablity_update_now($seat->id, $planType->id);
            $this->dataUpdateNow($learner_id);

        } catch (\Exception $e) {
         
            DB::rollBack();

            \Log::error('Error in createLearnerDetail: ' . $e->getMessage());

            throw $e;
        }
    }
    
    function updateLearner($learnerData, $data, $dob, $hours, $payment_mode, $status, $plan, $planType, $seat, $start_date, $endDate, $joinDate, $is_paid) {
       
        Learner::where('id', $learnerData->id)->update([
            'mobile' => trim($data['mobile']),
            'dob' => $dob,
            'hours' => trim($hours),
            'seat_no' => trim($data['seat_no']),
            'address' => trim($data['address']),
            'status' => $status,
        ]);
    
        LearnerDetail::where('learner_id', $learnerData->id)
            ->where('plan_start_date', $start_date)
            ->update([
                'plan_id' => $plan->id,
                'plan_type_id' => $planType->id,
                'plan_price_id' => trim($data['plan_price']),
                'plan_start_date' => $start_date,
                'plan_end_date' => $endDate,
                'join_date' => $joinDate,
                'hour' => $hours,
                'seat_id' => $seat->id,
                'is_paid' => $is_paid,
                'status' => $status,
                'payment_mode' => $payment_mode,
            ]);
            $this->seat_availablity_update_now($seat->id,$planType->id);
            $this->dataUpdateNow($learnerData->id);
    }

    function seat_availablity_update_now($seat_id,$plan_type_id){
        $seat = Seat::where('id',$seat_id)->first();
                 
        $available=5;
        $day_type_id=PlanType::where('id',$plan_type_id)->select('day_type_id')->first();
        
        if( $seat->is_available == 1 && $day_type_id->day_type_id==1 ){
           
            $available = 5;
        }elseif($seat->is_available == 1 && $day_type_id->day_type_id==2 ){
           
            $available = 2;
        }elseif($seat->is_available == 1 && $day_type_id->day_type_id==3 ){
           
            $available = 3;
        }elseif($seat->is_available == 1 && ($day_type_id->day_type_id==4 || $day_type_id->day_type_id==5 ||$day_type_id->day_type_id==6 || $day_type_id->day_type_id==7) ){
           
            $available = 4;
           
        }elseif($seat->is_available == 2 && $day_type_id->day_type_id==3){
           
            $available = 5;
        }elseif($seat->is_available == 2 && ($day_type_id->day_type_id==6 || $day_type_id->day_type_id==7)){
           
            $available = 4;
        }elseif($seat->is_available == 3 && ($day_type_id->day_type_id==4 || $day_type_id->day_type_id==5)){
           
            $available = 4;
        }elseif($seat->is_available == 3 && $day_type_id->day_type_id==2){
           
            $available = 5;
        }elseif($seat->is_available == 4 && ($day_type_id->day_type_id==2|| $day_type_id->day_type_id==3||$day_type_id->day_type_id==4 || $day_type_id->day_type_id==5 || $day_type_id->day_type_id==6 || $day_type_id->day_type_id==5)){
            $available = 4;
            
        }
        
        // Update seat availability
        $update=Seat::where('id',$seat_id)->update(['is_available' => $available]);
        
    }

    function dataUpdateNow($learner_id){
       
        $seats = Seat::get();
 
        foreach($seats as $seat){
            $total_hourse=Learner::where('library_id',Auth::user()->id)->where('status', 1)->where('seat_no',$seat->seat_no)->sum('hours');
           
            $updateseat=Seat::where('library_id',Auth::user()->id)->where('id', $seat->id)->update(['total_hours' => $total_hourse]);
        
        }
    
        $userUpdate = Learner::where('library_id',Auth::user()->id)->where('id',$learner_id)->where('status', 1)->first();
  
       
           $today = date('Y-m-d'); 
           $customerdatas=LearnerDetail::where('learner_id',$learner_id)->where('status',1)->get();
           $extend_days_data = Hour::where('library_id', Auth::user()->id)->first();
           $extend_day = $extend_days_data ? $extend_days_data->extend_days : 0;
           foreach($customerdatas as $customerdata){
                $planEndDateWithExtension = Carbon::parse($customerdata->plan_end_date)->addDays($extend_day);
                $current_date = Carbon::today();
                $hasFuturePlan = LearnerDetail::where('learner_id', $userUpdate->id)
                ->where('plan_end_date', '>', $current_date->copy()->addDays(5))->where('status',0)
                ->exists();
                $hasPastPlan = LearnerDetail::where('learner_id', $userUpdate->id)
                    ->where('plan_end_date', '<', $current_date->copy()->addDays(5))
                    ->exists();

                $isRenewed = $hasFuturePlan && $hasPastPlan;
                if ($planEndDateWithExtension->lte($today)) {
                    $userUpdate->update(['status' => 0]);
                    $customerdata->update(['status' => 0]);
                }elseif ($isRenewed) {
                    LearnerDetail::where('learner_id', $userUpdate->id)->where('plan_start_date', '<=', $today)->where('plan_end_date', '>', $current_date->copy()->addDays(5))->update(['status'=>1]);
                    LearnerDetail::where('learner_id', $userUpdate->id)->where('plan_end_date', '<', $today)->update(['status'=>0]);
                }else{
                    $userUpdate->update(['status' => 1]);
                    LearnerDetail::where('learner_id', $userUpdate->learner_id)->where('status',0)->where('plan_start_date','<=',$today)->where('plan_end_date','>',$today)->update(['status' => 1]);
                }
           }
           
      

       //seat table update
        $userS = $this->getLearnersByLibrary()->where('learners.status', 0)->leftJoin('plan_types', 'learner_detail.plan_type_id', '=', 'plan_types.id')->select('learners.*','plan_types.day_type_id')->get();
      
        foreach ($userS as $user) {
        
            $seatNo = $user->seat_no;
            $seat = Seat::where('library_id', auth()->user()->id)->where('seat_no', $seatNo)->first();
            
            $available = 1; 
            
            if ($seat->is_available == 5) {
                $available = 1;
            } elseif ($seat->is_available == 4 && ($user->day_type_id == 4 || $user->day_type_id==5 || $user->day_type_id==6 || $user->day_type_id==7)) {
                $available = 1;
            } elseif ($seat->is_available == 3 && $user->day_type_id == 3) {
                $available = 1;
            } elseif ($seat->is_available == 2 && $user->day_type_id == 2) {
                $available = 1;
            } elseif ($seat->is_available == 2 && $user->day_type_id == 3) {
                $available = 2;
            } elseif ($seat->is_available == 3 && $user->day_type_id == 2) {
                $available = 3;
            }elseif ($seat->is_available == 4 && $user->day_type_id == 3) {
                    $available = 4;
            } else {
                $available = 1;
            }
            
            Seat::where('library_id', auth()->user()->id)->where('seat_no', $seatNo)->update(['is_available' => $available]);
        }

        foreach($seats as $seat){
            Seat::where('library_id', auth()->user()->id)->where('id',$seat->id)->where('total_hours',0)->where('is_available','!=',1)->update(['is_available' => 1]);
   
        }
    }
    protected function parseDate($date)
    {
        $formats = ['d/m/Y', 'm/d/Y', 'Y-m-d', 'd-m-Y'];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $date)->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }
        }
        return false;
    }

    private function getPaymentMode($paymentMode)
    {
        return match ($paymentMode) {
            'Online' => 1,
            'Offline' => 2,
            'Paylater' => 3,
            default => 2,
        };
    }
    public function clearSession(Request $request)
    {
        $request->session()->forget('invalidRecords');
        
        return response()->json(['status' => 'success']);
    }
    // master create
    protected function validateMasterInsert($data, &$successRecords, &$invalidRecords, $library_id)
    {
       
      
        $validator = Validator::make($data, [
            'Operating_hour' => 'required|integer',
            'start_time' => ['required', function($attribute, $value, $fail) {
                if (!preg_match('/^(?:[01]?\d|2[0-3]):[0-5]\d$/', $value)) {
                    $fail($attribute.' must be a valid time (HH:MM format).');
                }
            }],
            'end_time' => ['required', function($attribute, $value, $fail) {
                if (!preg_match('/^(?:[01]?\d|2[0-3]):[0-5]\d$/', $value)) {
                    $fail($attribute.' must be a valid time (HH:MM format).');
                }
            }],
            'total_seat' => 'required|integer',
            'fullday_price' => 'required|integer',
            'halfday_price' => 'required|integer',
            'hourly_price' => 'required|integer',
            
        ]);
      
        if ($validator->fails()) {
          
            $errors = $validator->errors()->all();
        
            $errorMessages = implode(', ', $errors);
        
            $invalidRecords[] = array_merge($data, ['error' => $errorMessages]);
            return;
        }
        if (!trim($data['total_seat']) || trim($data['total_seat']) <= 0) {
            $invalidRecords[] = array_merge($data, ['error' => 'Invalid Seats']);
            return;
        }
       
        $libraryData = Library::where('id', $library_id)->first();

        if ($libraryData) {
            $seatLimit = ($libraryData->library_type == 1) ? 50 : (($libraryData->library_type == 2) ? 75 : null);
        
            if ($seatLimit !== null && trim($data['total_seat']) > $seatLimit) {
                $invalidRecords[] = array_merge($data, ['error' => 'Total seats not your Subscription according']);
                return;  
            }
        } else {
          
            $invalidRecords[] = array_merge($data, ['error' => 'Library not found']);
        }
        
        $start_time = Carbon::createFromFormat('H:i', trim($data['start_time']));
        $end_time = Carbon::createFromFormat('H:i', trim($data['end_time']));
       
        if ($end_time->lessThan($start_time)) {
            $invalidRecords[] = array_merge($data, ['error' => 'End time must be later than start time.']);
            return; 
        }
        
        $totalHours = $start_time->diffInHours($end_time);
        
        if ($totalHours != trim($data['Operating_hour'])) {
           
            $invalidRecords[] = array_merge($data, ['error' => 'Operating hour does not match the difference between start and end times.']);
            return;
        }
       
        // Using database transaction for atomic operations
        DB::transaction(function () use ($data, $library_id, $start_time, $end_time, $totalHours,&$invalidRecords,&$successRecords) {
            // Update or create the operating hours
           $hourData= Hour::withoutGlobalScopes()->updateOrCreate(
                ['library_id' => $library_id],
                ['hour' => trim($data['Operating_hour']), 'extend_days' => trim($data['extend_day']) ?? null]
            );
            if ($hourData) {
                
                $successRecords[] = array_merge($data, ['success' => 'Operating Hour added successfully']);
            } else {
                $invalidRecords[] = array_merge($data, ['error' => 'Failed to add/update Operating Hour']);
            }
    
            // Define slot configurations
            $slots = $this->defineSlots($start_time, $end_time, $totalHours);
    
            // Check user permissions and handle slot updates
            $this->handleSlotUpdates($slots, $library_id, $invalidRecords, $data,$successRecords);
    
            // Define plans
            $plans = [
                ['name' => '1 MONTHS', 'plan_id' => 1],
                ['name' => '3 MONTHS', 'plan_id' => 3],
                ['name' => '6 MONTHS', 'plan_id' => 6],
                ['name' => '12 MONTHS', 'plan_id' => 12],
            ];
    
            // Handle plans updates
            $this->handlePlanUpdates($plans, $library_id,$invalidRecords,$successRecords);
    
            // Handle price updates
            $this->handlePlanPrices($library_id, trim($data['fullday_price']), trim($data['halfday_price']), trim($data['hourly_price']));
            if( Seat::where('library_id', $library_id)->count() < trim($data['total_seat'])){
                $this->handelSeats($library_id,trim($data['total_seat']));
            }
           
            $this->expenseAdd($library_id);
        });
        
    }
    
    // Function to define plantype
    private function defineSlots($start_time, $end_time, $totalHours)
    {
      
        return [
            ['type_id' => 1, 'name' => 'Full Day', 'start_time' => $start_time, 'end_time' => $end_time, 'slot_hours' => $totalHours],
            ['type_id' => 2, 'name' => 'First Half', 'start_time' => $start_time, 'end_time' => $start_time->copy()->addHours($totalHours / 2), 'slot_hours' => $totalHours / 2],
            ['type_id' => 3, 'name' => 'Second Half', 'start_time' => $start_time->copy()->addHours($totalHours / 2), 'end_time' => $end_time, 'slot_hours' => $totalHours / 2],
            ['type_id' => 4, 'name' => 'Hourly Slot 1', 'start_time' => $start_time, 'end_time' => $start_time->copy()->addHours($totalHours / 4), 'slot_hours' => $totalHours / 4],
            ['type_id' => 5, 'name' => 'Hourly Slot 2', 'start_time' => $start_time->copy()->addHours($totalHours / 4), 'end_time' => $start_time->copy()->addHours(($totalHours / 4) * 2), 'slot_hours' => $totalHours / 4],
            ['type_id' => 6, 'name' => 'Hourly Slot 3', 'start_time' => $start_time->copy()->addHours(($totalHours / 4) * 2), 'end_time' => $start_time->copy()->addHours(($totalHours / 4) * 3), 'slot_hours' => $totalHours / 4],
            ['type_id' => 7, 'name' => 'Hourly Slot 4', 'start_time' => $start_time->copy()->addHours(($totalHours / 4) * 3), 'end_time' => $end_time, 'slot_hours' => $totalHours / 4],
        ];
    }
    
    // Function to handle plantype updates
    private function handleSlotUpdates($slots, $library_id, &$invalidRecords, $data,&$successRecords)
    {

       
        Log::info('Starting handleSlotUpdates', ['library_id' => $library_id, 'slots' => $slots]);

        $user = Library::withoutGlobalScopes()->find($library_id);
        Log::info('User fetched', ['user' => $user]);

        foreach ($slots as $slot) {
            Log::info('Processing slot', ['slot' => $slot]);

            $hasPermission = true; 

            if ($slot['type_id'] == 1 && !$user->can('has-permission', 'Full Day')) {
                $hasPermission = false;
            } elseif ($slot['type_id'] == 2 && !$user->can('has-permission', 'First Half')) {
                $hasPermission = false;
            } elseif ($slot['type_id'] == 3 && !$user->can('has-permission', 'Second Half')) {
                $hasPermission = false;
            } elseif ($slot['type_id'] == 4 && !$user->can('has-permission', 'Hourly Slot 1')) {
                $hasPermission = false;
            } elseif ($slot['type_id'] == 5 && !$user->can('has-permission', 'Hourly Slot 2')) {
                $hasPermission = false;
            } elseif ($slot['type_id'] == 6 && !$user->can('has-permission', 'Hourly Slot 3')) {
                $hasPermission = false;
            } elseif ($slot['type_id'] == 7 && !$user->can('has-permission', 'Hourly Slot 4')) {
                $hasPermission = false;
            }
            if (!$hasPermission) {
                // $invalidRecords[] = array_merge($data, ['error' => 'No permission for slot ' . $slot['type_id']]);
                continue; 
            }

            $start_time_new = Carbon::parse($slot['start_time'])->format('H:i');
            $end_time_new = Carbon::parse($slot['end_time'])->format('H:i');
            Log::info('Parsed time', ['start_time_new' => $start_time_new, 'end_time_new' => $end_time_new]);
           
            // Update or create plan type
            $planType=PlanType::withoutGlobalScopes()->updateOrCreate(
                ['library_id' => $library_id, 'day_type_id' => $slot['type_id']],
                [
                    'name' => $slot['name'],
                    'start_time' => $start_time_new,
                    'end_time' => $end_time_new,
                    'slot_hours' => $slot['slot_hours'],
                    'image'=>'public/img/booked.png',
                ]
            );
            if ($planType) {
                $successRecords[] = array_merge($data, ['success' => 'Plan type updated or created']);
            } else {
                $invalidRecords[] = array_merge($data, ['error' => 'Failed to update or create plan type']);
            }
    
            Log::info('Plan type updated or created', ['slot' => $slot]);
            
        }
       
        Log::info('handleSlotUpdates finished successfully.');
    }

    
    // Function to handle plan updates
    private function handlePlanUpdates($plans, $library_id, &$invalidRecords, &$successRecords)
    {
        foreach ($plans as $plan) {
            try {
                // Update or create a plan based on library_id and plan_id
                $updatedPlan = Plan::withoutGlobalScopes()->updateOrCreate(
                    ['library_id' => $library_id, 'plan_id' => $plan['plan_id']],
                    ['name' => $plan['name']]
                );
    
                // If plan is successfully created or updated, add to success records
                if ($updatedPlan) {
                    $successRecords[] = [
                        'library_id' => $library_id,
                        'plan_id' => $plan['plan_id'],
                        'status' => 'success',
                        'message' => 'Plan updated or created successfully'
                    ];
                } else {
                    // If something went wrong, track it as a failure
                    $invalidRecords[] = [
                        'library_id' => $library_id,
                        'plan_id' => $plan['plan_id'],
                        'status' => 'error',
                        'message' => 'Failed to update or create plan'
                    ];
                }
            } catch (\Exception $e) {
                // Handle any exceptions and log the error
                Log::error('Error updating or creating plan', ['plan' => $plan, 'error' => $e->getMessage()]);
                
                // Add to invalid records for later use
                $invalidRecords[] = [
                    'library_id' => $library_id,
                    'plan_id' => $plan['plan_id'],
                    'status' => 'error',
                    'message' => 'Exception: ' . $e->getMessage()
                ];
            }
        }
    }
    
    
    // Function to handle price updates
    private function handlePlanPrices($library_id, $fullday_price, $halfday_price, $hourly_price)
    {
       
        $plans_prices = Plan::withoutGlobalScopes()->where('library_id', $library_id)->get();
        $plantype_prices = PlanType::withoutGlobalScopes()->where('library_id', $library_id)->get();
        
        foreach ($plans_prices as $plans_price) {
            foreach ($plantype_prices as $plantype_price) {
                // Initialize price variable
                $price = 0;

                // Calculate prices based on the type of plan
                if ($plantype_price->day_type_id == 1) {
                    $price = $fullday_price * $plans_price->plan_id;
                } elseif ($plantype_price->day_type_id == 2 || $plantype_price->day_type_id == 3) {
                    $price = $halfday_price * $plans_price->plan_id;
                } elseif (in_array($plantype_price->day_type_id, [4, 5, 6, 7])) {
                    $price = $hourly_price * $plans_price->plan_id;
                }

                // Check if the plan_type_id exists before inserting
                if (PlanType::withoutGlobalScopes()->where('id', $plantype_price->id)->exists()) {
                    // Update or create plan type price
                    PlanPrice::withoutGlobalScopes()->updateOrCreate(
                        ['library_id' => $library_id, 'plan_id' => $plans_price->id, 'plan_type_id' => $plantype_price->id],
                        ['price' => $price]
                    );
                } else {
                    Log::warning("Attempted to insert price for non-existing plan type id: " . $plantype_price->id);
                }
            }
        }
    }

    private function handelSeats($library_id, $total_seats)
    {
        // Get the current seat count and the highest seat number for the library
        $lastSeatNo = Seat::withoutGlobalScopes()->where('library_id', $library_id)
            ->orderBy('seat_no', 'desc')
            ->value('seat_no');
    
        $current_seat_count = Seat::withoutGlobalScopes()->where('library_id', $library_id)->count();
    
        // If current seats are less than total seats, add missing seats
        if ($current_seat_count < $total_seats) {
            $startSeatNo = $lastSeatNo ? $lastSeatNo + 1 : 1; 
            $seatsToAdd = [];
          
            // Calculate how many seats need to be added
            $seatsToAddCount = $total_seats - $current_seat_count;
    
            for ($i = 0; $i < $seatsToAddCount; $i++) {
                $seatsToAdd[] = [
                    'seat_no' => $startSeatNo + $i,
                    'library_id' => $library_id,
                    'is_available' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
    
            // Insert new seats into the database
            Seat::withoutGlobalScopes()->where('library_id', $library_id)->insert($seatsToAdd);
        }else{
            return;
        }
    
        // // If current seats are more than total seats, delete the excess seats
        // if ($current_seat_count > $total_seats) {
           
        //     // Get the excess seat count to be removed
        //     $seatsToRemoveCount = $current_seat_count - $total_seats;
    
        //     // Find the seat numbers to delete, ordering by seat_no in descending order
        //     Seat::withoutGlobalScopes()->where('library_id', $library_id)->where('library_id', $library_id)
        //         ->orderBy('seat_no', 'desc')
        //         ->take($seatsToRemoveCount)
        //         ->delete();
        // }
    }

    private function expenseAdd($library_id){
        $data=['Electricity Bill','Water Camper','Internet Wi-Fi','Papers','Repair & Maintenance','Tea & Snacks','Petrol','Flex Oreinting'];
        foreach ($data as $expenseName) {
            Expense::withoutGlobalScopes()->create([
                'library_id' => $library_id,
                'name' => $expenseName
            ]);
        }
    }

    public function renewConfigration(){
        $library_id=Auth::user()->id;
        $today = date('Y-m-d');
        $today_renew = LibraryTransaction::where('library_id', Auth::user()->id)
            ->where('is_paid', 1)
            ->where('status', 0)
            ->where('start_date', '<=', $today)->first();
        if($today_renew){
            Library::where('id',$library_id)->update([
                'library_type'=>$today_renew->subscription
    
            ]);
        }
       
    
        $user = Auth::user();
        $planType=PlanType::withoutGlobalScopes()->where('library_id', $library_id)->first();
        
        if($planType){
            $start_time = Carbon::parse($planType->start_time);
            $end_time = Carbon::parse($planType->end_time);
            $totalHours = $planType->slot_hours;

            $slots = $this->defineSlots($start_time, $end_time, $totalHours);
           
            foreach ($slots as $slot) {
               
                $hasPermission = true; 
              
                if ($slot['type_id'] == 1 && !$user->can('has-permission', 'Full Day')) {
                    $hasPermission = false;
                } elseif ($slot['type_id'] == 2 && !$user->can('has-permission', 'FirstHalf')) {
                    $hasPermission = false;
                } elseif ($slot['type_id'] == 3 && !$user->can('has-permission', 'SecondHalf')) {
                    $hasPermission = false;
                } elseif ($slot['type_id'] == 4 && !$user->can('has-permission', 'Hourly1')) {
                    $hasPermission = false;
                } elseif ($slot['type_id'] == 5 && !$user->can('has-permission', 'Hourly2')) {
                    $hasPermission = false;
                } elseif ($slot['type_id'] == 6 && !$user->can('has-permission', 'Hourly3')) {
                    $hasPermission = false;
                } elseif ($slot['type_id'] == 7 && !$user->can('has-permission', 'Hourly4')) {
                    $hasPermission = false;
                }
                $existPlantype=PlanType::withoutGlobalScopes()->where('library_id',$library_id)->where('day_type_id',$slot['type_id'])->first();
                $id = $existPlantype ? $existPlantype->id : null;
                $data = PlanType::withTrashed()->find($id);
                if ($existPlantype) {
                    // If the plan type exists but is soft-deleted, restore it
                    if ($existPlantype->trashed()) {
                        $data->restore();
                    }
                }
              
                if (!$hasPermission) {
                    if ($existPlantype) {
                        $existPlantype->delete(); // Soft-delete if no permission
                    }
                } else{
                    $start_time_new = Carbon::parse($slot['start_time'])->format('H:i');
                    $end_time_new = Carbon::parse($slot['end_time'])->format('H:i');
                    Log::info('Parsed time', ['start_time_new' => $start_time_new, 'end_time_new' => $end_time_new]);

                    // Update or create plan type
                    PlanType::withoutGlobalScopes()->updateOrCreate(
                        ['library_id' => $library_id, 'day_type_id' => $slot['type_id']],
                        [
                            'name' => $slot['name'],
                            'start_time' => $start_time_new,
                            'end_time' => $end_time_new,
                            'slot_hours' => $slot['slot_hours'],
                            'image'=>'public/img/booked.png',
                        ]
                    );

                    Log::info('Plan type updated or created', ['slot' => $slot]);

                }

               
            }
            
            $plans_prices = Plan::withoutGlobalScopes()->where('library_id', $library_id)->withTrashed()->get();
            $plantype_prices = PlanType::withoutGlobalScopes()->where('library_id', $library_id)->withTrashed()->get();
            $onemonthplan = Plan::withoutGlobalScopes()->where('library_id', $library_id)->where('plan_id', 1)->first();
    
            foreach ($plans_prices as $plans_price) {
                foreach ($plantype_prices as $plantype_price) {
    
                    // Fetch the full-day price for the current plan and plan type
                    $fullday_price = PlanPrice::withoutGlobalScopes()->where('library_id', $library_id)->where('plan_type_id', $planType->id)
                                            ->where('plan_id', $onemonthplan->id)
                                            ->withTrashed()
                                            ->first();
                    
                    $price = 0;
    
                    // Calculate prices based on the type of plan
                    if ($plantype_price->day_type_id == 1) {
                        $price = $fullday_price->price * $plans_price->plan_id;
                    } elseif ($plantype_price->day_type_id == 2 || $plantype_price->day_type_id == 3) {
                        $price = ($fullday_price->price * $plans_price->plan_id) / 2;
                    } elseif (in_array($plantype_price->day_type_id, [4, 5, 6, 7])) {
                        $price = ($fullday_price->price * $plans_price->plan_id) / 4;
                    }
                    
                    
                    $existing_price = PlanPrice::withoutGlobalScopes()->where('library_id', $library_id)->where('plan_type_id', $plantype_price->id)
                                            ->where('plan_id', $plans_price->id)
                                            ->withTrashed()
                                            ->first();
                    
                    if ($existing_price) {
                        // If price exists and plan type is not deleted
                        if (!$plantype_price->trashed()) {
                            // If the existing price is deleted, restore it
                            if ($existing_price->trashed()) {
                                $existing_price->restore();
                            }
                            // Update the price
                            $existing_price->price = $price;
                            $existing_price->save();
                        } else {
                            // If plan type is deleted, ensure price is deleted
                            $existing_price->delete();
                        }
                    } else {
                        // If the plan type is not deleted and price doesn't exist, insert new price
                        if (!$plantype_price->trashed()) {
                            PlanPrice::create([
                                'library_id' =>$library_id,
                                'plan_type_id' => $plantype_price->id,
                                'plan_id'      => $plans_price->id,
                                'price'        => $price,
                            ]);
                        }
                    }
                }
            }

            $this->statusUpdate();
            return response()->json(['message' => 'Plan Configration successfully renewed!'], 200);
        }

        
        return response()->json(['error' => 'Plan not found!'], 404);

    }

    protected function statusUpdate(){
        $today = date('Y-m-d');
        Library::where('id',Auth::user()->id)->update([
            'is_paid'=>1,
            'status'=>1

        ]);
        LibraryTransaction::where('library_id', Auth::user()->id)
            ->where('is_paid', 1)
            ->where('end_date', '>=', $today)->update([
              
                'status'=>1,
                'is_paid'=>1
    
            ]);
        LibraryTransaction::where('library_id', Auth::user()->id)
        ->where('is_paid', 1)
        ->where('end_date', '<', $today)
        ->where('start_date', '<', $today)->update([
            
            'status'=>0

        ]);
    }

    // learner export functionality
    public function exportLearnerCSV()
    {
        Log::info("Export CSV function called.");  // Track function call start
        
        $fileName = 'learners.csv';
        
        try {
            // Create the streamed response
            $response = new StreamedResponse(function () {
                $handle = fopen('php://output', 'w');
                Log::info("CSV file opened for output.");

                // Set CSV headers
                fputcsv($handle, ['name', 'email', 'mobile', 'seat_no', 'dob', 'address', 'plan', 'plan_type', 'plan_price', 'join_date', 'start_date', 'end_date', 'paid_amount', 'paid_date', 'payment_mode']);
                Log::info("CSV headers written.");

                // Fetch learners data
                $learners = Learner::where('library_id', Auth::user()->id)->with(['learnerDetails', 'learnerTransactions'])->get();
                Log::info("Fetched learners data.", ['count' => $learners->count()]);

                foreach ($learners as $learner) {
                    if ($learner->learnerDetails->isEmpty() && $learner->learnerTransactions->isEmpty()) {
                        fputcsv($handle, [$learner->name, $learner->email, $learner->mobile, $learner->seat_no, $learner->dob, $learner->address]);
                        Log::info("Wrote learner without details or transactions.", ['learner' => $learner->id]);
                    } else {
                        foreach ($learner->learnerDetails as $detail) {
                            foreach ($learner->learnerTransactions as $transaction) {
                                fputcsv($handle, [
                                    $learner->name,
                                    $learner->email,
                                    $learner->mobile,
                                    $learner->seat_no,
                                    $learner->dob,
                                    $learner->address,
                                    $detail->plan->name, 
                                    $detail->planType->name, 
                                    $detail->plan_price_id,
                                    $detail->join_date,
                                    $detail->plan_start_date,
                                    $detail->plan_end_date,
                                    $transaction->paid_amount,
                                    $transaction->paid_date,
                                    $learner->payment_mode == 1 ? 'Online' : ($learner->payment_mode == 2 ? 'Offline' : 'Pay Later'),
                                ]);
                                Log::info("Wrote learner with details and transaction.", ['learner' => $learner->id]);
                            }
                        }
                    }
                }

                fclose($handle);  // Close the output stream
                Log::info("CSV file output closed.");
            });

            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            Log::info("Response headers set.");

            return $response;

        } catch (\Exception $e) {
            // Catch any exception, log it, and throw an error
            Log::error("Error occurred in CSV export.", ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to export CSV'], 500);
        }
    }

   
    
    
    

}
