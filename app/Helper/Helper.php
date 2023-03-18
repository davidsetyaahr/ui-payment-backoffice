<?php

use Illuminate\Support\Facades\Http;

class Helper
{
    public static function sendMessage($phone, $message)
    {
        $response = Http::post(env('URL_GATEWAY') . 'send-message', [
            'api_key' => env('API_GATEWAY'),
            'sender' => env('SENDER_PHONE'),
            'number' => $phone,
            'message' => $message,
        ]);
        return $response;
    }

    public static function getGrade($score)
    {
        if (intval($score) < 50) {
            return 'E';
        } else if (intval($score) >= 50 && intval($score) <= 59) {
            return 'D';
        } else if (intval($score) >= 60 && intval($score) <= 69) {
            return 'C';
        } else if (intval($score) >= 70 && intval($score) <= 85) {
            return 'B';
        } else if (intval($score) >= 86 && intval($score) <= 100) {
            return 'A';
        }
    }
}
