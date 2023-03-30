<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\HistoryBilling;
use App\Models\PaymentBillDetail;
use App\Models\PaymentFromApp;
use App\Models\PaymentFromAppDetail;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

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
            $data = $query->paginate($request->perpage);
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

    public function listBill($studentId)
    {
        try {
            $tmp = PaymentBillDetail::join('student', 'student.id', 'payment_bill_detail.student_id')
                ->select('student.name', 'payment_bill_detail.*')
                ->where('payment_bill_detail.student_id', $studentId)
                ->get();
            $data = [];
            foreach ($tmp as $value) {
                $value->student_id = str_pad($value->student_id, 6, '0', STR_PAD_LEFT);
                array_push($data, $value);
            }
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

    public function checkout(Request $request)
    {
        try {
            $uniqCode = substr($request->total_payment, 0, -3) . rand(111, 999);
            $code = 'PB' . date('ymd') . rand(1, 9);

            $history = HistoryBilling::create([
                'amount' => $uniqCode,
                'unique_code' => $code,
                'created_by' => Auth::guard('parent')->user()->id,
            ]);
            for ($i = 0; $i < count($request->id_bill); $i++) {
                PaymentBillDetail::where('id', $request->id_bill[$i])
                    ->update([
                        'unique_code' => $code,
                        'status' => 'To Be Confirm'
                    ]);
            }
            $data = ([
                'total_pay' => $uniqCode,
                'id_transaction' => $code,
            ]);
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
            return $th;
        }
    }

    public function verifyPayment($transId)
    {
        try {
            $data = HistoryBilling::where('unique_code', $transId)->first();
            $student = PaymentBillDetail::join('student', 'student.id', 'payment_bill_detail.student_id')
                ->select('student.name')
                ->where('payment_bill_detail.unique_code', $transId)->first();
            $amount =  "Rp " . number_format($data->amount, 0, ',', '.');
            $message = $student->name . "melakukan pembayaran dengan nominal *" . $amount . "* dengan kode pembayaran *" . $data->unique_code . "*";

            $send = Helper::sendMessage(env('ADMIN_PHONE'), $message);

            if ($send) {
                return response()->json([
                    'code' => '00',
                    'payload' => 'Success',
                ], 200);
            } else {
                return response()->json([
                    'code' => '10',
                    'message' => 'Failed verify payment, please try again later',
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '400',
                'error' => 'internal server error',
                'message' => $th,
            ], 403);
        }
    }

    public function printInvoice($paymentId)
    {
        // $data = DB::select('select py.total, py.method, py.number, py.bank, py.trfdate, pd.id, pd.paymentid, pd.studentid, pd.voucherid, pd.category, pd.monthpay, SUM(pd.amount) as subtotal, s.name, p.program, pd.explanation
        // FROM paydetail pd
        // INNER JOIN student s ON pd.studentid = s.id
        // INNER JOIN price p ON s.priceid = p.id
        // INNER JOIN payment py ON pd.paymentid = py.id
        // WHERE pd.paymentid = ?
        // GROUP BY pd.studentid', [$paymentId]);
        $data = PaymentFromAppDetail::join('payment_from_apps as pfa', 'pfa.id', 'payment_from_app_details.payment_from_app_id')
            ->join('student as st', 'st.id', 'pfa.student_id')
            ->join('price as pr', 'pr.id', 'st.priceid')
            ->select('st.name', 'st.id as student_id', 'pr.program', 'payment_from_app_details.*')
            ->where('payment_from_app_details.payment_from_app_id', $paymentId)
            ->orderBy('payment_from_app_details.id', 'ASC')
            ->get();
        $detail = PaymentFromApp::where('id', $paymentId)->first();
        
        $fileName = "invoice_payment_" . $paymentId . ".pdf";
        
        $width = 5.5 / 2.54 * 72;
        $height = 18 / 2.54 * 72;
        $customPaper = array(0, 0, $height, $width);
        $pdf = PDF::loadview('report.print', ['data' => $data, 'detail' => $detail])->setPaper($customPaper, 'landscape');
        // return $pdf->download($fileName);
        return  $pdf->stream($fileName);
    }
}
