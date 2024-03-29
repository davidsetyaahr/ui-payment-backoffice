<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Price;
use App\Models\Score;
use App\Models\Students;
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

    public function getResult($studentId)
    {
        try {
            $class = Students::join('price', 'price.id', 'student.priceid')
                ->select('price.program')
                ->where('student.id', $studentId)->first();
            $sc = StudentScore::where('student_id', $studentId)->where('price_id', $class->priceid)->get();
            if (count($sc) != 0) {
                $test = Tests::count();
                $totalScore = 0;
                $totalTest = 0;
                foreach ($sc as $s) {
                    $totalScore += $s->average_score;
                    $totalTest += 1;
                }
                $total = $totalScore / $totalTest;
                $data = ([
                    'total_score' => $total,
                    'class' => $class->program,
                    'grade' => Helper::getGrade($total),
                    'total_test' => $test,
                    'total_test_passed' => $totalTest,
                ]);
            } else {
                $data = ([
                    'total_score' => 0,
                    'class' => $class->program,
                    'grade' => Helper::getGrade(0),
                    'total_test' => 0,
                    'total_test_passed' => 0,
                ]);
            }
            return response()->json([
                'code' => '00',
                'payload' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '400',
                'error' => 'internal server error ' . $th,
                'message' => $th,
            ], 403);
        }
    }

    public function getScoreByTest($studentId, $testId, Request $request)
    {
        try {
            $getStudent = Students::find($studentId);
            $score = StudentScore::join('tests as t', 't.id', 'student_scores.test_id')
                ->select('t.name', 'student_scores.average_score', 'student_scores.average_score', 'student_scores.id as scoreId', 'student_scores.comment')
                ->where('student_scores.test_id', $testId);
            if ($request->class) {
                $score = $score->where('student_scores.price_id', $request->class);
            } else {
                $score = $score->where('student_scores.price_id', $getStudent->priceid);
            }

            $score = $score->where('student_scores.student_id', $studentId)
                ->first();
            if ($score != null) {
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
            } else {
                $data = ([
                    'score' => [],
                    'scoreItems' => [],
                ]);
            };

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
