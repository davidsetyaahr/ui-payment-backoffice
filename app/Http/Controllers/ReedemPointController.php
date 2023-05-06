<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\AttendanceDetailPoint;
use App\Models\PointCategories;
use App\Models\PointHistory;
use App\Models\PointHistoryCategory;
use App\Models\Price;
use App\Models\ReedemItems;
use App\Models\ReedemPoint;
use App\Models\Students;
use App\Models\Teacher;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReedemPointController extends Controller
{
    public function create()
    {
        try {
            $class = Price::all();
            // $students = Students::join('price as p', 'p.id', 'student.priceid')
            //     ->select('student.name', 'student.id', 'student.total_point')
            //     ->get();
            $students = Students::where('status', 'ACTIVE')->get();
            $item = ReedemItems::all();
            $title = 'Reedem Point';
            return view('reedemPoint.form', compact('students', 'title', 'item', 'class'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function store(Request $request)
    {
        try {

            $student = $request->student == null && $request->id_student != null ? $request->id_student : $request->student;
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
            return redirect('/reedemPoint')->with('status', 'Success Reedem Point');
            // for ($i = 0; $i < count($request->item); $i++) {
            //     $items = ReedemItems::where('id', $request->item[$i])->first();
            //     $subTotal = intval($items->point) * intval($request->qty[$i]);
            //     $tmpTotal += $subTotal;
            // }
            // if ($request->point < $tmpTotal) {
            //     return back()->with('error', 'Point tidak cukup');
            // } else {
            //     for ($i = 0; $i < count($request->item); $i++) {
            //         $items = ReedemItems::where('id', $request->item[$i])->first();
            //         $subTotal = intval($items->point) * intval($request->qty[$i]);
            //         ReedemPoint::create([
            //             'item_id' => $request->item[$i],
            //             'point' => intval($items->point),
            //             'student_id' => $student,
            //             'qty' => $request->qty[$i],
            //         ]);
            //         PointHistory::create([
            //             'student_id' => $student,
            //             'date' => date('Y-m-d'),
            //             'total_point' => intval($items->point),
            //             'type' => 'redeem',
            //             'keterangan' => $items->item,
            //         ]);
            //         // PointHistoryCategory::create([
            //         //     'point_history_id' => $history->id,
            //         //     'point_category_id' =>  $request->item[$i],
            //         // ]);
            //     }
            //     $newPoint = intval($request->point) - $subTotal;
            //     Students::where('id', $student)
            //         ->update([
            //             'total_point' =>  $newPoint,
            //         ]);
            //     return redirect('/reedemPoint')->with('status', 'Success Reedem Point');
            // }
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

    public function saldoAwal(Request $request)
    {
        $where = '';
        $teachers = Teacher::get();
        if (Auth::guard('teacher')->check() == true) {
            $where = 'AND id_teacher = ' . Auth::guard('teacher')->user()->id;
        }

        if (Auth::guard('staff')->check() == true && Auth::guard('staff')->user()->id != 7) {
            $where = 'AND id_staff = ' . Auth::guard('staff')->user()->id;
        }
        $class = DB::select("SELECT DISTINCT priceid,day1,day2,course_time,id_teacher,price.level,price.program,day_1.day day_one,day_2.day day_two,teacher.name teacher_name from student join price on student.priceid = price.id join day day_1 on student.day1 = day_1.id join day day_2 on student.day2 = day_2.id join teacher on student.id_teacher = teacher.id  WHERE day1 is NOT null AND day2 is NOT null AND course_time is NOT null AND id_teacher is NOT null $where;");
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
        return view('reedemPoint.saldo-awal', compact('private', 'general', 'day', 'teachers'));
    }

    public function createSaldoAwal($priceId, Request $request)
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
            ->orderBy('id', 'DESC')
            ->first();

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
            }
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
            ->where('course_time', $reqTime);
        if (Auth::guard('teacher')->check() == true) {
            $student = $student->where('id_teacher', Auth::guard('teacher')->user()->id);
        } else {
            $student = $student->where('id_teacher', $reqTeacher);
        }

        $student =   $student->get();


        $pointCategories = PointCategories::all();
        // return $student;
        return view('reedemPoint.form-saldo-awal', compact('title', 'data', 'student', 'pointCategories', 'day'));
    }

    public function storeSaldoAwal(Request $request)
    {
        try {
            foreach ($request->studentId as $key => $value) {
                $student = Students::find($value);
                $student->total_point = $request->saldo_awal[$key];
                $student->save();
            }
            return redirect()->back()->withStatus('Data berhasil diperbarui ');
        } catch (Exception $e) {
            //throw $th;
            ddd($e);
        } catch (QueryException $e) {
            ddd($e);
        }
    }
}
