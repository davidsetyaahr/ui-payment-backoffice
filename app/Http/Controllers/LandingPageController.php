<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\PointHistory;
use App\Models\ReedemPoint;
use App\Models\ReedemItems;
use App\Models\Staff;
use App\Models\Students;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function redeemPoint(Request $request)
    {
        try {
            $class = Price::all();
            // $students = Students::join('price as p', 'p.id', 'student.priceid')
            //     ->select('student.name', 'student.id', 'student.total_point')
            //     ->get();
            $students = Students::where('status', 'ACTIVE')->get();
            $item = ReedemItems::all();
            $title = 'Landing Page Reedem Point';
            return view('landing-page.redeem-point', compact('students', 'title', 'item', 'class'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function redeemPointStudent($id)
    {
        $student = Students::find($id);
        return $student;
    }

    public function storeReedemPoint(Request $request)
    {
        $student = $request->id_student == null ? $request->student : $request->id_student;
        $findPointStudent = Students::find($student);
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
            'balance_in_advanced' => $findPointStudent->total_point,
        ]);
        $newPoint = intval($request->point) - intval($request->total_point);
        Students::where('id', $student)
            ->update([
                'total_point' =>  $newPoint,
            ]);
        return redirect('/landing-page/redeem-point')->with('status', 'Success Reedem Point');
    }
}
