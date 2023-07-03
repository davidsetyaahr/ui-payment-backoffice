<?php

namespace App\Http\Controllers;

use App\Models\OrderReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewTestPaperController extends Controller
{
    public function index()
    {
        $data = OrderReview::with('teacher')->groupBy('id_attendance')->get();
        return view('order-review.index', compact('data'));
    }

    public function done($id)
    {
        DB::beginTransaction();
        try {
            OrderReview::where('id_attendance', $id)->update([
                'is_done' => true,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
        return redirect('/review')->with('status', 'Berhasil mengupdate');
    }
}
