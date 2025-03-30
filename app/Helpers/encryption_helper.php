<?php

use App\Models\LearnerTransaction;
use Illuminate\Support\Facades\Log;
use App\Models\Library;
use App\Models\Subscription;
use App\Models\LibraryTransaction;
use Illuminate\Support\Facades\Session;

if (!function_exists('encryptData')) {
    function encryptData($data)
    {
        $key = "gingerth1nksaasT";
        $cipher = "AES-128-CBC";
        $iv_size = openssl_cipher_iv_length($cipher);
        $IV = substr(md5($key), 0, $iv_size);
        $encrypted = openssl_encrypt($data, $cipher, $key, 0, $IV);
        return str_replace(["+", "/"], [" ", "*"], $encrypted);
    }
}

if (!function_exists('decryptData')) {
    function decryptData($data)
    {
        $key = "gingerth1nksaasT";
        $cipher = "AES-128-CBC";
        $iv_size = openssl_cipher_iv_length($cipher);
        $IV = substr(md5($key), 0, $iv_size);
        $data = str_replace([" ", "*"], ["+", "/"], $data);
        $decrypted=openssl_decrypt($data, $cipher, $key, 0, $IV);
        \Log::info("Decryption Successful: " . $data . " → " . $decrypted);
        return $decrypted;
    }
}

if (!function_exists('getLibraryData')) {
    function getLibraryData()
    {
        $id = Session::get('selected_library_id');
        \Log::info("selected_library_id " . $id);
        if (!$id) {
            return null; // No library selected
        }

        $library = Library::find($id);
        if (!$library) {
            return null; // Invalid library ID
        }

        $plan = Subscription::where('id', $library->library_type)->with('permissions')->first();
        $library_transaction = LibraryTransaction::withoutGlobalScopes()->where('library_id', $library->id)
            ->where('is_paid', 1)
            ->orderBy('created_at', 'DESC')
            ->with('subscription')
            ->first();
        $library_all_transaction = LibraryTransaction::withoutGlobalScopes()->where('library_id', $library->id) ->with('subscription')->get();

        return (object) [
            'library' => $library,
            'plan' => $plan,
            'latest_transaction' => $library_transaction,
            'all_transactions' => $library_all_transaction,
        ];
    }
}

if(!function_exists('learnerTransaction') ){
    function learnerTransaction($id,$detail_id)
    {
       $transaction=LearnerTransaction::where('learner_id',$id)->where('learner_detail_id',$detail_id)->first();
       return  $transaction;
    }
}