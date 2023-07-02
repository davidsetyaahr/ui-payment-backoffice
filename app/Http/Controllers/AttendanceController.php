<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\AttendanceDetailPoint;
use App\Models\Mutasi;
use App\Models\PointCategories;
use App\Models\PointHistory;
use App\Models\Price;
use App\Models\Students;
use App\Models\StudentScore;
use App\Models\Teacher;
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
    public function index(Request $request)
    {
        // $class = Price::all();
        // if (Auth::gurad) {
        //     # code...
        // }
        $where = '';
        $teachers = Teacher::get();
        $level = Price::get();
        if (Auth::guard('teacher')->check() == true) {
            $where = 'AND id_teacher = ' . Auth::guard('teacher')->user()->id;
        }

        if (Auth::guard('staff')->check() == true && Auth::guard('staff')->user()->id != 7) {
            $where = $where . ' AND id_staff = ' . Auth::guard('staff')->user()->id;
        }
        if ($request->teacher) {
            $where = $where . ' AND id_teacher = ' . $request->teacher;
        }
        if ($request->level && Auth::guard('staff')->check() == true) {
            $where = $where . ' AND priceid = ' . $request->level;
        }
        if ($request->level && Auth::guard('teacher')->check() == true) {
            $where = $where . ' AND priceid = ' . $request->level . ' AND id_teacher =' . Auth::guard('teacher')->user()->id;
        }
        if ($request->day && Auth::guard('teacher')->check() == true) {
            $where = $where . ' AND (day1 = ' . $request->day . ' OR day2 = ' . $request->day . ') AND id_teacher =' . Auth::guard('teacher')->user()->id;
        }
        $class = DB::select("SELECT DISTINCT priceid,day1,day2,course_time,id_teacher,price.level,price.program,day_1.day day_one,day_2.day day_two,teacher.name teacher_name from student join price on student.priceid = price.id join day day_1 on student.day1 = day_1.id join day day_2 on student.day2 = day_2.id join teacher on student.id_teacher = teacher.id  WHERE day1 is NOT null AND day2 is NOT null AND course_time is NOT null AND id_teacher is NOT null $where ORDER BY priceid ASC, day1,course_time;");
        $private = [];
        $general = [];
        foreach ($class as $key => $value) {
            if ($value->level == 'Private') {
                array_push($private, $value);
            } else {
                array_push($general, $value);
            }
        }
        $day = DB::table('day',)->get();
        return view('attendance.index', compact('private', 'general', 'day', 'teachers', 'level'));
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
        $reqTeacher = $request->teacher;
        // $reqAmpm = $request->ampm;
        $student = "";
        $day = DB::table('day')->get();
        $cek = Attendance::where('price_id', $priceId)
            ->where('date', date('Y-m-d'))
            ->where('day1', $reqDay1)
            ->where('day2', $reqDay2)
            ->where('course_time', $reqTime)
            ->where('teacher_id', $reqTeacher)
            ->orderBy('id', 'DESC')
            ->first();
        // $agenda = [];


        $class = Price::where('id', $priceId)->first();
        $title = $class->level == 'Private' ? 'Private Class ' . $class->program : 'Regular';
        if ($cek) {
            $detail = AttendanceDetail::where('attendance_id', $cek->id)->get();
            foreach ($detail as $key => $id) {
                // multiple
                $points = [];
                $attPoint = AttendanceDetailPoint::where('attendance_detail_id', $id->id)
                    ->select('point_category_id')
                    ->get();

                foreach ($attPoint as $idp) {
                    array_push($points, intval($idp->point_category_id));
                }
                $id['category'] = $points;

                // manual
                // $points = [];
                // $catPoints = [];
                // $attPoint = AttendanceDetailPoint::where('attendance_detail_id', $id->id)
                //     ->get();

                // foreach ($attPoint as $idp) {
                //     array_push($points, $idp->point);
                //     array_push($catPoints, $idp->point_category);
                // }
                // $id->categoryPoint = $points != null ? $points[0] : '';
                // $id->category = $catPoints != null ? $catPoints[0] : '';
            }
            $data = (object)[
                'type' => 'update',
                'id' => $class->id,
                'attendanceId' => $cek->id,
                'comment' => $cek->activity,
                'textBook' => $cek->text_book,
                'excerciseBook' => $cek->excercise_book,
                'students' => $detail,
                'is_presence' => $cek->is_presence,
                'id_test' => $cek->id_test,
                'date_review' => $cek->date_review,
                'date_test' => $cek->date_test,
            ];
            // return $data;
        } else {
            // $agenda = [];
            $data = (object)[
                'type' => 'create',
                'id' => $class->id,
                'attendanceId' => 0,
                'comment' => '',
                'textBook' => '',
                'excerciseBook' => '',
                'students' => [],
                'is_presence' => '',
                'id_test' => '',
                'date_review' => '',
                'date_test' => '',
            ];
        }


        $student = Students::where('status', 'ACTIVE')->where('priceid', $class->id)
            ->where("day1", $reqDay1)
            ->where("day2", $reqDay2)
            ->where('course_time', $reqTime);
        if (Auth::guard('teacher')->check() == true) {
            $student = $student->where('id_teacher', Auth::guard('teacher')->user()->id);
        } else {
            $student = $student->where('id_teacher', $reqTeacher);
        }

        $student =   $student->get();


        $pointCategories = PointCategories::where('id', '!=', 5)->orderBy('point', 'ASC')->get();
        $attendance = Attendance::where('price_id', $priceId)
            ->where('day1', $reqDay1)
            ->where('day2', $reqDay2)
            ->where('course_time', $reqTime)
            ->where('teacher_id', $reqTeacher)
            ->orderBy('id', 'asc')
            ->get();

        // return $student;
        if (count($student) != 0) {
            return view('attendance.form', compact('attendance', 'title', 'data', 'student', 'pointCategories', 'day', 'priceId', 'reqDay1', 'reqDay2', 'reqTeacher', 'reqTime'));
        } else {
            $inStudent = Students::where('status', 'INACTIVE')->where('priceid', $class->id)
                ->where("day1", $reqDay1)
                ->where("day2", $reqDay2)
                ->where('course_time', $reqTime);
            if (Auth::guard('teacher')->check() == true) {
                $inStudent = $inStudent->where('id_teacher', Auth::guard('teacher')->user()->id);
            } else {
                $inStudent = $inStudent->where('id_teacher', $reqTeacher);
            }
            $inStudent =   $inStudent->update([
                'day1' => null,
                'day2' => null,
                'course_time' => null,
                'id_teacher' => null,
                'id_staff' => null,
            ]);
            return redirect('/attendance/class')->with('error', 'There is no student');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        try {
            if ($request->cekAllAbsen == true) {
                $pointCategories = PointCategories::all();
                $createAttendance = [
                    'price_id' => $request->priceId,
                    'day1' => (int)$request->day1,
                    'day2' => (int)$request->day2,
                    'course_time' => $request->time,
                    'date' => date('Y-m-d'),
                    'teacher_id' => $request->teacher,
                    'activity' => $request->comment,
                    'text_book' => $request->textBook,
                    'excercise_book' => $request->excerciseBook,
                    'is_presence' => true,
                    'id_test' => $request->id_test,
                    'date_review' => $request->date_review,
                    'date_test' => $request->date_test,
                ];
                $attendance = Attendance::create($createAttendance);
                for ($i = 0; $i < count($request->totalPoint); $i++) {
                    $detail = AttendanceDetail::create([
                        'attendance_id' => $attendance->id,
                        'student_id' => $request->studentId[$i],
                        'is_absent' => count($request->isAbsent[$i + 1]) > 1 ? '1' : '0',
                        'total_point' => $request->totalPoint[$i],
                        'is_permission' => count($request->isPermission[$i + 1]) > 1 ? true : false,
                        'is_alpha' => count($request->isAlpha[$i + 1]) > 1 ? true : false,
                    ]);
                    $student = Students::where('id', $request->studentId[$i])->first();
                    Students::where('id', $request->studentId[$i])->update([
                        'total_point' => $student->total_point +  $request->totalPoint[$i],
                    ]);
                    if (count($request->isAbsent[$i + 1]) != 1) {
                        PointHistory::create([
                            'student_id' => $request->studentId[$i],
                            'date' => date('Y-m-d'),
                            'total_point' =>  10,
                            'type' => 'in',
                            'keterangan' => 'Present',
                            'balance_in_advanced' => $student->total_point,
                        ]);
                    }
                    if ($request->birthdaypoint[$i + 1][0] != 0) {
                        AttendanceDetailPoint::create([
                            'attendance_detail_id' => $detail->id,
                            'point_category_id' => 5,
                            'point' => 30,
                        ]);
                        PointHistory::create([
                            'student_id' => $request->studentId[$i],
                            'date' => date('Y-m-d'),
                            'total_point' =>  30,
                            'type' => 'in',
                            'keterangan' =>  'Extra Birthday',
                            'balance_in_advanced' => $student->total_point,
                        ]);
                        Students::where('id', $request->studentId[$i])->update([
                            'total_point' => $student->total_point +  30,
                        ]);
                    }

                    // Multiple
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
                                        'balance_in_advanced' => $student->total_point,
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

                    // Manual
                    // if ($request->category) {
                    //     if (array_key_exists($i + 1, $request->category)) {
                    //         for ($x = 0; $x < count($request->category[$i + 1]); $x++) {
                    //             if ($request->category[$i + 1][$x] != null && $request->point_category[$i + 1][$x] != null) {
                    //                 $attendanceDetailPoint = new AttendanceDetailPoint;
                    //                 $attendanceDetailPoint->attendance_detail_id = $detail->id;
                    //                 $attendanceDetailPoint->point_category = $request->category[$i + 1][$x];
                    //                 $attendanceDetailPoint->point = $request->point_category[$i + 1][$x];
                    //                 $attendanceDetailPoint->save();
                    //                 if ($request->totalPoint[$i] > 0) {
                    //                     PointHistory::create([
                    //                         'student_id' => $request->studentId[$i],
                    //                         'date' => date('Y-m-d'),
                    //                         'total_point' =>  $request->point_category[$i + 1][$x],
                    //                         'type' => 'in',
                    //                         'keterangan' =>  $request->category[$i + 1][$x],
                    //                     ]);
                    //                 }
                    //             }
                    //         }
                    //     }
                    // }
                }
                return redirect('/attendance/class')->with('message', 'Schedule student update');
            } else {

                return redirect()->back()->with('status', 'Schedule failed to update');
            }
        } catch (\Throwable $th) {
            ddd($th);
        }
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
    public function edit(Request $request, $id)
    {
        $reqDay1 = $request->day1;
        $reqDay2 = $request->day2;
        $reqTime = $request->time;
        $reqTeacher = $request->teacher;
        $priceId = $request->class;
        $student = "";
        $day = DB::table('day')->get();
        $cek = Attendance::where('id', $id)
            ->orderBy('id', 'DESC')
            ->first();
        // $agenda = [];


        $class = Price::where('id', $priceId)->first();
        $title = $class->level == 'Private' ? 'Private Class ' . $class->program : 'Regular';
        if ($cek) {
            $detail = AttendanceDetail::where('attendance_id', $cek->id)->get();
            foreach ($detail as $key => $idDetail) {
                // multiple
                $points = [];
                $attPoint = AttendanceDetailPoint::where('attendance_detail_id', $idDetail->id)
                    ->select('point_category_id')
                    ->get();

                foreach ($attPoint as $idp) {
                    array_push($points, intval($idp->point_category_id));
                }
                $idDetail['category'] = $points;

                // manual
                // $points = [];
                // $catPoints = [];
                // $attPoint = AttendanceDetailPoint::where('attendance_detail_id', $id->id)
                //     ->get();

                // foreach ($attPoint as $idp) {
                //     array_push($points, $idp->point);
                //     array_push($catPoints, $idp->point_category);
                // }
                // $id->categoryPoint = $points != null ? $points[0] : '';
                // $id->category = $catPoints != null ? $catPoints[0] : '';
            }
            $data = (object)[
                'type' => 'update',
                'id' => $class->id,
                'attendanceId' => $cek->id,
                'comment' => $cek->activity,
                'textBook' => $cek->text_book,
                'excerciseBook' => $cek->excercise_book,
                'students' => $detail,
                'is_presence' => $cek->is_presence,
                'id_test' => $cek->id_test,
                'date_review' => $cek->date_review,
                'date_test' => $cek->date_test,
            ];
            // return $data;
        } else {
            // $agenda = [];
            $data = (object)[
                'type' => 'create',
                'id' => $class->id,
                'attendanceId' => 0,
                'comment' => '',
                'textBook' => '',
                'excerciseBook' => '',
                'students' => [],
                'is_presence' => '',
                'id_test' => '',
                'date_review' => '',
                'date_test' => '',
            ];
        }


        $student = Students::where('status', 'ACTIVE')->where('priceid', $class->id)
            ->where("day1", $reqDay1)
            ->where("day2", $reqDay2)
            ->where('course_time', $reqTime);
        if (Auth::guard('teacher')->check() == true) {
            $student = $student->where('id_teacher', Auth::guard('teacher')->user()->id);
        } else {
            $student = $student->where('id_teacher', $reqTeacher);
        }

        $student =   $student->get();


        $pointCategories = PointCategories::where('id', '!=', 5)->orderBy('point', 'ASC')->get();
        $attendance = Attendance::where('id', $id)
            ->orderBy('id', 'DESC')
            ->get();

        return view('attendance.form', compact('attendance', 'title', 'data', 'student', 'pointCategories', 'day', 'priceId', 'reqDay1', 'reqDay2', 'reqTeacher', 'reqTime'));
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
            if ($request->cekAllAbsen == true) {
                $pointCategories = PointCategories::all();
                Attendance::where('id', $request->attendanceId)->update([
                    'price_id' => $request->priceId,
                    'date' => date('Y-m-d'),
                    'teacher_id' => $request->teacher,
                    'activity' => $request->comment,
                    'text_book' => $request->textBook,
                    'excercise_book' => $request->excerciseBook,
                    'id_test' => $request->id_test,
                    'date_review' => $request->date_review,
                    'date_test' => $request->date_test,
                ]);
                for ($i = 0; $i < count($request->totalPoint); $i++) {
                    $dataDetail = AttendanceDetail::where('attendance_id', $request->attendanceId)
                        ->where('student_id', $request->studentId[$i]);
                    if ($dataDetail->count() == 0) {
                        //insert
                        $insert = AttendanceDetail::create([
                            'attendance_id' => $request->attendanceId,
                            'student_id' => $request->studentId[$i],
                            'is_absent' => count($request->isAbsent[$i + 1]) > 1 ? '1' : '0',
                            'total_point' => $request->totalPoint[$i],
                            'is_permission' => count($request->isPermission[$i + 1]) > 1 ? true : false,
                            'is_alpha' => count($request->isAlpha[$i + 1]) > 1 ? true : false,
                        ]);

                        $detailTotalPoint = 0;
                        $attendanceDetailId = $insert->id;
                    } else {
                        $dataDetail = $dataDetail->first();
                        $attendanceDetailId = $dataDetail->id;
                        $detailTotalPoint = $dataDetail->total_point;
                        AttendanceDetail::where('attendance_id', $request->attendanceId)
                            ->where('student_id', $request->studentId[$i])
                            ->update([
                                'is_absent' => count($request->isAbsent[$i + 1]) > 1 ? '1' : '0',
                                'total_point' => $request->totalPoint[$i],
                                'is_permission' => count($request->isPermission[$i + 1]) > 1 ? true : false,
                                'is_alpha' => count($request->isAlpha[$i + 1]) > 1 ? true : false,
                            ]);
                    }
                    $student = Students::where('id', $request->studentId[$i])->first();
                    $tmpPoint = $student->total_point - $detailTotalPoint;
                    Students::where('id', $request->studentId[$i])->update([
                        'total_point' => $tmpPoint +  $request->totalPoint[$i],
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

                    // Multiple
                    if ($request->categories) {
                        if (array_key_exists($i + 1, $request->categories)) {
                            for ($x = 0; $x < count($request->categories[$i + 1]); $x++) {
                                $pos = 0;
                                foreach ($pointCategories as $key => $value) {
                                    if ($request->categories[$i + 1][$x] == $value['id']) {
                                        $pos = $key;
                                    }
                                }
                                $avl =  AttendanceDetailPoint::where('attendance_detail_id', $attendanceDetailId)
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
                                        'attendance_detail_id' => $attendanceDetailId,
                                        'point_category_id' => $request->categories[$i + 1][$x],
                                        'point' => $pointCategories[$pos]->point,
                                    ]);
                                }
                            }
                        }
                    }

                    // Manual
                    // if ($request->category) {
                    //     if (array_key_exists($i + 1, $request->category)) {
                    //         for ($x = 0; $x < count($request->category[$i + 1]); $x++) {
                    //             if ($request->category[$i + 1][$x] != null && $request->point_category[$i + 1][$x] != null) {
                    //                 $avl =  AttendanceDetailPoint::where('attendance_detail_id', $dataDetail->id)
                    //                     ->get();
                    //                 foreach ($avl as $key => $value) {
                    //                     AttendanceDetailPoint::where('id', $value->id)->delete();
                    //                 }
                    //                 $attendanceDetailPoint = new AttendanceDetailPoint;
                    //                 $attendanceDetailPoint->attendance_detail_id = $dataDetail->id;
                    //                 $attendanceDetailPoint->point_category = $request->category[$i + 1][$x];
                    //                 $attendanceDetailPoint->point = $request->point_category[$i + 1][$x];
                    //                 $attendanceDetailPoint->save();
                    //             }
                    //         }
                    //     }
                    // }
                }
            } else {
                Attendance::where('id', $request->attendanceId)->delete();
                AttendanceDetail::where('attendance_id', $request->attendanceId)->delete();
            }

            return redirect('/attendance/class')->with('message', 'Schedule student update');
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
        $arrAbsentFilter = [];
        $students = Students::where('status', 'ACTIVE')->get();
        // $students = Students::limit(100)->get();
        $class = Price::get();
        $teachers = Teacher::get();
        foreach ($students as $key => $value) {
            $ttlApha = 0;
            $attendance = AttendanceDetail::join('student as st', 'st.id', 'attendance_details.student_id')
                ->join('price as p', 'p.id', 'st.priceid')
                ->join('attendances as a', 'a.id', 'attendance_details.attendance_id')
                ->join('teacher as t', 't.id', 'a.teacher_id')
                ->select('attendance_details.*', 'st.name', 'p.program', 't.name as teacher', 'a.price_id', 'a.teacher_id', 'st.id_staff', 'a.date')
                ->where('attendance_details.student_id', $value->id);
            if (Auth::guard('teacher')->user() != null) {
                $attendance = $attendance->where('teacher_id', Auth::guard('teacher')->user()->id);
            }
            if (Auth::guard('staff')->user() != null && Auth::guard('staff')->user()->id != 7 && Auth::guard('staff')->user()->id != 1) {
                $attendance = $attendance->where('id_staff', Auth::guard('staff')->user()->id);
            }

            $attendance = $attendance->orderBy('attendance_details.id', 'desc')->limit(2)->get();
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



        foreach ($arrAbsent as $k => $v) {
            if ($request->level != '' && $request->teacher == '') {
                if ($v[0]->price_id == $request->level) {
                    array_push($arrAbsentFilter, $v);
                }
            }
            if ($request->level == '' && $request->teacher != '') {
                if ($v[0]->teacher_id == $request->teacher) {
                    array_push($arrAbsentFilter, $v);
                }
            }
            if ($request->level != '' && $request->teacher != '') {
                if ($v[0]->teacher_id == $request->teacher && $v[0]->price_id == $request->level) {
                    array_push($arrAbsentFilter, $v);
                }
            }
        }

        if ($request->level != '' && $request->teacher == '') {
            $data = $arrAbsentFilter;
        } else if ($request->level == '' && $request->teacher != '') {
            $data = $arrAbsentFilter;
        } else if ($request->level != '' && $request->teacher != '') {
            $data = $arrAbsentFilter;
        } else {
            $data = $arrAbsent;
        }

        // $page = !empty($request->page) ? (int) $request->page : 1;
        // $total = count($data); //total items in array
        // $limit = 10; //per page
        // $totalPages = ceil($total / $limit); //calculate total pages
        // $page = max($page, 1); //get 1 page when $request->page <= 0
        // $page = min($page, $totalPages); //get last page when $request->page > $totalPages
        // $offset = ($page - 1) * $limit;
        // if ($offset < 0) $offset = 0;
        // $data = array_slice($data, $offset, $limit);
        // return view('attendance.reminder', compact('data', 'totalPages'));
        return view('attendance.reminder', compact('data', 'class', 'teachers'));
    }

    public function reminderDone(Request $request)
    {
        try {
            $student = $request->student;
            AttendanceDetail::where('student_id', $student)->limit(2)->orderBy('id', 'desc')->update([
                "is_done" => true
            ]);

            return redirect()->back()->with('message', 'Berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('message', 'Terjadi kesalahan. : ' . $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('message', 'Terjadi kesalahan pada database : ' . $e->getMessage());
        }
    }

    public function reminderAbsen(Request $request)
    {
        try {
            $student = $request->student;
            AttendanceDetail::where('student_id', $student)->limit(2)->orderBy('id', 'desc')->update([
                "is_absent" => '1'
            ]);

            return redirect()->back()->with('message', 'Berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('message', 'Terjadi kesalahan. : ' . $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('message', 'Terjadi kesalahan pada database : ' . $e->getMessage());
        }
    }

    public function mutasi(Request $request)
    {
        $studentId = $request->student;
        $students = Students::where('status', 'ACTIVE')->get();
        $price = Price::get();
        $data = [];
        // $class = Students::join('price', 'price.id', 'student.priceid')
        //     ->select('price.program')
        //     ->where('student.id', $studentId)->first();
        // $query = [];
        // $query = AttendanceDetail::join('attendances as atd', 'atd.id', 'attendance_details.attendance_id')
        //     ->join('student as st', 'st.id', 'attendance_details.student_id')
        //     ->join('price as pr', 'pr.id', 'atd.price_id')
        //     ->select('st.name', 'pr.program', 'atd.id as attendance_id1', 'attendance_details.*', 'atd.date', 'st.id as student_id1', 'pr.id as price_id')
        //     ->where('attendance_details.student_id', $studentId);
        // if ($request->class) {
        //     $query = $query->where('atd.price_id',  $request->class);
        // }
        // $data = $query->orderBy('atd.date', 'DESC')->groupBy('pr.program')->paginate(10);
        if ($request->student && $request->class) {
            $student = Students::find($studentId);
            $data = Mutasi::with('level', 'score')->where('student_id', $student->id)->paginate(10);
        }
        return view('attendance.mutasi', compact('data', 'students', 'price'));
    }

    public function storeMutasi(Request $request)
    {
        try {
            $student = Students::find($request->student);
            $score = StudentScore::where('student_id', $request->student)->where('price_id', $student->priceid)->orderBy('id', 'desc')->first();
            $mutasi = new Mutasi;
            $mutasi->student_id = $request->student;
            $mutasi->price_id = $student->priceid;
            if ($score != null) {
                $mutasi->score_id = $score->id;
            }
            $student->priceid = $request->level;
            $student->save();
            $mutasi->save();
            return redirect('mutasi?student=' . $student->id . '&class=' . $student->priceid)->with('message', 'Berhasil dimutasi');
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Terjadi kesalahan. : ' . $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('status', 'Terjadi kesalahan pada database : ' . $e->getMessage());
        }
        return $score != null ? 'asd' : 'ccd';
    }

    public function getClass(Request $request)
    {
        $level = DB::table('price');
        if ($request->level == 'priv') {
            $level = $level->where('level', 'Private');
        } else {
            $level = $level->where('level', '!=', 'Private');
        }
        return $level->get();
    }

    public function updateClass(Request $request)
    {
        try {
            $reqDay1 = $request->update_day1;
            $reqDay2 = $request->update_day2;
            $reqTime = $request->update_time;
            $priceId = $request->update_class;
            $student = DB::table('student')->where('priceid', $priceId)->where("day1", $reqDay1)
                ->where("day2", $reqDay2)
                ->where('course_time', $reqTime)->get();
            foreach ($student as $key => $value) {
                if ($value->priceid != $request->update_level) {
                    $score = StudentScore::where('student_id', $request->student)->where('price_id', $value->priceid)->orderBy('id', 'desc')->first();
                    $mutasi = new Mutasi;
                    $mutasi->student_id = $value->id;
                    $mutasi->price_id = $priceId;
                    if ($score != null) {
                        $mutasi->score_id = $score->id;
                    }
                    $mutasi->save();
                }
            }
            DB::table('student')->where('priceid', $priceId)->where("day1", $reqDay1)
                ->where("day2", $reqDay2)
                ->where('course_time', $reqTime)->update([
                    "day1" => $request->update_day_one,
                    "day2" => $request->update_day_two,
                    "course_time" => $request->update_course_time,
                    "priceid" => $request->update_level,
                    "id_teacher" => $request->update_teacher,
                ]);

            return redirect()->back()->with('message', 'Berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('message', 'Terjadi kesalahan. : ' . $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('message', 'Terjadi kesalahan pada database : ' . $e->getMessage());
        }
    }

    public function addComment($id, Request $request)
    {
        try {
            $model = AttendanceDetail::find($id);
            if ($request->type == 'teacher') {
                $model->comment_teacher = $request->comment;
            } else {
                $model->comment_staff = $request->comment;
            }
            $model->save();
            return redirect()->back()->with('message', 'Berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('message', 'Terjadi kesalahan. : ' . $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('message', 'Terjadi kesalahan pada database : ' . $e->getMessage());
        }
    }


    function ajaxStudent(Request $request)
    {
        $student = Students::where('status', 'ACTIVE')->where('priceid', $request->class)
            ->where("day1", $request->day1)
            ->where("day2", $request->day2)
            ->where('course_time', $request->time)
            ->where('id_teacher', $request->teacher)->get();
        return $student;
    }
}
