<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\Score;
use App\Models\Students;
use App\Models\StudentScore;
use App\Models\StudentScoreDetail;
use App\Models\TestItems;
use App\Models\Tests as ModelsTests;
use Illuminate\Http\Request;
use PHPUnit\Framework\Test;
use SebastianBergmann\CodeCoverage\Report\Xml\Tests;

use function PHPSTORM_META\type;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $students = Students::join('price as p', 'p.id', 'student.priceid')
                ->select('student.name', 'student.id')
                ->where('p.program', '!=', 'Private')
                ->get();
            $test = ModelsTests::all();
            $item = TestItems::orderBy('id', 'ASC')->get();
            $class = Price::all();
            $title = 'Input Score';
            $data = (object)[
                'type' => 'create',
            ];
            return view('score.form', compact('data', 'title', 'test', 'item', 'students', 'class'));
        } catch (\Throwable $th) {
            //throw $th;
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

        try {
            $scores = StudentScore::create([
                'test_id' => $request->test,
                'student_id' => $request->student,
                'average_score' => round($request->total),
                'comment' => $request->comment ?? '-',
                'date' => $request->date,
            ]);
            for ($i = 0; $i < count($request->items); $i++) {
                StudentScoreDetail::create([
                    'student_score_id' => $scores->id,
                    'test_item_id' => $request->items[$i],
                    'score' => $request->score[$i],
                ]);
            }
            return redirect('/score/form')->with('success', 'Success add Score');
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to save data');
            return $th;

            //throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function show(Score $score)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function edit(Score $score)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $score)
    {
        // return $request;
        try {
            StudentScore::where('id', $score)->update([
                'average_score' => round($request->total),
                'comment' => $request->comment ?? '-'
            ]);
            for ($i = 0; $i < count($request->items); $i++) {
                StudentScoreDetail::where('id',  $request->idScore[$i])
                    ->update([
                        'score' => $request->score[$i],
                    ]);
            }
            return redirect('/score/form')->with('success', 'Success add Score');
        } catch (\Throwable $th) {
            return $th;
            return back()->with('error', 'Failed to update data');
        }
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function destroy(Score $score)
    {
        //
    }

    public function filter(Request $request)
    {
        try {
            $data = [];
            $detail = [];
            $query = StudentScore::where('test_id', $request->test)
                ->where('student_id', $request->student)
                ->where('date', $request->date)
                ->first();

            if ($query) {
                $detail = StudentScoreDetail::where('student_score_id', $query->id)->get();
                // return $detail;
            } else {
                $detail = [];
            }
            $data  = ([
                'data' => $query,
                'detail' => $detail,
            ]);
            return $data;
        } catch (\Throwable $th) {
            return $th;
            //throw $th;
        }
        return $request;
    }

    public function filterStudent(Request $request)
    {

        try {
            $query = [];
            $query = Students::where('priceid', $request->class)->get();
            return $query;

            // return $data;
        } catch (\Throwable $th) {
            //throw $th;
            return $th;
        }
    }
}
