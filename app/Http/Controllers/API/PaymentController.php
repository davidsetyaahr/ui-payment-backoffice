<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\HistoryBilling;
use App\Models\PaymentBill;
use App\Models\PaymentBillDetail;
use App\Models\PaymentFromApp;
use App\Models\Students;
use App\Models\PaymentFromAppDetail;
use App\Models\Price;
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
            $class = Students::join('price', 'student.priceid', 'price.id')->where('student.id', $studentId)->first();
            $query = HistoryBilling::join('payment_bill_detail as pbd', 'pbd.unique_code', 'history_billing.unique_code')
                ->select('history_billing.*')
                ->where('pbd.student_id', $studentId)
                ->distinct();

            if ($request->start && $request->end) {
                $query = $query->whereBetween('history_billing.created_at',  [$request->start . " 00:00", $request->end . " 23:59"]);
            }
            $data = $query->paginate($request->perpage);
            $class = Students::join('price', 'price.id', 'student.priceid')
                ->select('price.program')
                ->where('student.id', $studentId)->first();
            return response()->json([
                'code' => '00',
                'class' => $class->program,
                'payload' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return $th;
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
            // $data = PaymentFromAppDetail::join('payment_from_apps as pfa', 'pfa.id', 'payment_from_app_details.payment_from_app_id')
            //     ->join('student as st', 'st.id', 'pfa.student_id')
            //     ->select('st.name', 'payment_from_app_details.*')
            //     ->where('payment_from_app_details.payment_from_app_id', $idPayment)
            //     ->orderBy('payment_from_app_details.id', 'ASC')
            //     ->get();
            $data = PaymentBillDetail::join('student as st', 'st.id', 'payment_bill_detail.student_id')
                ->select('payment_bill_detail.*', 'st.name')
                ->where('payment_bill_detail.unique_code', $idPayment)->get();
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
            $class = Students::join('price', 'student.priceid', 'price.id')->where('student.id', $studentId)->first();
            $tmp = PaymentBillDetail::join('student', 'student.id', 'payment_bill_detail.student_id')
                ->select('student.name', 'payment_bill_detail.*')
                ->where('payment_bill_detail.student_id', $studentId)
                ->where('payment_bill_detail.status', 'Waiting')
                ->get();
            $data = [];
            foreach ($tmp as $value) {
                $value->student_id = str_pad($value->student_id, 6, '0', STR_PAD_LEFT);
                array_push($data, $value);
            }
            $class = Students::join('price', 'price.id', 'student.priceid')
                ->select('price.program')
                ->where('student.id', $studentId)->first();
            return response()->json([
                'code' => '00',
                'class' => $class->program,
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
            PaymentBillDetail::where('unique_code', $transId)
                ->update([
                    'status' => 'To Be Confirm'
                ]);
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
        // $data = PaymentFromAppDetail::join('payment_from_apps as pfa', 'pfa.id', 'payment_from_app_details.payment_from_app_id')
        //     ->join('student as st', 'st.id', 'pfa.student_id')
        //     ->join('price as pr', 'pr.id', 'st.priceid')
        //     ->select('st.name', 'st.id as student_id', 'pr.program', 'payment_from_app_details.*')
        //     ->where('payment_from_app_details.payment_from_app_id', $paymentId)
        //     ->orderBy('payment_from_app_details.id', 'ASC')
        //     ->get();
        $data = PaymentBillDetail::join('student as st', 'st.id', 'payment_bill_detail.student_id')
            ->join('price as pr', 'pr.id', 'st.priceid')
            ->select('payment_bill_detail.*', 'st.name', 'pr.program')
            ->where('payment_bill_detail.unique_code', $paymentId)->get();
        $detail = HistoryBilling::where('unique_code', $paymentId)->first();

        // return $data;
        $fileName = "invoice_payment_" . $paymentId . ".pdf";

        $width = 5.5 / 2.54 * 72;
        $height = 18 / 2.54 * 72;
        $customPaper = array(0, 0, $height, $width);
        $pdf = PDF::loadview('report.print', ['data' => $data, 'detail' => $detail])->setPaper($customPaper, 'landscape');
        return $pdf->download($fileName);
        // return  $pdf->stream($fileName);
    }

    public function getBillMonth($studentId)
    {
        try {
            $detailPaid = PaymentBillDetail::where('student_id', $studentId)->where('category', 'COURSE')->orderBy('id', 'DESC')->first();
            $payDetail = DB::table('paydetail')->where('studentid', $studentId)->where('category', 'COURSE')->first();
            $exPayDetailMonth = explode('-', $payDetail->monthpay);
            $getPayDetailMonth = (int)$exPayDetailMonth[1]/*  . '-' . $exPayDetailMonth[0] */;
            $student = Students::find($studentId);
            $price = Price::find($student->priceid);
            if ($detailPaid != null) {
                $exMonth = explode(' ', $detailPaid->payment);
                $month = explode('-', $exMonth[1]);
                $detailPaid->month = (int)$month[0];
                $exCourse = explode('COURSE ', $detailPaid->payment);
                $exCourseMonth = explode('-', $exCourse[1]);
                if ($getPayDetailMonth != (int)$exCourseMonth[0]) {
                    if ($detailPaid->month != now()->month) {
                        $model = new PaymentBill();
                        $model->class_type = $price->program != 'Private' || $price->program != 'Semi Private' ? 'Reguler' : 'Private';
                        $model->total_price = $price->course;
                        $model->created_by = Auth::guard('parent')->user()->name;
                        $model->updated_by = Auth::guard('parent')->user()->name;
                        $model->save();

                        $modelDetail = new PaymentBillDetail();
                        $modelDetail->id_payment_bill = $model->id;
                        $modelDetail->student_id = $studentId;
                        $modelDetail->category = 'COURSE';
                        $modelDetail->price = $price->course;
                        $modelDetail->unique_code = '-';
                        $modelDetail->payment = now()->month < 10 ? 'COURSE 0' . now()->month . '-' . now()->year : 'COURSE ' . now()->month . '-' . now()->year;
                        $modelDetail->status = 'Waiting';
                        $modelDetail->save();
                        return response()->json([
                            'code' => '00',
                            'payload' => 'Success1 ' . $detailPaid->id . '/' . $payDetail->id
                        ], 200);
                    } else {
                        return response()->json([
                            'code' => '00',
                            'payload' => 'Pembayaran untuk bulan ini sudah tertagih',
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'code' => '00',
                        'payload' => 'Pembayaran untuk bulan ini sudah terbayar',
                    ], 200);
                }
            } else {
                $model = new PaymentBill();
                $model->class_type = $price->program != 'Private' || $price->program != 'Semi Private' ? 'Reguler' : 'Private';
                $model->total_price = $price->course;
                $model->created_by = Auth::guard('parent')->user()->name;
                $model->updated_by = Auth::guard('parent')->user()->name;
                $model->save();

                $modelDetail = new PaymentBillDetail();
                $modelDetail->id_payment_bill = $model->id;
                $modelDetail->student_id = $studentId;
                $modelDetail->category = 'COURSE';
                $modelDetail->price = $price->course;
                $modelDetail->unique_code = '-';
                $modelDetail->payment = now()->month < 10 ? 'COURSE 0' . now()->month . '-' . now()->year : 'COURSE ' . now()->month . '-' . now()->year;
                $modelDetail->status = 'Waiting';
                $modelDetail->save();
                return response()->json([
                    'code' => '00',
                    'payload' => 'Success3',
                ], 200);
            }
            return response()->json([
                'code' => '00',
                'payload' => 'Nothing Happend',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '400',
                'error' => 'internal server error ' . $th,
            ], 403);
        }
    }
}
