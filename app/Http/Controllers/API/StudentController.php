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
            $query = PointHistoryCategory::join('point_histories', 'point_histories.id', 'point_history_categories.point_history_id')
                ->join('point_categories', 'point_categories.id', 'point_history_categories.point_category_id')
                ->select('point_histories.date', 'point_histories.total_point', 'point_histories.type', 'point_categories.name')
                ->where('point_histories.student_id', $studentId);
            if ($request->start && $request->end) {
                $query = $query->whereBetween('point_histories.date',  [$request->start, $request->end]);
            }

            $data = $query->orderBy('point_histories.date', 'DESC')->paginate(10);
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
