<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $day = DB::table('day')->get();
        $class = DB::table('price')->get();
        $data = '';
        if ($request->class) {
            $data = DB::table('student')->where('priceid', $request->class)->get();
        }
        return view('schedule-class.index', compact('day', 'class', 'data'));
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
        // return $request->all();
        try {
            DB::transaction(function () use ($request) {
                foreach ($request->upcls as $key => $value) {
                    // DB::table('student')
                    //     ->where('id', $value)
                    //     ->update(['day1' => null]);
                    DB::table('student')
                        ->where('id', $value)
                        ->update(['day1' => $request->day]);
                }
            });
            return redirect()->back()->with('message', 'Schedule student update');
        } catch (\Exception $e) {
            return redirect()->back()->with('message', 'Terjadi kesalahan. : ' . $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('message', 'Terjadi kesalahan pada database : ' . $e->getMessage());
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
