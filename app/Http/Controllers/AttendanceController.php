<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\AttendanceDetailPoint;
use App\Models\PointCategories;
use App\Models\PointHistory;
use App\Models\Price;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $class = Price::all();
        $private = [];
        $general = [];
        foreach ($class as $key => $value) {
            if ($value->level == 'Private') {
                array_push($private, $value);
            } else {
                array_push($general, $value);
            }
            
        }
        return view('attendance.index', compact('private', 'general'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($priceId)
    {
        $cek = Attendance::where('price_id', $priceId)
            ->where('date', date('Y-m-d'))
            ->orderBy('id', 'DESC')
            ->first();
        $class = Price::where('id', $priceId)->first();
        $title = $class->level == 'Private' ? 'Private Class ' . $class->program : 'Reguler Class ' . $class->program;
        if ($cek) {
            $detail = AttendanceDetail::where('attendance_id', $cek->id)->get();
            $points = [];
            foreach ($detail as $key => $id) {
                $attPoint = AttendanceDetailPoint::where('attendance_detail_id', $id->id)
                    ->select('point_category_id')
                    ->get();

                foreach ($attPoint as $idp) {
                    array_push($points, intval($idp->point_category_id));
                }
                $id['category'] = $points;
            }
            // return $cek;
            $data = (object)[
                'type' => 'update',
                'id' => $class->id,
                'attendanceId' => $cek->id,
                'comment' => $cek->activity,
                'textBook' => $cek->text_book,
                'excerciseBook' => $cek->excercise_book,
                'students' => $detail,
            ];
            // return $data;
        } else {

            $data = (object)[
                'type' => 'create',
                'id' => $class->id,
                'attendanceId' => 0,
                'comment' => '',
                'textBook' => '',
                'excerciseBook' => '',
                'students' => [],
            ];
        }


        $student = Students::where('priceid', $class->id)
            ->get();

        $pointCategories = PointCategories::all();
        // return $student;
        return view('attendance.form', compact('title', 'data', 'student', 'pointCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        try {
            $pointCategories = PointCategories::all();
            $attendance = Attendance::create([
                'price_id' => $request->priceId,
                'date' => date('Y-m-d'),
                'teacher_id' => Auth::guard('teacher')->user()->id,
                'activity' => $request->comment,
                'text_book' => $request->textBook,
                'excercise_book' => $request->excerciseBook,
            ]);
            for ($i = 0; $i < count($request->totalPoint); $i++) {
                $detail = AttendanceDetail::create([
                    'attendance_id' => $attendance->id,
                    'student_id' => $request->studentId[$i],
                    'is_absent' => count($request->isAbsent[$i + 1]) > 1 ? '1' : '0',
                    'total_point' => $request->totalPoint[$i],
                ]);
                $student = Students::where('id', $request->studentId[$i])->first();
                Students::where('id', $request->studentId[$i])->update([
                    'total_point' => $student->total_point +  $request->totalPoint[$i],
                ]);
                if (count($request->isAbsent[$i + 1]) > 1) {
                    PointHistory::create([
                        'student_id' => $request->studentId[$i],
                        'date' => date('Y-m-d'),
                        'total_point' =>  10,
                        'type' => 'in',
                        'keterangan' => 'Absent',
                    ]);
                }
                if ($request->categories) {
                    if (array_key_exists($i + 1, $request->categories)) {
                        for ($x = 0; $x < count($request->categories[$i + 1]); $x++) {
                            $pos = 0;
                            foreach ($pointCategories as $key => $value) {
                                if ($request->categories[$i + 1][$x] == $value['id']) {
                                    $pos = $key;
                                }
                            }
                            AttendanceDetailPoint::create([
                                'attendance_detail_id' => $detail->id,
                                'point_category_id' => $request->categories[$i + 1][$x],
                                'point' => $pointCategories[$pos]->point,
                            ]);
                            if ($request->totalPoint[$i] > 0) {
                                PointHistory::create([
                                    'student_id' => $request->studentId[$i],
                                    'date' => date('Y-m-d'),
                                    'total_point' =>  $pointCategories[$pos]->point,
                                    'type' => 'in',
                                    'keterangan' =>  $pointCategories[$pos]->name,
                                ]);
                            }
                            // return ([
                            //     'attendance_detail_id' => $detail->id,
                            //     'point_category_id' => $request->categories[$i + 1][$x-1],
                            //     'point' => $pointCategories[$pos]->point,
                            // ]);
                        }
                    }
                }
            }
            return redirect('/dashboard');
        } catch (\Throwable $th) {
            return $th;
        }
        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $attendance)
    {
        // return $request;
        try {
            $pointCategories = PointCategories::all();
            Attendance::where('id', $request->attendanceId)->update([
                'price_id' => $request->priceId,
                'date' => date('Y-m-d'),
                'teacher_id' => Auth::guard('teacher')->user()->id,
                'activity' => $request->comment,
                'text_book' => $request->textBook,
                'excercise_book' => $request->excerciseBook,
            ]);
            for ($i = 0; $i < count($request->totalPoint); $i++) {
                $dataDetail = AttendanceDetail::where('attendance_id', $request->attendanceId)
                    ->where('student_id', $request->studentId[$i])
                    ->first();
                AttendanceDetail::where('attendance_id', $request->attendanceId)
                    ->where('student_id', $request->studentId[$i])
                    ->update([
                        'is_absent' => count($request->isAbsent[$i + 1]) > 1 ? '1' : '0',
                        'total_point' => $request->totalPoint[$i],
                    ]);
                $student = Students::where('id', $request->studentId[$i])->first();
                $tmpPoint = $student->total_point - $dataDetail->total_point;
                Students::where('id', $request->studentId[$i])->update([
                    'total_point' => $tmpPoint +  $request->totalPoint[$i],
                ]);
                // if (count($request->isAbsent[$i + 1]) > 1) {
                //     PointHistory::create([
                //         'student_id' => $request->studentId[$i],
                //         'date' => date('Y-m-d'),
                //         'total_point' =>  10,
                //         'type' => 'in',
                //         'keterangan' => 'Absent',
                //     ]);
                // }

                if ($request->categories) {
                    if (array_key_exists($i + 1, $request->categories)) {
                        for ($x = 0; $x < count($request->categories[$i + 1]); $x++) {
                            $pos = 0;
                            foreach ($pointCategories as $key => $value) {
                                if ($request->categories[$i + 1][$x] == $value['id']) {
                                    $pos = $key;
                                }
                            }
                            $avl =  AttendanceDetailPoint::where('attendance_detail_id', $dataDetail->id)
                                ->get();
                            $tmpDetail = [];

                            foreach ($avl as $key => $value) {
                                if (in_array($value->point_category_id, $request->categories[$i + 1]) == 0) {
                                    AttendanceDetailPoint::where('id', $value->id)->delete();
                                }
                                array_push($tmpDetail, $value->point_category_id);
                            }
                            if (in_array($request->categories[$i + 1][$x], $tmpDetail) == false) {
                                AttendanceDetailPoint::create([
                                    'attendance_detail_id' => $dataDetail->id,
                                    'point_category_id' => $request->categories[$i + 1][$x],
                                    'point' => $pointCategories[$pos]->point,
                                ]);
                            }
                        }
                    }
                }
            }
            return redirect('/dashboard');
        } catch (\Throwable $th) {
            return $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
