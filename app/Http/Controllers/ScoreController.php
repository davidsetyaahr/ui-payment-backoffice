<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\Students;
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
            $item = TestItems::all();
            $title = 'Input Score';
            $data = (object)[
                'type' => 'create',
            ];
            return view('score.form', compact('data', 'title', 'test', 'item', 'students'));
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
        //
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
    public function update(Request $request, Score $score)
    {
        //
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
}
