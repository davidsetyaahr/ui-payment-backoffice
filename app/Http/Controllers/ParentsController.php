<?php

namespace App\Http\Controllers;

use App\Models\Parents;
use App\Models\ParentStudents;
use App\Models\Students;
use Illuminate\Http\Request;

class ParentsController extends Controller
{
    public function index()
    {
        $data = Parents::all();
        return view('parents.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Add Parents';
        $data = (object)[
            'id' => 0,
            'name' => '',
            'no_hp' => '',
            'gender' => '',
            'type' => 'create',
        ];
        return view('parents.form', compact('data', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'no_hp' => 'required',
        ]);


        $input['name'] = $request['name'];
        $input['no_hp'] = $request['no_hp'];
        $input['gender'] = $request['gender'];
        $input['otp'] = '1111';

        try {
            Parents::create($input);
            return redirect('/parents')->with('status', 'Berhasil menambah data');
        } catch (\Throwable $th) {
            return $th;
            return back('/parents/create')->with('status', 'Ggagal menambah data');
        }
    }

    public function storeParentStudents(Request $request)
    {
        try {
            foreach ($request->student as $key => $value) {
                ParentStudents::create([
                    'parent_id' => $request->parent_id,
                    'student_id' => $value,
                ]);
            }
            return redirect('/parents/'.$request->parent_id)->with('status', 'Berhasil menambah data');
        } catch (\Throwable $th) {
            return back()->with('status', 'Gagal menambah data');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Parents  $test
     * @return \Illuminate\Http\Response
     */
    public function show(Parents $parent)
    {
        $data =  $parent;
        $dataStudent = Students::all();
        $students = ParentStudents::join('student', 'student.id', 'parent_students.student_id')
            ->select('student.*')
            ->where('parent_students.parent_id', $parent->id)
            ->get();

        return view('parents.detail', compact('data', 'students', 'dataStudent'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Parents  $parent
     * @return \Illuminate\Http\Response
     */
    public function edit(Parents $parent)
    {
        $title = 'Edit Parents';
        $data = (object)[
            'id' => $parent->id,
            'name' =>  $parent->name,
            'no_hp' =>  $parent->no_hp,
            'gender' =>  $parent->gender,
            'type' => 'edit',
        ];
        return view('parents.form', compact('data', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Parents  $parent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Parents $parent)
    {

        $request->validate([
            'name' => 'required',
            'no_hp' => 'required',

        ]);
        $input['name'] = $request['name'];
        $input['no_hp'] = $request['no_hp'];
        $input['gender'] = $request['gender'];


        try {
            Parents::where('id', $parent->id)->update($input);
            return redirect('/parents')->with('status', 'Berhasil mengubah data');
        } catch (\Throwable $th) {
            return $th;
            return back('/parents/create')->with('status', 'Ggagal mengubah data');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Parents  $parent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Parents $parent)
    {
        try {
            Parents::where('id', $parent->id)->delete();
            return redirect('/parents')->with('status', 'Berhasil menghapus data');
        } catch (\Throwable $th) {
            return $th;
            return redirect('/parents')->with('error', 'Gagal menghapus data');
        }
    }
}
