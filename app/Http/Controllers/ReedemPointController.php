<?php

namespace App\Http\Controllers;

use App\Models\ReedemItems;
use App\Models\ReedemPoint;
use App\Models\Students;
use Illuminate\Http\Request;

class ReedemPointController extends Controller
{
    public function create()
    {
        try {
            $students = Students::join('price as p', 'p.id', 'student.priceid')
                ->select('student.name', 'student.id', 'student.total_point')
                ->where('p.program', '!=', 'Private')
                ->limit(5)
                ->get();
            $item = ReedemItems::all();
            $title = 'Reedem Point';
            return view('reedemPoint.form', compact('students', 'title', 'item'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function store(Request $request)
    {
        try {
            $tmpTotal = 0;
            for ($i = 0; $i < count($request->item); $i++) {
                $items = ReedemItems::where('id', $request->item[$i])->first();
                $subTotal = intval($items->point) * intval($request->qty[$i]);
                $tmpTotal += $subTotal;
            }
            if ($request->point < $tmpTotal) {
                return back()->with('error', 'Point tidak cukup');
            } else {
                for ($i = 0; $i < count($request->item); $i++) {
                    $items = ReedemItems::where('id', $request->item[$i])->first();
                    $subTotal = intval($items->point) * intval($request->qty[$i]);
                    ReedemPoint::create([
                        'item_id' => $request->item[$i],
                        'point' => intval($items->point),
                        'student_id' => $request->student,
                        'qty' => $request->qty[$i],
                    ]);
                }
                $newPoint = intval($request->point) - $subTotal;
                Students::where('id', $request->student)
                    ->update([
                        'total_point' =>  $newPoint,
                    ]);
                return redirect('/reedemPoint')->with('status', 'Success Reedem Point');
            }
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed Reedem Point');
            //throw $th;
        }
    }

    public function getStudent(Request $request)
    {
        try {
            // $data = 
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
