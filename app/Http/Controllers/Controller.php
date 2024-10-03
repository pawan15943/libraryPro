<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Library;
use App\Models\LibraryTransaction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function generateReceipt(Request $request)
    {
        if($request->type=='library'){
            $data = LibraryTransaction::where('id', $request->id)->first();
            $library_data = Library::where('id', $data->library_id)->where('status', 1)->first();
        }
       
        
        $send_data = [
            'data' => $library_data,
            'transactiondate' => $data->transaction_date,
            'paid_amount' => $data->paid_amount,
            'payment_mode' => $data->payment_mode ? $data->payment_mode : 'Offline',
            'invoice_ref_no' => $data->transaction_id ? $data->transaction_id : 'NA',
            'total_amount' => $data->amount,
            'start_date' => $data->start_date,
            'end_date' => $data->end_date,
            'monthly_amount' => $data->amount,
            'month' => $data->month,
            'currency' => 'Rs.',
        ];

        // Generate the PDF without saving it on the server
        $pdf = PDF::loadView('recieptPdf', $send_data);

        return $pdf->download(time() . '_receipt.pdf');
    }
}
