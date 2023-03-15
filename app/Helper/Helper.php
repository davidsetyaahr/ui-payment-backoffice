<?php

use Illuminate\Support\Facades\Http;

class Helper
{
    public static function sendMessage($phone, $message)
    {
        $response = Http::post(env('URL_GATEWAY').'send-message', [
            'api_key' => env('API_GATEWAY'),
            'sender' => env('SENDER_PHONE'),
            'number' => $phone,
            'message' => $message,
        ]);
        return $response;
    }
}

?>