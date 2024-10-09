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

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

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
        }
        if($request->type=='learner'){
            $data = LearnerTransaction::where('id', $request->id)->first();
          
            $user = Learner::where('id', $data->learner_id)->where('status', 1)->first();
            $transactionDate=$data->paid_date;
            $paymentMode='Offline';
            $total_amount=$data->total_amount;
            $learnerDeatail = LearnerDetail::where('id', $data->learner_deatail_id)
            ->with(['plan', 'planType'])
            ->first();
        
            if ($learnerDeatail) {
                $month = $learnerDeatail->plan ? $learnerDeatail->plan->plan_id : null; // Check if 
                $start_date = $learnerDeatail->plan_start_date;
                $end_date = $learnerDeatail->plan_end_date;
            } else {
            
                $month = null;
                $start_date = null;
                $end_date = null;
            }
        
        }
       
        
        $send_data = [
            'data' => $user,
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

    public function showUploadForm()
    {
        return view('library.csv'); // View to show the CSV upload form
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
        // Validate file input
        $request->validate([
            'csv_file' => 'required|file|mimes:csv',
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

        DB::transaction(function () use ($csvData, &$invalidRecords, &$successRecords) {
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
            session(['invalidRecords' => $invalidRecords]); // Persist invalid records in the session
            return redirect()->back()->with([
                'successCount' => count($successRecords),
                'autoExportCsv' => true, // This triggers the CSV download
            ]);
        }
    
        // Handle success case
        return redirect()->back()->with('successCount', count($successRecords));
    }
    

    protected function validateAndInsert($data, &$successRecords, &$invalidRecords)
    {
        
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
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
        $planType = PlanType::where('name', 'LIKE', trim($data['plan_type']))->first();
        $planPrice = PlanPrice::where('price', 'LIKE', trim($data['plan_price']))->first();
        if ((!$user->can('has-permission', 'FullDay') && $planType->day_type_id==1) || (!$user->can('has-permission', 'FirstHalf') && $planType->day_type_id==2) || (!$user->can('has-permission', 'SecondHalf') && $planType->day_type_id==3) || (!$user->can('has-permission', 'Hourly1') && $planType->day_type_id==4)|| (!$user->can('has-permission', 'Hourly2') && $planType->day_type_id==5)|| (!$user->can('has-permission', 'Hourly3') && $planType->day_type_id==6)|| (!$user->can('has-permission', 'Hourly4') && $planType->day_type_id==7)){
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
        $payment_mode = $this->getPaymentMode(trim($data['payment_mode']));
        $hours = $planType->slot_hours;
        $duration = trim($data['plan']) ?? 0;
        $joinDate = isset($data['join_date']) ? $this->parseDate(trim($data['join_date'])) : $start_date;
        if (isset($data['end_date'])) {
            $endDate = $this->parseDate(trim($data['end_date']));
        } else {
            $endDate = Carbon::parse($start_date)->addMonths($duration)->format('Y-m-d');
        }
        

        $pending_amount = $planPrice->price - trim($data['paid_amount']);
        $paid_date = isset($data['paid_date']) ? $this->parseDate(trim($data['paid_date'])) : $start_date;
        $status = $endDate > date('Y-m-d') ? 1 : 0;
        $is_paid = $pending_amount <= 0 ? 1 : 0;
        
        
       
        if ($status == 1) {
            // Check if the learner already exists with active status
            $alreadyLearner = Learner::where('library_id', Auth::user()->id)
                ->where('email', trim($data['email']))
                ->where('status', 1)
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
                        
                    $first_record = Hour::first();
                    $total_hour = $first_record ? $first_record->hour : null;
                    $hours = PlanType::where('id', $planType->id)->value('slot_hours');
        
                    // Check if total hours exceed allowed hours
                    if ((Learner::where('seat_no', trim($data['seat_no']))
                        ->where('learners.status', 1)
                        ->sum('hours') + $hours) > $total_hour) {
        
                        $invalidRecords[] = array_merge($data, ['error' => 'Your plan type exceeds the library total hours']);
                        return;
                    } else {
                        // Create new learner and associated records
                        $learner = $this->createLearner($data, $hours, $dob, $payment_mode, $status, $plan, $planType, $seat, $start_date, $endDate, $joinDate, $is_paid, $planPrice, $pending_amount, $paid_date);
                    }
                } else {
                    // If seat is not occupied, directly create learner
                    $learner = $this->createLearner($data, $hours, $dob, $payment_mode, $status, $plan, $planType, $seat, $start_date, $endDate, $joinDate, $is_paid, $planPrice, $pending_amount, $paid_date);
                }
            }
        } else {
            // Handling non-active status (status != 1)
            $exist_check = Learner::where('library_id', Auth::user()->id)
                ->where('email', trim($data['email']))
                ->exists();
        
            if (Learner::where('library_id', Auth::user()->id)
                ->where('email', trim($data['email']))
                ->where('status', 1)
                ->exists()) {
                
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
                } else {
                    // Create learner details for the existing learner
                    $this->createLearnerDetail($learnerData->id, $plan,$status, $planType, $seat, $data, $start_date, $endDate, $joinDate, $hours, $is_paid, $planPrice, $pending_amount, $paid_date);
                }
            } else {
                // Create a new learner if they don't exist
                $learner = $this->createLearner($data, $hours, $dob, $payment_mode, $status, $plan, $planType, $seat, $start_date, $endDate, $joinDate, $is_paid, $planPrice, $pending_amount, $paid_date);
            }
        }
      
        $successRecords[] = $data;
    }

    public function exportCsv()
    {
        $invalidRecords = session('invalidRecords', []);

        if (empty($invalidRecords)) {
            return redirect()->back()->with('error', 'No invalid records found for export.');
        }

        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="invalid_records.csv"'];

        $callback = function () use ($invalidRecords) {
            $file = fopen('php://output', 'w');

            // Set the headers for the CSV
            $headerRow = array_keys(reset($invalidRecords));
            fputcsv($file, $headerRow);

            // Write each record to the CSV
            foreach ($invalidRecords as $record) {
                fputcsv($file, $record);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
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

    
    function createLearner($data, $hours, $dob, $payment_mode, $status, $plan, $planType, $seat, $start_date, $endDate, $joinDate, $is_paid, $planPrice, $pending_amount, $paid_date) {
        $learner = Learner::create([
            'library_id' => Auth::user()->id,
            'name' => trim($data['name']),
            'email' => trim($data['email']),
            'password' => bcrypt(trim($data['mobile'])),
            'mobile' => trim($data['mobile']),
            'dob' => $dob,
            'hours' => trim($hours),
            'seat_no' => trim($data['seat_no']),
            'payment_mode' => $payment_mode,
            'address' => trim($data['address']),
            'status' => $status,
        ]);
    
        $this->createLearnerDetail($learner->id, $plan,$status, $planType, $seat, $data, $start_date, $endDate, $joinDate, $hours, $is_paid, $planPrice, $pending_amount, $paid_date);
        $this->seat_availablity_update_now($seat->id,$planType->id);
        $this->dataUpdateNow($learner->id);
        return $learner;
    }

    function createLearnerDetail($learner_id, $plan,  $status,$planType, $seat, $data, $start_date, $endDate, $joinDate, $hours, $is_paid, $planPrice, $pending_amount, $paid_date) {
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
            'is_paid' => $is_paid,
            'status' => $status,
        ]);
    
        LearnerTransaction::create([
            'learner_id' => $learner_id,
            'library_id' => Auth::user()->id,
            'learner_deatail_id' => $learner_detail->id,
            'total_amount' => $planPrice->price,
            'paid_amount' => trim($data['paid_amount']),
            'pending_amount' => $pending_amount,
            'paid_date' => $paid_date,
            'is_paid' => 1
        ]);
        $this->seat_availablity_update_now($seat->id,$planType->id);
        $this->dataUpdateNow($learner_id);
    }
    
    function updateLearner($learnerData, $data, $dob, $hours, $payment_mode, $status, $plan, $planType, $seat, $start_date, $endDate, $joinDate, $is_paid) {
        Learner::where('id', $learnerData->id)->update([
            'mobile' => trim($data['mobile']),
            'dob' => $dob,
            'hours' => trim($hours),
            'seat_no' => trim($data['seat_no']),
            'payment_mode' => $payment_mode,
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
    
        $userUpdate = Learner::where('library_id',Auth::user()->id)->where('learner_id',$learner_id)->where('status', 1)->first();
  
       
           $today = date('Y-m-d'); 
           $customerdatas=LearnerDetail::where('learner_id',$learner_id)->where('status',1)->get();
           $extend_days_data = Hour::where('library_id', Auth::user()->id)->first();
           $extend_day = $extend_days_data ? $extend_days_data->extend_days : 0;
           foreach($customerdatas as $customerdata){
                $planEndDateWithExtension = Carbon::parse($customerdata->plan_end_date)->addDays($extend_day);
                if ($planEndDateWithExtension->lte($today)) {
                    $userUpdate->update(['status' => 0]);
                    $customerdata->update(['status' => 0]);
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

    public function clearSession(Request $request)
    {
        $request->session()->forget('invalidRecords');
        
        return response()->json(['status' => 'success']);
    }

    

}
