<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Advertises;
use App\Models\Announces;
use App\Models\Attendance;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function getAdvertise()
    {
        try {
            $result = Advertises::orderBy('id','DESC')->take(5)->get();
            return response()->json([
                'code' => '00',
                'payload' => $result,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '400',
                'error' => 'internal server error', 'message' => $th,
            ], 403);
        }
    }

    public function getAnnouncement()
    {
        try {
            $result = Announces::orderBy('id', 'desc')
            ->first();
            return response()->json([
                'code' => '00',
                'payload' => $result,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '400',
                'error' => 'internal server error', 'message' => $th,
            ], 403);
        }
    }

    public function getAgenda()
    {
        try {
            $result = Attendance::select('activity')
                ->orderBy('id', 'desc')
                ->first();
            return response()->json([
                'code' => '00',
                'payload' => $result,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '400',
                'error' => 'internal server error', 'message' => $th,
            ], 403);
        }
    }
}
