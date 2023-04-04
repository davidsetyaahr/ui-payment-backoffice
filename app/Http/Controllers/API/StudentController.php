<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\PaymentBillDetail;
use App\Models\PointHistory;
use App\Models\PointHistoryCategory;
use App\Models\Students;
use App\Models\StudentScore;
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
            $billing = PaymentBillDetail::where('student_id', $studentId)->where('status', 'Waiting')->sum('price');
            $point = Students::where('id', $studentId)->select('total_point')->first();
            $data['score'] = $score->average_score;
            $data['billing'] = $billing;
            $data['point'] = $point->total_point;
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
}
