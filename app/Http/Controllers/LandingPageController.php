<?php

namespace App\Http\Controllers;

use App\Models\PointHistory;
use App\Models\ReedemPoint;
use App\Models\Staff;
use App\Models\Students;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function redeemPoint(Request $request)
    {
        $students = Students::where('status', 'ACTIVE')->get();
        $staffs = Staff::get();
        $reqStudent = $request->student != null ? $request->student : $request->id_student;
        $reqStaff = $request->staff;
        $student  = Students::where('id', $reqStudent)->where('id_staff', $reqStaff)->first();
        return view('landing-page.redeem-point', compact('students', 'staffs', 'reqStudent', 'reqStaff', 'student'));
    }

    public function storeReedemPoint(Request $request)
    {
        $student = $request->student;
        ReedemPoint::create([
            'point' => intval($request->total_point),
            'student_id' => $student,
        ]);
        PointHistory::create([
            'student_id' => $student,
            'date' => date('Y-m-d'),
            'total_point' => intval($request->total_point),
            'type' => 'redeem',
            'keterangan' => 'Reedem Point',
        ]);
        $newPoint = intval($request->point) - intval($request->total_point);
        Students::where('id', $student)
            ->update([
                'total_point' =>  $newPoint,
            ]);
        return redirect('/landing-page/redeem-point')->with('status', 'Success Reedem Point');
    }
}
