<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\OrderReview;
use App\Models\PaymentBillDetail;
use App\Models\PointHistory;
use App\Models\PointHistoryCategory;
use App\Models\Students;
use App\Models\StudentScore;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function getMyPoint(Request $request, $studentId)
    {
        try {
            // return $studentId;
            $query = [];
            $query = PointHistory::select('date', 'total_point', 'type', 'keterangan')
                ->where('student_id', $studentId);
            if ($request->start && $request->end) {
                $query = $query->whereBetween('date',  [$request->start, $request->end]);
            }

            $data = $query->orderBy('date', 'DESC')->paginate($request->perpage);
            $point = Students::where('id', $studentId)->select('total_point')->first();
            $class = Students::join('price', 'price.id', 'student.priceid')
                ->select('price.program')
                ->where('student.id', $studentId)->first();
            return response()->json([
                'code' => '00',
                'total_point' =>  $point->total_point,
                'class' =>  $class->program,
                'payload' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '400',
                'error' => 'internal server error', 'message' => $th,
            ], 403);
        }
    }

    public function getAttendance(Request $request, $studentId)
    {
        try {
            $class = Students::join('price', 'price.id', 'student.priceid')
                ->select('price.program')
                ->where('student.id', $studentId)->first();
            $query = [];
            $query = AttendanceDetail::join('attendances as atd', 'atd.id', 'attendance_details.attendance_id')
                ->join('student as st', 'st.id', 'attendance_details.student_id')
                ->join('price as pr', 'pr.id', 'atd.price_id')
                ->select('st.name', 'pr.program', 'atd.id as attendance_id', 'attendance_details.*', 'atd.date')
                ->where('attendance_details.student_id', $studentId);
            if ($request->class) {
                $query = $query->where('atd.price_id',  $request->class);
            }
            $data = $query->orderBy('atd.date', 'DESC')->paginate($request->perpage);
            return response()->json([
                'code' => '00',
                'class' =>  $class->program,
                'payload' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '400',
                'error' => 'internal server error', 'message' => $th,
            ], 403);
        }
    }

    public function getClass($studentId)
    {
        try {
            $data = [];
            $data = Attendance::join('attendance_details as ad', 'ad.attendance_id', 'attendances.id')
                ->join('price as p', 'p.id', 'attendances.price_id')
                ->select('p.program', 'p.id')
                ->where('ad.student_id', $studentId)
                ->groupBy('attendances.price_id')
                ->get();
            return response()->json([
                'code' => '00',
                'payload' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '400',
                'error' => 'internal server error', 'message' => $th,
            ], 403);
        }
    }

    public function getSummary($studentId)
    {
        try {
            $score = StudentScore::where('student_id', $studentId)
                ->select('average_score')
                ->orderBy('id', 'DESC')
                ->first();

            $billing = PaymentBillDetail::where('category', 'COURSE')->where('student_id', $studentId)->where('status', 'Waiting')->where('payment', 'COURSE ' . Carbon::now()->format('m-Y'))->sum('price');
            $agenda = AttendanceDetail::join('attendances', 'attendances.id', 'attendance_details.attendance_id')
                ->select('attendances.activity', 'attendances.date', 'attendances.text_book', 'attendances.id')
                ->where('attendance_details.student_id', $studentId)
                ->orderBy('attendance_details.id', 'DESC')
                ->take(5)->get();
            $review = [];
            $test = [];
            foreach ($agenda as $key => $value) {
                $modelReview = OrderReview::where('id_attendance', $value->id)
                    ->join('attendances', 'attendances.id', 'order_reviews.id_attendance')
                    ->select('attendances.date_review', 'attendances.date_test', 'attendances.date', 'review_test')
                    ->where('is_done', true)
                    ->get();
                if ($modelReview) {
                    array_push($review, $modelReview);
                    // array_push($test, $modelReview);
                }
                // array_push($review, $modelReview->class);
            }
            $point = Students::where('id', $studentId)->select('total_point')->first();
            $data['score'] = $score ? $score->average_score : 0;
            $data['billing'] = $billing ? $billing : 0;
            $data['point'] = $point ? $point->total_point : 0;
            $data['agenda'] = $agenda;
            $data['review'] = $review;
            $data['test'] = $test;
            return response()->json([
                'code' => '00',
                'payload' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                'code' => '400',
                'error' => 'internal server error', 'message' => $th,
            ], 403);
        }
    }
}
