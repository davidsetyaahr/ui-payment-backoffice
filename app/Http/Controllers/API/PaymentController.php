<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentFromApp;
use App\Models\PaymentFromAppDetail;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function getHistory(Request $request, $studentId)
    {
        try {
            $query = [];
            $query = PaymentFromApp::orderBy('date', 'DESC');
            if ($request->start && $request->end) {
                $query = $query->whereBetween('date',  [$request->start, $request->end]);
            }
            $data = $query->paginate(10);
            return response()->json([
                'code' => '00',
                'payload' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '400',
                'error' => 'internal server error',
                'message' => $th,
            ], 403);
        }
    }

    public function getDetailHistory($idPayment)
    {
        try {
            $data = PaymentFromAppDetail::join('payment_from_apps as pfa', 'pfa.id', 'payment_from_app_details.payment_from_app_id')
                ->join('student as st', 'st.id', 'pfa.student_id')
                ->select('st.name', 'payment_from_app_details.*')
                ->where('payment_from_app_details.payment_from_app_id', $idPayment)
                ->orderBy('payment_from_app_details.id', 'ASC')
                ->get();
            return response()->json([
                'code' => '00',
                'payload' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '400',
                'error' => 'internal server error',
                'message' => $th,
            ], 403);
        }
    }
}
