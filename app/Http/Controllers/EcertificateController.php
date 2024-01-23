<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\Students;
use App\Models\TestItems;
use App\Models\Tests;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EcertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $where = '';
            $level = Price::get();
            if (Auth::guard('teacher')->check() == true) {
                $where = 'AND id_teacher = ' . Auth::guard('teacher')->user()->id;
            }
            if ($request->level && Auth::guard('staff')->check() == true) {
                $where = $where . ' AND priceid = ' . $request->level;
            }
            if ($request->level && Auth::guard('teacher')->check() == true) {
                $where = $where . ' AND priceid = ' . $request->level . ' AND id_teacher =' . Auth::guard('teacher')->user()->id;
            }
            $class = DB::select("SELECT
    priceid,
    day1,
    day2,
    course_time,
    id_teacher,
    price.level,
    price.program,
    day_1.day AS day_one,
    day_2.day AS day_two,
    teacher.name AS teacher_name,
    student.id_teacher AS teacher_id,
    student.day1 AS d1,
    student.day2 AS d2
FROM
    student
JOIN
    price ON student.priceid = price.id
JOIN
    student_scores AS ss ON ss.student_id = student.id
JOIN
    day day_1 ON student.day1 = day_1.id
JOIN
    day day_2 ON student.day2 = day_2.id
JOIN
    teacher ON student.id_teacher = teacher.id
WHERE
    day1 IS NOT NULL
    AND day2 IS NOT NULL
    AND course_time IS NOT NULL
    AND id_teacher IS NOT NULL
    AND ss.test_id = 3
    AND ss.price_id = price.id
    $where
GROUP BY
    priceid, day1, day2, course_time, id_teacher, price.level, price.program, day_one, day_two, teacher_name, teacher_id, d1, d2
HAVING
    COUNT(DISTINCT CASE WHEN ss.test_id = 3 THEN ss.student_id END) > 0
ORDER BY
    priceid ASC, day1, course_time;");
            // return count($class);
            return view('e-certificate.index', compact('class', 'level'));
        } catch (\Throwable $th) {
            // throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->student as $key => $value) {
                $student = Students::find($value);
                $student->is_certificate = true;
                $student->date_certificate = $request->date_certificate;
                $student->save();
                DB::commit();
            }
            return redirect('/e-certificate')->with('message', 'Berhasil mengupdate');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $students = Students::with('score')->where('priceid', $id)->where('day1', $request->day1)->where('day2', $request->day2)->where('id_teacher', $request->teacher)->where('course_time', $request->time)
            // ->where('is_certificate', false)
            // ->where(function ($query) {
            //     $query->orWhere('is_follow_up', true);
            // })
            ->get();
        $class = Price::find($id);
        $test = Tests::get();
        if ($id == 1 || $id == 2 || $id == 3 || $id == 4 || $id == 5 || $id == 6) {
            $testItem = TestItems::where('id', '!=', 5)->where('id', '!=', 6)->get();
        } else {
            $testItem = TestItems::get();
        }
        return view('e-certificate.form', compact('students', 'class', 'testItem', 'test'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            if ($request->type == 'done') {
                $student = Students::find($id);
                $student->is_certificate = true;
                $student->date_certificate = $request->date_certificate;
                $student->save();
                DB::commit();
            }
            return redirect('/e-certificate')->with('message', 'Berhasil mengupdate');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
