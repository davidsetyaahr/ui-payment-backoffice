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
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $class = Price::all();
        $class = DB::select("SELECT DISTINCT priceid,day1,day2,course_time,id_teacher,price.level,price.program,day_1.day day_one,day_2.day day_two,teacher.name teacher_name from student join price on student.priceid = price.id join day day_1 on student.day1 = day_1.id join day day_2 on student.day2 = day_2.id join teacher on student.id_teacher = teacher.id  WHERE day1 is NOT null AND day2 is NOT null AND course_time is NOT null AND id_teacher is NOT null;");
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
    public function create($priceId, Request $request)
    {
        $reqDay1 = $request->day1;
        $reqDay2 = $request->day2;
        $reqTime = $request->time;
        // $reqAmpm = $request->ampm;
        $student = "";
        $day = DB::table('day')->get();
        $cek = Attendance::where('price_id', $priceId)
            ->where('date', date('Y-m-d'))
            ->where('day1', $reqDay1)
            ->where('day2', $reqDay2)
            ->where('course_time', $reqTime)
            ->orderBy('id', 'DESC')
            ->first();

        $class = Price::where('id', $priceId)->first();
        $title = $class->level == 'Private' ? 'Private Class ' . $class->program : 'Reguler Class ' . $class->program;
        if ($cek) {
            $detail = AttendanceDetail::where('attendance_id', $cek->id)->get();
            foreach ($detail as $key => $id) {
                $points = [];
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
            ->where("day1", $reqDay1)
            ->where("day2", $reqDay2)
            ->where('course_time', $reqTime)
            // ->where('id_teacher',Auth::guard('teacher')->user()->id)
            ->get();


        $pointCategories = PointCategories::all();
        // return $student;
        return view('attendance.form', compact('title', 'data', 'student', 'pointCategories', 'day'));
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
            $createAttendance = [
                'price_id' => $request->priceId,
                'day1' => (int)$request->day1,
                'day2' => (int)$request->day2,
                'course_time' => $request->time,
                'date' => date('Y-m-d'),
                'teacher_id' => Auth::guard('teacher')->user()->id,
                'activity' => $request->comment,
                'text_book' => $request->textBook,
                'excercise_book' => $request->excerciseBook,
            ];
            $attendance = Attendance::create($createAttendance);
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
                        'keterangan' => 'Present',
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
            return redirect()->back();
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
            return redirect()->back();
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

    public function reminder(Request $request)
    {
        $arrAbsent = [];
        $students = Students::get();
        foreach ($students as $key => $value) {
            $ttlApha = 0;
            $attendance = AttendanceDetail::join('student as st', 'st.id', 'attendance_details.student_id')
                ->join('price as p', 'p.id', 'st.priceid')
                ->select('attendance_details.*', 'st.name', 'p.program')
                ->where('student_id', $value->id)->orderBy('attendance_details.id', 'desc')->limit(2)->get();
            $countA = count($attendance);
            if ($countA != 0) {
                foreach ($attendance as $keya => $valuea) {
                    if ($valuea->is_absent == '0') {
                        $ttlApha++;
                    }
                }
            }
            if ($ttlApha >= 2) {
                array_push($arrAbsent, $attendance);
            }
        }
        $data = $arrAbsent;
        $page = !empty($request->page) ? (int) $request->page : 1;
        $total = count($data); //total items in array
        $limit = 10; //per page
        $totalPages = ceil($total / $limit); //calculate total pages
        $page = max($page, 1); //get 1 page when $request->page <= 0
        $page = min($page, $totalPages); //get last page when $request->page > $totalPages
        $offset = ($page - 1) * $limit;
        if ($offset < 0) $offset = 0;
        $data = array_slice($data, $offset, $limit);
        return view('attendance.reminder', compact('data', 'totalPages'));
    }

    public function mutasi(Request $request)
    {
        $studentId = $request->student;
        $student = Students::get();
        $price = Price::get();
        $class = Students::join('price', 'price.id', 'student.priceid')
            ->select('price.program')
            ->where('student.id', $studentId)->first();
        $query = [];
        $query = AttendanceDetail::join('attendances as atd', 'atd.id', 'attendance_details.attendance_id')
            ->join('student as st', 'st.id', 'attendance_details.student_id')
            ->join('price as pr', 'pr.id', 'atd.price_id')
            ->select('st.name', 'pr.program', 'atd.id as attendance_id1', 'attendance_details.*', 'atd.date', 'st.id as student_id1', 'pr.id as price_id')
            ->where('attendance_details.student_id', $studentId);
        if ($request->class) {
            $query = $query->where('atd.price_id',  $request->class);
        }
        $data = $query->orderBy('atd.date', 'DESC')->groupBy('pr.program')->paginate(10);
        // return $data;
        return view('attendance.mutasi', compact('data', 'student', 'price'));
        // if ($request->class) {
        //     $query = $query->where('atd.price_id',  $request->class);
        // }
        // $data = $query->orderBy('atd.date', 'DESC')->paginate($request->perpage);
    }
}
