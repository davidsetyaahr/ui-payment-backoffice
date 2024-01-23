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
            $class = DB::select("SELECT DISTINCT priceid,day1,day2,course_time,id_teacher,price.level,price.program,day_1.day day_one,day_2.day day_two,teacher.name teacher_name, student.id_teacher as teacher_id, student.day1 as d1, student.day2 as d2  from student join price on student.priceid = price.id join day day_1 on student.day1 = day_1.id join day day_2 on student.day2 = day_2.id join teacher on student.id_teacher = teacher.id  WHERE day1 is NOT null AND day2 is NOT null AND course_time is NOT null AND id_teacher is NOT null $where ORDER BY priceid ASC, day1,course_time;");
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
                $student->date_certificate = Carbon::now()->format('Y-m-d');
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
        // ->where('is_certificate', '!=', true)
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
                $student->date_certificate = Carbon::now()->format('Y-m-d');
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
