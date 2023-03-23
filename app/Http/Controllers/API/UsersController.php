<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Parents;
use App\Models\ParentStudents;
use App\Models\StudentScore;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UsersController extends Controller
{

    public function getOtp(Request $request)
    {
        try {
            $data = Parents::where('no_hp', $request->phone)->first();
            if ($data) {
                $otp = substr(str_shuffle("0123456789"), 0, 4);

                $generate = Parents::where('no_hp', $request->phone)->update(['otp' => $otp, 'password' => bcrypt($otp)]);
                $message = 'Your verification code is: ' . $otp;

                $sendOTP =  Helper::sendMessage($request->phone, $message);
                if ($generate) {
                    return response()->json([
                        'code' => '00',
                        'message' => $message,
                    ], 200);
                } else {
                    return response()->json([
                        'code' => '10',
                        'message' => $sendOTP['msg'],
                    ], 200);
                }
            } else {
                return response()->json([
                    'code' => '10',
                    'message' => 'Nomor HP Salah',
                ], 200);
            }
        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                'code' => '400',
                'error' => 'internal server error', 'message' => $th,
            ], 403);
        }
    }

    public function authenticate(Request $request)
    {
        $credentials = ([
            'no_hp' => $request->phone,
            'password' => $request->otp,
        ]);

        // return $credentials;
        $data = Parents::where('no_hp', $request->phone)
            ->where('otp', $request->otp)
            ->first();

        if ($data) {
            if ($token = JWTAuth::attempt($credentials)) {
                // return $this->respondWithToken($token, 'parent');
                return response()->json([
                    'code' => '00',
                    'data' => $data,
                    'token' => $this->respondWithToken($token),
                ]);
            }
        } else {
            return response()->json([
                'code' => '10',
                'message' => 'Login credentials are invalid.',
            ], 400);
        }

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'code' => '10',
                    'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
            return $credentials;
            return response()->json([
                'code' => '00',
                'message' => 'Could not create token.',
            ], 500);
        }


    }

    public function submitOtp(Request $request)
    {
        // return $request->header('Authorization');
        try {
            $data = Parents::where('no_hp', $request->phone)
                ->where('otp', $request->otp)
                ->first();
            if ($data) {
                $token = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 32);
                $result = ([
                    'token' => $token,
                    'data' => $data,
                ]);
                return response()->json([
                    'code' => '00',
                    'payload' => $result,
                ], 200);
            } else {
                return response()->json([
                    'code' => '10',
                    'message' => 'Nomor HP Salah',
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '400',
                'error' => 'internal server error',
                'message' => $th,
            ], 403);
        }
    }

    public function students($parentId)
    {
        try {
            $students = [];
            $data = ParentStudents::join('student', 'student.id', 'parent_students.student_id')
                ->select('student.*')
                ->where('parent_students.parent_id', $parentId)
                ->get();
            foreach ($data as $key => $val) {
                $score = StudentScore::where('student_id', $val->id)
                    ->select('average_score')
                    ->orderBy('id', 'DESC')
                    ->first();
                if ($score) {
                    $student = array_merge($val->toArray(), $score->toArray());
                    array_push($students, $student);
                } else {
                    $student = array_merge($val->toArray(), (['average_score' => 0]));
                    array_push($students, $student);
                }
            }
            return response()->json([
                'code' => '00',
                'payload' => $students,
            ], 200);
        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                'code' => '400',
                'error' => 'internal server error',
                'message' => $th,
            ], 403);
        }
    }

    protected function respondWithToken($token)
    {
        return ([
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }
}
