<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Score;
use App\Models\StudentScore;
use App\Models\StudentScoreDetail;
use App\Models\Tests;
use Helper;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    public function getTest()
    {
        try {
            $test = Tests::all();
            return response()->json([
                'code' => '00',
                'payload' => $test,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '400',
                'error' => 'internal server error',
                'message' => $th,
            ], 403);
        }
    }

    public function getScoreByTest($studentId, $testId)
    {
        try {
            $score = StudentScore::join('tests as t', 't.id', 'student_scores.test_id')
                ->select('t.name', 'student_scores.average_score', 'student_scores.average_score', 'student_scores.id as scoreId', 'student_scores.comment')
                ->where('student_scores.test_id', $testId)
                ->where('student_scores.student_id', $studentId)
                ->first();
            $item = StudentScoreDetail::join('test_items as ti', 'ti.id', 'student_score_details.test_item_id')
                ->select('ti.name', 'student_score_details.*')
                ->where('student_score_details.student_score_id', $score->scoreId)
                ->get();
            $score['grade'] = Helper::getGrade($score->average_score);
            $items = [];
            foreach ($item as $value) {
               $value['grade'] = Helper::getGrade($value->score);
               array_push($items, $value);
            }
            $data = ([
                'score' => $score,
                'scoreItems' => $items,
            ]);
            return response()->json([
                'code' => '00',
                'payload' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '400',
                'error' => 'internal server error',
                'message' => $th,
            ], 403);
        }
    }
}