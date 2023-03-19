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

            $data = $query->orderBy('date', 'DESC')->cursorPaginate(10);
            if ($request->start && $request->end) {
                $data->withPath(url('/api/myPoint/' . $studentId . '?start=' . $request->start . '&end=' . $request->start));
            }
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
