<?php
use Illuminate\Support\Facades\Log;

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
