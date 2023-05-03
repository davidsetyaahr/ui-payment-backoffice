<?php

namespace App\Http\Controllers;

use App\Models\ParentStudents;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ParentStudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\ParentStudents  $parentStudents
     * @return \Illuminate\Http\Response
     */
    public function show(ParentStudents $parentStudents)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ParentStudents  $parentStudents
     * @return \Illuminate\Http\Response
     */
    public function edit(ParentStudents $parentStudents)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ParentStudents  $parentStudents
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ParentStudents $parentStudents)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ParentStudents  $parentStudents
     * @return \Illuminate\Http\Response
     */
    public function destroy(ParentStudents $parentStudents)
    {
        //
    }

    public function student($id)
    {
        // $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
        $generator = new \Picqer\Barcode\BarcodeGeneratorJPG();
        // Storage::putFileAs('barcode', $generator->getBarcode('081231723897', $generator::TYPE_CODABAR), Hash::make('asd') . 'jpg');
        // file_put_contents('\public\barcode\barcode.jpg', $generator->getBarcode('081231723897', $generator::TYPE_CODABAR));
        // \Storage::disk('public')->put('test.png', base64_decode(DNS2D::getBarcodePNG("4", "PDF417")));
        $student = Students::find($id);
        File::put('storage/test.jpg', $generator->getBarcode($student->id, $generator::TYPE_CODE_128));
        return view('parents.barcode', compact('student'));
    }
}
