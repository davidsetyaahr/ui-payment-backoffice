<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FollowUpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = FollowUp::with('class', 'teacher', 'student')->select('follow_up.*', 'd1.day as day1', 'd2.day as day2')
            ->join('day as d1', 'd1.id', 'follow_up.old_day_1')
            ->join('day as d2', 'd2.id', 'follow_up.old_day_2')
            ->get();
        return view('follow-up.index', compact('students'));
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
            // Insert data in follow up table
            foreach ($request->id as $key => $value) {
                $cekFollowUp = FollowUp::where('student_id', $value)->first();
                if (!$cekFollowUp) {
                    $followUp = new FollowUp();
                    $followUp->student_id = $value;
                    $followUp->old_price_id = $request->old_class;
                    $followUp->old_day_1 = $request->old_day1;
                    $followUp->old_day_2 = $request->old_day2;
                    $followUp->old_teacher_id = $request->old_teacher;
                    $followUp->course_time = $request->old_time;
                    $followUp->save();
                }
            }

            DB::commit();
            return redirect()->back()->with('status', 'Success bulk update');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed bulk update ' . $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed bulk update ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            FollowUp::where('id', $id)->delete();
            DB::commit();
            return redirect('/follow-up')->with('status', 'Success update data');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/follow-up')->with('error', 'Failed update data ' . $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return redirect('/follow-up')->with('error', 'Failed update database ' . $e->getMessage());
        }
    }

    public function bulkDestroy(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->student_id as $key => $value) {
                FollowUp::where('id', $value)->delete();
            }
            DB::commit();
            return redirect('/follow-up')->with('status', 'Success bulk update');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/follow-up')->with('error', 'Failed bulk update ' . $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return redirect('/follow-up')->with('error', 'Failed bulk update ' . $e->getMessage());
        }
    }
}
