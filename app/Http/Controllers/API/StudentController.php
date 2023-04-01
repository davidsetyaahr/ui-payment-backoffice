<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AttendanceDetail;
use App\Models\PointHistory;
use App\Models\PointHistoryCategory;
use App\Models\Students;
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
            
            return response()->json([
                'code' => '00',
                'total_point' =>  $point->total_point,
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
            $query = [];
            $query = AttendanceDetail::join('attendances as atd', 'atd.id', 'attendance_details.attendance_id')
                ->join('student as st', 'st.id', 'attendance_details.student_id')
                ->join('price as pr', 'pr.id', 'atd.price_id')
                ->select('st.name', 'pr.program', 'atd.id as attendance_id', 'attendance_details.*', 'atd.date')
                ->where('attendance_details.student_id', $studentId);
            if ($request->start && $request->end) {
                $query = $query->whereBetween('date',  [$request->start, $request->end]);
            }
            $data = $query->orderBy('atd.date', 'DESC')->paginate($request->perpage);
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
