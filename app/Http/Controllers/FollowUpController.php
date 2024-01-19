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
        return 'asd';
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
            return 'Success Follow Up Student';
        } catch (\Exception $e) {
            DB::rollback();
            return 'Terjadi kesalahan. : ' . $e->getMessage();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return 'Terjadi kesalahan pada database : ' . $e->getMessage();
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
        //
    }
}
